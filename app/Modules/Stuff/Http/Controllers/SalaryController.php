<?php

namespace App\Modules\Stuff\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SalaryController extends Controller
{
    // To index salaryItemIndex
    public function salaryItemIndex(Request $request)
    {
        if ($request->ajax()) {
            $salaryItem = DB::table('salary_item')
                ->where('is_trash', 0)
                ->get();
            return DataTables::of($salaryItem)
                ->addIndexColumn()
                ->editColumn('type', function ($row) {
                    if ($row->type == '1') {
                        return 'Gross';
                    } elseif ($row->type == '2') {
                        return 'Deduction';
                    } elseif ($row->type == '3') {
                        return 'Allowance';
                    } else {
                        return 'Not Selected';
                    }
                })
                ->editColumn('amount_type', function ($row) {
                    if ($row->amount_type == 'flat') {
                        return 'Flat';
                    } elseif ($row->amount_type == 'percentage') {
                        return 'Percentage';
                    } else {
                        return 'Not Selected';
                    }
                })
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

                    $action = ' <a href="' . route('salary.item.edit', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('salary.item.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;
                })->rawColumns(['action', 'status', 'product_type'])
                ->make(true);
        }

        return view('Stuff::salaryItem.index');
    }

    public function salaryItemCreate()
    {
        $pageTitle = 'Add Salary Item';
        $editTitle = false;
        $categorys = [null => 'Select Category'] + DB::table('user_product_relation')->where('is_trash', 0)->where('type', 'category')->whereNot('status', 'cancel')->pluck('name', 'id')->toArray();

        return view('Stuff::salaryItem.create', compact('pageTitle', 'categorys', 'editTitle'));
    }

    // To store salary item
    public function salaryItemStore(Request $request)
    {

        $input = $request->all();
        $validated = $request->validate([

            'status' => 'required',
            'name' => 'required',

        ]);
        DB::beginTransaction();
        try {
            $name = $request->name;
            $errorName = [];
            foreach ($name as $key => $value) {
                if ($value != null) {
                    if (DB::table('salary_item')->where('name', $value)->where('type', $request->type[$key])->where('amount_type', $request->amount_type[$key])->where('is_trash', 0)->where('status', 'active')->doesntExist()) {
                        if (isset($input['is_basic'])) {
                            DB::table('salary_item')->where('is_trash', 0)->where('status', 'active')->update([
                                'is_basic' => '0',
                            ]);
                            $item_id = DB::table('salary_item')->insertGetId([
                                'name' => $value,
                                'type' => $request->type[$key],
                                'amount_type' => $request->amount_type[$key],
                                'amount' => $request->amount[$key],
                                'status' => $request->status,
                                'is_trash' => 0,
                                'is_basic' => 1,
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        } else {
                            $item_id = DB::table('salary_item')->insertGetId([
                                'name' => $value,
                                'type' => $request->type[$key],
                                'amount_type' => $request->amount_type[$key],
                                'amount' => $request->amount[$key],
                                'status' => $request->status,
                                'is_trash' => 0,
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                        }

                        Session::flash('success', 'Salary item is added successfully !');
                    } else {
                        array_push($errorName, $value);
                    }
                }

            }
            if (!empty($errorName)) {
                $nameJson = trim(json_encode($errorName), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            } else {
                Session::flash('success', ' No duplicate found !');
            }

            DB::commit();
            return redirect()->route('salary.item.index');

        } catch (\Throwable$th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }

    }

    // To edit salary item
    public function salaryItemEdit($id)
    {
        $pageTitle = 'Edit Salary Item';
        $editTitle = true;
        $item = DB::table('salary_item')->where('id', $id)->first();

        return view('Stuff::salaryItem.edit', compact('pageTitle', 'item', 'editTitle'));
    }

    // To update salary item
    public function salaryItemUpdate(Request $request, $id)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            if (DB::table('salary_item')->whereNot('id', $id)->where('name', $input['name'][0])->where('type', $request->type[0])->where('amount_type', $request->amount_type[0])->where('is_trash', 0)->where('status', 'active')->doesntExist()) {
                if (isset($input['is_basic'])) {
                    DB::table('salary_item')->where('is_trash', 0)->where('status', 'active')->update([
                        'is_basic' => '0',
                    ]);
                    DB::table('salary_item')->where('id', $id)->update([
                        'name' => $input['name'][0],
                        'type' => $request->type[0],
                        'amount_type' => $request->amount_type[0],
                        'amount' => $request->amount[0],
                        'status' => $request->status,
                        'is_trash' => 0,
                        'is_basic' => 1,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    DB::table('salary_item')->where('id', $id)->update([
                        'name' => $input['name'][0],
                        'type' => $request->type[0],
                        'amount_type' => $request->amount_type[0],
                        'amount' => $request->amount[0],
                        'status' => $request->status,
                        'is_trash' => 0,
                        'is_basic' => 0,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                DB::commit();
                Session::flash('success', 'Salary Item Updated Successfully');
            } else {
                Session::flash('danger', 'Same item or short name exist');
                return redirect()->back();
            }
        } catch (\Exception$e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
        return redirect()->route('salary.item.index');
    }

    // To destroy salary item
    public function salaryItemDestroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('salary_item')->where('id', $id)->update([
                'is_trash' => '1',
            ]);
            DB::commit();
            Session::flash('success', 'Salary Item Deleted Successfully');
        } catch (\Exception$e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
        return redirect()->route('salary.item.index');
    }
}
