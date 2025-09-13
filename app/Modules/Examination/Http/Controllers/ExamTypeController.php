<?php

namespace App\Modules\Examination\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ExamTypeController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $data = DB::table('exam_types')->where('is_trash', 0)->get();

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

                    $action = ' <a href="' . route('exam_type.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('exam_type.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Examination::examType.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $editTitle = false;
        return view('Examination::examType.create',compact('editTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $validated = $request->validate([
     
            'status' => 'required',
            'name' => 'required',

        ]);
        DB::beginTransaction();
        try {
            $data= [];
            $errorName= [];
            $name = $request->name;
            foreach ($name as $key => $value) {
                if (DB::table('exam_types')->where('name', $value)->where('status', 'active')->where('is_trash', 0)->doesntExist()) {

                    $data[$key]['name']= $value;
                    $data[$key]['status'] =$request->status;
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;
                    $data[$key]['is_trash'] = 0;
                }
                else{
                    array_push($errorName,$value);
                }

                
            }
            if(!empty($errorName)){
                $nameJson = trim(json_encode($errorName), '[]');
                Session::flash('danger', $nameJson.' already exists !');
            }
            else{
                Session::flash('success', ' No duplicate found');
            }
            if (!empty($data)) {
                DB::table('exam_types')->insert($data);
                Session::flash('success', ' Exam Type added successfully !');
            }

            DB::commit();  
            return redirect('exam-type');

        } 
        catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
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
        $editTitle = true;
        $data = DB::table('exam_types')->where('is_trash',0)->where('id',$id)->first();
                        
        //  return $data;
        return view('Examination::examType.edit',compact('data','editTitle'));
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

        // check is that Exam Type has in trash ???
        $examTypeName = $request->name;
        $findSameId = DB::table('exam_types')->where('name', $examTypeName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Exam Type Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('exam_types')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $data = array();
            $data['name'] = $request->name[0];
            $data['status'] = $request->status;
            $data['updated_at'] = date("Y-m-d h:i:s");
            $data['updated_by'] = Auth::user()->id;

            DB::table('exam_types')->where('id', $id)->update($data);

            Session::flash('success', 'Exam Type is updated successfully !');
            return redirect('exam-type');

        } catch (\Throwable$e) {
            //throw $th;
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

        // Check Exam Type use any place
        $examTypeCheck = count(DB::table('exams')->where('exam_type_id', $id)->where('is_trash',0)->get());


        if ($examTypeCheck < 1) {
            DB::table('exam_types')->where('id', $id)->update(['is_trash' => 1]);
        } else {
            Session::flash('error', "This Exam Type is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }

        Session::flash('success', 'Exam type deleted successfully !');
        return redirect()->back();
    }

    // To trash examType
    public function trash(Request $request)
    {

        if ($request->ajax()) {
            // query builder
            $data = DB::table('exam_types')->where('is_trash', 1)->get();
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

                    $action_btn = '<a href="' . route('exam_type.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Examination::examType.trash');

    }

    // to restore class
    public function exam_type_restore($id)
    {
        DB::table('exam_types')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Exam Type Restored Successfully ");
        return redirect()->route('exam_type.index');
    }
}
