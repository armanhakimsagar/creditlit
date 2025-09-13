<?php

namespace App\Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\MonthlyClassItem;
use App\Modules\Item\Models\UserItemRelation;
use Illuminate\Support\Facades\Session;
use App\Modules\Item\Models\GeneratePayableList;

class MonthlyItemSetupController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Generated Monthly Item';
        if ($request->ajax()) {
            $generatedList = DB::table('generate_payable_list')->join('classes', 'classes.id', 'generate_payable_list.class_id')->join('academic_years', 'academic_years.id', 'generate_payable_list.academic_year_id')->join('enum_month', 'enum_month.id', 'generate_payable_list.month_id')
                ->select('generate_payable_list.academic_year_id as year_id', 'generate_payable_list.month_id as month_id', 'generate_payable_list.class_id as class_id', 'enum_month.name as month_name', 'academic_years.year', 'classes.name as class_name', DB::raw('DATE_FORMAT(generate_payable_list.date, "%d-%m-%Y") as date'))
                ->orderByDesc('generate_payable_list.id')
                ->get();
            return DataTables::of($generatedList)
                ->addIndexColumn()
                ->make(true);
        }
        return view('Item::monthlyItemSetup.index');
    }
    public function create(Request $request)
    {
        $academicYearList = AcademicYear::where('is_trash', '0')->whereNot('status', 'cancel')->latest('id')->pluck('year', 'id')->toArray();
        $classList = ['0' => 'Select Class'] + Classes::where('is_trash', '0')->whereNot('status', 'cancel')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $months = ['0' => 'Select Month'] + DB::table('enum_month')->pluck('name', 'id')->toArray();

        if ($request->ajax()) {
            $classId = $request->class_id;
            $monthId = $request->monthId;
            $academicYearId = $request->academicYearId;
            $products = DB::table('products')->join('user_product_relation', 'user_product_relation.product_relation_id', 'products.id')->where('products.status', 'active')->select('products.*')->get();
            foreach ($products as $product) {
                $product->classId = $classId;
                $product->monthId = $monthId;
                $product->academicYearId = $academicYearId;
            }
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('fees', function ($row) {
                        $prices = DB::table('pricing')->where('product_id', $row->id)->where('class_id', $row->classId)->where('status', 'active')->first();
                        if (!empty($prices)) {
                            $itemPrice = $prices->price;
                        } else {
                            $itemPrice = 0;
                        }
                        return ' <input type="text" class="form-control" name="fees[' . $row->id . ']" value="' . $itemPrice . '" id="feesId_' . $row->id . '">';
                })
                ->addColumn('checkbox', function ($row) {
                    // if (DB::table('contact_payable_items')->where('class_id', $row->classId)->where('product_id', $row->id)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->exists()) {
                    //     if (DB::table('contact_payable_items')->where('paid_amount', '>', '0')->where('product_id', $row->id)->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->exists()) {
                    //         $btn = '<input type="checkbox" class=" all-check-box" checked="" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()" disabled=""> <br>
                    //         <span class="error">You can not uncheck this.This Fee is already Paid</span>';
                    //     } else {
                    //         $btn = '<input type="checkbox" class="allCheck all-check-box" checked="" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    //     }
                    // } else {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    // }
                    return $btn;
                })
                ->rawColumns(['checkbox', 'fees'])
                ->make(true);
        }
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Item::monthlyItemSetup.create', compact('academicYearList', 'classList', 'months', 'request','currentYear'));
    }
    public function store(Request $request)
    {
        $input = $request->all();
        if(empty($request->item_id)){
            toastr()->warning('Please check atleast one item');
            return back()->withInput($request->all());
        }
        $students = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
            ->where('contact_academics.academic_year_id', $input['academic_year'])->where('contact_academics.class_id', $input['class_name'])
            ->where('contact_academics.is_trash', '0')->where('contact_academics.status', 'active')->where('contacts.status', 'active')->select('contacts.*')->get();

        if (!$students->isEmpty()) {
            DB::beginTransaction();
            try {
                $generatePayable = new GeneratePayableList;
                $generatePayable->academic_year_id = $request->academic_year;
                $generatePayable->month_id = $request->month_id;
                $generatePayable->class_id = $request->class_name;
                $generatePayable->created_at = date('Y-m-d h:i:s');
                $generatePayable->status = 'active';
                $generatePayable->date = date('Y-m-d');
                $generatePayable->save();

                foreach ($students as $key => $stdVal) {
                    foreach ($request->item_id as $product_id ) {
                        $stdSetupAmount = DB::table('contactwise_item_discount_price_list')->where('contact_id',$stdVal->id)
                        ->where('academic_year_id',$request->academic_year)->where('class_id',$request->class_name)->where('product_id',$product_id)->where('enum_month_id',$request->month_id)->select('id','amount')->first();

                        $checkExistDue = DB::table('contact_payable_items')->where('contact_id',$stdVal->id)->where('product_id',$product_id)
                        ->where('class_id',$request->class_name)->where('month_id',$request->month_id)->where('academic_year_id',$request->academic_year)->select('amount')->first();
                        $amount = !empty($stdSetupAmount) ? $stdSetupAmount->amount : $request->fees[$product_id];
                        if(empty($checkExistDue) && $amount > 0){
                            DB::table('contact_payable_items')->insert([
                                'contact_id' => $stdVal->id,
                                'product_id' => $product_id,
                                'class_id' => $request->class_name,
                                'month_id' => $request->month_id,
                                'academic_year_id' => $request->academic_year,
                                'amount' => $amount,
                                'paid_amount' => 0,
                                'due' => $amount,
                                'created_by' => auth()->user()->id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'date' => date('Y-m-d'),
                                'generated_payable_list_id' => $generatePayable->id,
                                'contact_discount_id' => !empty($stdSetupAmount) ? $stdSetupAmount->id : NULL,
                            ]);
                        }
                    }

                }
                    DB::commit();
                    toastr()->success('Monthly Class Item Added Successfully');
                    return redirect()->route('monthly.item.index');
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
        } else {
            Session::flash('danger', 'No Student In The Selected Session');
            return redirect()->back();
        }
    }
    public function storeItemSetup(Request $request)
    {
        $input = $request->all();
        $previousItems = DB::table('monthly_class_item')->where('class_id', $input['class_name'])->where('academic_year_id', $input['academic_year'])->where('month_id', $input['month_id'])->where('is_trash', '0')->where('status', 'active')->where('is_trash', '0')->get();
        if (!empty($request->item_id)) {
            $items = $input['item_id'];
            DB::beginTransaction();
            try {
                foreach ($previousItems as $prev) {
                    if (!in_array($prev->item_id, $items)) {
                        if (DB::table('contact_payable_items')->where('product_id', $prev->item_id)->where('class_id', $input['class_name'])->where('academic_year_id', $input['academic_year'])->where('month_id', $input['month_id'])->where('is_trash', '0')->where('paid_amount', '>', '0')->doesntExist()) {
                            $deleted = DB::table('monthly_class_item')->where('class_id', $input['class_name'])->where('academic_year_id', $input['academic_year'])->where('month_id', $input['month_id'])->where('is_trash', '0')->where('item_id', $prev->item_id)->first();

                            DB::table('monthly_class_item_version')->insert([
                                'class_id' => $deleted->class_id,
                                'academic_year_id' => $deleted->class_id,
                                'item_id' => $prev->item_id,
                                'status' => 'active',
                                'month_id' => $deleted->class_id,
                                'created_at' => date('Y-m-d h:i:s'),
                                'created_by' => auth()->user()->id,
                                'item_price' => $deleted->class_id,
                                'is_trash' => 0,
                                'flag' => 'Deleted',
                                'monthly_class_item_id' => $deleted->id
                            ]);
                            DB::table('monthly_class_item')->where('id', $deleted->id)->delete();
                        }
                    }
                }
                foreach ($items as $item) {
                    if (DB::table('monthly_class_item')->where('item_id', $item)->where('class_id', $input['class_name'])->where('academic_year_id', $input['academic_year'])->where('month_id', $input['month_id'])->where('is_trash', '0')->doesntExist()) {
                        if (isset($input['all_monthly_item_id'][$item][0])) {
                            if ($input['all_monthly_item_id'][$item][0] == $item) {
                                for ($i = 1; $i <= 12; $i++) {
                                    if (DB::table('monthly_class_item')->where('class_id', $input['class_name'])->where('academic_year_id', $input['academic_year'])->where('month_id', $i)->where('is_trash', '0')->where('item_id', $item)->doesntExist()) {
                                        DB::table('monthly_class_item')->insert([
                                            'class_id' => $input['class_name'],
                                            'academic_year_id' => $input['academic_year'],
                                            'item_id' => $item,
                                            'status' => 'active',
                                            'month_id' => $i,
                                            'created_at' => date('Y-m-d h:i:s'),
                                            'created_by' => auth()->user()->id,
                                            'item_price' => $input['fees'][$item][0],
                                            'is_trash' => 0
                                        ]);
                                    }
                                }
                            } else {
                                DB::table('monthly_class_item')->insert([
                                    'class_id' => $input['class_name'],
                                    'academic_year_id' => $input['academic_year'],
                                    'item_id' => $item,
                                    'status' => 'active',
                                    'month_id' => $input['month_id'],
                                    'created_at' => date('Y-m-d h:i:s'),
                                    'created_by' => auth()->user()->id,
                                    'item_price' => $input['fees'][$item][0],
                                    'is_trash' => 0
                                ]);
                            }
                        } else {
                            DB::table('monthly_class_item')->insert([
                                'class_id' => $input['class_name'],
                                'academic_year_id' => $input['academic_year'],
                                'item_id' => $item,
                                'status' => 'active',
                                'month_id' => $input['month_id'],
                                'created_at' => date('Y-m-d h:i:s'),
                                'created_by' => auth()->user()->id,
                                'item_price' => $input['fees'][$item][0],
                                'is_trash' => 0
                            ]);
                        }
                    }
                }
                Session::flash('success', 'Monthly Item Added');
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
        } else {
            Session::flash('danger', 'Please Select An Item');
            return redirect()->back();
        }
        return redirect()->route('monthly.item.setup.index');
    }
    public function monthlyItemIndex(Request $request)
    {
        $academicYearList = AcademicYear::where('is_trash', '0')->whereNot('status', 'cancel')->latest('id')->pluck('year', 'id')->toArray();
        $classList = ['0' => 'Select Class'] + Classes::where('is_trash', '0')->whereNot('status', 'cancel')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $months = ['0' => 'Select Month'] + DB::table('enum_month')->pluck('name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        if ($request->ajax()) {
            $classId = $request->class_id;
            $monthId = $request->monthId;
            $academicYearId = $request->academicYearId;
            $products = DB::table('products')->where('products.status', 'active')->select('products.*')->get();
            foreach ($products as $product) {
                $product->classId = $classId;
                $product->monthId = $monthId;
                $product->academicYearId = $academicYearId;
            }
            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('fees', function ($row) {
                    if (DB::table('monthly_class_item')->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->where('item_id', $row->id)->exists()) {
                        $itemVal = DB::table('monthly_class_item')->where('class_id', $row->classId)->where('item_id', $row->id)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->first();

                        return ' <input type="text" class="form-control" name="fees[' . $row->id . '][]" value="' . $itemVal->item_price . '" id="feesId_' . $row->id . '" tabindex="' . $row->id+1 . '">';
                    } else {
                        $prices = DB::table('pricing')->where('product_id', $row->id)->where('class_id', $row->classId)->where('status', 'active')->first();
                        if (!empty($prices)) {
                            $itemPrice = $prices->price;
                        } else {
                            $itemPrice = 0;
                        }
                        return ' <input type="text" class="form-control" name="fees[' . $row->id . '][]" value="' . $itemPrice . '" id="feesId_' . $row->id . '" tabindex="' . $row->id+1 . '">';
                    }
                })
                ->addColumn('checkbox', function ($row) {
                    if (DB::table('monthly_class_item')->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->where('item_id', $row->id)->exists()) {
                        if (DB::table('contact_payable_items')->where('product_id', $row->id)->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->where('paid_amount', '>', '0')->exists()) {
                            $btn = '<input type="checkbox" class=" all-check-box" checked="" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '"  keyValue="' . $row->id . '" disabled>';
                        } else {
                            $btn = '<input type="checkbox" class="allCheck all-check-box" checked="" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                        }
                    } else {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="item_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    }
                    return $btn;
                })
                ->addColumn('all_item', function ($row) {
                    if (DB::table('monthly_class_item')->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->where('item_id', $row->id)->exists()) {
                        if (DB::table('contact_payable_items')->where('product_id', $row->id)->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('month_id', $row->monthId)->where('is_trash', '0')->where('paid_amount', '>', '0')->exists()) {
                            $btn = '<input type="checkbox" class="monthly-check-box" id="allMonthlyItemId_' . $row->id . '" checked="" name="all_monthly_item_id[' . $row->id . '][]" value="' . $row->id . '"  keyValue="' . $row->id . '" disabled>';
                        } else {
                            if ((DB::table('monthly_class_item')->where('class_id', $row->classId)->where('academic_year_id', $row->academicYearId)->where('is_trash', '0')->where('item_id', $row->id)->count())==12){
                                $btn = '<input type="checkbox" class="monthly-check-box" id="allMonthlyItemId_' . $row->id . '" checked="" name="all_monthly_item_id[' . $row->id . '][]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="checkMonthlyItem('.$row->id.')">';
                            }else{
                                $btn = '<input type="checkbox" class="monthly-check-box" id="allMonthlyItemId_' . $row->id . '" name="all_monthly_item_id[' . $row->id . '][]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="checkMonthlyItem('.$row->id.')">';
                            }

                        }
                    } else {
                        $btn = '<input type="checkbox" class="monthly-check-box" id="allMonthlyItemId_' . $row->id . '" name="all_monthly_item_id[' . $row->id . '][]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="checkMonthlyItem('.$row->id.')">';
                    }
                    // onclick="unCheckMonthlyItem(this.id);"
                    return $btn;
                })
                ->rawColumns(['checkbox', 'fees', 'all_item'])
                ->make(true);
        }
        return view('Item::monthlyItem.create', compact('academicYearList', 'classList', 'months', 'request','currentYear'));
    }
}
