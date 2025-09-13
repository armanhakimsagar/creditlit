<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Image;
use Spatie\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;
use App\Modules\Contact\Models\Contact;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    // To show all Supplier
    public function index(Request $request)
    {
        $data = DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->leftjoin('key_personnel', 'supplier.key_personnel_id', 'key_personnel.id')
            ->select('supplier.id', 'supplier.full_name as supplier_name', 'supplier.cp_phone_no as supplier_phone', 'supplier.cp_email as supplier_email', 'supplier.status', 'supplier.address')
            ->orderBy('supplier.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('selected.supplier.order.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                     aria-haspopup="true" aria-expanded="false">
                     <i class="fa fa-bars"></i>
                     </button>
                         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                             <a class="dropdown-item" href="' . route('supplier.edit', [$row->id]) . '" target="_blank">Edit Supplier</a>
                             <a class="dropdown-item" href="' . route('add.supplier.price', [$row->id]) . '" target="_blank">Supplier Wise Pricing</a>
                             <a class="dropdown-item" href="' . $url . '">All Order</a>
                             <a class="dropdown-item" href="' . route('supplier.delete', [$row->id]) . '" id="delete">Delete</a>
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
        return view('Contact::supplier.index');
    }

    // To show Supplier create page
    public function create()
    {
        $addPage = "Add Supplier";
        return view("Contact::supplier.create", compact('addPage'));
    }

    //  To store Supplier
    public function store(Request $request)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $supplier = new Contact();
            $supplier->full_name = $request->full_name;
            $supplier->cp_phone_no = $request->cp_phone_no;
            $supplier->cp_email = $request->cp_email;
            $supplier->address = $request->address;
            $supplier->status = $request->status;
            $supplier->type = 4;
            $supplier->save();
            DB::commit();
            Session::flash('success', "Supplier Created Successfully");
            return redirect()->route('supplier.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show Supplier edit page
    public function edit($id)
    {
        $editPage = "Edit Supplier";
        $supplier = DB::table('contacts')
            ->where('contacts.id', $id)
            ->where('contacts.is_trash', 0)
            ->where('contacts.type', 4)
            ->first();
        return view("Contact::supplier.edit", compact('editPage', 'supplier'));
    }

    // To update supplier data
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            $supplier = Contact::find($id);
            $supplier->full_name = $request->full_name;
            $supplier->cp_phone_no = $request->cp_phone_no;
            $supplier->cp_email = $request->cp_email;
            $supplier->address = $request->address;
            $supplier->status = $request->status;
            $supplier->type = 4;
            $supplier->save();
            DB::commit();
            Session::flash('success', "Supplier Updated Successfully");
            return redirect()->route('supplier.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Supplier
    public function destroy($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Supplier Successfully Removed into Trash ");
        return redirect()->back();
    }


    // To trash Supplier
    public function trash(Request $request)
    {
        $data = DB::table('contacts as supplier')
            ->where('supplier.is_trash', 1)
            ->where('supplier.type', 4)
            ->leftjoin('key_personnel', 'supplier.key_personnel_id', 'key_personnel.id')
            ->select('supplier.id', 'supplier.full_name as supplier_name', 'supplier.cp_phone_no as supplier_phone', 'supplier.cp_email as supplier_email', 'supplier.status', 'supplier.address')
            ->orderBy('supplier.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('supplier.restore', [$row->id]) . '" class="btn btn-danger btn-sm supplier_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a>';
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
        return view('Contact::supplier.trash');
    }

    // to restore Supplier
    public function supplierRestore($id)
    {
        Contact::where('id', $id)->update([
            'is_trash' => 0,
            'status' => 'active',
        ]);
        Session::flash('success', "Supplier Restored Successfully ");
        return redirect()->route('supplier.trash');
    }


    //Add Supplier Pricing 
    public function addSupplierPricing($id)
    {
        $id = $id;
        $itemArr = DB::table('products')->where('status', 'active')->get();
        $priceArrList = DB::table('pricing')->pluck('price', 'product_id')->toArray();
        $totalCountry = count(DB::table('pricing')
            ->join('products', 'products.id', 'pricing.product_id')
            ->where('pricing.status', 'active')
            ->select('pricing.price', 'products.name as item_name')
            ->orderBy('pricing.class_id', 'asc')->get());
        return view('Contact::supplier.supplier_price_create', compact('itemArr', 'priceArrList', 'totalCountry', 'id'));
    }


    // Supplier Pricing Store
    public function supplierPricingStore(Request $request, $id)
    {
        $input = $request->all();
        $price = $input['price'];
        DB::beginTransaction();
        try {
            foreach ($price as $product_id => $val) {
                if (!empty($val)) {
                    DB::table('supplier_pricing')->updateOrInsert(
                        [
                            'product_id' => $product_id,
                            'dealer_id' => $id,
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
            Session::flash('success', 'Supplier Price Setup Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }


    // Selected supplier order
    public function selectedSupplierOrder(Request $request)
    {
        $supplierId = ['' => 'Select Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
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
                $datam->where('orders.supplier_id', $request->customer_id);
            }

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
            }

            $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.order_status')
                ->orderBy('orders.id', 'ASC')
                ->get();
        }
        return view('Contact::supplier.all_order', compact('request', 'supplierId', 'data'));
    }

    // Selected supplier order filter
    public function selectedSupplierOrderFilter (Request $request)
    {
        $url = 'selected-supplier-order?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }
}
