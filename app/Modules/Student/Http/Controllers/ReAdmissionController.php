<?php

namespace App\Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\App;

class ReAdmissionController extends Controller
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
    public function index(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('readmission.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $currentYears = DB::table('academic_years')->where('is_current', '1')->first();
        $currentYear = DB::table('academic_years')->where('id', $currentYears->id-1)->first();
        $group_list = ['0' => 'Select Group'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $shift_list = ['0' => 'Select Shift'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentTypeList = ['0' => 'Select Student Type'] + DB::table('student_type')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $transport_list = ['0' => 'Select Transport'] + DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();

        $students = [];
        $model = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')->join('classes', 'classes.id', 'contact_academics.class_id')->leftJoin('sections', 'contact_academics.section_id', 'sections.id')->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id');
        if ($request->class_id) {
            $model = $model->where('contact_academics.class_id', $request->class_id);
        }
        if (!empty($request->section_id)) {
            $model = $model->where('contact_academics.section_id', $request->section_id);
        }
        $model = $model->where('contact_academics.academic_year_id', $request->academicYearId)->where('contact_academics.is_trash', 0)->where('contact_academics.status', 'active')->where('contacts.status', 'active');

        $students = $model->select('contacts.id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academic_id', 'contacts.contact_id as student_id', 'contacts.status')->get();

        if ($request->ajax()) {
            return DataTables::of($students)->addIndexColumn()

                ->addColumn('checkbox', function ($row) {

                    if (DB::table('contact_academics')->where('academic_year_id', $row->year)->where('class_id', $row->class_name)->where('is_trash', '0')->exists()) {
                        $btn = '<input type="checkbox" checked="" class="allCheck all-check-box" id="checkStudent_' . $row->id . '" name="contact_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked();">';
                    } else {
                        $btn = '<input type="checkbox"  class="allCheck all-check-box" id="checkStudent_' . $row->id . '" name="contact_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked();">';
                    }

                    return $btn;
                })

                ->editColumn('newroll', function ($row) {
                    return '<div class="form-group"> <input type="text" class ="form-control new_roll" data-key="' . $row->id . '" onChange="rollValidate(this.id)" id="newRoll' . $row->id . '" name="new_roll[' . $row->id . '][]">
                      <span class="error error-new-roll" id="error_newRoll' . $row->id . '"></span>
                   <input type="hidden" value="' . $row->contact_academic_id . '" name="contact_academic_id[' . $row->id . '][]"></div>';

                })

                ->editColumn('full_name', function ($row) {
                    $btn = '<a href="' . route(App::make('studentName'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->full_name . '</a>';
                    return $btn;
                })

                ->editColumn('student_id', function ($row) {
                    $btn = '<a href="' . route(App::make('SID'), ['id' => $row->id]) . '" target="_blank" style="font-weight:bold; cursor: pointer;">' . $row->student_id . '</a>';
                    return $btn;
                })

                ->rawColumns(['checkbox', 'newroll', 'full_name', 'student_id'])
                ->make(true);
        }
        $productlist = DB::table('products')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        if (!empty(Session::get('studentReadmissionDefaultItem'))) {
            $setItems = json_decode(Session::get('studentReadmissionDefaultItem'));
            $selectedItems = DB::table('products')->whereIn('id', $setItems)->orderByRaw(DB::raw("FIELD(id, " . implode(',', $setItems) . ")"))->get();
            $productlist = DB::table('products')->where('status', 'active')->whereNotIn('id', $setItems)->get();
            return view('Student::reAdmission.index', compact('classList', 'academicYearList', 'currentYear', 'group_list', 'shift_list', 'enumMonth', 'productlist','transport_list','selectedItems', 'studentTypeList'));
        } else {
            return view('Student::reAdmission.index', compact('classList', 'academicYearList', 'currentYear', 'group_list', 'shift_list', 'enumMonth', 'productlist','transport_list', 'studentTypeList'));
        }
    }
    public function create(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('readmission.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $input = $request->all();
        // echo "<pre>";
        // print_r($input);
        // exit;
        $contactIds = $request->contact_id;
        DB::beginTransaction();
        try {
            $data = [];
            $errorName = [];
            foreach ($contactIds as $key => $value) {
                if (DB::table('contact_academics')->where('class_id', $input['new_class'])->where('academic_year_id', $input['session_year'])
                    ->where('contact_id', $value)
                    ->where('is_trash', '0')->doesntExist()) {

                    $contactAcademicId[$key]['id'] = $input['contact_academic_id'][$value][0];
                    $data[$key]['class_roll'] = $input['new_roll'][$value][0];
                    $data[$key]['class_id'] = $input['new_class'];
                    $data[$key]['academic_year_id'] = $input['session_year'];
                    $data[$key]['section_id'] = $input['section_id'];
                    $data[$key]['shift_id'] = $input['shift_id'];
                    $data[$key]['student_type_id'] = $input['student_type_id'];
                    $data[$key]['transport_id'] = $input['transport_id'];
                    $data[$key]['notes'] = $input['notes'];
                    $data[$key]['group_id'] = $input['group_id'];
                    $data[$key]['admission_date'] = date('Y-m-d');
                    $data[$key]['contact_id'] = $value;
                    $data[$key]['admission_type'] = 2;
                    $data[$key]['status'] = "active";
                    $data[$key]['created_at'] = date("Y-m-d h:i:s");
                    $data[$key]['created_by'] = Auth::user()->id;

                    if (!empty($request->product_id)) {
                        $products = $request->product_id;
                        $amount = $request->amount;
                        $discount = $request->discount;
                        $payable = $request->payable;
                        $note = $request->note;
                        $month_id = $request->month_id;

                        $affected_month = $request->affected_month;
                        $selected_month = '';
                        // $affected_month = $request->affected_month;
                        $discountArr = [];
                        foreach ($products as $key => $product) {

                            $exit= DB::table('contactwise_item_discount_price_list')
                                    ->where('contact_id', $value)
                                    ->where('academic_year_id', $request->session_year)
                                    ->where('product_id', $product)
                                    ->where('enum_month_id', $month_id[$key][0])
                                    ->first();
                                if($exit) {
                                    DB::table('contactwise_item_discount_price_list')->where('contact_id', $value)
                                        ->where('academic_year_id', $request->academic_year_id)
                                        ->where('product_id', $product)
                                        ->update([
                                            'academic_year_id' => $request->session_year,
                                            'class_id' => $request->new_class,
                                            'product_id' => $product[0],
                                            'actual_amount' => $amount[$key][0],
                                            'amount' => $payable[$key][0],
                                            'discount_amount' => $discount[$key][0],
                                            'notes' => $note[$key][0],
                                            'updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'enum_month_id' => $month_id[$key][0],
                                        ]);
                                $dis_id=$exit->id;
                                } else {
                                    if ($payable[$key][0] > 0) {
                                        $dis_id=DB::table('contactwise_item_discount_price_list')->insertGetId([
                                            'academic_year_id' => $request->session_year,
                                            'class_id' => $request->new_class,
                                            'contact_id' => $value,
                                            'product_id' => $product[0],
                                            'actual_amount' => $amount[$key][0],
                                            'amount' => $payable[$key][0],
                                            'discount_amount' => $discount[$key][0],
                                            'notes' => $note[$key][0],
                                            'created_by' => Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'enum_month_id' => $month_id[$key][0],
                                        ]);
                                    }
                                }
                                
                                $payment_items= DB::table('contact_payable_items')->where('contact_id', $value)->where('product_id', $product[0])->where('month_id', $month_id[$key][0])->where('academic_year_id', $request->session_year)->first();
                                if($payment_items){

                                    $contact_payable_items_update = DB::table('contact_payable_items')->where('contact_id', $value)->where('product_id', $product[0])->where('month_id', $month_id[$key][0])->where('academic_year_id', $request->session_year)->update([
                                            'class_id' => $request->new_class,
                                            'amount' => (float) $payable[$key][0],
                                            'due' => (float) ($payable[$key][0]-$payment_items->paid_amount),
                                            'is_paid' => (float) ($payable[$key][0]-$payment_items->paid_amount)==0? 1:0,
                                            'updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'contact_discount_id'=>$dis_id,
                                            'month_id'=>$month_id[$key][0]
                                        ]);

                                }else{
                                    if ($payable[$key][0] > 0) {
                                    $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
                                            'contact_id' => $value,
                                            'product_id' => $product[0],
                                            'class_id' => $request->new_class,
                                            'month_id' => 1,
                                            'academic_year_id' => $request->session_year,
                                            'amount' => (float) $payable[$key][0],
                                            'paid_amount' => 0,
                                            'due' => (float) $payable[$key][0],
                                            'is_paid' => 0,
                                            'created_by' => Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'date' => date('Y-m-d'),
                                            'contact_discount_id'=>$dis_id,
                                            'month_id'=>$month_id[$key][0],
                                        ]);
                                }
                    
                            }

                                                        
                            if (!empty($request->till_current_month[$key]) && $month_id[$key][0] < date('m')) {
                                $till_current = [];
                                $selected_month = $month_id[$key][0] + 1;
                                $currentMonth = date('m');
                                $monthId = range($selected_month, $currentMonth);
                                $amount_upto_month = $payable[$key][0];
                                $amount_month = $amount[$key][0];
                                $productId_upto_month = $product[0];

                                                                            
                                foreach ($monthId as $key1 => $row) {
                                    $dis_id1 = DB::table('contactwise_item_discount_price_list')->insertGetId([
                                        'academic_year_id' => $request->session_year,
                                        'class_id' => $request->new_class,
                                        'contact_id' => $value,
                                        'product_id' => $product[0],
                                        'actual_amount' => (float) $amount_month,
                                        'amount' => (float) $amount_upto_month,
                                        'discount_amount' => (float) $discount[$key][0],
                                        'created_by' => Auth::user()->id,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'enum_month_id' => $row,
                                    ]);
                                    
                                    $exist = DB::table('contact_payable_items')
                                        ->where('contact_id', $value)
                                        ->where('product_id', $productId_upto_month)
                                        ->where('month_id', $row)
                                        ->where('academic_year_id', $request->academic_year_id)
                                        ->first();
                            
                                    if (!$exist) {
                                        $till_current[$key1]['contact_id'] = $value;
                                        $till_current[$key1]['product_id'] = $productId_upto_month;
                                        $till_current[$key1]['class_id'] = $request->new_class;
                                        $till_current[$key1]['month_id'] = $row;
                                        $till_current[$key1]['academic_year_id'] = $request->session_year;
                                        $till_current[$key1]['amount'] = (float) $amount_upto_month;
                                        $till_current[$key1]['paid_amount'] = 0;
                                        $till_current[$key1]['due'] = (float) $amount_upto_month;
                                        $till_current[$key1]['created_by'] = Auth::user()->id;
                                        $till_current[$key1]['created_at'] = date('Y-m-d H:i:s');
                                        $till_current[$key1]['date'] = date('Y-m-d');
                                        $till_current[$key1]['contact_discount_id'] = $dis_id1;
                                    }
                                }
                                DB::table('contact_payable_items')->insert($till_current);

                            }
                                            if (!empty($request->check_whole_year[$key])) {
                                                $select_whole_year = $month_id[$key][0];
                                                $month_year_id = range($select_whole_year, 12);


                                                foreach ($month_year_id as $month) {
                                                    $exist_check_whole = DB::table('contactwise_item_discount_price_list')
                                                        ->where('contact_id', $value)
                                                        ->where('product_id', $product[0])
                                                        ->where('enum_month_id', $month)
                                                        ->where('academic_year_id', $request->session_year)
                                                        ->first();
                                                
                                                    if (!$exist_check_whole) {
                                                        DB::table('contactwise_item_discount_price_list')->insert([
                                                            'academic_year_id' => $request->session_year,
                                                            'class_id' => $request->new_class,
                                                            'contact_id' => $value,
                                                            'product_id' => $product[0],
                                                            'actual_amount' => (float) $amount[$key][0],
                                                            'amount' => (float) $payable[$key][0],
                                                            'discount_amount' => (float) $discount[$key][0],
                                                            'created_by' => Auth::user()->id,
                                                            'created_at' => date('Y-m-d H:i:s'),
                                                            'enum_month_id' => $month,
                                                        ]);
                                                    }
                                                }

                                        }
         }
                        DB::table('contactwise_item_discount_price_list')->insert($discountArr);
                    }
                } else {
                    array_push($errorName, $value);
                }
            }

            if (!empty($data)) {
                DB::table('contact_academics')->insert($data);
                Session::flash('success', __('Student::label.ADD_SUCCESSFULL_MSG'));
            } else {
                $a = [];
                foreach ($errorName as $key => $value) {
                    $a[$key] = DB::table('contacts')->where('id', $value)->pluck('full_name', 'id')->first();
                }
                $nameJson = trim(json_encode($a), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            }

            if (empty($contactAcademicId)) {
                $a = [];
                foreach ($errorName as $key => $value) {
                    $a[$key] = DB::table('contacts')->where('id', $value)->pluck('full_name', 'id')->first();
                }
                $nameJson = trim(json_encode($a), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            } else {
                foreach ($contactAcademicId as $key => $contactAcademic) {
                    DB::table('contact_academics')->where('id', $contactAcademic)->update([
                        'status' => "inactive",
                        'updated_at' => date("Y-m-d h:i:s"),
                        'updated_by' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('readmission.index');
        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }
    public function singleReadmission($classId,$studentId)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('student.readmission')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $student = DB::table('contacts')
            ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
            ->leftJoin('classes', 'classes.id', 'contact_academics.class_id')
            ->leftJoin('sections', 'contact_academics.section_id', 'sections.id')
            ->join('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
            ->select('contacts.contact_id as SID', 'contacts.id', 'contacts.full_name as full_name', 'classes.name as class_name', 'sections.name as section_name', 'academic_years.year as year', 'contact_academics.class_roll as class_roll', 'contact_academics.id as contact_academic_id', 'contact_academics.status')
            ->where('contact_academics.status', 'active')
            ->where('contacts.id', $studentId)->first();

        $class_weight = DB::table('classes')->where('id',$classId)->pluck('weight');
        $classList = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();

        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        $group_list = ['0' => 'Select Group'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $shift_list = ['0' => 'Select Shift'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentTypeList = ['0' => 'Select Student Type'] + DB::table('student_type')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $transport_list = ['0' => 'Select Transport'] + DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();

        $productlist = DB::table('products')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        if (!empty(Session::get('studentReadmissionDefaultItem'))) {
            $setItems = json_decode(Session::get('studentReadmissionDefaultItem'));
            $selectedItems = DB::table('products')->whereIn('id', $setItems)->get();
            $productlist = DB::table('products')->where('status', 'active')->whereNotIn('id', $setItems)->get();
            return view('Student::reAdmission.singleStudent', compact('student', 'classList', 'academicYearList', 'currentYear', 'group_list', 'shift_list', 'enumMonth', 'productlist','transport_list', 'selectedItems','studentTypeList'));
        } else {
            return view('Student::reAdmission.singleStudent', compact('student', 'classList', 'academicYearList', 'currentYear', 'group_list', 'shift_list', 'enumMonth', 'productlist','transport_list','studentTypeList'));
        }

    }
    public function singleReadmissionCreate(Request $request)
    {
        try {
            if (DB::table('contact_academics')->where('class_id', $request->class_id)->where('academic_year_id', $request->academic_year_id)
                ->where('contact_id', $request->contact_id)
                ->where('is_trash', '0')->doesntExist()) {
                DB::table('contact_academics')->insert([
                    'class_roll' => $request->new_roll,
                    'class_id' => $request->class_id,
                    'academic_year_id' => $request->academic_year_id,
                    'section_id' => $request->section_id,
                    'shift_id' => $request->shift_id,
                    'student_type_id' => $request->student_type_id,
                    'group_id' => $request->group_id,
                    'contact_id' => $request->contact_id,
                    'transport_id' => $request->transport_id,
                    'notes' => $request->notes,
                    'admission_type' => 2,
                    'admission_date' => date('Y-m-d'),
                    'status' => "active",
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
                DB::table('contact_academics')->where('id', $request->contact_academic_id)->update([
                    'status' => "inactive",
                    'updated_at' => date("Y-m-d h:i:s"),
                    'updated_by' => Auth::user()->id,
                ]);

                if (!empty($request->product_id)) {
                    $products = $request->product_id;
                    $amount = $request->amount;
                    $discount = $request->discount;
                    $payable = $request->payable;
                    $note = $request->note;
                    $month_id =$request->month_id;
                    $affected_month = $request->affected_month;
                    $month_id = $request->month_id;
                    $selected_month = '';
                    // $affected_month = $request->affected_month;
                    $discountArr = [];
                    foreach ($products as $key => $product) {
                        $exit= DB::table('contactwise_item_discount_price_list')
                        ->where('contact_id', $request->contact_id)
                        ->where('academic_year_id', $request->academic_year_id)
                        ->where('enum_month_id', $month_id[$key][0])
                        ->where('product_id', $product)
                        ->first();
                        if($exit) {
                            DB::table('contactwise_item_discount_price_list')->where('contact_id', $request->contact_id)
                                ->where('academic_year_id', $request->academic_year_id)
                                ->where('product_id', $product)
                                ->update([
                                    'academic_year_id' => $request->academic_year_id,
                                    'class_id' => $request->class_id,
                                    'product_id' => $product[0],
                                    'actual_amount' => $amount[$key][0],
                                    'amount' => $payable[$key][0],
                                    'discount_amount' => $discount[$key][0],
                                    'notes' => $note[$key][0],
                                    'updated_by' => Auth::user()->id,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'enum_month_id' => $month_id[$key][0],
                                ]);
                        $dis_id=$exit->id;
                        } else {
                            if ($payable[$key][0] > 0) {
                                $dis_id=DB::table('contactwise_item_discount_price_list')->insertGetId([
                                    'academic_year_id' => $request->academic_year_id,
                                    'class_id' => $request->class_id,
                                    'contact_id' => $request->contact_id,
                                    'product_id' => $product[0],
                                    'actual_amount' => $amount[$key][0],
                                    'amount' => $payable[$key][0],
                                    'discount_amount' => $discount[$key][0],
                                    'notes' => $note[$key][0],
                                    'created_by' => Auth::user()->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'enum_month_id' => $month_id[$key][0],
                                ]);
                            }
                        }
                        




                                $payment_items= DB::table('contact_payable_items')->where('contact_id', $request->contact_id)->where('product_id', $product[0])->where('month_id', $month_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();
                                if($payment_items){

                                    $contact_payable_items_update = DB::table('contact_payable_items')->where('contact_id', $request->contact_id)->where('product_id', $product[0])->where('month_id', $month_id[$key][0])->where('academic_year_id', $request->academic_year_id)->update([
                                            'class_id' => $request->class_id,
                                            'amount' => (float) $payable[$key][0],
                                            'due' => (float) ($payable[$key][0]-$payment_items->paid_amount),
                                            'is_paid' => (float) ($payable[$key][0]-$payment_items->paid_amount)==0? 1:0,
                                            'updated_by' => Auth::user()->id,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'contact_discount_id'=>$dis_id
                                        ]);

                                }else{
                                    if ($payable[$key][0] > 0) {
                                    $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
                                            'contact_id' => $request->contact_id,
                                            'product_id' => $product[0],
                                            'class_id' => $request->class_id,
                                            'month_id' => $month_id[$key][0],
                                            'academic_year_id' => $request->academic_year_id,
                                            'amount' => (float) $payable[$key][0],
                                            'paid_amount' => 0,
                                            'due' => (float) $payable[$key][0],
                                            'is_paid' => 0,
                                            'created_by' => Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'date' => date('Y-m-d'),
                                            'contact_discount_id'=>$dis_id
                                        ]);
                                }






                                if (!empty($request->till_current_month[$key]) && $month_id[$key][0] < date('m')) {
                                    $till_current = [];
                                    $selected_month = $month_id[$key][0] + 1;
                                    $currentMonth = date('m');
                                    $monthId = range($selected_month, $currentMonth);
                                    $amount_upto_month = $payable[$key][0];
                                    $amount_month = $amount[$key][0];
                                    $productId_upto_month = $product[0];
            
                                    foreach ($monthId as $key1 => $row) {
                                        $dis_id1 = DB::table('contactwise_item_discount_price_list')->insertGetId([
                                            'academic_year_id' => $request->academic_year_id,
                                            'class_id' => $request->class_id,
                                            'contact_id' => $request->contact_id,
                                            'product_id' => $productId_upto_month,
                                            'actual_amount' => (float) $amount_month,
                                            'amount' => (float) $amount_upto_month,
                                            'discount_amount' => (float) $discount[$key][0],
                                            'created_by' => Auth::user()->id,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'enum_month_id' => $row,
                                        ]);
                                        $exist = DB::table('contact_payable_items')->where('contact_id', $request->contact_id)->where('product_id', $productId_upto_month)->where('month_id', $row)->where('academic_year_id', $request->academic_year_id)->first();
                                        if (!$exist) {
                                            $till_current[$key1]['contact_id'] = $request->contact_id;
                                            $till_current[$key1]['product_id'] = $productId_upto_month;
                                            $till_current[$key1]['class_id'] = $request->class_id;
                                            $till_current[$key1]['month_id'] = $row;
                                            $till_current[$key1]['academic_year_id'] = $request->academic_year_id;
                                            $till_current[$key1]['amount'] = (float) $amount_upto_month;
                                            $till_current[$key1]['paid_amount'] = 0;
                                            $till_current[$key1]['due'] = (float) $amount_upto_month;
                                            $till_current[$key1]['created_by'] = Auth::user()->id;
                                            $till_current[$key1]['created_at'] = date('Y-m-d H:i:s');
                                            $till_current[$key1]['date'] = date('Y-m-d');
                                            $till_current[$key1]['contact_discount_id'] = $dis_id1;
                                        }
            
                                    }
                                    DB::table('contact_payable_items')->insert($till_current);
            
                                }
                                if (!empty($request->check_whole_year[$key])) {
                                    $select_whole_year = $month_id[$key][0];
                                    $month_year_id = range($select_whole_year, 12);
                                    foreach ($month_year_id as $value) {
                                        $exist_check_whole = DB::table('contactwise_item_discount_price_list')
                                            ->where('contact_id', $value)->where('product_id', $product[0])
                                            ->where('enum_month_id', $value)->where('academic_year_id', $request->academic_year_id)->first();
            
                                        if (!$exist_check_whole) {
                                            DB::table('contactwise_item_discount_price_list')->insert([
                                                'academic_year_id' => $request->academic_year_id,
                                                'class_id' => $request->class_id,
                                                'contact_id' => $request->contact_id,
                                                'product_id' => $product[0],
                                                'actual_amount' => (float) $amount[$key][0],
                                                'amount' => (float) $payable[$key][0],
                                                'discount_amount' => (float) $discount[$key][0],
                                                'created_by' => Auth::user()->id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'enum_month_id' => $value,
                                            ]);
                                           
                                        }
                                    }
                                }














                                
            
                        }




                    











                    }
                    DB::table('contactwise_item_discount_price_list')->insert($discountArr);
                }
                Session::flash('success', __('Student added successfully!!'));
                return redirect()->back();
            } else {
                Session::flash('danger', __('Student already exist!!'));
                return redirect()->back();
            }

        } catch (\Throwable$th) {
            DB::rollback();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }
    public function getClassList(Request $request)
    {
        $class_weight = DB::table('classes')->where('id',$request->classId)->pluck('weight');
        $weight_wise_class_list = DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->get();
        return  $weight_wise_class_list;
    }
}
