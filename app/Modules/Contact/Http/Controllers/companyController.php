<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Image;
use Spatie\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use App\Modules\Contact\Models\Contact;

class companyController extends Controller
{
    // To show all Company
    public function index(Request $request)
    {
        $data = DB::table('contacts as company')
            ->where('company.is_trash', 0)
            ->where('company.type', 3)
            ->leftjoin('key_personnel', 'company.key_personnel_id', 'key_personnel.id')
            ->select('company.id', 'company.full_name as company_name', 'company.cp_phone_no as company_phone', 'company.cp_email as company_email', 'company.status', 'company.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email')
            ->orderBy('company.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('selected.company.order.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-bars"></i>
                     </button>
                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             <a class="dropdown-item" href="' . route('company.edit', [$row->id]) . '" target="_blank">Edit Company</a>
                             <a class="dropdown-item" href="' . route('add.company.price', [$row->id]) . '" target="_blank">Company Wise Pricing</a>
                             <a class="dropdown-item" href="' . $url . '">All Order</a>
                             <a class="dropdown-item" href="' . route('company.delete', [$row->id]) . '" id="delete">Delete</a>
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
        return view('Contact::company.index');
    }

    // To show Bank create page
    public function create()
    {
        $addPage = "Add Company";
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::company.create", compact('addPage', 'keyPersonnel'));
    }

    //  To store Bank
    public function store(Request $request)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $company = new Contact();
            $company->full_name = $request->full_name;
            $company->cp_phone_no = $request->cp_phone_no;
            $company->cp_email = $request->cp_email;
            $company->address = $request->address;
            $company->status = $request->status;
            $company->type = 3;
            $company->key_personnel_id = $request->key_personnel;
            $company->save();
            DB::commit();
            Session::flash('success', "Company Created Successfully");
            return redirect()->route('company.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show company edit page
    public function edit($id)
    {
        $editPage = "Edit Company";
        $company = DB::table('contacts')
            ->where('contacts.id', $id)
            ->where('contacts.is_trash', 0)
            ->where('contacts.type', 3)
            ->first();
        $keyPersonnel = DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view("Contact::company.edit", compact('editPage', 'company', 'keyPersonnel'));
    }

    // To update Company data
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $company = Contact::find($id);
            $company->full_name = $request->full_name;
            $company->cp_phone_no = $request->cp_phone_no;
            $company->cp_email = $request->cp_email;
            $company->address = $request->address;
            $company->status = $request->status;
            $company->type = 3;
            $company->key_personnel_id = $request->key_personnel;
            $company->save();
            DB::commit();
            Session::flash('success', "Company Updated Successfully");
            return redirect()->route('company.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Company
    public function destroy($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Company Successfully Removed into Trash ");
        return redirect()->back();
    }


    // To trash Company 
    public function trash(Request $request)
    {
        $data = DB::table('contacts as company')
            ->where('company.is_trash', 1)
            ->where('company.type', 3)
            ->leftjoin('key_personnel', 'company.key_personnel_id', 'key_personnel.id')
            ->select('company.id', 'company.full_name as company_name', 'company.cp_phone_no as company_phone', 'company.cp_email as company_email', 'company.status', 'company.address', 'key_personnel.full_name as keyPersonnel_name', 'key_personnel.cp_phone_no as keyPersonnel_phone', 'key_personnel.cp_email as keyPersonnel_email')
            ->orderBy('company.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('company.restore', [$row->id]) . '" class="btn btn-danger btn-sm company_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a>';
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
        return view('Contact::company.trash');
    }

    // to restore Company
    public function companyRestore($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 0,
            'status' => 'active',
        ]);
        Session::flash('success', "Company Restored Successfully ");
        return redirect()->route('company.trash');
    }


    //Add Company Pricing 
    public function addCompanyPricing($id)
    {
        $id = $id;
        $itemArr = DB::table('products')->where('status', 'active')->get();
        $priceArrList = DB::table('pricing')->pluck('price', 'product_id')->toArray();
        $totalCountry = count(DB::table('pricing')
            ->join('products', 'products.id', 'pricing.product_id')
            ->where('pricing.status', 'active')
            ->select('pricing.price', 'products.name as item_name')
            ->orderBy('pricing.class_id', 'asc')->get());
        return view('Contact::company.company_price_create', compact('itemArr', 'priceArrList', 'totalCountry', 'id'));
    }


    // Branch Pricing Store
    public function companyPricingStore(Request $request, $id)
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
            Session::flash('success', 'Company Price Setup Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }


    // Selected Company order
    public function selectedCompanyOrder(Request $request)
    {
        $companyId = ['' => 'Select Company'] + DB::table('contacts as company')
            ->where('company.is_trash', 0)
            ->where('company.type', 3)
            ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
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
        return view('Contact::company.all_order', compact('request', 'companyId', 'data'));
    }

    // Selected company order filter
    public function selectedCompanyOrderFilter(Request $request)
    {
        $url = 'selected-company-order?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }
}
