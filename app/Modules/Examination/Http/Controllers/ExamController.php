<?php

namespace App\Modules\Examination\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Examination\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Exam::leftJoin('exam_types', 'exams.exam_type_id', 'exam_types.id')
                ->leftJoin('academic_years', 'exams.academic_year_id', 'academic_years.id')
                ->select('exam_types.name as exam_type_name', 'academic_years.year as academic_year', 'exams.*')->where('exams.is_trash', 0)->get();

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

                    $action = ' <a href="' . route('exam.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('exam.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;
                })->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('Examination::exam.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $class_list = DB::table('classes')->where('is_trash', 0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->all();
        $academic_year = DB::table('academic_years')->where('is_trash', 0)->latest('id')->pluck('year', 'id')->all();
        $exam_type_list = ['' => 'Select Exam Type'] + DB::table('exam_types')->where('is_trash', 0)->pluck('name', 'id')->all();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        $editTitle = false;
        return view('Examination::exam.create', compact('class_list', 'academic_year', 'exam_type_list', 'currentYear', 'editTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'exam_name' => 'required',
            'status' => 'required',
            'exam_type_id' => 'required',
            'academic_year_id' => 'required',

        ]);
        DB::beginTransaction();
        try {
            $input = $request->all();
            $data = [];
            $errorName = [];
            $exam_name = $request->exam_name;
                foreach ($exam_name as $key => $value) {
                    if (DB::table('exams')->where('academic_year_id', $request->academic_year_id)->where('exam_type_id', $request->exam_type_id)->where('exam_name', $value)->where('status', 'active')->where('is_trash', 0)->doesntExist()) {
                        $data[$key]['exam_name'] = $value;
                        $data[$key]['exam_type_id'] = $request->exam_type_id;
                        $data[$key]['academic_year_id'] = $request->academic_year_id;
                        $data[$key]['status'] = $request->status;
                        $data[$key]['created_at'] = date("Y-m-d h:i:s");
                        $data[$key]['created_by'] = Auth::user()->id;
                        $data[$key]['is_trash'] = 0;
                    } else {
                        array_push($errorName, $value);
                    }
                }
            if (!empty($errorName)) {
                $nameJson = trim(json_encode($errorName), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            } else {
                Session::flash('success', ' No duplicate found !');
            }
            if (!empty($data)) {
                DB::table('exams')->insert($data);
                Session::flash('success', ' Exam name added successfully !');
            }
            DB::commit();

            return redirect('exam');

        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
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
    public function edit($id)
    {

        // $class_list = ['' => 'Select Class'] + DB::table('classes')->where('is_trash', 0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->all();
        $academic_year = ['' => 'Select Year'] + DB::table('academic_years')->where('is_trash', 0)->pluck('year', 'id')->all();
        $exam_type_list = ['' => 'Select Exam Type'] + DB::table('exam_types')->where('is_trash', 0)->pluck('name', 'id')->all();
        $exam = DB::table('exams')->where('is_trash', 0)->where('id', $id)->first();
        $editTitle = true;
        return view('Examination::exam.edit', compact('exam', 'academic_year', 'exam_type_list', 'editTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_name' => 'required',
            'status' => 'required',
            'exam_type_id' => 'required',
            'academic_year_id' => 'required',

        ]);

        try {
            $exam = array();
            $exam['exam_name'] = $request->exam_name[0];
            $exam['exam_type_id'] = $request->exam_type_id;
            $exam['academic_year_id'] = $request->academic_year_id;
            $exam['status'] = $request->status;
            $exam['is_trash'] = 0;
            $exam['updated_at'] = date("Y-m-d h:i:s");
            $exam['updated_by'] = Auth::user()->id;

            DB::table('exams')->where('id', $id)->update($exam);

            Session::flash('success', 'exam is updated successfully !');
            return redirect('exam');
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
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
    public function destroy($id)
    {
        DB::table('exams')->where('id', $id)->update(['is_trash' => 1]);

        Session::flash('success', 'Exam deleted successfully !');
        return redirect()->back();
    }
}
