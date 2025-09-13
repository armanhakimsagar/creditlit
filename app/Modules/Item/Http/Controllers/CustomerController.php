<?php

namespace App\Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\GenerateInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    public $user;

    // Construct Method
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
    }

    // To show all customer data
    public function index(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('class.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {
            $data = DB::table('contacts')
                ->where('is_trash', 0)
                ->where('type', 6)
                ->where('is_trash', 0)
                ->get();
            return Datatables::of($data)
                ->startsWithSearch()
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('customer.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('customer.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a> <a href="' . route('customer.profile', [$row->id]) . '" class="btn btn-outline-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Profile" id= "profile"><i class="fas fa-user"></i></a>';
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
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('Item::customer.index');
    }

    // To show Customer create page
    public function create()
    {
        // Create Permission check
        if (is_null($this->user) || !$this->user->can('class.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $pageTitle = "Add Customer";
        return view("Item::customer.create", compact('pageTitle'));
    }

    // To Store Customer Data
    public function store(Request $request)
    {
        // Store Permission Check
        if (is_null($this->user) || !$this->user->can('class.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // Guardian ID Generate
        $customerIdYearFind = date('Y');
        $customerIdPrefix = 'CM';
        $customerIdYear = substr($customerIdYearFind, 2);
        $totalCustomer = DB::table('contacts')->where('type', 6)->count() + 1;
        $customerLastFourDigit = sprintf("%04d", $totalCustomer);
        $customerIdGenarate = $customerIdPrefix . '' . $customerIdYear . '' . $customerLastFourDigit;
        try {
            $customerId = DB::table('contacts')->insertGetId([
                'type' => 6,
                'full_name' => $request->name,
                'contact_id' => $customerIdGenarate,
                'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                'nationality' => $request->nationality,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'cp_phone_no' => $request->cp_phone_no,
                'cp_email' => $request->email,
                'address' => $request->customer_address,
                'status' => $request->status,
                'religion_id' => $request->religion,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            Session::flash('success', "Customer Created Successfully ");
            return redirect()->route('customer.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To edit a Customer
    public function edit($id)
    {
        // Edit permission check
        if (is_null($this->user) || !$this->user->can('class.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $pageTitle = "Edit Customer";
        $customer = DB::table('contacts')->where('id', $id)->first();
        return view('Item::customer.edit', compact('pageTitle', 'customer'));
    }

    // To update customer data
    public function update(Request $request, $id)
    {
        // Update permission check
        if (is_null($this->user) || !$this->user->can('class.update')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        try {
            DB::table('contacts')->where('id', $id)->update([
                'type' => 6,
                'full_name' => $request->name,
                'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                'nationality' => $request->nationality,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'cp_phone_no' => $request->cp_phone_no,
                'cp_email' => $request->email,
                'address' => $request->customer_address,
                'status' => $request->status,
                'religion_id' => $request->religion,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            Session::flash('success', "Customer Updated Successfully ");
            return redirect()->route('customer.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy Class
    public function destroy($id)
    {
        // destroy permission check
        if (is_null($this->user) || !$this->user->can('class.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // $classCheck1 = count(DB::table('contact_academics')->where('class_id', $id)->get());
        // $classCheck2 = count(DB::table('contact_payable_items')->where('class_id', $id)->get());
        // $classCheck3 = count(DB::table('contact_payable_items_version')->where('class_id', $id)->get());
        // $classCheck4 = count(DB::table('exams')->where('class_id', $id)->get());
        // $classCheck5 = count(DB::table('generate_payable_list')->where('class_id', $id)->get());
        // $classCheck6 = count(DB::table('monthly_class_item')->where('class_id', $id)->get());
        // $classCheck7 = count(DB::table('pricing')->where('class_id', $id)->get());
        // $classCheck8 = count(DB::table('section_relations')->where('class_id', $id)->get());
        // $classCheck9 = count(DB::table('student_imp')->where('class_id', $id)->get());
        // // total count of relation other table
        // $classCheck = $classCheck1 + $classCheck2 + $classCheck3 + $classCheck4 + $classCheck5 + $classCheck6 + $classCheck7 + $classCheck8 + $classCheck9;

        DB::table('contacts')->where('id', $id)->update([
            'is_trash' => 1,
        ]);
        Session::flash('success', "Customer Successfully Removed into Trash ");
        return redirect()->back();
    }


    // Recieve Customer Payment
    public function receive_customer_payment(Request $request, $studentID)
    {
        $datam = DB::table('contacts as student')
            ->where('student.is_trash', 0)
            ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
            ->join('contacts as father', 'father_relation.target_contact', 'father.id')
            ->where('father.type', 2)
            ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
            ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
            ->where('mother.type', 3)
            ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
            ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id');
        $selected_student = DB::table('contacts')->where('contacts.id', $studentID)->join('contact_academics', 'contacts.id', 'contact_academics.contact_id')->where('contact_academics.status', 'active')->select('contacts.*', 'contact_academics.class_roll')->first();
        if ($request->academicYearId) {
            $datam->where('contact_academics.academic_year_id', $request->academicYearId);
        }
        if ($request->classId) {
            $datam->where('contact_academics.class_id', $request->classId);
        }
        if ($request->sectionId) {
            $datam->where('contact_academics.section_id', $request->sectionId);
        }

        $customerList = DB::table('contacts')
            ->where('type', 6)
            ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/Phone: ",IFNULL(contacts.cp_phone_no,"")) as full_name'))
            ->get();
        // echo "<pre>";
        // print_r($customerList);
        // exit();
        $pageTitle = "Receive Customer Payment";
        $academic_year = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $shiftList = ['0' => 'Select Shift'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $versionList = ['0' => 'Select Version'] + DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = ['0' => 'Select Group'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentList = [];
        $productlist = DB::table('products')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        $academicYear = DB::table('academic_years')->where('is_trash', 0)->latest('id')->get();
        $account_category = DB::table('accountcategorys')->where('status', 'active')->get();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Item::receive-payment.index', compact('academic_year', 'classList', 'shiftList', 'versionList', 'groupList', 'studentList', 'enumMonth', 'productlist', 'account_category', 'currentYear', 'academicYear', 'studentID', 'selected_student', 'pageTitle','customerList'));
    }



    public function getCustomerDetails(Request $request)
    {
        $response = [];
        $response['sid'] = '';
        $response['student_name'] = '';
        $response['customer_phone'] = '';
        $response['address'] = '';
        $response['data'] = '';
        $response['count'] = '';
        $response['due'] = '';
        $response['month'] = '';
        $studentID = $request->studentID;
        $student = DB::table('contacts')
            ->select('contacts.id', 'contacts.contact_id', 'contacts.full_name', 'contacts.cp_phone_no', 'contacts.address')
            ->where('contacts.status', 'active')
            ->where('contacts.id', $request->studentID)
            ->where('contacts.is_trash', 0)->first();
        $total_due = DB::table('contact_payable_items')
            ->join('products', 'contact_payable_items.product_id', 'products.id')
            ->join('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
            ->join('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
            ->where('contact_payable_items.contact_id', $request->studentID)
        // ->where('contact_payable_items.class_id',$student->class_id)
        // ->where('contact_payable_items.academic_year_id',$request->academicYearId)
            ->where('contact_payable_items.is_paid', 0)
            ->select('contact_payable_items.*', 'enum_month.name as month', 'enum_month.id as month_id', 'academic_years.year', 'products.name as product_name')
            ->sum('contact_payable_items.due');

        $generate_due = DB::table('contact_payable_items')
            ->join('products', 'contact_payable_items.product_id', 'products.id')
            ->join('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
            ->join('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
            ->where('contact_payable_items.contact_id', $request->studentID)
        // ->where('contact_payable_items.class_id',$student->class_id)
        // ->where('contact_payable_items.academic_year_id',$request->academicYearId)
            ->where('contact_payable_items.is_paid', 0)
            ->select('contact_payable_items.*', 'enum_month.name as month', 'enum_month.id as month_id', 'academic_years.year', 'products.name as product_name')
            ->get();
        $enumMonth = DB::table('enum_month')->get();
        $last_payment = DB::table('sales')->where('customer_id', $studentID)->latest('sales_invoice_date')->first(['paid_amount', 'sales_invoice_date']);
        // foreach ($enumMonth as $monthkey => $enumValue) {
        //     $monthly_payable = DB::table('monthly_class_item')
        //         ->where('academic_year_id', $request->academicYearId)
        //         ->where('month_id', $enumValue->id)->pluck('item_id')->toArray();
        //     $payable = DB::table('contact_payable_items')
        //         ->where('academic_year_id', $request->academicYearId)
        //         ->where('month_id', $enumValue->id)
        //         ->where('contact_id', $request->studentID)
        //         ->whereIn('product_id', $monthly_payable)
        //         ->count();
        //     if (count($monthly_payable) == $payable && $monthly_payable > 0 && $payable > 0) {
        //         $response['month'] .= "<td><input type='checkbox' class='allCheck all-check-box month_check' style='vertical-align:middle' id='checkSection_" . $monthkey . "' name='month_check[]' value='" . $enumValue->id . "' keyValue='" . $enumValue->id . "' onclick='this.checked=!this.checked; getMonthlyDue(" . $enumValue->id . ");' checked> <strong>" . $enumValue->name . "</strong></td>";
        //     } else {
        //         $response['month'] .= "<td><input type='checkbox' class='allCheck all-check-box month_check' style='vertical-align:middle' id='checkSection_" . $monthkey . "' name='month_check[]' value='" . $enumValue->id . "' keyValue='" . $enumValue->id . "' onclick='getMonthlyDue(" . $enumValue->id . ");'> <strong>" . $enumValue->name . "</strong></td>";
        //     }
        // }
        foreach ($generate_due as $key => $value) {
            $response['data'] .= "<tr>";
            $response['data'] .= "<td>" . ($key + 1) . "<input type='hidden' name='due_id[" . ($key + 1) . "][]' value='" . $value->id . "' placeholder='' ></td>";
            $response['data'] .= "<td>" . ($value->product_name) . "<input type='hidden' name='product_id[" . ($key + 1) . "][]' value='" . $value->product_id . "' id='product_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td>" . ($value->month) . "-" . $value->year . "<input type='hidden' name='month_id[" . ($key + 1) . "][]' value='" . $value->month_id . "' id='month_id_" . ($key + 1) . "' /><input type='hidden' name='academic_year_ids[" . ($key + 1) . "][]' value='" . $value->academic_year_id . "' id='academic_year_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td><input type='text' class='form-control' value='" . $value->amount . "' tabindex=" . ($key + 300) . " name='amount[" . ($key + 1) . "][]' id='amount-" . ($key + 1) . "' oninput='calculation()'/></td>";
            $response['data'] .= "<td><input type='text' class='form-control due_amount' value='" . $value->amount - $value->paid_amount . "' name='due_amount[" . ($key + 1) . "][]' id='due-amount-" . ($key + 1) . "' data-key='" . ($key + 1) . "' readonly=''/> <span class='error' id='due_amount_error-" . ($key + 1) . "' ></span> </td>";
            $response['data'] .= "<td><input style='' type='text' class='form-control paid_amount' value='0' name='paid_amount[" . ($key + 1) . "][]' tabindex=" . ($key + 200) . " id='paid_amount_" . ($key + 1) . "' data-key='" . ($key + 1) . "' oninput='calculation()' /><input type='hidden' value='" . $value->paid_amount . "'id='hidden-paid-" . ($key + 1) . "'></td>";

            $response['data'] .= "<td><input type='text' class='form-control' name='note[" . ($key + 1) . "][]' tabindex=" . ($key + 300) . " id='note_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td></td></tr>";
        }
        $response['sid'] = '<a href="' . route('customer.profile', ['id' => $request->studentID]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $student->contact_id . '</a>';
        $response['studentID'] = $request->studentID;
        $response['student_name'] = '<a href="' . route('customer.profile', ['id' => $request->studentID]) . '" target="_blank">' . $student->full_name . '</a>';
        $response['customer_phone'] = $student->cp_phone_no;
        $response['address'] = $student->address;
        $response['count'] = count($generate_due);
        $response['due'] = $total_due;
        $response['paid_amount'] = ($last_payment) ? $last_payment->paid_amount : 0;
        $response['sales_invoice_date'] = ($last_payment) ? $last_payment->sales_invoice_date : '';

        $response['result'] = 'success';
        return $response;
    }


    // Customer Payment Store
    public function customer_payment_store(Request $request)
    {
        $invoice_no = $this->GenerateInvoice();
        DB::beginTransaction();
        try {
            $accountCategory = DB::table('accountcategorys')->where('id', $request->category_id)->first();
            if ($accountCategory->AccountTypeId == 1) {
                $payment_type = 'cash';
            } else {
                $payment_type = 'bank';
            }
            $payment_history = DB::table('payment_history')->insertGetId([
                'payment_invoice' => $invoice_no['invoice'],
                'payment_date' => $request->payment_date,
                'customer_id' => $request->student_id,
                'payment_amount' => $request->total_paid,
                'flag' => $payment_type,
                'source' => 'payment_receive',
                'dealer_id' => Auth::user()->id,
                'status' => 'active',
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::user()->id,
                'AccountTypeId' => $accountCategory->AccountTypeId,
                'AccountCategoryId' => $request->category_id,
                // 'AccountId' => $input['payment_account'],
            ]);
            $cashbank_insert = DB::table('cash_banks')->insertGetId([
                'invoice_date' => $request->payment_date,
                'invoice_no' => $invoice_no['invoice'],
                'payment_type' => $payment_type,
                'amount' => $request->total_paid,
                'dealer_id' => Auth::user()->id,
                'customer_id' => $request->student_id,
                'source_flag' => 'payment_receive',
                'status' => 'active',
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::user()->id,
            ]);
            $sales = DB::table('sales')->insertGetId([
                'sales_type' => 'partial',
                'sales_invoice_date' => $request->payment_date,
                'customer_id' => $request->student_id,
                'sales_invoice_no' => $invoice_no['invoice'],
                'status' => 'active',
                'grand_total' => $request->total_paid,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'delivery_type' => 'regular',
                'subtotal' => $request->total_paid,
                'paid_amount' => $request->total_paid,
                'total_due' => $request->total_due,
            ]);

            $sales_payment = DB::table('sales_payment')->insertGetId([
                'sales_id' => $sales,
                'sales_payment_date' => $request->payment_date,
                'absolute_amount' => $request->total_paid,
                'grand_total' => $request->total_paid,
                'down_payment' => $request->total_paid,
                'due_payment' => 0,
                'write_of' => 0,
                'status' => 'active',
                'payment_relation_id' => $payment_history,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            foreach ($request->product_id as $key => $value) {
                if ($request->paid_amount[$key][0] > 0) {
                    $contactPayableId = $request->due_id[$key][0];
                    if ($request->due_id[$key][0] == 0) {
                        $contactPayableId = $this->insertGeneratedDueList($request->student_id, $value[0], $request->month_id[$key][0], $request->academic_year_ids[$key][0], $request->paid_amount[$key][0], $request->due_amount[$key][0], $request->amount[$key][0]);
                    }
                    if ($request->due_id[$key][0] > 0) {
                        $list_data = DB::table('contact_payable_items')->where('id', $request->due_id[$key][0])->first();
                        $paid = (string) ($list_data->paid_amount + $request->paid_amount[$key][0]);
                        $this->updateGeneratedDueList($request->due_id[$key][0], $paid, $request->due_amount[$key][0]);
                    }

                    $sales_product = DB::table('sales_product_relation')->insertGetId([
                        'sales_id' => $sales,
                        'customer_category_id' => 1,
                        'sales_group_id' => 1,
                        'product_id' => $value[0],
                        'quantity' => 1,
                        'price' => $request->paid_amount[$key][0],
                        'subtotal' => $request->paid_amount[$key][0],
                        'status' => 'active',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'actual_price' => $request->amount[$key][0],
                        'remain_due' => $request->due_amount[$key][0],
                        'note' => $request->note[$key][0],
                        'month_id' => $request->month_id[$key][0],
                        'academic_year_id' => $request->academic_year_ids[$key][0],
                        'contact_payable_id' => $contactPayableId,
                    ]);
                }

            }

            if ($request->total_paid > 0) {
                DB::commit();
                Session::flash('success', __('Student::label.ADD_SUCCESSFULL_MSG'));
                return Redirect::route('customer.payment.receipt', ['sales_id' => $sales]);
            } else {
                Session::flash('danger', __('Payment Not Possible Without(Payment Amount 0)'));
                return redirect()->back();
            }

            // return view('Payment::receive-payment.receipt');
            // return redirect()->with('payment.receipt');
        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }

    public function itemDetails(Request $request)
    {
        $response = [];
        $response['price'] = '';
        $response['error'] = '';
        $student_price = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', $request->academicYearId)->where('enum_month_id', $request->month_id)->where('class_id', $request->class_id)->where('contact_id', $request->studentID)->where('product_id', $request->itemId)->first();
        $price = DB::table('pricing')->where('pricing.class_id', $request->class_id)->where('pricing.product_id', $request->itemId)
            ->select('pricing.price')
            ->first();
        if ($student_price) {
            $response['price'] = $student_price->amount;
        } elseif ($price) {
            $response['price'] = ($price->price > 0) ? $price->price : 0;
        } else {
            $response['price'] = 0;
        }

        $check_entry = DB::table('contact_payable_items')->where('contact_id', $request->studentID)->where('academic_year_id', $request->academicYearId)->where('month_id', $request->month_id)->where('product_id', $request->itemId)->first();
        if ($check_entry) {
            $response['error'] = 'This item payment already generated/taken selected year and month';
        } else {
            $response['error'] = 1;
        }
        // $response['due_credit'] = $due_payment;
        $response['result'] = 'success';
        return $response;

    }


    // Generate Invoice
    public function GenerateInvoice()
    {
        $response = [];
        $response['invoice'] = '';

        $checkGenerateInvoice = DB::table('sales')->orderBy('id', 'DESC')->first();

        // if($checkGenerateInvoice){
        // $record =  $checkGenerateInvoice->invId;
        // }else{
        $records = DB::table('sales')->orderBy('id', 'DESC')->first();
        $record = (!empty($records)) ? $records->id : 0;
        // }

        //get last record
        $number = $record + 1;
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
            $invoice_no = 'INV-' . date('ymd') . '-4' . $number;
        } else {
            $invoice_no = 'INV-' . date('ymd') . '-4' . $number;
        }

        $response['invoice'] = $invoice_no;
        $response['result'] = 'success';
        return $response;

    }


    public function insertGeneratedDueList($contact, $product_id, $month_id, $academic_year_id, $paid_amount, $due_amount, $amount)
    {
        $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
            'contact_id' => $contact,
            'product_id' => $product_id,
            'month_id' => $month_id,
            'academic_year_id' => $academic_year_id,
            'amount' => $amount,
            'paid_amount' => $paid_amount,
            'due' => $due_amount,
            'is_paid' => ($due_amount > 0) ? 0 : 1,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
        ]);
        return $contact_payable_items;
    }


    public function updateGeneratedDueList($id, $paid, $due)
    {
        $contact_payable_items_update = DB::table('contact_payable_items')->where('id', $id)->update([
            'paid_amount' => $paid,
            'due' => $due,
            'is_paid' => ($due > 0) ? 0 : 1,
        ]);
    }


    // Customer Payment Reciept 
    public function customer_payment_receipt($id)
    {
        // echo "<pre>";
        // print_r($saleData);
        // exit();
        $saleData = DB::table('sales')
            ->where('sales.id', $id)
            ->join('contacts', 'contacts.id', 'sales.customer_id')
            ->select('sales.*', 'contacts.full_name as full_name', 'contacts.cp_phone_no', 'contacts.address')
            ->first();
        // echo "<pre>";
        // print_r($saleData);
        // exit();
        $month = DB::table('sales_product_relation')
            ->where('sales_product_relation.sales_id', $id)
            ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
            ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
            ->select('enum_month.short_name as month_name', 'academic_years.year as year')
            ->groupby('month_name', 'year')
            ->get();
        $item = DB::table('sales_product_relation')
            ->where('sales_product_relation.sales_id', $id)
            ->leftjoin('products', 'sales_product_relation.product_id', 'products.id')
            ->select('products.name as product_name')
            ->groupby('product_name')
            ->get();
        return view("Item::receive-payment.receipt", compact('saleData', 'month', 'item'));
    }

    public function customerProfile($id)
    {
           $customerData =  DB::table('contacts')->where('type',6)->where('id',$id)->select('contacts.*')->first();        
           $payments = DB::table('sales')->where('customer_id', $id)->where('status', 'active')->paginate(10);
            return view('Item::customer.profile',compact('customerData','payments','id'));
    }
    public function customerPaymentHistory($id)
    {
        $data = DB::table('sales')
            ->where('sales.id', $id)
            ->join('contacts', 'contacts.id', 'sales.customer_id')
            ->select('sales.*', 'contacts.*')
            ->first();
        $service = DB::table('sales_product_relation')
            ->where('sales_product_relation.sales_id', $id)
            ->leftjoin('products', 'sales_product_relation.product_id', 'products.id')
            ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
            ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
            ->select('products.name as product_name', 'sales_product_relation.price as price', 'enum_month.name as month_name', 'academic_years.year as year')
            ->get();
        return view('Item::customer.paymentView',compact('data','service'));
    }
}
