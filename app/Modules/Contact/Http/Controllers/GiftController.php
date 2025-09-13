<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Image;
use Spatie\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use App\Modules\Contact\Models\Gift;

class GiftController extends Controller
{
    // To show all Gift
    public function index(Request $request)
    {
        $data = DB::table('gifts')
            ->where('gifts.is_trash', 0)
            ->leftjoin('gift_type', 'gifts.type', 'gift_type.id')
            ->leftjoin('contacts as customer', 'gifts.bank_id', 'customer.id')
            ->leftjoin('contacts as deliveredMan', 'gifts.delivered_by', 'deliveredMan.id')
            ->leftjoin('key_personnel', 'gifts.key_personnel', 'key_personnel.id')
            ->leftJoin('users', 'gifts.created_by', 'users.id')
            ->select('gifts.id', 'gifts.name as gift_name', 'gifts.cost', 'gifts.date as gift_date', 'gifts.status as status', 'gift_type.name as gift_type_name', 'customer.full_name as customer_name', 'deliveredMan.full_name as customer_name', 'deliveredMan.full_name as deliveredMan_name', 'key_personnel.full_name as keyPersonnel_name', DB::raw('CONCAT(IFNULL(users.first_name,"")," ", IFNULL(users.last_name,""),"") as issued_by'))
            ->orderBy('gifts.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('gift.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('gift.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
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
                ->editColumn('gift_date', function ($row) {
                    return Carbon::createFromFormat('d-m-Y', $row->gift_date)->format('jS M, Y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('Contact::gift.index');
    }

    // To show Gift create page
    public function create()
    {
        $addPage = "Add Gift";
        $type = ['' => 'Select Gift Type'] + DB::table('gift_type')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('gift_type.id', 'gift_type.name as type_name')
            ->pluck('type_name', 'id')
            ->toArray();
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $delivered_by = ['' => 'Select One'] + DB::table('contacts')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->where('contacts.type', 5)
            ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/Mobile: ",IFNULL(contacts.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $branch = ['' => 'At first select a Bank'];
        return view("Contact::gift.create", compact('addPage', 'keyPersonnel', 'type', 'bankId', 'branch', 'delivered_by'));
    }

    //  To store Bank
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $gift = new Gift();
            $gift->name = $request->name;
            $gift->type = $request->type;
            $gift->cost = $request->cost;
            $gift->customer_type = $request->customer_type;
            if ($request->customer_type == 'bank' || $request->customer_type == 'company') {
                $gift->bank_id = $request->bank_id;
            } else if ($request->customer_type == 'branch') {
                $gift->bank_id = $request->branch_id;
            }
            $gift->key_personnel = $request->key_personnel;
            $gift->delivered_by = $request->delivered_by;
            $gift->date = $request->date;
            $gift->status = 'active';
            $gift->created_by = Auth::user()->id;
            $gift->created_at = date("Y-m-d h:i:s");
            $gift->save();
            DB::commit();
            Session::flash('success', "Gift Created Successfully");
            return redirect()->route('gift.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show Gift edit page
    public function edit($id)
    {
        $editPage = "Edit Gift";
        $gift = DB::table('gifts')
            ->where('gifts.id', $id)
            ->where('gifts.is_trash', 0)
            ->first();
        $type = ['' => 'Select Gift Type'] + DB::table('gift_type')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('gift_type.id', 'gift_type.name as type_name')
            ->pluck('type_name', 'id')
            ->toArray();
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $delivered_by = ['' => 'Select One'] + DB::table('contacts')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->where('contacts.type', 5)
            ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/Mobile: ",IFNULL(contacts.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $customer = '';
        $branchId = [];
        $branch = ['' => 'At first select a Bank'];
        if ($gift->customer_type == 'bank') {
            $customer = $gift->bank_id;
        } else if ($gift->customer_type == 'company') {
            $customer = $gift->bank_id;
        } else if ($gift->customer_type == 'branch') {
            $customer = DB::table('contacts as branch')
                ->where('branch.id', $gift->bank_id)
                ->leftjoin('contacts as customer', 'branch.bank_id', 'customer.id')
                ->value('customer.id');
            $branchId = $gift->bank_id;
        }

        if ($gift->customer_type == 'bank') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($gift->customer_type == 'company') {
            $bankId = ['' => 'Select Company'] + DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        } else if ($gift->customer_type == 'branch') {
            $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
                ->where('bank.is_trash', 0)
                ->where('bank.type', 1)
                ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
            $branch = ['' => 'At first select a Bank'] + DB::table('contacts as branch')
                ->where('branch.is_trash', 0)
                ->where('branch.type', 2)
                ->where('branch.bank_id', $customer)
                ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
                ->pluck('full_name', 'id')
                ->toArray();
        }

        return view("Contact::gift.edit", compact('editPage', 'gift', 'keyPersonnel', 'type', 'customer', 'branchId', 'branch', 'bankId', 'delivered_by'));
    }

    // To update Gift data
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $gift = Gift::find($id);
            $gift->name = $request->name;
            $gift->type = $request->type;
            $gift->cost = $request->cost;
            $gift->customer_type = $request->customer_type;
            if ($request->customer_type == 'bank' || $request->customer_type == 'company') {
                $gift->bank_id = $request->bank_id;
            } else if ($request->customer_type == 'branch') {
                $gift->bank_id = $request->branch_id;
            }
            $gift->key_personnel = $request->key_personnel;
            $gift->delivered_by = $request->delivered_by;
            $gift->date = $request->date;
            $gift->status = 'active';
            $gift->created_by = Auth::user()->id;
            $gift->created_at = date("Y-m-d h:i:s");
            $gift->save();
            DB::commit();
            Session::flash('success', "Gift Updated Successfully");
            return redirect()->route('gift.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Gift
    public function destroy($id)
    {
        Gift::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Gift Successfully Removed into Trash ");
        return redirect()->back();
    }
}
