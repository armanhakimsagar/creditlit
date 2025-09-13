<?php

namespace App\Modules\Certificate\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CertificateController extends Controller
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

    public function welcome()
    {
        return view("Certificate::welcome");
    }
    public function transferCertificateIndex(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            return studentList($request);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Certificate::transferCertificate.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }
    public function generateCertificte(Request $request)
    {
        $contactAcademicId = $request->contact_academic_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $pageTitle = 'Transfer Certificate';
        if (!empty($contactAcademicId)) {
            $certificates = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId,$pageTitle);
            return view("Certificate::transferCertificate.certificate", compact('certificates', 'pageTitle'));
        }
        Session::flash('danger', 'Please Select a Student First');
        return redirect()->back();
    }
    public function getSections(Request $request)
    {
        $data = DB::table('section_relations')->join('sections', 'sections.id', 'section_relations.section_id')->where('section_relations.class_id', $request->classId)->where('section_relations.academic_year_id', $request->yearId)->where('sections.is_trash', '0')->get();
        return response()->json($data);
    }
    public function testimonialIndex(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            return studentList($request);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Certificate::testimonialCertificate.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }
    public function generatetestimonialCertificte(Request $request)
    {
        $pageTitle = 'Testimonial';
        $contactAcademicId = $request->contact_academic_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        if (!empty($contactAcademicId)) {
            $certificates = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle);
            return view("Certificate::testimonialCertificate.testimonial", compact('certificates', 'pageTitle'));
        }
        Session::flash('danger', 'Please Select a Student First');
        return redirect()->back();
        // return view("Certificate::testimonialCertificate.testimonial");
    }
    public function studentshipIndex(Request $request)
    {
        $classList = DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        if ($request->ajax()) {
            return studentList($request);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Certificate::studentshipCertificate.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }
    public function generateStudentshipCertificte(Request $request)
    {
        $contactAcademicId = $request->contact_academic_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $pageTitle = 'Studentship';
        if (!empty($contactAcademicId)) {
            $certificates = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle);
            return view("Certificate::studentshipCertificate.studentship", compact('certificates'));
        }
        Session::flash('danger', 'Please Select a Student First');
        return redirect()->back();
    }
    // for exam seat plan
    public function examSeatIndex(Request $request)
    {

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $exam_list = ['0' => 'Select Exam'] + DB::table('exams')->where('is_trash', '0')->pluck('exam_name', 'id')->toArray();
        if ($request->ajax()) {

            return studentList($request);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Certificate::examSeatPlan.index', compact('classList', 'academicYearList', 'exam_list', 'currentYear'));
    }
    public function generateExamSeat(Request $request)
    {

        try {
            $contactAcademicId = $request->contact_academic_id;
            $studentClassId = $request->class_name;
            $sessionId = $request->academic_year;
            $print_id = $request->design_option;
            $pageTitle = 'Exam Seat';
            $exam_list = DB::table('exams')->where('id', $request->exam_name)->first();

            if (!empty($contactAcademicId)) {
                $studentList = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle);
                return view('Certificate::examSeatPlan.seat', compact('exam_list', 'studentList', 'print_id', 'pageTitle'));
            }
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
    // For admit card

    public function admitCardIndex(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $exam_list = ['0' => 'Select Exam'] + DB::table('exams')->where('is_trash', '0')->pluck('exam_name', 'id')->toArray();

        if ($request->ajax()) {

            return studentList($request);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Certificate::admitCard.index', compact('classList', 'academicYearList', 'exam_list', 'currentYear'));
    }

    public function generateAdmitCard(Request $request)
    {
        // return $request;
        try {
            $pageTitle = 'Admit Card';
            $contactAcademicId = $request->contact_academic_id;
            $studentClassId = $request->class_name;
            $sessionId = $request->academic_year;
            $print_id = $request->design_option;
            $exam_list = DB::table('exams')->where('id', $request->exam_name)->first();
            $company = DB::table('companies')->first();
            if (!empty($contactAcademicId)) {
                $studentList = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle);

                if ($company->admit_card_design == 1) {
                    return view('Certificate::admitCard.admitCard', compact('exam_list', 'studentList', 'print_id'));
                }
                if ($company->admit_card_design == 2) {
                    return view('Certificate::admitCard.innovationAdmitCard', compact('exam_list', 'studentList', 'print_id'));
                }

            }
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
