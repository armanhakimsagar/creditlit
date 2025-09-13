<?php

namespace App\Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class incomeReportController extends Controller
{
    public function incomeReportIndex(Request $request)
    {
        $from_date = 0;
        $pageTitle = "Income & expense Reports";
        $to_date = 0;
        $incomeInfo = [];
        $incomeInfo2 = [];
        $expenseInfo = [];
        $data = [];
        $productList = '';
        if ($request->search == 'true') {

            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            if ($from_date != null) {
                $pageTitle = "FROM : " . $from_date . ' TO : ' . $to_date;
            }

            $incomeInfo = DB::table('sales_product_relation')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->Join('products', 'sales_product_relation.product_id', 'products.id')
                ->where('sales.status', 'active')
                ->whereNot('sales_product_relation.product_id', 9);
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $incomeInfo = $incomeInfo->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            $incomeInfo2 = DB::table('sales_product_relation')
                ->join('sales', 'sales_product_relation.sales_id', 'sales.id')
                ->Join('products', 'sales_product_relation.product_id', 'products.id')
                ->Join('contact_academics', 'sales.customer_id', 'contact_academics.contact_id')
                ->where('sales.status', 'active')
                ->where('contact_academics.status', 'active')
                ->where('sales_product_relation.product_id', 9);
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $incomeInfo2 = $incomeInfo2->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            // $incomeInfo = $incomeInfo->selectRaw('sales_product_relation.product_id,sales_product_relation.price,products.name as product_name,contact_academics.shift_id as shiftId')->get()->toArray();

            $incomeInfo = $incomeInfo->selectRaw('sales_product_relation.product_id,SUM(sales_product_relation.price) as totalPrice,sales_product_relation.product_id,sales_product_relation.price,products.name as product_name')
                ->groupBy('sales_product_relation.product_id')->get()->toArray();

            $incomeInfo2 = $incomeInfo2->selectRaw('sales_product_relation.product_id,sales_product_relation.price,products.name as product_name, contact_academics.student_type_id as student_type_id')->get()->toArray();
            foreach ($incomeInfo2 as $key => $value) {
                if ($value->student_type_id == 1) {
                    $data[$value->product_id]['dcf'] = !empty($data[$value->product_id]['dcf']) ? $data[$value->product_id]['dcf'] : 0;
                    $data[$value->product_id]['dcf'] += $value->price;
                    $data[$value->product_id]['dcf_name'] = "Tuition Fees (DCF)";

                }
                if ($value->student_type_id == 2) {
                    $data[$value->product_id]['dc'] = !empty($data[$value->product_id]['dc']) ? $data[$value->product_id]['dc'] : 0;
                    $data[$value->product_id]['dc'] += $value->price;
                    $data[$value->product_id]['dc_name'] = "Tuition Fees (DC)";

                }
                if ($value->student_type_id == 3) {
                    $data[$value->product_id]['ac'] = !empty($data[$value->product_id]['ac']) ? $data[$value->product_id]['ac'] : 0;
                    $data[$value->product_id]['ac'] += $value->price;
                    $data[$value->product_id]['ac_name'] = "Tuition Fees (AC)";
                }
                if ($value->student_type_id == 4) {
                    $data[$value->product_id]['res'] = !empty($data[$value->product_id]['res']) ? $data[$value->product_id]['res'] : 0;
                    $data[$value->product_id]['res'] += $value->price;
                    $data[$value->product_id]['res_name'] = "Tuition Fees (RES)";
                }
                if ($value->student_type_id == null) {
                    $data[$value->product_id]['other'] = !empty($data[$value->product_id]['other']) ? $data[$value->product_id]['other'] : 0;
                    $data[$value->product_id]['other'] += $value->price;
                    $data[$value->product_id]['other_name'] = "Tuition Fees (Other)";
                }
            }

            $expenseInfo = DB::table('other_payment')
                ->where('other_payment.status', 'active')
                ->join('sales_chart', 'other_payment.sales_chart_id', 'sales_chart.id');
            if (!empty($request->to_date) && !empty($request->from_date)) {
                $expenseInfo = $expenseInfo->whereBetween(DB::raw('str_to_date(other_payment.payment_date, "%d-%m-%Y")'), [Carbon::parse($from_date)->format('Y-m-d'), Carbon::parse($to_date)->format('Y-m-d')]);
            }
            $expenseInfo = $expenseInfo->selectRaw('sales_chart_id,SUM(other_payment.payment_amount) as expense_price,sales_chart.name as sales_chart_name, other_payment.payment_invoice')
                ->groupBy('other_payment.sales_chart_id')->get()->toArray();

        }

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                // echo "<pre>";
                // print_r($value['dc_name']);
                // exit();
                // Add 'other_name' product
                if (isset($value['other_name'])) {
                    $incomeInfo[] = (object) [
                        'product_id' => $key,
                        'totalPrice' => $value['other'],
                        'price' => null,
                        'product_name' => $value['other_name'],
                    ];
                }

                // Add 'ac_name' product
                if (isset($value['ac_name'])) {
                    $incomeInfo[] = (object) [
                        'product_id' => $key,
                        'totalPrice' => $value['ac'],
                        'price' => null,
                        'product_name' => $value['ac_name'],
                    ];
                }

                // Add 'dc_name' product
                if (isset($value['dc_name'])) {
                    $incomeInfo[] = (object) [
                        'product_id' => $key,
                        'totalPrice' => $value['dc'],
                        'price' => null,
                        'product_name' => $value['dc_name'],
                    ];
                }

                // Add 'dcf_name' product
                if (isset($value['dcf_name'])) {
                    $incomeInfo[] = (object) [
                        'product_id' => $key,
                        'totalPrice' => $value['dcf'],
                        'price' => null,
                        'product_name' => $value['dcf_name'],
                    ];
                }

                // Add 'res_name' product
                if (isset($value['res_name'])) {
                    $incomeInfo[] = (object) [
                        'product_id' => $key,
                        'totalPrice' => $value['res'],
                        'price' => null,
                        'product_name' => $value['res_name'],
                    ];
                }
            }

        }

        return view('Report::incomeReport.index', compact('request', 'from_date', 'to_date', 'incomeInfo', 'expenseInfo', 'data', 'pageTitle'));
    }

    public function incomeReportFilter(Request $request)
    {
        $url = 'income-report-index?search=true&from_date=' . $request->from_date . '&to_date=' . $request->to_date;
        return redirect($url);

    }

}
