<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TransportController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transport::where('is_trash', 0)->whereNot('status', 'cancel')->select('id', 'name', 'status')->get();
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
                    $btn = ' <a href="' . route('transport.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('transport.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("Academic::transport.index");
    }
    public function create()
    {

        return view("Academic::transport.create");
    }
    public function store(Request $request)
    {
        // check is that transport has in trash ???
        $transportName = $request->name;
        $findSameId = DB::table('transports')->where('name', $transportName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Transport Already has in Trash, Please Restore <a href="' . route('transport.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('transports')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Transport::create([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            DB::commit();
            Session::flash('success', 'Data Stored Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('transport.index');
    }
    public function edit($id)
    {
        $data = Transport::where('id', $id)->first();
        return view('Academic::transport.edit', compact('data'));
    }

    public function delete(Request $request, $id)
    {
        // Check Group use any place
        $transportCheck = count(DB::table('contact_academics')->where('transport_id', $id)->get());

        if ($transportCheck < 1) {
            DB::beginTransaction();
            try {
                Transport::where('id', $id)->update([
                    'is_trash' => '1',
                ]);
                DB::commit();
                Session::flash('success', 'Data Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
            return redirect()->route('transport.index');
        } else {
            Session::flash('error', "This Transport is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        // check is that transport has in trash ???
        $transportName = $request->name;
        $findSameId = DB::table('transports')->where('name', $transportName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Transport Already has in Trash, Please Restore <a href="' . route('transport.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }
        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('transports')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            Transport::where('id', $id)->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);
            DB::commit();
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('transport.index');
    }

    // To trash transport
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = Transport::where('is_trash', 1)->select('id', 'name', 'status')->get();
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
                    $action_btn = '<a href="' . route('transport.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("Academic::transport.trash");
    }

    // to restore transport
    public function transport_restore($id)
    {

        DB::table('transports')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Tranport Restored Successfully ");
        return redirect()->route('transport.index');
    }
}
