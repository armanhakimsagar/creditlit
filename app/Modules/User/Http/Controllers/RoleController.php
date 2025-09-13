<?php

namespace App\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    // To show all Role
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::where('is_trash', 0)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('role.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('role.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
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
        return view('User::role.index');
    }

    // To create Role
    public function create()
    {
        $pageTitle = "Add Role";
        $all_permissions = Permission::all();
        $groupName = DB::table('permissions')
            ->select('group_name')
            ->groupBy('group_name')
            ->get();
        return view("User::role.create", compact('pageTitle', 'groupName','all_permissions'));
    }

    // To Store Role
    public function store(Request $request)
    {
        // check is that class has in trash ???
        $roleName = $request->name;
        $findSameId = DB::table('roles')->where('name', $roleName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'roles Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => 'required', Rule::unique('classes')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
        ]);
        try {
            $role = Role::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            $permission = $request->input('permissions');

            if (!empty($permission)) {
                $role->syncPermissions($permission);
            }
            Session::flash('success', "Roles Created Successfully ");
            return redirect()->route('role.create');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To Edit ROLE
    public function edit($id)
    {
        $pageTitle = "Edit Role";
        $groupName = DB::table('permissions')
            ->select('group_name')
            ->groupBy('group_name')
            ->get();
        $role = Role::findById($id);
        $all_permissions = Permission::all();

        return view("User::role.edit", compact('pageTitle', 'groupName', 'role','all_permissions'));
    }

    public function update(Request $request, $id)
    {
        // check is that class has in trash ???
        $roleName = $request->name;
        $findSameId = DB::table('roles')->where('name', $roleName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'roles Already has in Trash, Please Restore <a href="' . route('role.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => 'required', Rule::unique('roles')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
        ]);
        $role = Role::findById($id);
        try {
            Role::where('id', $role->id)->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            $permissions = $request->input('permissions');

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }

            session()->flash('success', 'Role has been updated !!');
            return back();
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Role
    public function destroy($id)
    {
        // Check Role use any place
        $roleCheck = count(DB::table('users')->where('roles_id', $id)->get());

        if ($roleCheck < 1) {
            $role = Role::findById($id);
            if (!is_null($role)) {
                Role::where('id', $id)->update([
                    'is_trash' => 1,
                ]);
            }
            Session::flash('success', "Role Successfully Removed into Trash ");
            return redirect()->back();
        } else {
            Session::flash('error', "This Role is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To role trash
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::where('is_trash', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('role.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
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
        return view('User::role.trash');
    }

    // to restore role
    public function role_restore($id)
    {
        DB::table('roles')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Role Restored Successfully ");
        return redirect()->route('role.index');
    }
}
