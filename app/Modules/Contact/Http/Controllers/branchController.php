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
use App\Modules\Contact\Models\Contact;

class branchController extends Controller
{
    // To show all Branch
    public function index(Request $request)
    {
        $data = DB::table('contacts as branch')
            ->where('branch.is_trash', 0)
            ->where('branch.type', 2)
            ->leftjoin('key_personnel', 'branch.key_personnel_id', 'key_personnel.id')
            ->leftJoin('contacts as bank', 'branch.bank_id', '=', 'bank.id')
            ->select('branch.id', 'branch.full_name as branch_name', 'bank.full_name as bank_name', 'branch.cp_phone_no as branch_phone', 'branch.cp_email as branch_email', 'branch.status', 'branch.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email')
            ->orderBy('branch.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('selected.branch.order.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false">
                         <i class="fa fa-bars"></i>
                         </button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                 <a class="dropdown-item" href="' . route('branch.edit', [$row->id]) . '" target="_blank">Edit Branch</a>
                                 <a class="dropdown-item" href="' . route('add.branch.price', [$row->id]) . '" target="_blank">Branch Wise Pricing</a>
                                 <a class="dropdown-item" href="' . $url . '">All Order</a>
                                 <a class="dropdown-item" href="' . route('branch.delete', [$row->id]) . '">Delete</a>
                             </div>';
                    return $btn;
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
        return view('Contact::branch.index');
    }

    // To show Branch create page
    public function create()
    {
        $addPage = "Add Branch";
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::branch.create", compact('addPage', 'keyPersonnel', 'bankId'));
    }

    //  To store Bank
    public function store(Request $request)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        if(isset($request->price_same_with_main_bank)){
            $price_same_with_main_bank = $request->price_same_with_main_bank;
        }else{
            $price_same_with_main_bank = 0;
        }

        DB::beginTransaction();
        try {
            $branch = new Contact();
            $branch->full_name = $request->full_name;
            $branch->cp_phone_no = $request->cp_phone_no;
            $branch->cp_email = $request->cp_email;
            $branch->address = $request->address;
            $branch->status = $request->status;
            $branch->type = 2;
            $branch->bank_id = $request->bank_id;
            $branch->key_personnel_id = $request->key_personnel;
            $branch->price_same_with_main_bank = $request->price_same_with_main_bank;
            $branch->save();
            DB::commit();
            Session::flash('success', "Branch Created Successfully");
            return redirect()->route('branch.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show Bank edit page
    public function edit($id)
    {
        $editPage = "Edit Branch";
        $branch = DB::table('contacts')
            ->where('contacts.id', $id)
            ->where('contacts.is_trash', 0)
            ->where('contacts.type', 2)
            ->first();
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::branch.edit", compact('editPage', 'branch', 'keyPersonnel', 'bankId'));
    }

    // To update branch data
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);


        if(isset($request->price_same_with_main_bank)){
            $price_same_with_main_bank = $request->price_same_with_main_bank;
        }else{
            $price_same_with_main_bank = 0;
        }

        DB::beginTransaction();
        try {
            $branch = Contact::find($id);
            $branch->full_name = $request->full_name;
            $branch->cp_phone_no = $request->cp_phone_no;
            $branch->cp_email = $request->cp_email;
            $branch->address = $request->address;
            $branch->status = $request->status;
            $branch->type = 2;
            $branch->bank_id = $request->bank_id;
            $branch->key_personnel_id = $request->key_personnel;
            $branch->price_same_with_main_bank = $request->price_same_with_main_bank;
            $branch->save();
            DB::commit();
            Session::flash('success', "Branch Updated Successfully");
            return redirect()->route('branch.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Branch
    public function destroy($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Branch Successfully Removed into Trash ");
        return redirect()->back();
    }


    // To trash branch
    public function trash(Request $request)
    {
        $data = DB::table('contacts as branch')
            ->where('branch.is_trash', 1)
            ->where('branch.type', 2)
            ->leftjoin('key_personnel', 'branch.key_personnel_id', 'key_personnel.id')
            ->select('branch.id', 'branch.full_name as branch_name', 'branch.cp_phone_no as branch_phone', 'branch.cp_email as branch_email', 'branch.status', 'branch.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email')
            ->orderBy('branch.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('branch.restore', [$row->id]) . '" class="btn btn-danger btn-sm bank_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a>';
                    return $btn;
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
        return view('Contact::branch.trash');
    }

    // to restore Branch
    public function branchRestore($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 0,
            'status' => 'active',
        ]);
        Session::flash('success', "Branch Restored Successfully ");
        return redirect()->route('branch.trash');
    }


    // get Bank type dependent on Bank ID
    public function getBankType(Request $request)
    {
        $data = DB::table('contacts')
            ->where('id', $request->bankId)
            ->where('type', 1)
            ->where('is_trash', '0')->first();
        return response()->json($data);
    }


    //Add Branch Pricing 
    public function addBranchPricing($id)
    {
        $id = $id;
        $mainBankHasConnect = DB::table('contacts as branch')
            ->where('branch.id', $id)
            ->leftJoin('contacts as bank', 'branch.bank_id', '=', 'bank.id')
            ->where('branch.price_same_with_main_bank', 1)
            ->select('branch.id', 'branch.full_name as branch_name', 'bank.full_name as bank_name')
            ->orderBy('branch.id', 'ASC')
            ->first();
        // echo "<pre>";
        // print_r($mainBankHasConnect);
        // exit();
        $itemArr = DB::table('products')->where('status', 'active')->get();
        $priceArrList = DB::table('pricing')->pluck('price', 'product_id')->toArray();
        $totalCountry = count(DB::table('pricing')
            ->join('products', 'products.id', 'pricing.product_id')
            ->where('pricing.status', 'active')
            ->select('pricing.price', 'products.name as item_name')
            ->orderBy('pricing.class_id', 'asc')->get());
        return view('Contact::branch.branch_price_create', compact('itemArr', 'priceArrList', 'totalCountry', 'id', 'mainBankHasConnect'));
    }


    // Branch Pricing Store
    public function branchPricingStore(Request $request, $id)
    {
        $input = $request->all();
        $price = $input['price'];
        DB::beginTransaction();
        try {
            foreach ($price as $product_id => $val) {
                if (!empty($val)) {
                    DB::table('customer_pricing')->updateOrInsert(
                            [
                                'product_id' => $product_id,
                                'customer_id' => $id,
                            ],
                            [
                                'price' => $val,
                                'status' => 'active',
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                }
            }

            DB::commit();
            Session::flash('success', 'Branch Price Setup Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }


    // Selected bank order
    public function selectedBranchOrder(Request $request)
    {
        $branchId = ['' => 'Select Branch'] + DB::table('contacts as branch')
            ->where('branch.is_trash', 0)
            ->where('branch.type', 2)
            ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $customer_id = '';
        $from_date = 0;
        $to_date = 0;
        $data = [];
        if ($request->search == 'true') {
            $datam = DB::table('orders')
                ->where('orders.is_trash', 0)
                ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
                ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
                ->where('supplier.type', 4)
                ->leftjoin('products as country', 'orders.country_id', 'country.id');

            if ($request->customer_id) {
                $datam->where('orders.bank_id', $request->customer_id);
            }

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
            }

            $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.order_status')
                ->orderBy('orders.id', 'ASC')
                ->get();
        }
        return view('Contact::branch.all_order', compact('request', 'branchId', 'data'));
    }

    // Selected bank order filter
    public function selectedBranchOrderFilter(Request $request)
    {
        $url = 'selected-branch-order?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }
}
