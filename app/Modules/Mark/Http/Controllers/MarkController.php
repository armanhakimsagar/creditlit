<?php

namespace App\Modules\Mark\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
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

    public function markAttributeIndex(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('mark.attribute.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {

            $data = DB::table('mark_attribute')->where('is_trash', 0)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success ">Active</span> ';
                    } elseif ($row->status == 'inactive') {
                        return '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        return '<span class="badge badge-danger">Cancel</span> ';
                    }
                })
                ->addColumn('action', function ($row) {

                    $action = ' <a href="' . route('mark.attribute.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('mark.attribute.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;
                })->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('Mark::markAttribute.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAttributeCreate()
    {
        $pageTitle = "Add Mark Attribute";
        return view('Mark::markAttribute.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAttributeStore(Request $request)
    {
        $validated = $request->validate([
            'name' => Rule::unique('mark_attribute')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        try {
            DB::table('mark_attribute')->insert([
                'name' => $request->name,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'created_at' => date("Y-m-d h:i:s"),
                'is_trash' => 0,
            ]);
            Session::flash('success', "mark attribute Created Successfully ");
            return redirect()->route('mark.attribute.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAttributeEdit($id)
    {
        $editTitle = "Edit mark attribute";
        $data = DB::table('mark_attribute')->where('is_trash', 0)->where('id', $id)->first();
        return view('Mark::markAttribute.edit', compact('data', 'editTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAttributeUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => Rule::unique('mark_attribute', 'name')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        try {
            DB::table('mark_attribute')->where('id', $id)->update([
                'name' => $request->name,
                'status' => $request->status,
                'updated_by' => Auth::id(),
                'updated_at' => date("Y-m-d h:i:s"),
                'is_trash' => 0,
            ]);
            Session::flash('success', "mark attribute Updated Successfully");
            return redirect()->route('mark.attribute.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAttributeDestroy($id)
    {
        DB::table('mark_attribute')->where('id', $id)->update(['is_trash' => 1]);

        Session::flash('success', 'mark Attribute deleted successfully !');
        return redirect()->back();
    }
    public function markGradeIndex(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('exam.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {

            $data = DB::table('mark_grades')->where('is_trash', 0)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success ">Active</span> ';
                    } elseif ($row->status == 'inactive') {
                        return '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        return '<span class="badge badge-danger">Cancel</span> ';
                    }
                })
                ->addColumn('action', function ($row) {

                    $action = ' <a href="' . route('mark.grade.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('mark.grade.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;
                })->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('Mark::markGrade.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function markGradeCreate()
    {
        // Create Permission check
        if (is_null($this->user) || !$this->user->can('exam.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $pageTitle = "Add Mark Grade";
        return view('Mark::markGrade.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markGradeStore(Request $request)
    {
        // Store Permission Check
        if (is_null($this->user) || !$this->user->can('exam.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        // $markGradeName = $request->name;
        // $findSameId = DB::table('mark_attribute')->where('name', $markGradeName)->where('is_trash', 1)->first();
        // if (isset($findSameId)) {
        //     Session::flash('error', 'mark Attribute Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
        //     return redirect()->back()->withInput($request->all());
        // }

        $validated = $request->validate([
            'start_mark' => Rule::unique('mark_grades')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'end_mark' => Rule::unique('mark_grades')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'grade' => 'required',
            'status' => 'required',
        ]);
        try {
            DB::table('mark_grades')->insert([
                'start_mark' => $request->start_mark,
                'end_mark' => $request->end_mark,
                'grade' => $request->grade,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'created_at' => date("Y-m-d h:i:s"),
                'is_trash' => 0,
            ]);
            Session::flash('success', "mark grade Created Successfully ");
            return redirect()->route('mark.grade.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markGradeEdit($id)
    {
        // Edit permission check
        if (is_null($this->user) || !$this->user->can('exam.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $editTitle = "Edit mark grade";
        $data = DB::table('mark_grades')->where('is_trash', 0)->where('id', $id)->first();
        return view('Mark::markGrade.edit', compact('data', 'editTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markGradeUpdate(Request $request, $id)
    {
        // Edit permission check
        if (is_null($this->user) || !$this->user->can('exam.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $validated = $request->validate([
            'start_mark' => Rule::unique('mark_grades', 'start_mark')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'end_mark' => Rule::unique('mark_grades', 'end_mark')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
            'grade' => 'required',
        ]);
        try {
            DB::table('mark_grades')->where('id', $id)->update([
                'start_mark' => $request->start_mark,
                'end_mark' => $request->end_mark,
                'grade' => $request->grade,
                'status' => $request->status,
                'updated_by' => Auth::id(),
                'updated_at' => date("Y-m-d h:i:s"),
                'is_trash' => 0,
            ]);
            Session::flash('success', "mark grade Updated Successfully");
            return redirect()->route('mark.grade.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markGradeDestroy($id)
    {
        // destroy permission check
        if (is_null($this->user) || !$this->user->can('mark.grade.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        DB::table('mark_grades')->where('id', $id)->update(['is_trash' => 1]);

        Session::flash('success', 'mark grade deleted successfully !');
        return redirect()->back();
    }

    public function markConfigIndex()
    {

        $data = DB::table('mark_configs')
            ->join('classes', 'classes.id', 'mark_configs.class_id')
            ->select('classes.name as class_name', 'mark_configs.class_id')
            ->groupBy('mark_configs.class_id')
            ->get();
        return view('Mark::markConfig.index', compact('data'));
    }
    public function markConfigCreate()
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $examList = DB::table('exams')->where('is_trash', 0)->get();
        $markAttributeList = DB::table('mark_attribute')->where('is_trash', 0)->get();
        return view('Mark::markConfig.create', compact('classList', 'academicYearList', 'examList', 'markAttributeList'));
    }

    public function markConfigStore(Request $request)
    {
        $input = $request->all();
        $data = [];
        // echo "<pre/>";
        //     print_r($input);
        //     exit();
        DB::beginTransaction();
        try {
            if ($request->check_exam && $request->check_subject && $request->check_mark_attribute) {
                # code...
                foreach ($request->check_exam as $key => $value) {
                    foreach ($request->check_subject as $key2 => $value2) {
                        foreach ($request->check_mark_attribute as $key3 => $value3) {

                            $data = DB::table('mark_configs')->updateOrInsert(
                                [
                                    'academic_year_id' => $input['academic_year_id'],
                                    'class_id' => $input['class_id'],
                                    'exam_id' => $value,
                                    'subject_id' => $value2,
                                    'mark_attribute_id' => $value3,
                                ],
                                [
                                    'total_mark' => $input['total'][$value3][0],
                                    'pass_mark' => $input['pass'][$value3][0],
                                    'percentage' => $input['percentage'][$value3][0],
                                    'status' => "active",
                                    'created_at' => date("Y-m-d h:i:s"),
                                    'created_by' => Auth::user()->id,

                                ]
                            );
                            // ]);
                            // }

                        }
                    }
                }
            } else {
                if (empty($request->check_subject)) {
                    Session::flash('danger', 'Please select atleast one Subject');
                    return redirect()->back();
                }
                if (empty($request->check_exam)) {
                    Session::flash('danger', 'Please select atleast one exam');
                    return redirect()->back();
                }
                if (empty($request->check_mark_attribute)) {
                    Session::flash('danger', 'Please select atleast one attribute');
                    return redirect()->back();
                }
            }
            if ($data) {
                DB::commit();
                Session::flash('success', 'Data inserted Successfully');
                return redirect()->back();
            } else {
                Session::flash('warning', 'Data already exists');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }
    public function studentMarkInputIndex()
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $examList = ['0' => 'Select Exam'] + DB::table('exams')->where('is_trash', '0')->pluck('exam_name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Mark::studentMarkInput.index', compact('classList', 'academicYearList', 'examList', 'currentYear'));
    }

    public function studentMarkInputStore(Request $request)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            if($request->subject_name == 'all'){
                $subjectIds = DB::table('subject_relations')
                    ->leftJoin('subjects','subjects.id','subject_relations.subject_id')
                    ->where('subject_relations.class_id', $request->class_name)
                    ->where('subjects.status', 'active')
                    ->pluck('subjects.id')->toArray();
            }else{
                $subjectIds = explode(',', $request->subject_name);
            }

            foreach ($subjectIds as $sub_key => $sub_id) {
                $attribute = DB::table('mark_configs')
                ->join('mark_attribute', 'mark_attribute.id', 'mark_configs.mark_attribute_id')
                ->where('mark_configs.academic_year_id', $input['academic_year'])->where('mark_configs.class_id', $input['class_name'])
                ->where('mark_configs.exam_id', $input['exam_name'])->where('mark_configs.subject_id', $sub_id)
                ->select('mark_attribute.id as attribute_id')->get();
                foreach ($request->student_id as $key => $value) {
                    if (DB::table('student_marks')->where('academic_year_id', $input['academic_year'])
                        ->where('class_id', $input['class_name'])->where('subject_id', $sub_id)
                        ->where('exam_id', $input['exam_name'])->where('student_id', $value)->doesntExist()
                    ) {

                        $student_info_id = DB::table('student_marks')->insertGetId([
                            'academic_year_id' => $input['academic_year'],
                            'exam_id' => $input['exam_name'],
                            'class_id' => $input['class_name'],
                            'subject_id' => $sub_id,
                            'section_id' => $input['section_name'],
                            'student_id' => $value,
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => Auth::user()->id,
                        ]);
                        foreach ($attribute as $value2) {
                            $data = DB::table('student_marks_details')->insert([
                                'student_info_id' => $student_info_id,
                                'attribute_mark_id' => $value2->attribute_id,
                                'attribute_marks' => !empty($input['is_absent'][$value][$sub_id][$value2->attribute_id][0]) ? 0 : (!empty($input['attribute_mark'][$value][$sub_id][$value2->attribute_id][0]) ? $input['attribute_mark'][$value][$sub_id][$value2->attribute_id][0] : 0),
                                'is_absent' => !empty($input['is_absent'][$value][$sub_id][$value2->attribute_id][0]) ? $input['is_absent'][$value][$sub_id][$value2->attribute_id][0] : 0,
                                'date' => date("Y-m-d"),
                                'status' => 'active',
                                'created_at' => date("Y-m-d h:i:s"),
                                'created_by' => Auth::user()->id,
                            ]);
                        }
                    } else {

                        $studentInfoId = DB::table('student_marks')->where('academic_year_id', $input['academic_year'])
                            ->where('class_id', $input['class_name'])->where('subject_id', $sub_id)
                            ->where('exam_id', $input['exam_name'])->where('student_id', $value)->first();
                        $previousData = DB::table('student_marks_details')->where('student_info_id', $studentInfoId->id)->get();
                        foreach ($previousData as $prevData) {
                            $data = DB::table('student_marks_details_version')->insert([
                                'student_marks_details_id' => $prevData->id,
                                'student_info_id' => $prevData->student_info_id,
                                'attribute_mark_id' => $prevData->attribute_mark_id,
                                'attribute_marks' => $prevData->attribute_marks,
                                'date' => $prevData->date,
                                'status' => $prevData->status,
                                'created_at' => $prevData->created_at,
                                'created_by' => $prevData->created_by,
                                'updated_at' => $prevData->updated_at,
                                'updated_by' => $prevData->updated_by,
                                'is_absent' => $prevData->is_absent,
                            ]);
                        }
                        DB::table('student_marks_details')->where('student_info_id', $studentInfoId->id)->delete();
                        foreach ($attribute as $key => $value2) {
                            $data = DB::table('student_marks_details')->insert([
                                'student_info_id' => $studentInfoId->id,
                                'attribute_mark_id' => $value2->attribute_id,
                                'attribute_marks' => !empty($input['is_absent'][$value][$sub_id][$value2->attribute_id][0]) ? 0 : (!empty($input['attribute_mark'][$value][$sub_id][$value2->attribute_id][0]) ? $input['attribute_mark'][$value][$sub_id][$value2->attribute_id][0] : 0),
                                'is_absent' => !empty($input['is_absent'][$value][$sub_id][$value2->attribute_id][0]) ? $input['is_absent'][$value][$sub_id][$value2->attribute_id][0] : 0,
                                'date' => date("Y-m-d"),
                                'status' => 'active',
                                'created_at' => date("Y-m-d h:i:s"),
                                'created_by' => Auth::user()->id,
                                'updated_at' => date("Y-m-d h:i:s"),
                                'updated_by' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }

            if ($data) {
                DB::commit();
                if (!request()->ajax()) { 
                    Session::flash('success', 'Data inserted Successfully');
                    return redirect()->back();
                }
            } else {
                if (!request()->ajax()) { 
                    Session::flash('warning', 'Data already exists');
                    return redirect()->back();
                }
            }
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }

    // Mark Sheet
    public function markSheetIndex(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $examList = ['0' => 'Select Exam'] + DB::table('exams')->where('is_trash', '0')->pluck('exam_name', 'id')->toArray();
        if ($request->ajax()) {
            $students = [];
            $model = DB::table('contacts')
                ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
                ->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->join('student_marks', 'student_marks.student_id', 'contact_academics.contact_id')
                ->leftJoin('exams', 'exams.id', 'student_marks.exam_id')
                ->where('student_marks.exam_id', $request->examId);
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->whereNot('contacts.status', 'cancel')->groupBy('contacts.id');

            $students = $model->select('contacts.id as contacts_id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'contact_academics.class_roll as class_roll', 'exams.exam_name as exam_name', 'contacts.contact_id as student_id')->get();
            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contacts_id . '" name="contact_id[]" value="' . $row->contacts_id . '" keyValue="' . $row->contacts_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->editColumn('full_name', function ($row) {
                    $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->contacts_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    return $btn;
                })
                ->rawColumns(['checkbox', 'full_name'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Mark::marksheet.index", compact('classList', 'academicYearList', 'request', 'currentYear', 'examList'));
    }
    public function markSheetGenerate(Request $request)
    {
    
        $subArr = $markArr = $attributeMark = $setupMksArr = $isAbsent = [];
        $originalTotalMark = $originalTotalPassMark = $total = 0;
        $contactId = $request->contact_id;
        $studentClassId = $request->student_class_id;
        $sessionId = $request->session_id;
        $attributeId=$request->check_exam;
        $examId = $request->student_exam_id;
        $pageTitle = 'Student Marksheet';
        $yearName = DB::table('academic_years')->where('id', $request->session_id)->first();
        $examName = DB::table('exams')->where('id', $examId)->first();
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
                ->select('student.id', 'student.full_name as student_name', 'student.gender', 'father.full_name as father_name', 'mother.full_name as mother_name', 'classes.name as class_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'sections.name as section_name', 'shifts.name as shift_name', 'student.date_of_birth', 'student.contact_id as student_id', 'contact_academics.registration_no', 'contact_academics.class_id', 'contact_academics.academic_year_id', 'contact_academics.contact_id')
                ->get();
                

                $attributeList = DB::table('mark_configs')->join('mark_attribute', 'mark_attribute.id', 'mark_configs.mark_attribute_id')
                ->where('mark_configs.class_id', $studentClassId)->where('mark_configs.exam_id', $examID)
                ->whereIn('mark_configs.mark_attribute_id',$attributeId)
                ->groupBy('mark_configs.mark_attribute_id')
                ->select('mark_attribute.name as attribute_name', 'mark_attribute.id')->get();
            // dd($attributeList);

            $mark = DB::table('student_marks')
                ->join('subjects', 'subjects.id', 'student_marks.subject_id')
                ->join('student_marks_details', 'student_marks_details.student_info_id', 'student_marks.id')
                ->whereIn('student_marks.student_id', $contactId)
                ->where('student_marks.exam_id', $examId)
                ->where('student_marks.academic_year_id', $sessionId)
                ->where('student_marks.class_id', $studentClassId)
                ->whereIn('student_marks_details.attribute_mark_id',$attributeId)
                ->get();
                
            foreach ($mark as $key => $value) {
                $markArr[$value->student_id][$value->subject_id][$value->attribute_mark_id] = $value->attribute_marks;
                $isAbsent[$value->student_id][$value->subject_id][$value->attribute_mark_id] = $value->is_absent;
                $markArr[$value->student_id][$value->subject_id]['total'] = !empty($markArr[$value->student_id][$value->subject_id]['total']) ? $markArr[$value->student_id][$value->subject_id]['total'] : 0;
                $markArr[$value->student_id][$value->subject_id]['total'] += $value->attribute_marks;
                $setupMarkInfo = DB::table('mark_configs')->where('academic_year_id', $sessionId)->where('class_id', $studentClassId)
                ->where('exam_id', $examId)
                ->where('subject_id', $value->subject_id)
                ->whereIn('mark_attribute_id', $attributeId)
                ->sum('total_mark');
                $setupMksArr[$value->student_id][$value->subject_id]['total'] = !empty($setupMksArr[$value->student_id][$value->subject_id]['total']) ? $setupMksArr[$value->student_id][$value->subject_id]['total'] : 0;
                $setupMksArr[$value->student_id][$value->subject_id]['total'] = $setupMarkInfo;
                $markArr[$value->student_id][$value->subject_id]['percentage'] = ceil(($markArr[$value->student_id][$value->subject_id]['total'] * 100) / (!empty($setupMksArr[$value->student_id][$value->subject_id]['total']) ? $setupMksArr[$value->student_id][$value->subject_id]['total'] : 1));
                $markArr[$value->student_id][$value->subject_id]['grade'] = getGrade($markArr[$value->student_id][$value->subject_id]['percentage']);
                
            }
            $subInfo = DB::table('subjects')->join('subject_relations', 'subject_relations.subject_id', 'subjects.id')
            // ->where('student_marks.academic_year_id',$sessionId)
                ->where('subject_relations.class_id', $studentClassId)
            // ->where('student_marks.exam_id',$examId)
            // ->whereIn('student_marks.student_id',$contactId)
                ->get();

            foreach ($subInfo as $sinfo) {
                $subArr[$sinfo->subject_id]['subject_id'] = $sinfo->subject_id;
                $subArr[$sinfo->subject_id]['sub_code'] = $sinfo->sub_code;
                $subArr[$sinfo->subject_id]['sub_name'] = $sinfo->name;
            }

            return view("Mark::marksheet.allStudentResult", compact('data', 'examID', 'yearName', 'attributeList', 'examName', 'subArr', 'markArr', 'isAbsent'));
        } else {
            Session::flash('danger', 'Please Select a Student First');
            return redirect()->back();
        }
    }

    public function getSubject(Request $request)
    {
        $data = DB::table('subject_relations')
            ->join('subjects', 'subjects.id', 'subject_relations.subject_id')
            ->where('subject_relations.class_id', $request->classId)
            ->select('subjects.*')
            ->get();
        $response = [];
        $response['data'] = '';
        if ($data->isEmpty()) {
            $response['data'] .= '<div class="error ml-4"><h1>Subject Not Found !!</h1> </div>';
        } else {
            $response['data'] .= '<div class="col-md-2">';
            $response['data'] .= '<div class="custom-control custom-checkbox mt-3">';
            $response['data'] .= '<input type="checkbox" class="custom-control-input all-check-box allChecktwo" id="chkbxAll"
                        value="" onclick="return checkAll()">';
            $response['data'] .= '<label class="custom-control-label" for="chkbxAll">Mark All</label>';
            $response['data'] .= '</div></div>';
            foreach ($data as $key => $value) {
                $response['data'] .= '<div class="col-md-2">';
                $response['data'] .= '<div class="custom-control custom-checkbox mt-3">';
                $response['data'] .= '<input type="checkbox" name="check_subject[]" value="' . $value->id . '" class="custom-control-input allChecktwo allCheck all-check-box subject-id" keyValue="' . $value->id . '"  id="subjectId_' . ($value->id) . '" onclick="unCheck(this.id);isChecked();">';
                $response['data'] .= '<label class="custom-control-label" for="subjectId_' . ($value->id) . '">' . $value->name . '</label>';
                $response['data'] .= '</div></div>';
            }
        }

        return $response;
    }

    public function getExam(Request $request)
    {
        $data = DB::table('mark_configs')
            ->join('classes', 'classes.id', '=' ,'mark_configs.class_id')
            ->join('mark_attribute','mark_attribute.id','mark_configs.mark_attribute_id')
            ->where('mark_configs.class_id', $request->classId)
            ->where('mark_configs.exam_id', $request->examId)
            ->groupBy('mark_attribute.name','mark_attribute.id')
            ->get();  
            $response = [];
            $response['data'] = '';
            if ($data->isEmpty()) {
                $response['data'] .= '<div class="error ml-4"><h1> This Exam Atribute Is Not Set !!</h1> </div>';
            } else {
                $response['data'] .= '<div class="col-md-1">';
                $response['data'] .= '<div class="custom-control custom-checkbox mt-0">';
                $response['data'] .= '<input style=" padding-left:0px;" type="checkbox" class="custom-control-input all-check-box" id="chkbxAlltwo"
                            value="" checked onclick="return checkAlltwo()">';
                $response['data'] .= '<label style="padding-top:0px;padding-left:0px;" class="custom-control-label" for="chkbxAlltwo">Select All</label>';
                $response['data'] .= '</div></div>';
                foreach ($data as $key => $value) {
                $response['data'] .= '<div class="col-md-1">';
                $response['data'] .= '<div class="custom-control custom-checkbox mt-0">';
                $response['data'] .= '<div">';
                $response['data'] .= '<input required checked style=" padding-left:0px; padding:0px;margin-left:0px" type="checkbox" name="check_exam[]" value="' . $value->id . '" class="custom-control-input allChecktwo all-check-box subject-id" keyValue="' . $value->id . '"  id="examId_' . ($value->id) . '" onclick="unChecktwo(this.id);isCheckedtwo();">';
                $response['data'] .= '<label style="padding-top:0px; padding-left:0px;" class="custom-control-label" for="examId_' . ($value->id) . '">' . $value->name . '</label>';
                $response['data'] .= '</div">';
                $response['data'] .= '</div></div>';
            }
        }

        return $response;
        
    }

    public function getMarkHeader(Request $request)
    {

        
        $response = $mksArr = $is_absent = [];
        $response['attribute'] = '';
        $response['attribute1'] = '';
        $response['data'] = '';
        $total = $totalMks = 0;
        $student_info = DB::table('contacts')
            ->leftJoin('contact_academics', 'contact_academics.contact_id', 'contacts.id')
            ->where('contact_academics.academic_year_id', $request->yearId)
            ->where('contact_academics.class_id', $request->classId)
            ->where('contacts.status', "active")
            ->where('contact_academics.status', 'active');
        if ($request->sectionId) {
            $student_info = $student_info->where('contact_academics.section_id', $request->sectionId);
        }
        $student_info = $student_info->select('contacts.full_name as student_name', 'contacts.contact_id as SID', 'contact_academics.class_roll', 'contacts.id')
            ->orderByRaw('ISNULL(contact_academics.class_roll),contact_academics.class_roll ASC')
            ->get();

        if($request->subjectId[0] == 'all'){
            $subjectName = DB::table('subject_relations')
            ->leftJoin('subjects','subjects.id','subject_relations.subject_id')
            ->where('subject_relations.class_id', $request->classId)
            ->where('subjects.status', 'active')
            ->get();
            $attribute = DB::table('mark_configs')
            ->join('mark_attribute', 'mark_attribute.id', 'mark_configs.mark_attribute_id')
            ->where('mark_configs.academic_year_id', $request->yearId)->where('mark_configs.class_id', $request->classId)->where('mark_configs.exam_id', $request->examId)->where('mark_configs.status', 'active')
            ->select('mark_attribute.name as mark_name', 'mark_attribute.id', 'mark_configs.total_mark', 'mark_configs.pass_mark', 'mark_configs.subject_id')->get();
        }else{
            $subjectName = DB::table('subjects')->whereIn('subjects.id', $request->subjectId)->where('subjects.status', 'active')->get();     
            $attribute = DB::table('mark_configs')
            ->join('mark_attribute', 'mark_attribute.id', 'mark_configs.mark_attribute_id')
            ->where('mark_configs.academic_year_id', $request->yearId)->where('mark_configs.class_id', $request->classId)->where('mark_configs.exam_id', $request->examId)->whereIn('mark_configs.subject_id', $request->subjectId)->where('mark_configs.status', 'active')
            ->select('mark_attribute.name as mark_name', 'mark_attribute.id', 'mark_configs.total_mark', 'mark_configs.pass_mark', 'mark_configs.subject_id')->get();
        }
        

        

        $response['attribute'] .= '<th class="text-center" rowspan="2" class="serial-no"> SL </th>';
        $response['attribute'] .= '<th  style="width: 10%;" rowspan="2" class="table-checkbox-header-center">';
        $response['attribute'] .= '<div class="custom-control custom-checkbox mt-3">';
        $response['attribute'] .= '<input type="checkbox" class="custom-control-input all-check-box" id="chkbxAll" onclick="return checkAll()">';
        $response['attribute'] .= '<label class="custom-control-label" for="chkbxAll">Mark All</label> </div></th>';
        $response['attribute'] .= '<th class="text-center" rowspan="2"> SID </th>';
        $response['attribute'] .= '<th class="text-center" rowspan="2"> Student Name </th>';
        $response['attribute'] .= '<th class="text-center" rowspan="2"> Roll Number </th>';
        foreach ($subjectName as $key => $value) {
            $attributes = $attribute->where('subject_id', $value->id);
            $total_attributes = count($attributes);
            $response['attribute'] .= '<th class="text-center" colspan="' . $total_attributes + 1 . '"> ' . ($value->name) . ' </th>';
        }

        foreach ($subjectName as $subjectKey => $subjectValue) {
            $attributes = $attribute->where('subject_id', $subjectValue->id);
            $subjectTotal = 0;

            foreach ($attributes as $attributeKey => $attributeValue) {
                $response['attribute1'] .= '<th class="text-center"> ' . ($attributeValue->mark_name) . ' (' . (!empty($attributeValue->total_mark) ? $attributeValue->total_mark : 0) . ') </th>';
                $subjectTotal += $attributeValue->total_mark;
            }

            $response['attribute1'] .= '<th class="text-center"> Total (' . $subjectTotal . ') </th>';
        }

        foreach ($student_info as $key => $value) {
            $tickSign = !empty($mksArr[$value->id]['tick']) ? 'checked' : '';
            $response['data'] .= '<tr>';
            $response['data'] .= '<td>' . ($key + 1) . '</td>';
            $response['data'] .= '<td class="text-center">';
            $response['data'] .= '<div class="custom-control custom-checkbox mt-3">';
            $response['data'] .= '<input type="checkbox" ' . $tickSign . ' name="student_id[]" value="' . $value->id . '" class="custom-control-input allCheck all-check-box" keyValue="' . $value->id . '"  id="checkStudent_' . ($value->id) . '" onclick="unCheck(this.id);isChecked();">';
            $response['data'] .= '<label class="custom-control-label" for="checkStudent_' . ($value->id) . '"></label>';
            $response['data'] .= '</div></td>';
            $response['data'] .= '<td class="text-center"><a href="' . route(app('SID'), $value->id) . '" target="_blank">' . ($value->SID) . '</a></td>';
            $response['data'] .= '<td class="text-center"><a href="' . route(app('studentName'), $value->id) . '" target="_blank">' . ($value->student_name) . '</a></td>';
            $response['data'] .= '<td class="text-center">' . ($value->class_roll) . '</td>';
            $subjectTotalMksArr = [];
            foreach ($subjectName as $subjectKey => $subjectValue) {
                $mksInfo = DB::table('student_marks')
                    ->join('student_marks_details', 'student_marks_details.student_info_id', 'student_marks.id')
                    ->where('student_marks.academic_year_id', $request->yearId)
                    ->where('student_marks.class_id', $request->classId)
                    ->where('student_marks.exam_id', $request->examId)
                    ->where('student_marks.subject_id', $subjectValue->id)
                    ->where('student_marks.student_id', $value->id)
                    ->select('student_marks_details.attribute_marks', 'student_marks_details.attribute_mark_id', 'student_marks_details.is_absent')
                    ->get();
                    
                // Process the fetched marks for the current student and subject
                foreach ($mksInfo as $mksVal) {
                    $mksArr[$value->id][$subjectValue->id][$mksVal->attribute_mark_id] = $mksVal->attribute_marks;
                    $is_absent[$value->id][$subjectValue->id][$mksVal->attribute_mark_id] = $mksVal->is_absent;
                    $mksArr[$value->id][$subjectValue->id]['tick'] = 1;
                }
                $attributes = $attribute->where('subject_id', $subjectValue->id);
                $subjectTotalMks = 0;
                foreach ($attributes as $value2) {
                    $mks = !empty($mksArr[$value->id][$subjectValue->id][$value2->id]) ? $mksArr[$value->id][$subjectValue->id][$value2->id] : 0;
                    $is_absentTick = !empty($is_absent[$value->id][$subjectValue->id][$value2->id]) ? 'checked' : '';
                    $subjectTotalMks += $mks;
                    $response['data'] .= '<td class="text-center"> <input style="width: -webkit-fill-available;" type="number" value="' . $mks . '" class="form-control attribute_mark_' . $value->id . ' mks-input" attrId="' . $value2->id . '" totalMks="' . $value2->total_mark . '" sid="' . $value->id . '" subjectName="' . $subjectValue->id . '" id="writtenMark_' . $subjectValue->id . '_' . $value2->id . '_' . $value->id . '" name="attribute_mark[' . $value->id . '][' . $subjectValue->id . '][' . $value2->id . '][]">
                            <span class="error" id="inputError_'. $subjectValue->id . '_'  . $value->id . '_' . $value2->id . '"> </span> <input type="hidden" value="0" class="error-handle" id="errorHandleId_' . $subjectValue->id . '_' . $value->id . '_' . $value2->id . '" />';
                    $response['data'] .= '<div class="custom-control custom-checkbox mt-3">';
                    $response['data'] .= '<input type="checkbox" ' . $is_absentTick . ' name="is_absent[' . $value->id . '][' . $subjectValue->id . '][' . $value2->id . '][]" value="1" class="custom-control-input all-check-box absent_checkbox" keyValue="' . $value->id . '"  id="checkAbsent_' . $value->id . '_' . $subjectValue->id . '_' . $value2->id . '" onclick="unCheck(this.id);isChecked();">';
                    $response['data'] .= '<label class="custom-control-label" for="checkAbsent_'. ($value->id) . '_' . ($subjectValue->id) . '_' . $value2->id . '">Absent</label>';
                    $response['data'] .= '</div></td>';
                }
                $subjectTotalMksArr[$subjectValue->id] = $subjectTotalMks;
                $response['data'] .= '<td class="text-center"> <input style="width: -webkit-fill-available;" type="text" value="' . $subjectTotalMksArr[$subjectValue->id] . '" class="form-control total_mark"pattern="[0-9.]+" readonly id="totalMark_' . $subjectValue->id . '_' .  $value->id . '"  name="total_mark[' . $value->id . '][' . $subjectValue->id . '][]">
                </td>';
            }
            $totalMks = 0;
            $response['data'] .= '</tr>';
        }

        return $response;
    }

    public function configHistory($id)
    {
        $data = DB::table('mark_configs')
            ->join('subjects', 'subjects.id', 'mark_configs.subject_id')
            ->join('exams', 'exams.id', 'mark_configs.exam_id')
            ->join('mark_attribute', 'mark_attribute.id', 'mark_configs.mark_attribute_id')
            ->where('mark_configs.class_id', $id)
            ->where('mark_configs.status', 'active')
            ->select('mark_configs.id', 'subjects.name as subject_name', 'exams.exam_name as exam_name', 'mark_attribute.name as mark_attribute_name', 'mark_configs.total_mark', 'mark_configs.pass_mark', 'mark_configs.percentage')
            ->orderBy('mark_configs.exam_id')
            ->orderBy('mark_configs.subject_id')
            ->get();
        $className = DB::table('classes')->where('id', $id)->first();
        return view('Mark::markConfig.configView', compact('data', 'className'));
    }

    // Delete config
    public function viewConfigDestroy($id)
    {
        DB::table('mark_configs')->where('id', $id)->update(['status' => 'cancel']);
        return response()->json(['message' => 'Data deleted successfully']);
    }
}
