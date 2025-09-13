<?php

namespace App\Modules\Stuff\Http\Controllers;

use App\Http\Controllers\Controller;
use Devfaysal\BangladeshGeocode\Models\District;
use Devfaysal\BangladeshGeocode\Models\Division;
use Devfaysal\BangladeshGeocode\Models\Upazila;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class StuffController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $datam = DB::table('contacts as employee')
            ->where('employee.is_trash', 0)
            ->where('employee.type', 5)
            ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
            ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');

        if ($request->department) {
            $datam->where('employee.employee_department_id', $request->department);
        }
        if ($request->designation) {
            $datam->where('employee.employee_designation_id', $request->designation);
        }
        $data = $datam->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.*')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href=" ' . route('employee.edit', [$row->id]) . ' " class="btn btn-outline-info btn-xs" title="Edit"><i class="fas fa-edit"></i></a>
                              <a href="' . route('employee.delete', [$row->id]) . ' " class="btn btn-outline-danger btn-xs" title="Delete" id="delete" ><i class="fas fa-trash"></i></a>';
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
        $designation = ['0' => 'Select Designation'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        $department = ['0' => 'Select Department'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();

        return view('Stuff::stuff.index', compact('designation', 'department'));
    }

    public function create(Request $request)
    {

        $addPage = "Add Employee";
        $division = ['' => ' Select A Devision'] + Division::pluck('name', 'id')->all();
        $designation = ['' => ' Select Designation'] + DB::table('enum_employee_types')->pluck('name', 'id')->all();
        $department = ['' => ' Select Department'] + DB::table('enum_department_types')->pluck('name', 'id')->all();
        $religion = DB::table('religions')->pluck('name', 'id')->all();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view("Stuff::stuff.create", compact('addPage', 'division', 'religion', 'designation', 'department','currentYear'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'image|max:1024',
            $request->validate([
                'cp_phone_no' => ['nullable', Rule::unique('contacts')->where(function ($query) {
                    return $query->where('is_trash', 0);
                })]
            ]),

        ]);
        DB::beginTransaction();
        try {
            //Employee Image processing
            if ($request->photo) {
                $photo = $request->photo;
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/employees/' . $photoName);

            } else {
                $photoName = 'profile.png';
            }

            // Employee General info add in contact table
            $employeeId = DB::table('contacts')->insertGetId([
                'type' => 5,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'date_of_birth' => !empty($request->date_of_birth) ? date('d-m-Y', strtotime($request->date_of_birth)) : null,
                'employee_joining_date' => date('Y-m-d', strtotime($request->employee_joining_date)),
                'education_qualification' => $request->education_qualification,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'religion_id' => $request->religion,
                'cp_phone_no' => $request->cp_phone_no,
                'cp_email' => $request->cp_email,
                'employee_department_id' => $request->employee_department_id,
                'employee_designation_id' => $request->employee_designation_id,
                'father_name' => $request->fathers_name,
                'mother_name' => $request->mother_name,
                'status' => $request->status,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'photo' => $photoName,
                'mobile' => $request->emergency_phone_no,
                'payment_type' => $request->payment_type,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
            ]);

            if ($request->has('same_address')) {
                // When both address are same
                $sameAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $employeeId,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->present_division,
                    'district' => $request->present_district,
                    'upazila' => $request->present_upazila,
                    'area' => $request->present_address,
                    'is_present' => 1,
                    'is_permanent' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                // Present address
                $presentAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $employeeId,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->present_division,
                    'district' => $request->present_district,
                    'upazila' => $request->present_upazila,
                    'area' => $request->present_address,
                    'is_present' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                // Permanent address
                $permanentAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $employeeId,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->permanent_division,
                    'district' => $request->permanent_district,
                    'upazila' => $request->permanent_upazila,
                    'area' => $request->permanent_address,
                    'is_permanent' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            DB::commit();
            Session::flash('success', "Employee added successfully");
            return redirect()->route('employee.create');
        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {

        $editPage = "Edit Employee";
        $employee = DB::table('contacts as employee')
            ->where('employee.id', $id)
            ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
            ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id')
            ->leftjoin('addresses as employee_present_address', 'employee_present_address.contact_id', 'employee.id')
            ->where('employee_present_address.contact_type', 5)->where('employee_present_address.is_present', '1')
            ->leftjoin('addresses as employee_permanent_address', 'employee_permanent_address.contact_id', 'employee.id')
            ->where('employee_permanent_address.contact_type', 5)->where('employee_permanent_address.is_permanent', '1')
            ->select('employee.*', 'employee_permanent_address.area as permanent_address', 'employee_permanent_address.division as permanent_division', 'employee_permanent_address.district as permanent_district', 'employee_permanent_address.upazila as permanent_upazila', 'employee_present_address.area as present_address', 'employee_present_address.division as present_division', 'employee_present_address.district as present_district', 'employee_present_address.upazila as present_upazila', 'enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.photo as old_photo')
            ->first();


        $designation = ['' => ' Select Designation'] + DB::table('enum_employee_types')->pluck('name', 'id')->all();
        $department = ['' => ' Select Department'] + DB::table('enum_department_types')->pluck('name', 'id')->all();
        $religion = DB::table('religions')->pluck('name', 'id')->all();

        $division = ['' => 'Please Select A Devision'] + Division::pluck('name', 'id')->all();
        $present_selected_division = Division::where('id', $employee->present_division)->first();
        $present_district = ['' => 'Please Select A District'] + District::where('division_id', $employee->present_division)
            ->pluck('name', 'id')->all();
        $present_selected_district = District::where('id', $employee->present_district)->first();
        $present_upazila = ['' => 'Please Select A Upazila'] + Upazila::where('district_id', $employee->present_district)
            ->pluck('name', 'id')->all();
        $present_selected_upazila = Upazila::where('id', $employee->present_upazila)->first();

        $permanent_selected_division = Division::where('id', $employee->permanent_division)->first();
        $permanent_district = ['' => 'Please Select A District'] + District::where('division_id', $employee->permanent_division)
            ->pluck('name', 'id')->all();
        $permanent_selected_district = District::where('id', $employee->permanent_district)->first();
        $permanent_upazila = ['' => 'Please Select A Upazila'] + Upazila::where('district_id', $employee->permanent_district)
            ->pluck('name', 'id')->all();
        $permanent_selected_upazila = Upazila::where('id', $employee->permanent_upazila)->first();
        $same_address = DB::table('addresses')->where('addresses.contact_id', $id)->where('addresses.is_permanent', '1')->where('addresses.is_present', '1')->first();


        return view('Stuff::stuff.edit', compact('editPage', 'employee', 'division', 'designation', 'department', 'religion', 'division', 'present_selected_division', 'present_district', 'present_selected_district', 'present_upazila', 'present_selected_upazila', 'permanent_selected_division', 'permanent_district', 'permanent_selected_district', 'permanent_upazila', 'permanent_selected_upazila', 'same_address'));

    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'photo' => 'image|max:1024',
        ]);

        DB::beginTransaction();
        try {
            //Employee Image processing
            if ($request->photo) {
                $old_photo = $request->old_photo;
                if ($old_photo == "profile.png") {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/employees/' . $photoName);
                } else if (File::exists(base_path() . '/public/backend/images/employees/' . $request->old_photo)) {
                    if (File::exists(base_path() . '/public/backend/images/employees/' . $request->old_photo)) {
                        unlink(base_path() . '/public/backend/images/employees/' . $request->old_photo);
                    }
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/employees/' . $photoName);
                } else {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/employees/' . $photoName);
                }
            } else {
                $photoName = $request->old_photo;
            }

            // Employee General info add in contact table
            $employeeId = DB::table('contacts')->where('id', $id)->update([
                'type' => 5,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'date_of_birth' => !empty($request->date_of_birth) ? date('d-m-Y', strtotime($request->date_of_birth)) : null,
                'employee_joining_date' => date('Y-m-d', strtotime($request->employee_joining_date)),
                'education_qualification' => $request->education_qualification,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'religion_id' => $request->religion,
                'cp_phone_no' => $request->cp_phone_no,
                'cp_email' => $request->cp_email,
                'employee_department_id' => $request->employee_department_id,
                'employee_designation_id' => $request->employee_designation_id,
                'father_name' => $request->fathers_name,
                'mother_name' => $request->mother_name,
                'status' => $request->status,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'photo' => $photoName,
                'mobile' => $request->emergency_phone_no,
                'payment_type' => $request->payment_type,
                'bank_name' => $request->bank_name,
                'bank_account_name' => $request->bank_account_name,
                'bank_account_number' => $request->bank_account_number,
            ]);


            if ($request->has('same_address')) {
                // When both address are same
                $sameAddress = DB::table('addresses')->where('contact_id', $id)->delete([
                    'contact_id' => $id,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->present_division,
                    'district' => $request->present_district,
                    'upazila' => $request->present_upazila,
                    'area' => $request->present_address,
                    'is_present' => 1,
                    'is_permanent' => 1,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {
                // Present address
                $presentAddress = DB::table('addresses')->where('contact_id', $id)->where('is_present', 1)->delete();
                DB::table('addresses')->insert([
                    'contact_id' => $id,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->present_division,
                    'district' => $request->present_district,
                    'upazila' => $request->present_upazila,
                    'area' => $request->present_address,
                    'is_present' => 1,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),

                ]);

                // Permanent address
                $permanentAddress = DB::table('addresses')->where('contact_id', $id)->where('is_permanent', 1)->delete();

                DB::table('addresses')->insert([
                    'contact_id' => $id,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'division' => $request->permanent_division,
                    'district' => $request->permanent_district,
                    'upazila' => $request->permanent_upazila,
                    'area' => $request->permanent_address,
                    'is_permanent' => 1,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            DB::commit();
            Session::flash('success', 'Employee Updated Successfully done!!!!');
            return redirect()->back();
        } catch (\Exception$e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // Employee Delete
    public function destroy($id)
    {
        DB::table('sections')->where('id', $id)->update(['is_trash' => 1]);

        DB::table('contacts')
            ->join('addresses', 'addresses.contact_id', 'contacts.id')
            ->where('contacts.id', $id)->where('addresses.contact_id', $id)->update([
            'contacts.is_trash' => 1,
            'addresses.status' => 'cancel',
        ]);

        Session::flash('success', 'Employee deleted successfully !');
        return redirect()->back();
    }

    public function employeeEntry(){
        $data = DB::table('march')->whereNull('employee_id')->get();
        // dd($data);
        foreach($data as $value){

            $employeeId = DB::table('contacts')->insertGetId([
                'type' => 5,
                'first_name' => $value->name,
                'full_name' => $value->name,
                'employee_designation_id' => $value->designation_id,
                'status' => 'active',
                // 'created_by' => Auth::user()->id,
                // 'created_at' => date('Y-m-d H:i:s'),
                'bank_account_number' => $value->bank_acc,
            ]);
            DB::table('march')
                ->where('id', $value->id)->update([
                'march.employee_id' =>  $employeeId,
            ]);

             $sameAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $employeeId,
                    'address_cat_id' => 1,
                    'contact_type' => 5,
                    'is_present' => 1,
                    'is_permanent' => 1,
                ]);

        }

    }

    public function employeSetupEntry(){

        $data = DB::table('general')->where('gross','>',0)->get();

        foreach($data as $value){
            if($value->gross>0){

                    $basic= ($value->gross*25)/100;
                    $rent= ($value->gross*50)/100;
                    $medical= ($value->gross*15)/100;
                    $others= ($value->gross*10)/100;
                    $deduct = (($value->pf)?$value->pf:0) + (($value->advanced)?$value->advanced:0) + (($value->loan)?$value->loan:0);
                    $allow = (($value->extra)?$value->extra:0) + (($value->stduy)?$value->stduy:0);

                // echo"<pre>";
                // print_r($value->gross);
                // echo"<br>";
                // print_r($basic);
                // echo"<br>";
                // print_r($rent);
                // echo"<br>";
                // print_r($medical);
                // echo"<br>";
                // print_r($others);
                // echo"<br>";
                // print_r($deduct);
                // echo"<br>";
                // print_r($allow);
                // echo"<br>";
                // print_r(($basic+$rent+$medical+$others));
                // echo"<br>";
                // print_r(($value->gross+$allow)-$deduct);
                // echo"</pre>";
                // exit();
                $employeeSalaryId = DB::table('employee_salary')->insertGetId([
                    'contact_id' => $value->employee_id,
                    'basic_salary' => $basic,
                    'gross_salary' => $value->gross,
                    'other_salary' => ($basic+$rent+$medical+$others),
                    'deduction_salary' =>$deduct,
                    'total_salary' => ($value->gross+$allow)-$deduct,
                    'allowance_salary' => $allow,
                    'academic_year_id' => 6,
                    ]);

                $gross = DB::table('salary_item')->get();
                foreach($gross as $gross_value){
                    if($gross_value->id==1){
                        $amount = $rent;
                    }
                    if($gross_value->id==2){
                        $amount = $medical;
                    }
                    if($gross_value->id==3){
                        $amount = $others;
                    }
                    if($gross_value->id==7){
                        $amount = $basic;
                    }
                    if($gross_value->id==4){
                        $amount = $value->pf;
                    }
                    if($gross_value->id==10){
                        $amount = $value->advanced;
                    }
                    if($gross_value->id==9){
                        $amount = $value->stduy;
                    }
                    if($gross_value->id==11){
                        $amount = $value->loan;
                    }

                    if($gross_value->id==8){
                        $amount = $value->extra;
                    }

                    DB::table('employee_salary_item_details')->insertGetId([
                        'employee_id' => $value->employee_id,
                        'salary_id' => $employeeSalaryId,
                        'item_id' => $gross_value->id,
                        'amount_type' => $gross_value->amount_type,
                        'amount' =>$amount,
                        'total_amount' => $amount,
                    ]);
                }

            }

        }


    }
}
