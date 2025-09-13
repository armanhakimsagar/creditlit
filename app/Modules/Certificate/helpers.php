<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

function studentList($request)
{
    $students = [];
    $model = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')->join('classes', 'classes.id', 'contact_academics.class_id')->leftJoin('sections', 'contact_academics.section_id', 'sections.id')->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id');
    if ($request->class_id) {
        $model = $model->whereIn('contact_academics.class_id', $request->class_id);
    }
    if (!empty($request->section_id)) {
        $model = $model->whereIn('contact_academics.section_id', $request->section_id);
    }
    $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->where('contacts.status', 'active');

    $students = $model->select('contacts.full_name as full_name', 'contacts.id as sid', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academics_id', 'contacts.contact_id as student_id')->get();

    return Datatables::of($students)->addIndexColumn()
        ->addColumn('checkbox', function ($row) {
            $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contact_academics_id . '" name="contact_academic_id[]" value="' . $row->contact_academics_id . '" keyValue="' . $row->contact_academics_id . '" onclick="unCheck(this.id);isChecked();">';
            return $btn;
        })
        ->editColumn('full_name', function ($row) {
            $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->sid]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
            return $btn;
        })
        ->rawColumns(['checkbox', 'full_name'])
        ->make(true);
}


function fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle)
{
    $data = [];
    if ($pageTitle != 'Account Clearence Report') {
        $classId = explode(",", $studentClassId);
    } else {
        $explodedclassIdArray = $studentClassId;
        $classId = [];
        foreach ($explodedclassIdArray as $explodedclass) {
            $classId = array_merge($classId, explode(",", $explodedclass));
        }
    }

    $data = DB::table('contacts as student')
        ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
        ->join('contacts as father', 'father_relation.target_contact', 'father.id')
        ->where('father.type', 2)
        ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
        ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
        ->where('mother.type', 3)
        ->join('contact_academics', 'contact_academics.contact_id', 'student.id')->join('classes', 'classes.id', 'contact_academics.class_id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
        ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
        ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
        ->leftJoin('addresses', 'student.id', 'addresses.contact_id')
        ->where('addresses.is_permanent', 1)
        ->leftJoin('divisions', 'addresses.division', 'divisions.id')
        ->leftJoin('districts', 'addresses.district', 'districts.id')
        ->leftJoin('upazilas', 'addresses.upazila', 'upazilas.id')
        ->where('contact_academics.academic_year_id', $sessionId)
        ->whereIn('contact_academics.id', $contactAcademicId)
        ->whereIn('contact_academics.class_id', $classId)
        ->select('student.full_name as student_name', 'student.gender as student_gender', 'father.full_name as father_name', 'mother.full_name as mother_name', 'classes.name as class_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'sections.name as section_name', 'shifts.name as shift_name', 'student.date_of_birth', 'addresses.area', 'divisions.name as division_name', 'districts.name as district_name', 'upazilas.name as upazila_name', 'student.contact_id as student_id', 'student.photo')
        ->get();
    return $data;
}

function fetchPayrollDetails($payrollId)
{
    $data = [];
    $data = DB::table('employee_payroll')
        ->leftJoin('contacts', 'contacts.id', 'employee_payroll.employee_id')
        ->leftJoin('enum_employee_types', 'enum_employee_types.id', 'contacts.employee_designation_id')
        ->select('employee_payroll.*', 'contacts.full_name', 'contacts.cp_phone_no as phone', 'contacts.bank_account_number as bank_acc', 'enum_employee_types.name as designation')
        ->whereIn('employee_payroll.id', $payrollId)->get();
    return $data;
}

function paymentSMS($request)
{
    $item = '';
    foreach ($request->product_id as $key => $value) {
        $itemName = DB::table('products')->where('id', $value[0])
            ->select('products.short_name')
            ->first();
        if ($request->paid_amount[$key][0] > 0) {
            $item .= $itemName->short_name . " " . $request->paid_amount[$key][0] . ", ";
        }
    }
    $studentInfo = DB::table('contacts as student')->where('student.type', 1)
        ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
        ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
        ->where('guardian.type', 4)
        ->where('student.id', $request->student_id)
        ->select('student.full_name', 'student.contact_id', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no')->first();
    $due = DB::table('contact_payable_items')->where('contact_id', $request->student_id)->sum('due');
    $smsEnablityCheck = DB::table('companynotificationsettings')->first();
    $db_format = $smsEnablityCheck->smsAfterStudentPaymentFormat;
    $Constant = ["{GuardianName}", "{StudentName}", "{PaidAmount}", "{SID}", "{TotalAmount}", "{Items}"];
    $Replace = [$studentInfo->guardian_name, $studentInfo->full_name, $request->total_paid, $studentInfo->contact_id, $request->total_due, $item];
    $text = str_replace($Constant, $Replace, $db_format);
    $sms_count = ceil(Str::length($text) / 165);
    $url = "http://66.45.237.70/api.php";
    $numbers = $studentInfo->cp_phone_no;
    if (!empty($text)) {

        if (!empty($numbers)) {
            $data = array(
                'username' => "01914239553",
                'password' => config('app.password'),
                'number' => $numbers,
                'message' => $text,
            );
            $ch = curl_init(); // Initialize cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
            $p = explode("|", $smsresult);
            $sendstatus = $p[0];
            // dd($sendstatus);
            if ($sendstatus == 1101) {
                $isent = 1;
            } else {
                $isent = 0;
            }
            //Message Status
            if ($sendstatus == 1000) {
                $error = "Invalid user or Password";
            } elseif ($sendstatus == 1002) {
                $error = "Empty Number";
            } elseif ($sendstatus == 1003) {
                $error = "Invalid message or empty message";
            } elseif ($sendstatus == 1004) {
                $error = "Invalid number";
            } elseif ($sendstatus == 1005) {
                $error = "All Number is Invalid";
            } elseif ($sendstatus == 1006) {
                $error = "insufficient Balance";
            } elseif ($sendstatus == 1009) {
                $error = "Inactive Account";
            } elseif ($sendstatus == 1010) {
                $error = "Max number limit exceeded";
            } elseif ($sendstatus == 1101) {
                $error = "Success";
            }
            $message = DB::table('msgnotificationsents')->insert([
                'CompanyId' => '1',
                'CustomerId' => $request->student_id,
                'MobileNumbers' => $numbers,
                'SentMessage' => $text,
                'NumberOfMessage' => '1',
                'IsSent' => $isent,
                'ErrorCode' => $error,
                'sms_type' => 5,
                'sms_count' => $sms_count,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::user()->id,
            ]);
        }
    }
}

function dueSMS($request)
{

    // return $request;
    $input = $request->all();
    $smsEnablityCheck = DB::table('companynotificationsettings')->first();
    $db_format = $smsEnablityCheck->smsStudentDueFormat;
    $Constant = ["{GuardianName}", "{StudentName}", "{SID}", "{Due}"];

    $url = "http://66.45.237.70/api.php";
    if (!empty($db_format)) {
        foreach ($input['contact_academic_id'] as $key => $contactAcademicId) {
            $students = DB::table('contact_payable_items')
                ->where('contact_payable_items.due', '>', 0)
                ->leftJoin('contacts', 'contacts.id', 'contact_payable_items.contact_id')
                ->leftJoin('classes', 'classes.id', 'contact_payable_items.class_id')
                ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->where('contact_academics.status', 'active')
                ->leftjoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftjoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->where('contacts.id', $contactAcademicId)
                ->select('contacts.full_name as student_name', 'contacts.contact_id as student_id', 'guardian.full_name as guardian_name', DB::raw('SUM(contact_payable_items.due) as student_due'))
                ->groupBy('contact_academics.contact_id', 'contacts.full_name', 'guardian.full_name')
                ->first();
            $Replace = [$students->guardian_name, $students->student_name, $students->student_id, $students->student_due];
            $text = str_replace($Constant, $Replace, $db_format);
            $sms_count = ceil(Str::length($text) / 165);
            $number = $input['phone'][$contactAcademicId];
            if (!empty($number)) {
                $data = array(
                    'username' => "01914239553",
                    'password' => config('app.password'),
                    'number' => $number,
                    'message' => $text,
                );
                $ch = curl_init(); // Initialize cURL
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $smsresult = curl_exec($ch);
                $p = explode("|", $smsresult);
                $sendstatus = $p[0];
                // dd($sendstatus);
                if ($sendstatus == 1101) {
                    $isent = 1;
                } else {
                    $isent = 0;
                }
                //Message Status
                if ($sendstatus == 1000) {
                    $error = "Invalid user or Password";
                } elseif ($sendstatus == 1002) {
                    $error = "Empty Number";
                } elseif ($sendstatus == 1003) {
                    $error = "Invalid message or empty message";
                } elseif ($sendstatus == 1004) {
                    $error = "Invalid number";
                } elseif ($sendstatus == 1005) {
                    $error = "All Number is Invalid";
                } elseif ($sendstatus == 1006) {
                    $error = "insufficient Balance";
                } elseif ($sendstatus == 1009) {
                    $error = "Inactive Account";
                } elseif ($sendstatus == 1010) {
                    $error = "Max number limit exceeded";
                } elseif ($sendstatus == 1101) {
                    $error = "Success";
                }
                $message = DB::table('msgnotificationsents')->insert([
                    'CompanyId' => '1',
                    'CustomerId' => $contactAcademicId,
                    'MobileNumbers' => $number,
                    'SentMessage' => $text,
                    'NumberOfMessage' => '1',
                    'IsSent' => $isent,
                    'ErrorCode' => $error,
                    'sms_type' => 4,
                    'sms_count' => $sms_count,
                    'created_at' => date("Y-m-d h:i:s"),
                    'created_by' => Auth::user()->id,
                ]);
            }
        }
        Session::flash('success', 'Message Sent Successful');
        return redirect()->back();
    } else {
        Session::flash('danger', 'Message Not Sent (Text filed was empty)');
        return redirect()->back();
    }
}
