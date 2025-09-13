<?php

namespace App\Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StudentTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $user;

    // Construct Method
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('shift.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {
            // query builder
            $data = DB::table('student_type')->where('is_trash', 0)->whereNot('status', 'cancel')->get();

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

                    $action = ' <a href="' . route('student.type.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs"data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('student.type.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;

                })->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::studentType.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Create Permission check
        if (is_null($this->user) || !$this->user->can('shift.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        return view('Academic::studentType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Store Permission Check
        if (is_null($this->user) || !$this->user->can('shift.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // check is that studentTypeName has in trash ???
        $studentTypeName = $request->name;
        $findSameId = DB::table('student_type')->where('name', $studentTypeName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Shift Already has in Trash, Please Restore <a href="' . route('student.type.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('student_type')->where(function ($query) {
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

            DB::table('student_type')->insert($data);

            Session::flash('success', 'student type is added successfully !');
            return redirect()->route('student.type.index');

        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Edit permission check
        if (is_null($this->user) || !$this->user->can('shift.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $data = DB::table('student_type')->where('is_trash', 0)->where('id', $id)->first();

        //  return $data;
        return view('Academic::studentType.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Update permission check
        if (is_null($this->user) || !$this->user->can('shift.update')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // check is that studentTypeName has in trash ???
        $studentTypeName = $request->name;
        $findSameId = DB::table('student_type')->where('name', $studentTypeName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Shift Already has in Trash, Please Restore <a href="' . route('student.type.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('student_type')->ignore($id)->where(function ($query) {
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

            DB::table('student_type')->where('id', $id)->update($data);

            Session::flash('success', 'student type is updated successfully !');
            return redirect()->route('student.type.index');

        } catch (\Throwable$e) {
            //throw $th;
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
        // destroy permission check
        if (is_null($this->user) || !$this->user->can('shift.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // Check Shift use any place
        $shiftCheck = count(DB::table('contact_academics')->where('student_type_id', $id)->get());

        if ($shiftCheck < 1) {
            DB::table('student_type')->where('id', $id)->update(['is_trash' => 1]);
            Session::flash('success', 'student type deleted successfully !');
            return redirect()->back();

        }else{
            Session::flash('error', "This student type is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }
    

    // To trash shift
    public function trash(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('shift.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {
            // query builder
            $data = DB::table('student_type')->where('is_trash', 1)->get();

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

                    $action_btn = '<a href="' . route('student.type.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;

                })
                ->rawColumns(['action', 'status'])
                ->make(true);

        }

        return view('Academic::studentType.trash');

    }

    // to restore student Type 
    public function studentTypeRestore($id)
    {
        // destroy permission check
        if (is_null($this->user) || !$this->user->can('shift.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        DB::table('student_type')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "student type Restored Successfully ");
        return redirect()->route('student.type.index');
    }
}
