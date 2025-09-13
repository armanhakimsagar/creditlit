<?php

namespace App\Modules\Announcement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Announcement\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class HolidayController extends Controller
{

    // To show all class data
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('holiday')->where('is_trash', 0)
                ->orderByRaw('ISNULL(holiday.from_date),holiday.from_date ASC')
                ->get();
            return Datatables::of($data)
                ->startsWithSearch()
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('holiday.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('holiday.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
                    return $action_btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Announcement::holiday.index');
    }

    // To show Class create page
    public function create()
    {
        $pageTitle = "Add Holiday";
        return view("Announcement::holiday.create", compact('pageTitle'));
    }

    // To Store Class Data
    public function store(Request $request)
    {
        // check is that class has in trash ???
        $input=$request->all();
        $title = $request->title;
        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d');

        $findSameId = DB::table('holiday')->where('title', $title)->where('from_date', $from_date)->where('to_date', $to_date)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Holiday Already has in Trash, Please Restore <a href="' . route('holiday.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'title' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        try {
            Holiday::create([
                'title' => $request->title,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'details' => strip_tags($request->principal_details_in_certificate),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Holiday Created Successfully ");
            return redirect()->route('holiday.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To edit a brand
    public function edit($id)
    {
        $pageTitle = "Edit Holiday";
        $holiday = Holiday::find($id);
        return view('Announcement::holiday.edit', compact('pageTitle', 'holiday'));
    }

    public function update(Request $request, $id)
    {
        // check is that class has in trash ???
        $title = $request->title;
        $from_date = Carbon::createFromFormat('Y-m-d', $request->from_date)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('Y-m-d', $request->to_date)->format('Y-m-d');

        $findSameId = DB::table('holiday')->where('title', $title)->where('from_date', $from_date)->where('to_date', $to_date)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Holiday Already has in Trash, Please Restore <a href="' . route('holiday.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'title' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        try {
            Holiday::where('id', $id)->update([
                'title' => $request->title,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'details' => strip_tags($request->principal_details_in_certificate),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Holiday Updated Successfully ");
            return redirect()->route('holiday.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Class
    public function destroy($id)
    {

        $class = Holiday::find($id);
        Holiday::where('id', $id)->update([
            'is_trash' => 1,
        ]);
        Session::flash('success', "Holiday Successfully Removed into Trash ");
        return redirect()->back();
    }

    // To trash class
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('holiday')->where('is_trash', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('holiday.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Announcement::holiday.trash');
    }

    // to restore class
    public function holiday_restore($id)
    {
        DB::table('holiday')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Holiday Restored Successfully ");
        return redirect()->route('holiday.index');
    }
}
