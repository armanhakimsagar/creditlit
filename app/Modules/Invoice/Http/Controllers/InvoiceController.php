<?php

namespace App\Modules\Invoice\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\PDF;

class InvoiceController extends Controller
{

    // To show all Invoice
    public function indexInvoice(Request $request)
    {
        $datam = DB::table('generateinvoices')
            ->where('generateinvoices.is_trash', 0)
            ->leftjoin('contacts as customer', 'generateinvoices.customer_id', 'customer.id')
            ->leftjoin('users', 'users.id', 'generateinvoices.created_by');

        // echo "<pre>";
        // print_r($datam);
        // exit();
        if ($request->customer_type) {
            $datam->where('generateinvoices.customer_type', $request->customer_type);
        }

        if ($request->customer_id) {
            $datam->where('generateinvoices.customer_id', $request->customer_id);
        }

        if ($request->paymentStatus) {
            $datam->where('generateinvoices.paymentStatus', $request->paymentStatus);
        }

        if (!empty($request->to_date) && !empty($request->from_date)) {
            $searchToDate = Carbon::createFromFormat('d-m-Y', $request->to_date)->format('d F Y');
            $searchFromDate = Carbon::createFromFormat('d-m-Y', $request->from_date)->format('d F Y');
            $datam->whereBetween('generateinvoices.invoiceDate', [$searchFromDate, $searchToDate]);
        }

        $data = $datam->select('generateinvoices.id', 'generateinvoices.invoiceNo', 'generateinvoices.invoiceDate', 'generateinvoices.customer_id', 'generateinvoices.customer_type', 'generateinvoices.paymentStatus', 'generateinvoices.paymentDate',  'generateinvoices.total_amount', 'generateinvoices.vat', 'customer.full_name as customer_name', DB::raw('CONCAT(users.first_name, " ", users.last_name) AS user_name'))
            ->orderBy('generateinvoices.id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('details', function ($row) {
                    $btn = '<a class="btn btn-outline-success btn-xs" href="' . route('invoice.details', [$row->id]) . '" target="_blank"><i class="fa fa-info" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->editColumn('paymentStatus', function ($row) {
                    if ($row->paymentStatus == 'due') {
                        return '<span class="badge badge-danger">Due</span>';
                    } elseif ($row->paymentStatus == 'paid') {
                        return '<span class="badge badge-success">Paid</span>';
                    }
                })
                ->rawColumns(['details', 'paymentStatus'])
                ->make(true);
        }
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        return view('Invoice::invoice.index', compact('bankId', 'country', 'supplierId'));
    }

    // To show invoice details page
    public function invoiceDetails($id)
    {
        $serialNumber = 1;
        $invoiceDetails = DB::table('generateinvoices')->where('id', $id)->where('is_trash', 0)->first();

        $data = DB::table('contacts')
            ->where('contacts.id', $invoiceDetails->customer_id)
            ->leftJoin('key_personnel', 'contacts.key_personnel_id', 'key_personnel.id')
            ->select('contacts.id', 'key_personnel.full_name as key_personnel_name', 'contacts.address', 'contacts.cp_phone_no', 'contacts.cp_email', 'contacts.full_name', 'contacts.type')
            ->first();

        $idArray = json_decode($invoiceDetails->invoiceOrderId);
        $orderList = DB::table('orders')
            ->whereIn('orders.id', $idArray)
            ->leftJoin('contacts', 'contacts.id', 'orders.bank_id')
            ->leftJoin('products', 'orders.country_id', 'products.id')
            ->select('products.name as country_name', 'orders.id', 'orders.company_name', 'orders.order_invoice_no', 'orders.order_date', 'orders.bank_reference', 'orders.selling_price', 'contacts.full_name as customer_name', 'contacts.id as customer_id')
            ->get();

        // Loop through each item in the $orderList array
        foreach ($orderList as $item) {
            if (isset($orderSummary[$item->customer_name])) {
                $orderSummary[$item->customer_name]['count']++;

                $orderSummary[$item->customer_name]['total'] += $item->selling_price;
            } else {
                $orderSummary[$item->customer_name] = [
                    'serial' => $serialNumber,
                    'count' => 1,
                    'total' => $item->selling_price,
                    'customer_id' => $item->customer_id,
                ];
                $serialNumber++;
            }
        }

        return view('Invoice::invoice.details', compact('data', 'orderSummary', 'orderList', 'invoiceDetails'));
    }

    // To create invoice
    public function createInvoice(Request $request)
    {
        $pageTitle = "Invoice Create";
        $studentInfo = [];
        $from_date = 0;
        $to_date = 0;
        $data = '';
        $orderSummary = [];
        $orderList = [];
        $orderIdArray = [];
        $customerId = '';
        $customerType = '';
        $bankId = ['' => 'Select Bank'] + DB::table('contacts as bank')
            ->where('bank.is_trash', 0)
            ->where('bank.type', 1)
            ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $country = ['' => 'All Country'] + DB::table('products')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('products.id', 'products.name as country_name')
            ->pluck('country_name', 'id')
            ->toArray();
        $supplierId = ['' => 'All Supplier'] + DB::table('contacts as supplier')
            ->where('supplier.is_trash', 0)
            ->where('supplier.type', 4)
            ->where('supplier.status', 'active')
            ->select('supplier.id', DB::raw('CONCAT(IFNULL(supplier.full_name,""),"/Mobile: ",IFNULL(supplier.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();

        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;;
            $serialNumber = 1;
            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            $customerId = $request->customer_id;
            $customerType = $request->customer_type;


            $data = DB::table('contacts')
                ->where('contacts.id', $request->customer_id)
                ->leftJoin('key_personnel', 'contacts.key_personnel_id', 'key_personnel.id')
                ->select('contacts.id', 'key_personnel.full_name as key_personnel_name', 'contacts.address', 'contacts.cp_phone_no', 'contacts.cp_email', 'contacts.full_name', 'contacts.type')
                ->first();
            if ($request->customer_type == 'bank') {
                $orderId = DB::table('contacts')
                    ->where('contacts.id', $request->customer_id)
                    ->orWhere('contacts.bank_id', $request->customer_id)
                    ->Where('contacts.price_same_with_main_bank', 1)
                    ->get();
            } elseif ($request->customer_type == 'branch') {
                $orderId = DB::table('contacts')
                    ->where('contacts.id', $request->customer_id)
                    ->get();
            } elseif ($request->customer_type == 'company') {
                $orderId = DB::table('contacts')
                    ->where('contacts.id', $request->customer_id)
                    ->get();
            }


            // echo "<pre>";
            // print_r($orderId);
            // exit();
            if (!empty($orderId)) {
                $idArray = $orderId->pluck('id')->toArray();
                $orderList = DB::table('orders')
                    ->whereIn('orders.bank_id', $idArray)
                    ->where('orders.is_generate_invoice', 0)
                    ->where('orders.delivered_status', 1)
                    ->leftJoin('contacts', 'contacts.id', 'orders.bank_id')
                    ->leftJoin('products', 'orders.country_id', 'products.id')
                    ->select('products.name as country_name', 'orders.id', 'orders.company_name', 'orders.order_invoice_no', 'orders.order_date', 'orders.bank_reference', 'orders.selling_price', 'contacts.full_name as customer_name', 'contacts.id as customer_id')
                    ->get();
                $orderIdArray = json_encode($orderList->pluck('id')->toArray());


                // Loop through each item in the $orderList array
                foreach ($orderList as $item) {
                    if (isset($orderSummary[$item->customer_name])) {
                        $orderSummary[$item->customer_name]['count']++;

                        $orderSummary[$item->customer_name]['total'] += $item->selling_price;
                    } else {
                        $orderSummary[$item->customer_name] = [
                            'serial' => $serialNumber,
                            'count' => 1,
                            'total' => $item->selling_price,
                            'customer_id' => $item->customer_id,
                        ];
                        $serialNumber++;
                    }
                }
            }
        }




        return view('Invoice::invoice.create', compact('pageTitle', 'from_date', 'to_date', 'request', 'bankId', 'country', 'supplierId', 'data', 'orderSummary', 'orderList', 'orderIdArray', 'customerId', 'customerType'));
    }

    // To store invoice
    public function storeInvoice(Request $request)
    {
        DB::beginTransaction();
        try {

            $orderId = json_decode($request->orderId);
            foreach ($orderId as $orderKey => $orderValue) {

                $orderTableUpdate = DB::table('orders')->where('id', $orderValue)->update([
                    'is_generate_invoice' => 1,
                    'invoice_gernerated_at' => Carbon::now(),
                ]);
            }

            $insertInvoiceTable = DB::table('generateinvoices')->insert([
                'invoiceNo' => $request->invoiceNo,
                'invoiceDate' => $request->invoiceDate,
                'invoiceOrderId' => $request->orderId,
                'customer_id' => $request->customerId,
                'customer_type' => $request->customerType,
                'usdToBdt' => $request->usdToBdt,
                'total_amount' => $request->total_amount,
                'due_amount' => $request->total_amount,
                'vat' => $request->vat,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            Session::flash('success', "Invoice Created Successfully");
            return redirect()->route('create.invoice');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // Invoice Filter
    public function createInvoiceFilter(Request $request)
    {

        $url = 'create-invoice?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&customer_type=' . $request->customer_type . '&bank_type=' . $request->bank_type . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }

    // get customer dependent on customer type
    public function getInvoiceCustomer(Request $request)
    {
        if ($request->customerType == 'bank') {
            $data = [];
            if (!empty($request->bankType)) {
                $data = DB::table('contacts as bank')
                    ->where('bank.is_trash', 0)
                    ->where('bank.type', 1);

                if ($request->bankType) {
                    $data = $data->where('bank.bank_type', $request->bankType)
                        ->select('bank.id', DB::raw('CONCAT(IFNULL(bank.full_name,""),"/Mobile: ",IFNULL(bank.cp_phone_no,"")) as full_name'))
                        ->get();
                }
            }
        } else if ($request->customerType == 'branch') {
            $data = DB::table('contacts as branch')
                ->where('branch.is_trash', 0)
                ->where('branch.type', 2)
                ->where('branch.price_same_with_main_bank', 0)
                ->select('branch.id', DB::raw('CONCAT(IFNULL(branch.full_name,""),"/Mobile: ",IFNULL(branch.cp_phone_no,"")) as full_name'))
                ->get();
        } else if ($request->customerType == 'company') {
            $data = DB::table('contacts as company')
                ->where('company.is_trash', 0)
                ->where('company.type', 3)
                ->select('company.id', DB::raw('CONCAT(IFNULL(company.full_name,""),"/Mobile: ",IFNULL(company.cp_phone_no,"")) as full_name'))
                ->get();
        }

        return response()->json($data);
    }



    // To updateInvoice 
    public function updateInvoice(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $invoiceDetails = DB::table('generateinvoices')->where('id', $id)->where('is_trash', 0)->first();

            
            $orderId = json_decode($invoiceDetails->invoiceOrderId);
            // echo "<pre>";
            // print_r($invoiceDetails);
            // exit();

            if($request->paymentStatus == 'paid'){
                foreach ($orderId as $orderKey => $orderValue) {
                    $orderTableUpdate = DB::table('orders')->where('id', $orderValue)->update([
                        'payment_status' => 'paid',
                        'updated_at' => Carbon::now(),
                    ]);
                }

                $updateInvoiceTable = DB::table('generateinvoices')->where('id', $id)->update([
                    'due_amount' => 0,
                    'paid_amount' => $invoiceDetails->due_amount,
                    'paymentStatus' => $request->paymentStatus,
                    'paymentDate' => Carbon::now(),
                    'paymentCreateBy' => Auth::id(),
                ]);
            }
            
            DB::commit();
            Session::flash('success', "Invoice Updated Successfully");
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
}
