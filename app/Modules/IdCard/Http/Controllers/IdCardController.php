<?php

namespace App\Modules\IdCard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class IdCardController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public $user;
    // Construct Method
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    // All student ID card
    public function allStudentIdCard(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('transfer.certificate.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            $students = [];
            $model = DB::table('contacts')
                ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftJoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id');
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->whereNot('contacts.status', 'cancel');

            $students = $model->select('contacts.id as contacts_id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'contact_academics.class_roll as class_roll', 'contacts.contact_id as student_id')->get();

            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contacts_id . '" name="contact_id[]" value="' . $row->contacts_id . '" keyValue="' . $row->contacts_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("IdCard::studentIdCard.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // Generate all student Id Card
    public function generateStudentIdCard(Request $request)
    {

        $contactId = $request->contact_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $pageTitle = 'Student Marksheet';
        if (!empty($contactId)) {
            $data = DB::table('contacts as student')
                ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->join('contact_academics', 'contact_academics.contact_id', 'student.id')->join('classes', 'classes.id', 'contact_academics.class_id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->where('contact_academics.class_id', $studentClassId)
                ->where('contact_academics.academic_year_id', $sessionId)
                ->whereIn('contact_academics.contact_id', $contactId)
                ->select('student.full_name as student_name', 'classes.name as class_name', 'contact_academics.class_roll as class_roll', 'sections.name as section_name', 'student.contact_id as student_id', 'contact_academics.contact_id', 'guardian.cp_phone_no as contact_no', 'student.photo')
                ->get();
            return view("IdCard::studentIdCard.allStudentIdCard", compact('data'));

        } else {
            Session::flash('danger', 'Please Select a Student First');
            return redirect()->back();
        }

    }

    // All guardian ID card
    public function allGuardianIdCard(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('transfer.certificate.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            $students = [];
            $model = DB::table('contacts as student')
                ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->join('contact_academics', 'contact_academics.contact_id', 'student.id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftJoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id');
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->whereNot('student.status', 'cancel');

            $students = $model->select('student.id as contacts_id', 'student.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'contact_academics.class_roll as class_roll', 'student.contact_id as student_id', 'guardian.full_name as guardian_name')->get();

            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contacts_id . '" name="contact_id[]" value="' . $row->contacts_id . '" keyValue="' . $row->contacts_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("IdCard::guardianIdCard.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // Generate all guardian Id Card
    public function generateGuardianIdCard(Request $request)
    {
        $contactId = $request->contact_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $pageTitle = 'Student Marksheet';
        if (!empty($contactId)) {
            $data = DB::table('contacts as student')
                ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->join('contact_academics', 'contact_academics.contact_id', 'student.id')->join('classes', 'classes.id', 'contact_academics.class_id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->where('contact_academics.class_id', $studentClassId)
                ->where('contact_academics.academic_year_id', $sessionId)
                ->whereIn('contact_academics.contact_id', $contactId)
                ->select('student.full_name as student_name', 'student.contact_id as student_id', 'guardian.full_name as guardian_name', 'student.photo')
                ->get();
            return view("IdCard::guardianIdCard.allGuardianIdCard", compact('data'));

        } else {
            Session::flash('danger', 'Please Select a Student First');
            return redirect()->back();
        }

    }

    // All guardian ID card
    public function allEmployeeIdCard(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('transfer.certificate.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {
            $employee = DB::table('contacts as employee')
                ->where('employee.type', 5)
                ->leftJoin('enum_department_types', 'employee.employee_department_id', 'enum_department_types.id')
                ->leftJoin('enum_employee_types', 'employee.employee_designation_id', 'enum_employee_types.id')
                ->select('employee.id as contacts_id', 'employee.full_name as employee_name', 'enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.cp_phone_no as contact_no', 'employee.photo')
                ->get();

            return Datatables::of($employee)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contacts_id . '" name="contact_id[]" value="' . $row->contacts_id . '" keyValue="' . $row->contacts_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        return view("IdCard::employeeIdCard.index");
    }

    // Generate all guardian Id Card
    public function generateEmployeeIdCard(Request $request)
    {

        $contactId = $request->contact_id;
        if (!empty($contactId)) {
            $data = DB::table('contacts as employee')
                ->where('employee.type', 5)
                ->leftJoin('enum_department_types', 'employee.employee_department_id', 'enum_department_types.id')
                ->leftJoin('enum_employee_types', 'employee.employee_designation_id', 'enum_employee_types.id')
                ->select('employee.id as contacts_id', 'employee.full_name as employee_name', 'enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.cp_phone_no as contact_no', 'employee.photo')
                ->whereIn('employee.id', $contactId)
                ->get();
            return view("IdCard::employeeIdCard.allEmployeeIdCard", compact('data'));

        } else {
            Session::flash('danger', 'Please Select a Employee First');
            return redirect()->back();
        }

    }

}
