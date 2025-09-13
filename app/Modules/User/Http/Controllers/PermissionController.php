<?php

namespace App\Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Artisan;

class PermissionController extends Controller
{
    // To show all Role
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::all();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('permission.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a>';
                    return $action_btn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('User::permission.index');
    }

    // To create Role
    public function create()
    {
        $pageTitle = "Add Permission";
        return view("User::permission.create", compact('pageTitle'));
    }

    // To Store Role
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|unique:permissions,name',
            'user_show_name' => 'required|unique:permissions,user_show_name',
        ]);
        DB::beginTransaction();
        try {
            Permission::create([
                'name' => $request->route_name,
                'user_show_name' => $request->user_show_name,
                'group_name' => $request->group_name,
            ]);
            DB::commit();
            Artisan::call('cache:clear');
            Session::flash('success', "Permission Created Successfully ");
            return redirect()->route('permission.create');
        } catch (\Exception$e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To Edit ROLE
    public function edit($id)
    {
        $pageTitle = "Edit Permission";
        $permission = Permission::find($id);
        return view("User::permission.edit", compact('pageTitle', 'permission'));
    }


     // To Update Role
     public function update(Request $request, $id)
     {
         $validated = $request->validate([
             'route_name' => 'required|unique:roles,name,'.$id,
             'user_show_name' => 'required|unique:permissions,user_show_name,'.$id,
         ]);
         DB::beginTransaction();
         try {
             Permission::where('id', $id)->update([
                 'name' => $request->route_name,
                 'user_show_name' => $request->user_show_name,
                 'group_name' => $request->group_name,
             ]);
             DB::commit();
             Artisan::call('cache:clear');
             Session::flash('success', "Permission Updated Successfully ");
             return redirect()->back();
         } catch (\Exception$e) {
             DB::rollback();
             Session::flash('danger', $e->getMessage());
             return redirect()->back();
         }
     }

}
