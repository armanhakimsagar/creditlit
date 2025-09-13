<?php

namespace App\Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            // query builder
            $category_list=DB::table('user_product_relation')->where('is_trash',0)->where('type','category')->whereNot('status','cancel')->get();

            return DataTables::of($category_list)
                    ->addIndexColumn()
                    ->editColumn('status',function($row){
                        if($row->status=='active'){
                            return '<span class="badge badge-success ">Active</span> ';

                        }elseif($row->status=='inactive'){
                            return '<span class="badge badge-warning">Inactive</span>';
                        }else{
                            return '<span class="badge badge-danger">Cancel</span> ';
                        }
                    })
                    // '.route('section.edit',[$row->id]).'
                    ->addColumn('action',function($row){

                        $action =' <a href="'.route('category.edit',[$row->id]).'" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="'.route('category.delete',[$row->id]).'" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                        return $action;

                    })->rawColumns(['action','status'])
                      ->make(true);

        }

        return view('Item::category.index');
    }

    public function create()
    {
        $pageTitle ="Add Category";
        $editTitle =false;
        return view('Item::category.create',compact('pageTitle','editTitle'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $input = $request->all();
        $validated = $request->validate([
     
            'status' => 'required',
            'name' => 'required',

        ]);
        $count = count($input['name']);
       
            try {
                $category = [];  
                $errorName = [];
                $name = $request->name;
                foreach ($name as $key => $value) {
                    if (DB::table('user_product_relation')->where('name', $value)->where('type', 'category')->where('status','active')->where('is_trash',0)->doesntExist()) {
                        $category[$key]['name']= $value;
                        $category[$key]['status'] =$request->status;
                        $category[$key]['created_at'] = date("Y-m-d h:i:s");
                        $category[$key]['created_by'] = Auth::user()->id;
                        $category[$key]['is_trash'] = 0;
                        $category[$key]['type'] = 'category';
                    }else {
                        array_push($errorName,$value);
                    }
                    
                }
                if(!empty($errorName)){
                    $nameJson = trim(json_encode($errorName), '[]');
                    Session::flash('danger', $nameJson.' already exists !');
                }
                else{
                    Session::flash('success', ' No duplicate found !');
                }
                if (!empty($category)) {
                    DB::table('user_product_relation')->insert($category);
                    Session::flash('success', ' category is added successfully !');
                }
    
                DB::commit();

                return redirect('category');

            } catch (\Throwable $th) {
                DB::rollback();
                Session::flash('danger', $th->getMessage());
            }
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle ="Edit Category";
        $editTitle =true;
        $category=DB::table('user_product_relation')->where('is_trash',0)->where('id',$id)->first();
        return view('Item::category.edit',compact('pageTitle','category','editTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required',Rule::unique('user_product_relation', 'name')->ignore($id)],
            'status' => 'required',

        ]);

            try {
                $category = array();
                $category['name'] = $request->name[0];
                $category['status'] = $request->status;
                $category['updated_at'] = date("Y-m-d h:i:s");
                $category['updated_by'] = Auth::user()->id;

                DB::table('user_product_relation')->where('id',$id)->update($category);

                Session::flash('success', 'category is updated successfully !');
                return redirect('category');

            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       DB::table('user_product_relation')->where('id',$id)->update(['is_trash'=>1]);
       Session::flash('success', 'category deleted successfully !');
       return redirect()->back();
    }
}
