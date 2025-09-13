<?php

namespace App\Modules\Examination\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class StudentMarkGenerateController extends Controller
{
    public $user;
    // Construct Method
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function create(Request $request)
    {

        // $data =
        // echo "<pre>";
        // print_r($data);
        // exit();
        // // Create permission check
        // if (is_null($this->user) || !$this->user->can('section.asign.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view this page !');
        // }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();

        if ($request->ajax()) {
            $classId = $request->class_id;
            $academicYearId = $request->academicYearId;
            $examId = $request->examId;
            $subjectId = $request->subjectId;
            $sectionID = $request->sectionID;
            $exam_genarate = DB::table('exams')
                ->where('exams.id', $examId)
                ->leftJoin('contact_academics', 'contact_academics.class_id', 'exams.class_id')
                ->where('contact_academics.academic_year_id', $academicYearId)
                ->where('contact_academics.class_id', $classId)
                ->leftJoin('contacts', 'contacts.id', 'contact_academics.contact_id')
                ->leftJoin('subject_relations', 'subject_relations.class_id', 'exams.class_id')
                ->where('subject_relations.subject_id', $subjectId)
                ->where('contact_academics.status', 'active')
                ->where('contacts.status', 'active')
                ->where('contacts.is_trash', '0')
                ->where('contact_academics.is_trash', '0');

            if ($sectionID) {
                $exam_genarate->where('contact_academics.section_id', $sectionID);
            }
            $exam_genarate = $exam_genarate->select('contacts.id', 'contacts.contact_id', 'contacts.full_name', 'contact_academics.class_roll', 'contact_academics.contact_id as contact_academic_id', 'contact_academics.class_id', 'contact_academics.academic_year_id', 'exams.id as exams_id', 'subject_relations.subject_id as subject_id', 'contact_academics.section_id')
                ->orderByRaw('ISNULL(contact_academics.class_roll),contact_academics.class_roll ASC')
                ->get();

            foreach ($exam_genarate as $data) {
                $data->classId = $classId;
                $data->academicYearId = $academicYearId;
                $data->examId = $examId;
                $data->subjectId = $subjectId;
            }

            return DataTables::of($exam_genarate)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $btn = '<input type="checkbox"  class="allCheck all-check-box" id="checkStudent_' . $row->id . '" name="student_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" checked onclick="unCheck(this.id);isChecked();">';
                    } else {
                        $btn = '<input type="checkbox"  class="allCheck all-check-box" id="checkStudent_' . $row->id . '" name="student_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked();">';
                    }
                    return $btn;

                })
                ->editColumn('written', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $writtenVal = DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->first();
                        return '<div class="form-group"> <input type="number" class ="form-control written_mark"  pattern="[0-9.]+"  value="' . $writtenVal->written_mark . '" oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');" id="writtenMark' . $row->id . '" name="written_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    } else {
                        return '<div class="form-group"> <input type="number" class ="form-control written_mark"  pattern="[0-9.]+"  oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');" id="writtenMark' . $row->id . '" name="written_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    }

                })
                ->editColumn('mcq', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $mcqVal = DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->first();
                        return '<div class="form-group"> <input type="number" class ="form-control mcq_mark" oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');" value="' . $mcqVal->mcq_mark . '" id="mcqMark' . $row->id . '" name="mcq_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    } else {
                        return '<div class="form-group"> <input type="number" class ="form-control mcq_mark" oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');"id="mcqMark' . $row->id . '" name="mcq_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    }

                })
                ->editColumn('lab', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $labVal = DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->first();
                        return '<div class="form-group"> <input type="number" class ="form-control lab_mark" oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');" id="labMark' . $row->id . '" value="' . $labVal->lab_mark . '" name="lab_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    } else {
                        return '<div class="form-group"> <input type="number" class ="form-control lab_mark" oninput="calculatePayable(' . $row->id . ');Check(' . $row->id . ');"id="labMark' . $row->id . '" name="lab_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    }
                })
                ->editColumn('total', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $totalVal = DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->first();
                        return '<div class="form-group"><input type="text" class ="form-control total_mark" readonly id="totalMark' . $row->id . '" value="' . $totalVal->total_mark . '" name="total_mark[' . $row->id . '][]" ></div><span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';
                    } else {
                        return '<div class="form-group"> <input type="text" class ="form-control total_mark" readonly  id="totalMark' . $row->id . '" name="total_mark[' . $row->id . '][]">
                          <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>';

                    }
                })
                ->addColumn('action', function ($row) {
                    if (DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->where('is_trash','0')->exists()) {
                        $totalVal = DB::table('student_marks')->where('academic_year_id', $row->academic_year_id)->where('class_id', $row->class_id)->where('exam_id', $row->exams_id)->where('subject_id', $row->subject_id)->where('student_id', $row->id)->first();
                        return '<a class="btn btn-outline-danger btn-xs delete-mark" data-toggle="tooltip" data-placement="top" title="Delete" data-id= "' . $totalVal->id . '" ><i class="fas fa-trash"></i></a>';
                    } else {
                        return '';

                    }
                })
                ->rawColumns(['checkbox', 'written', 'mcq', 'lab', 'total','action'])
                ->make(true);
        }
        return view('Examination::studentMark.studentMarkGenerate', compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // store student marks
    public function store(Request $request)
    {
        $input = $request->all();
        $contactIds = $request->student_id;
        DB::beginTransaction();
        $data = [];
        $updateData = [];
        try {
            foreach ($contactIds as $key => $value) {
                if (DB::table('student_marks')->where('academic_year_id', $input['academic_year'])->where('class_id', $input['class_name'])->where('exam_id', $input['exam_name'])->where('subject_id', $input['subject_name'])->where('student_id', $input['student_id'][$key])->exists()) {
                    $studentMarkUpdate = DB::table('student_marks')->where('academic_year_id', $input['academic_year'])->where('class_id', $input['class_name'])->where('exam_id', $input['exam_name'])->where('subject_id', $input['subject_name'])->where('student_id', $input['student_id'][$key])->update([
                        'exam_id' => $input['exam_name'],
                        'class_id' => $input['class_name'],
                        'academic_year_id' => $input['academic_year'],
                        'section_id' => $input['section_name'],
                        'student_id' => $input['student_id'][$key],
                        'subject_id' => $input['subject_name'],
                        'written_mark' => $input['written_mark'][$value][0],
                        'mcq_mark' => $input['mcq_mark'][$value][0],
                        'lab_mark' => $input['lab_mark'][$value][0],
                        'total_mark' => $input['total_mark'][$value][0],
                        'updated_at' => date("Y-m-d h:i:s"),
                        'updated_by' => Auth::user()->id,
                        'is_trash' => 0,
                    ]);
                } else {
                    $data[$key]['exam_id'] = $input['exam_name'];
                    $data[$key]['class_id'] = $input['class_name'];
                    $data[$key]['academic_year_id'] = $input['academic_year'];
                    $data[$key]['section_id'] = $input['section_name'];
                    $data[$key]['student_id'] = $input['student_id'][$key];
                    $data[$key]['subject_id'] = $input['subject_name'];
                    $data[$key]['written_mark'] = $input['written_mark'][$value][0];
                    $data[$key]['mcq_mark'] = $input['mcq_mark'][$value][0];
                    $data[$key]['lab_mark'] = $input['lab_mark'][$value][0];
                    $data[$key]['total_mark'] = $input['total_mark'][$value][0];
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;
                }
            }
            if (!empty($data) || !empty($studentMarkUpdate)) {
                DB::table('student_marks')->insert($data);
                DB::commit();
                Session::flash('success', __('Examination::label.STUDENTS_MARKS_ADDED_SUCCESSFULLY'));
                return redirect()->back();
            }
        } catch (\Exception$e) {
            //If there are any exceptions, rollback the student mark
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

        echo "<pre>";
        print_r($data);
        exit();
    }

    // delete student marks
    public function destroy($id){
        DB::table('student_marks')->where('id', $id)->update(['is_trash' => 1]);
        return response()->json('Delete Successfully');
    }

    // Subject Wise Result
    public function subjectWiseResult(Request $request)
    {
        // // Create permission check
        // if (is_null($this->user) || !$this->user->can('section.asign.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view this page !');
        // }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();

        if ($request->ajax()) {
            $classId = $request->class_id;
            $academicYearId = $request->academicYearId;
            $examId = $request->examId;
            $subjectId = $request->subjectId;
            $sectionID = $request->sectionID;
            $subject_result = DB::table('student_marks')
                ->where('student_marks.academic_year_id', $academicYearId)
                ->where('student_marks.class_id', $classId)
                ->where('student_marks.exam_id', $examId)
                ->where('student_marks.subject_id', $subjectId)
                ->where('student_marks.is_trash', '0')
                ->leftJoin('contacts', 'contacts.id', 'student_marks.student_id')
                ->leftJoin('contact_academics', 'contact_academics.contact_id', 'contacts.id');
                if($sectionID){
                    $subject_result->where('contact_academics.section_id', $sectionID);
                }
                $subject_result = $subject_result->select('contacts.contact_id', 'contacts.full_name', 'contact_academics.class_roll', 'student_marks.*')
                ->orderByRaw('ISNULL(contact_academics.class_roll),contact_academics.class_roll ASC')
                ->get();
            foreach ($subject_result as $data) {
                $data->classId = $classId;
                $data->academicYearId = $academicYearId;
                $data->examId = $examId;
                $data->subjectId = $subjectId;
            }

            return DataTables::of($subject_result)
                ->addIndexColumn()
                ->addColumn('grade', function ($row) {
                    if ($row->total_mark >= 80) {
                        return "A+";
                    } elseif ($row->total_mark >= 70) {
                        return 'A';
                    } elseif ($row->total_mark >= 60) {
                        return 'A-';
                    } elseif ($row->total_mark >= 50) {
                        return 'B';
                    } elseif ($row->total_mark >= 40) {
                        return 'C-';
                    } elseif ($row->total_mark >= 33) {
                        return 'D';
                    } else {
                        return 'F';
                    }
                })

                ->addColumn('point', function ($row) {
                    if ($row->total_mark >= 80) {
                        return "5.00";
                    } elseif ($row->total_mark >= 70) {
                        return '4.00';
                    } elseif ($row->total_mark >= 60) {
                        return '3.50';
                    } elseif ($row->total_mark >= 50) {
                        return '3.00';
                    } elseif ($row->total_mark >= 40) {
                        return '2.50';
                    } elseif ($row->total_mark >= 33) {
                        return '2.00';
                    } else {
                        return '0.00';
                    }
                })

                ->rawColumns(['grade', 'point'])
                ->make(true);
        }
        return view('Examination::studentMark.subjectWiseResult', compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // Subject Wise Result
    public function studentWiseResult(Request $request)
    {
        // // Create permission check
        // if (is_null($this->user) || !$this->user->can('section.asign.create')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view this page !');
        // }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();

        // if ($request->ajax()) {
        //     $classId = $request->class_id;
        //     $academicYearId = $request->academicYearId;
        //     $examId = $request->examId;
        //     $studentId = $request->subjectId;
        //     $sectionID = $request->sectionID;
        //     $studentt_result = DB::table('student_marks')
        //     ->where('student_marks.academic_year_id',$academicYearId)
        //     ->where('student_marks.class_id',$classId)
        //     ->where('student_marks.section_id',$sectionID)
        //     ->where('student_marks.exam_id',$examId)
        //     ->where('student_marks.subject_id',$subjectId)
        //     ->leftJoin('contacts', 'contacts.id', 'student_marks.student_id')
        //     ->leftJoin('contact_academics', 'contact_academics.contact_id', 'contacts.id')
        //     ->select('contacts.contact_id','contacts.full_name','contact_academics.class_roll','student_marks.*')
        //     ->orderByRaw('ISNULL(contact_academics.class_roll),contact_academics.class_roll ASC')
        //     ->get();
        //     foreach ($student_result as $data) {
        //         $data->classId = $classId;
        //         $data->academicYearId = $academicYearId;
        //         $data->examId = $examId;
        //         $data->subjectId = $subjectId;
        //     }

        //     return DataTables::of($student_result)
        //         ->addIndexColumn()
        //         ->addColumn('grade', function ($row) {
        //             if($row->total_mark >= 80){
        //                 return "A+";
        //             }elseif($row->total_mark >= 70){
        //                 return 'A';
        //             }elseif($row->total_mark >= 60){
        //                 return 'A-';
        //             }elseif($row->total_mark >= 50){
        //                 return 'B';
        //             }elseif($row->total_mark >= 40){
        //                 return 'C-';
        //             }elseif($row->total_mark >= 33){
        //                 return 'D';
        //             }else{
        //                 return 'F';
        //             }
        //         })

        //         ->addColumn('point', function ($row) {
        //             if($row->total_mark >= 80){
        //                 return "5.00";
        //             }elseif($row->total_mark >= 70){
        //                 return '4.00';
        //             }elseif($row->total_mark >= 60){
        //                 return '3.50';
        //             }elseif($row->total_mark >= 50){
        //                 return '3.00';
        //             }elseif($row->total_mark >= 40){
        //                 return '2.50';
        //             }elseif($row->total_mark >= 33){
        //                 return '2.00';
        //             }else{
        //                 return '0.00';
        //             }
        //         })

        //         ->rawColumns(['grade','point'])
        //         ->make(true);
        // }
        return view('Examination::studentMark.studentWiseResult', compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // get exam dependent on class
    public function getExam(Request $request)
    {
        $data = DB::table('exams')
            ->where('exams.class_id', $request->classId)
            ->where('exams.academic_year_id', $request->yearId)
            ->where('is_trash',0)
            ->get();
        return response()->json($data);
    }

    // get Subject dependent on class
    public function getSubject(Request $request)
    {
        $data = DB::table('subject_relations')
            ->join('subjects', 'subjects.id', 'subject_relations.subject_id')
            ->where('subject_relations.class_id', $request->classId)
            ->where('subjects.is_trash', '0')->get();
        return response()->json($data);
    }

    // get Subject dependent on class
    public function getStudent(Request $request)
    {
        $data = DB::table('contact_academics')
            ->where('class_id', $request->classId)
            ->where('academic_year_id', $request->yearId)
            ->leftJoin('contacts', 'contacts.id', 'contact_academics.contact_id')
            ->select('contacts.full_name', 'contacts.id')
            ->get();
        return response()->json($data);
    }

    // get student wise mark sheet
    public function getStudentMark(Request $request)
    {
        $data = DB::table('contact_academics')
            ->where('class_id', $request->classId)
            ->where('academic_year_id', $request->yearId)
            ->where('contact_academics.contact_id', $request->studentId)
            ->leftJoin('contacts as student', 'student.id', 'contact_academics.contact_id')
            ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
            ->join('contacts as father', 'father_relation.target_contact', 'father.id')
            ->where('father.type', 2)
            ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
            ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
            ->where('mother.type', 3)
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->select('student.full_name', 'student.date_of_birth', 'student.contact_id', 'student.gender', 'father.full_name as father_name', 'mother.full_name as mother_name', 'contact_academics.class_roll', 'classes.name as class_name', 'contact_academics.registration_no', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name')
            ->first();
        $subject = DB::table('student_marks')
            ->where('class_id', $request->classId)
            ->where('academic_year_id', $request->yearId)
            ->where('exam_id', $request->examId)
            ->where('student_id', $request->studentId)
            ->where('student_marks.is_trash', '0')
            ->leftJoin('subjects', 'subjects.id', 'student_marks.subject_id')
            ->select('subjects.name', 'subjects.sub_code', 'student_marks.written_mark', 'student_marks.mcq_mark', 'student_marks.lab_mark', 'student_marks.total_mark')
            ->get();

        return response()->json([$data, $subject]);
    }

    public function allStudentResultIndex(Request $request)
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
                ->leftJoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->leftJoin('exams', 'exams.academic_year_id', 'contact_academics.academic_year_id')
                ->where('contact_academics.status', 'active')
                ->where('contacts.status', 'active')
                ->where('contacts.is_trash', '0')
                ->where('contact_academics.is_trash', '0')
                ->where('exams.id', $request->examId);
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->whereNot('contacts.status', 'cancel');

            $students = $model->select('contacts.id as contacts_id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'exams.exam_name as exam_name', 'contact_academics.class_roll as class_roll', 'contacts.contact_id as student_id')
            ->orderByRaw('ISNULL(contact_academics.class_roll),contact_academics.class_roll ASC')->get();

            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contacts_id . '" name="contact_id[]" value="' . $row->contacts_id . '" keyValue="' . $row->contacts_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Examination::marksheet.index", compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    // Generate all student marksheet
    public function generateMarksheet(Request $request)
    {
        // dd($request->all());
        $contactId = $request->contact_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $examId = $request->student_exam_id;
        $pageTitle = 'Student Marksheet';
        $yearName = DB::table('academic_years')->where('id',$request->session_id)->first();
        if (!empty($contactId)) {
            $examID = $request->student_exam_id;
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
                ->where('contact_academics.class_id', $studentClassId)
                ->where('contact_academics.academic_year_id', $sessionId)
                ->whereIn('contact_academics.contact_id', $contactId)
                ->select('student.full_name as student_name', 'father.full_name as father_name', 'mother.full_name as mother_name', 'classes.name as class_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'sections.name as section_name', 'shifts.name as shift_name', 'student.date_of_birth', 'student.contact_id as student_id', 'contact_academics.registration_no', 'contact_academics.class_id', 'contact_academics.academic_year_id', 'contact_academics.contact_id', )
                ->get();
            return view("Examination::marksheet.allStudentResult", compact('data', 'examID','yearName'));

        } else {
            Session::flash('danger', 'Please Select a Student First');
            return redirect()->back();
        }

    }
}
