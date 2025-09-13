<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $data = DB::table('shifts')->where('is_trash', 0)->whereNot('status', 'cancel')->get();

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

                    $action = ' <a href="' . route('shift.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs"data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('shift.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::shift.index');

    }

    // To Create Shift
    public function create()
    {
        return view('Academic::shift.create');
    }

    // To Store Shift
    public function store(Request $request)
    {
        // check is that shift has in trash ???
        $shiftName = $request->name;
        $findSameId = DB::table('shifts')->where('name', $shiftName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Shift Already has in Trash, Please Restore <a href="' . route('shift.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('shifts')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $data = array();
            $data['name'] = $request->name;
            $data['status'] = $request->status;
            $data['created_at'] = date("Y-m-d h:i:s");
            $data['created_by'] = Auth::user()->id;

            DB::table('shifts')->insert($data);

            Session::flash('success', 'shift is added successfully !');
            return redirect('shift');

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

    // To Edit Shift
    public function edit($id)
    {
        $data = DB::table('shifts')->where('is_trash', 0)->where('id', $id)->first();

        //  return $data;
        return view('Academic::shift.edit', compact('data'));
    }


    // To update shift
    public function update(Request $request, $id)
    {
        // check is that shift has in trash ???
        $shiftName = $request->name;
        $findSameId = DB::table('shifts')->where('name', $shiftName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Shift Already has in Trash, Please Restore <a href="' . route('shift.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('shifts')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $data = array();
            $data['name'] = $request->name;
            $data['status'] = $request->status;
            $data['updated_at'] = date("Y-m-d h:i:s");
            $data['updated_by'] = Auth::user()->id;
            $data['is_trash'] = $request->is_trash;

            DB::table('shifts')->where('id', $id)->update($data);

            Session::flash('success', 'shift is updated successfully !');
            return redirect('shift');

        } catch (\Throwable$e) {
            //throw $th;
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }


    // To destroy shift
    public function destroy($id)
    {
        // Check Shift use any place
        $shiftCheck = count(DB::table('contact_academics')->where('shift_id', $id)->get());

        if ($shiftCheck < 1) {
            DB::table('shifts')->where('id', $id)->update(['is_trash' => 1]);
            Session::flash('success', 'shift deleted successfully !');
            return redirect()->back();

        }else{
            Session::flash('error', "This shift is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }
    

    // To trash shift
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $data = DB::table('shifts')->where('is_trash', 1)->get();

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

                    $action_btn = '<a href="' . route('shift.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;

                })
                ->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::shift.trash');

    }

    // to restore shift
    public function shift_restore($id)
    {
        DB::table('shifts')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "shift Restored Successfully ");
        return redirect()->route('shift.index');
    }
}
