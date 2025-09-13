<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contact\Models\keyPersonnel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Image;
use Spatie\Permission;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Carbon;

class keyPersonnelController extends Controller
{
    // To show all Role
    public function index(Request $request)
    {
        $data = DB::table('key_personnel as keyPersonnel')
            ->where('keyPersonnel.is_trash', 0)
            ->select('keyPersonnel.id', 'keyPersonnel.full_name as keyPersonnel_name', 'keyPersonnel.cp_phone_no as keyPersonnel_phone', 'keyPersonnel.cp_email as keyPersonnel_email', 'keyPersonnel.status', 'keyPersonnel.nationality', 'keyPersonnel.blood_group', 'keyPersonnel.gender', 'keyPersonnel.father_name', 'keyPersonnel.mother_name', 'keyPersonnel.date_of_birth', 'keyPersonnel.nid', 'keyPersonnel.marital_status', 'keyPersonnel.spouse_name', 'keyPersonnel.child_name', 'keyPersonnel.current_job_place', 'keyPersonnel.previous_job_place', 'keyPersonnel.address', 'keyPersonnel.photo')
            ->orderBy('keyPersonnel.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url = route('selected.keypersonnel.order.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $giftUrl = route('selected.keypersonnel.gift.filter', [
                        'from_date' => null,
                        'to_date' => null,
                        'customer_id' => urlencode($row->id),
                    ]);
                    $btn = '<button class="btn " type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bars"></i>
                    </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="' . route('keypersonnel.edit', [$row->id]) . '" target="_blank">Edit keyPersonnel</a> <a class="dropdown-item" href="' . $url . '">All Order</a> <a class="dropdown-item" href="' . $giftUrl . '">All Gift Details</a>
                            <a class="dropdown-item" id="delete" href="' . route('keypersonnel.delete', [$row->id]) . '">Delete</a>
                        </div>';
                    return $btn;
                })
                ->editColumn('gender', function ($row) {
                    if ($row->gender == 'male') {
                        return '<span>Boy</span>';
                    } elseif ($row->gender == 'female') {
                        return '<span>Girl</span>';
                    }
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
                ->rawColumns(['action', 'status', 'gender'])
                ->make(true);
        }
        return view('Contact::keyPersonnel.index');
    }

    // To show keyPersonnel create page
    public function create()
    {
        $addPage = "Add Key Personnel";
        return view("Contact::keyPersonnel.create", compact('addPage'));
    }

