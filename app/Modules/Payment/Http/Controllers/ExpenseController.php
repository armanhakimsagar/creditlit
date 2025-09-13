<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\AccountCategory;
use App\Modules\Payment\Models\AccountList;
use App\Modules\Payment\Models\Cashbank;
use App\Modules\Payment\Models\OthersPayment;
use App\Modules\Payment\Models\SalesChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ExpenseController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function expenseChartIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales_chart')->where('is_trash', 0)->whereNot('status', 'cancel')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        $status = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        $status = '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Cancel</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('edit.expense.chart', [$row->id]) . '" class=" btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('delete.expense.chart', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::expenseChart.index');
    }
    public function createExpenseChart()
    {
        $editTitle = false;
        return view('Payment::expenseChart.create', compact('editTitle'));
    }
    public function storeExpenseChart(Request $request)
    {
        // check is that expense chart has in trash ???
        $expenseChartName = $request->name;
        $findSameId = DB::table('sales_chart')->where('name', $expenseChartName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Expense Chart Already has in Trash, Please Restore <a href="' . route('expense.chart.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('sales_chart')->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $input = $request->all();
            $data = [];
            $errorName = [];
            $name = $request->name;
            foreach ($name as $key => $value) {
                if (DB::table('sales_chart')->where('name', $value)->where('status', 'active')->where('is_trash', 0)->doesntExist()) {

                    $data[$key]['name'] = $value;
                    $data[$key]['slug'] = Str::slug($value);
                    $data[$key]['status'] = $request->status;
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;
                    $data[$key]['is_trash'] = 0;
                } else {
                    array_push($errorName, $value);
                }

            }

            if (!empty($errorName)) {
                $nameJson = trim(json_encode($errorName), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            }
            if (!empty($data)) {
                DB::table('sales_chart')->insert($data);
                Session::flash('success', 'Expense chart is added!');
            }

            DB::commit();
            return redirect()->route('expense.chart');

        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }

    }

    public function editExpenseChart($id)
    {
        $data = SalesChart::findOrFail($id);
        $editTitle = true;
        return view('Payment::expenseChart.edit', compact('data', 'editTitle'));
    }
    public function updateExpenseChart(Request $request, $id)
    {
        // check is that expense chart has in trash ???
        $expenseChartName = $request->name[0];
        $findSameId = DB::table('sales_chart')->where('name', $expenseChartName)->where('is_trash', 1)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same Expense Chart Already has in Trash, Please Restore <a href="' . route('expense.chart.restore', [$findSameId->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'name' => Rule::unique('sales_chart')->ignore($id)->where(function ($query) {
                return $query->where('is_trash', 0);
            }),
            'status' => 'required',
        ]);

        $input = $request->all();
        $input['dealer_id'] = auth()->id();

        DB::beginTransaction();
        try {
            $data = array();
            $data['name'] = $request->name[0];
            $data['status'] = $request->status;
            $data['slug'] = Str::slug($request->name[0]);
            $data['withdraw'] = $request->withdraw;
            $data['updated_at'] = date("Y-m-d h:i:s");
            $data['updated_by'] = Auth::user()->id;

           $result = DB::table('sales_chart')->where('id', $id)->update($data);

            if ($result) {
                DB::commit();
            }
            Session::flash('success', 'Expense Successfully updated!');
            return redirect()->route('expense.chart');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
        }
    }
    public function destroyExpenseChart($id)
    {
        $model = SalesChart::where('id', $id)
            ->select('sales_chart.*')
            ->first();

        $paymentChecking = OthersPayment::where('sales_chart_id', $id)->where('status', 'active')->first();
        if (!$paymentChecking) {
            DB::beginTransaction();
            try {

                $model->is_trash = '1';
                // if ($model->status == 'active') {
                //     $model->status = 'cancel';
                // } else {
                //     $model->status = 'active';
                // }

                if ($model->save()) {
                    DB::commit();
                }
                Session::flash('success', "Head Successfully Deleted.");
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('danger', $e->getMessage());
            }

            return redirect()->back();
        } else {
            Session::flash('danger', 'Contact with support for this change!');
            return redirect()->route('expense.chart');
        }
    }
    public function expenseIndex(Request $request)
    {
        $pageTitle = "Payment Lists";
        $data = DB::table('other_payment')->leftJoin('cash_banks', 'cash_banks.invoice_no', 'other_payment.payment_invoice')->leftJoin('sales_chart', 'sales_chart.id', 'other_payment.sales_chart_id')->where('other_payment.is_trash', 0)->whereNot('other_payment.status', 'cancel')->select('other_payment.*', 'sales_chart.name as chart_name')->orderBy('other_payment.id', 'asc')->get();
        if ($request->ajax()) {
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        $status = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        $status = '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Cancel</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('edit.expense', [$row->id]) . '" class=" btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('delete.expense', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::expense.index', compact('pageTitle'));
    }
    public function createExpense()
    {
        $pageTitle = "Add New Expense";
        $chartList = ['' => 'Select Expense Chart'] + SalesChart::where('status', 'active')->where('is_trash', '0')->pluck('name', 'id')->all();
        $editTitle = false;
        $accountCategory = ['' => 'Please Select Payment Method'] + DB::table('accountcategorys')->where('status', 'active')->pluck('TypeName', 'id')->all();

        return view('Payment::expense.create', compact('pageTitle', 'chartList', 'accountCategory', 'editTitle'));
    }
    public function getAccount(Request $request)
    {
        $response = [];

        $response['data'] = '';
        $response['type_id'] = '';

        $payment_method = $_POST['payment_method'];

        $model2 = AccountList::where('AccountCategoryId', $payment_method)->get();

        $account_type = AccountCategory::where('id', $payment_method)->first();

        if (!empty($model2)) {

            $response['data'] .= "<option value=''>Select Payment Account</option>";

            foreach ($model2 as $data) {
                $response['data'] .= '<option value="' . $data['id'] . '">' . $data['ShortName'] . '</option>';
            }
        }
        $response['type_id'] = $account_type->AccountTypeId;
        $response['result'] = 'success';
        return $response;
    }

    // Store Expense
    public function storePayment(Request $request)
    {
        $input = $request->all();
        $input['dealer_id'] = auth()->id();
        if ($input['receive_type'] == 'consumption' || $input['receive_type'] == 'systemloss' || $input['receive_type'] == 'gift' || $input['receive_type'] == 'wastage' || $input['receive_type'] == 'cnf') {

            $input['AccountTypeId'] = 1;
            $input['AccountCategoryId'] = 1;
        }
        DB::beginTransaction();
        try {
            $data = [];
            $invoiceId = [];
            $other_payment_data = [];
            $payment_amount = $input['payment_amount'];

            if ($input['receive_type'] == 'regular') {
                foreach ($payment_amount as $key => $value) {
                    if ($input['AccountTypeId'][$key] == '1') {
                        $input['transaction_type'] = 'cash';
                    } else {
                        $input['transaction_type'] = 'bank';
                    }
                    $account_type_id=DB::table('accountcategorys')->where('id',$input['AccountCategoryId'][$key])->select('AccountTypeId')->first();
                    $input['AccountTypeId'][$key]=$account_type_id->AccountTypeId;
                    if ($value > 0) {
                        $invoiceId = $this->generateExpenseInvoice($key + 1);
                        $data[$key]['amount'] = $value * -1;
                        $data[$key]['source_flag'] = 'other_expense';
                        $data[$key]['invoice_date'] = $input['payment_date'];
                        $data[$key]['invoice_no'] = $invoiceId['invoice'];
                        $data[$key]['payment_type'] = $input['transaction_type'];
                        $data[$key]['note'] = $input['note'][$key];
                        $data[$key]['cheque_no'] = $input['cheque_no'];
                        $data[$key]['status'] = $request->status;
                        $data[$key]['created_at'] = date("Y-m-d h:i:s");
                        $data[$key]['dealer_id'] = Auth::user()->id;
                        $data[$key]['created_by'] = Auth::user()->id;

                        $other_payment_data[$key]['sales_chart_id'] = $input['sales_chart_id'][$key];
                        $other_payment_data[$key]['payment_amount'] = $input['payment_amount'][$key];
                        $other_payment_data[$key]['transaction_type'] = $input['transaction_type'];
                        $other_payment_data[$key]['payment_invoice'] = $invoiceId['invoice'];
                        $other_payment_data[$key]['payment_date'] = $input['payment_date'];
                        $other_payment_data[$key]['AccountCategoryId'] = $input['AccountCategoryId'][$key];
                        $other_payment_data[$key]['AccountTypeId'] = $input['AccountTypeId'][$key];
                        $other_payment_data[$key]['AccountId'] = $input['AccountId'][$key];
                        $other_payment_data[$key]['note'] = $input['note'][$key];
                        $other_payment_data[$key]['receive_type'] = $input['receive_type'];
                        $other_payment_data[$key]['status'] = $request->status;
                        $other_payment_data[$key]['dealer_id'] = Auth::user()->id;
                        $other_payment_data[$key]['created_by'] = Auth::user()->id;
                        $other_payment_data[$key]['created_at'] = date("Y-m-d h:i:s");
                    }
                }
            }
            DB::table('cash_banks')->insert($data);
            $other_payment = DB::table('other_payment')->insert($other_payment_data);
            if ($input['receive_type'] == 'consumption' || $input['receive_type'] == 'systemloss' || $input['receive_type'] == 'gift' || $input['receive_type'] == 'wastage' || $input['receive_type'] == 'cnf') {
                for ($i = 0; $i < count($input['product_id']); $i++) {

                    $details = DB::table('other_payment_details')->insert([
                        'other_payment_id' => $other_payment->id,
                        'product_id' => $input['product_id'][$i],
                        'consumption_quantity' => $input['item_quantity'][$i],
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => Auth::user()->id,
                    ]);
                    // stockUpdateSale($input['product_id'][$i], $input['item_quantity'][$i]);
                }
            }

            if ($other_payment) {
                DB::commit();
                Session::flash('success', 'Expense added successfully !');
                return redirect()->route('expense.index');
            }
            return redirect()->back();

        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // Edit Expense
    public function editExpense($id)
    {

        $pageTitle = "Update Payment List";
        $editTitle = true;

        $data = OthersPayment::where('id', $id)
            ->select('other_payment.*')
            ->first();
        $chartList = ['' => 'Select Chart of account'] + SalesChart::where('status', 'active')->pluck('name', 'id')->all();
        $data_cashbank = CashBank::where('invoice_no', $data->payment_invoice)
            ->select('cash_banks.*')
            ->first();
        $accountCategory = ['' => 'Please Select Payment Method'] + DB::table('accountcategorys')->where('status', 'active')->pluck('TypeName', 'id')->all();
        $account_category_select = AccountCategory::where('id', $data->AccountCategoryId)->first(['id']);
        $account = AccountCategory::where('id', $data->AccountTypeId)->first();

        if (empty($data)) {
            Session::flash('danger', 'Payment List not found.');
            return redirect()->route('expense.index');
        }
        // Return view
        return view("Payment::expense.edit", compact('data', 'pageTitle', 'data_cashbank', 'chartList', 'accountCategory', 'account_category_select', 'account', 'editTitle'));
    }

    // Update Expense
    public function updateExpense(Request $request, $id)
    {
        $input = $request->all();

        $input['dealer_id'] = auth()->id();

        if ($input['AccountTypeId'][0] == '1') {
            $input['transaction_type'] = 'cash';
        } else {
            $input['transaction_type'] = 'bank';
        }
        
        if ($input['receive_type'] == 'consumption' || $input['receive_type'] == 'systemloss' || $input['receive_type'] == 'gift' || $input['receive_type'] == 'wastage' || $input['receive_type'] == 'cnf') {

            $input['AccountTypeId'][0] = 1;
            $input['AccountCategoryId'][0] = 1;
        }
        DB::beginTransaction();
        try {
            $model = OthersPayment::find($id);


            if ($input['receive_type'] == 'regular') {
                $model2 = Cashbank::where('invoice_no', $model->payment_invoice)->update([
                    'invoice_date' => $input['payment_date'],
                    'cheque_no' => $input['cheque_no'],
                    'payment_type' => $input['transaction_type'],
                    'amount' => $input['payment_amount'][0] * -1,
                    'note' => $input['note'][0],
                    'dealer_id' => Auth::user()->id,
                    'status' => 'active',
                    'updated_at' => date("Y-m-d h:i:s"),
                    'updated_by' => Auth::user()->id,
                ]);
            }
            $account_type_id=DB::table('accountcategorys')->where('id',$input['AccountCategoryId'][0])->select('AccountTypeId')->first();
            $input['AccountTypeId'][0]=$account_type_id->AccountTypeId;
            $result = $model->update([
                'payment_date' => $input['payment_date'],
                'sales_chart_id' => $input['sales_chart_id'][0],
                'payment_amount' => $input['payment_amount'][0],
                'transaction_type' => $input['transaction_type'],
                'dealer_id' => $input['dealer_id'],
                'status' => $input['status'],
                'AccountCategoryId' => $input['AccountCategoryId'][0],
                'AccountTypeId' => $input['AccountTypeId'][0],
                'AccountId' => $input['AccountId'][0],
                'note' => $input['note'][0],
            ]);
            $updated_other_expesne = DB::table('other_payment_details')->where('other_payment_id', $id)->get();
            // foreach ($updated_other_expesne as $other_expesne_value) {

            //     stockUpdateHistory($other_expesne_value->product_id, $other_expesne_value->consumption_quantity);
            // }
            $delete_details = DB::table('other_payment_details')->where('other_payment_id', $id)->delete();
            if ($input['receive_type'] == 'consumption' || $input['receive_type'] == 'systemloss' || $input['receive_type'] == 'gift' || $input['receive_type'] == 'wastage' || $input['receive_type'] == 'cnf') {

                for ($i = 0; $i < count($input['product_id']); $i++) {
                    $details = DB::table('other_payment_details')->insert([
                        'other_payment_id' => $id,
                        'product_id' => $input['product_id'][$i],
                        'consumption_quantity' => $input['item_quantity'][$i],
                        'created_at' => date("Y-m-d h:i:s"),
                        'created_by' => Auth::user()->id,
                    ]);
                    // stockUpdateSale($input['product_id'][$i], $input['item_quantity'][$i]);
                }
            }
            if ($result) {
                DB::commit();
            }
            if ($input['receive_type'] == 'regular') {
                Session::flash('success', 'Payment List Successfully updated!');
                return redirect()->route('expense.index');
            } else {
                Session::flash('success', 'Consumption Is Updated!');
                return redirect()->route('expense.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->back();
    }
    public function destroyExpense($id)
    {
        $payment = OthersPayment::where('id', $id)
            ->select('other_payment.*')
            ->first();
        $dataCashbank = CashBank::where('invoice_no', $payment->payment_invoice)
            ->select('cash_banks.*')
            ->first();
        // $delete_details = OthersPaymentDetails::where('other_payment_id', $id)->get();

        DB::beginTransaction();
        try {
            $payment->is_trash = '0';
            if ($payment->status == 'active') {
                $payment->status = 'cancel';
            } else {
                $payment->status = 'active';
            }
            if ($dataCashbank->status == 'active') {
                $dataCashbank->status = 'cancel';
            } else {
                $dataCashbank->status = 'active';
            }
            // foreach($delete_details as $valuse){
            //     $delete = OthersPaymentDetails::where('id', $valuse->id)->first();
            //     if ($delete->status == 'active') {
            //     $delete->status = 'cancel';
            //     } else {
            //         $delete->status = 'active';
            //     }
            //     $delete->save();
            // }
            if ($payment->save() && $dataCashbank->save()) {
                DB::commit();
            }
            //     if($delete_details==null){
            // }else{
            //     if ($model->save()) {
            //         DB::commit();
            //     }
            // }
            Session::flash('success', "Expense Successfully Deleted.");
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->back();
    }
    public function generateExpenseInvoice($id)
    {
        $response = [];
        $response['invoice'] = '';

        $checkGenerateInvoice = DB::table('other_payment')->orderBy('id', 'DESC')->first();

        $records = DB::table('other_payment')->orderBy('id', 'DESC')->first();
        $record = (!empty($records)) ? $records->id : 0;
        //get last record
        $number = $record + $id;
        if (strlen($number) != 5) {
            $add_digits = 5 - strlen($number);
            // if ($add_digits == 5)
            //     $number = '00000' . $number;
            if ($add_digits == 4) {
                $number = '0000' . $number;
            } else if ($add_digits == 3) {
                $number = '000' . $number;
            } else if ($add_digits == 2) {
                $number = '00' . $number;
            } else if ($add_digits == 1) {
                $number = '0' . $number;
            }

        }
        //check first day in a year
        if ($record != null) {
            $invoice_no = 'INV-' . date('ymd') . '-6' . $number;
        } else {
            $invoice_no = 'INV-' . date('ymd') . '-6' . $number;
        }

        $response['invoice'] = $invoice_no;
        return $response;
    }

    // To trash expense
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales_chart')->where('is_trash', 1)->whereNot('status', 'cancel')->get();
            return Datatables::of($data)->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        $status = '<span class="badge badge-success">Active</span>';
                    } elseif ($row->status == 'inactive') {
                        $status = '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-danger">Cancel</span>';
                    }
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('expense.chart.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::expenseChart.trash');
    }

    // to restore expense chart
    public function expense_chart_restore($id)
    {
        // // destroy permission check
        // if (is_null($this->user) || !$this->user->can('class.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view this page !');
        // }

        DB::table('sales_chart')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Expense Chart Restored Successfully ");
        return redirect()->route('expense.chart');
    }
}
