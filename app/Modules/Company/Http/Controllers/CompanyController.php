<?php

namespace App\Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Image;

class CompanyController extends Controller
{
    protected $company_image_path;
    protected $company_brand_image_path;
    protected $water_pressure_photo_path;
    protected $principle_signature;
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->company_image_path = public_path('uploads/company');
        $this->company_image_relative_path = '/uploads/company';

        $this->company_brand_image_path = public_path('uploads/company/brandimage');
        $this->company_brand_image_relative_path = '/uploads/company/brandimage';

        $this->water_pressure_photo_path = public_path('backend/images/watermark');
        $this->water_pressure_relative_path = '/backend/images/watermark';

        $this->principle_signature = public_path('/image/principalSignature');
        $this->principle_signature_relative_path = '/image/principalSignature';
    }
    public function welcome()
    {
        return view("Company::welcome");
    }
    public function index()
    {
        $pageTitle = "List of Institution Information";
        $data = Company::select('companies.*')->where('status', 'active')->paginate(10);
        // dd($data);
        return view("Company::settings.index", compact('data', 'pageTitle'));
    }
    public function create()
    {
        return view("Company::settings.create");
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $input['status'] = 'active';
        if (isset($input['admit_card_design'])) {
            $input['admit_card_design'] = $input['admit_card_design'];
        }
        $data = Company::where('company_name', $input['company_name'])->exists();
        DB::beginTransaction();
        if (!$data) {
            try {
                $company_image = $request->file('image');
                if ($company_image) {
                    $company_image_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $company_image->getClientOriginalExtension());
                    $company_image_link = $this->company_image_relative_path . '/' . $company_image_title;
                } else {
                    $company_image_link = '';
                    $company_image_title = '';
                }

                $company_brand_image = $request->file('brand_image');
                if ($company_brand_image) {
                    $company_brand_image_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $company_brand_image->getClientOriginalExtension());
                    $company_brand_image_link = $this->company_brand_image_relative_path . '/' . $company_brand_image_title;
                } else {
                    $company_brand_image_link = '';
                    $company_brand_image_title = '';
                }

                $water_pressure_photo = $request->file('water_pressure_photo');
                if ($water_pressure_photo) {
                    $water_pressure_photo_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $water_pressure_photo->getClientOriginalExtension());
                    $water_pressure_photo_link = $this->water_pressure_relative_path . '/' . $water_pressure_photo_title;
                } else {
                    $water_pressure_photo_link = '';
                    $water_pressure_photo_title = '';
                }

                $principle_signature = $request->file('principle_signature');
                if ($principle_signature) {
                    $principle_signature_title = str_replace(' ', '-', 'principle-signature' . '_' . time() . '.' . $principle_signature->getClientOriginalExtension());
                    $principle_signature_link = $this->principle_signature_relative_path . '/' . $principle_signature_title;
                } else {
                    $principle_signature_link = '';
                    $principle_signature_title = '';
                }

                $input['brand_image'] = $company_brand_image_title;
                $input['image'] = $company_image_title;
                $input['water_pressure_photo'] = $water_pressure_photo_title;
                $input['principle_signature'] = $principle_signature_title;

                Company::create($input);
                if ($company_image != null) {
                    $company_image->move($this->company_image_path, $company_image_title);
                }

                if ($company_brand_image != null) {
                    $company_brand_image->move($this->company_brand_image_path, $company_brand_image_title);
                }

                if ($water_pressure_photo != null) {
                    $water_pressure_photo->move($this->water_pressure_photo_path, $water_pressure_photo_title);
                }
                if ($principle_signature != null) {
                    $principle_signature->move($this->principle_signature, $principle_signature_title);
                }
                DB::commit();
                Session::flash('success', 'Institution is added!');
            } catch (\Exception $e) {
                DB::rollBack();
                Session::flash('danger', $e->getMessage());
            }
        } else {
            Session::flash('info', 'This Company already added!');
        }
        return redirect()->route('institution.index');
    }
    public function edit($id)
    {
        $pageTitle = "Update Company";

        $data = Company::where('id', $id)
            ->select('companies.*')
            ->first();

        $accountCategory = ['' => 'Please Select Payment Method'] + DB::table('accountcategorys')->where('status', 'active')->pluck('TypeName', 'id')->all();

        if (empty($data)) {
            Session::flash('danger', 'Company not found.');
            return redirect()->route('institution.index');
        }

        return view("Company::settings.edit", compact('data', 'pageTitle','accountCategory'));
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $model = Company::where('id', $id)
            ->select('companies.*')
            ->first();
        $model1 = Company::where('id', $id)
            ->select('companies.*')
            ->first();
        if (!isset($input['rollEnableOnAdmitCard'])) {
            $input['rollEnableOnAdmitCard'] = 0;
        }

        if (!isset($input['enable_multiple_payment'])) {
            $input['enable_multiple_payment'] = 0;
        }

        if (!isset($input['enable_student_type_in_receipt'])) {
            $input['enable_student_type_in_receipt'] = 0;
        }

        if (isset($input['studentDefaultItem'])) {
            $input['studentDefaultItem'] = json_encode($input['studentDefaultItem']);
        }
        if (isset($input['studentReadmissionDefaultItem'])) {
            $input['studentReadmissionDefaultItem'] = json_encode($input['studentReadmissionDefaultItem']);
        }
        if (isset($input['repeatedDefaultItem'])) {
            $input['repeatedDefaultItem'] = json_encode($input['repeatedDefaultItem']);
        }
        if (isset($input['admit_card_design'])) {
            $input['admit_card_design'] = $input['admit_card_design'];
        }
        // Image link
        $company_image = $request->file('image');

        if ($company_image) {
            $company_image_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $company_image->getClientOriginalExtension());
            $company_image_link = $this->company_image_relative_path . '/' . $company_image_title;
        } elseif ($input['image_reset'] != 1) {
            $company_image_link = $model->image;
            $company_image_title = $model->image;
        } else {
            $company_image_link = '';
            $company_image_title = '';
        }

        $input['image'] = $company_image_title;

        $company_brand_image = $request->file('brand_image');
        if ($company_brand_image) {
            $company_brand_image_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $company_brand_image->getClientOriginalExtension());
            $company_brand_image_link = $this->company_brand_image_relative_path . '/' . $company_brand_image_title;
        } elseif ($input['brand_image_reset'] != 1) {
            $company_brand_image_link = $model->brand_image;
            $company_brand_image_title = $model->brand_image;
        } else {
            $company_brand_image_link = '';
            $company_brand_image_title = '';
        }

        $input['brand_image'] = $company_brand_image_title;

        $water_pressure_photo = $request->file('water_pressure_photo');
        $old_water_pressure_photo = $request->old_water_pressure_photo;
        if ($water_pressure_photo) {
            $water_pressure_photo_title = str_replace(' ', '-', $input['company_name'] . '_' . time() . '.' . $water_pressure_photo->getClientOriginalExtension());
            $water_pressure_photo_link = $this->water_pressure_relative_path . '/' . $water_pressure_photo_title;
        } else if ($old_water_pressure_photo) {
            $water_pressure_photo_title = $old_water_pressure_photo;
            $water_pressure_photo_link = $this->water_pressure_relative_path . '/' . $water_pressure_photo_title;
        } else {
            $water_pressure_photo_link = '';
            $water_pressure_photo_title = '';
        }

        $input['water_pressure_photo'] = $water_pressure_photo_title;

        $principle_signature = $request->file('principle_signature');
        $old_principle_signature = $request->old_principle_signature;

        if ($principle_signature) {
            $principle_signature_title = str_replace(' ', '-', 'principle-signature' . '_' . time() . '.' . $principle_signature->getClientOriginalExtension());
            $principle_signature_link = $this->principle_signature_relative_path . '/' . $principle_signature_title;
        } else if ($old_principle_signature) {
            $principle_signature_title = $old_principle_signature;
            $principle_signature_link = $this->principle_signature_relative_path . '/' . $principle_signature_title;
        } else {
            $principle_signature_link = '';
            $principle_signature_title = '';
        }

        $input['principle_signature'] = $principle_signature_title;

        if (isset($input['settings'])) {
            $input['ReportFontSize'] = $request->ReportFontSize;
            $input['principal_details_in_certificate'] = $request->principal_details_in_certificate;
        }
        $input['ReportFontSize'] = $request->ReportFontSize;
        $input['dollarExhangeRateBDT'] = $request->dollarExhangeRateBDT;
        $input['invoiceVatPercent'] = $request->invoiceVatPercent;
        $input['accountTitle'] = $request->accountTitle;
        $input['AccountNo'] = $request->AccountNo;
        $input['bankName'] = $request->bankName;
        $input['branchName'] = $request->branchName;
        $input['principal_details_in_certificate'] = $request->principal_details_in_certificate;
        $input['salary_system'] = $request->salary_system;

        $input['water_pressure_height'] = $request->water_pressure_height;
        $input['water_pressure_margin_top'] = $request->water_pressure_margin_top;
        $input['water_pressure_margin_left'] = $request->water_pressure_margin_left;
        $input['principle_signature_height'] = $request->principle_signature_height;
        $input['rollEnableOnAdmitCard'] = $request->rollEnableOnAdmitCard;
        $input['ownerMobileNumber'] = $request->ownerMobileNumber;
        $input['enable_multiple_payment'] = $request->enable_multiple_payment;
        $input['DefaultPaymentMethod'] = $request->DefaultPaymentMethod;
        $input['enable_student_type_in_receipt'] = $request->enable_student_type_in_receipt;

        DB::beginTransaction();
        try {
            $result = $model->update($input);

            if ($result) {

                if ($company_image != null) {
                    if (!empty($model1->image)) {
                        File::delete(base_path() . '/public/uploads/company/' . $model1->image);
                    }
                    $company_image->move($this->company_image_path, $company_image_title);
                }

                if ($company_brand_image != null) {
                    if (!empty($model1->brand_image)) {
                        File::delete(base_path() . '/public/uploads/company/brandimage/' . $model1->brand_image);
                    }
                    $company_brand_image->move($this->company_brand_image_path, $company_brand_image_title);
                }

                if ($water_pressure_photo != null) {
                    if (!empty($model1->water_pressure_photo)) {
                        File::delete(base_path() . '/public/backend/images/watermark/' . $model1->water_pressure_photo);
                    }
                    $water_pressure_photo->move($this->water_pressure_photo_path, $water_pressure_photo_title);
                }

                if ($principle_signature != null) {
                    if (!empty($model1->principle_signature)) {
                        File::delete(base_path() . '/public/image/principalSignature/' . $model1->principle_signature);
                    }
                    $principle_signature->move($this->principle_signature, $principle_signature_title);
                }
                DB::commit();
            }
            Session::flash('success', 'Successfully updated!');
            if (!isset($input['settings'])) {
                return redirect(route('institution.index'));
            } else {
                return redirect(route('institution.settings', 1));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function institutionSetting($id)
    {
        $pageTitle = "Add Settings";
        $data = Company::where('id', $id)
            ->select('companies.*')
            ->first();
        $items = DB::table('products')->where('status', 'active')->pluck('name', 'id')->toArray();
        $accountCategory = ['' => 'Please Select Payment Method'] + DB::table('accountcategorys')->where('status', 'active')->pluck('TypeName', 'id')->all();
        if (empty($data)) {
            Session::flash('danger', 'Institution not found.');
            return redirect()->route('institution.index');
        }
        return view("Company::settings.settingsCreate", compact('pageTitle', 'data', 'items', 'accountCategory'));
    }

    // SMS setting
    public function smsSetting()
    {
        $pageTitle = "Add SMS Settings";
        $data = DB::table('companynotificationsettings')->where('CompanyId', Session::get('company_id'))->first();
        return view("Company::settings.sms.setting", compact('pageTitle', 'data'));
    }

    // SMS setting store
    public function adminSmsStore(Request $request)
    {
        // Get all input data
        $input = $request->all();

        $input['CompanyId'] = Session::get('company_id');
        if (!isset($input['smsEnableAfterPayment'])) {
            $input['smsEnableAfterPayment'] = 0;
        }
        if (!isset($input['smsEnableGuardianSms'])) {
            $input['smsEnableGuardianSms'] = 0;
        }
        if (!isset($input['smsEnableTeacherSms'])) {
            $input['smsEnableTeacherSms'] = 0;
        }
        if (!isset($input['smsEnableDynamicSms'])) {
            $input['smsEnableDynamicSms'] = 0;
        }
        if (!isset($input['smsEnableDueSms'])) {
            $input['smsEnableDueSms'] = 0;
        }
        if (!isset($input['smsEnableOwnerSms'])) {
            $input['smsEnableOwnerSms'] = 0;
        }

        DB::table('companynotificationsettings')->updateOrInsert(
            [
                'CompanyId' => $input['CompanyId']],
            [
                'smsEnableAfterPayment' => $request->smsEnableAfterPayment,
                'smsEnableGuardianSms' => $request->smsEnableGuardianSms,
                'smsEnableTeacherSms' => $request->smsEnableTeacherSms,
                'smsEnableDynamicSms' => $request->smsEnableDynamicSms,
                'smsEnableDueSms' => $request->smsEnableDueSms,
                'smsEnableOwnerSms' => $request->smsEnableOwnerSms,
                'smsAfterStudentPaymentFormat' => $request->smsAfterStudentPaymentFormat,
                'smsStudentDueFormat' => $request->smsStudentDueFormat,
                'ownerSmsFormat' => $request->ownerSmsFormat,
            ]
        );
        Session::flash('success', 'Setting updated successfully!');
        return redirect()->back();

    }
    public function sidSetting($id)
    {
        $pageTitle = "Edit SID Settings";
        $data = DB::table('sid_config')->where('id', $id)
            ->select('sid_config.*')
            ->first();
        if (empty($data)) {
            Session::flash('danger', 'Sid setting not found.');
            return redirect()->route('sid.create');
        }
        return view("Company::settings.sid.edit", compact('pageTitle', 'data'));
    }

    // For Fine Settings
    public function fineSetting()
    {
        $pageTitle = "Edit Fine Settings";
        $items = DB::table('products')->where('status', 'active')->pluck('name', 'id')->toArray();
        $data = DB::table('late_fines')->first();
        $classList = DB::table('classes')->where('is_trash', 0)->where('status', 'active')->orderBy('classes.weight', 'ASC')->pluck('name', 'id')->toArray();
        return view("Company::settings.fine.create", compact('pageTitle', 'items', 'classList', 'data'));
    }

    // For store fine setting
    public function fineSettingStore(Request $request)
    {
        $input = $request->all();
        if (isset($input['item_id'])) {
            $input['item_id'] = json_encode($input['item_id']);
        } else {
            $input['item_id'] = '';
        }
        DB::beginTransaction();
        try {
            foreach ($input['class_id'] as $key => $value) {
                if (DB::table('late_fines')->where('class_id', $value)->doesntExist()) {
                    DB::table('late_fines')->insertGetId([
                        'class_id' => $value,
                        'date' => $input['date'][$value],
                        'amount' => $input['amount'][$value],
                        'item_id' => $input['item_id'],
                        'fine_item_id' => $input['fine_item_id'],
                        'created_by' => Auth::user()->id,
                        'created_at' => date("Y-m-d h:i:s"),
                    ]);
                } else {
                    DB::table('late_fines')->where('class_id', $value)->update([
                        'class_id' => $value,
                        'date' => $input['date'][$value],
                        'amount' => $input['amount'][$value],
                        'item_id' => $input['item_id'],
                        'fine_item_id' => $input['fine_item_id'],
                        'created_by' => Auth::user()->id,
                        'created_at' => date("Y-m-d h:i:s"),
                    ]);
                }
            }

            DB::commit();
            Session::flash('success', 'Fine Setting Store Successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function sidUpdate(Request $request, $id)
    {
        $input = $request->all();
        DB::beginTransaction();
        try {
            $result = DB::table('sid_config')->where('id', $id)->update([
                'defaultManualCustomizeSID' => $request->defaultManualCustomizeSID,
                'prefix' => $request->prefix,
                'year' => !empty($request->year) ? $request->year : 0,
                'month' => !empty($request->month) ? $request->month : 0,
                'sign_align_date' => $request->sign_align_date,
                'digits' => $request->digits,
            ]);

            if ($result) {
                DB::commit();
                Session::flash('success', "Setting Updated Successfully ");
                return redirect()->back();
            }

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function sidCreate()
    {
        $pageTitle = "Edit SID Settings";
        $data = DB::table('sid_config')->select('sid_config.*')
            ->first();
        return view("Company::settings.sid.create");
    }

    public function customizeSidStore(Request $request)
    {

        $result = DB::table('sid_config')->insert([
            'defaultManualCustomizeSID' => $request->defaultManualCustomizeSID,
            'prefix' => $request->prefix,
            'year' => !empty($request->year) ? $request->year : 0,
            'month' => !empty($request->month) ? $request->month : 0,
            'sign_align_date' => $request->sign_align_date,
            'digits' => $request->digits,
        ]);
        if ($result) {
            Session::flash('success', "Setting Updated Successfully ");
            return redirect()->route('sid.setting', 1);
        }

    }


    public function saveStudentCollectionData(Request $request){
        $items = $request->items;
        if (isset($items)) {
            $items = json_encode($items);
        }else{
            $items = null;
        }
        DB::table('companies')->where('id',1)->update([
            'studentCollectionSetItem' => $items
        ]);
        return response()->json(['message' => 'Data received successfully']);
    }
}
