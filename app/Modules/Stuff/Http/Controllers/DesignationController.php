<?php

namespace App\Modules\Stuff\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class DesignationController extends Controller
{
    // To show all designation data
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('enum_employee_types')->where('is_trash', 0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('designation.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('designation.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
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
        return view('Stuff::designation.index');
    }

    // To show designation create page
    public function create()
    {

        $pageTitle = "Add designation";
        return view("Stuff::designation.create", compact('pageTitle'));
    }

    // To Store designation Data
    public function store(Request $request)
    {
        // check is that designation has in trash ???
        $designationName = $request->name;
        $findSameId = DB::table('enum_employee_types')->where('name', $designationName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'DesignationName Already has in Trash, Please Restore <a href="' . route('designation.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('enum_employee_types')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        try {
            DB::table('enum_employee_types')->insert([
                'name' => $request->name,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'created_at' => date('Y-m-d h:i:s'),
                'is_trash' => 0,
            ]);
            Session::flash('success', "designation Created Successfully ");
            return redirect()->route('designation.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To edit a designation
    public function edit($id)
    {
        $pageTitle = "Edit designation";
        $designation = DB::table('enum_employee_types')->where('id', $id)->first();
        return view('Stuff::designation.edit', compact('pageTitle', 'designation'));
    }

    // to update designation 
    public function update(Request $request, $id)
    {
        // check is that designationName has in trash ???
        $designationName = $request->name;
        $findSameId = DB::table('enum_employee_types')->where('name', $designationName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'DesignationAlready has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('enum_employee_types', 'name')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            DB::table('enum_employee_types')->where('id', $id)->update([
                'name' => $request->name,
                'status' => $request->status,
                'updated_at' => date('Y-m-d h:i:s'),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "designation Updated Successfully ");
            return redirect()->route('designation.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy designation
    public function destroy(Request $request, $id)
    {
        // Check designation use any place
        $designationCheck = count(DB::table('contacts')->where('employee_designation_id', $id)->get());

        if ($designationCheck < 1) {
            DB::beginTransaction();
            try {
                DB::table('enum_employee_types')->where('id', $id)->update([
                    'is_trash' => '1',
                ]);
                DB::commit();
                Session::flash('success', 'Data Deleted Successfully');
            } catch (\Exception$e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
            return redirect()->route('designation.index');
        } else {
            Session::flash('error', "This designation is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To trash designation
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('enum_employee_types')->where('is_trash', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('designation.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
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
        return view('Stuff::designation.trash');
    }

    // to restore designation
    public function designation_restore($id)
    {
        DB::table('enum_employee_types')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "designation Restored Successfully ");
        return redirect()->route('designation.index');
    }

}
