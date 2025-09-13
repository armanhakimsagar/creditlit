<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("Admin::welcome");
    }

    public function index()
    {

        $admin = Auth::guard()->user();
        $pageTitle = 'Admin Dashboard';

        // Today Order
        $today = date('d-m-Y');
        $todayOrder = DB::table('orders')->where('is_trash', 0)->where('order_date', $today)->select('id', 'order_status', 'buying_price')->get();
        $todayInvoice = DB::table('generateinvoices')
            ->where('is_trash', 0)
            ->where("invoiceDate", date('d F Y'))
            ->select('id', 'paymentStatus')
            ->get();


        $todayTotalOrders = $todayOrder->count();
        $todayPendingOrders = $todayOrder->where('order_status', 'pending')->count();
        $todayProcessingOrders = $todayOrder->where('order_status', 'processing')->count();
        $todayQueryOrders = $todayOrder->where('order_status', 'query')->count();
        $todayCancelOrders = $todayOrder->where('order_status', 'cancel')->count();
        $todayCompletedOrders = $todayOrder->where('order_status', 'completed')->count();
        $todayDeliveredOrders = $todayOrder->where('order_status', 'delivered')->count();
        $todayTotalSale = $todayOrder->sum('buying_price');
        $todayTotalInvoice = $todayInvoice->count();
        $todayPaidInvoice = $todayInvoice->where('paymentStatus', 'paid')->count();
        $todayDueInvoice = $todayInvoice->where('paymentStatus', 'due')->count();



        // This week order
        $startOfWeek = Carbon::now()->startOfWeek()->format('d-m-Y');
        $endOfWeek = Carbon::now()->endOfWeek()->format('d-m-Y');
        $thisWeekOrders = DB::table('orders')->where('is_trash', 0)->whereBetween('order_date', [$startOfWeek, $endOfWeek])->select('id', 'order_status', 'buying_price')->get();
        $thisWeekInvoice = DB::table('generateinvoices')
            ->where('is_trash', 0)
            ->whereBetween('invoiceDate', [Carbon::now()->startOfWeek()->format('d F Y'), Carbon::now()->endOfWeek()->format('d F Y')])
            ->select('id', 'paymentStatus')
            ->get();


        $thisWeekTotalOrders = $thisWeekOrders->count();
        $thisWeekPendingOrders = $thisWeekOrders->where('order_status', 'pending')->count();
        $thisWeekProcessingOrders = $thisWeekOrders->where('order_status', 'processing')->count();
        $thisWeekQueryOrders = $thisWeekOrders->where('order_status', 'query')->count();
        $thisWeekCancelOrders = $thisWeekOrders->where('order_status', 'cancel')->count();
        $thisWeekCompletedOrders = $thisWeekOrders->where('order_status', 'completed')->count();
        $thisWeekDeliveredOrders = $thisWeekOrders->where('order_status', 'delivered')->count();
        $thisWeekTotalSale = $thisWeekOrders->sum('buying_price');
        $thisWeekTotalInvoice = $thisWeekInvoice->count();
        $thisWeekPaidInvoice = $thisWeekInvoice->where('paymentStatus', 'paid')->count();
        $thisWeekDueInvoice = $thisWeekInvoice->where('paymentStatus', 'due')->count();


        // This month order
        $startOfMonth = Carbon::now()->startOfMonth()->format('d-m-Y');
        $endOfMonth  = Carbon::now()->endOfMonth()->format('d-m-Y');
        $thisMonthOrders  = DB::table('orders')->where('is_trash', 0)->whereBetween('order_date', [$startOfMonth, $endOfMonth])->select('id', 'order_status', 'buying_price')->get();
        $thisMonthInvoice = DB::table('generateinvoices')
            ->where('is_trash', 0)
            ->whereBetween('invoiceDate', [Carbon::now()->startOfMonth()->format('d F Y'), Carbon::now()->endOfMonth()->format('d F Y')])
            ->select('id', 'paymentStatus')
            ->get();

        $thisMonthTotalOrders = $thisMonthOrders->count();
        $thisMonthPendingOrders = $thisMonthOrders->where('order_status', 'pending')->count();
        $thisMonthProcessingOrders = $thisMonthOrders->where('order_status', 'processing')->count();
        $thisMonthQueryOrders = $thisMonthOrders->where('order_status', 'query')->count();
        $thisMonthCancelOrders = $thisMonthOrders->where('order_status', 'cancel')->count();
        $thisMonthCompletedOrders = $thisMonthOrders->where('order_status', 'completed')->count();
        $thisMonthDeliveredOrders = $thisMonthOrders->where('order_status', 'delivered')->count();
        $thisMonthTotalSale = $thisMonthOrders->sum('buying_price');
        $thisMonthTotalInvoice = $thisMonthInvoice->count();
        $thisMonthPaidInvoice = $thisMonthInvoice->where('paymentStatus', 'paid')->count();
        $thisMonthDueInvoice = $thisMonthInvoice->where('paymentStatus', 'due')->count();



        // This year order
        $startOfYear = Carbon::now()->startOfYear()->format('d-m-Y');
        $endOfYear  = Carbon::now()->endOfYear()->format('d-m-Y');
        $thisYearOrders   = DB::table('orders')->where('is_trash', 0)->whereBetween('order_date', [$startOfYear, $endOfYear])->select('id', 'order_status', 'buying_price')->get();
        $thisYearInvoice = DB::table('generateinvoices')
            ->where('is_trash', 0)
            ->whereBetween('invoiceDate', [Carbon::now()->startOfYear()->format('d F Y'), Carbon::now()->endOfYear()->format('d F Y')])
            ->select('id', 'paymentStatus')
            ->get();

        $thisYearTotalOrders = $thisYearOrders->count();
        $thisYearPendingOrders = $thisYearOrders->where('order_status', 'pending')->count();
        $thisYearProcessingOrders = $thisYearOrders->where('order_status', 'processing')->count();
        $thisYearQueryOrders = $thisYearOrders->where('order_status', 'query')->count();
        $thisYearCancelOrders = $thisYearOrders->where('order_status', 'cancel')->count();
        $thisYearCompletedOrders = $thisYearOrders->where('order_status', 'completed')->count();
        $thisYearDeliveredOrders = $thisYearOrders->where('order_status', 'delivered')->count();
        $thisYearTotalSale = $thisYearOrders->sum('buying_price');
        $thisYearTotalInvoice = $thisYearInvoice->count();
        $thisYearPaidInvoice = $thisYearInvoice->where('paymentStatus', 'paid')->count();
        $thisYearDueInvoice = $thisYearInvoice->where('paymentStatus', 'due')->count();


        // total order
        $allTimeOrders   = DB::table('orders')->where('is_trash', 0)->select('id', 'order_status', 'buying_price')->get();
        $allTimeInvoice = DB::table('generateinvoices')
            ->where('is_trash', 0)
            ->select('id', 'paymentStatus')
            ->get();

        $allTimeTotalOrders = $allTimeOrders->count();
        $allTimePendingOrders = $allTimeOrders->where('order_status', 'pending')->count();
        $allTimeProcessingOrders = $allTimeOrders->where('order_status', 'processing')->count();
        $allTimeQueryOrders = $allTimeOrders->where('order_status', 'query')->count();
        $allTimeCancelOrders = $allTimeOrders->where('order_status', 'cancel')->count();
        $allTimeCompletedOrders = $allTimeOrders->where('order_status', 'completed')->count();
        $allTimeDeliveredOrders = $allTimeOrders->where('order_status', 'delivered')->count();
        $allTimeTotalSale = $allTimeOrders->sum('buying_price');
        $allTimeTotalInvoice = $allTimeInvoice->count();
        $allTimePaidInvoice = $allTimeInvoice->where('paymentStatus', 'paid')->count();
        $allTimeDueInvoice = $allTimeInvoice->where('paymentStatus', 'due')->count();



        $totalCustomer = DB::table('contacts')->where('status', 'active')->where('is_trash', 0)->select('id', 'type')->get();
        $totalBank = $totalCustomer->where('type', 1)->count();
        $totalBranch = $totalCustomer->where('type', 2)->count();
        $totalCompany = $totalCustomer->where('type', 3)->count();
        $totalSupplier = $totalCustomer->where('type', 4)->count();
        $totalEmployee = $totalCustomer->where('type', 5)->count();
        $totalUser = DB::table('users')->where('status', 'active')->count();
        $totalKeyPersonnel = DB::table('key_personnel')->where('status', 'active')->where('is_trash', 0)->count();

        $lastTenPendingOrder = DB::table('orders')
            ->where('orders.is_trash', 0)
            ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
            ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
            ->where('supplier.type', 4)
            ->where('orders.order_status', 'pending')
            ->leftjoin('products as country', 'orders.country_id', 'country.id')
            ->select('orders.id', 'orders.order_invoice_no', 'customer.full_name as customer_name',  'orders.order_date', 'orders.order_status')
            ->orderBy('orders.id', 'desc')
            ->take(10)
            ->get();

        $lastTenDueInvoice = DB::table('generateinvoices')
            ->where('generateinvoices.is_trash', 0)
            ->leftjoin('contacts as customer', 'generateinvoices.customer_id', 'customer.id')
            ->leftjoin('users', 'users.id', 'generateinvoices.created_by')
            ->select('generateinvoices.id', 'generateinvoices.invoiceNo', 'generateinvoices.invoiceDate', 'generateinvoices.total_amount', 'customer.full_name as customer_name')
            ->orderBy('generateinvoices.id', 'desc')
            ->take(10)
            ->get();
            
        return view("Admin::dashboard.index", compact('admin', 'pageTitle', 'todayTotalOrders', 'todayPendingOrders', 'todayProcessingOrders', 'todayQueryOrders', 'todayCancelOrders', 'todayCompletedOrders', 'todayDeliveredOrders', 'todayTotalSale', 'todayTotalInvoice', 'todayPaidInvoice', 'todayDueInvoice', 'thisWeekTotalOrders', 'thisWeekPendingOrders', 'thisWeekProcessingOrders', 'thisWeekQueryOrders', 'thisWeekCancelOrders', 'thisWeekCompletedOrders', 'thisWeekDeliveredOrders', 'thisWeekTotalSale', 'thisWeekTotalInvoice', 'thisWeekPaidInvoice', 'thisWeekDueInvoice', 'thisMonthTotalOrders', 'thisMonthPendingOrders', 'thisMonthProcessingOrders', 'thisMonthQueryOrders', 'thisMonthCancelOrders', 'thisMonthCompletedOrders', 'thisMonthDeliveredOrders', 'thisMonthTotalSale', 'thisMonthTotalInvoice', 'thisMonthPaidInvoice', 'thisMonthDueInvoice', 'thisYearTotalOrders', 'thisYearPendingOrders', 'thisYearProcessingOrders', 'thisYearQueryOrders', 'thisYearCancelOrders', 'thisYearCompletedOrders', 'thisYearDeliveredOrders', 'thisYearTotalSale', 'thisYearTotalInvoice', 'thisYearPaidInvoice', 'thisYearDueInvoice', 'allTimeTotalOrders', 'allTimePendingOrders', 'allTimeProcessingOrders', 'allTimeQueryOrders', 'allTimeCancelOrders', 'allTimeCompletedOrders', 'allTimeDeliveredOrders', 'allTimeTotalSale', 'allTimeTotalInvoice', 'allTimePaidInvoice', 'allTimeDueInvoice', 'totalBank', 'totalBranch', 'totalCompany', 'totalEmployee', 'totalSupplier', 'totalUser', 'totalKeyPersonnel', 'lastTenPendingOrder', 'lastTenDueInvoice'));
    }
}
