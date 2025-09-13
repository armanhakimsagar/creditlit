<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Models\GenerateInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\App;

class PaymentController extends Controller
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

    public function receive_payment(Request $request, $studentID)
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
        return view('Payment::receive-payment.index', compact('academic_year', 'classList', 'shiftList', 'versionList', 'groupList', 'studentList', 'enumMonth', 'productlist', 'account_category', 'currentYear', 'academicYear', 'studentID', 'selected_student'));
    }

    public function getstudent(Request $request)
    {

        $model = DB::table('contacts')
            ->join('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->join('classes', 'classes.id', 'contact_academics.class_id')
            ->where('contact_academics.status', 'active')
            ->where('contacts.status', 'active');
        $search_keywords = $request->search;

        // if(!empty($request->academicYearId) && $request->academicYearId>0){
        $model = $model->where('contact_academics.academic_year_id', $request->academicYearId);
        // }
        if (!empty($request->classId) && $request->classId > 0) {
            $model = $model->where('contact_academics.class_id', $request->classId);
        }
        if (!empty($request->sectionId) && $request->sectionId > 0) {
            $model = $model->where('contact_academics.section_id', $request->sectionId);
        }

        $model = $model->where(function ($query) use ($search_keywords) {

            $query = $query->orWhere('contacts.full_name', 'LIKE', '%' . $search_keywords . '%');
            $query = $query->orWhere('contacts.contact_id', 'LIKE', '%' . $search_keywords . '%');
            $query = $query->orWhere('contact_academics.class_roll', 'LIKE', '%' . $search_keywords . '%');
        });
        $data = $model->select('contacts.id', 'contacts.full_name', 'contact_academics.class_roll', 'classes.name', 'contacts.contact_id')->limit(50)->get();
        $response = array();
        foreach ($data as $key => $datas) {
            $response[] = array(
                "id" => $datas->id,
                "text" => $datas->full_name . ' [' . $datas->class_roll . '] [' . $datas->contact_id . ']' . '-' . $datas->name);
        }

        echo json_encode($response);
        exit;
    }
    public function getstudentDetails(Request $request)
    {
        $response = [];
        $response['sid'] = '';
        $response['student_name'] = '';
        $response['student_class'] = '';
        $response['student_section'] = '';
        $response['data'] = '';
        $response['count'] = '';
        $response['due'] = '';
        $response['month'] = '';
        $response['shift_name'] = '';
        $response['transport_name'] = '';
        $studentID = $request->studentID;
        $student = DB::table('contacts')
            ->join('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'shifts.id', 'contact_academics.shift_id')
            ->leftjoin('transports', 'transports.id', 'contact_academics.transport_id')
            ->select('contacts.id', 'contacts.contact_id', 'contacts.full_name', 'contacts.photo', 'classes.name as class_name', 'sections.name as section_name', 'contact_academics.class_roll', 'contact_academics.class_id', 'shifts.name as shiftname', 'transports.name as transportname', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_number')
            ->where('contact_academics.status', 'active')
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
        foreach ($enumMonth as $monthkey => $enumValue) {
            $monthly_payable = DB::table('monthly_class_item')
                ->where('academic_year_id', $request->academicYearId)
                ->where('class_id', $student->class_id)
                ->where('month_id', $enumValue->id)->pluck('item_id')->toArray();
            $payable = DB::table('contact_payable_items')
                ->where('academic_year_id', $request->academicYearId)
                ->where('class_id', $student->class_id)
                ->where('month_id', $enumValue->id)
                ->where('contact_id', $request->studentID)
                ->whereIn('product_id', $monthly_payable)
                ->count();
            if (count($monthly_payable) == $payable && $monthly_payable > 0 && $payable > 0) {
                $response['month'] .= "<td><input type='checkbox' class='allCheck all-check-box month_check' style='vertical-align:middle' id='checkSection_" . $monthkey . "' name='month_check[]' value='" . $enumValue->id . "' keyValue='" . $enumValue->id . "' onclick='this.checked=!this.checked; getMonthlyDue(" . $enumValue->id . ");' checked> <strong>" . $enumValue->name . "</strong></td>";
            } else {
                $response['month'] .= "<td><input type='checkbox' class='allCheck all-check-box month_check' style='vertical-align:middle' id='checkSection_" . $monthkey . "' name='month_check[]' value='" . $enumValue->id . "' keyValue='" . $enumValue->id . "' onclick='getMonthlyDue(" . $enumValue->id . ");'> <strong>" . $enumValue->name . "</strong></td>";
            }
        }
        foreach ($generate_due as $key => $value) {
            $response['data'] .= "<tr>";
            $response['data'] .= "<td>" . ($key + 1) . "<input type='hidden' name='due_id[" . ($key + 1) . "][]' value='" . $value->id . "' placeholder='' ></td>";
            $response['data'] .= "<td style='width:100px;'>" . ($value->product_name) . "<input type='hidden' name='product_id[" . ($key + 1) . "][]' value='" . $value->product_id . "' id='product_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td style='width:100px;'>" . ($value->month) . "-" . $value->year . "<input type='hidden' name='month_id[" . ($key + 1) . "][]' value='" . $value->month_id . "' id='month_id_" . ($key + 1) . "' /><input type='hidden' name='academic_year_ids[" . ($key + 1) . "][]' value='" . $value->academic_year_id . "' id='academic_year_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td><input type='text' class='form-control'style='width:110px;' value='" . $value->amount . "' tabindex=" . ($key + 300) . " name='amount[" . ($key + 1) . "][]' id='amount-" . ($key + 1) . "' oninput='calculation()'/></td>";
            $response['data'] .= "<td><input type='text' class='form-control due_amount'style='width:110px;' value='" . $value->amount - $value->paid_amount . "' name='due_amount[" . ($key + 1) . "][]' id='due-amount-" . ($key + 1) . "' data-key='" . ($key + 1) . "' readonly=''/> <span class='error' id='due_amount_error-" . ($key + 1) . "' ></span> </td>";
            $response['data'] .= "<td><input style='width:110px;' type='text' class='form-control paid_amount' value='0' name='paid_amount[" . ($key + 1) . "][]' tabindex=" . ($key + 200) . " id='paid_amount_" . ($key + 1) . "' data-key='" . ($key + 1) . "' oninput='calculation()' /><input type='hidden' value='" . $value->paid_amount . "'id='hidden-paid-" . ($key + 1) . "'></td>";

            $response['data'] .= "<td><input type='text' class='form-control'style='width:200px;' name='note[" . ($key + 1) . "][]' tabindex=" . ($key + 300) . " id='note_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td></td></tr>";
        }
        $response['sid'] = '<a href="' . route(App::make('SID'), ['id' => $request->studentID]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $student->contact_id . '</a>&nbsp;&nbsp;<a href="' . route('students.payment.setup', ['id' => $request->studentID]) . '" target="_blank">Payment Setup</a></a>&nbsp;&nbsp;<a href="' . route('students.wise.payment.history.report', ['id' => $request->studentID]) . '" target="_blank">Account Profile</a>';
        $response['studentID'] = $request->studentID;
        $response['student_name'] = '<a href="' . route(App::make('studentName'), ['id' => $request->studentID]) . '" target="_blank">' . $student->full_name . '</a>';
        $response['student_photo'] = ($student->photo) ? asset(config('app.asset') . 'backend/images/students/' . $student->photo) : asset(config('app.asset') . 'backend/images/students/profile.png');

        $response['student_class'] = $student->class_name;
        $response['class_roll'] = $student->class_roll;
        $response['student_class_id'] = $student->class_id;
        $response['student_section'] = $student->section_name;
        $response['guardian_name'] = $student->guardian_name;
        $response['guardian_number'] = $student->guardian_number;
        $response['shift_name'] = (!empty($student->shiftname)) ? $student->shiftname : '';
        $response['transport_name'] = (!empty($student->transportname)) ? $student->transportname : '';
        $response['count'] = count($generate_due);
        $response['due'] = '<a href="' . route('due.details', ['id' => $request->studentID]) . '" target="_blank">' . $total_due . '</a>';
        $response['paid_amount'] = ($last_payment) ? $last_payment->paid_amount : 0;
        $response['sales_invoice_date'] = ($last_payment) ? $last_payment->sales_invoice_date : '';

        $response['result'] = 'success';
        return $response;
    }

    public function getMonthlyDue(Request $request)
    {
        $response = [];
        $response['sid'] = '';
        $response['student_name'] = '';
        $response['student_class'] = '';
        $response['student_section'] = '';
        $response['data'] = '';
        $response['count'] = '';
        $response['due'] = '';
        $response['month'] = '';
        $studentID = $request->studentID;

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

        foreach ($generate_due as $key => $value) {
            $response['data'] .= "<tr>";
            $response['data'] .= "<td>" . ($key + 1) . "<input type='hidden' name='due_id[" . ($key + 1) . "][]' value='" . $value->id . "' placeholder='' ></td>";
            $response['data'] .= "<td>" . ($value->product_name) . "<input type='hidden' name='product_id[" . ($key + 1) . "][]' value='" . $value->product_id . "' id='product_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td>" . ($value->month) . "-" . $value->year . "<input type='hidden' name='month_id[" . ($key + 1) . "][]' value='" . $value->month_id . "' id='month_id_" . ($key + 1) . "' /><input type='hidden' name='academic_year_ids[" . ($key + 1) . "][]' value='" . $value->academic_year_id . "' id='academic_year_id_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td><input type='text' class='form-control' value='" . $value->amount . "' name='amount[" . ($key + 1) . "][]' id='amount-" . ($key + 1) . "' oninput='calculation()'/></td>";
            $response['data'] .= "<td><input type='text' class='form-control due_amount' value='" . $value->amount - $value->paid_amount . "' name='due_amount[" . ($key + 1) . "][]' id='due-amount-" . ($key + 1) . "'data-key='" . ($key + 1) . "' readonly=''/> <span class='error' id='due_amount_error-" . ($key + 1) . "' ></span></td>";
            $response['data'] .= "<td><input style='' type='text' class='form-control paid_amount' value='0' name='paid_amount[" . ($key + 1) . "][]' id='paid_amount_" . ($key + 1) . "' data-key='" . ($key + 1) . "' oninput='calculation()' /><input type='hidden' value='" . $value->paid_amount . "'id='hidden-paid-" . ($key + 1) . "'></td>";

            $response['data'] .= "<td><input type='text' class='form-control' name='note[" . ($key + 1) . "][]' id='note_" . ($key + 1) . "' /></td>";
            $response['data'] .= "<td></td></tr>";
        }
        $monthly_payable = DB::table('monthly_class_item')
            ->join('products', 'monthly_class_item.item_id', 'products.id')
            ->join('enum_month', 'monthly_class_item.month_id', 'enum_month.id')
            ->join('academic_years', 'monthly_class_item.academic_year_id', 'academic_years.id')
            ->where('academic_year_id', $request->academicYearId)
            ->where('class_id', $request->classId)
            ->whereIn('month_id', $request->month_arr)
            ->select('monthly_class_item.*', 'enum_month.name as month', 'enum_month.id as month_id', 'academic_years.year', 'products.name as product_name')
            ->orderBy('enum_month.id', 'ASC')
            ->get();
        $data = 1;
        foreach ($monthly_payable as $monthkey => $monthlypayableValue) {

            $payable = DB::table('contact_payable_items')
                ->where('academic_year_id', $monthlypayableValue->academic_year_id)
                ->where('class_id', $monthlypayableValue->class_id)
                ->where('month_id', $monthlypayableValue->month_id)
                ->where('contact_id', $request->studentID)
                ->where('product_id', $monthlypayableValue->item_id)
                ->first();
            $student_price = DB::table('contactwise_item_discount_price_list')->where('class_id', $monthlypayableValue->class_id)->where('product_id', $monthlypayableValue->item_id)->where('academic_year_id', $monthlypayableValue->academic_year_id)->where('enum_month_id', $monthlypayableValue->month_id)->where('contact_id', $request->studentID)->first();
            if ($student_price) {
                $price = ($student_price->amount > 0) ? $student_price->amount : 0;
            } else {
                $price = isset($monthlypayableValue->item_price) ? $monthlypayableValue->item_price : 0;
            }

            if (!$payable) {
                $response['data'] .= "<tr>";
                $response['data'] .= "<td>" . (count($generate_due) + $data) . "<input type='hidden' name='due_id[" . (count($generate_due) + $data) . "][]' value='' placeholder='' ></td>";
                $response['data'] .= "<td>" . ($monthlypayableValue->product_name) . "<input type='hidden' name='product_id[" . (count($generate_due) + $data) . "][]' value='" . $monthlypayableValue->item_id . "' id='product_id_" . (count($generate_due) + $data) . "' /></td>";

                $response['data'] .= "<td>" . ($monthlypayableValue->month) . "-" . $monthlypayableValue->year . "<input type='hidden' name='month_id[" . (count($generate_due) + $data) . "][]' value='" . $monthlypayableValue->month_id . "' id='month_id_" . (count($generate_due) + $data) . "' /><input type='hidden' name='academic_year_ids[" . (count($generate_due) + $data) . "][]' value='" . $monthlypayableValue->academic_year_id . "' id='academic_year_id_" . (count($generate_due) + $data) . "' /></td>";

                $response['data'] .= "<td><input type='text' class='form-control' value='" . $price . "' name='amount[" . (count($generate_due) + $data) . "][]' id='amount-" . (count($generate_due) + $data) . "' oninput='calculation()'/></td>";
                $response['data'] .= "<td><input type='text' class='form-control due_amount' value='0' name='due_amount[" . (count($generate_due) + $data) . "][]' id='due-amount-" . (count($generate_due) + $data) . "'data-key='" . (count($generate_due) + $data) . "' readonly=''/><span class='error' id='due_amount_error-" . (count($generate_due) + $data) . "' ></span></td>";
                $response['data'] .= "<td><input style='' type='text' class='form-control paid_amount' value='0' name='paid_amount[" . (count($generate_due) + $data) . "][]' id='paid_amount_" . (count($generate_due) + $data) . "' data-key='" . (count($generate_due) + $data) . "' oninput='calculation()' /><input type='hidden' value='0'id='hidden-paid-" . (count($generate_due) + $data) . "'></td>";

                $response['data'] .= "<td><input type='text' class='form-control' name='note[" . (count($generate_due) + $data) . "][]' id='note_" . (count($generate_due) + $data) . "' /></td>";
                $response['data'] .= "<td></td></tr>";
                $data = $data + 1;
            }

        }
        // dd($monthly_payable);
        $response['count'] = $data + count($generate_due);

        // $response['due_credit'] = $due_payment;
        $response['result'] = 'success';
        return $response;
    }

    public function itemDetails(Request $request)
    {
        $response = [];
        $response['price'] = '';
        $response['error'] = '';

        if ($request->class_id) {
            $student_price = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', $request->academicYearId)->where('enum_month_id', $request->month_id)->where('class_id', $request->class_id)->where('contact_id', $request->studentID)->where('product_id', $request->itemId)->first();
            $price = DB::table('pricing')->where('pricing.class_id', $request->class_id)->where('pricing.product_id', $request->itemId)
                ->select('pricing.price')
                ->first();
        } else {
            $student_price = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', $request->academicYearId)->where('enum_month_id', $request->month_id)->where('contact_id', $request->studentID)->where('product_id', $request->itemId)->first();
            $price = DB::table('pricing')->where('pricing.product_id', $request->itemId)
                ->select('pricing.price')
                ->first();
        }
        if ($student_price) {
            $response['price'] = $student_price->amount;
        } elseif ($price) {
            $response['price'] = ($price->price > 0) ? $price->price : 0;
        } else {
            $response['price'] = 0;
        }

        $check_entry = DB::table('contact_payable_items')->where('contact_id', $request->studentID)->where('academic_year_id', $request->academicYearId)->where('month_id', $request->month_id)->where('product_id', $request->itemId)->first();
        if ($check_entry) {
            $response['error'] = 'This item payment already generated/taken for this selected year and month';
        } else {
            $response['error'] = 1;
        }
        // $response['due_credit'] = $due_payment;
        $response['result'] = 'success';
        return $response;

    }
    public function payment_store(Request $request)
    {

        // Check if the student exists in the contact table
        $studentExists = DB::table('contacts')->where('id', $request->student_id)->where('type', '1')->where('is_trash', 0)->exists();

        // If the request is a valid student then it will be hit in the If condition either it will hit the else condition
        if ($studentExists) {
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
                            $contactPayableId = $this->insertGeneratedDueList($request->student_id, $value[0], $request->preload_class_id, $request->month_id[$key][0], $request->academic_year_ids[$key][0], $request->paid_amount[$key][0], $request->due_amount[$key][0], $request->amount[$key][0]);
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
                    $smsEnablityCheck = DB::table('companynotificationsettings')->first();
                    if ($smsEnablityCheck->smsEnableAfterPayment == 1 && config('app.IsSMSActive') == 1) {
                        paymentSMS($request);
                    }
                    Session::flash('success', __('Student::label.ADD_SUCCESSFULL_MSG'));
                    return Redirect::route('payment.receipt', ['sales_id' => $sales]);
                } else {
                    Session::flash('danger', __('Payment Not Possible Without(Payment Amount 0)'));
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
        }
        // If this is a guest student
        else {

            // check student id has or not
            if (empty($request->student_id)) {
                $guestId = DB::table('contacts')->where('full_name', 'Guest')->where('type', '6')->where('is_trash', 0)->value('id');
                if (empty($guestId)) {
                    $guestId = DB::table('contacts')->insertGetId([
                        'type' => 6,
                        'first_name' => 'Guest',
                        'last_name' => '',
                        'full_name' => 'Guest',
                        'status' => 'active',
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $request->merge(['student_id' => $guestId]);

            // Store data
            DB::beginTransaction();
            try {
                $sales = $this->customer_payment_store($request);
                // Check the response for the redirect
                if ($request->total_paid > 0) {
                    DB::commit();
                    return Redirect::route('customer.payment.receipt', ['sales_id' => $sales]);
                } else {
                    Session::flash('danger', __('Payment Not Possible Without(Payment Amount 0)'));
                    return redirect()->back();
                }

            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
            // return view('Payment::receive-payment.receipt');
            // return redirect()->with('payment.receipt');
        }

    }

    public function updateGeneratedDueList($id, $paid, $due)
    {
        $contact_payable_items_update = DB::table('contact_payable_items')->where('id', $id)->update([
            'paid_amount' => $paid,
            'due' => $due,
            'is_paid' => ($due > 0) ? 0 : 1,
        ]);
    }
    public function insertGeneratedDueList($contact, $product_id, $class_id, $month_id, $academic_year_id, $paid_amount, $due_amount, $amount)
    {
        $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
            'contact_id' => $contact,
            'product_id' => $product_id,
            'class_id' => $class_id,
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

    // Customer Due
    public function insertGeneratedCustomerDueList($contact, $product_id, $month_id, $academic_year_id, $paid_amount, $due_amount, $amount)
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
    public function payment_receipt($id)
    {
        $saleData = DB::table('sales')
            ->where('sales.id', $id)
            ->join('contacts', 'contacts.id', 'sales.customer_id')
            ->join('contact_academics', 'contact_academics.contact_id', 'sales.customer_id')
            ->where('contact_academics.status', 'active')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('student_type', 'contact_academics.student_type_id', 'student_type.id')
            ->select('sales.*', 'contacts.full_name as full_name', 'contact_academics.class_roll as class_roll', 'student_type.name as s_type_name', 'classes.name as class_name', 'sections.name as section_name')
            ->first();
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
        return view("Payment::receive-payment.receipt", compact('saleData', 'month', 'item'));
    }

    // Payment List
    public function paymentList(Request $request)
    {
        if ($request->ajax()) {
            $datam = DB::table('sales')
                ->leftJoin('contacts', 'contacts.id', 'sales.customer_id')
                ->leftJoin('users', 'users.id', 'sales.updated_by')
                ->leftJoin('sales_product_relation', 'sales_product_relation.sales_id', 'sales.id');

            if ($request->academicYearId) {
                $datam->whereYear(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), $request->academicYearId);
            }
            if ($request->studentId) {
                $datam->where('contacts.id', $request->studentId);
            }
            if ($request->monthId) {
                $datam->whereMonth(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), '=', $request->monthId);
            }
            if ($request->itemId) {
                $datam->where('sales_product_relation.product_id', $request->itemId);
            }
            if ($request->invoiceId) {
                $datam->where('sales.sales_invoice_no', $request->invoiceId);
            }
            if ($request->fromDate && $request->toDate) {
                $datam->whereBetween(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), [Carbon::parse($request->fromDate)->format('Y-m-d'), Carbon::parse($request->toDate)->format('Y-m-d')]);
            }
            if ($request->typeId) {
                $datam->where('contacts.type', $request->typeId);
            }


            $data = $datam->select('sales.id', 'contacts.full_name', 'contacts.id as student_id', 'contacts.contact_id', 'contacts.type as contact_type', 'sales.sales_invoice_date', 'sales.sales_invoice_no', 'sales.grand_total as total', 'sales_product_relation.sales_id', 'users.first_name', 'users.last_name', 'sales.updated_at')
                ->groupBy('sales_product_relation.sales_id', 'sales.id', 'contacts.full_name', 'contacts.contact_id', 'sales.sales_invoice_date', 'sales.sales_invoice_no', 'total')
                ->get();

            $formattedData = $data->map(function ($item) {
                $item->updated_time = '';
                if ($item->updated_at) {
                    $formattedDate = Carbon::parse($item->updated_at)->format('d F, Y');
                    $formattedTime = Carbon::parse($item->updated_at)->format('g:i A');
                    $item->updated_time = $formattedDate . ', ' . $formattedTime;
                }
                return $item;
            });

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href=" ' . route('payment.edit', [$row->id]) . ' " class="btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>';
                    return $btn;
                })

                ->addColumn('name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->rawColumns(['action', 'name'])
                ->editColumn('full_name', function ($row) {
                    $btn = '';
                    if($row->contact_type == 1){
                        $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    }else if($row->contact_type == 6){
                        $btn = '<a href="' . route(App::make('custId'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    }
                    return $btn;
                })

                ->editColumn('contact_id', function ($row) {
                    $btn = '';
                    if($row->contact_type == 1){
                        $btn = '<a href="' . route(App::make('SID'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->contact_id . '</a>';
                    }else if($row->contact_type == 6){
                        $btn = '<a href="' . route(App::make('custId'), ['id' => $row->student_id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->contact_id . '</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action','full_name','contact_id'])
                ->make(true);
        }
        $academic_year = ['' => 'All'] + DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'year')->toArray();
        $monthList = ['' => 'Select Month'] + DB::table('enum_month')->orderBy('id', 'ASC')->pluck('short_name', 'id')->toArray();
        $itemList = ['' => 'Select Item'] + DB::table('products')->where('is_trash', '0')->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $invoiceList = ['' => 'Select Invoice'] + DB::table('sales')->pluck('sales_invoice_no', 'sales_invoice_no')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Payment::paymentlist.index', compact('academic_year', 'monthList', 'itemList', 'invoiceList', 'currentYear'));
    }

    // Payment Details
    public function paymentEdit($id)
    {
        $contactId = DB::table('sales')->where('id', $id)->first();
        $contactDetails = DB::table('contacts')->where('id', $contactId->customer_id)->first();

        if ($contactDetails->type == 1) {
            $data = DB::table('sales')
                ->leftJoin('contacts', 'contacts.id', 'sales.customer_id')
                ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftJoin('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
                ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
                ->select('sales.id', 'contacts.id as student_id', 'contacts.full_name', 'contacts.contact_id', 'contacts.gender', 'classes.name as class_name', 'classes.id as class_id', 'sections.name as section_name', 'contact_academics.class_roll', 'contact_academics.registration_no', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year', 'academic_years.id as academic_year_id', 'sales.grand_total as total', 'sales.sales_invoice_no', 'sales.sales_invoice_date')
                ->where('sales.id', $id)
                ->where('contact_academics.status', 'active')
                ->first();
            $who = "Student";
        } elseif ($contactDetails->type == 6) {
            $data = DB::table('sales')
                ->leftJoin('contacts', 'contacts.id', 'sales.customer_id')
                ->select('sales.id', 'contacts.id as student_id', 'contacts.full_name', 'contacts.contact_id', 'contacts.gender', 'sales.grand_total as total', 'sales.sales_invoice_no', 'sales.sales_invoice_date', 'contacts.cp_phone_no', 'contacts.address')
                ->where('sales.id', $id)
                ->where('contacts.status', 'active')
                ->first();
            $who = "Customer";
        }

        $details = DB::table('sales_product_relation')
            ->where('sales_product_relation.sales_id', $id)
            ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
            ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
            ->leftjoin('products', 'sales_product_relation.product_id', 'products.id')
            ->leftjoin('contact_payable_items', 'contact_payable_items.id', 'sales_product_relation.contact_payable_id')
            ->select('sales_product_relation.id', 'products.id as product_id', 'products.name', 'academic_years.id as academic_year_id', 'enum_month.id as month_id', 'sales_product_relation.price', 'sales_product_relation.note', 'contact_payable_items.amount')
            ->get();
        $accCatInfo = DB::table('sales_payment')->join('payment_history', 'payment_history.id', 'sales_payment.payment_relation_id')->select('payment_history.AccountCategoryId')->where('sales_payment.sales_id', $id)->first();
        $productlist = DB::table('products')->where('status', 'active')->get();
        $account_category = DB::table('accountcategorys')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        $academicYear = DB::table('academic_years')->where('is_trash', 0)->latest('id')->get();
        return view('Payment::paymentlist.payment_edit', compact('data', 'details', 'account_category', 'enumMonth', 'academicYear', 'productlist', 'accCatInfo', 'who'));
    }

    // Payment Item Update
    public function paymentItemUpdate(Request $request, $id)
    {

        $input = $request->all();
        DB::beginTransaction();
        try {
            $accountCategory = DB::table('accountcategorys')->where('id', $request->category_id)->first();
            if ($accountCategory->AccountTypeId == 1) {
                $payment_type = 'cash';
            } else {
                $payment_type = 'bank';
            }
            $paymentId = $request->item_id;

            $salesData = DB::table('sales')->where('id', $id)->first();
            /************************************
            Version table data insert
             ************************************/
            // data add in sales version table
            DB::table('sales_version')->insert([
                'sales_type' => $salesData->sales_type,
                'customer_id' => $salesData->customer_id,
                'dealer_id' => $salesData->dealer_id,
                'prev_customer_credit' => $salesData->prev_customer_credit,
                'sales_invoice_no' => $salesData->sales_invoice_no,
                'sales_invoice_date' => $salesData->sales_invoice_date,
                'status' => $salesData->status,
                'grand_total' => $salesData->grand_total,
                'created_by' => $salesData->created_by,
                'created_at' => $salesData->created_at,
                'updated_by' => $salesData->updated_by,
                'updated_at' => $salesData->updated_at,
                'sales_id' => $salesData->id,
                'quotation_id' => $salesData->quotation_id,
                'delivery_type' => $salesData->delivery_type,
                'previous_due' => $salesData->previous_due,
                'due_product_relation_id' => $salesData->due_product_relation_id,
                'booking_amount' => $salesData->booking_amount,
                'condition_payment' => $salesData->condition_payment,
                'delivery_date' => $salesData->delivery_date,
                'delivery_status' => $salesData->delivery_status,
                'work_order' => $salesData->work_order,
                'workorder_date' => $salesData->workorder_date,
                'delivery_place' => $salesData->delivery_place,
                'vehicle_type' => $salesData->vehicle_type,
                'grn' => $salesData->grn,
                'subtotal' => $salesData->subtotal,
                'discount_option' => $salesData->discount_option,
                'discount' => $salesData->discount,
                'paid_amount' => $salesData->paid_amount,
                'total_due' => $salesData->total_due,
                'display_name' => $salesData->display_name,
                'isOld' => $salesData->isOld,
                'adjustment_amount' => $salesData->adjustment_amount,
            ]);

            // data add in sales_payment version table
            $salesPayment = DB::table('sales_payment')->where('sales_payment.sales_id', $id)->first();
            DB::table('sales_payment_version')->insert([
                'sales_id' => $salesPayment->sales_id,
                'dealer_id' => $salesPayment->dealer_id,
                'sales_payment_date' => $salesPayment->sales_payment_date,
                'absolute_amount' => $salesPayment->absolute_amount,
                'sales_discount' => $salesPayment->sales_discount,
                'discount_option' => $salesPayment->discount_option,
                'interest_rate' => $salesPayment->interest_rate,
                'installment_no' => $salesPayment->installment_no,
                'rate_per_installment' => $salesPayment->rate_per_installment,
                'grand_total' => $salesPayment->grand_total,
                'down_payment' => $salesPayment->down_payment,
                'due_payment' => $salesPayment->due_payment,
                'advance_payment' => $salesPayment->advance_payment,
                'settlement_amount' => $salesPayment->settlement_amount,
                'last_installment_date' => $salesPayment->last_installment_date,
                'write_of' => $salesPayment->write_of,
                'note' => $salesPayment->note,
                'status' => $salesPayment->status,
                'created_by' => $salesPayment->created_by,
                'updated_by' => $salesPayment->updated_by,
                'created_at' => $salesPayment->created_at,
                'updated_at' => $salesPayment->updated_at,
                'payment_relation_id' => $salesPayment->payment_relation_id,
            ]);

            // data add in payment_history version table
            $paymentHistory = DB::table('payment_history')->where('payment_history.id', $salesPayment->payment_relation_id)->first();
            DB::table('payment_history_version')->insert([
                'payment_invoice' => $paymentHistory->payment_invoice,
                'payment_date' => $paymentHistory->payment_date,
                'customer_id' => $paymentHistory->customer_id,
                'payment_amount' => $paymentHistory->payment_amount,
                'due_amount' => $paymentHistory->due_amount,
                'flag' => $paymentHistory->flag,
                'source' => $paymentHistory->source,
                'dealer_id' => $paymentHistory->dealer_id,
                'status' => $paymentHistory->status,
                'created_by' => $paymentHistory->created_by,
                'updated_by' => $paymentHistory->updated_by,
                'created_at' => $paymentHistory->created_at,
                'updated_at' => $paymentHistory->updated_at,
                'AccountTypeId' => $paymentHistory->AccountTypeId,
                'AccountCategoryId' => $paymentHistory->AccountCategoryId,
                'AccountId' => $paymentHistory->AccountId,
                'note' => $paymentHistory->note,
            ]);

            $cashbankData = DB::table('cash_banks')->where('cash_banks.invoice_no', $salesData->sales_invoice_no)->first();
            DB::table('cash_banks_version')->insert([
                'invoice_date' => $cashbankData->invoice_date,
                'invoice_no' => $cashbankData->invoice_no,
                'cheque_no' => $cashbankData->cheque_no,
                'amount' => $cashbankData->amount,
                'payment_type' => $cashbankData->payment_type,
                'note' => $cashbankData->note,
                'dealer_id' => $cashbankData->dealer_id,
                'customer_id' => $cashbankData->customer_id,
                'supplier_id' => $cashbankData->supplier_id,
                'source_flag' => $cashbankData->source_flag,
                'status' => $cashbankData->status,
                'created_by' => $cashbankData->created_by,
                'updated_by' => $cashbankData->updated_by,
                'created_at' => $cashbankData->created_at,
                'updated_at' => $cashbankData->updated_at,
            ]);

            $productRelationInfo = DB::table('sales_product_relation')->where('sales_product_relation.sales_id', $id)->get();
            foreach ($productRelationInfo as $key => $productRelationData) {
                DB::table('sales_product_relation_version')->insert([
                    'customer_category_id' => $productRelationData->customer_category_id,
                    'sales_id' => $productRelationData->sales_id,
                    'product_id' => $productRelationData->product_id,
                    'quantity' => $productRelationData->quantity,
                    'discount_option' => $productRelationData->discount_option,
                    'discount' => $productRelationData->discount,
                    'price' => $productRelationData->price,
                    'package_id' => $productRelationData->package_id,
                    'status' => $productRelationData->status,
                    'created_by' => $productRelationData->created_by,
                    'updated_by' => $productRelationData->updated_by,
                    'created_at' => $productRelationData->created_at,
                    'updated_at' => $productRelationData->updated_at,
                    'sales_group_id' => $productRelationData->sales_group_id,
                    'serial_number' => $productRelationData->serial_number,
                    'warranty' => $productRelationData->warranty,
                    'chassis_no' => $productRelationData->chassis_no,
                    'engine_no' => $productRelationData->engine_no,
                    'key_no' => $productRelationData->key_no,
                    'alt_id' => $productRelationData->alt_id,
                    'description' => $productRelationData->description,
                    'subtotal' => $productRelationData->subtotal,
                    'isVat_percent' => $productRelationData->isVat_percent,
                    'vat' => $productRelationData->vat,
                    'vat_amount' => $productRelationData->vat_amount,
                    'isTax_percent' => $productRelationData->isTax_percent,
                    'tax' => $productRelationData->tax,
                    'tax_amount' => $productRelationData->tax_amount,
                    'return_qty' => $productRelationData->return_qty,
                    'year_of_manufacture' => $productRelationData->year_of_manufacture,
                    'maker' => $productRelationData->maker,
                    'makerCountry' => $productRelationData->makerCountry,
                    'package_qty' => $productRelationData->package_qty,
                    'value' => $productRelationData->value,
                    'total_value' => $productRelationData->total_value,
                    'loose_qty' => $productRelationData->loose_qty,
                    'product_sales_type' => $productRelationData->product_sales_type,
                    'bonus_parent_product_id' => $productRelationData->bonus_parent_product_id,
                    'actual_price' => $productRelationData->actual_price,
                    'remain_due' => $productRelationData->remain_due,
                    'note' => $productRelationData->note,
                    'month_id' => $productRelationData->month_id,
                    'academic_year_id' => $productRelationData->academic_year_id,
                    'contact_payable_id' => $productRelationData->contact_payable_id,
                ]);

                // contact payable item table update
                $contactPayableData = DB::table('contact_payable_items')->where('id', $productRelationData->contact_payable_id)->first();
                if (!empty($contactPayableData)) {
                    DB::table('contact_payable_items_version')->insert([
                        'main_id' => $contactPayableData->id,
                        'contact_id' => $contactPayableData->contact_id,
                        'product_id' => $contactPayableData->product_id,
                        'class_id' => $contactPayableData->class_id,
                        'month_id' => $contactPayableData->month_id,
                        'academic_year_id' => $contactPayableData->academic_year_id,
                        'amount' => $contactPayableData->amount,
                        'paid_amount' => $contactPayableData->paid_amount,
                        'due' => $contactPayableData->due,
                        'is_paid' => $contactPayableData->is_paid,
                        'created_by' => $contactPayableData->created_by,
                        'created_at' => $contactPayableData->created_at,
                        'updated_by' => $contactPayableData->updated_by,
                        'updated_at' => $contactPayableData->updated_at,
                        'date' => $contactPayableData->date,
                        'is_trash' => $contactPayableData->is_trash,
                        'generated_payable_list_id' => $contactPayableData->generated_payable_list_id,
                        'flag' => 'update',
                    ]);
                }
            }

            /************************************
            Table data insert
             ************************************/

            // Sales table update
            DB::table('sales')->where('sales.id', $id)->update([
                'sales_invoice_date' => $request->payment_date,
                'grand_total' => $request->total_paid,
                'updated_by' => Auth::user()->id,
                'updated_at' => date("Y-m-d h:i:s"),
                'subtotal' => $request->total_paid,
                'paid_amount' => $request->total_paid,
            ]);

            // sales payment update
            $salesPaymentUpdate = DB::table('sales_payment')->where('sales_payment.sales_id', $id)->update([
                'sales_payment_date' => $request->payment_date,
                'absolute_amount' => $request->total_paid,
                'grand_total' => $request->total_paid,
                'down_payment' => $request->total_paid,
                'updated_by' => Auth::user()->id,
                'updated_at' => date("Y-m-d h:i:s"),
            ]);

            // Payment history update
            $payment_history = DB::table('payment_history')->where('payment_history.id', $salesPayment->payment_relation_id)->update([
                'payment_date' => $request->payment_date,
                'payment_amount' => $request->total_paid,
                'flag' => $payment_type,
                'updated_by' => Auth::user()->id,
                'updated_at' => date("Y-m-d h:i:s"),
                'AccountTypeId' => $accountCategory->AccountTypeId,
                'AccountCategoryId' => $request->category_id,
            ]);

            // cashbank update
            DB::table('cash_banks')->where('cash_banks.invoice_no', $salesData->sales_invoice_no)->update([
                'invoice_date' => $request->payment_date,
                'payment_type' => $payment_type,
                'amount' => $request->total_paid,
                'updated_by' => Auth::user()->id,
                'updated_at' => date("Y-m-d h:i:s"),
            ]);

            // contact_payable_id
            $productRelationOldPrice = DB::table('sales_product_relation')->where('sales_id', $id)->get();
            foreach ($productRelationOldPrice as $productRelData) {
                $previousConPayable = DB::table('contact_payable_items')->where('id', $productRelData->contact_payable_id)->first();
                DB::table('contact_payable_items')->where('id', $productRelData->contact_payable_id)->update([
                    'paid_amount' => $previousConPayable->paid_amount - $productRelData->price,
                    'due' => $previousConPayable->due + $productRelData->price,
                    'is_paid' => (($previousConPayable->due + $productRelData->price) > 0) ? 0 : 1,
                ]);
            }
            DB::table('sales_product_relation')->where('sales_id', $id)->delete();

            foreach ($request->product_id as $key => $value) {
                $checkConPayable = DB::table('contact_payable_items')->where('contact_id', $request->student_id)->where('product_id', $value[0])->where('class_id', $request->preload_class_id)->where('month_id', $request->month_id[$key][0])->where('academic_year_id', $request->academic_year_ids[$key][0])->first();
                if (!empty($checkConPayable)) {
                    $contactPayableId = $checkConPayable->id;
                    DB::table('contact_payable_items')->where('id', $checkConPayable->id)->update([
                        'amount' => $request->amount[$key][0],
                        'paid_amount' => $checkConPayable->paid_amount + $request->paid_amount[$key][0],
                        'due' => $request->amount[$key][0] - ($checkConPayable->paid_amount + $request->paid_amount[$key][0]),
                        'is_paid' => (($request->amount[$key][0] - ($checkConPayable->paid_amount + $request->paid_amount[$key][0])) > 0) ? 0 : 1,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date("Y-m-d h:i:s"),
                    ]);
                } else {
                    $contactPayableId = DB::table('contact_payable_items')->insertGetId([
                        'contact_id' => $request->student_id,
                        'product_id' => $value[0],
                        'class_id' => $request->preload_class_id,
                        'month_id' => $request->month_id[$key][0],
                        'academic_year_id' => $request->academic_year_ids[$key][0],
                        'amount' => $request->amount[$key][0],
                        'paid_amount' => $request->paid_amount[$key][0],
                        'due' => $request->amount[$key][0] - $request->paid_amount[$key][0],
                        'is_paid' => (($request->amount[$key][0] - $request->paid_amount[$key][0]) > 0) ? 0 : 1,
                        'created_by' => Auth::user()->id,
                        'created_at' => date("Y-m-d h:i:s"),
                        'date' => date("Y-m-d"),
                    ]);
                }
                DB::table('sales_product_relation')->insert([
                    'customer_category_id' => $request->category_id,
                    'sales_id' => $id,
                    'product_id' => $value[0],
                    'quantity' => 1,
                    'price' => $request->paid_amount[$key][0],
                    'status' => 'active',
                    'created_by' => Auth::user()->id,
                    'created_at' => date("Y-m-d h:i:s"),
                    'subtotal' => $request->paid_amount[$key][0],
                    'actual_price' => $request->paid_amount[$key][0],
                    'remain_due' => $request->amount[$key][0] - $request->paid_amount[$key][0],
                    'note' => $request->note[$key][0],
                    'month_id' => $request->month_id[$key][0],
                    'academic_year_id' => $request->academic_year_ids[$key][0],
                    'contact_payable_id' => $contactPayableId,
                ]);

            }
            DB::commit();
            Session::flash('success', __('Payment::label.PAYMENT_UPDATED_SUCCESSFULLY'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // get student dependent on academic year
    public function getStudentsContact(Request $request)
    {
        if ($request->typeId == 1) {
            $data = DB::table('contacts')
                ->where('contacts.type', $request->typeId)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->where('contacts.is_trash', '0')
                ->where('academic_years.year', $request->yearId)
                ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))
                ->get();
        } elseif ($request->typeId == 6) {
            $data = DB::table('contacts')
                ->where('contacts.type', $request->typeId)
                ->where('contacts.is_trash', '0')
                ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))
                ->get();
        } else {
            $data1 = DB::table('contacts')
                ->where('contacts.type', 1)
                ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftjoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
                ->where('contacts.is_trash', '0')
                ->where('academic_years.year', $request->yearId)
                ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))
                ->get();
            $data2 = DB::table('contacts')
                ->where('contacts.type', 6)
                ->where('contacts.is_trash', '0')
                ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"/SID: ",IFNULL(contacts.contact_id,"")) as full_name'))
                ->get();
            $data = $data1->concat($data2);
        }

        return response()->json($data);
    }

    // get invoice dependent on academic year
    public function getInvoice(Request $request)
    {
        $data = DB::table('sales')
            ->whereYear(DB::raw('str_to_date(sales.sales_invoice_date, "%d-%m-%Y")'), $request->yearId)
            ->get();
        return response()->json($data);
    }

    // Due List
    public function dueList(Request $request)
    {
        if ($request->ajax()) {
            $datam = DB::table('contacts')
                ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
                ->leftJoin('contact_payable_items', 'contacts.id', 'contact_payable_items.contact_id')
                ->leftJoin('classes', 'classes.id', 'contact_academics.class_id')
                ->leftJoin('sections', 'sections.id', 'contact_academics.section_id')
                ->Where('contact_payable_items.is_trash', 0)
                ->where('contacts.type', 1)
                ->where('contact_academics.status', 'active')
                ->where('contacts.is_trash', 0)
                ->where('contact_payable_items.due', '>', 0);

            if ($request->academicYearId) {
                $datam->where('contact_payable_items.academic_year_id', $request->academicYearId);
            }
            if ($request->classId) {
                $datam->whereIn('contact_academics.class_id', $request->classId);
            }
            if ($request->sectionId) {
                $datam->whereIn('contact_academics.section_id', $request->sectionId);
            }
            if ($request->shiftId) {
                $datam->whereIn('contact_academics.shift_id', $request->shiftId);
            }
            if ($request->genderId) {
                $datam->where('contacts.gender', $request->genderId);
            }
            if ($request->versionId) {
                $datam->whereIn('contact_academics.version_id', $request->versionId);
            }
            if ($request->status) {
                $datam->where('contacts.status', $request->status);
            }
            if ($request->groupId) {
                $datam->where('contact_academics.group_id', $request->groupId);
            }
            $data = $datam->select('contacts.id', 'contacts.full_name', 'contacts.contact_id', 'contacts.type as contact_type', 'classes.name as class_name', 'guardian.cp_phone_no', 'sections.name as section_name', DB::raw('SUM(contact_payable_items.due) as student_due'))
                ->groupBy('contacts.id', 'contacts.full_name', 'contacts.contact_id', 'class_name', 'section_name')
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars"></i>
                    </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"
                                href="' . route('due.details', [$row->id]) . '" target="_blank">View Due Details</a>
                            <a class="dropdown-item" href="' . route('student.edit', [$row->id]) . '">Student Edit</a>
                        </div>';
                    return $btn;
                })

                ->editColumn('full_name', function ($row) {
                    $btn = '';
                    if($row->contact_type == 1){
                        $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    }else if($row->contact_type == 6){
                        $btn = '<a href="' . route(App::make('custId'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    }
                    return $btn;
                })

                ->editColumn('contact_id', function ($row) {
                    $btn = '';
                    if($row->contact_type == 1){
                        $btn = '<a href="' . route(App::make('SID'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->contact_id . '</a>';
                    }else if($row->contact_type == 6){
                        $btn = '<a href="' . route(App::make('custId'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->contact_id . '</a>';
                    }
                    return $btn;
                })

                ->rawColumns(['action','full_name','contact_id'])
                ->make(true);
        }
        $academic_year = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        // $classList = ['' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $classList = DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        // $versionList = ['' => 'All'] + DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $versionList = DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = ['' => 'All'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Payment::duelist.index', compact('academic_year', 'classList', 'versionList', 'groupList', 'currentYear', 'shift_list'));
    }

    // Due Details
    public function dueDetails($id)
    {
        $data = DB::table('contact_payable_items')
            ->where('contact_payable_items.contact_id', $id)
            ->leftJoin('contacts', 'contacts.id', 'contact_payable_items.contact_id')
            ->leftJoin('classes', 'classes.id', 'contact_payable_items.class_id')
            ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contact_academics.status', 'active')
            ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
            ->select('contacts.full_name', 'contacts.contact_id', 'contacts.gender', 'classes.name as class_name', 'sections.name as section_name', 'contact_academics.class_roll', 'contact_academics.registration_no', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year', DB::raw('SUM(contact_payable_items.due) as student_due'))
            ->groupBy('contacts.full_name', 'contacts.contact_id', 'contacts.gender', 'classes.name', 'sections.name', 'contact_academics.class_roll', 'contact_academics.registration_no', 'versions.name', 'sections.name', 'shifts.name', 'academic_years.year')
            ->first();

        $details = DB::table('contact_payable_items')
            ->where('contact_payable_items.contact_id', $id)
            ->where('contact_payable_items.is_paid', 0)
            ->leftJoin('contacts', 'contacts.id', 'contact_payable_items.contact_id')
            ->leftJoin('products', 'contact_payable_items.product_id', 'products.id')
            ->leftJoin('enum_month', 'contact_payable_items.month_id', 'enum_month.id')
            ->select('contact_payable_items.id', 'products.name as item_name', 'enum_month.name as month_name', 'contact_payable_items.amount', 'contact_payable_items.paid_amount', 'contact_payable_items.due')
            ->get();
        if ($data) {
            return view('Payment::duelist.due_details', compact('data', 'details'));
        } else {
            Session::flash('danger', "No due found for this student");
            return redirect()->route('due.list');
        }
    }

    // Due Item edit
    public function dueItemEdit($id)
    {
        $data = DB::table('contact_payable_items')->where('id', $id)->first();
        if ($data->paid_amount == 0) {
            return view('Payment::duelist.item_edit', compact('data'));
        } else {
            Session::flash('danger', "You haven't rights to delete");
            return redirect()->back();
        }
    }

    // Due Item Update
    public function dueItemUpdate(Request $request)
    {
        $data = array();
        $data['amount'] = $request->amount;
        $data['due'] = $request->amount;
        $data['updated_by'] = Auth::user()->id;
        $data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('contact_payable_items')->where('id', $request->id)->update($data);
        Session::flash('success', "Due Item Updated Successfully");
        return redirect()->back();
    }

    // Due Item Delete
    public function dueItemDelete($id)
    {
        $item = DB::table('contact_payable_items')->where('id', $id)->first();
        if ($item->paid_amount == 0) {
            DB::beginTransaction();
            try {
                $data = array();
                $data['main_id'] = $item->id;
                $data['contact_id'] = $item->contact_id;
                $data['product_id'] = $item->product_id;
                $data['class_id'] = $item->class_id;
                $data['month_id'] = $item->month_id;
                $data['academic_year_id'] = $item->academic_year_id;
                $data['amount'] = $item->amount;
                $data['paid_amount'] = $item->paid_amount;
                $data['due'] = $item->due;
                $data['is_paid'] = $item->is_paid;
                $data['created_by'] = $item->created_by;
                $data['created_at'] = $item->created_at;
                $data['updated_by'] = $item->updated_by;
                $data['updated_at'] = $item->updated_at;
                $data['date'] = $item->date;
                $data['is_trash'] = $item->is_trash;
                $data['generated_payable_list_id'] = $item->generated_payable_list_id;
                $data['flag'] = 'delete';
                DB::table('contact_payable_items_version')->insert($data);
                DB::table('contact_payable_items')->where('id', $id)->delete();
                DB::commit();
                Session::flash('success', "Due Item Deleted Successfully");
                return redirect()->back();
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
        } else {
            Session::flash('danger', "You haven't rights to delete");
            return redirect()->back();
        }

    }

    // Student wise payment history report
    public function studentWisePaymentHistoryReport($id)
    {
        $data = DB::table('contacts')
            ->where('contacts.id', $id)
            ->leftJoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contact_academics.status', 'active')
            ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)
            ->leftJoin('classes', 'classes.id', 'contact_academics.class_id')
            ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
            ->select('contacts.full_name', 'contacts.contact_id', 'contacts.gender', 'classes.name as class_name', 'sections.name as section_name', 'contact_academics.class_roll', 'contact_academics.registration_no', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year', 'guardian.full_name as guardian_name')
            ->first();

        $currentMonthdetails1 = DB::table('contactwise_item_discount_price_list')
            ->where('contactwise_item_discount_price_list.contact_id', $id)
            ->where('contactwise_item_discount_price_list.enum_month_id', date('n'))
            ->whereNot('contactwise_item_discount_price_list.enum_month_id', 1)
            ->leftJoin('contact_payable_items', 'contact_payable_items.contact_discount_id', 'contactwise_item_discount_price_list.id')
            ->leftJoin('sales_product_relation', 'sales_product_relation.contact_payable_id', 'contact_payable_items.id')
            ->leftJoin('sales', 'sales.id', 'sales_product_relation.sales_id')
            ->leftjoin('products', 'contactwise_item_discount_price_list.product_id', 'products.id')
            ->leftjoin('academic_years', 'contactwise_item_discount_price_list.academic_year_id', 'academic_years.id')
            ->where('academic_years.is_current', 1)
            ->select('contactwise_item_discount_price_list.contact_id', 'contactwise_item_discount_price_list.product_id', 'contactwise_item_discount_price_list.academic_year_id', 'contactwise_item_discount_price_list.enum_month_id as month_id', 'contact_payable_items.id', 'products.name', 'contactwise_item_discount_price_list.actual_amount', 'contactwise_item_discount_price_list.amount', 'contactwise_item_discount_price_list.enum_month_id as month_id', 'contactwise_item_discount_price_list.discount_amount', 'contact_payable_items.paid_amount', 'contact_payable_items.due', 'sales.sales_invoice_date as payment_date')
            ->get();

        $currentMonthdetails2 = DB::table('contact_payable_items')
            ->where('contact_payable_items.contact_id', $id)
            ->where('contact_payable_items.month_id', date('n'))
            ->leftJoin('contactwise_item_discount_price_list', 'contact_payable_items.contact_discount_id', 'contactwise_item_discount_price_list.id')
            ->leftJoin('sales_product_relation', 'sales_product_relation.contact_payable_id', 'contact_payable_items.id')
            ->leftJoin('sales', 'sales.id', 'sales_product_relation.sales_id')
            ->leftjoin('products', 'contact_payable_items.product_id', 'products.id')
            ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
            ->where('academic_years.is_current', 1)
            ->select('contact_payable_items.contact_id', 'contact_payable_items.product_id', 'contact_payable_items.academic_year_id', 'contact_payable_items.month_id', 'products.name', 'contactwise_item_discount_price_list.actual_amount', 'contact_payable_items.amount', 'contact_payable_items.month_id', 'contactwise_item_discount_price_list.discount_amount', 'contact_payable_items.paid_amount', 'contact_payable_items.due', 'sales.sales_invoice_date as payment_date')
            ->get();

        $diff = $currentMonthdetails1
            ->whereNotIn('product_id', $currentMonthdetails2->pluck('product_id'))
            ->all();

        $details = DB::table('contact_payable_items')
            ->where('contact_payable_items.contact_id', $id)
            ->leftJoin('contactwise_item_discount_price_list', 'contact_payable_items.contact_discount_id', 'contactwise_item_discount_price_list.id')
            ->leftJoin('sales_product_relation', 'sales_product_relation.contact_payable_id', 'contact_payable_items.id')
            ->leftJoin('sales', 'sales.id', 'sales_product_relation.sales_id')
            ->leftjoin('products', 'contact_payable_items.product_id', 'products.id')
            ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
            ->where('academic_years.is_current', 1)
            ->select('sales.id as contact_id', 'products.id', 'products.name', 'contactwise_item_discount_price_list.actual_amount', 'contact_payable_items.amount', 'contact_payable_items.month_id', 'contactwise_item_discount_price_list.discount_amount', 'contact_payable_items.paid_amount', 'contact_payable_items.due', 'sales.sales_invoice_date as payment_date', 'sales.created_at')
            ->groupBy('products.id', 'contact_payable_items.month_id')
            ->get();

        $currentMonthdetails = collect($currentMonthdetails2)->concat($diff);

        $currentDue = (DB::table('contact_payable_items')
                ->where('contact_payable_items.contact_id', $id)
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('academic_years.is_current', 1)
                ->select(DB::raw('SUM(contact_payable_items.due) as student_due'))
                ->first())->student_due;

        $currentYear = ((DB::table('academic_years')
                ->where('is_current', 1)
                ->first())->year - 1);

        $oldDue = (DB::table('contact_payable_items')
                ->where('contact_payable_items.contact_id', $id)
                ->leftjoin('academic_years', 'contact_payable_items.academic_year_id', 'academic_years.id')
                ->where('academic_years.year', $currentYear)
                ->select(DB::raw('SUM(contact_payable_items.due) as student_due'))
                ->first())->student_due;

        $currentMonth = DB::table('enum_month')
            ->where('enum_month.id', date('n'))
            ->select('enum_month.id', 'enum_month.name')
            ->groupBy('enum_month.id', 'enum_month.name')
            ->get();

        $monthList = DB::table('enum_month')
            ->whereNot('enum_month.id', date('n'))
            ->select('enum_month.id', 'enum_month.name')
            ->groupBy('enum_month.id', 'enum_month.name')
            ->orderByDesc('enum_month.id')
            ->get();

        $selected_student = DB::table('contacts')->where('contacts.id', $id)
            ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contact_academics.status', 'active')
            ->select('contacts.id', 'contacts.full_name', 'contacts.contact_id', 'contact_academics.class_roll', 'contact_academics.class_id', 'contact_academics.academic_year_id', 'contact_academics.section_id')
            ->first();
        $academic_year = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $studentList = DB::table('contacts')
            ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contact_academics.status', 'active')
            ->where('contact_academics.class_id', $selected_student->class_id)
            ->where('contact_academics.section_id', $selected_student->section_id)
            ->select('contacts.id', DB::raw('CONCAT(IFNULL(contacts.full_name,""),"[",IFNULL(contact_academics.class_roll,"null"),"][",IFNULL(contacts.contact_id,""),"]") as full_name'))
            ->pluck('full_name', 'contacts.id')
            ->toArray();
        $studentTotalPaid = DB::table('contact_payable_items')->where('contact_id',$id)->where('academic_year_id',$selected_student->academic_year_id)->sum('paid_amount');   
        return view('Payment::paymentHistoryReport.studentWisePaymentHistory', compact('data', 'oldDue', 'academic_year', 'classList', 'selected_student', 'studentList', 'currentMonthdetails', 'monthList', 'currentDue', 'details', 'currentMonth','studentTotalPaid'));
    }

    // Student Wise Payment History Report Search
    public function studentWisePaymentHistoryReportSearch(Request $request)
    {
        $url = 'students-wise-payment-history-report/' . $request->student_id;
        return redirect($url);
    }

    public function dataCorrection()
    {

        $salse = DB::table('sales_product_relation')->join('sales', 'sales_product_relation.sales_id', 'sales.id')->select('sales_product_relation.*')
            ->get();
        foreach ($salse as $value) {
            $data = DB::table('contact_payable_items')->where('id', $value->contact_payable_id)->first();
            if (!$data) {
                echo "<pre>";
                print_r($value);
                echo "</pre>";
                // dd($data);
            }
        }

    }

    // Check Student Exist
    public function checkStudentExists(Request $request)
    {
        $studentID = $request->input('studentID');

        // Check if the student exists in the contact table
        $studentExists = DB::table('contacts')->where('id', $studentID)->where('is_trash', 0)->exists();

        if ($studentExists) {
            return response()->json(['result' => 'success']);
        } else {
            return response()->json(['result' => 'error', 'message' => 'Student not found in the contact table']);
        }
    }

    // Customer payment store
    public function customer_payment_store(Request $request)
    {
        $invoice_no = $this->GenerateInvoice();

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
                    $contactPayableId = $this->insertGeneratedCustomerDueList($request->student_id, $value[0], $request->month_id[$key][0], $request->academic_year_ids[$key][0], $request->paid_amount[$key][0], $request->due_amount[$key][0], $request->amount[$key][0]);
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
        return $sales;

    }
}
