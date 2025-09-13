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

class BankController extends Controller
{
    // To show all Bank
    public function index(Request $request)
    {
        $data = DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->leftjoin('key_personnel', 'bank.key_personnel_id', 'key_personnel.id')
            ->select('bank.id', 'bank.full_name as bank_name', 'bank.cp_phone_no as bank_phone', 'bank.cp_email as bank_email', 'bank.status', 'bank.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email', 'bank.bank_type')
            ->orderBy('bank.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('selected.bank.order.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-bars"></i>
                     </button>
                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             <a class="dropdown-item" href="' . route('bank.edit', [$row->id]) . '" target="_blank">Edit Bank</a>
                             <a class="dropdown-item" href="' . route('add.bank.price', [$row->id]) . '" target="_blank">Bank Wise Pricing</a>
                             <a class="dropdown-item" href="' . $url . '">All Order</a>
                             <a class="dropdown-item" href="' . route('bank.delete', [$row->id]) . '" id="delete">Delete</a>
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
                ->editColumn('bank_type', function ($row) {
                    if ($row->bank_type == 'centralized') {
                        return 'Centralized';
                    } elseif ($row->bank_type == 'decentralized') {
                        return 'Decentralized';
                    }
                })
                ->rawColumns(['action', 'status', 'bank_type'])
                ->make(true);
        }
        return view('Contact::bank.index');
    }

    // To show Bank create page
    public function create()
    {
        $addPage = "Add Bank";
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::bank.create", compact('addPage', 'keyPersonnel'));
    }

    //  To store Bank
    public function store(Request $request)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $bank = new Contact();
            $bank->full_name = $request->full_name;
            $bank->cp_phone_no = $request->cp_phone_no;
            $bank->cp_email = $request->cp_email;
            $bank->address = $request->address;
            $bank->status = $request->status;
            $bank->type = 1;
            $bank->key_personnel_id = $request->key_personnel;
            $bank->bank_type = $request->bank_type;
            $bank->save();
            DB::commit();
            Session::flash('success', "Bank Created Successfully");
            return redirect()->route('bank.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show Bank edit page
    public function edit($id)
    {
        $editPage = "Edit Bank";
        $bank = DB::table('contacts')
            ->where('contacts.id', $id)
            ->where('contacts.is_trash', 0)
            ->where('contacts.type', 1)
            ->first();
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::bank.edit", compact('editPage', 'bank', 'keyPersonnel'));
    }

    // To update bank data
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $bank = Contact::find($id);
            $bank->full_name = $request->full_name;
            $bank->cp_phone_no = $request->cp_phone_no;
            $bank->cp_email = $request->cp_email;
            $bank->address = $request->address;
            $bank->status = $request->status;
            $bank->bank_type = $request->bank_type;
            $bank->type = 1;
            $bank->key_personnel_id = $request->key_personnel;
            $bank->save();
            DB::commit();
            Session::flash('success', "Bank Updated Successfully");
            return redirect()->route('bank.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Bank
    public function destroy($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Bank Successfully Removed into Trash ");
        return redirect()->back();
    }


    // To trash keyPersonnel
    public function trash(Request $request)
    {
        $data = DB::table('contacts as bank')
            ->where('bank.is_trash', 1)
            ->where('bank.type', 1)
            ->leftjoin('key_personnel', 'bank.key_personnel_id', 'key_personnel.id')
            ->select('bank.id', 'bank.full_name as bank_name', 'bank.cp_phone_no as bank_phone', 'bank.cp_email as bank_email', 'bank.status', 'bank.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email')
            ->orderBy('bank.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('bank.restore', [$row->id]) . '" class="btn btn-danger btn-sm bank_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a>';
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
        return view('Contact::bank.trash');
    }

    // to restore Bank
    public function bankRestore($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 0,
            'status' => 'active',
        ]);
        Session::flash('success', "Bank Restored Successfully ");
        return redirect()->route('bank.trash');
    }


    //Add Bank Pricing 
    public function addBankPricing($id)
    {
        $id = $id;
        $itemArr = DB::table('products')->where('status', 'active')->get();
        $priceArrList = DB::table('pricing')->pluck('price', 'product_id')->toArray();
        $totalCountry = count(DB::table('pricing')
            ->join('products', 'products.id', 'pricing.product_id')
            ->where('pricing.status', 'active')
            ->select('pricing.price', 'products.name as item_name')
            ->orderBy('pricing.class_id', 'asc')->get());
        $affectedBranch = DB::table('contacts as branch')
            ->where('bank.is_trash', 0)
            ->leftJoin('contacts as bank', 'branch.bank_id', '=', 'bank.id')
            ->where('branch.bank_id', $id)
            ->where('branch.price_same_with_main_bank', 1)
            ->select('branch.id', 'branch.full_name as branch_name')
            ->orderBy('branch.id', 'ASC')
            ->get();
        return view('Contact::bank.bank_price_create', compact('itemArr', 'priceArrList', 'totalCountry', 'id', 'affectedBranch'));
    }


    // Bank Pricing Store
    public function bankPricingStore(Request $request, $id)
    {
        $input = $request->all();
        $price = $input['price'];
        $affectedBranch = DB::table('contacts as branch')
            ->where('branch.is_trash', 0)
            ->leftJoin('contacts as bank', 'branch.bank_id', '=', 'bank.id')
            ->where('branch.bank_id', $id)
            ->where('branch.price_same_with_main_bank', 1)
            ->select('branch.id', 'branch.full_name as branch_name')
            ->orderBy('branch.id', 'ASC')
            ->get();
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

            foreach ($affectedBranch as $key => $value) {
                foreach ($price as $product_id => $val) {
                    if (!empty($val)) {
                        DB::table('customer_pricing')->updateOrInsert(
                            [
                                'product_id' => $product_id,
                                'customer_id' => $value->id,
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
            }

            DB::commit();
            Session::flash('success', 'Bank Price Setup Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }



    // Selected bank order
    public function selectedCompanyOrder(Request $request)
    {
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
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
        return view('Contact::bank.all_order', compact('request', 'bankId', 'data'));
    }

    // Selected bank order filter
    public function selectedBankOrderFilter(Request $request)
    {
        $url = 'selected-bank-order?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }
}
