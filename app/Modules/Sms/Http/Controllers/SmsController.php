<?php

namespace App\Modules\Sms\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;
class SmsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Sms::welcome");
    }
    public function dynamicSmsIndex(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            $students = [];
            $model = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')->join('classes', 'classes.id', 'contact_academics.class_id')->leftJoin('sections', 'contact_academics.section_id', 'sections.id')->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->where('contacts.is_trash', 0);
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->where('contacts.status', 'active');

            $students = $model->select('contacts.full_name as full_name', 'contacts.id as student_id', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academics_id', 'guardian.full_name as guardian_name', 'guardian.guardian_relation as relationship', 'guardian.cp_phone_no as phone')->get();
            // dd($students);
            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contact_academics_id . '" name="contact_academic_id[]" value="' . $row->student_id . '" keyValue="' . $row->contact_academics_id . '" onclick="unCheck(this.id);isChecked();"><input type="hidden" value="' . $row->phone . '" name="phone[' . $row->student_id . ']"/>';
                    return $btn;
                })
                ->editColumn('full_name', function ($row) {
                    $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    return $btn;
                })
                ->rawColumns(['checkbox','full_name'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Sms::dynamic.index', compact('classList', 'academicYearList', 'request', 'currentYear'));
    }
    // For guardian SMS
    public function guardianSmsIndex(Request $request)
    {
        $classList = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $transportList = DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $item_list = DB::table('products')->where('is_trash', '0')->pluck('name', 'id')->toArray();

        if ($request->ajax()) {
            
            $students = [];
            $model = DB::table('contacts')
                ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->where('contacts.is_trash', 0);
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            if (!empty($request->transportId)) {
                $model = $model->whereIn('contact_academics.transport_id', $request->transportId);
            }
            if (!empty($request->shiftId)) {
                $model = $model->whereIn('contact_academics.shift_id', $request->shiftId);
            }
            if (!empty($request->item_id)) {
                $model = $model->join('contact_payable_items', 'contact_payable_items.contact_id', 'contacts.id')
                ->whereIn('contact_payable_items.product_id', $request->item_id)->groupby('contact_payable_items.contact_id');
            }
            if ($request->genderId) {
                $model = $model->where('contacts.gender', $request->genderId);
            }
            if ($request->admissionTypeId) {
                $model = $model->where('contact_academics.admission_type', $request->admissionTypeId);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->where('contacts.status', 'active');

            $students = $model->select('contacts.full_name as full_name', 'contacts.id as student_id', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academics_id', 'guardian.full_name as guardian_name', 'guardian.guardian_relation as relationship', 'guardian.cp_phone_no as phone')->get();
            // dd($students);
            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contact_academics_id . '" name="contact_academic_id[]" value="' . $row->student_id . '" keyValue="' . $row->contact_academics_id . '" onclick="unCheck(this.id);isChecked();"><input type="hidden" value="' . $row->phone . '" name="phone[' . $row->student_id . ']"/>';
                    return $btn;
                })
                ->editColumn('full_name', function ($row) {
                    $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    return $btn;
                })
                ->rawColumns(['checkbox','full_name'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Sms::guardianSms.index', compact('classList', 'academicYearList', 'request', 'currentYear', 'transportList', 'shift_list', 'item_list'));
    }

    public function sendSms(Request $request)
    {
        $smsEnablityCheck = DB::table('companynotificationsettings')->first();
        if ($smsEnablityCheck->smsEnableDynamicSms == 1 && config('app.IsSMSActive') == 1) {
            // return $request;
            $input = $request->all();
            $db_format = $input['sms'];
            $Constant = ["{GUARDIAN_NAME}", "{STUDENT_FULLNAME}", "{SID}", "{ROLL}"];

            $url = "http://66.45.237.70/api.php";
            if (!empty($db_format)) {
                foreach ($input['contact_academic_id'] as $key => $contactAcademicId) {
                    $students = DB::table('contacts as student')->where('student.type', 1)
                        ->join('contact_academics', 'contact_academics.contact_id', 'student.id')
                        ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                        ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                        ->where('guardian.type', 4)
                        ->where('student.id', $contactAcademicId)
                        ->select('student.full_name as student_name', 'student.contact_id as sid', 'guardian.full_name as guardian_name', 'contact_academics.class_roll')->first();
                    $Replace = [$students->guardian_name, $students->student_name, $students->sid, $students->class_roll];
                    $text = str_replace($Constant, $Replace, $db_format);
                    // dd($input['phone'][$contactAcademicId]);
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
                            'sms_type' => 1,
                            'sms_count' => $input['sms_count'],
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
                Session::flash('success', 'Message Sent Successful');
                return redirect()->route('dynamic.sms.index');
            } else {
                Session::flash('danger', 'Message Not Sent (Text filed was empty)');
                return redirect()->back();
            }
        } else {
            Session::flash('danger', 'Sending Dynamic Message is not enable');
            return redirect()->back();
        }
    }
    public function guardianSendSms(Request $request)
    {
        $smsEnablityCheck = DB::table('companynotificationsettings')->first();
        if ($smsEnablityCheck->smsEnableGuardianSms == 1 && config('app.IsSMSActive') == 1) {
            // return $request;
            $input = $request->all();
            $text = $input['sms'];
            $url = "http://66.45.237.70/api.php";
            if (!empty($text)) {
                foreach ($input['contact_academic_id'] as $key => $contactAcademicId) {
                    $number = $input['phone'][$contactAcademicId];
                    // dd($number);
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
                            'sms_type' => 2,
                            'sms_count' => $input['sms_count'],
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
        } else {
            Session::flash('danger', 'Sending Guardian Message is not enable');
            return redirect()->back();
        }
    }
    // For Due SMS
    public function dueSmsIndex(Request $request)
    {
        $classList = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $versionList = DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $transportList = DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        if ($request->ajax()) {
            $students = [];
            $model = DB::table('contacts')
                ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftJoin('contact_payable_items', 'contacts.id', 'contact_payable_items.contact_id')
                ->leftJoin('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'sections.id', 'contact_academics.section_id')
                ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
                ->leftjoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftjoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('contacts.is_trash', 0)
                ->where('contacts.type', 1)
                ->where('contact_academics.status', 'active')
                ->where('contact_payable_items.due', '>', 0)
                ->where('guardian.type', 4);

            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }

            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            if (!empty($request->shiftId)) {
                $model = $model->whereIn('contact_academics.shift_id', $request->shiftId);
            }
            if (!empty($request->genderId)) {
                $model = $model->whereIn('contacts.gender', $request->genderId);
            }
            if (!empty($request->versionId)) {
                $model = $model->whereIn('contact_academics.version_id', $request->versionId);
            }
            if (!empty($request->groupId)) {
                $model = $model->whereIn('contact_academics.group_id', $request->groupId);
            }
            if (!empty($request->status)) {
                $model = $model->whereIn('contacts.status', $request->status);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0);

            $students = $model->select('contacts.full_name as full_name', 'contact_payable_items.contact_id as student_id', 'classes.name as class_name', 'sections.name as section_name', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academics_id', 'guardian.cp_phone_no as phone', DB::raw('SUM(contact_payable_items.due) as student_due'))
                ->groupBy('full_name', 'student_id', 'section_name', 'class_roll', 'class_name', 'contact_academics_id', 'phone')
                ->get();
            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contact_academics_id . '" name="contact_academic_id[]" value="' . $row->student_id . '" keyValue="' . $row->contact_academics_id . '" onclick="unCheck(this.id);isChecked();"><input type="hidden" value="' . $row->phone . '" name="phone[' . $row->student_id . ']"/><input type="hidden" value="' . $row->student_due . '" name="due[' . $row->student_id . ']"/>';
                    return $btn;
                })
                ->editColumn('full_name', function ($row) {
                    $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    return $btn;
                })
                ->rawColumns(['checkbox','full_name'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Sms::dueSms.index', compact('classList', 'academicYearList', 'request', 'currentYear', 'versionList', 'shift_list', 'groupList'));
    }

    // Send Due SMS
    public function dueSendSms(Request $request)
    {

        $smsEnablityCheck = DB::table('companynotificationsettings')->first();
        if ($smsEnablityCheck->smsEnableDueSms == 1 && config('app.IsSMSActive') == 1) {
            dueSMS($request);
            return redirect()->route('due.sms.index');
        } else {
            Session::flash('danger', 'Sending Due Message is not enable');
            return redirect()->route('due.sms.index');
        }
    }

    public function teacherSmsIndex(Request $request)
    {
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $model = [];
        if ($request->ajax()) {
            $model = DB::table('contacts as employee')
                ->where('employee.is_trash', 0)
                ->where('employee.type', 5)
                ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
                ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');
            if ($request->department_id) {
                $model->where('employee.employee_department_id', $request->department_id);
            }
            if ($request->designation_id) {
                $model->where('employee.employee_designation_id', $request->designation_id);
            }
            $model = $model->select('employee.*', 'enum_department_types.name as department_name', 'enum_employee_types.name as designation_name')
                ->get();

            // dd($model);
            return Datatables::of($model)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->id . '" name="employee_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked();"><input type="hidden" value="' . $row->cp_phone_no . '" name="phone[' . $row->id . ']"/>';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        return view('Sms::teacherSms.index', compact('department', 'designation', 'request', 'model'));
    }

    public function teacherSendSms(Request $request)
    {
        // return $request;
        $smsEnablityCheck = DB::table('companynotificationsettings')->first();
        if ($smsEnablityCheck->smsEnableTeacherSms == 1 && config('app.IsSMSActive') == 1) {
            $input = $request->all();
            $text = $input['sms'];
            $url = "http://66.45.237.70/api.php";
            if (!empty($text)) {
                foreach ($input['employee_id'] as $key => $employeeId) {
                    $number = $input['phone'][$employeeId];
                    // dd($number);
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
                            'CustomerId' => $employeeId,
                            'MobileNumbers' => $number,
                            'SentMessage' => $text,
                            'NumberOfMessage' => '1',
                            'IsSent' => $isent,
                            'ErrorCode' => $error,
                            'sms_type' => 3,
                            'sms_count' => $input['sms_count'],
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
        } else {
            Session::flash('danger', 'Sending Teacher Message is not enable');
            return redirect()->back();
        }
    }

    // Owner SMS Index
    public function ownerSmsIndex(Request $request)
    {
        $today = Carbon::now()->format('d-m-Y');
        $hasSmsToday = DB::table('msgnotificationsents')
            ->where('sms_type', 6)
            ->where(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'), $today)
            ->first();
        $expense = DB::table('other_payment')
            ->where(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'), $today)
            ->sum('payment_amount');
        $recieve = DB::table('payment_history')
            ->where(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'), $today)
            ->sum('payment_amount');
        $cashBook = DB::table('cash_banks')
            ->where('payment_type', 'cash')
            ->where(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'), $today)
            ->sum('amount');
        $bankBook = DB::table('cash_banks')
            ->where('payment_type', 'bank')
            ->where(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")'), $today)
            ->sum('amount');
        $allSms = DB::table('msgnotificationsents')
            ->where('IsSent', 1)
            ->where('sms_type',6)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
        return view('Sms::ownerSms.index', compact('expense', 'recieve', 'cashBook', 'bankBook', 'hasSmsToday', 'allSms'));
    }

    // Owner SMS Index
    public function ownerSendSms(Request $request)
    {

        $smsEnablityCheck = DB::table('companynotificationsettings')->first();
        if ($smsEnablityCheck->smsEnableOwnerSms == 1 && config('app.IsSMSActive') == 1) {
            $ownerNumber = DB::table('companies')->first();
            $db_format = $smsEnablityCheck->ownerSmsFormat;
            $Constant = ["{Expense}", "{Recieve}", "{Cashbook}", "{Bankbook}"];

            $url = "http://66.45.237.70/api.php";
            if (!empty($db_format)) {
                $Replace = [$request->today_expense, $request->today_recieve, $request->today_cashBook, $request->today_bankBook];
                $text = str_replace($Constant, $Replace, $db_format);
                $number = $ownerNumber->ownerMobileNumber;
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
                        'MobileNumbers' => $number,
                        'SentMessage' => $text,
                        'NumberOfMessage' => '1',
                        'IsSent' => $isent,
                        'ErrorCode' => $error,
                        'sms_type' => 6,
                        'sms_count' => 1,
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => Auth::user()->id,
                    ]);
                }
                Session::flash('success', 'Message Sent to Owner Successfully');
                return redirect()->back();
            } else {
                Session::flash('danger', 'Message Not Sent (Text filed was empty)');
                return redirect()->back();
            }
            return redirect()->route('owner.sms.index');
        } else {
            Session::flash('danger', 'Sending Owner Message is not enable');
            return redirect()->route('owner.sms.index');
        }

    }
}