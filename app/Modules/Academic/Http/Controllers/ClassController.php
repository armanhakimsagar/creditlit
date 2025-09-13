<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ClassController extends Controller
{
    // To show all class data
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('classes')->where('is_trash', 0)
                ->orderByRaw('ISNULL(classes.weight),classes.weight ASC')
                ->get();
            return Datatables::of($data)
                ->startsWithSearch()
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('class.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('class.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
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
        return view('Academic::class.index');
    }

    // To show Class create page
    public function create()
    {
        $pageTitle = "Add Class";
        return view("Academic::class.create", compact('pageTitle'));
    }

    // To Store Class Data
    public function store(Request $request)
    {
        // check is that class has in trash ???
        $className = $request->name;
        $findSameId = DB::table('classes')->where('name', $className)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Class Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('classes')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
            'weight' => 'required|unique:classes,weight',
        ]);
        try {
            Classes::create([
                'name' => $request->name,
                'weight' => $request->weight,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Class Created Successfully ");
            return redirect()->route('class.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To edit a brand
    public function edit($id)
    {
        $pageTitle = "Edit Class";
        $class = Classes::find($id);
        return view('Academic::class.edit', compact('pageTitle', 'class'));
    }

    public function update(Request $request, $id)
    {
        // check is that class has in trash ???
        $className = $request->name;
        $findSameId = DB::table('classes')->where('name', $className)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Class Already has in Trash, Please Restore <a href="' . route('class.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('classes', 'name')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
            'weight' => 'required|unique:classes,weight,' . $id,
        ]);
        try {
            Classes::where('id', $id)->update([
                'name' => $request->name,
                'weight' => $request->weight,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Class Updated Successfully ");
            return redirect()->route('class.index');
        } catch (\Exception$e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Class
    public function destroy($id)
    {
        $classCheck1 = count(DB::table('contact_academics')->where('class_id', $id)->get());
        $classCheck2 = count(DB::table('contact_payable_items')->where('class_id', $id)->get());
        $classCheck3 = count(DB::table('contact_payable_items_version')->where('class_id', $id)->get());
        $classCheck4 = count(DB::table('exams')->where('class_id', $id)->get());
        $classCheck5 = count(DB::table('generate_payable_list')->where('class_id', $id)->get());
        $classCheck6 = count(DB::table('monthly_class_item')->where('class_id', $id)->get());
        $classCheck7 = count(DB::table('pricing')->where('class_id', $id)->get());
        $classCheck8 = count(DB::table('section_relations')->where('class_id', $id)->get());
        // total count of relation other table
        $classCheck = $classCheck1 + $classCheck2 + $classCheck3 + $classCheck4 + $classCheck5 + $classCheck6 + $classCheck7 + $classCheck8 ;

        if ($classCheck < 1) {
            $class = Classes::find($id);
            Classes::where('id', $id)->update([
                'is_trash' => 1,
            ]);
            Session::flash('success', "Class Successfully Removed into Trash ");
            return redirect()->back();
        } else {
            Session::flash('error', "This class is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // To trash class
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('classes')->where('is_trash', 1)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('class.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
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
        return view('Academic::class.trash');
    }

    // to restore class
    public function class_restore($id)
    {
        DB::table('classes')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Class Restored Successfully ");
        return redirect()->route('class.index');
    }
}
