<?php

namespace App\Modules\DataImport\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\AttendenceImportInsert;
use App\Modules\DataImport\Models\BatchTable;
use App\Modules\DataImport\Models\ImprAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class DataImportController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function attendanceDataImportIndex()
    {
        $batchData = BatchTable::where('type', 'attendance')
            ->orderBy('created_at', 'desc')
            ->get();
        $headingText = 'Attendance Data Sheet Import';
        $t = time();
        $route = 'attendance.data.insert.show';
        $formRoute = 'attendance.data.insert.import';
        return view('DataImport::attendanceImport.create', compact('batchData', 'headingText', 'route', 'formRoute'));
    }

    // Temporary import
    public function attendanceDataInsertImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required',
        ]);

        $file = $request->file('excel_file');
        $extension = $file->getClientOriginalExtension();
        if ($extension == 'xls' || $extension == 'xlsx' || $extension == 'csv') {
            $data = Excel::toArray(null, $file)[0];
            // Remove the first row (header)
            $header = array_shift($data);

            if (empty($data)) {
                Session::flash('danger', "Your excel file is empty");
                return redirect()->back();
            } else {
                // Add the header row back to the data array
                array_unshift($data, $header);

                DB::beginTransaction();
                try {
                    $file = $request->file('excel_file');
                    $fileName = date('Ymd_His') . '_' . $file->getClientOriginalName();
                    $fileSavePath = public_path('importfile/attendance/');
                    $file->move($fileSavePath, $fileName);
                    // ini_set('memory_limit', '-1');
                    Excel::import(new AttendenceImportInsert(), public_path('importfile/attendance/' . $fileName));

                    $getLastBatchId = BatchTable::all()->last()->id;
                    BatchTable::where('id', $getLastBatchId)->update(['name' => $fileName]);
                    ImprAttendance::where('batch_id', null)->update(['batch_id' => $getLastBatchId, 'created_by' => Auth::user()->id]);
                    ImprAttendance::where('batch_id', $getLastBatchId)->where('name', null)->delete();
                    DB::commit();
                    // return redirect('admin-data-insert-show/' . $getLastBatchId);
                    Session::flash('success', "Excel export successfully");
                    return redirect()->back();

                } catch (Exception $ex) {
                    DB::rollback();
                    Session::flash('danger', $ex->getMessage());
                    return redirect()->back();
                }
            }
        } else {
            Session::flash('danger', "Please insert an Excel file");
            return redirect()->back();
        }

    }

    // to show attendance data import
    public function attendanceDataInsertShow($id)
    {
        $batchId = $id;
        $data = DB::select('call getAttendance(' . $batchId . ')');
        return view('DataImport::attendanceImport.show', compact('data', 'batchId'));
    }

    // To update attendance data import
    public function attendanceDataInsertUpdate(Request $request)
    {
        if (empty($request->input_value) && ($request->field_name == 'name' || $request->field_name == 'punch_date' || $request->field_name == 'card_no' || $request->field_name == 'in_time')) {
            $isValidate = '0';
            $errorMessage = 'The ' . $request->field_name . ' is required.';
        } else {
            $isValidate = '1';
            $errorMessage = null;
        }
        ImprAttendance::where('id', $request->name_id)->update([
            $request->field_name => $request->input_value,
            'is_validate' => $isValidate,
            'error_message' => $errorMessage,
            'updated_by' => Auth::user()->id,
        ]);
        return response()->json(['success' => true], 200);
    }

    // Transfer data into main table
    public function attendanceDataInsertImportData($id)
    {
        $batchId = $id;
        // Fetch all data from import attendence table
        $data = DB::table('impr_attendance_table')
            ->join('contacts', 'impr_attendance_table.card_no', 'contacts.fingerprint_card_serial_no')
            ->select('impr_attendance_table.id', 'impr_attendance_table.name', 'impr_attendance_table.punch_date', 'impr_attendance_table.card_no', 'impr_attendance_table.in_gate_name', 'impr_attendance_table.in_time', 'impr_attendance_table.out_gate_name', 'impr_attendance_table.out_time', 'impr_attendance_table.status', 'impr_attendance_table.type', 'impr_attendance_table.batch_id', 'impr_attendance_table.is_validate', 'impr_attendance_table.error_message', 'contacts.id as contact_id','contacts.type as contact_type')
            ->where('batch_id', $batchId)
            ->orderBy('impr_attendance_table.id', 'asc')
            ->get();

        // Validation Data
        $errorMessage = '';
        $errorCount = 0;
        $slCheck = 0;
        $loopCount = 1;
        $slNo = '';
        if (!empty($data)) {
            foreach ($data as $index => $value) {
                if (empty($value->name)) {
                    $errorCount++;
                    $slNo .= ($index + 1) . ',';
                    if (strpos($errorMessage, 'Name is required.') === false) {
                        $errorMessage .= 'Name is required.';
                    }
                    DB::table('impr_attendance_table')->where('id', $value->id)->update(['is_validate' => $value->is_validate == '1' ? '0' : '0', 'error_message' => $errorMessage]);
                }

                if (empty($value->punch_date)) {
                    $errorCount++;
                    $slNo .= ($index + 1) . ',';
                    if (strpos($errorMessage, 'Punch Date is required.') === false) {
                        $errorMessage .= 'Punch Date is required.';
                    }
                    DB::table('impr_attendance_table')->where('id', $value->id)->update(['is_validate' => $value->is_validate == '1' ? '0' : '0', 'error_message' => $errorMessage]);
                }

                if (empty($value->card_no)) {
                    $errorCount++;
                    $slNo .= ($index + 1) . ',';
                    if (strpos($errorMessage, 'Card No is required.') === false) {
                        $errorMessage .= 'Card No is required.';
                    }
                    DB::table('impr_attendance_table')->where('id', $value->id)->update(['is_validate' => $value->is_validate == '1' ? '0' : '0', 'error_message' => $errorMessage]);
                }
            }

            if ($errorCount > 0) {
                Session::flash('danger', 'In SL No.' . rtrim($slNo, ',') . ' some error found. Please fix it and import again');
                return redirect('attendance-data-insert-show/' . $batchId);
            }
        }

        $processedData = [];

        foreach ($data as $attendance) {
            $date = $attendance->punch_date;
            $cardNo = $attendance->card_no;

            // Check if the entry for the given date and card number already exists in the processedData array
            if (isset($processedData[$date][$cardNo])) {
                // If the entry already exists, update the last data with the current one
                $processedData[$date][$cardNo]['last'] = $attendance;
            } else {
                // If the entry doesn't exist, initialize both first and last data with the current one
                $processedData[$date][$cardNo] = [
                    'first' => $attendance,
                    'last' => $attendance,
                ];
            }
        }

        // Extract the first data and last data of each day for each card number
        $firstDataOfEachDay = [];
        $lastDataOfEachDay = [];

        foreach ($processedData as $date => $cardData) {
            foreach ($cardData as $cardNo => $attendanceData) {
                $firstDataOfEachDay[] = $attendanceData['first'];
                $lastDataOfEachDay[] = $attendanceData['last'];
            }
        }

        // to hold in time data & out time data from these two ($firstDataOfEachDay, $lastDataOfEachDay)
        $fileterableData = [];
        foreach ($firstDataOfEachDay as $index => $firstData) {
            $fileterableData[$index]['id'] = $firstData->id;
            $fileterableData[$index]['punch_date'] = $firstData->punch_date;
            $fileterableData[$index]['card_no'] = $firstData->card_no;
            $fileterableData[$index]['in_gate_name'] = $firstData->in_gate_name;
            $fileterableData[$index]['in_time'] = $firstData->in_time;
            $fileterableData[$index]['out_gate_name'] = $firstData->out_gate_name;
            $fileterableData[$index]['out_time'] = $firstData->out_time;
            $fileterableData[$index]['type'] = $firstData->type;
            $fileterableData[$index]['contact_id'] = $firstData->contact_id;
            if($firstData->contact_type == 1){
                $fileterableData[$index]['contact_type'] = 'student';
            }else{
                $fileterableData[$index]['contact_type'] = 'stuff';
            }
            $fileterableData[$index]['created_at'] = $firstData->contact_id;
            $fileterableData[$index]['created_by'] = Auth::id();
        }

        foreach ($lastDataOfEachDay as $index => $lastData) {
            if (isset($lastData->out_time)) {
                $fileterableData[$index]['out_gate_name'] = $lastData->out_gate_name;
                $fileterableData[$index]['out_time'] = $lastData->out_time;
            } else {
                $fileterableData[$index]['in_gate_name'] = $lastData->in_gate_name;
                $fileterableData[$index]['in_time'] = $lastData->in_time;
            }
        }

        $totalRow = count($fileterableData);
        $limitPerTime = 20;
        $loopCount = ceil($totalRow / $limitPerTime);
        DB::beginTransaction();
        try {
            for ($i = 0; $i < $loopCount; $i++) {
                $offset = $i * $limitPerTime;
                $batchData = array_slice($fileterableData, $offset, $limitPerTime);

                foreach ($batchData as $data) {
                    $cardNo = $data['card_no'];
                    $punchDate = $data['punch_date'];

                    // Check if the entry for the given card number and punch date already exists
                    $existingData = DB::table('attendance')
                        ->where('card_no', $cardNo)
                        ->where('punch_date', $punchDate)
                        ->first();

                    if ($existingData) {
                        // Update the existing data
                        DB::table('attendance')
                            ->where('card_no', $cardNo)
                            ->where('punch_date', $punchDate)
                            ->update($data);
                    } else {
                        // Insert the new data
                        DB::table('attendance')->insert($data);
                    }
                }
            }

            DB::commit();
            Session::flash('success', 'Attendance Uploaded Successfully');
            return redirect()->route('attendance.data.import.index');
        } catch (Exception $ex) {
            DB::rollback();
            Session::flash('danger', $ex->getMessage());
            return redirect('attendance.data.insert.show/' . $batchId);
        }

    }



}
