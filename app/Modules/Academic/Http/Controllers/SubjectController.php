<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classes;
use App\Modules\Academic\Models\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SubjectController extends Controller
{

    // To show Subject create page
    public function create()
    {
        $pageTitle = "Add Subject";
        return view("Academic::subject.create", compact('pageTitle'));
    }


    // To Store Subject Data
    public function store(Request $request)
    {
        // check is that subject has in trash ???
        $subjectName = $request->name;
        $findSameId = DB::table('subjects')->where('name', $subjectName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Subjects Already has in Trash, Please Restore <a href="' . route('subject.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('subjects')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
            'sub_code' => 'nullable|unique:subjects,sub_code,'
        ]);
        try {
            Subjects::create([
                'name' => $request->name,
                'sub_code' => $request->sub_code,
                'status' => $request->status,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Subject Created Successfully ");
            return redirect()->route('subject.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show all subject data
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('subjects')->where('is_trash', 0)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('subject.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('subject.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
                    return $action_btn;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        return '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        return '<span class="badge badge-danger">Cancel</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('Academic::subject.index');
    }


    // To edit a brand
    public function edit($id)
    {
        $pageTitle = "Edit Subject";
        $subject = Subjects::find($id);
        return view('Academic::subject.edit', compact('pageTitle', 'subject'));
    }

    public function update(Request $request, $id)
    {
        // check is that subject has in trash ???
        $subjectName = $request->name;
        $findSameId = DB::table('subjects')->where('name', $subjectName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Subjects Already has in Trash, Please Restore <a href="' . route('subject.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('subjects', 'name')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
            'sub_code' => 'nullable|unique:subjects,sub_code,'.$id,
        ]);
        try {
            Subjects::where('id', $id)->update([
                'name' => $request->name,
                'sub_code' => $request->sub_code,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Subject Updated Successfully ");
            return redirect()->route('subject.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To destroy Class
    public function destroy($id)
    {
        $subject = Subjects::find($id);
        Subjects::where('id', $id)->update([
            'is_trash' => 1,
        ]);
        Session::flash('success', "Subject Successfully Removed into Trash ");
        return redirect()->back();

    }


    // To show all subject trash data
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('subjects')->where('is_trash', 1)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('subject.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Academic::subject.trash');
    }


    // to restore subject
    public function subject_restore($id)
    {
        DB::table('subjects')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Subject Restored Successfully ");
        return redirect()->route('subject.index');
    }


    /***************************
      Subject assign controller 
     ***************************/
    public function assignCreate(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();

        if ($request->ajax()) {
            $classId = $request->class_id;
            $subject = DB::table('subjects')->where('is_trash', 0)->where('status', 'active')->get();
            foreach ($subject as $data) {
                $data->classId = $classId;
            }

            return DataTables::of($subject)
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
                ->addColumn('checkbox', function ($row) {
                    if (DB::table('subject_relations')->where('subject_id', $row->id)->where('class_id', $row->classId)->exists()) {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" checked="" id="checkSection_' . $row->id . '" name="subject_check[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    } else {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="subject_check[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    }
                    return $btn;
                })
                ->rawColumns(['checkbox', 'status'])
                ->make(true);
        }
        return view('Academic::subjectAssign.subjectAssign', compact('classList', 'request'));
    }

    public function assignStore(Request $request)
    {
        // return $request;
        try {
            $data = [];
            if (!empty($request->subject_check)) {
                foreach ($request->subject_check as $key => $value) {
                    $data[$key]['class_id'] = $request->class_name;
                    $data[$key]['subject_id'] = $value;
                    $data[$key]['status'] = "active";
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;
                }
            }

            DB::table('subject_relations')->where('class_id', $request->class_name)->delete();
            if (!empty($data)) {
                DB::table('subject_relations')->insert($data);
                Session::flash('success', 'subject assigned successfully !');
            } else {
                Session::flash('success', 'subject unassigned successfully !');
            }

            return redirect('subject-asign-create?class_id=' . $request->class_name);
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

}
