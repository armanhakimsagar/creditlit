<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::where('is_trash', 0)->whereNot('status', 'cancel')->select('id', 'name', 'status')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        $status = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        $status = '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Cancel</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('group.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs " title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('group.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("Academic::group.index");
    }

    public function create()
    {
        return view("Academic::group.create");
    }

    public function store(Request $request)
    {

        // check is that group has in trash ???
        $groupName = $request->name;
        $findSameId = DB::table('groups')->where('name', $groupName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Group Already has in Trash, Please Restore <a href="' . route('group.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('groups')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Group::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            DB::commit();
            Session::flash('success', 'Data Stored Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('group.index');
    }
    public function edit($id)
    {

        $data = Group::where('id', $id)->first();
        return view('Academic::group.edit', compact('data'));
    }

    public function delete(Request $request, $id)
    {

        // Check Group use any place
        $groupCheck = count(DB::table('contact_academics')->where('group_id', $id)->get());

        if ($groupCheck < 1) {
            DB::beginTransaction();
            try {
                Group::where('id', $id)->update([
                    'is_trash' => '1',
                ]);
                DB::commit();
                Session::flash('success', 'Data Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
            return redirect()->route('group.index');
        } else {
            Session::flash('error', "This Group is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {

        // check is that group has in trash ???
        $groupName = $request->name;
        $findSameId = DB::table('groups')->where('name', $groupName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Group Already has in Trash, Please Restore <a href="' . route('group.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('groups')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Group::where('id', $id)->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            DB::commit();
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('group.index');
    }

    // To trash group
    public function trash(Request $request)
    {

        if ($request->ajax()) {
            $data = Group::where('is_trash', 1)->select('id', 'name', 'status')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        $status = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        $status = '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Cancel</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('group.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("Academic::group.trash");
    }

    // to restore group
    public function group_restore($id)
    {

        DB::table('groups')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Group Restored Successfully ");
        return redirect()->route('group.index');
    }
}
