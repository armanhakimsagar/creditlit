<?php

namespace App\Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use PhpParser\Node\Expr\FuncCall;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    // For Account Category
    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table('accountcategorys')
            ->join('accounttypes', 'accounttypes.id', 'accountcategorys.AccountTypeId')
            ->select('accounttypes.TypeName as account_type','accountcategorys.*')
            ->where('accountcategorys.is_trash', 0)->whereNot('accountcategorys.status', 'cancel')->get();
            return DataTables::of($data)->addIndexColumn()
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
                    $btn = ' <a href=" ' . route('account.category.edit', [$row->id]) . ' " class=" btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('account.category.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::accountCategory.index');
    }


    public function create(){
        $pageTitle = "Add Account Category";
        $accountType = ['' => 'Select Account Type'] + DB::table('accounttypes')->pluck('TypeName', 'id')->toArray();
        return view('Payment::accountCategory.create',compact('pageTitle','accountType'));
    }

    public function store(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'category_name' => 'required',
            'status' => 'required',
        ]);

        $categoryName = $request->category_name;
        $accountTypeId = $request->account_type;

        // Find Same id in acount category which is not trash
        $findSameId = DB::table('accountcategorys')->where('TypeName', $categoryName)->where('AccountTypeId',$accountTypeId)->where('is_trash', 0)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same account category already exist');
            return redirect()->back()->withInput($request->all());
        }

        // Find Same id in acount category trash
        $findSameIdTrash = DB::table('accountcategorys')->where('TypeName', $categoryName)->where('AccountTypeId',$accountTypeId)->where('is_trash', 1)->first();
        if (isset($findSameIdTrash)) {
            Session::flash('error', 'Same account category Already has in Trash, Please Restore <a href="' . route('account.category.restore', [$findSameIdTrash->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }


        try {
            DB::table('accountcategorys')->insert([
                'TypeName' => $request->category_name,
                'AccountTypeId' => $request->account_type,
                'status' => $request->status,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Account Category Created Successfully ");
            return redirect()->route('account.category.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id){
        $pageTitle = "Edit Account Category";
        $accountType = ['' => 'Select Account Type'] + DB::table('accounttypes')->pluck('TypeName', 'id')->all();
        $accountCategory = DB::table('accountcategorys')
                                ->join('accounttypes', 'accounttypes.id', 'accountcategorys.AccountTypeId')
                                ->select('accounttypes.TypeName as account_type','accountcategorys.TypeName as category_name','accountcategorys.*')
                                ->where('accountcategorys.id',$id)->first();
        return view('Payment::accountCategory.edit',compact('pageTitle','accountType','accountCategory'));
    }

    public function update(Request $request,$id)
    {

         // return $request;
         $validated = $request->validate([
            'category_name' => 'required',
            'status' => 'required',
        ]);

        $categoryName = $request->category_name;
        $accountTypeId = $request->account_type;

        // Find Same id in acount category which is not trash
        $findSameId = DB::table('accountcategorys')->whereNot('id',$id)->where('TypeName', $categoryName)->where('AccountTypeId',$accountTypeId)->where('is_trash', 0)->first();
        if (isset($findSameId)) {
            Session::flash('error', 'Same account category already exist');
            return redirect()->back()->withInput($request->all());
        }

        // Find Same id in acount category trash
        $findSameIdTrash = DB::table('accountcategorys')->whereNot('id',$id)->where('TypeName', $categoryName)->where('AccountTypeId',$accountTypeId)->where('is_trash', 1)->first();
        if (isset($findSameIdTrash)) {
            Session::flash('error', 'Same account category Already has in Trash, Please Restore <a href="' . route('account.category.restore', [$findSameIdTrash->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>');
            return redirect()->back()->withInput($request->all());
        }


            try {
                $accountCategory = array();
                $accountCategory['AccountTypeId'] = $request->account_type;
                $accountCategory['TypeName'] = $request->category_name;
                $accountCategory['status'] = $request->status;
                $accountCategory['updated_at'] = date("Y-m-d h:i:s");
                $accountCategory['updated_by'] = Auth::user()->id;

                DB::table('accountcategorys')->where('id',$id)->update($accountCategory);

                Session::flash('success', 'Account Category is updated successfully !');
                return redirect('account-category-index');

            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back();
            }
    }

    public function destroy($id)
    {
        DB::table('accountcategorys')->where('id',$id)->update(['is_trash'=>1]);

        Session::flash('success', 'Account category deleted successfully !');
        return redirect()->back();
    }

    // For account

    public function getAccountCategory(Request $request)
    {
          $data =DB::table('accountcategorys')
                ->join('accounttypes', 'accounttypes.id', 'accountcategorys.AccountTypeId')
                ->where('accountcategorys.AccountTypeId',$request->accountType)
                ->select('accountcategorys.*')
                ->where('accountcategorys.is_trash', '0')->get();

        return response()->json($data);
    }

    public Function accountIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('accountlists')
                    ->join('accounttypes', 'accounttypes.id', 'accountlists.AccountTypeId')
                    ->join('accountcategorys', 'accountcategorys.id', 'accountlists.AccountCategoryId')
                    ->select('accounttypes.TypeName as account_type','accountcategorys.TypeName as category_name','accountlists.*')
                    ->where('accountlists.is_trash', 0)->whereNot('accountlists.status', 'cancel')->get();
            return DataTables::of($data)->addIndexColumn()
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
                    $btn = ' <a href=" ' . route('account.edit', [$row->id]) . ' " class=" btn btn-outline-info btn-xs" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="' . route('account.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::account.index');
    }

    public function accountCreate()
    {
        $pageTitle = "Add Account";
        $accountType = ['' => 'Select Account Type'] + DB::table('accounttypes')->pluck('TypeName', 'id')->toArray();
        $category = ['' => 'Select Account Category'] + DB::table('accountcategorys')->pluck('TypeName', 'id')->toArray();
        return view('Payment::account.create',compact('pageTitle','accountType','category'));
    }
    public function accountStore(Request $request)
    {

        $accountIdYearFind = date("ymd");
        $accountIdPrefix = 'AC';
        $Account = DB::table('accountlists')->latest()->first()->id;
        $totalAccount = $Account + 1;
        $accountLastsixDigit = sprintf("%06d", $totalAccount);
        $accountIdGenarate = $accountIdPrefix . '-' . $accountIdYearFind .'-'. $accountLastsixDigit;
        try {
           DB::table('accountlists')->insert([
                'AccountCategoryId' => $request->account_category_id,
                'AccountTypeId' => $request->account_type,
                'ShortName' => $request->short_name,
                'BankName' => $request->bank_name,
                'BankBranch' => $request->bank_branch,
                'AccountName' => $request->account_name,
                'AccountNumber' => $request->account_number,
                'AccountId' => $accountIdGenarate,
                'status' => $request->status,
                'created_at' => date("Y-m-d h:i:s"),
                'created_by' => Auth::id(),
                'is_trash' => 0,
            ]);
            Session::flash('success', "Account Created Successfully ");
            return redirect()->route('account.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function accountEdit($id)
    {
        $pageTitle = "Edit Account";
        $accountType = ['' => 'Select Account Type'] + DB::table('accounttypes')->pluck('TypeName', 'id')->toArray();
         $account = DB::table('accountlists')
            ->join('accounttypes', 'accounttypes.id', 'accountlists.AccountTypeId')
            ->leftjoin('accountcategorys', 'accountcategorys.id', 'accountlists.AccountCategoryId')
            ->select('accountlists.ShortName as short_name','accountlists.BankName as bank_name','accountlists.BankBranch as bank_branch','accountlists.AccountName as account_name','accountlists.AccountNumber as account_number','accountlists.*')
            ->where('accountlists.id',$id)
            ->first();

         $category = DB::table('accountcategorys')
            ->where('AccountTypeId',$account->AccountTypeId)
            ->pluck('TypeName', 'id')
            ->all();                      
        return view('Payment::account.edit',compact('pageTitle','accountType','account','category'));
    }

    public function accountUpdate(Request $request, $id)
    {

        try {
            DB::table('accountlists')->where('id',$id)->update([
                'AccountCategoryId' => $request->account_category_id,
                'AccountTypeId' => $request->account_type,
                'ShortName' => $request->short_name,
                'BankName' => $request->bank_name,
                'BankBranch' => $request->bank_branch,
                'AccountName' => $request->account_name,
                'AccountNumber' => $request->account_number,
                'status' => $request->status,
                'updated_at' => date("Y-m-d h:i:s"),
                'updated_by' => Auth::id()
            ]);
            Session::flash('success', "Account Updated Successfully ");
            return redirect()->route('account.index');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function accountDestroy($id)
    {
        DB::table('accountlists')->where('id',$id)->update(['is_trash'=>1]);

        Session::flash('success', 'Account deleted successfully !');
        return redirect()->back();
    }


    // To Trash Account Category
    public function trash(Request $request){
        if ($request->ajax()) {
            $data = DB::table('accountcategorys')
            ->join('accounttypes', 'accounttypes.id', 'accountcategorys.AccountTypeId')
            ->select('accounttypes.TypeName as account_type','accountcategorys.*')
            ->where('accountcategorys.is_trash', 1)->whereNot('accountcategorys.status', 'cancel')->get();
            return DataTables::of($data)->addIndexColumn()
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
                    $action_btn = '<a href="' . route('account.category.restore', [$row->id]) . '" class="btn btn-danger btn-sm class_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a></span>';
                    return $action_btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Payment::accountCategory.trash');
    }

    // to restore class
    public function account_category_restore($id)
    {
        // // destroy permission check
        // if (is_null($this->user) || !$this->user->can('class.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized to view this page !');
        // }

        DB::table('accountcategorys')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Account Category Restored Successfully ");
        return redirect()->route('account.category.index');
    }
}
