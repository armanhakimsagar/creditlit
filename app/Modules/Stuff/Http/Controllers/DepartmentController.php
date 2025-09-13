<?php

namespace App\Modules\Stuff\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    // To show all Department data
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('enum_department_types')->where('is_trash', 0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('department.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('department.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
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
        return view('Stuff::department.index');
    }

    // To show Class create page
    public function create()
    {
        $pageTitle = "Add Department";
        return view("Stuff::department.create", compact('pageTitle'));
    }

    // To Store Class Data
    public function store(Request $request)
    {
        // check is that class has in trash ???
        $departmentName = $request->name;
        $findSameId = DB::table('enum_department_types')->where('name', $departmentName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Class Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('enum_department_types')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        try {
            DB::table('enum_department_types')->insert([
                'name' => $request->name,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d h:i:s'),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Department Created Successfully ");
            return redirect()->route('department.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To edit a brand
    public function edit($id)
    {
        $pageTitle = "Edit Department";
        $department = DB::table('enum_department_types')->where('id', $id)->first();
        return view('Stuff::department.edit', compact('pageTitle', 'department'));
    }

    public function update(Request $request, $id)
    {
        // check is that departmentName has in trash ???
        $departmentName = $request->name;
        $findSameId = DB::table('enum_department_types')->where('name', $departmentName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Class Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('enum_department_types', 'name')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            DB::table('enum_department_types')->where('id', $id)->update([
                'name' => $request->name,
                'status' => $request->status,
                'updated_by' => Auth::id(),
                'updated_at' => date('Y-m-d h:i:s'),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Department Updated Successfully ");
            return redirect()->route('department.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(Request $request, $id)
    {
        // Check Group use any place
        $departmentCheck = count(DB::table('contacts')->where('employee_department_id', $id)->get());

        if ($departmentCheck < 1) {
            DB::beginTransaction();
            try {
                DB::table('enum_department_types')->where('id', $id)->update([
                    'is_trash' => '1',
                ]);
                DB::commit();
                Session::flash('success', 'Data Deleted Successfully');
            } catch (\Exception$e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
            return redirect()->route('department.index');
        } else {
            Session::flash('error', "This Department is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To trash department
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('enum_department_types')->where('is_trash', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('department.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
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
        return view('Stuff::department.trash');
    }

    // to restore department
    public function department_restore($id)
    {
        DB::table('enum_department_types')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Department Restored Successfully ");
        return redirect()->route('department.index');
    }

}
