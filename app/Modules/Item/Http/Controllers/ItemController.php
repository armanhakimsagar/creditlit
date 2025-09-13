<?php

namespace App\Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\UserItemRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = DB::table('products')->where('products.is_trash', 0)->select('products.*')->get();
            return DataTables::of($products)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge badge-success ">Active</span> ';
                    } elseif ($row->status == 'inactive') {
                        return '<span class="badge badge-warning">Inactive</span>';
                    } else {
                        return '<span class="badge badge-danger">Cancel</span> ';
                    }
                })
                ->addColumn('action', function ($row) {

                    $action = '<a href="' . route('edit.item', [$row->id]) . '" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="' . route('delete.item', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                    return $action;
                })->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('Item::item.index');
    }
    public function create()
    {
        $pageTitle = 'Add Country';
        $editTitle = false;
        return view('Item::item.create', compact('pageTitle', 'editTitle'));
    }
    public function store(Request $request)
    {

        $input = $request->all();
        $validated = $request->validate([

            'status' => 'required',
            'name' => 'required',

        ]);
        DB::beginTransaction();
        try {
            $name = $request->name;
            $short_name = $request->short_name;
            $errorName = [];
            foreach ($name as $key => $value) {
                if (Item::where('name', $value)->where('is_trash', 0)->where('status', 'active')->doesntExist()) {
                    if (Item::where('short_name', $short_name[$key])->where('is_trash', 0)->where('status', 'active')->doesntExist()) {
                        $item_id = Item::insertGetId([
                            'name' => $value,
                            'short_name' => $short_name[$key],
                            'status' => $request->status,
                            'is_trash' => 0,
                            'value' => 1,
                            'created_at' => date("Y-m-d h:i:s"),
                            'created_by' => Auth::user()->id,

                        ]);
                        if ($item_id) {
                            UserItemRelation::create([
                                'product_relation_id' => $item_id,
                                'name' => $value,
                                'type' => 'product',
                                'status' => $input['status'],
                                'is_trash' => '0',
                            ]);
                        }
                        Session::flash('success', 'item is added successfully !');
                    } else {
                        array_push($errorName, $short_name[$key]);
                    }
                } else {
                    array_push($errorName, $value);
                }
            }
            if (!empty($errorName)) {
                $nameJson = trim(json_encode($errorName), '[]');
                Session::flash('danger', $nameJson . ' already exists !');
            } else {
                Session::flash('success', ' No duplicate found !');
            }

            DB::commit();
            return redirect()->route('item.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Item';
        $editTitle = true;
        $product = DB::table('products')->where('products.id', $id)->where('products.is_trash', 0)->whereNot('products.status', 'cancel')->select('products.*')->first();

        $categorys = [null => 'Select Category'] + DB::table('user_product_relation')->where('is_trash', 0)->where('type', 'category')->whereNot('status', 'cancel')->pluck('name', 'id')->toArray();

        return view('Item::item.edit', compact('pageTitle', 'categorys', 'product', 'editTitle'));
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            if (DB::table('products')->whereNot('id', $id)->where('name', $input['name'][0])->doesntExist()) {
                Item::where('id', $id)->update([
                    'name' => $input['name'][0],
                    'short_name' => $input['short_name'][0],
                    'value' => '1',
                    'status' => $input['status'],
                ]);
                UserItemRelation::where('product_relation_id', $id)->update([
                    'name' => $input['name'],
                    'type' => 'product',
                    'status' => $input['status'],
                    'is_trash' => '0',
                ]);
                DB::commit();
                Session::flash('success', 'Item Updated Successfully');
            } else {
                Session::flash('danger', 'Same item or short name exist');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
        return redirect()->route('item.index');
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            DB::table('products')->where('id', $id)->update(['is_trash' => 1]);
            DB::commit();
            Session::flash('success', 'Item Deleted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
        return redirect()->route('item.index');
    }


    public function addPricing(Request $request)
    {

        $itemArr = DB::table('products')->where('status', 'active')->get();
        $priceArrList = DB::table('pricing')->pluck('price', 'product_id')->toArray();
        $totalCountry = count(DB::table('pricing')
            ->join('products', 'products.id', 'pricing.product_id')
            ->where('pricing.status', 'active')
            ->select('pricing.price', 'products.name as item_name')
            ->orderBy('pricing.class_id', 'asc')->get());
        return view('Item::pricing.create', compact('itemArr', 'priceArrList', 'totalCountry'));
    }


    public function pricingStore(Request $request)
    {
        $input = $request->all();
        $price = $input['price'];
        DB::beginTransaction();
        try {
            foreach ($price as $product_id => $val) {
                if (!empty($val)) {
                    DB::table('pricing')
                        ->updateOrInsert(
                            [
                                'product_id' => $product_id,
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
            Session::flash('success', 'Price Setup Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }

    // Generate Late fine
    public function generateLateFine()
    {
        $pageTitle = "Generate Late Fine";
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $enumMonth = DB::table('enum_month')->pluck('name', 'id')->toArray();
        $currentMonth = DB::table('enum_month')
            ->where('enum_month.id', date('n'))
            ->select('enum_month.id', 'enum_month.name')
            ->groupBy('enum_month.id', 'enum_month.name')
            ->first();
        $generatedData = DB::table('due_generated')->where('type', 2)
            ->leftjoin('classes', 'due_generated.class_id', 'classes.id')
            ->leftjoin('enum_month', 'due_generated.month_id', 'enum_month.id')
            ->leftjoin('academic_years', 'due_generated.academic_year_id', 'academic_years.id')
            ->select('due_generated.id', 'due_generated.created_at', 'classes.name as class_name', 'enum_month.name as month_name', 'academic_years.year as year_name')
            ->orderBy('due_generated.created_at', 'desc')
            ->get();
        $totalStudent = count($generatedData);
        return view("Item::lateFine.create", compact('pageTitle', 'academicYearList', 'enumMonth', 'currentMonth', 'generatedData', 'totalStudent'));
    }

    // To store late fine
    public function lateFineStore(Request $request)
    {

        $lateCountingItem = DB::table('late_fines')->select('item_id', 'fine_item_id')->first();
        $itemIds = json_decode($lateCountingItem->item_id);
        $fine_item = $lateCountingItem->fine_item_id;
        $studentWithDue = DB::table('contact_payable_items')
            ->where('academic_year_id', $request->academic_year_id)->where('month_id', $request->month_id)->where('is_paid', 0)->where('product_id', $itemIds)
            ->groupBy('contact_id')
            ->get();
        $currentDate = date('Y-m-d');
        $yearData = DB::table('academic_years')->where('id', $request->academic_year_id)->select('year')->first();
        $monthData = DB::table('enum_month')
            ->where('id', $request->month_id)
            ->select('short_name')
            ->first();
        $year = $yearData->year;
        $month = date('m', strtotime($monthData->short_name));
        $combinedDate = '';
        DB::beginTransaction();
        try {
            foreach ($studentWithDue as $item) {
                $alreadyHasFine = DB::table('contact_payable_items')->where('academic_year_id', $request->academic_year_id)->where('month_id', $request->month_id)->where('product_id', $fine_item)->where('contact_id', $item->contact_id)->first();

                if (empty($alreadyHasFine)) {
                    $lastDateData = DB::table('late_fines')->where('class_id', $item->class_id)->select('date', 'amount')->first();
                    $fineAmount = $lastDateData->amount;
                    $lastDate = $lastDateData->date;
                    $combinedDate = $year . '-' . $month . '-' . $lastDate;

                    if ((preg_match('/^\d{4}-\d{2}-\d{2}$/', $combinedDate)) && $fineAmount > 0) {
                        if ($combinedDate < $currentDate) {
                            $fineAmountAdd = DB::table('contact_payable_items')->insert([
                                'contact_id' => $item->contact_id,
                                'class_id' => $item->class_id,
                                'product_id' => $fine_item,
                                'month_id' => $request->month_id,
                                'academic_year_id' => $request->academic_year_id,
                                'amount' => $fineAmount,
                                'paid_amount' => 0,
                                'due' => $fineAmount,
                                'is_paid' => 0,
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'date' => date('Y-m-d'),
                            ]);

                            $hasGenerateLateFineHistory = DB::table('due_generated')->where('academic_year_id', $request->academic_year_id)->where('month_id', $request->month_id)->where('class_id', $item->class_id)->where('type', 2)->first();
                            if (empty($hasGenerateLateFineHistory)) {
                                $GenrateLateFineHistory = DB::table('due_generated')->insert([
                                    'class_id' => $item->class_id,
                                    'month_id' => $request->month_id,
                                    'academic_year_id' => $request->academic_year_id,
                                    'type' => 2,
                                    'created_by' => Auth::user()->id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }
                }
            }
            DB::commit();
            Session::flash('success', 'Fine Generated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('danger', $th->getMessage());
            return redirect()->back();
        }
    }
}
