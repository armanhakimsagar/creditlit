<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class GiftTypeController extends Controller
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
            $gift_type_list=DB::table('gift_type')->where('is_trash',0)->whereNot('status','cancel')->get();

            return DataTables::of($gift_type_list)
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

                        $action =' <a href="'.route('gift.type.edit',[$row->id]).'" class=" btn btn-outline-info btn-xs "data-toggle="tooltip" data-placement="top" title="edit"><i class="fas fa-edit"></i></a>
                            <a href="'.route('gift.type.delete',[$row->id]).'" class="btn btn-outline-danger btn-xs" id="delete"data-toggle="tooltip" data-placement="top" title="delete"><i class="fas fa-trash"></i></a>';
                        return $action;

                    })->rawColumns(['action','status'])
                      ->make(true);

        }

        return view('Contact::giftType.index');
    }

    public function create()
    {
        $pageTitle ="Add Gift Type";
        $editTitle =false;
        return view('Contact::giftType.create',compact('pageTitle','editTitle'));
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
                $giftType = [];  
                $errorName = [];
                $name = $request->name;
                foreach ($name as $key => $value) {
                    if (DB::table('gift_type')->where('name', $value)->where('status','active')->where('is_trash',0)->doesntExist()) {
                        $giftType[$key]['name']= $value;
                        $giftType[$key]['status'] =$request->status;
                        $giftType[$key]['created_at'] = date("Y-m-d h:i:s");
                        $giftType[$key]['created_by'] = Auth::user()->id;
                        $giftType[$key]['is_trash'] = 0;
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
                if (!empty($giftType)) {
                    DB::table('gift_type')->insert($giftType);
                    Session::flash('success', ' gift type is added successfully !');
                }
    
                DB::commit();

                return redirect()->route('gift.type.index');

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
        $pageTitle ="Edit Gift Type";
        $editTitle =true;
        $giftType=DB::table('gift_type')->where('is_trash',0)->where('id',$id)->first();
        return view('Contact::giftType.edit',compact('pageTitle','giftType','editTitle'));
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
            'name' => ['required',Rule::unique('gift_type', 'name')->ignore($id)],
            'status' => 'required',

        ]);

            try {
                $giftType = array();
                $giftType['name'] = $request->name[0];
                $giftType['status'] = $request->status;
                $giftType['updated_at'] = date("Y-m-d h:i:s");
                $giftType['updated_by'] = Auth::user()->id;

                DB::table('gift_type')->where('id',$id)->update($giftType);

                Session::flash('success', 'gift type is updated successfully !');
                
                return redirect()->route('gift.type.index');

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
       DB::table('gift_type')->where('id',$id)->update(['is_trash'=>1]);
       Session::flash('success', 'gift type deleted successfully !');
       return redirect()->back();
    }
}
