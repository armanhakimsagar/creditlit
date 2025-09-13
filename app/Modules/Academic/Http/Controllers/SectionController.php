<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    // Section Index
    public function index(Request $request)
    {

        if ($request->ajax()) {
            // query builder
            $section_list = DB::table('sections')->where('is_trash', 0)->whereNot('status', 'cancel')->get();

            return DataTables::of($section_list)
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

                    $action = ' <a href="' . route('section.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('section.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::section.index');
    }

    public function create()
    {
        return view('Academic::section.create');
    }

    public function store(Request $request)
    {

        // check is that class has in trash ???
        $sectionName = $request->name;
        $findSameId = DB::table('sections')->where('name', $sectionName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Section Already has in Trash, Please Restore <a href="' . route('section.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('sections')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $section = array();
            $section['name'] = $request->name;
            $section['status'] = $request->status;
            $section['created_at'] = date("Y-m-d h:i:s");
            $section['created_by'] = Auth::user()->id;
            $section['is_trash'] = $request->is_trash;

            DB::table('sections')->insert($section);

            Session::flash('success', 'section is added successfully !');
            return redirect('section');

        } catch (\Exception $e) {
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

        $section = DB::table('sections')->where('is_trash', 0)->where('id', $id)->first();
        // return $section;
        return view('Academic::section.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        // check is that section has in trash ???
        $sectionName = $request->name;
        $findSameId = DB::table('sections')->where('name', $sectionName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Section Already has in Trash, Please Restore <a href="' . route('section.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        // return $request;
        $validated = $request->validate([
            'name' => Rule::unique('sections')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        try {
            $section = array();
            $section['name'] = $request->name;
            $section['status'] = $request->status;
            $section['updated_at'] = date("Y-m-d h:i:s");
            $section['updated_by'] = Auth::user()->id;

            DB::table('sections')->where('id', $id)->update($section);

            Session::flash('success', 'section is added successfully !');
            return redirect('section');

        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $sectionCheck1 = count(DB::table('contact_academics')->where('section_id', $id)->get());
        $sectionCheck2 = count(DB::table('section_relations')->where('section_id', $id)->get());
        // $sectionCheck3 = count(DB::table('student_imp')->where('section_id', $id)->get());
        // total count of relation other table
        $sectionCheck = $sectionCheck1 + $sectionCheck2;

        if ($sectionCheck < 1) {
            DB::table('sections')->where('id', $id)->update(['is_trash' => 1]);
            Session::flash('success', 'section deleted successfully !');
            return redirect()->back();
        } else {
            Session::flash('error', "This Section is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To trash section
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            // query builder
            $section_list = DB::table('sections')->where('is_trash', 1)->get();

            return DataTables::of($section_list)
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
                    $action_btn = '<a href="' . route('section.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::section.trash');
    }

    // to restore class
    public function section_restore($id)
    {

        DB::table('sections')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Section Restored Successfully ");
        return redirect()->route('section.index');
    }

}