    //  To store key personnel
    public function store(Request $request)
    {
        $request->validate([
            'mobile_no' => 'max:16',
        ]);

        DB::beginTransaction();
        try {
            //keyPersonnel Imagge processing
            if ($request->photo) {
                $photo = $request->photo;
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/keypersonnel/' . $photoName);
            } else {
                $photoName = 'profile.png';
            }

            if ($request->date_of_birth) {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $request->date_of_birth);
                $date_of_birth = $carbonDate->format('Y-m-d');
            }
            $keyPersonnel = new keyPersonnel();
            $keyPersonnel->first_name = $request->first_name;
            $keyPersonnel->last_name = $request->last_name;
            $keyPersonnel->full_name = $request->first_name . ' ' . $request->last_name;
            $keyPersonnel->cp_phone_no = $request->cp_phone_no;
            $keyPersonnel->cp_email = $request->cp_email;
            $keyPersonnel->father_name = $request->father_name;
            $keyPersonnel->mother_name = $request->mother_name;
            if ($request->date_of_birth) {
                $keyPersonnel->date_of_birth = $date_of_birth;
            }
            $keyPersonnel->nid = $request->nid;
            $keyPersonnel->nationality = $request->nationality;
            $keyPersonnel->marital_status = $request->marital_status;
            $keyPersonnel->spouse_name = $request->spouse_name;
            $keyPersonnel->child_name = $request->child_name;
            $keyPersonnel->current_job_place = $request->current_job_place;
            $keyPersonnel->previous_job_place = $request->previous_job_place;
            $keyPersonnel->gender = $request->gender;
            $keyPersonnel->address = $request->address;
            $keyPersonnel->status = $request->status;
            $keyPersonnel->photo = $photoName;
            $keyPersonnel->save();
            DB::commit();
            Session::flash('success', "Key Personnel Created Successfully ");
            return redirect()->route('keypersonnel.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }


    // To show keyPersonnel create page
    public function edit($id)
    {
        $editPage = "Edit Key Personnel";
        $keyPersonnel = DB::table('key_personnel')
            ->where('key_personnel.id', $id)
            ->where('key_personnel.is_trash', 0)
            ->first();
        return view("Contact::keyPersonnel.edit", compact('editPage', 'keyPersonnel'));
    }

    // To update user data
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //Key Personnel Image processing
            if ($request->photo) {
                $old_photo = $request->old_photo;

                if ($old_photo == "profile.png") {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/keypersonnel/' . $photoName);
                } else if (File::exists(base_path() . '/public/backend/images/keypersonnel/' . $request->old_photo)) {
                    if (File::exists(base_path() . '/public/backend/images/keypersonnel/' . $request->old_photo)) {
                        unlink(base_path() . '/public/backend/images/keypersonnel/' . $request->old_photo);
                    }
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/keypersonnel/' . $photoName);
                } else {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/keypersonnel/' . $photoName);
                }
            } else {
                $photoName = $request->old_photo;
            }

            if ($request->date_of_birth) {
                $carbonDate = Carbon::createFromFormat('d-m-Y', $request->date_of_birth);
                $date_of_birth = $carbonDate->format('Y-m-d');
            }

            $keyPersonnel = keyPersonnel::find($id);
            $keyPersonnel->first_name = $request->first_name;
            $keyPersonnel->last_name = $request->last_name;
            $keyPersonnel->full_name = $request->first_name . ' ' . $request->last_name;
            $keyPersonnel->cp_phone_no = $request->cp_phone_no;
            $keyPersonnel->cp_email = $request->cp_email;
            $keyPersonnel->father_name = $request->father_name;
            $keyPersonnel->mother_name = $request->mother_name;
            if ($request->date_of_birth) {
                $keyPersonnel->date_of_birth = $request->date_of_birth;
            }
            $keyPersonnel->nid = $request->nid;
            $keyPersonnel->nationality = $request->nationality;
            $keyPersonnel->marital_status = $request->marital_status;
            $keyPersonnel->spouse_name = $request->spouse_name;
            $keyPersonnel->child_name = $request->child_name;
            $keyPersonnel->current_job_place = $request->current_job_place;
            $keyPersonnel->previous_job_place = $request->previous_job_place;
            $keyPersonnel->gender = $request->gender;
            $keyPersonnel->address = $request->address;
            $keyPersonnel->status = $request->status;
            $keyPersonnel->photo = $photoName;
            $keyPersonnel->save();
            DB::commit();
            Session::flash('success', "Key Personnel Updated Successfully ");
            return redirect()->route('keypersonnel.index');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // To destroy keyPersonnel
    public function destroy($id)
    {
        keyPersonnel::where('id', $id)->update([
            'is_trash' => 1,
            'status' => 'cancel',
        ]);
        Session::flash('success', "Key Personnel Successfully Removed into Trash ");
        return redirect()->back();
    }

    // To trash keyPersonnel
    public function trash(Request $request)
    {
        $data = DB::table('key_personnel as keyPersonnel')
            ->where('keyPersonnel.is_trash', 1)
            ->select('keyPersonnel.id', 'keyPersonnel.full_name as keyPersonnel_name', 'keyPersonnel.cp_phone_no as keyPersonnel_phone', 'keyPersonnel.cp_email as keyPersonnel_email', 'keyPersonnel.status', 'keyPersonnel.nationality', 'keyPersonnel.blood_group', 'keyPersonnel.gender', 'keyPersonnel.father_name', 'keyPersonnel.mother_name', 'keyPersonnel.date_of_birth', 'keyPersonnel.nid', 'keyPersonnel.marital_status', 'keyPersonnel.spouse_name', 'keyPersonnel.child_name', 'keyPersonnel.current_job_place', 'keyPersonnel.previous_job_place', 'keyPersonnel.address', 'keyPersonnel.photo')
            ->orderBy('keyPersonnel.id', 'ASC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('keypersonnel.restore', [$row->id]) . '" class="btn btn-danger btn-sm keypersonnel_restore" id="restore" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fas fa-redo"></i></a>';
                    return $btn;
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
        return view('Contact::keyPersonnel.trash');
    }

    // to restore keyPersonnel
    public function keyPersonnelRestore($id)
    {
        keyPersonnel::where('id', $id)->update([
            'is_trash' => 0,
            'status' => 'active',
        ]);
        Session::flash('success', "Key Personnel Restored Successfully ");
        return redirect()->route('keypersonnel.trash');
    }


    // Selected keyPersonnel order
    public function selectedKeypersonnelOrder(Request $request)
    {
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $customer_id = '';
        $from_date = 0;
        $to_date = 0;
        $data = [];
        if ($request->search == 'true') {
            $datam = DB::table('orders')
                ->where('orders.is_trash', 0)
                ->leftjoin('contacts as customer', 'orders.bank_id', 'customer.id')
                ->leftjoin('contacts as supplier', 'orders.supplier_id', 'supplier.id')
                ->where('supplier.type', 4)
                ->leftjoin('key_personnel', 'orders.key_personnel_id', 'key_personnel.id')
                ->leftjoin('products as country', 'orders.country_id', 'country.id');

            if ($request->customer_id) {
                $datam->where('orders.key_personnel_id', $request->customer_id);
            }

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $datam->whereBetween('orders.order_date', [$request->from_date, $request->to_date]);
            }

            $data = $datam->select('orders.id', 'orders.order_invoice_no', 'orders.company_name', 'customer.full_name as customer_name', 'supplier.full_name as supplier_name', 'country.name as country_name', 'orders.order_date', 'orders.pending_status', 'orders.processing_status', 'orders.query_status', 'orders.cancel_status', 'orders.completed_status', 'orders.delivered_status', 'orders.order_status', 'key_personnel.full_name as key_personnel_name')
                ->orderBy('orders.id', 'ASC')
                ->get();
        }
        return view('Contact::keyPersonnel.all_order', compact('request', 'keyPersonnel', 'data'));
    }

    // Selected keyPersonnel order filter
    public function selectedKeypersonnelOrderFilter(Request $request)
    {
        $url = 'selected-keypersonnel-order?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }


    // Selected keyPersonnel order
    public function selectedKeypersonnelGift(Request $request)
    {
        $keyPersonnel = ['' => 'Select Key Personnel'] + DB::table('key_personnel')
            ->where('is_trash', 0)
            ->where('status', 'active')
            ->select('key_personnel.id', DB::raw('CONCAT(IFNULL(key_personnel.full_name,""),"/Mobile: ",IFNULL(key_personnel.cp_phone_no,"")) as full_name'))
            ->pluck('full_name', 'id')
            ->toArray();
        $customer_id = '';
        $from_date = 0;
        $to_date = 0;
        $data = [];
        if ($request->search == 'true') {

            $datam = DB::table('gifts')
                ->where('gifts.is_trash', 0)
                ->leftjoin('gift_type', 'gifts.type', 'gift_type.id')
                ->leftjoin('contacts as customer', 'gifts.bank_id', 'customer.id')
                ->leftjoin('contacts as deliveredMan', 'gifts.delivered_by', 'deliveredMan.id')
                ->leftjoin('key_personnel', 'gifts.key_personnel', 'key_personnel.id')
                ->leftJoin('users', 'gifts.created_by', 'users.id');

            if ($request->customer_id) {
                $datam->where('gifts.key_personnel', $request->customer_id);
            }

            if (!empty($request->to_date) && !empty($request->from_date)) {
                $datam->whereBetween('gifts.date', [$request->from_date, $request->to_date]);
            }

            $data = $datam->select('gifts.id', 'gifts.name as gift_name', 'gifts.cost', 'gifts.date as gift_date', 'gifts.status as status', 'gift_type.name as gift_type_name', 'customer.full_name as customer_name', 'deliveredMan.full_name as customer_name', 'deliveredMan.full_name as deliveredMan_name', 'key_personnel.full_name as keyPersonnel_name', DB::raw('CONCAT(IFNULL(users.first_name,"")," ", IFNULL(users.last_name,""),"") as issued_by'))
                ->orderBy('gifts.id', 'ASC')
                ->get();

            // echo "<pre>";
            // print_r($data);
            // exit();
        }
        return view('Contact::keyPersonnel.all_gift', compact('request', 'keyPersonnel', 'data'));
    }

    // Selected keyPersonnel order filter
    public function selectedKeypersonnelGiftFilter(Request $request)
    {
        $url = 'selected-keypersonnel-gift?search=true&from_date=' . ($request->from_date ?? null) . '&to_date=' . ($request->to_date ?? null) . '&customer_id=' . $request->customer_id;
        return redirect($url);
    }
}
