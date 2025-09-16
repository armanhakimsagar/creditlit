<?php

namespace App\Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\Cashbank;
use App\Modules\Payment\Models\OthersPayment;
use App\Modules\Payment\Models\SalesChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{

    /**
     * Display the module welcome screen
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


    // Order Report
    public function orderReport(Request $request)
    {
        $from_date = 0;
        $pageTitle = "Order Reports";
        $to_date = 0;
        $incomeInfo = [];
        $incomeInfo2 = [];
        $expenseInfo = [];
        $data = [];
        $productList = '';
        $allOrders = [];
        $pendingOrders = [];
        $processingOrders = [];
        $queryOrders = [];
        $cancelOrders = [];
        $completedOrders = [];
        $deliveredOrders = [];
        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            if ($from_date != null) {
                $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            }


            $allOrders = DB::table('orders')->whereBetween(
                DB::raw('str_to_date(order_date, "%d-%m-%Y")'),
                [
                    Carbon::parse($from_date)->format('Y-m-d'),
                    Carbon::parse($to_date)->format('Y-m-d')
                ]
            )->get();

            $pendingOrders = $allOrders->where('order_status', 'pending')->where('pending_status', 1);
            $processingOrders = $allOrders->where('order_status', 'processing')->where('processing_status', 1);
            $queryOrders = $allOrders->where('order_status', 'query')->where('query_status', 1);
            $cancelOrders = $allOrders->where('order_status', 'cancel')->where('cancel_status', 1);
            $completedOrders = $allOrders->where('order_status', 'completed')->where('completed_status', 1);
            $deliveredOrders = $allOrders->where('order_status', 'delivered')->where('delivered_status', 1);
            // echo "<pre>";
            // print_r($pendingOrders);
            // exit();
        }

        return view('Report::orderReport.index', compact('request', 'from_date', 'to_date', 'pageTitle', 'allOrders', 'pendingOrders', 'processingOrders', 'queryOrders', 'cancelOrders', 'completedOrders', 'deliveredOrders'));
    }

    public function orderReportFilter(Request $request)
    {
        $url = 'order-report?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        return redirect($url);
    }


    public function expenseReportIndex(Request $request)
    {
        $pageTitle = "Other Payment Report";

        $salesCharts = SalesChart::where('status', 'active')->pluck('name', 'id')->toArray();
        $data = [];
        $model = [];
        $from_date = 0;
        $to_date = 0;
        $header_wise = 0;
        $expense_chart_id = 0;
        if ($request->search == 'true') {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $header_wise = $request->header_wise;
            $expense_chart_id = json_decode($request->expense_chart_id);
            if ($from_date != null) {
                $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            }

            $model = OthersPayment::where('payment_amount', '>', 0);
            if (!empty($expense_chart_id)) {
                $model = $model->wherein('sales_chart_id', $expense_chart_id);
            }
            $model = $model->where('other_payment.status', 'active');

            if (!empty($request->to_date) && !empty($request->from_date) && empty($request->header_wise)) {

                $model = $model->whereBetween(DB::raw('str_to_date(other_payment.payment_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                $model = $model->get(['other_payment.*']);
            } elseif (!empty($request->to_date) && !empty($request->from_date) && !empty($request->header_wise)) {

                $model = $model->whereBetween(DB::raw('str_to_date(other_payment.payment_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')])->groupby('other_payment.sales_chart_id')->get(['other_payment.*', DB::raw('SUM(other_payment.payment_amount) as total_other_payment')]);
            }

            foreach ($model as $transaction_main) {

                if (empty($request->header_wise)) {

                    $data[] = array('Date' => $transaction_main->payment_date, 'Expense_chart' => $transaction_main->sales_chart_id, 'Type' => $transaction_main->transaction_type, 'payment_amount' => $transaction_main->payment_amount, 'invoice' => $transaction_main->payment_invoice, 'supplier_id' => '');
                } else {

                    $data[] = array('Date' => $transaction_main->payment_date, 'Expense_chart' => $transaction_main->sales_chart_id, 'Type' => $transaction_main->transaction_type, 'payment_amount' => $transaction_main->total_other_payment, 'invoice' => $transaction_main->payment_invoice);
                }
            }
            $data = collect($data)->sortBy('Date');
        }

        return view("Report::expenseReport.index", compact('pageTitle', 'salesCharts', 'data', 'model', 'from_date', 'to_date', 'header_wise', 'request'));
    }
    public function expenseReportFilter(Request $request)
    {
        $url = 'expense-report-index?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&expense_chart_id=' . urlencode(json_encode($request->expense_chart_id)) . '&header_wise=' . $request->header_wise;
        return redirect($url);
    }

    // Cash Report

    public function cashBookReportIndex(Request $request)
    {
        $pageTitle = "Balance Transfer";
        $data = Cashbank::where('status', 'active')->paginate(10);
        $model = [];
        $from_date = 0;
        $to_date = 0;
        $opening_balance = 0;

        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            $model = CashBank::Where('cash_banks.payment_type', 'cash');
            $opening_balance = CashBank::where('status', 'active')->Where('cash_banks.payment_type', 'cash');

            $user_id = auth()->id();
            if (!empty($request->to_date) && !empty($request->from_date)) {

                $model->whereBetween(DB::raw('str_to_date(cash_banks.invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                $opening_balance->where(DB::raw('str_to_date(cash_banks.invoice_date, "%d-%m-%Y")'), '<', [Carbon::parse($from_date)]);
            }
            $opening_balance = $opening_balance->sum('amount');
            $model = $model->where('cash_banks.status', 'active');
            $model = $model->where('cash_banks.payment_type', 'cash');
            // $model = $model->groupBy('sales.sales_invoice_no');
            $model = $model->get(['cash_banks.*']);
        }
        // echo "<pre>";
        // print_r($model);
        // exit();
        return view('Report::cashBankReport.cashReportIndex', compact('pageTitle', 'data', 'from_date', 'to_date', 'opening_balance', 'model', 'request'));
    }

    public function cashBookReportFilter(Request $request)
    {
        $url = 'cash-book-report-index?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        return redirect($url);
    }

    // Bank Report
    public function bankBookReportIndex(Request $request)
    {
        $pageTitle = "Balance Transfer";
        $data = Cashbank::where('status', 'active')->paginate(10);
        $model = [];
        $from_date = 0;
        $to_date = 0;
        $opening_balance = 0;

        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;

            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            $model = CashBank::Where('cash_banks.payment_type', 'bank');
            $opening_balance = CashBank::where('status', 'active')->Where('cash_banks.payment_type', 'bank');

            $user_id = auth()->id();
            if (!empty($request->to_date) && !empty($request->from_date)) {

                $model->whereBetween(DB::raw('str_to_date(cash_banks.invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                $opening_balance->where(DB::raw('str_to_date(cash_banks.invoice_date, "%d-%m-%Y")'), '<', [Carbon::parse($from_date)]);
            }
            $opening_balance = $opening_balance->sum('amount');
            $model = $model->where('cash_banks.status', 'active');
            $model = $model->where('cash_banks.payment_type', 'bank');
            // $model = $model->groupBy('sales.sales_invoice_no');
            $model = $model->get(['cash_banks.*']);
        }
        return view('Report::cashBankReport.bankReportIndex', compact('pageTitle', 'data', 'from_date', 'to_date', 'opening_balance', 'model', 'request'));
    }

    public function bankBookReportFilter(Request $request)
    {
        $url = 'bank-book-report-index?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        return redirect($url);
    }

    public function admissionCollectionReport(Request $request)
    {

        $pageTitle = "Collection Report";
        $data = DB::table('sales')->Where('status', 'active')->get();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentTypeList = DB::table('student_type')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $item_list = DB::table('products')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $class_list = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $model = [];
        $model1 = [];
        $model2 = [];
        $studentInfo = [];
        $from_date = 0;
        $to_date = 0;
        $className = '';
        $yearName = '';
        $paymentStatus = '';

        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $shift = json_decode($request->shift_id);
            $studentTypeId = json_decode($request->student_type_id);
            $item = json_decode($request->item_id);
            $student = json_decode($request->student_id);
            $paymentStatus = $request->payment_status_id;
            $className = DB::table('classes')->where('id', $request->class_id)->pluck('name')->first();
            $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();

            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;

            if ($request->type_id == 1) {
                $model = DB::table('sales_product_relation')->Where('sales_product_relation.status', 'active')
                    ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                    ->join('products', 'sales_product_relation.product_id', 'products.id');

                if (!empty($request->to_date) && !empty($request->from_date)) {
                    $model->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                }
                if (!empty($shift)) {
                    $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                    $model = $model->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($studentTypeId)) {
                    $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                    $model = $model->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($request->class_id)) {
                    $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                    $model = $model->whereIn('sales.customer_id', $studentInfo2)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($item)) {
                    $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
                }
                if (!empty($student)) {
                    $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
                }
                if (!empty($request->yearId)) {
                    $model = $model->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                $model = $model->join('contacts', 'sales.customer_id', 'contacts.id')
                    ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                    ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                    ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                    ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                    ->where('contact_academics.academic_year_id', $request->yearId)
                    ->where('contacts.type', 1)
                    ->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'contacts.id as student_id', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);
            } elseif ($request->type_id == 6) {
                $model = DB::table('sales_product_relation')
                    ->Where('sales_product_relation.status', 'active')
                    ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                    ->join('products', 'sales_product_relation.product_id', 'products.id')
                    ->join('contacts', 'sales.customer_id', 'contacts.id')
                    ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                    ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                    ->where('contacts.type', 6);
                if (!empty($request->to_date) && !empty($request->from_date)) {
                    $model->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                }
                if (!empty($item)) {
                    $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
                }
                if (!empty($student)) {
                    $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
                }
                $model = $model->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'contacts.id as student_id', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);
            } else {
                $model1 = DB::table('sales_product_relation')->Where('sales_product_relation.status', 'active')
                    ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                    ->join('products', 'sales_product_relation.product_id', 'products.id');

                if (!empty($request->to_date) && !empty($request->from_date)) {
                    $model1->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                }
                if (!empty($shift)) {
                    $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                    $model1 = $model1->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($studentTypeId)) {
                    $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                    $model1 = $model1->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($request->class_id)) {
                    $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                        ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                    $model1 = $model1->whereIn('sales.customer_id', $studentInfo2)->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                if (!empty($item)) {
                    $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
                }
                if (!empty($student)) {
                    $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
                }
                if (!empty($request->yearId)) {
                    $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId);
                }
                $model1 = $model1->join('contacts', 'sales.customer_id', 'contacts.id')
                    ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                    ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                    ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                    ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                    ->where('contact_academics.academic_year_id', $request->yearId)
                    ->where('contacts.type', 1)
                    ->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'contacts.id as student_id', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);

                $model2 = DB::table('sales_product_relation')
                    ->Where('sales_product_relation.status', 'active')
                    ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                    ->join('products', 'sales_product_relation.product_id', 'products.id')
                    ->join('contacts', 'sales.customer_id', 'contacts.id')
                    ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                    ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                    ->where('contacts.type', 6);
                if (!empty($request->to_date) && !empty($request->from_date)) {
                    $model2->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
                }
                if (!empty($item)) {
                    $model2 = $model2->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
                }
                if (!empty($student)) {
                    $model2 = $model2->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
                }
                $model2 = $model2->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'contacts.id as student_id', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);

                $model = $model1->concat($model2);
            }
            if ($paymentStatus == 1) {
                $model = $this->processPaymentStatusOne($request);
            } else if ($paymentStatus == 2) {
                $model = $this->processPaymentStatusTwo($request);
            } else {
                $model = $this->processPaymentStatusThree($request);
            }
        }

        return view('Report::collectionReport.index', compact('pageTitle', 'data', 'from_date', 'to_date', 'model', 'request', 'shift_list', 'class_list', 'academic_year_list', 'className', 'yearName', 'item_list', 'paymentStatus', 'studentTypeList'));
    }

    public function collectionReportFilter(Request $request)
    {

        $url = 'admission-collection-report?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&payment_status_id=' . $request->payment_status_id . '&student_id=' . urlencode(json_encode($request->student_id)) . '&yearId=' . $request->academic_year_id . '&class_id=' . $request->class_id . '&type_id=' . $request->type_id . '&shift_id=' . urlencode(json_encode($request->shift_id)) . '&student_type_id=' . urlencode(json_encode($request->student_type_id)) . '&item_id=' . urlencode(json_encode($request->item_id));
        return redirect($url);
    }

    // Salary Item Report
    public function salaryItemReport(Request $request)
    {
        $pageTitle = "Salary Item Wise Cost Report";
        $item_list = DB::table('salary_item')->where('is_trash', '0')->where('status', 'active')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $enumMonth = DB::table('enum_month')->orderBy('id', 'ASC')->pluck('short_name', 'id')->toArray();
        $currentMonth = (DB::table('enum_month')->where('short_name', date('M'))->first())->id;
        $model = [];
        $yearName = '';
        $monthName = '';

        if ($request->search == 'true') {

            $item = json_decode($request->item_id);
            $employee = json_decode($request->employee_id);
            $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();
            $monthName = DB::table('enum_month')->where('id', $request->monthId)->pluck('short_name')->first();
            $model = DB::table('employee_payroll')
                ->leftJoin('contacts', 'employee_payroll.employee_id', 'contacts.id')
                ->leftJoin('employee_payroll_item_details', 'employee_payroll_item_details.payroll_id', 'employee_payroll.id')
                ->leftJoin('salary_item', 'employee_payroll_item_details.item_id', 'salary_item.id')
                ->leftJoin('academic_years', 'academic_years.id', 'employee_payroll.academic_year_id')
                ->leftJoin('enum_month', 'enum_month.id', 'employee_payroll.month_id')
                ->where('employee_payroll.academic_year_id', $request->yearId)
                ->where('employee_payroll.month_id', $request->monthId)
                ->where('employee_payroll.is_disbursed', 1)
                ->where('contacts.type', 5)
                ->where('employee_payroll_item_details.total_amount', '>', 0);
            if (!empty($item)) {
                $model = $model->whereIn('employee_payroll_item_details.item_id', $item);
            }

            if (!empty($employee)) {
                $model = $model->whereIn('employee_payroll_item_details.employee_id', $employee);
            }
            $model = $model->select('contacts.full_name', 'academic_years.year', 'enum_month.short_name', 'salary_item.name', 'employee_payroll_item_details.total_amount')
                ->get();
            // echo "<pre>";
            // print_r($model);
            // exit();
        }

        return view('Report::salaryItemReport.index', compact('pageTitle', 'model', 'request', 'academic_year_list', 'yearName', 'item_list', 'enumMonth', 'currentMonth', 'monthName'));
    }

    // Salary Item Report Filter
    public function salaryItemReportFilter(Request $request)
    {

        $url = 'salary-item-report?search=true&employee_id=' . urlencode(json_encode($request->employee_id)) . '&yearId=' . $request->academic_year_id . '&monthId=' . $request->month_id . '&item_id=' . urlencode(json_encode($request->item_id));
        return redirect($url);
    }

    public function studentCollectionReport(Request $request)
    {
        // dd($request->all());
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $student_list = ['0' => 'All'] + DB::table('contacts')->where('is_trash', '0')->where('type', 1)->pluck('full_name', 'id')->toArray();
        $class_list = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $shiftList = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentTypeList = DB::table('student_type')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $versionList = ['' => 'All'] + DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = ['' => 'All'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $transportList = DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $model = [];
        $info = [];
        $product = '';
        $item = '';
        $month = '';
        $type = $request->type;
        $total_fixed_paid_amount_each_product = [];
        $total_fixed_paid_monthly = [];
        $total_paid_amount = [];
        $total_fixed_amount = [];
        $month_paid_sum = [];
        $fixed_tuition_fee_sum = [];
        $items = DB::table('products')->where('status', 'active')->where('id', '!=', 9)->pluck('name', 'id')->toArray();
        if ($request->search == true) {
            $shift = json_decode($request->shift_id);
            $studentTypeId = json_decode($request->student_type_id);
            $transport = json_decode($request->transport_id);
            $section = json_decode($request->section_id);
            $student = json_decode($request->student_id);
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $model = DB::table('contacts')
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('contact_payable_items', 'contacts.id', 'contact_payable_items.contact_id')
                ->leftjoin('classes', 'classes.id', 'contact_academics.class_id')
                ->leftjoin('sections', 'sections.id', 'contact_academics.section_id')
                ->leftjoin('shifts', 'shifts.id', 'contact_academics.shift_id')
                ->where('contacts.is_trash', 0);

            if (!empty($request->yearId)) {
                $model = $model->where('contact_academics.academic_year_id', $request->yearId);
            }
            if (!empty($request->class_id)) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($section)) {
                $model = $model->whereIn('contact_academics.section_id', $section);
            }
            if (!empty($shift)) {
                $model = $model->whereIn('contact_academics.shift_id', $shift);
            }
            if (!empty($studentTypeId)) {
                $model = $model->whereIn('contact_academics.student_type_id', $studentTypeId);
            }
            if (!empty($request->group_id)) {
                $model = $model->where('contact_academics.group_id', $request->group_id);
            }
            if (!empty($request->gender_id)) {
                $model = $model->where('contacts.gender', $request->gender_id);
            }
            if (!empty($request->admission_type)) {
                $model = $model->where('contact_academics.admission_type', $request->admission_type);
            }
            if (!empty($request->version_id)) {
                $model = $model->where('contact_academics.version_id', $request->version_id);
            }
            if (!empty($transport)) {
                $model = $model->whereIn('contact_academics.transport_id', $transport);
            }

            if (!empty($student)) {
                $model = $model->whereIn('contacts.id', $student);
            }

            if (!empty($request->to_date) && !empty($request->from_date)) {

                $model = $model->whereBetween(DB::raw('str_to_date(contact_payable_items.date, "%Y-%m-%d")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }

            $model = $model->select('contact_academics.academic_year_id', 'contact_academics.class_id', 'contact_academics.contact_id', 'contacts.full_name as student_name', 'classes.name as class_name', 'shifts.name as shift_name', 'sections.name as section_name', 'contacts.id as student_id',)->orderBy('classes.weight', 'ASC')->groupBy('contact_academics.contact_id')->get();

            $companyDetails = \App::make('companyDetails');
            $newItems = json_decode($companyDetails->studentCollectionSetItem);
            $tuition_fees_id = \App::make('tf_id');

            if (!empty($newItems)) {
                $product = DB::table('products')->whereIn('id', $newItems)->get();
            } else {
                $product = DB::table('products')->get();
            }

            $month = DB::table('enum_month')->get();
            if (!empty($model)) {
                foreach ($model as $key => $value) {
                    foreach ($product as $row) {
                        $paid_amount[$row->id] = DB::table('contact_payable_items')->where('academic_year_id', $value->academic_year_id)->where('class_id', $value->class_id)->where('product_id', $row->id)->where('contact_id', $value->contact_id)->latest()->first();
                        $total_paid_amount[$row->id][] = !empty($total_paid_amount[$row->id]) ? $total_paid_amount[$row->id] : 0;
                        $total_paid_amount[$row->id][] = !empty($paid_amount[$row->id]->paid_amount) ? $paid_amount[$row->id]->paid_amount : 0;

                        $amount[$row->id] = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', $value->academic_year_id)->where('class_id', $value->class_id)->where('product_id', $row->id)->where('contact_id', $value->contact_id)->latest()->first();

                        $total_fixed_amount[$row->id][] = !empty($total_fixed_amount[$row->id]) ? $total_fixed_amount[$row->id] : 0;
                        $total_fixed_amount[$row->id][] = !empty($amount[$row->id]->amount) ? $amount[$row->id]->amount : 0;
                    }

                    foreach ($month as $key => $row) {
                        // Query for variable tuition fees payment
                        $month_paid[$row->id] = DB::table('contact_payable_items')
                            ->where('academic_year_id', $value->academic_year_id)
                            ->where('class_id', $value->class_id)
                            ->where('month_id', $row->id)
                            ->where('contact_id', $value->contact_id)
                            ->where('product_id', $tuition_fees_id)
                            ->select('paid_amount')
                            ->first();
                        $month_paid_sum[$row->id][] = !empty($month_paid_sum[$row->id]) ? $month_paid_sum[$row->id] : 0;
                        $month_paid_sum[$row->id][] = !empty($month_paid[$row->id]->paid_amount) ? $month_paid[$row->id]->paid_amount : 0;

                        // Query for fixed tuition fees payment
                        $fixed_tuition_fee[$row->id] = DB::table('contactwise_item_discount_price_list')
                            ->where('academic_year_id', $value->academic_year_id)
                            ->where('class_id', $value->class_id)
                            ->where('enum_month_id', $row->id)
                            ->where('contact_id', $value->contact_id)
                            ->where('product_id', $tuition_fees_id)
                            ->select('amount')
                            ->first();
                        $fixed_tuition_fee_sum[$row->id][] = !empty($fixed_tuition_fee_sum[$row->id]) ? $fixed_tuition_fee_sum[$row->id] : 0;
                        $fixed_tuition_fee_sum[$row->id][] = !empty($fixed_tuition_fee[$row->id]->amount) ? $fixed_tuition_fee[$row->id]->amount : 0;

                        for ($i = 1; $i <= 12; $i++) {
                            $monthly_data = array();

                            if (isset($month_paid[$i])) {
                                $monthly_data['paid_amount'] = $month_paid[$i]->paid_amount;
                            }

                            if (isset($fixed_tuition_fee[$i])) {
                                $monthly_data['amount'] = $fixed_tuition_fee[$i]->amount;
                            }

                            $combined_data[$i] = (object) $monthly_data;
                        }
                    }
                    $due = DB::table('contact_payable_items')->where('academic_year_id', $value->academic_year_id)->where('class_id', $value->class_id)->where('contact_id', $value->contact_id)->sum('contact_payable_items.due');
                    $info[] = [
                        'student_name' => $value->student_name,
                        'student_id' => $value->student_id,
                        'class_name' => $value->class_name,
                        'section_name' => $value->section_name,
                        'shift_name' => $value->shift_name,
                        'paid_amount' => $paid_amount,
                        'amount' => $amount,
                        'monthly_amount' => $combined_data,
                        'fixed_tuition_fee' => $fixed_tuition_fee,
                        'due' => $due
                    ];
                }
                foreach ($total_paid_amount as $key => $values) {
                    $sum_paid = array_sum($values);
                    $sum_fixed = array_sum($total_fixed_amount[$key]);
                    $total_fixed_paid_amount_each_product[$key] = [
                        'total_paid' => $sum_paid,
                        'total_fixed' => $sum_fixed
                    ];
                }
                foreach ($fixed_tuition_fee_sum as $key => $values) {
                    $sum_fixed = array_sum($values);
                    $sum_paid = array_sum($month_paid_sum[$key]);
                    $total_fixed_paid_monthly[$key] = [
                        'monthly_total_paid' => $sum_paid,
                        'monthly_total_fixed' => $sum_fixed
                    ];
                }
            }

            // echo "<pre>";
            // print_r($total_fixed_paid_amount_each_product);
            // exit();
        }
        // exit();
        return view('Report::collectionReport.studentCollection', compact('class_list', 'academic_year_list', 'student_list', 'request', 'model', 'product', 'month', 'shiftList', 'versionList', 'groupList', 'transportList', 'info', 'item', 'studentTypeList', 'items', 'type', 'total_fixed_paid_amount_each_product', 'total_fixed_paid_monthly'));
    }
    public function studentReportFilter(Request $request)
    {
        $url = 'student-collection-report?search=true&yearId=' . $request->academic_year_id . '&class_id=' . $request->class_id . '&student_id=' . urlencode(json_encode($request->student_id)) . '&section_id=' . urlencode(json_encode($request->section_id)) . '&student_type_id=' . urlencode(json_encode($request->student_type_id)) . '&shift_id=' . urlencode(json_encode($request->shift_id)) . '&group_id=' . $request->group_id . '&gender_id=' . $request->gender_id . '&transport_id=' . urlencode(json_encode($request->transport_id)) . '&version_id=' . $request->version_id . '&admission_type=' . $request->admission_type . '&to_date=' . $request->to_date . '&from_date=' . $request->from_date . '&type=' . $request->type;
        $url .= '&page=' . $request->page;
        return redirect($url);
    }
    public function getStudents(Request $request)
    {
        $data = [];
        if ($request->typeId == 1) {
            $data = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id');
            if (!empty($request->yearId)) {
                $data = $data->where('contact_academics.academic_year_id', $request->yearId);
            }
            if (!empty($request->classId)) {
                $data = $data->where('contact_academics.academic_year_id', $request->yearId)->where('contact_academics.class_id', $request->classId);
            }
            $data = $data->where('contacts.is_trash', 0)->where('contacts.type', $request->typeId)->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))->get();
        } elseif ($request->typeId == 6) {
            $data = DB::table('contacts')
                ->where('contacts.is_trash', 0)->where('contacts.type', $request->typeId)->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/CID: ",IFNULL(contacts.contact_id,"")) as full_name'))->get();
        } else {
            $data1 = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id');
            if (!empty($request->yearId)) {
                $data1 = $data1->where('contact_academics.academic_year_id', $request->yearId);
            }
            if (!empty($request->classId)) {
                $data1 = $data1->where('contact_academics.academic_year_id', $request->yearId)->where('contact_academics.class_id', $request->classId);
            }
            $data1 = $data1->where('contacts.is_trash', 0)->where('contacts.type', 1)->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))->get();

            $data2 = DB::table('contacts')->where('contacts.is_trash', 0)->where('contacts.type', 6)->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/CID: ",IFNULL(contacts.contact_id,"")) as full_name'))->get();

            $data = $data1->CONCAT($data2);
        }
        return response()->json($data);
    }

    public function getEmployee(Request $request)
    {

        $data = [];
        $data = DB::table('employee_payroll')
            ->where('academic_year_id', $request->yearId)
            ->where('month_id', $request->monthId)
            ->leftJoin('contacts', 'contacts.id', 'employee_payroll.employee_id')
            ->where('contacts.type', 5)
            ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"(",IFNULL(contacts.cp_phone_no,""),")") as full_name'))
            ->get();
        return response()->json($data);
    }

    public function smsReport(Request $request)
    {
        $model = [];
        $info = [];
        $studentInfo = [];
        $from_date = 0;
        $to_date = 0;
        $pageTitle = '';
        $academic_year_list = ['0' => 'All'] + DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $class_list = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $smsType = ['0' => 'All'] + DB::table('enum_sms_type')->pluck('name', 'id')->toArray();
        if ($request->search == true) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $model = DB::table('msgnotificationsents')
                ->join('contacts', 'contacts.id', 'msgnotificationsents.CustomerId')
                ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->where('contact_academics.status', 'active');
            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $model = $model->whereBetween(DB::raw('str_to_date(msgnotificationsents.created_at, "%Y-%m-%d")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            if (!empty($request->sms_type) && ($request->sms_type != 0)) {
                $model = $model->where('msgnotificationsents.sms_type', $request->sms_type);
            }
            if (!empty($request->status)) {
                $model = $model->where('msgnotificationsents.ErrorCode', $request->status);
            }
            $model = $model->select('contacts.full_name as student_name', 'contacts.id as student_id', 'contacts.contact_id as sid', 'classes.name as class_name', 'msgnotificationsents.SentMessage as msg', 'msgnotificationsents.MobileNumbers as mobile_number', 'msgnotificationsents.ErrorCode as status', 'msgnotificationsents.sms_count')->get();
        }
        return view('Report::smsReport.index', compact('request', 'from_date', 'to_date', 'model', 'pageTitle', 'academic_year_list', 'class_list', 'smsType'));
    }
    public function smsReportFilter(Request $request)
    {
        $url = 'sms-report?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date . '&sms_type=' . $request->sms_type . '&status=' . $request->ErrorCode;
        return redirect($url);
    }

    // for exam seat plan
    public function accountClearenceIndex(Request $request)
    {
        // destroy permission check
        if (is_null($this->user) || !$this->user->can('exam.seat.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();

        if ($request->ajax()) {
            $model = DB::table('contacts')
                ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
                ->join('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftJoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftJoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->join('contact_payable_items', 'contact_payable_items.contact_id', 'contacts.id');
            if ($request->class_id) {
                $model = $model->where('contact_academics.class_id', $request->class_id);
            }
            if (!empty($request->section_id)) {
                $model = $model->where('contact_academics.section_id', $request->section_id);
            }
            $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->whereNot('contacts.status', 'cancel');

            $students = $model->select('contacts.id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academics_id', 'contacts.contact_id as student_id', 'contacts.photo as image', DB::raw('SUM(contact_payable_items.due) as total_due'))
                ->groupBy('contacts.id')
                ->havingRaw('COALESCE(SUM(contact_payable_items.due), 0) = 0')
                ->get();
            return Datatables::of($students)->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkStudent_' . $row->contact_academics_id . '" name="contact_academic_id[]" value="' . $row->contact_academics_id . '" keyValue="' . $row->contact_academics_id . '" onclick="unCheck(this.id);isChecked();">';
                    return $btn;
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Report::accountClearence.index', compact('classList', 'academicYearList', 'currentYear'));
    }

    public function accountClearenceGenerate(Request $request)
    {

        try {
            $contactAcademicId = $request->contact_academic_id;
            $studentClassId = $request->class_name;
            $sessionId = $request->academic_year;
            $print_id = $request->design_option;
            $pageTitle = 'Account Clearence Report';

            if (!empty($contactAcademicId)) {
                $studentList = fetchStudentDetail($contactAcademicId, $studentClassId, $sessionId, $pageTitle);
                return view('Report::accountClearence.clearence', compact('studentList', 'print_id', 'pageTitle'));
            }
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function dueReport(Request $request)
    {
        $pageTitle = "Collection Report";
        $data = DB::table('sales')->Where('status', 'active')->get();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $item_list = DB::table('products')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $class_list = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $month_list = DB::table('enum_month')->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $model = [];
        $model1 = [];
        $model2 = [];
        $studentInfo = [];
        $from_date = 0;
        $to_date = 0;
        $className = '';
        $yearName = '';
        $data_array = [];

        if ($request->search == 'true') {
            // dd($request->all());
            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            $data = DB::table('contacts')->join('contact_academics', 'contacts.id', 'contact_academics.contact_id')->where('contacts.status', 'active')->where('contact_academics.academic_year_id', $request->yearId)->where('contact_academics.status', 'active')->where('contacts.is_trash', 0)->select('contacts.*', 'contact_academics.class_id')->get();
            foreach ($data as $value) {
                $contact_payemnt_check = DB::table('contact_payable_items')->where('contact_payable_items.contact_id', $value->id)->where('contact_payable_items.month_id', $request->month)->where('contact_payable_items.academic_year_id', $request->yearId)->first();
                if (empty($contact_payemnt_check)) {
                    $class = DB::table('classes')->where('id', $value->class_id)->first();
                    $data_array[] = array('sid' => $value->contact_id, 'name' => $value->full_name, 'class' => $class->name);
                }
            }
            // dd($data_array);

        }

        return view('Report::dueReport.dueReport', compact('pageTitle', 'data', 'from_date', 'to_date', 'model', 'request', 'shift_list', 'class_list', 'academic_year_list', 'className', 'yearName', 'item_list', 'month_list', 'data_array'));
    }

    public function dueReportFilter(Request $request)
    {

        $url = 'due-report?search=true&yearId=' . $request->academic_year_id . '&month=' . $request->month_id;
        return redirect($url);
    }

    // If payment_status == 1 in admission collection controller
    private function processPaymentStatusOne($request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $shift = json_decode($request->shift_id);
        $studentTypeId = json_decode($request->student_type_id);
        $item = json_decode($request->item_id);
        $student = json_decode($request->student_id);
        $paymentStatus = $request->payment_status_id;
        $className = DB::table('classes')->where('id', $request->class_id)->pluck('name')->first();
        $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();

        $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
        if ($request->type_id == 1) {
            $model = DB::table('sales_product_relation')->Where('sales_product_relation.status', 'active')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->join('products', 'sales_product_relation.product_id', 'products.id');

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $model->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model = $model->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model = $model->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model = $model->whereIn('sales.customer_id', $studentInfo2)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
            }
            if (!empty($request->yearId)) {
                $model = $model->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            $model = $model->join('contacts', 'sales.customer_id', 'contacts.id')
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);
        } elseif ($request->type_id == 6) {
            $model = DB::table('sales_product_relation')
                ->Where('sales_product_relation.status', 'active')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->join('products', 'sales_product_relation.product_id', 'products.id')
                ->join('contacts', 'sales.customer_id', 'contacts.id')
                ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6);
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $model->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            if (!empty($item)) {
                $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
            }
            $model = $model->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);
        } else {
            $model1 = DB::table('sales_product_relation')->Where('sales_product_relation.status', 'active')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->join('products', 'sales_product_relation.product_id', 'products.id');

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $model1->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('sales.customer_id', $studentInfo)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('sales.customer_id', $studentInfo2)->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
            }
            if (!empty($student)) {
                $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
            }
            if (!empty($request->yearId)) {
                $model1 = $model1->where('sales_product_relation.academic_year_id', $request->yearId);
            }
            $model1 = $model1->join('contacts', 'sales.customer_id', 'contacts.id')
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);

            $model2 = DB::table('sales_product_relation')
                ->Where('sales_product_relation.status', 'active')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->join('products', 'sales_product_relation.product_id', 'products.id')
                ->join('contacts', 'sales.customer_id', 'contacts.id')
                ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6);
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $model2->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            if (!empty($item)) {
                $model2 = $model2->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales_product_relation.product_id', $item);
            }
            if (!empty($student)) {
                $model2 = $model2->where('sales_product_relation.academic_year_id', $request->yearId)->whereIn('sales.customer_id', $student);
            }
            $model2 = $model2->get(['sales.*', 'sales_product_relation.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);

            $model = $model1->concat($model2);
        }
        return $model;
    }

    // If payment_status == 2 in admission collection controller
    private function processPaymentStatusTwo($request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $shift = json_decode($request->shift_id);
        $studentTypeId = json_decode($request->student_type_id);
        $item = json_decode($request->item_id);
        $student = json_decode($request->student_id);
        $paymentStatus = $request->payment_status_id;
        $className = DB::table('classes')->where('id', $request->class_id)->pluck('name')->first();
        $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();

        $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
        if ($request->type_id == 1) {
            $model = DB::table('contact_payable_items')->Where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id');

            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }

            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo2)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            if (!empty($request->yearId)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            $model = $model->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftJoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftJoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);
        } elseif ($request->type_id == 6) {
            $model = DB::table('contact_payable_items')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id')
                ->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6);

            if (!empty($item)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            $model = $model->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);
        } else {
            $model1 = DB::table('contact_payable_items')->Where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id');

            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo2)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            if (!empty($request->yearId)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            $model1 = $model1->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftJoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftJoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name']);

            $model2 = DB::table('contact_payable_items')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id')
                ->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6);

            if (!empty($item)) {
                $model2 = $model2->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model2 = $model2->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            $model2 = $model2->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name']);

            $model = $model1->concat($model2);
        }
        return $model;
    }

    private function processPaymentStatusThree($request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $shift = json_decode($request->shift_id);
        $studentTypeId = json_decode($request->student_type_id);
        $item = json_decode($request->item_id);
        $student = json_decode($request->student_id);
        $paymentStatus = $request->payment_status_id;
        $className = DB::table('classes')->where('id', $request->class_id)->pluck('name')->first();
        $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();

        $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;


        if ($request->type_id == 1) {
            $model = DB::table('contact_payable_items')->Where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id');

            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }

            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model = $model->whereIn('contact_payable_items.contact_id', $studentInfo2)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            if (!empty($request->yearId)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            $model = $model->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftJoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftJoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->where('contact_payable_items.is_trash', 0)
                ->groupBy('contact_payable_items.contact_id')
                ->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name', DB::raw('sum(contact_payable_items.due) as total_due'), DB::raw('sum(contact_payable_items.paid_amount) as total_paid')]);
        } elseif ($request->type_id == 6) {
            $model = DB::table('contact_payable_items')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id')
                ->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6)
                ->groupBy('contact_payable_items.contact_id');

            if (!empty($item)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model = $model->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            $model = $model->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', DB::raw('sum(contact_payable_items.due) as total_due'), DB::raw('sum(contact_payable_items.paid_amount) as total_paid')]);
        } else {
            $model1 = DB::table('contact_payable_items')->Where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id');

            if (!empty($shift)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('shift_id', $shift)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($studentTypeId)) {
                $studentInfo = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->whereIn('student_type_id', $studentTypeId)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($request->class_id)) {
                $studentInfo2 = DB::table('contact_academics')->where('academic_year_id', $request->yearId)
                    ->where('class_id', $request->class_id)->pluck('contact_id')->toArray();
                $model1 = $model1->whereIn('contact_payable_items.contact_id', $studentInfo2)->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            if (!empty($item)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            if (!empty($request->yearId)) {
                $model1 = $model1->where('contact_payable_items.academic_year_id', $request->yearId);
            }
            $model1 = $model1->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftJoin('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->leftJoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->where('contact_academics.academic_year_id', $request->yearId)
                ->where('contacts.type', 1)
                ->where('contacts.status', 'active')
                ->where('contact_academics.status', 'active')
                ->groupBy('contact_payable_items.contact_id')
                ->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', 'classes.name as class_name', DB::raw('sum(contact_payable_items.due) as total_due'), DB::raw('sum(contact_payable_items.paid_amount) as total_paid')]);

            $model2 = DB::table('contact_payable_items')
                ->where('contact_payable_items.is_paid', 0)
                ->where('contact_payable_items.is_trash', 0)
                ->join('products', 'contact_payable_items.product_id', 'products.id')
                ->join('contacts', 'contact_payable_items.contact_id', 'contacts.id')
                ->leftjoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('contacts.status', 'active')
                ->where('contacts.type', 6)
                ->groupBy('contact_payable_items.contact_id');

            if (!empty($item)) {
                $model2 = $model2->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.product_id', $item);
            }
            if (!empty($student)) {
                $model2 = $model2->where('contact_payable_items.academic_year_id', $request->yearId)->whereIn('contact_payable_items.contact_id', $student);
            }
            $model2 = $model2->get(['contact_payable_items.*', 'contacts.full_name as student_name', 'products.name as product_name', 'enum_month.short_name as month_name', 'academic_years.year as year_name', DB::raw('sum(contact_payable_items.due) as total_due'), DB::raw('sum(contact_payable_items.paid_amount) as total_paid')]);

            $model = $model1->concat($model2);
        }
        return $model;
    }

    public function searchCompany(){
        return view('Report::dueReport.searchCompany');
    }

    public function generateCompany(){
        $reports = DB::table('company_report')->orderBy('id', 'desc')->get();
        return view('Report::dueReport.generateCompany', compact('reports'));
    }

    public function searchCompanySubmit(Request $request){
            // Get company_name from form input
        $companyName = $request->input('company_name');

        // Prepare data for API request
        $postData = [
            "appToken" => "584aa18e05e448fe8f47128f62d9ac65",
            "module"   => "RP001",
            "args"     => json_encode([
                "keyWord"    => $companyName,
                "areaCode"   => "*",
                "pageSize"   => 10,
                "pageNum"    => 1,
                "searchType" => 1,
                "language"   => "2"
            ])
        ];

        // Initialize CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://service.so315.cn/union/ent/v2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Cookie: JSESSIONID=EA5A16A7393AD56B3523C7404BD3F875; JSESSIONID=1FA1C8F660912E42278F171C188336E0"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        // Execute and get response
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response
        $result = json_decode($response, true);
        
        // Pass data to view
        return view('Report::dueReport.searchCompany', compact('result', 'companyName'));
    }

    public function generateCompanyOrder(Request $request)
    {
        // If id and company_name are provided, call API and save record
        if ($request->has(['id', 'company_name'])) {
            $orgId = $request->id;
            $companyName = $request->company_name;
            $name = $request->name;

            // Prepare data for API
            $postData = [
                "args" => [
                    "reportType"      => "13",
                    "reportSpeed"     => "1",
                    "chsName"         => $companyName,
                    "reportLanguage"  => "2",
                    "orgId"           => $orgId
                ],
                "appToken" => "3a22cd6733494fbaba56f210210ce2eb",
                "module"   => "RP002"
            ];

            // Hit the CURL API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://service.so315.cn/union/ent/v2");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Cookie: JSESSIONID=35C16795E1CC6BCFC2DA3CF08BBE13B0"
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            // Store data in company_report table if response success
            if (isset($result['code']) && $result['code'] === "0001") {
                DB::table('company_report')->insert([
                    'company_name' => $name,
                    'country'      => 'China',
                    'orderId'      => $result['result']['orderId'],
                    'orgId'        => $result['result']['orgId'],
                    'status'       => 'pending',
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);
            }
        }

        // Fetch all records from company_report to show in the view
        $reports = DB::table('company_report')->orderBy('id', 'desc')->get();

        return view('Report::dueReport.generateCompany', compact('reports'));
    }

    public function downloadReport(Request $request)
    {
        $orderId = $request->orderId;

        // Prepare data for API
        $postData = [
            "args" => [
                "orderId"        => '2025091314390560335670371412',//$orderId
                "reportLanguage" => "2"
            ],
            "appToken" => "3a22cd6733494fbaba56f210210ce2eb",
            "module"   => "RP004"
        ];

        // Call API using CURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://service.so315.cn/union/ent/v2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Cookie: JSESSIONID=F83BA2DECE2841022099770119444E1C"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);


        // Basic order info
        $orderId = $result['result']['order']['orderId'];
        $completionDate = $result['result']['order']['dateOfCompletion'];
        $orgId = $result['result']['order']['orgId'];
        $orgName = $result['result']['order']['orgName'];

        // Order info
        $companyName = $result['result']['orderInfo']['name'];

        // Basic company info
        $chineseName = $result['result']['basic']['chineseName'];
        $englishName = $result['result']['basic']['englishName'];
        $enterpriseState = $result['result']['basic']['enterpriseState'];

        // Contact info
        $address = $result['result']['contact']['address'];
        $addressDetail = $result['result']['contact']['addressDetail'];
        $email = $result['result']['contact']['mailbox'];
        $telephone = $result['result']['contact']['telephone'];

        // Registration info
        $legalRepresentative = $result['result']['register']['legalRepresentative'];
        $registeredCapital = $result['result']['register']['registeredCapital'];
        $registeredCurrency = $result['result']['register']['registeredCurrency'];
        $registrationNumber = $result['result']['register']['registrationNumber'];
        $socialCreditCode = $result['result']['register']['socialCreditCode'];
        $timeOfEstablishment = $result['result']['register']['timeOfEstablishment'];
        $theNatureOfTheEnterprise = $result['result']['register']['theNatureOfTheEnterprise'];
        $legalScopeOfOperation = $result['result']['register']['legalScopeOfOperation'];
        $collectionOfCapital = $result['result']['register']['collectionOfCapital'];
        $registrationAuthority = $result['result']['register']['registrationAuthority'];
        
        

        $regModifys = $result['result']['regModifys'];

        $directorSupervisors = $result['result']['directorSupervisors'];
        $managers = $result['result']['managers'];

        // Shareholders (example: first shareholder)
        $firstShareholder = $result['result']['shareholders'][0]['basic']['shareholderName'] ?? null;
        $firstShareholderCapital = $result['result']['shareholders'][0]['basic']['capitalContribution'] ?? null;
        $theCountryOfShareholders = $result['result']['shareholders'][0]['basic']['theCountryOfShareholders'] ?? null;
        
        // Website list
        $websites = array_map(fn($w) => $w['website'], $result['result']['websites']);

        // Credit rating
        $creditRating = $result['result']['creditRating']['creditRating'];
        $creditScore = $result['result']['creditRating']['creditScore'];
        $basicCreditLine = $result['result']['creditRating']['basicCreditLine'];
        

        // Financial highlights (example: latest year)
        $latestFinancial = end($result['result']['financialHighlights']);
        $totalAssets = $latestFinancial['financialHighlightAsset']['totalAssets'] ?? null;
        $totalLiabilities = $latestFinancial['financialHighlightBalance']['balanceOfLiabilities'] ?? null;
        $shareholderEquity = $latestFinancial['financialHighlightBalance']['shareholderEquity'] ?? null;
        $grossRevenue = $latestFinancial['financialHighlightProfit']['grossRevenue'] ?? null;
        $netProfit = $latestFinancial['financialHighlightProfit']['netProfit'] ?? null;

        // Pass these variables to blade
        return view('Report::dueReport.reportPage', compact(
            'orderId', 'completionDate', 'orgId', 'orgName',
            'companyName', 'chineseName', 'englishName', 'enterpriseState',
            'address', 'addressDetail', 'email', 'telephone',
            'legalRepresentative', 'registeredCapital', 'registeredCurrency',
            'registrationNumber', 'socialCreditCode', 'timeOfEstablishment',
            'firstShareholder', 'firstShareholderCapital',
            'websites', 'creditRating', 'creditScore',
            'totalAssets', 'totalLiabilities', 'shareholderEquity', 'grossRevenue', 'netProfit',
            'theNatureOfTheEnterprise','legalScopeOfOperation','collectionOfCapital','basicCreditLine',
            'registrationAuthority','regModifys','theCountryOfShareholders','directorSupervisors','managers'
        ));

    }

}
