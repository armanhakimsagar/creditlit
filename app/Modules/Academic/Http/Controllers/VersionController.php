<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class VersionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $version_list = DB::table('versions')->where('is_trash', 0)->whereNot('status', 'cancel')->get();

            return DataTables::of($version_list)
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

                    $action = ' <a href="' . route('version.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('version.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::version.index');

    }


    public function create()
    {
        return view('Academic::version.create');
    }


    public function store(Request $request)
    {
        // check is that version has in trash ???
        $versionName = $request->name;
        $findSameId = DB::table('versions')->where('name', $versionName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Shift Already has in Trash, Please Restore <a href="' . route('version.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('versions')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $version = array();
            $version['name'] = $request->name;
            $version['status'] = $request->status;
            $version['created_at'] = date("Y-m-d h:i:s");
            $version['created_by'] = Auth::user()->id;
            $version['is_trash'] = $request->is_trash;

            DB::table('versions')->insert($version);

            Session::flash('success', 'version is added successfully !');
            return redirect('version');

        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $version = DB::table('versions')->where('is_trash', 0)->where('id', $id)->first();

        //  return $version;
        return view('Academic::version.edit', compact('version'));
    }


    public function update(Request $request, $id)
    {
        // check is that version has in trash ???
        $versionName = $request->name;
        $findSameId = DB::table('versions')->where('name', $versionName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Shift Already has in Trash, Please Restore <a href="' . route('version.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('versions')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $version = array();
            $version['name'] = $request->name;
            $version['status'] = $request->status;
            $version['updated_at'] = date("Y-m-d h:i:s");
            $version['updated_by'] = Auth::user()->id;

            DB::table('versions')->where('id', $id)->update($version);

            Session::flash('success', 'version is updated successfully !');
            return redirect('version');

        } catch (\Throwable$e) {
            //throw $th;
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }


    public function destroy($id)
    {
        // Check Version use any place
        $versionCheck = count(DB::table('contact_academics')->where('version_id', $id)->get());

        if ($versionCheck < 1) {
            DB::table('versions')->where('id', $id)->update(['is_trash' => 1]);
            Session::flash('success', 'version deleted successfully !');
            return redirect()->back();
        }else{
            Session::flash('error', "This Version is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To trash version
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $version_list = DB::table('versions')->where('is_trash', 1)->get();

            return DataTables::of($version_list)
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
                    $action_btn = '<a href="' . route('version.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::version.trash');

    }

    // to restore class
    public function version_restore($id)
    {
        DB::table('versions')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Version Restored Successfully ");
        return redirect()->route('version.index');
    }
}
