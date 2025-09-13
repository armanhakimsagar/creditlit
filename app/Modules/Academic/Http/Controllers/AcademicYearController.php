<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AcademicYear::where('is_trash', 0)->whereNot('status', 'cancel')->select('id', 'year', 'start_date', 'end_date', 'status')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('start_date', function ($row) {
                    $startDate = date('d-m-Y', strtotime($row->start_date));
                    return $startDate;
                })
                ->editColumn('end_date', function ($row) {
                    $endDate = date('d-m-Y', strtotime($row->end_date));
                    return $endDate;
                })
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
                    $btn = ' <a href="' . route('academic.year.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "><i class="fas fa-edit"></i></a>
                    <a href="' . route('academic.year.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['start_date', 'end_date', 'status', 'action'])
                ->make(true);
        }
        return view("Academic::academicYear.index");
    }
    public function create()
    {
        return view("Academic::academicYear.create");
    }
    public function store(Request $request)
    {
        // check is that transport has in trash ???
        $yearName = $request->year;
        $findSameId = DB::table('academic_years')->where('year', $yearName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'This Academic year already has in Trash, Please Restore <a href="' . route('academic.year.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'year' => Rule::unique('academic_years')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'start_date' => 'required|unique:academic_years,start_date',
            'end_date' => 'required|unique:academic_years,end_date',
            'status' => 'required',
        ]);

        $input = $request->all();
        if (isset($input['is_current'])) {
            $input['is_current'] = 1;
        } else {
            $input['is_current'] = 0;
        }
        DB::beginTransaction();
        try {
            if ($input['is_current'] == 1) {
                AcademicYear::where('is_trash', 0)->where('status', 'active')->update([
                    'is_current' => '0',
                ]);
            }
            AcademicYear::create([
                'year' => $request->year,
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'end_date' => date('Y-m-d', strtotime($request->end_date)),
                'status' => $request->status,
                'is_current' => $input['is_current'],
            ]);
            DB::commit();
            Session::flash('success', 'Data Stored Successfully');
        } catch (\Exception$e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('academic.year.index');
    }
    public function edit($id)
    {
        $data = AcademicYear::where('id', $id)->first();
        return view('Academic::academicYear.edit', compact('data'));
    }

    public function delete(Request $request, $id)
    {
        $academicYearCheck1 = count(DB::table('contact_academics')->where('academic_year_id', $id)->get());
        $academicYearCheck2 = count(DB::table('contact_payable_items')->where('academic_year_id', $id)->get());
        $academicYearCheck3 = count(DB::table('contact_payable_items_version')->where('academic_year_id', $id)->get());
        $academicYearCheck4 = count(DB::table('exams')->where('academic_year_id', $id)->get());
        $academicYearCheck5 = count(DB::table('generate_payable_list')->where('academic_year_id', $id)->get());
        $academicYearCheck6 = count(DB::table('monthly_class_item')->where('academic_year_id', $id)->get());
        $academicYearCheck7 = count(DB::table('pricing')->where('academic_year_id', $id)->get());
        $academicYearCheck8 = count(DB::table('section_relations')->where('academic_year_id', $id)->get());
        // total count of relation other table
        $academicYearCheck = $academicYearCheck1 + $academicYearCheck2 + $academicYearCheck3 + $academicYearCheck4 + $academicYearCheck5;

        if ($academicYearCheck < 1) {
            DB::beginTransaction();
            try {
                AcademicYear::where('id', $id)->update([
                    'is_trash' => '1',
                    'is_current' => '0',
                ]);
                DB::commit();
                Session::flash('success', 'Data Deleted Successfully');
            } catch (\Exception$e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
            return redirect()->route('academic.year.index');
        }else{
            Session::flash('error', "This Academic Year is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }
    public function update(Request $request, $id)
    {
        // check is that transport has in trash ???
        $yearName = $request->year;
        $findSameId = DB::table('academic_years')->where('year', $yearName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'This Academic year already has in Trash, Please Restore <a href="' . route('academic.year.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'year' => Rule::unique('academic_years')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'start_date' => Rule::unique('academic_years')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'end_date' => Rule::unique('academic_years')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        $input = $request->all();
        if (isset($input['is_current'])) {
            $input['is_current'] = 1;
        } else {
            $input['is_current'] = 0;
        }
        DB::beginTransaction();
        try {
            if ($input['is_current'] == 1) {
                AcademicYear::where('is_trash', 0)->where('status', 'active')->update([
                    'is_current' => '0',
                ]);
            }
            AcademicYear::where('id', $id)->update([
                'year' => $request->year,
                'start_date' => date('Y-m-d', strtotime($request->start_date)),
                'end_date' => date('Y-m-d', strtotime($request->end_date)),
                'status' => $request->status,
                'is_current' => $input['is_current'],
            ]);
            DB::commit();
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception$e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('academic.year.index');
    }

    // To trash Academic Year
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = AcademicYear::where('is_trash', 1)->select('id', 'year', 'start_date', 'end_date', 'status')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('start_date', function ($row) {
                    $startDate = date('d-m-Y', strtotime($row->start_date));
                    return $startDate;
                })
                ->editColumn('end_date', function ($row) {
                    $endDate = date('d-m-Y', strtotime($row->end_date));
                    return $endDate;
                })
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
                    $action_btn = '<a href="' . route('academic.year.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['start_date', 'end_date', 'status', 'action'])
                ->make(true);
        }
        return view("Academic::academicYear.trash");
    }

    // to restore academic_years
    public function academic_year_restore($id)
    {
        DB::table('academic_years')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Acadmic Year Restored Successfully ");
        return redirect()->route('academic.year.index');
    }
}
