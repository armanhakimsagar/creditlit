<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class SectionAsignController extends Controller
{

    public function create(Request $request)
    {
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList =  DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();

        if ($request->ajax()) {
            $classId = $request->class_id;
            $academicYearId = $request->academicYearId;
            $section = DB::table('sections')->where('is_trash', 0)->where('status', 'active')->get();
            foreach ($section as $data) {
                $data->classId = $classId;
                $data->academicYearId = $academicYearId;
            }

            return DataTables::of($section)
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
                ->addColumn('checkbox', function ($row) {
                    if (DB::table('section_relations')->where('section_id', $row->id)->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->exists()) {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" checked="" id="checkSection_' . $row->id . '" name="section_check[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    } else {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="section_check[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    }
                    return $btn;
                })
                ->rawColumns(['checkbox', 'status'])
                ->make(true);
        }
        return view('Academic::sectionAsign.sectionAssign', compact('classList', 'academicYearList', 'request', 'currentYear'));
    }

    public function store(Request $request)
    {
        // return $request;
        try {
            $data = [];
            if (!empty($request->section_check)) {
                foreach ($request->section_check as $key => $value) {
                    $data[$key]['class_id'] = $request->class_name;
                    $data[$key]['academic_year_id'] = $request->academic_year;
                    $data[$key]['section_id'] = $value;
                    $data[$key]['status'] = "active";
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;
                }
            }

            DB::table('section_relations')->where('class_id', $request->class_name)->where('academic_year_id', $request->academic_year)->delete();
            if (!empty($data)) {
                DB::table('section_relations')->insert($data);
                Session::flash('success', 'section assigned successfully !');
            } else {
                Session::flash('success', 'section unassigned successfully !');
            }

            return redirect('section-asign-create?academic_year_id=' . $request->academic_year . '&&class_id=' . $request->class_name);
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
