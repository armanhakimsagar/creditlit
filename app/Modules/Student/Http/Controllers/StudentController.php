<?php

namespace App\Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Devfaysal\BangladeshGeocode\Models\District;
use Devfaysal\BangladeshGeocode\Models\Division;
use Devfaysal\BangladeshGeocode\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Image;
use Yajra\DataTables\DataTables;

class StudentController extends Controller
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

    // To show all student
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $datam = DB::table('contacts as student')
                ->where('student.is_trash', 0)
                ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
                ->join('contacts as father', 'father_relation.target_contact', 'father.id')
                ->where('father.type', 2)
                ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
                ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
                ->where('mother.type', 3)
                ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
                ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
                ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
                ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id');

            if ($request->academicYearId) {
                $datam->where('contact_academics.academic_year_id', $request->academicYearId);
            }
            if ($request->classId) {
                $datam->whereIn('contact_academics.class_id', $request->classId);
            }
            if ($request->sectionId) {
                $datam->whereIn('contact_academics.section_id', $request->sectionId);
            }
            if ($request->genderId) {
                $datam->where('student.gender', $request->genderId);
            }
            if ($request->versionId) {
                $datam->whereIn('contact_academics.version_id', $request->versionId);
            }
            if ($request->status) {
                $datam->where('student.status', $request->status);
            }
            if ($request->groupId) {
                $datam->where('contact_academics.group_id', $request->groupId);
            }
            if ($request->admissionTypeId) {
                $datam->where('contact_academics.admission_type', $request->admissionTypeId);
            }
            if ($request->transportId) {
                $datam->whereIn('contact_academics.transport_id', $request->transportId);
            }
            if ($request->shiftId) {
                $datam->whereIn('contact_academics.shift_id', $request->shiftId);
            }
            if ($request->studentTypeId) {
                $datam->whereIn('contact_academics.student_type_id', $request->studentTypeId);
            }
            $data = $datam->select('student.id', 'student.full_name as student_name', 'student.cp_phone_no as student_phone', 'student.cp_email as student_email', 'student.status', 'student.nationality', 'student.blood_group', 'student.gender', 'student.religion_id', 'classes.name as class_name', 'contact_academics.class_roll', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'transports.name as transport_name', 'groups.name as group_name', 'contact_academics.registration_no', 'contact_academics.previous_school', 'contact_academics.admission_date', 'academic_years.year as academic_year', 'guardian.full_name as guardian_name', 'mother.full_name as mother_name', 'guardian.cp_phone_no as guardian_number', 'student.contact_id', 'contact_academics.class_id')
            // ->orderBy('contact_academics.class_id', 'ASC')
                ->orderBy('student.contact_id', 'ASC')
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
                                href="' . route('student.profile', [$row->id]) . '" target="_blank">Profile</a>
                            <a class="dropdown-item" href="' . route('student.edit', [$row->id]) . '" target="_blank">Edit
                                Student</a>
                            <a class="dropdown-item" href="' . route('student.readmission', ['classId' => $row->class_id, 'studentId' => $row->id]) . '" target="_blank">Re Admission</a>
                            <a class="dropdown-item" href="' . route('students.quick.payment', [$row->id]) . '" target="_blank">Quick Payment</a>
                            <a class="dropdown-item" href="' . route('students.wise.payment.history.report', [$row->id]) . '" target="_blank">Account Profile</a>
                            <a class="dropdown-item" href="' . route('students.payment.setup', [$row->id]) . '" target="_blank">Payment Setup</a>
                            <a class="dropdown-item" href="' . route('students.wise.payment.price.list', [$row->id]) . '" target="_blank">Payment Price List</a>
                            <a class="dropdown-item" href="' . route('student.delete', [$row->id]) . '">Delete</a>
                        </div>';
                    // $action_btn = '<a href="' . route('student.profile', [$row->id]) . '" class="btn btn-outline-primary btn-xs" data-id= "" title="Profile" data-toggle="tooltip"><i class="fas fa-user"></i></a> <a href="' . route('student.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-id= "" title="Edit" data-toggle="tooltip"><i class="fas fa-edit"></i></a> <a href="" class="btn btn-outline-danger btn-xs" id= "delete" title="Delete" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->editColumn('contact_id', function ($row) {
                    return '<a href="' . route('student.profile', [$row->id]) . '" target="_blank">' . ($row->contact_id) . '</a>';
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
                ->rawColumns(['action', 'status', 'contact_id', 'gender'])
                ->make(true);
        }
        $academic_year = ['' => 'All'] + DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        // $classList = ['' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $classList = DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $shiftList = ['' => 'All'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $studentTypeList = DB::table('student_type')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $versionList = DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = ['' => 'All'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $transportList = DB::table('transports')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $shift_list = DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        return view('Student::student.index', compact('academic_year', 'classList', 'shiftList', 'versionList', 'groupList', 'currentYear', 'transportList', 'shift_list', 'studentTypeList'));
    }
    // To show Student create page
    public function create()
    {
        $addPage = "Add Class";
        $classes = ['' => 'Please Select Class'] + DB::table('classes')->where('status', 'active')->where('is_trash', 0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->all();
        $sections = DB::table('sections')->where('status', 'active')->where('is_trash', 0)->pluck('name', 'id')->all();
        $division = ['' => 'Please Select A Devision'] + Division::pluck('name', 'id')->all();
        $transport = ['' => 'Please Select Transport'] + DB::table('transports')->pluck('name', 'id')->all();
        $shift = ['' => 'Please Select Shift'] + DB::table('shifts')->pluck('name', 'id')->all();
        $studentType = ['' => 'Please Select Student Type'] + DB::table('student_type')->pluck('name', 'id')->all();
        $version = ['' => 'Please Select Version'] + DB::table('versions')->pluck('name', 'id')->all();
        $groupName = ['' => 'Please Select Group'] + DB::table('groups')->pluck('name', 'id')->all();
        $religion = DB::table('religions')->pluck('name', 'id')->all();
        $academic_year = DB::table('academic_years')->where('status', 'active')->where('is_trash', 0)->latest('id')->pluck('year', 'id')->all();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        $existing_guardian = [0 => 'Select Guardian'] + DB::table('contacts')
            ->where('contacts.is_trash', 0)->where('contacts.type', 1)
            ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)->whereNotNull('guardian.full_name')->whereNotNull('guardian.cp_phone_no')
            ->select(DB::raw('CONCAT(IFNULL(contacts.contact_id,""),"/", IFNULL(contacts.full_name,""),"/",IFNULL(guardian.contact_id,""),"/", IFNULL(guardian.full_name,""),"/",IFNULL(guardian.cp_phone_no,"")) as full_name'), 'guardian.id')
            ->pluck('full_name', 'id')
            ->toArray();
        $productlist = DB::table('products')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        if (!empty(Session::get('studentDefaultItem'))) {
            $setItems = json_decode(Session::get('studentDefaultItem'));
            $selectedItems = DB::table('products')->whereIn('id', $setItems)->orderByRaw(DB::raw("FIELD(id, " . implode(',', $setItems) . ")"))->get();
            $productlist = DB::table('products')->where('status', 'active')->whereNotIn('id', $setItems)->get();
            return view("Student::student.create", compact('addPage', 'classes', 'division', 'transport', 'shift', 'version', 'groupName', 'academic_year', 'existing_guardian', 'religion', 'productlist', 'enumMonth', 'currentYear', 'selectedItems', 'studentType'));
        } else {
            return view("Student::student.create", compact('addPage', 'classes', 'division', 'transport', 'shift', 'version', 'groupName', 'academic_year', 'existing_guardian', 'religion', 'productlist', 'enumMonth', 'currentYear', 'studentType'));
        }
    }

    // To store student admission
    public function store(Request $request)
    {
        $input = $request->all();
        if (is_null($this->user) || !$this->user->can('student.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // check is that roll has in any student of same academic year and class & section  ???
        $academicYear = $request->academic_year_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $shiftId = $request->shift_id;
        $versionId = $request->version_id;
        $groupId = $request->group_id;
        $roll = $request->roll;
        $registration = $request->registration;

        $studentNotTrash = DB::table('contacts')
            ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contacts.type', 1)
            ->where('contacts.is_trash', 0);

        if ($academicYear && $classId && $roll) {
            if ($academicYear) {
                $findSameRoll = $studentNotTrash->where('academic_year_id', $academicYear);
            }
            if ($classId) {
                $findSameRoll = $studentNotTrash->where('class_id', $classId);
            }
            if ($sectionId) {
                $findSameRoll = $studentNotTrash->where('section_id', $sectionId);
            }
            if ($shiftId) {
                $findSameRoll = $studentNotTrash->where('shift_id', $shiftId);
            }
            if ($versionId) {
                $findSameRoll = $studentNotTrash->where('version_id', $versionId);
            }
            if ($groupId) {
                $findSameRoll = $studentNotTrash->where('group_id', $groupId);
            }
            if ($roll) {
                $findSameRoll = $studentNotTrash->where('class_roll', $roll);
            }
            if ($registration) {
                $findSameRoll = $studentNotTrash->where('registration_no', $registration);
            }
            $findSame = $findSameRoll->select('contacts.is_trash', 'contact_academics.*');
            $find = $findSame->first();

        }
        if (isset($find)) {
            Session::flash('error', 'Already has a student with same class & roll </span>');
            return redirect()->back()->withInput($request->all());
        }

        $validated = $request->validate([
            'photo' => 'image',
            'phone' => 'min:0',
        ]);
        DB::beginTransaction();
        try {
            //Student Imagge processing
            if ($request->photo) {
                $photo = $request->photo;
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
            } else {
                $photoName = 'profile.png';
            }

            // If anyone want to create Student With new guardian
            if ($request->guardian_details == 'new') {
                if (Session::get('defaultManualCustomizeSID') == 1) {
                    $studentIdYearFind = DB::table('academic_years')->where('id', $request->academic_year_id)->select('academic_years.year')->first();
                    $studentIdPrefix = 'SID';
                    $studentIdYear = substr($studentIdYearFind->year, 2);
                    $totalStudent = DB::table('contacts')->where('type', 4)->count() + 1;
                    $studentLastFourDigit = sprintf("%04d", $totalStudent);
                    $studentIdGenarate = $studentIdYear . '07' . $studentLastFourDigit;
                }
                if (Session::get('defaultManualCustomizeSID') == 2) {
                    $studentId = DB::table('sid_config')->first();
                    $studentIdPrefix = $studentId->prefix;
                    $studentIdYear = ($studentId->year == 1) ? date('y') : '';
                    $studentIdMonth = ($studentId->month == 1) ? date('n') : '';
                    $signAlign = $studentId->sign_align_date;
                    $digits = $studentId->digits;
                    $totalStudent = DB::table('contacts')->where('type', 4)->count() + 1;
                    //get last record
                    $number = $this->generateSID($digits, $totalStudent);
                    if ($studentIdYear != '' && $studentIdMonth != '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdYear . $studentIdMonth . $signAlign . $number['sid'];
                    } else if ($studentIdYear != '' && $studentIdMonth == '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdYear . $signAlign . $number['sid'];

                    } else if ($studentIdYear == '' && $studentIdMonth != '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdMonth . $signAlign . $number['sid'];
                    }
                }

                // Student General info add in contact table
                if (Session::get('defaultManualCustomizeSID') == 3) {
                    $studentId = DB::table('contacts')->insertGetId([
                        'type' => 1,
                        'contact_id' => $request->student_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'full_name' => $request->first_name . ' ' . $request->last_name,
                        'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                        'birth_certificate' => $request->birth_certificate,
                        'nationality' => $request->nationality,
                        'blood_group' => $request->blood_group,
                        'gender' => $request->gender,
                        'religion_id' => $request->religion,
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'photo' => $photoName,
                        'fingerprint_card_no' => $request->fingerprint_card_no,
                        'fingerprint_card_serial_no' => $request->fingerprint_card_serial_no,
                    ]);
                } else {
                    $studentId = DB::table('contacts')->insertGetId([
                        'type' => 1,
                        'contact_id' => $studentIdGenarate,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'full_name' => $request->first_name . ' ' . $request->last_name,
                        'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                        'birth_certificate' => $request->birth_certificate,
                        'nationality' => $request->nationality,
                        'blood_group' => $request->blood_group,
                        'gender' => $request->gender,
                        'religion_id' => $request->religion,
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'photo' => $photoName,
                        'fingerprint_card_no' => $request->fingerprint_card_no,
                        'fingerprint_card_serial_no' => $request->fingerprint_card_serial_no,
                    ]);
                }

                // Student academic info add in Contact Academic table
                $studentAcademicId = DB::table('contact_academics')->insertGetId([
                    'contact_id' => $studentId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'shift_id' => $request->shift_id,
                    'student_type_id' => $request->student_type_id,
                    'version_id' => $request->version_id,
                    'admission_no' => $request->admission_no,
                    'class_roll' => $request->roll,
                    'registration_no' => $request->registration,
                    'admission_date' => date('Y-m-d', strtotime($request->admission_date)),
                    'academic_year_id' => $request->academic_year_id,
                    'previous_school' => $request->preschool,
                    'group_id' => $request->group_id,
                    'status' => $request->status,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'transport_id' => $request->transport_id,
                    'notes' => $request->notes,
                ]);

                // Father information add in contact table
                $fatherId = DB::table('contacts')->insertGetId([
                    'type' => 2,
                    'full_name' => $request->father_name,
                    'company_name' => $request->father_company,
                    'education_qualification' => $request->father_education,
                    'income' => $request->father_income,
                    'cp_phone_no' => $request->father_phone,
                    'cp_email' => $request->father_email,
                    'occupation' => $request->father_occupation,
                    'nid' => $request->father_nid,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                // Mother Information information add in contact table
                $motherId = DB::table('contacts')->insertGetId([
                    'type' => 3,
                    'full_name' => $request->mother_name,
                    'company_name' => $request->mother_company,
                    'education_qualification' => $request->mother_education,
                    'income' => $request->mother_income,
                    'cp_phone_no' => $request->mother_phone,
                    'cp_email' => $request->mother_email,
                    'occupation' => $request->mother_occupation,
                    'nid' => $request->mother_nid,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                // Guardian ID Generate
                $guardianIdYearFind = DB::table('academic_years')->where('id', $request->academic_year_id)->select('academic_years.year')->first();
                $guardianIdPrefix = 'G';
                $guardianIdYear = substr($guardianIdYearFind->year, 2);
                $totalGuardian = DB::table('contacts')->where('type', 4)->count() + 1;
                $guardianLastFourDigit = sprintf("%04d", $totalGuardian);
                $guardianIdGenarate = $guardianIdPrefix . '' . $guardianIdYear . '' . $guardianLastFourDigit;

                // Guardian Information information add in contact table
                $guardianId = DB::table('contacts')->insertGetId([
                    'type' => 4,
                    'full_name' => $request->guardian_name,
                    'cp_phone_no' => $request->guardian_phone,
                    'guardian_relation' => $request->guardian_relation,
                    'contact_id' => $guardianIdGenarate,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                // Guardian address add in address table
                $guardianAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $guardianId,
                    'address_cat_id' => 1,
                    'contact_type' => 4,
                    'area' => $request->guardian_address,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                /***************************************
                Relation make in contact hierarchy table
                 ***************************************/

                // Relation with Student and Father
                $fatherRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $fatherId,
                    'relationship_type_nodeid' => 1,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $motherRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $motherId,
                    'relationship_type_nodeid' => 2,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $guardianRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $guardianId,
                    'relationship_type_nodeid' => 3,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);
            }
            // If anyone want to create student with existing guardian
            elseif ($request->guardian_details == 'existing') {

                if (Session::get('defaultManualCustomizeSID') == 1) {
                    $studentIdYearFind = DB::table('academic_years')->where('id', $request->academic_year_id)->select('academic_years.year')->first();
                    $studentIdPrefix = 'SID';
                    $studentIdYear = substr($studentIdYearFind->year, 2);
                    $totalStudent = DB::table('contacts')->where('type', 1)->count() + 1;
                    $studentLastFourDigit = sprintf("%04d", $totalStudent);
                    $studentIdGenarate = $studentIdYear . '07' . $studentLastFourDigit;
                }
                if (Session::get('defaultManualCustomizeSID') == 2) {
                    $studentId = DB::table('sid_config')->first();
                    $studentIdPrefix = $studentId->prefix;
                    $studentIdYear = ($studentId->year == 1) ? date('y') : '';
                    $studentIdMonth = ($studentId->month == 1) ? date('n') : '';
                    $signAlign = $studentId->sign_align_date;
                    $digits = $studentId->digits;
                    $totalStudent = DB::table('contacts')->where('type', 1)->count() + 1;
                    //get last record
                    $number = $this->generateSID($digits, $totalStudent);
                    if ($studentIdYear != '' && $studentIdMonth != '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdYear . $studentIdMonth . $signAlign . $number['sid'];
                    } else if ($studentIdYear != '' && $studentIdMonth == '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdYear . $signAlign . $number['sid'];

                    } else if ($studentIdYear == '' && $studentIdMonth != '') {
                        $studentIdGenarate = $studentIdPrefix . $studentIdMonth . $signAlign . $number['sid'];
                    }
                }

                // Student General info add in contact table
                $studentId = DB::table('contacts')->insertGetId([
                    'type' => 1,
                    'contact_id' => $studentIdGenarate,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'full_name' => $request->first_name . ' ' . $request->last_name,
                    'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
                    'birth_certificate' => $request->birth_certificate,
                    'nationality' => $request->nationality,
                    'blood_group' => $request->blood_group,
                    'gender' => $request->gender,
                    'religion_id' => $request->religion,
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'status' => $request->status,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'photo' => $photoName,
                    'fingerprint_card_no' => $request->fingerprint_card_no,
                    'fingerprint_card_serial_no' => $request->fingerprint_card_serial_no,
                ]);

                // Student General info add in contact table
                if (Session::get('defaultManualCustomizeSID') == 3) {
                    $studentId = DB::table('contacts')->insertGetId([
                        'type' => 1,
                        'contact_id' => $request->student_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'full_name' => $request->first_name . ' ' . $request->last_name,
                        'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                        'birth_certificate' => $request->birth_certificate,
                        'nationality' => $request->nationality,
                        'blood_group' => $request->blood_group,
                        'gender' => $request->gender,
                        'religion_id' => $request->religion,
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'photo' => $photoName,
                    ]);
                } else {
                    // Student General info add in contact table
                    $studentId = DB::table('contacts')->insertGetId([
                        'type' => 1,
                        'contact_id' => $studentIdGenarate,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'full_name' => $request->first_name . ' ' . $request->last_name,
                        'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
                        'birth_certificate' => $request->birth_certificate,
                        'nationality' => $request->nationality,
                        'blood_group' => $request->blood_group,
                        'gender' => $request->gender,
                        'religion_id' => $request->religion,
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'status' => $request->status,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'cp_phone_no' => $request->phone,
                        'cp_email' => $request->email,
                        'photo' => $photoName,
                    ]);
                }
                // Student academic info add in Contact Academic table
                $studentAcademicId = DB::table('contact_academics')->insertGetId([
                    'contact_id' => $studentId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'shift_id' => $request->shift_id,
                    'student_type_id' => $request->student_type_id,
                    'version_id' => $request->version_id,
                    'admission_no' => $request->admission_no,
                    'class_roll' => $request->roll,
                    'registration_no' => $request->registration,
                    'admission_date' => date('Y-m-d', strtotime($request->admission_date)),
                    'academic_year_id' => $request->academic_year_id,
                    'previous_school' => $request->preschool,
                    'group_id' => $request->group_id,
                    'status' => $request->status,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'transport_id' => $request->transport_id,
                    'notes' => $request->notes,
                ]);

                // Existing Guardian Findout
                $old_guardian = DB::table('contacts as guardian')->where('guardian.id', $request->existing_guardian_id)
                    ->join('contact_hierarchy as guardian_source', 'guardian.id', 'guardian_source.target_contact')
                    ->where('guardian_source.relationship_type_nodeid', 3)
                    ->join('contact_hierarchy as father', 'father.source_contactid', 'guardian_source.source_contactid')
                    ->where('father.relationship_type_nodeid', 1)
                    ->join('contact_hierarchy as mother', 'mother.source_contactid', 'guardian_source.source_contactid')
                    ->where('mother.relationship_type_nodeid', 2)
                    ->select('guardian.id as guardian_id', 'guardian_source.source_contactid', 'father.target_contact as father_id', 'mother.target_contact as mother_id')->first();

                // Guardian address add in address table
                $guardianAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $old_guardian->guardian_id,
                    'address_cat_id' => 1,
                    'contact_type' => 4,
                    'area' => $request->guardian_address,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                /***************************************
                Relation make in contact hierarchy table
                 ***************************************/

                // Relation with Student and Father
                $fatherRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $old_guardian->father_id,
                    'relationship_type_nodeid' => 1,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $motherRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $old_guardian->mother_id,
                    'relationship_type_nodeid' => 2,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $guardianRelation = DB::table('contact_hierarchy')->insert([
                    'source_contactid' => $studentId,
                    'target_contact' => $old_guardian->guardian_id,
                    'relationship_type_nodeid' => 3,
                    'created_by' => Auth::user()->id,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);
            }

            // Student address add in address table
            if ($request->has('same_address')) {
                // When both address are same
                $sameAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $studentId,
                    'address_cat_id' => 1,
                    'contact_type' => 1,
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
                    'contact_id' => $studentId,
                    'address_cat_id' => 1,
                    'contact_type' => 1,
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
                    'contact_id' => $studentId,
                    'address_cat_id' => 1,
                    'contact_type' => 1,
                    'division' => $request->permanent_division,
                    'district' => $request->permanent_district,
                    'upazila' => $request->permanent_upazila,
                    'area' => $request->permanent_address,
                    'is_permanent' => 1,
                    'created_by' => Auth::user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            //Ekhne Code marte hbe
            if (!empty($request->product_id)) {
                $products = $request->product_id;
                $amount = $request->amount;
                $discount = $request->discount;
                $payable = $request->payable;
                $note = $request->note;
                $affected_month = $request->affected_month;
                $month_id = $request->month_id;
                $discountArr = [];
                $selected_month = '';
                foreach ($products as $key => $product) {
                    if ($payable[$key][0] > 0) {
                        // $months = json_encode($affected_month[$key]);
                        // $discountArr[$key]['academic_year_id'] = $request->academic_year_id;
                        // $discountArr[$key]['class_id'] = $request->class_id;
                        // $discountArr[$key]['contact_id'] = $studentId;
                        // $discountArr[$key]['product_id'] = $product[0];
                        // $discountArr[$key]['actual_amount'] = (float) $amount[$key][0];
                        // $discountArr[$key]['amount'] = (float) $payable[$key][0];
                        // // $discountArr[$key]['affected_month'] = $months;
                        // $discountArr[$key]['discount_amount'] = (float) $discount[$key][0];
                        // $discountArr[$key]['notes'] = $note[$key][0];
                        // $discountArr[$key]['created_at'] = date('Y-m-d h:i:s');
                        // $discountArr[$key]['created_by'] = Auth::user()->id;

                        $dis_id = DB::table('contactwise_item_discount_price_list')->insertGetId([
                            'academic_year_id' => $request->academic_year_id,
                            'class_id' => $request->class_id,
                            'contact_id' => $studentId,
                            'product_id' => $product[0],
                            'actual_amount' => (float) $amount[$key][0],
                            'amount' => (float) $payable[$key][0],
                            'discount_amount' => (float) $discount[$key][0],
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'enum_month_id' => $month_id[$key][0],
                        ]);
                    }
                    if ($payable[$key][0] > 0) {
                        $exit = DB::table('contact_payable_items')->where('contact_id', $studentId)->where('product_id', $product[0])->where('month_id', $month_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();
                        if (!$exit) {
                            $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
                                'contact_id' => $studentId,
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
                                'contact_discount_id' => $dis_id,
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
                                'academic_year_id' => $request->academic_year_id,
                                'class_id' => $request->class_id,
                                'contact_id' => $studentId,
                                'product_id' => $productId_upto_month,
                                'actual_amount' => (float) $amount_month,
                                'amount' => (float) $amount_upto_month,
                                'discount_amount' => (float) $discount[$key][0],
                                'created_by' => Auth::user()->id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'enum_month_id' => $row,
                            ]);
                            $exist = DB::table('contact_payable_items')->where('contact_id', $studentId)->where('product_id', $productId_upto_month)->where('month_id', $row)->where('academic_year_id', $request->academic_year_id)->first();
                            if (!$exist) {
                                $till_current[$key1]['contact_id'] = $studentId;
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
                                ->where('contact_id', $studentId)->where('product_id', $product[0])
                                ->where('enum_month_id', $value)->where('academic_year_id', $request->academic_year_id)->first();

                            if (!$exist_check_whole) {
                                DB::table('contactwise_item_discount_price_list')->insert([
                                    'academic_year_id' => $request->academic_year_id,
                                    'class_id' => $request->class_id,
                                    'contact_id' => $studentId,
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

            DB::commit();
            Session::flash('success', __('Student::label.ADD_SUCCESSFULL_MSG'));
            return redirect()->route('student.create');
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    public function edit($id)
    {
        $editPage = "Edit Page";
        $productlist = DB::table('products')->where('status', 'active')->get();
        $student = DB::table('contacts as student')
            ->where('student.id', $id)
            ->join('addresses as student_present_address', 'student.id', 'student_present_address.contact_id')
            ->where('student_present_address.contact_type', 1)->where('student_present_address.is_present', '1')
            ->join('addresses as student_permanent_address', 'student.id', 'student_permanent_address.contact_id')
            ->where('student_permanent_address.contact_type', 1)->where('student_permanent_address.is_permanent', '1')
            ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
            ->join('contacts as father', 'father_relation.target_contact', 'father.id')
            ->where('father.type', 2)
            ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
            ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
            ->where('mother.type', 3)
            ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)
            ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('student_type', 'contact_academics.student_type_id', 'student_type.id')
            ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
            ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
            ->where('contact_academics.status', 'active')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
            ->select('student.id', 'student.first_name as first_name', 'student.last_name', 'student.cp_phone_no as student_phone', 'student.cp_email as student_email', 'student.status', 'student.date_of_birth', 'student.birth_certificate', 'student.nationality', 'student.blood_group', 'student.gender', 'student.religion_id', 'student.photo as old_photo', 'classes.id as class_id', 'contact_academics.class_roll', 'versions.id as version_id', 'sections.id as section_id', 'shifts.id as shift_id', 'student_type.id as student_type_id', 'transports.id as transport_id', 'groups.id as group_id', 'contact_academics.registration_no', 'contact_academics.admission_no', 'contact_academics.previous_school as preschool', 'contact_academics.admission_date', 'contact_academics.notes', 'academic_years.id as academic_year_id', 'student.cp_phone_no as phone', 'student.cp_email as email', 'father.id as father_id', 'father.full_name as father_name', 'father.education_qualification as father_education', 'father.company_name as father_company', 'father.income as father_income', 'father.cp_phone_no as father_phone', 'father.cp_email as father_email', 'father.occupation as father_occupation', 'father.nid as father_nid', 'mother.id as mother_id', 'mother.full_name as mother_name', 'mother.education_qualification as mother_education', 'mother.company_name as mother_company', 'mother.income as mother_income', 'mother.cp_phone_no as mother_phone', 'mother.cp_email as mother_email', 'mother.occupation as mother_occupation', 'mother.nid as mother_nid', 'guardian.id as guardian_id', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'guardian.guardian_relation as guardian_relation', 'student_present_address.area as present_address', 'student_present_address.division as present_division', 'student_present_address.district as present_district', 'student_present_address.upazila as present_upazila', 'student_permanent_address.area as permanent_address', 'student_permanent_address.division as permanent_division', 'student_permanent_address.district as permanent_district', 'student_permanent_address.upazila as permanent_upazila', 'guardian.id as guardian_id', 'student.fingerprint_card_no', 'student.fingerprint_card_serial_no')
            ->first();
        $contactwise_item = DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)
            ->join('products', 'contactwise_item_discount_price_list.product_id', 'products.id')
            ->where('class_id', $student->class_id)
            ->where('academic_year_id', $student->academic_year_id)
            ->select('contactwise_item_discount_price_list.*', 'products.name as product_name')->get();

        $classes = ['' => 'Please Select Class'] + DB::table('classes')->where('status', 'active')->where('is_trash', 0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->all();
        $sections = ['' => 'Please Select Section'] + DB::table('sections')->where('status', 'active')->where('is_trash', 0)->pluck('name', 'id')->all();
        $selected_sections = DB::table('section_relations')->where('status', 'active')->where('is_trash', 0)->where('section_relations.class_id', $student->class_id)->where('section_relations.section_id', $student->section_id)->first();
        $transport = ['' => 'Please Select Transport'] + DB::table('transports')->pluck('name', 'id')->all();
        $shift = ['' => 'Please Select Shift'] + DB::table('shifts')->pluck('name', 'id')->all();
        $studentType = ['' => 'Please Select Student Type'] + DB::table('student_type')->pluck('name', 'id')->all();
        $version = ['' => 'Please Select Version'] + DB::table('versions')->pluck('name', 'id')->all();
        $groupName = ['' => 'Please Select Group'] + DB::table('groups')->pluck('name', 'id')->all();
        $academic_year = ['' => 'Please Select A Year'] + DB::table('academic_years')->where('status', 'active')->where('is_trash', 0)->pluck('year', 'id')->all();
        $division = ['' => 'Please Select A Devision'] + Division::pluck('name', 'id')->all();
        $present_selected_division = Division::where('id', $student->present_division)->first();
        $present_district = ['' => 'Please Select A District'] + District::where('division_id', $student->present_division)
            ->pluck('name', 'id')->all();
        $present_selected_district = District::where('id', $student->present_district)->first();
        $present_upazila = ['' => 'Please Select A Upazila'] + Upazila::where('district_id', $student->present_district)
            ->pluck('name', 'id')->all();
        $present_selected_upazila = Upazila::where('id', $student->present_upazila)->first();

        $permanent_selected_division = Division::where('id', $student->permanent_division)->first();
        $permanent_district = ['' => 'Please Select A District'] + District::where('division_id', $student->permanent_division)
            ->pluck('name', 'id')->all();
        $permanent_selected_district = District::where('id', $student->permanent_district)->first();
        $permanent_upazila = ['' => 'Please Select A Upazila'] + Upazila::where('district_id', $student->permanent_district)
            ->pluck('name', 'id')->all();
        $permanent_selected_upazila = Upazila::where('id', $student->permanent_upazila)->first();
        $same_address = DB::table('addresses')->where('addresses.contact_id', $id)->where('addresses.is_permanent', '1')->where('addresses.is_present', '1')->first();
        $guardian_address = DB::table('addresses as guardian_area')->where('contact_id', $student->guardian_id)->select('guardian_area.area as guardian_address')->first();
        $existing_guardian = [0 => 'Select Guardian'] + DB::table('contacts')
            ->where('contacts.is_trash', 0)->where('contacts.type', 1)
            ->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)->whereNotNull('guardian.full_name')->whereNotNull('guardian.cp_phone_no')
            ->select(DB::raw('CONCAT(IFNULL(contacts.contact_id,""),"/", IFNULL(contacts.full_name,""),"/",IFNULL(guardian.contact_id,""),"/", IFNULL(guardian.full_name,""),"/",IFNULL(guardian.cp_phone_no,"")) as full_name'), 'guardian.id')
            ->pluck('full_name', 'id')
            ->toArray();
        $guardian_count = DB::table('contact_hierarchy')->where('target_contact', $student->father_id)->where('relationship_type_nodeid', 1)->count();
        $is_paid = DB::table('contact_payable_items')->where('contact_id', $id)->where('class_id', $student->class_id)->where('academic_year_id', $student->academic_year_id)->sum('paid_amount');
        return view("Student::student.edit", compact('editPage', 'student', 'classes', 'transport', 'shift', 'version', 'groupName', 'academic_year', 'sections', 'selected_sections', 'division', 'present_selected_division', 'present_district', 'present_selected_district', 'present_upazila', 'present_selected_upazila', 'permanent_selected_division', 'permanent_district', 'permanent_selected_district', 'permanent_upazila', 'permanent_selected_upazila', 'same_address', 'guardian_address', 'existing_guardian', 'guardian_count', 'contactwise_item', 'productlist', 'is_paid', 'studentType'));
    }

    // To store student admission
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'photo' => 'image',
        ]);

        // check is that roll has in any student of same academic year and class & section  ???
        $academicYear = $request->academic_year_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;
        $shiftId = $request->shift_id;
        $studentTypeId = $request->student_type_id;
        $versionId = $request->version_id;
        $groupId = $request->group_id;
        $roll = $request->roll;
        $registration = $request->registration;

        $studentNotTrash = DB::table('contacts')
            ->leftjoin('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->where('contacts.type', 1)
            ->whereNot('contact_academics.contact_id', $id)
            ->where('contacts.status', 'active')
            ->where('contacts.is_trash', 0);

        if ($academicYear && $classId && $roll) {
            if ($academicYear) {
                $findSameRoll = $studentNotTrash->where('academic_year_id', $academicYear);
            }

            if ($classId) {
                $findSameRoll = $studentNotTrash->where('class_id', $classId);
            }
            if ($sectionId) {
                $findSameRoll = $studentNotTrash->where('section_id', $sectionId);
            }
            if ($shiftId) {
                $findSameRoll = $studentNotTrash->where('shift_id', $shiftId);
            }
            if ($versionId) {
                $findSameRoll = $studentNotTrash->where('version_id', $versionId);
            }
            if ($groupId) {
                $findSameRoll = $studentNotTrash->where('group_id', $groupId);
            }
            if ($roll) {
                $findSameRoll = $studentNotTrash->where('class_roll', $roll);
            }
            if ($registration) {
                $findSameRoll = $studentNotTrash->where('registration_no', $registration);
            }
            $findSame = $findSameRoll->select('contacts.type', 'contacts.is_trash', 'contact_academics.*')
                ->first();

        }

        if (isset($findSame)) {
            Session::flash('error', 'Already has a student with same class & roll </span>');
            return redirect()->back()->withInput($request->all());
        }

        DB::beginTransaction();
        try {
            //Student Imagge processing
            if ($request->photo) {
                $old_photo = $request->old_photo;
                if ($old_photo == "profile.png") {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                } else if ($request->old_photo) {
                    unlink(base_path() . '/public/backend/images/students/' . $request->old_photo);
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                } else {
                    $photo = $request->photo;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                }
            } else {
                $photoName = $request->old_photo;
            }

            // Student General info add in contact table
            if (Session::get('defaultManualCustomizeSID') == 3) {
                $studentId = DB::table('contacts')->where('id', $id)->update([
                    'type' => 1,
                    'contact_id' => $request->student_id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'full_name' => $request->first_name . ' ' . $request->last_name,
                    'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                    'birth_certificate' => $request->birth_certificate,
                    'nationality' => $request->nationality,
                    'blood_group' => $request->blood_group,
                    'gender' => $request->gender,
                    'religion_id' => $request->religion,
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'status' => $request->status,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'photo' => $photoName,
                    'fingerprint_card_no' => $request->fingerprint_card_no,
                    'fingerprint_card_serial_no' => $request->fingerprint_card_serial_no,
                ]);
            } else {
                $studentId = DB::table('contacts')->where('id', $id)->update([
                    'type' => 1,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'full_name' => $request->first_name . ' ' . $request->last_name,
                    'date_of_birth' => date('d-m-Y', strtotime($request->date_of_birth)),
                    'birth_certificate' => $request->birth_certificate,
                    'nationality' => $request->nationality,
                    'blood_group' => $request->blood_group,
                    'gender' => $request->gender,
                    'religion_id' => $request->religion,
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'status' => $request->status,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'cp_phone_no' => $request->phone,
                    'cp_email' => $request->email,
                    'photo' => $photoName,
                    'fingerprint_card_no' => $request->fingerprint_card_no,
                    'fingerprint_card_serial_no' => $request->fingerprint_card_serial_no,
                ]);
            }

            // Student academic info add in Contact Academic table
            $studentAcademicId = DB::table('contact_academics')->where('contact_id', $id)->where('status', 'active')->update([
                'contact_id' => $id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'shift_id' => $request->shift_id,
                'student_type_id' => $request->student_type_id,
                'version_id' => $request->version_id,
                'admission_no' => $request->admission_no,
                'class_roll' => $request->roll,
                'registration_no' => $request->registration,
                'admission_date' => date('Y-m-d', strtotime($request->admission_date)),
                'academic_year_id' => $request->academic_year_id,
                'previous_school' => $request->preschool,
                'group_id' => $request->group_id,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'transport_id' => $request->transport_id,
                'notes' => $request->notes,
            ]);

            if ($request->guardian_details == 'default') {
                // Father information add in contact table
                $father = DB::table('contact_hierarchy as father')->where('source_contactid', $id)->where('relationship_type_nodeid', 1)->select('father.target_contact as fatherId')->first();
                DB::table('contacts')->where('id', $father->fatherId)->update([
                    'type' => 2,
                    'full_name' => $request->father_name,
                    'company_name' => $request->father_company,
                    'education_qualification' => $request->father_education,
                    'income' => $request->father_income,
                    'cp_phone_no' => $request->father_phone,
                    'cp_email' => $request->father_email,
                    'occupation' => $request->father_occupation,
                    'nid' => $request->father_nid,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Mother Information information add in contact table
                $mother = DB::table('contact_hierarchy as mother')->where('source_contactid', $id)->where('relationship_type_nodeid', 2)->select('mother.target_contact as motherId')->first();
                DB::table('contacts')->where('id', $mother->motherId)->update([
                    'type' => 3,
                    'full_name' => $request->mother_name,
                    'company_name' => $request->mother_company,
                    'education_qualification' => $request->mother_education,
                    'income' => $request->mother_income,
                    'cp_phone_no' => $request->mother_phone,
                    'cp_email' => $request->mother_email,
                    'occupation' => $request->mother_occupation,
                    'nid' => $request->mother_nid,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Guardian Information information add in contact table
                $guardian = DB::table('contact_hierarchy as guardian')->where('source_contactid', $id)->where('relationship_type_nodeid', 3)->select('guardian.target_contact as guardianId')->first();
                DB::table('contacts')->where('id', $guardian->guardianId)->update([
                    'type' => 4,
                    'full_name' => $request->guardian_name,
                    'cp_phone_no' => $request->guardian_phone,
                    'guardian_relation' => $request->guardian_relation,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Guardian address add in address table
                $guardianAddress = DB::table('addresses')->where('contact_id', $guardian->guardianId)->update([
                    'contact_id' => $guardian->guardianId,
                    'address_cat_id' => 1,
                    'contact_type' => 4,
                    'area' => $request->guardian_address,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else if ($request->guardian_details == 'existing') {

                // Already Exist Guardian Check
                $old_guardian_check = DB::table('contacts as student')
                    ->where('student.id', $id)
                    ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
                    ->join('contacts as father', 'father_relation.target_contact', 'father.id')
                    ->where('father.type', 2)
                    ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
                    ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
                    ->where('mother.type', 3)
                    ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                    ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                    ->where('guardian.type', 4)
                    ->select('student.id', 'father.id as father_id', 'mother.id as mother_id', 'guardian.id as guardian_id')->first();
                $old_guardian_id_count = count(DB::table('contact_hierarchy')->where('target_contact', $old_guardian_check->guardian_id)->get());
                if ($old_guardian_id_count == 1) {
                    DB::table('contacts')->where('id', $old_guardian_check->father_id)->delete();
                    DB::table('contacts')->where('id', $old_guardian_check->mother_id)->delete();
                    DB::table('contacts')->where('id', $old_guardian_check->guardian_id)->delete();
                    DB::table('addresses')->where('contact_id', $old_guardian_check->guardian_id)->delete();
                }

                // Existing Guardian Findout
                $old_guardian = DB::table('contacts as guardian')->where('guardian.id', $request->existing_guardian_id)
                    ->join('contact_hierarchy as guardian_source', 'guardian.id', 'guardian_source.target_contact')
                    ->where('guardian_source.relationship_type_nodeid', 3)
                    ->join('contact_hierarchy as father', 'father.source_contactid', 'guardian_source.source_contactid')
                    ->where('father.relationship_type_nodeid', 1)
                    ->join('contact_hierarchy as mother', 'mother.source_contactid', 'guardian_source.source_contactid')
                    ->where('mother.relationship_type_nodeid', 2)
                    ->select('guardian.id as guardian_id', 'guardian_source.source_contactid', 'father.target_contact as father_id', 'mother.target_contact as mother_id')->first();

                // dd($old_guardian_id_count);
                // exit();

                /***************************************
                Relation make in contact hierarchy table
                 ***************************************/

                // Relation with Student and Father
                $fatherRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->father_id)->update([
                    'target_contact' => $old_guardian->father_id,
                    'relationship_type_nodeid' => 1,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $motherRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->mother_id)->update([
                    'target_contact' => $old_guardian->mother_id,
                    'relationship_type_nodeid' => 2,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $guardianRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->guardian_id)->update([
                    'target_contact' => $old_guardian->guardian_id,
                    'relationship_type_nodeid' => 3,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);
            } else if ($request->guardian_details == 'new') {
                // Father information add in contact table
                $fatherId = DB::table('contacts')->insertGetId([
                    'type' => 2,
                    'full_name' => $request->father_name,
                    'company_name' => $request->father_company,
                    'education_qualification' => $request->father_education,
                    'income' => $request->father_income,
                    'cp_phone_no' => $request->father_phone,
                    'cp_email' => $request->father_email,
                    'occupation' => $request->father_occupation,
                    'nid' => $request->father_nid,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Mother Information information add in contact table
                $motherId = DB::table('contacts')->insertGetId([
                    'type' => 3,
                    'full_name' => $request->mother_name,
                    'company_name' => $request->mother_company,
                    'education_qualification' => $request->mother_education,
                    'income' => $request->mother_income,
                    'cp_phone_no' => $request->mother_phone,
                    'cp_email' => $request->mother_email,
                    'occupation' => $request->mother_occupation,
                    'nid' => $request->mother_nid,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Guardian ID Generate
                $guardianIdYearFind = DB::table('academic_years')->where('id', $request->academic_year_id)->select('academic_years.year')->first();
                $guardianIdPrefix = 'G';
                $guardianIdYear = substr($guardianIdYearFind->year, 2);
                $totalGuardian = DB::table('contacts')->where('type', 4)->count() + 1;
                $guardianLastFourDigit = sprintf("%04d", $totalGuardian);
                $guardianIdGenarate = $guardianIdPrefix . '' . $guardianIdYear . '' . $guardianLastFourDigit;

                // Guardian Information information add in contact table
                $guardianId = DB::table('contacts')->insertGetId([
                    'type' => 4,
                    'full_name' => $request->guardian_name,
                    'cp_phone_no' => $request->guardian_phone,
                    'guardian_relation' => $request->guardian_relation,
                    'contact_id' => $guardianIdGenarate,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                /***************************************
                Relation make in contact hierarchy table
                 ***************************************/

                // Relation with Student and Father
                $fatherRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->father_id)->update([
                    'source_contactid' => $id,
                    'target_contact' => $fatherId,
                    'relationship_type_nodeid' => 1,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $motherRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->mother_id)->update([
                    'source_contactid' => $id,
                    'target_contact' => $motherId,
                    'relationship_type_nodeid' => 2,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);

                // Relation with Student and Mother
                $guardianRelation = DB::table('contact_hierarchy')->where('source_contactid', $id)->where('target_contact', $request->guardian_id)->update([
                    'source_contactid' => $id,
                    'target_contact' => $guardianId,
                    'relationship_type_nodeid' => 3,
                    'last_modified_by' => Auth::user()->id,
                    'last_modified_date' => date('Y-m-d H:i:s'),
                ]);

                // Guardian address add in address table
                $guardianAddress = DB::table('addresses')->insertGetId([
                    'contact_id' => $guardianId,
                    'address_cat_id' => 1,
                    'contact_type' => 4,
                    'area' => $request->guardian_address,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Student address add in address table
            if ($request->has('same_address')) {
                // When both address are same
                $same_address = DB::table('addresses')->where('contact_id', $id)->delete();
                DB::table('addresses')->insertGetId([
                    'contact_id' => $id,
                    'address_cat_id' => 1,
                    'contact_type' => 1,
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
                $presentAddress = DB::table('addresses')->where('contact_id', $id)->where('is_present', 1)->delete();
                DB::table('addresses')->insert([
                    'contact_id' => $id,
                    'address_cat_id' => 1,
                    'contact_type' => 1,
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
                    'contact_type' => 1,
                    'division' => $request->permanent_division,
                    'district' => $request->permanent_district,
                    'upazila' => $request->permanent_upazila,
                    'area' => $request->permanent_address,
                    'is_permanent' => 1,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            //Update Class
            $update_contact_wise_class = DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)->where('academic_year_id', $request->academic_year_id)->get();

            foreach ($update_contact_wise_class as $update_contact_wise_class_value) {
                $update = DB::table('contactwise_item_discount_price_list')->where('id', $update_contact_wise_class_value->id)->update([
                    'class_id' => $request->class_id,
                    // 'updated_by' => Auth::user()->id,
                    // 'updated_at' => date('Y-m-d H:i:s'),

                ]);
            }

            $update_contact_payable_class = DB::table('contact_payable_items')->where('contact_id', $id)->where('academic_year_id', $request->academic_year_id)->get();

            foreach ($update_contact_payable_class as $update_contact_payable_class_value) {
                $update = DB::table('contact_payable_items')->where('id', $update_contact_payable_class_value->id)->update([
                    'class_id' => $request->class_id,
                    // 'updated_by' => Auth::user()->id,
                    // 'updated_at' => date('Y-m-d H:i:s'),

                ]);
            }

            // $update_contact_wise = DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)->where('academic_year_id', $request->academic_year_id)->get();
            // foreach ($update_contact_wise as $update_contact_wise_value) {
            //     $contact_wise = DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)->where('enum_month_id', $update_contact_wise_value->enum_month_id)->where('product_id', $update_contact_wise_value->product_id)->where('academic_year_id', $request->academic_year_id)->first();

            //     $this->contactwise_item_update($contact_wise, $id, $request->academic_year_id, $request->class_id, $update_contact_wise_value->enum_month_id, $update_contact_wise_value->product_id, $update_contact_wise_value->actual_amount, $update_contact_wise_value->amount);
            // }
            // if (!empty($request->product_id)) {
            //     $products = $request->product_id;
            //     $amount = $request->amount;
            //     $discount = $request->discount;
            //     $payable = $request->payable;
            //     $note = $request->note;
            //     $discountArr = [];
            //     foreach ($products as $key => $product) {
            //         $exit= DB::table('contactwise_item_discount_price_list')
            //             ->where('contact_id', $id)
            //             ->where('academic_year_id', $request->academic_year_id)
            //             ->where('product_id', $product)
            //             ->first();
            //         if($exit) {
            //             DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)
            //                 ->where('academic_year_id', $request->academic_year_id)
            //                 ->where('product_id', $product)
            //                 ->update([
            //                     'academic_year_id' => $request->academic_year_id,
            //                     'class_id' => $request->class_id,
            //                     'product_id' => $product[0],
            //                     'actual_amount' => $amount[$key][0],
            //                     'amount' => $payable[$key][0],
            //                     'discount_amount' => $discount[$key][0],
            //                     'notes' => $note[$key][0],
            //                     'updated_by' => Auth::user()->id,
            //                     'updated_at' => date('Y-m-d H:i:s'),
            //                 ]);
            //         $dis_id=$exit->id;
            //         } else {
            //             if ($payable[$key][0] > 0) {
            //                 $dis_id=DB::table('contactwise_item_discount_price_list')->insertGetId([
            //                     'academic_year_id' => $request->academic_year_id,
            //                     'class_id' => $request->class_id,
            //                     'contact_id' => $id,
            //                     'product_id' => $product[0],
            //                     'actual_amount' => $amount[$key][0],
            //                     'amount' => $payable[$key][0],
            //                     'discount_amount' => $discount[$key][0],
            //                     'notes' => $note[$key][0],
            //                     'created_by' => Auth::user()->id,
            //                     'created_at' => date('Y-m-d H:i:s'),
            //                 ]);
            //             }
            //         }

            //                 $payment_items= DB::table('contact_payable_items')->where('contact_id', $id)->where('product_id', $product[0])->where('month_id', 1)->where('academic_year_id', $request->academic_year_id)->first();
            //                 if($payment_items){

            //                     $contact_payable_items_update = DB::table('contact_payable_items')->where('contact_id', $id)->where('product_id', $product[0])->where('month_id', 1)->where('academic_year_id', $request->academic_year_id)->update([
            //                             'class_id' => $request->class_id,
            //                             'amount' => (float) $payable[$key][0],
            //                             'due' => (float) ($payable[$key][0]-$payment_items->paid_amount),
            //                             'is_paid' => (float) ($payable[$key][0]-$payment_items->paid_amount)==0? 1:0,
            //                             'updated_by' => Auth::user()->id,
            //                             'updated_at' => date('Y-m-d H:i:s'),
            //                             'contact_discount_id'=>$dis_id
            //                         ]);

            //                 }else{
            //                     if ($payable[$key][0] > 0) {
            //                     $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
            //                             'contact_id' => $id,
            //                             'product_id' => $product[0],
            //                             'class_id' => $request->class_id,
            //                             'month_id' => 1,
            //                             'academic_year_id' => $request->academic_year_id,
            //                             'amount' => (float) $payable[$key][0],
            //                             'paid_amount' => 0,
            //                             'due' => (float) $payable[$key][0],
            //                             'is_paid' => 0,
            //                             'created_by' => Auth::user()->id,
            //                             'created_at' => date('Y-m-d H:i:s'),
            //                             'date' => date('Y-m-d'),
            //                             'contact_discount_id'=>$dis_id
            //                         ]);
            //                 }

            //         }
            //     }

            // }

            DB::commit();
            Session::flash('success', __('Student::label.UPDATE_SUCCESSFULL_MSG'));
            return redirect()->route('students.payment.setup', ['id' => $id]);
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back()->withInput($request->all());
        }
    }

    // Student Show
    public function profile($id)
    {
        $student = DB::table('contacts as student')
            ->where('student.id', $id)
            ->join('addresses as student_present_address', 'student.id', 'student_present_address.contact_id')
            ->where('student_present_address.contact_type', 1)->where('student_present_address.is_present', '1')
            ->join('addresses as student_permanent_address', 'student.id', 'student_permanent_address.contact_id')
            ->where('student_permanent_address.contact_type', 1)->where('student_permanent_address.is_permanent', '1')
            ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
            ->join('contacts as father', 'father_relation.target_contact', 'father.id')
            ->where('father.type', 2)
            ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
            ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
            ->where('mother.type', 3)
            ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)
            ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('student_type', 'contact_academics.student_type_id', 'student_type.id')
            ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
            ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id')
            ->leftjoin('religions', 'student.religion_id', 'religions.id')
            ->leftjoin('divisions as present_devision', 'student_present_address.division', 'present_devision.id')
            ->leftjoin('districts as present_district', 'student_present_address.district', 'present_district.id')
            ->leftjoin('upazilas as present_upazila', 'student_present_address.upazila', 'present_upazila.id')
            ->leftjoin('divisions as permanent_devision', 'student_permanent_address.division', 'permanent_devision.id')
            ->leftjoin('districts as permanent_district', 'student_permanent_address.district', 'permanent_district.id')
            ->leftjoin('upazilas as permanent_upazila', 'student_permanent_address.upazila', 'permanent_upazila.id')
            ->where('contact_academics.status', 'active')
            ->select('student.contact_id as student_id', 'student.full_name as full_name', 'student.first_name as first_name', 'student.last_name', 'student.cp_phone_no as student_phone', 'student.cp_email as student_email', 'student.status', 'student.date_of_birth', 'student.birth_certificate', 'student.nationality', 'student.blood_group', 'student.gender', 'religions.name as religion', 'student.photo as student_photo', 'classes.name as class_name', 'contact_academics.class_roll', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'student_type.name as student_type_name', 'transports.name as transport_name', 'groups.name as group_name', 'contact_academics.registration_no', 'contact_academics.admission_no', 'contact_academics.previous_school as preschool', 'contact_academics.admission_date', 'academic_years.year as academic_year_name', 'father.id as father_id', 'father.full_name as father_name', 'father.education_qualification as father_education', 'father.company_name as father_company', 'father.income as father_income', 'father.cp_phone_no as father_phone', 'father.cp_email as father_email', 'father.occupation as father_occupation', 'father.nid as father_nid', 'mother.id as mother_id', 'mother.full_name as mother_name', 'mother.education_qualification as mother_education', 'mother.company_name as mother_company', 'mother.income as mother_income', 'mother.cp_phone_no as mother_phone', 'mother.cp_email as mother_email', 'mother.occupation as mother_occupation', 'mother.nid as mother_nid', 'guardian.id as guardian_id', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'guardian.guardian_relation as guardian_relation', 'student_present_address.area as present_address', 'present_devision.name as present_division', 'present_district.name as present_district', 'present_upazila.name as present_upazila', 'student_permanent_address.area as permanent_address', 'permanent_devision.name as permanent_devision', 'permanent_district.name as permanent_district', 'permanent_upazila.name as permanent_upazila', 'guardian.contact_id as guardian_sid')
            ->first();
        $guardian_address = DB::table('addresses as guardian_area')->where('contact_id', $student->guardian_id)->select('guardian_area.area as guardian_address')->first();
        $payments = DB::table('sales')->where('customer_id', $id)->where('status', 'active')->paginate(10);
        return view('Student::student.profile', compact('student', 'guardian_address', 'payments', 'id'));
    }
    public function paymentHistory($id)
    {
        $data = DB::table('sales')
            ->where('sales.id', $id)
            ->join('contacts', 'contacts.id', 'sales.customer_id')
            ->join('contact_academics', 'contact_academics.contact_id', 'sales.customer_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->where('contact_academics.status', 'active')
            ->select('sales.*', 'contacts.full_name as full_name', 'contact_academics.class_roll as class_roll', 'classes.name as class_name', 'sections.name as section_name')
            ->first();
        $service = DB::table('sales_product_relation')
            ->where('sales_product_relation.sales_id', $id)
            ->leftjoin('products', 'sales_product_relation.product_id', 'products.id')
            ->leftjoin('enum_month', 'sales_product_relation.month_id', 'enum_month.id')
            ->leftjoin('academic_years', 'sales_product_relation.academic_year_id', 'academic_years.id')
            ->select('products.name as product_name', 'sales_product_relation.price as price', 'sales_product_relation.note as note', 'enum_month.name as month_name', 'academic_years.year as year')
            ->get();
        return view('Student::student.paymentView', compact('data', 'service'));
    }
    
    // get section dependent on class
    public function getSections(Request $request)
    {
        $data = DB::table('section_relations')
            ->join('sections', 'sections.id', 'section_relations.section_id')
            ->where('section_relations.class_id', $request->classId)
            ->where('section_relations.academic_year_id', $request->yearId)
            ->where('sections.is_trash', '0')->get();
        return response()->json($data);
    }

    // get student dependent on section
    public function getStudents(Request $request)
    {
        $data = DB::table('contacts')
            ->join('contact_academics', 'contacts.id', 'contact_academics.contact_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->where('contact_academics.section_id', $request->sectionId)
            ->where('contacts.is_trash', '0')
            ->select('contacts.id', 'contacts.contact_id', 'contacts.full_name', 'classes.name', 'sections.name', 'contact_academics.class_roll')
            ->get();
        return response()->json($data);
    }

    // Get Present District depends on Present Division
    public function getPresentDistrict($id)
    {
        $data = District::where('division_id', $id)->get();
        return response()->json($data);
    }

    // Get Present Upazila depends on Present District
    public function getPresentUpazila($id)
    {
        $data = Upazila::where('district_id', $id)->get();
        return response()->json($data);
    }

    // Get Permanent District depends on Permanent Division
    public function getPermanentDistrict($id)
    {
        $data = District::where('division_id', $id)->get();
        return response()->json($data);
    }

    // Get Present Upazila depends on Present District
    public function getPermanentUpazila($id)
    {
        $data = Upazila::where('district_id', $id)->get();
        return response()->json($data);
    }

    // To destroy Class
    public function destroy($id)
    {
        // Check Student use any place
        $studentCheck = count(DB::table('contact_payable_items')->where('contact_id', $id)->get());

        if ($studentCheck < 1) {
            $studentContact = DB::table('contacts')->where('id', $id)->update([
                'is_trash' => 1,
            ]);
            Session::flash('success', "Student Successfully Removed into Trash ");
            return redirect()->back();
        } else {
            Session::flash('error', "This Student is running in system. You can't delete this. Plase contact with support team");
            return redirect()->back();
        }
    }

    // Trash Student
    public function trash(Request $request)
    {
        $datam = DB::table('contacts as student')
            ->where('student.is_trash', 1)
            ->join('contact_hierarchy as father_relation', 'student.id', 'father_relation.source_contactid')
            ->join('contacts as father', 'father_relation.target_contact', 'father.id')
            ->where('father.type', 2)
            ->join('contact_hierarchy as mother_relation', 'student.id', 'mother_relation.source_contactid')
            ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
            ->where('mother.type', 3)
            ->join('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
            ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
            ->where('guardian.type', 4)
            ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
            ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
            ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
            ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
            ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
            ->leftjoin('student_type', 'contact_academics.student_type_id', 'student_type.id')
            ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
            ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
            ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id');

        if ($request->academicYearId) {
            $datam->where('contact_academics.academic_year_id', $request->academicYearId);
        }
        if ($request->classId) {
            $datam->where('contact_academics.class_id', $request->classId);
        }
        if ($request->sectionId) {
            $datam->where('contact_academics.section_id', $request->sectionId);
        }
        if ($request->shiftId) {
            $datam->where('contact_academics.shift_id', $request->shiftId);
        }
        if ($request->versionId) {
            $datam->where('contact_academics.version_id', $request->versionId);
        }
        if ($request->status) {
            $datam->where('student.status', $request->status);
        }
        if ($request->groupId) {
            $datam->where('contact_academics.group_id', $request->groupId);
        }
        $data = $datam->select('student.id', 'student.full_name as student_name', 'student.cp_phone_no as student_phone', 'student.cp_email as student_email', 'student.status', 'student.nationality', 'student.blood_group', 'student.gender', 'student.religion_id', 'classes.name as class_name', 'contact_academics.class_roll', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'transports.name as transport_name', 'groups.name as group_name', 'contact_academics.registration_no', 'contact_academics.previous_school', 'contact_academics.admission_date', 'academic_years.year as academic_year', 'father.full_name as father_name', 'mother.full_name as mother_name', 'guardian.cp_phone_no as guardian_number', 'student.contact_id')
            ->orderBy('contact_academics.class_id', 'ASC')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('student.profile', [$row->id]) . '" class="btn btn-outline-primary btn-xs" data-id= "" title="Profile" data-toggle="tooltip"><i class="fas fa-user"></i></a>';
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
        $academic_year = ['0' => 'All'] + DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $classList = ['0' => 'All'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $shiftList = ['0' => 'All'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $versionList = ['0' => 'All'] + DB::table('versions')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $groupList = ['0' => 'All'] + DB::table('groups')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        return view('Student::student.trash', compact('academic_year', 'classList', 'shiftList', 'versionList', 'groupList'));
    }

    // to restore class
    public function student_restore($id)
    {
        DB::table('contacts')->where('id', $id)->update(['is_trash' => 0]);
        Session::flash('success', "Student Restored Successfully ");
        return redirect()->route('student.index');
    }

    public function studentImport()
    {
        $student = DB::table('2023_n')->get();
        foreach ($student as $value) {
            DB::beginTransaction();
            try {
                $exit = DB::table('contacts')->where('contact_id', $value->sid)->first();
                if (!$exit) {
                    $studentInsert = DB::table('contacts')->insertGetId([
                        'full_name' => $value->name,
                        'first_name' => $value->name,
                        'type' => 1,
                        'contact_id' => $value->sid,
                        'cp_phone_no' => $value->gcell,
                        'date_of_birth' => $value->dob,
                        'nationality' => $value->national,
                        'blood_group' => $value->blood,
                        'admission_date' => $value->admi,
                        'birth_certificate' => $value->birth,
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => $value->gender,
                        'is_trash' => 0,

                    ]);
                    $father = DB::table('contacts')->insertGetId([
                        'full_name' => '',
                        'type' => 2,
                        'cp_phone_no' => '',
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 1,
                        'is_trash' => 0,

                    ]);
                    $mother = DB::table('contacts')->insertGetId([
                        'full_name' => '',
                        'type' => 3,
                        'cp_phone_no' => '',
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 2,
                        'is_trash' => 0,

                    ]);
                    $guardian = DB::table('contacts')->insertGetId([
                        'full_name' => $value->gname,
                        'type' => 4,
                        'cp_phone_no' => $value->gcell,
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 2,
                        'is_trash' => 0,

                    ]);
                    $address = DB::table('addresses')->insertGetId([
                        'address_cat_id' => 1,
                        'contact_id' => $studentInsert,
                        'contact_type' => 1,
                        'status' => 'active',
                        'is_present' => 1,
                        'is_permanent' => 1,
                    ]);
                    $address2 = DB::table('addresses')->insertGetId([
                        'address_cat_id' => 1,
                        'contact_id' => $guardian,
                        'contact_type' => 1,
                        'status' => 'active',
                        'is_present' => 1,
                        'is_permanent' => 1,
                    ]);
                    // if(!empty($value->mother_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => '',
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>2,
                    //         'is_trash'=>0

                    //     ]);
                    // }

                    // if(!empty($value->father_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => $value->mother_name,
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>1,
                    //         'is_trash'=>0

                    //     ]);
                    // }

                    // if(empty($value->mother_name) && empty($value->father_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => $value->mother_name,
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>2,
                    //         'is_trash'=>0

                    //     ]);

                    // }

                    $contact_haira_father = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $father,
                        'relationship_type_nodeid' => 1,

                    ]);
                    $contact_haira_mother = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $mother,
                        'relationship_type_nodeid' => 2,

                    ]);
                    $contact_haira_gurd = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $guardian,
                        'relationship_type_nodeid' => 3,

                    ]);

                    $contact_academics = DB::table('contact_academics')->insert([
                        'contact_id' => $studentInsert,
                        'class_id' => $value->class,
                        'version_id' => $value->version,
                        'section_id' => $value->section,
                        'shift_id' => $value->shift,
                        'group_id' => $value->group,
                        'admission_date' => $value->admi,
                        'class_roll' => empty($value->roll) ? null : $value->roll,
                        'section_id' => empty($value->section) ? null : $value->section,
                        'academic_year_id' => $value->year,
                        'admission_type' => 1,
                        'status' => 'active',
                        'is_trash' => 0,

                    ]);

                } else {
                    // dd($value->year);
                    $update = DB::table('contact_academics')->where('contact_id', $exit->id)->where('academic_year_id', '!=', $value->year)->update([
                        'status' => 'inactive',
                    ]);
                    $contact_academics = DB::table('contact_academics')->insert([
                        'contact_id' => $exit->id,
                        'class_id' => $value->class,
                        'version_id' => $value->version,
                        'section_id' => $value->section,
                        'shift_id' => $value->shift,
                        'group_id' => $value->group,
                        'admission_date' => $value->admi,
                        'class_roll' => empty($value->roll) ? null : $value->roll,
                        'section_id' => empty($value->section) ? null : $value->section,
                        'academic_year_id' => $value->year,
                        'admission_type' => 2,
                        'status' => 'active',
                        'is_trash' => 0,

                    ]);

                }
                DB::commit();
                // return redirect()->back();
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                return redirect()->back()->withInput($request->all());
            }

        }
    }
    public function studentInfo()
    {
        $student = DB::table('student_info')->leftjoin('contacts', 'student_info.sid', 'contacts.contact_id')->get();
        foreach ($student as $value) {
            $father = DB::table('contacts')->where('contacts.type', 2)->join('contact_hierarchy', 'contacts.id', 'contact_hierarchy.target_contact')->where('source_contactid', $value->id)->update([
                'full_name' => $value->father,
                'cp_phone_no' => $value->f_mobile,
            ]);
            $mother = DB::table('contacts')->where('contacts.type', 3)->join('contact_hierarchy', 'contacts.id', 'contact_hierarchy.target_contact')->where('source_contactid', $value->id)->update([
                'full_name' => $value->mother,
                'cp_phone_no' => $value->m_mobile,
            ]);
            $guardian = DB::table('contacts')->where('contacts.type', 4)->join('contact_hierarchy', 'contacts.id', 'contact_hierarchy.target_contact')->where('source_contactid', $value->id)->update([
                'full_name' => $value->guardian,
                'cp_phone_no' => $value->g_mobile,
                'guardian_relation' => $value->g_relation,
            ]);
        }
    }

    public function studentImportwith_due()
    {

        $student = DB::table('payment_due')->where('flag', 1)->get();
        // dd($student);
        foreach ($student as $value) {
            DB::beginTransaction();
            try {
                $exit = DB::table('contacts')->where('id', $value->contact_id)->first();
                if (!$exit) {
                    $studentInsert = DB::table('contacts')->insertGetId([
                        'full_name' => $value->name,
                        'first_name' => $value->name,
                        'type' => 1,
                        'contact_id' => $value->sid,
                        'cp_phone_no' => $value->g_cell,
                        'date_of_birth' => $value->dob,
                        'nationality' => "BANGLADESHI",
                        'admission_date' => (string) $value->admi_date,
                        'birth_certificate' => $value->dob,
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => $value->gender,
                        'is_trash' => 0,

                    ]);
                    $father = DB::table('contacts')->insertGetId([
                        'full_name' => $value->f_name,
                        'type' => 2,
                        'cp_phone_no' => '',
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 1,
                        'is_trash' => 0,

                    ]);
                    $mother = DB::table('contacts')->insertGetId([
                        'full_name' => $value->m_name,
                        'type' => 3,
                        'cp_phone_no' => '',
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 2,
                        'is_trash' => 0,

                    ]);
                    $guardian = DB::table('contacts')->insertGetId([
                        'full_name' => (!empty($value->m_name)) ? $value->m_name : $value->f_name,
                        'type' => 4,
                        'cp_phone_no' => $value->g_cell,
                        'status' => 'active',
                        'religion_id' => 1,
                        'gender' => 2,
                        'is_trash' => 0,

                    ]);
                    $address = DB::table('addresses')->insertGetId([
                        'address_cat_id' => 1,
                        'contact_id' => $studentInsert,
                        'contact_type' => 1,
                        'status' => 'active',
                        'is_present' => 1,
                        'is_permanent' => 1,
                    ]);
                    $address2 = DB::table('addresses')->insertGetId([
                        'address_cat_id' => 1,
                        'contact_id' => $guardian,
                        'contact_type' => 1,
                        'status' => 'active',
                        'is_present' => 1,
                        'is_permanent' => 1,
                    ]);
                    // if(!empty($value->mother_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => '',
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>2,
                    //         'is_trash'=>0

                    //     ]);
                    // }

                    // if(!empty($value->father_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => $value->mother_name,
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>1,
                    //         'is_trash'=>0

                    //     ]);
                    // }

                    // if(empty($value->mother_name) && empty($value->father_name)){
                    //     $guardian = DB::table('contacts')->insertGetId([
                    //         'full_name' => $value->mother_name,
                    //         'type' =>4,
                    //         'cp_phone_no' =>$value->phone_m,
                    //         'status' =>'active',
                    //         'religion_id'=>1,
                    //         'gender'=>2,
                    //         'is_trash'=>0

                    //     ]);

                    // }

                    $contact_haira_father = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $father,
                        'relationship_type_nodeid' => 1,

                    ]);
                    $contact_haira_mother = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $mother,
                        'relationship_type_nodeid' => 2,

                    ]);
                    $contact_haira_gurd = DB::table('contact_hierarchy')->insert([
                        'status' => 'active',
                        'source_contactid' => $studentInsert,
                        'target_contact' => $guardian,
                        'relationship_type_nodeid' => 3,

                    ]);

                    $contact_academics = DB::table('contact_academics')->insert([
                        'contact_id' => $studentInsert,
                        'class_id' => $value->class,
                        'shift_id' => $value->shift,
                        'admission_date' => $value->admi_date,
                        'academic_year_id' => 6,
                        'admission_type' => 1,
                        'status' => 'active',
                        'is_trash' => 0,

                    ]);

                    $inv = 'INV-' . date('Y-m-d') . '-' . $studentInsert;
                    $total = ((int) $value->p4 + (int) $value->p10 + (int) $value->p21 + (int) $value->p22 + (int) $value->p23 + (int) $value->p26 + (int) $value->p9);
                    //sales entry
                    $payment_history = DB::table('payment_history')->insertGetId([
                        'payment_invoice' => $inv,
                        'payment_date' => '25-12-2022',
                        'customer_id' => $studentInsert,
                        'payment_amount' => $total,
                        'flag' => 'cash',
                        'source' => 'payment_receive',
                        'status' => 'active',
                        'AccountTypeId' => 1,
                        'AccountCategoryId' => 1,
                        // 'AccountId' => $input['payment_account'],
                    ]);
                    $cashbank_insert = DB::table('cash_banks')->insertGetId([
                        'invoice_date' => '25-12-2022',
                        'invoice_no' => $inv,
                        'payment_type' => 'cash',
                        'amount' => $total,
                        'customer_id' => $studentInsert,
                        'source_flag' => 'payment_receive',
                        'status' => 'active',
                    ]);
                    $sales = DB::table('sales')->insertGetId([
                        'sales_type' => 'partial',
                        'sales_invoice_date' => '25-12-2022',
                        'customer_id' => $studentInsert,
                        'sales_invoice_no' => $inv,
                        'status' => 'active',
                        'grand_total' => $total,
                        'delivery_type' => 'regular',
                        'subtotal' => $total,
                        'paid_amount' => $total,
                        'total_due' => 0,
                    ]);

                    $sales_payment = DB::table('sales_payment')->insertGetId([
                        'sales_id' => $sales,
                        'sales_payment_date' => '25-12-2022',
                        'absolute_amount' => $total,
                        'grand_total' => $total,
                        'down_payment' => $total,
                        'due_payment' => 0,
                        'write_of' => 0,
                        'status' => 'active',
                        'payment_relation_id' => $payment_history,
                    ]);
                    //for discount amount and due entry
                    for ($i = 1; $i <= 7; $i++) {
                        if ($i == 1) {
                            $pid = 9;
                            $ammount = $value->d9;
                            $paid = $value->p9;
                        } elseif ($i == 2) {
                            if ($value->p4 > 0) {
                                $pid = 4;
                                $ammount = $value->d4_10;
                                $paid = $value->p4;
                            } else {
                                $pid = 10;
                                $ammount = $value->d4_10;
                                $paid = $value->p10;
                            }

                        } elseif ($i == 3) {
                            $pid = 21;
                            $ammount = $value->d21;
                            $paid = $value->p21;
                        } elseif ($i == 4) {
                            $pid = 22;
                            $ammount = $value->d22;
                            $paid = $value->p22;
                        } elseif ($i == 5) {
                            $pid = 23;
                            $ammount = $value->d23;
                            $paid = $value->p23;

                        } elseif ($i == 6) {
                            $pid = 26;
                            $ammount = $value->d26;
                            $paid = $value->p26;
                        } elseif ($i == 7) {
                            $pid = 19;
                            $ammount = $value->d19;
                        }

                        //discount entry
                        $dis = DB::table('contactwise_item_discount_price_list')->insertGetId([
                            'academic_year_id' => 6,
                            'class_id' => $value->class,
                            'contact_id' => $studentInsert,
                            'product_id' => $pid,
                            'amount' => $ammount,
                        ]);
                        //due item entry
                        if ($pid != 19) {
                            $due = DB::table('contact_payable_items')->insertGetId([
                                'academic_year_id' => 6,
                                'month_id' => 1,
                                'class_id' => $value->class,
                                'contact_id' => $studentInsert,
                                'product_id' => $pid,
                                'amount' => $ammount,
                                'paid_amount' => ($paid > 0) ? $paid : 0,
                                'due' => ((int) $ammount - (int) (($paid > 0) ? $paid : 0)),
                                'is_paid' => (((int) $ammount - (int) (($paid > 0) ? $paid : 0)) == 0) ? 1 : 0,
                                'contact_discount_id' => $dis,
                            ]);
                            if ($paid > 0) {
                                $sales_product_relation = DB::table('sales_product_relation')->insertGetId([

                                    'sales_id' => $sales,
                                    'customer_category_id' => 1,
                                    'sales_group_id' => 1,
                                    'product_id' => $pid,
                                    'quantity' => 1,
                                    'price' => $paid,
                                    'subtotal' => $paid,
                                    'status' => 'active',
                                    'actual_price' => $paid,
                                    'remain_due' => ((int) $ammount - (int) (($paid > 0) ? $paid : 0)),
                                    'month_id' => 1,
                                    'academic_year_id' => 6,

                                ]);
                            }

                        }

                    }

                }
                // else {
                //     // dd($value->year);
                //     $check = DB::table('contact_academics')->where('contact_id',$value->contact_id)->where('academic_year_id',6)->where('class_id',$value->class_id)->first();
                //     if(!$check){
                //         $update = DB::table('contact_academics')->where('contact_id', $value->contact_id)->where('academic_year_id', '!=', 6)->update([
                //             'status' => 'inactive',
                //         ]);
                //         $contact_academics = DB::table('contact_academics')->insert([
                //             'contact_id' => $value->contact_id,
                //             'class_id' => $value->class,
                //             'shift_id' => $value->shift,
                //             'admission_date' => $value->admi_date,
                //             'academic_year_id' => 6,
                //             'admission_type' => 2,
                //             'status' => 'active',
                //             'is_trash' => 0,

                //         ]);
                //     }

                // }
                DB::commit();
                // return redirect()->back();
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                dd($e->getMessage());
                return redirect()->back()->withInput($request->all());
            }

        }
    }

    public function studentImportwith_due_two()
    {

        $student = DB::table('payment_due')->where('flag', 2)->get();
        // dd($student);
        foreach ($student as $value) {
            DB::beginTransaction();
            try {
                $exit = DB::table('contacts')->where('id', $value->contact_id)->first();
                if ($exit) {

                    $check = DB::table('contact_academics')->where('contact_id', $value->contact_id)->where('academic_year_id', 6)->where('class_id', $value->class)->where('status', 'active')->first();
                    if (!$check) {
                        $update = DB::table('contact_academics')->where('contact_id', $value->contact_id)->where('academic_year_id', '!=', 6)->update([
                            'status' => 'inactive',
                        ]);
                        $contact_academics = DB::table('contact_academics')->insert([
                            'contact_id' => $value->contact_id,
                            'class_id' => $value->class,
                            'shift_id' => $value->shift,
                            'admission_date' => $value->admi_date,
                            'academic_year_id' => 6,
                            'admission_type' => 2,
                            'status' => 'active',
                            'is_trash' => 0,

                        ]);
                    }

                    $inv = 'INV-' . date('Y-m-d') . '-' . $value->contact_id;
                    $total = ((int) $value->p4 + (int) $value->p10 + (int) $value->p21 + (int) $value->p22 + (int) $value->p23 + (int) $value->p26 + (int) $value->p9);
                    //sales entry
                    $payment_history = DB::table('payment_history')->insertGetId([
                        'payment_invoice' => $inv,
                        'payment_date' => '25-12-2022',
                        'customer_id' => $value->contact_id,
                        'payment_amount' => $total,
                        'flag' => 'cash',
                        'source' => 'payment_receive',
                        'status' => 'active',
                        'AccountTypeId' => 1,
                        'AccountCategoryId' => 1,
                        // 'AccountId' => $input['payment_account'],
                    ]);
                    $cashbank_insert = DB::table('cash_banks')->insertGetId([
                        'invoice_date' => '25-12-2022',
                        'invoice_no' => $inv,
                        'payment_type' => 'cash',
                        'amount' => $total,
                        'customer_id' => $value->contact_id,
                        'source_flag' => 'payment_receive',
                        'status' => 'active',
                    ]);
                    $sales = DB::table('sales')->insertGetId([
                        'sales_type' => 'partial',
                        'sales_invoice_date' => '25-12-2022',
                        'customer_id' => $value->contact_id,
                        'sales_invoice_no' => $inv,
                        'status' => 'active',
                        'grand_total' => $total,
                        'delivery_type' => 'regular',
                        'subtotal' => $total,
                        'paid_amount' => $total,
                        'total_due' => 0,
                    ]);

                    $sales_payment = DB::table('sales_payment')->insertGetId([
                        'sales_id' => $sales,
                        'sales_payment_date' => '25-12-2022',
                        'absolute_amount' => $total,
                        'grand_total' => $total,
                        'down_payment' => $total,
                        'due_payment' => 0,
                        'write_of' => 0,
                        'status' => 'active',
                        'payment_relation_id' => $payment_history,
                    ]);
                    //for discount amount and due entry
                    for ($i = 1; $i <= 7; $i++) {
                        if ($i == 1) {
                            $pid = 9;
                            $ammount = $value->d9;
                            $paid = $value->p9;
                        } elseif ($i == 2) {
                            if ($value->p4 > 0) {
                                $pid = 4;
                                $ammount = $value->d4_10;
                                $paid = $value->p4;
                            } else {
                                $pid = 10;
                                $ammount = $value->d4_10;
                                $paid = $value->p10;
                            }

                        } elseif ($i == 3) {
                            $pid = 21;
                            $ammount = $value->d21;
                            $paid = $value->p21;
                        } elseif ($i == 4) {
                            $pid = 22;
                            $ammount = $value->d22;
                            $paid = $value->p22;
                        } elseif ($i == 5) {
                            $pid = 23;
                            $ammount = $value->d23;
                            $paid = $value->p23;

                        } elseif ($i == 6) {
                            $pid = 26;
                            $ammount = $value->d26;
                            $paid = $value->p26;
                        } elseif ($i == 7) {
                            $pid = 19;
                            $ammount = $value->d19;
                        }

                        //discount entry
                        $dis = DB::table('contactwise_item_discount_price_list')->insertGetId([
                            'academic_year_id' => 6,
                            'class_id' => $value->class,
                            'contact_id' => $value->contact_id,
                            'product_id' => $pid,
                            'amount' => $ammount,
                        ]);
                        //due item entry
                        if ($pid != 19) {
                            $due = DB::table('contact_payable_items')->insertGetId([
                                'academic_year_id' => 6,
                                'month_id' => 1,
                                'class_id' => $value->class,
                                'contact_id' => $value->contact_id,
                                'product_id' => $pid,
                                'amount' => $ammount,
                                'paid_amount' => ($paid > 0) ? $paid : 0,
                                'due' => ((int) $ammount - (int) (($paid > 0) ? $paid : 0)),
                                'is_paid' => (((int) $ammount - (int) (($paid > 0) ? $paid : 0)) == 0) ? 1 : 0,
                                'contact_discount_id' => $dis,
                            ]);
                            if ($paid > 0) {
                                $sales_product_relation = DB::table('sales_product_relation')->insertGetId([

                                    'sales_id' => $sales,
                                    'customer_category_id' => 1,
                                    'sales_group_id' => 1,
                                    'product_id' => $pid,
                                    'quantity' => 1,
                                    'price' => $paid,
                                    'subtotal' => $paid,
                                    'status' => 'active',
                                    'actual_price' => $paid,
                                    'remain_due' => ((int) $ammount - (int) (($paid > 0) ? $paid : 0)),
                                    'month_id' => 1,
                                    'academic_year_id' => 6,

                                ]);
                            }

                        }

                    }

                }
                // else {
                //     // dd($value->year);
                //     $check = DB::table('contact_academics')->where('contact_id',$value->contact_id)->where('academic_year_id',6)->where('class_id',$value->class_id)->first();
                //     if(!$check){
                //         $update = DB::table('contact_academics')->where('contact_id', $value->contact_id)->where('academic_year_id', '!=', 6)->update([
                //             'status' => 'inactive',
                //         ]);
                //         $contact_academics = DB::table('contact_academics')->insert([
                //             'contact_id' => $value->contact_id,
                //             'class_id' => $value->class,
                //             'shift_id' => $value->shift,
                //             'admission_date' => $value->admi_date,
                //             'academic_year_id' => 6,
                //             'admission_type' => 2,
                //             'status' => 'active',
                //             'is_trash' => 0,

                //         ]);
                //     }

                // }
                DB::commit();
                // return redirect()->back();
            } catch (\Exception $e) {
                //If there are any exceptions, rollback the transaction`
                DB::rollback();
                Session::flash('danger', $e->getMessage());
                dd($e->getMessage());
                return redirect()->back()->withInput($request->all());
            }

        }
    }
    public function paymentSetup($id)
    {
        $contact = DB::table('contacts')
            ->join('contact_academics', 'contact_academics.contact_id', 'contacts.id')
            ->join('classes', 'classes.id', 'contact_academics.class_id')
            ->leftjoin('shifts', 'shifts.id', 'contact_academics.shift_id')
            ->leftjoin('student_type', 'student_type.id', 'contact_academics.student_type_id')
            ->leftjoin('academic_years', 'academic_years.id', 'contact_academics.academic_year_id')
            ->leftjoin('sections', 'sections.id', 'contact_academics.section_id')
            ->leftjoin('transports', 'transports.id', 'contact_academics.transport_id')
            ->where('contacts.id', $id)
            ->where('contact_academics.status', 'active')
            ->select('academic_years.year as academic_year', 'contacts.full_name', 'classes.name as classname', 'shifts.name as shiftname', 'sections.name as sectionname', 'transports.name as transportsname', 'contact_academics.class_roll', 'contacts.cp_phone_no')->first();

        $academicYearList = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $month_List = ['0' => 'Select Month'] + DB::table('enum_month')->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $productlist = DB::table('products')->where('status', 'active')->get();
        $enumMonth = DB::table('enum_month')->get();
        $academicYear = DB::table('academic_years')->where('is_trash', 0)->latest('id')->get();
        $class_id = DB::table('contact_academics')->where('is_trash', '0')->where('status', 'active')->where('contact_id', $id)->value('class_id');
        return view('Student::student.paymentSetup', compact('academicYearList', 'month_List', 'id', 'productlist', 'academicYear', 'enumMonth', 'class_id', 'contact'));
    }
    public function getPaymentSetup(Request $request, $id)
    {
        $data = DB::table('contactwise_item_discount_price_list')
            ->leftjoin('products', 'contactwise_item_discount_price_list.product_id', 'products.id')
            ->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)
            ->where('enum_month_id', $request->getMonthId)->select('contactwise_item_discount_price_list.*', 'products.name as item_name')->get();

        $response['data'] = '';
        $response['count'] = '';
        foreach ($data as $key => $value) {
            $paid = DB::table('contact_payable_items')->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)->where('month_id', $request->getMonthId)->where('product_id', $value->product_id)->value('paid_amount');
            $count = DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)->where('product_id', $value->product_id)->where('enum_month_id', '>', $request->getMonthId)->count();
            if ($request->getMonthId != 12) {
                $checking_for_next_month_payment = DB::table('contact_payable_items')->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)->where('month_id', $request->getMonthId + 1)->where('product_id', $value->product_id)->value('paid_amount');
                if ($checking_for_next_month_payment > 0) {
                    $validated = 1;
                } else {
                    $validated = 0;
                }
            } else {
                $validated = 1;
            }
            $response['data'] .= "<tr>";
            $response['data'] .= "<td class='text-center'>" . ($key + 1) . "</td>";
            if (DB::table('contactwise_item_discount_price_list')->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)->where('enum_month_id', $request->getMonthId)->exists()) {
                if (DB::table('contact_payable_items')->where('contact_id', $id)->where('academic_year_id', $request->getAcademicId)->where('month_id', $request->getMonthId)->where('product_id', $value->product_id)->value('paid_amount') > 0) {
                    $response['data'] .= "<td class='text-center'> <input type='checkbox' checked onclick='return false;' class = 'all-check-box allCheck'id='checkItem_" . $value->id . "'  name='item_check[" . ($key + 1) . "][]' value='" . $value->id . "' keyValue='" . $value->id . "'  onclick='unCheck(this.id);isChecked()' ' /></td>";
                } else {
                    $response['data'] .= "<td class='text-center'> <input type='checkbox' checked ='' class = 'all-check-box allCheck'id='checkItem_" . $value->id . "'  name='item_check[" . ($key + 1) . "][]' value='" . $value->id . "' keyValue='" . $value->id . "'  onclick='unCheck(this.id);isChecked()' ' /></td>";
                }
            } else {
                $response['data'] .= "<td class='text-center'> <input type='checkbox' class = 'all-check-box allCheck'id='checkItem_" . $value->id . "'  name='item_check[" . ($key + 1) . "][]' value='" . $value->id . "' keyValue='" . $value->id . "'  onclick='unCheck(this.id);isChecked()' ' /></td>";
            }
            $response['data'] .= "<td class='text-center'><input type='hidden' name='product_id[" . ($key + 1) . "][]' value='" . $value->product_id . "' />" . ($value->item_name) . "</td>";

            $response['data'] .= "<td class='text-left'>";

            if ($validated == 0) {
                if ($count + $request->getMonthId != 12) {
                    $response['data'] .= "<input type='checkbox' class = 'all-check-box' value='1' name='whole_year[" . ($key + 1) . "][]' /> Whole Year </br>";
                } else {
                    $response['data'] .= "<input type='checkbox' class = 'all-check-box' value='1' name='next_month_affected[" . ($key + 1) . "][]'  /> Affected for next all months";
                }
            }

            $response['data'] .= "</td>";

            if ($value->actual_amount > 0) {
                $response['data'] .= "<td class='text-center'><input type='hidden'class = 'all-check-box' value='" . $value->actual_amount . "' name='actual_amount[" . ($key + 1) . "][]' />" . $value->actual_amount . "</td>";
            } else {
                $response['data'] .= "<td class='text-center'><input type='hidden'class = 'all-check-box' value='0' name='actual_amount[" . ($key + 1) . "][]' />" . 0 . "</td>";
            }

            $response['data'] .= "<td class='text-center'><input type='text' class='form-control' id='payable-amount-" . ($key + 1) . "' oninput='disableButton(" . ($key + 1) . ")' name='payable_amount[" . ($key + 1) . "][]' value = '" . $value->amount . "' /><input type='hidden' class='form-control' id='paid-amount-" . ($key + 1) . "' name='paid_amount[" . ($key + 1) . "][]' value = '" . $paid . "' /><div id='errorMsg" . ($key + 1) . "'></td>";

            $response['data'] .= "<td class='text-center'><input type='text' class='form-control' id='notes" . ($key + 1) . "' oninput='disableButton(" . ($key + 1) . ")' name='notes[" . ($key + 1) . "][]' value = '" . $value->notes . "' /><div id='notesErrorMsg" . ($key + 1) . "'></div></td>";
            $response['data'] .= "</tr>";
        }
        $response['count'] = count($data);
        return $response;
    }
    public function paymentSetupStore(Request $request)
    {

        if (!empty($request->product_id) && !empty($request->item_check)) {
            DB::beginTransaction();
            try {
                foreach ($request->item_check as $key => $item) {
                    // Whole Year
                    if (!empty($request->whole_year[$key][0])) {
                        //loop for upcoming months
                        for ($i = $request->enum_month_id; $i <= 12; $i++) {
                            $contact_wise = DB::table('contactwise_item_discount_price_list')->where('contact_id', $request->student_id)->where('enum_month_id', $i)->where('product_id', $request->product_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();
                            // If same data update or store
                            if ($contact_wise) {
                                $this->contactwise_item_update($contact_wise, $request->student_id, $request->academic_year_id, $request->class_id, $i, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                            } else {
                                $this->contactwise_item_insert($request->student_id, $request->academic_year_id, $request->class_id, $i, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                            }
                        }
                    } elseif (!empty($request->next_month_affected[$key][0])) {
                        //loop for upcoming months
                        for ($i = $request->enum_month_id; $i <= 12; $i++) {
                            $contact_wise_month = DB::table('contactwise_item_discount_price_list')->where('contact_id', $request->student_id)->where('enum_month_id', $i)->where('product_id', $request->product_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();
                            // If same data update or store
                            if ($contact_wise_month) {
                                $this->contactwise_item_update($contact_wise_month, $request->student_id, $request->academic_year_id, $request->class_id, $i, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                            }
                        }
                    } else {
                        // If no whole year selected
                        $contact_wise_month = DB::table('contactwise_item_discount_price_list')->where('contact_id', $request->student_id)->where('enum_month_id', $request->enum_month_id)->where('product_id', $request->product_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();
                        // If same data update or store
                        if ($contact_wise_month) {
                            $this->contactwise_item_update($contact_wise_month, $request->student_id, $request->academic_year_id, $request->class_id, $request->enum_month_id, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                        } else {
                            $this->contactwise_item_insert($request->student_id, $request->academic_year_id, $request->class_id, $request->enum_month_id, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                        }

                    }
                    //due generation
                    if ($request->generate_due == 1) {
                        $due_check = DB::table('contact_payable_items')->where('contact_id', $request->student_id)->where('month_id', $request->enum_month_id)->where('product_id', $request->product_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();

                        $discount_table_id = DB::table('contactwise_item_discount_price_list')->where('contact_id', $request->student_id)->where('enum_month_id', $request->enum_month_id)->where('product_id', $request->product_id[$key][0])->where('academic_year_id', $request->academic_year_id)->first();

                        if ($due_check) {

                            // $this->due_generate_update($due_check,$request->student_id, $request->academic_year_id, $request->class_id, $request->enum_month_id, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0]);
                        } else {
                            $this->due_generate_store($discount_table_id->id, $request->student_id, $request->academic_year_id, $request->class_id, $request->enum_month_id, $request->product_id[$key][0], $request->actual_amount[$key][0], $request->payable_amount[$key][0], $request->notes[$key][0]);
                        }

                    }
                }
                Session::flash('success', 'Payment Added');
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

        return redirect()->route('students.payment.setup', $request->student_id);

    }

    public function contactwise_item_update($data, $contact_id, $academic_year_id, $class_id, $month_id, $product_id, $actual_amount, $payable_ammount, $notes)
    {

        $due_check = DB::table('contact_payable_items')->where('contact_id', $contact_id)->where('month_id', $month_id)->where('product_id', $product_id)->where('academic_year_id', $academic_year_id)->first();
        // dd($payable_ammount);
        if (!$due_check || $due_check->paid_amount <= $payable_ammount) {
            $update = DB::table('contactwise_item_discount_price_list')->where('id', $data->id)->update([
                'class_id' => $class_id,
                'academic_year_id' => $academic_year_id,
                'actual_amount' => $actual_amount,
                'amount' => $payable_ammount,
                'notes' => $notes,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),

            ]);
            $version = DB::table('contactwise_item_discount_price_list_version')->insert([
                'academic_year_id' => $data->academic_year_id,
                'class_id' => $data->class_id,
                'contact_id' => $data->contact_id,
                'product_id' => $data->product_id,
                'actual_amount' => $data->actual_amount,
                'amount' => $data->amount,
                'notes' => $notes,
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'enum_month_id' => $data->enum_month_id,
                'flag' => 'update',

            ]);
        }

        if ($due_check && $due_check->paid_amount <= $payable_ammount) {
            $due_version = DB::table('contact_payable_items_version')->insert([
                'contact_id' => $due_check->contact_id,
                'class_id' => $due_check->class_id,
                'academic_year_id' => $due_check->academic_year_id,
                'product_id' => $due_check->product_id,
                'updated_at' => date('Y-m-d h:i:s'),
                'updated_by' => Auth::user()->id,
                'amount' => $due_check->amount,
                'month_id' => $due_check->month_id,
                'is_paid' => $due_check->is_paid,
                'paid_amount' => $due_check->paid_amount,
                'generated_payable_list_id' => $due_check->generated_payable_list_id,
                'flag' => 'update',
            ]);
            $contact_payable_items_update = DB::table('contact_payable_items')->where('id', $due_check->id)->update([
                'contact_id' => $contact_id,
                'product_id' => $product_id,
                'class_id' => $class_id,
                'month_id' => $month_id,
                'academic_year_id' => $academic_year_id,
                'amount' => $payable_ammount,
                'due' => (string) ($payable_ammount - $due_check->paid_amount),
                'is_paid' => (($payable_ammount - $due_check->paid_amount) == 0) ? 1 : 0,
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
                'date' => date('Y-m-d'),
            ]);
        }
    }

    public function contactwise_item_insert($contact_id, $academic_year_id, $class_id, $month_id, $product_id, $actual_amount, $payable_ammount, $notes)
    {

        $store = DB::table('contactwise_item_discount_price_list')->insertGetId([
            'academic_year_id' => $academic_year_id,
            'class_id' => $class_id,
            'contact_id' => $contact_id,
            'product_id' => $product_id,
            'actual_amount' => $actual_amount,
            'amount' => $payable_ammount,
            'notes' => $notes,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'enum_month_id' => $month_id,

        ]);
        $version = DB::table('contactwise_item_discount_price_list_version')->insert([
            'academic_year_id' => $academic_year_id,
            'class_id' => $class_id,
            'contact_id' => $contact_id,
            'product_id' => $product_id,
            'actual_amount' => $actual_amount,
            'amount' => $payable_ammount,
            'notes' => $notes,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'enum_month_id' => $month_id,
            'flag' => 'insert',

        ]);
        return $store;

    }
    public function due_generate_store($discount_id, $contact_id, $academic_year_id, $class_id, $month_id, $product_id, $actual_amount, $payable_ammount)
    {
        $contact_payable_items = DB::table('contact_payable_items')->insertGetId([
            'contact_id' => $contact_id,
            'product_id' => $product_id,
            'class_id' => $class_id,
            'month_id' => $month_id,
            'academic_year_id' => $academic_year_id,
            'amount' => $payable_ammount,
            'paid_amount' => 0,
            'due' => $payable_ammount,
            'is_paid' => 0,
            'created_by' => Auth::user()->id,
            'created_at' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
            'contact_discount_id' => $discount_id,
        ]);
    }

    public function due_generate_update($data, $contact_id, $academic_year_id, $class_id, $month_id, $product_id, $actual_amount, $payable_ammount)
    {
        $contact_payable_items_update = DB::table('contact_payable_items')->where('id', $data->id)->update([
            'contact_id' => $contact_id,
            'product_id' => $product_id,
            'class_id' => $class_id,
            'month_id' => $month_id,
            'academic_year_id' => $academic_year_id,
            'amount' => $payable_ammount,
            'due' => (string) ($payable_ammount - $data->paid_amount),
            'is_paid' => (($payable_ammount - $data->paid_amount) == 0) ? 1 : 0,
            'updated_by' => Auth::user()->id,
            'updated_at' => date('Y-m-d H:i:s'),
            'date' => date('Y-m-d'),
        ]);
        $version = DB::table('contact_payable_items_version')->insert([
            'contact_id' => $data->contact_id,
            'class_id' => $data->class_id,
            'academic_year_id' => $data->academic_year_id,
            'product_id' => $data->product_id,
            'updated_at' => date('Y-m-d h:i:s'),
            'updated_by' => Auth::user()->id,
            'amount' => $data->amount,
            'month_id' => $data->month_id,
            'is_paid' => $data->is_paid,
            'paid_amount' => $data->paid_amount,
            'generated_payable_list_id' => $data->generated_payable_list_id,
            'flag' => 'update',
        ]);
    }

    public function updateSalesRel()
    {
        $data = DB::table('sales')->join('sales_product_relation', 'sales_product_relation.sales_id', 'sales.id')
            ->select('sales.customer_id', 'sales_product_relation.*')
            ->get();
        foreach ($data as $key => $value) {
            $itemData = DB::table('contact_payable_items')->where('contact_id', $value->customer_id)
                ->where('product_id', $value->product_id)->where('month_id', $value->month_id)
                ->where('academic_year_id', $value->academic_year_id)->first();
            if (!empty($itemData)) {
                DB::table('sales_product_relation')->where('id', $value->id)
                    ->update([
                        'contact_payable_id' => $itemData->id,
                    ]);
            }
        }
        toastr()->success('updated');
        return back();
    }

    public function disCountUpdate()
    {
        $data = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', 6)->where('enum_month_id', 1)->whereIn('product_id', [9, 19])->get();
        foreach ($data as $val) {
            $checkData = DB::table('contactwise_item_discount_price_list')->where('academic_year_id', 6)->where('enum_month_id', 2)->where('product_id', $val->product_id)
                ->where('contact_id', $val->contact_id)->first();
            if (empty($checkData)) {
                DB::table('contactwise_item_discount_price_list')->insert([
                    'academic_year_id' => $val->academic_year_id,
                    'class_id' => $val->class_id,
                    'contact_id' => $val->contact_id,
                    'product_id' => $val->product_id,
                    'actual_amount' => $val->actual_amount,
                    'discount_amount' => $val->discount_amount,
                    'amount' => $val->amount,
                    'is_approved' => $val->is_approved,
                    'approved_by' => $val->approved_by,
                    'notes' => $val->notes,
                    'created_at' => date('Y-m-d h:i:s'),
                    'created_by' => auth()->user()->id,
                    'enum_month_id' => 2,
                ]);
            }
        }

        toastr()->success('updated');
        return back();

    }

    public function bulkStudentEdit(Request $request)
    {
        $academic_year = ['' => 'Select Academic Year'] + DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $classList = ['' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        $shiftList = ['' => 'All'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $sectionList = ['' => 'All'] + DB::table('sections')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $html = '';

        if ($request->search == 'true') {
            $students = DB::table('contacts')->join('contact_academics', 'contact_academics.contact_id', 'contacts.id');
            if ($request->update_type == 3) {
                $students = $students->join('contact_hierarchy as father_relation', 'contacts.id', 'father_relation.source_contactid')
                    ->join('contacts as father', 'father_relation.target_contact', 'father.id')
                    ->where('father.type', 2);
            }
            if ($request->update_type == 4) {
                $students = $students->join('contact_hierarchy as mother_relation', 'contacts.id', 'mother_relation.source_contactid')
                    ->join('contacts as mother', 'mother_relation.target_contact', 'mother.id')
                    ->where('mother.type', 3);
            }
            if ($request->update_type == 5) {
                $students = $students->join('contact_hierarchy as guardian_relation', 'contacts.id', 'guardian_relation.source_contactid')
                    ->join('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                    ->where('guardian.type', 4);
            }

            $students = $students->where('contact_academics.academic_year_id', $request->academic_year_id)->where('contact_academics.class_id', $request->class_id);
            if (!empty($request->section_id)) {
                $students = $students->where('contact_academics.section_id', $request->section_id);
            }
            $students = $students->where('contact_academics.is_trash', '0')->where('contact_academics.status', 'active')
                ->where('contacts.status', 'active');
            if ($request->update_type == 1) {
                $students = $students->select('contacts.*', 'contact_academics.section_id', 'contact_academics.shift_id', 'contact_academics.class_roll')->get();
                $totalStudent = count($students);
                $html = view('Student::student.academicInfoEdit', compact('students', 'shiftList', 'sectionList', 'totalStudent'))->render();
            }
            if ($request->update_type == 2) {
                $students = $students->select('contacts.*')->get();
                $totalStudent = count($students);
                $html = view('Student::student.studentInfoEdit', compact('students', 'totalStudent'))->render();
            }
            if ($request->update_type == 3) {
                $students = $students->select('contacts.*', 'father.full_name as father_name', 'father.cp_phone_no as father_phone', 'father.nid as father_nid', 'father.education_qualification as father_education')->get();
                $totalStudent = count($students);
                $html = view('Student::student.studentFatherInfoEdit', compact('students', 'totalStudent'))->render();
            }
            if ($request->update_type == 4) {
                $students = $students->select('contacts.*', 'mother.full_name as mother_name', 'mother.cp_phone_no as mother_phone', 'mother.education_qualification as mother_education')->get();
                $totalStudent = count($students);
                $html = view('Student::student.studentMotherInfoEdit', compact('students', 'totalStudent'))->render();
            }
            if ($request->update_type == 5) {
                $students = $students->select('contacts.*', 'guardian.full_name as guardian_name', 'guardian.cp_phone_no as guardian_phone', 'guardian.guardian_relation as relation')->get();
                $totalStudent = count($students);
                $html = view('Student::student.studentGuardianInfoEdit', compact('students', 'totalStudent'))->render();
            }

        }

        return view('Student::student.bulkEdit', compact('academic_year', 'classList', 'currentYear', 'html', 'request'));
    }

    public function bulkStudentEditFilter(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required',
            'class_id' => 'required',
            'update_type' => 'required',
        ]);

        $url = 'bulk-student-edit?search=true&academic_year_id=' . $request->academic_year_id . '&class_id=' . $request->class_id
        . '&section_id=' . $request->section_id . '&update_type=' . $request->update_type;
        return redirect($url);
    }

    public function bulkStudentUpdate(Request $request)
    {
        // if ($request->photo) {
        //     $old_photo = $request->old_photo;
        //     if ($old_photo == "profile.png") {
        //         $photo = $request->photo;
        //         $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        //         Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
        //     } else if ($request->old_photo) {
        //         unlink(base_path() . '/public/backend/images/students/' . $request->old_photo);
        //         $photo = $request->photo;
        //         $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        //         Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
        //     } else {
        //         $photo = $request->photo;
        //         $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
        //         Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
        //     }
        // } else {
        //     $photoName = $request->old_photo;
        // }

        DB::beginTransaction();
        try {
            if ($request->update_type == 1) {
                if (!empty($request->students)) {

                    foreach ($request->students as $studentId => $stdVal) {
                        DB::table('contact_academics')->where('contact_id', $studentId)->where('class_id', $request->class_id)
                            ->where('academic_year_id', $request->academic_year_id)->where('status', 'active')
                            ->update([
                                'class_roll' => $request->roll[$studentId],
                                'section_id' => $request->section[$studentId],
                                'shift_id' => $request->shift[$studentId],
                                'updated_by' => Auth::user()->id,
                                'updated_at' => date('Y-m-d h:i:s'),
                            ]);

                        // Student Information
                        $studentDetails = DB::table('contacts')->where('id', $studentId)->first();
                        $studentName = $studentDetails->first_name ? $studentDetails->first_name : $studentDetails->last_name;
                        $studentName = str_replace(' ', '', $studentName);
                        // Image Processing

                        if (isset($request->photo[$studentId]) && $request->photo[$studentId]) {
                            $photo = $request->photo[$studentId];

                            // Check if the file is an image
                            $image_info = getimagesize($photo);
                            if ($image_info !== false) {
                                // Upload the new photo
                                $old_photo = $request->old_photo[$studentId];
                                if ($old_photo == "profile.png") {
                                    $photoName = $studentName . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                                } else if ($old_photo && file_exists(base_path() . '/public/backend/images/students/' . $old_photo)) {
                                    unlink(base_path() . '/public/backend/images/students/' . $request->old_photo[$studentId]);
                                    $photoName = $studentName . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                                } else {
                                    $photoName = $studentName . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                                    Image::make($photo)->resize(150, 150)->save(base_path() . '/public/backend/images/students/' . $photoName);
                                }
                            } else {
                                // The file is not an image
                                echo "File is not an image";
                            }
                        } else {
                            $photoName = $request->old_photo[$studentId];
                        }

                        DB::table('contacts')->where('id', $studentId)->update([
                            'photo' => $photoName,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d h:i:s'),
                        ]);

                    }

                    DB::commit();
                    toastr()->success("Student's academic info updated successfully");
                    return back();
                } else {
                    toastr()->warning('Please check atleast one student');
                    return back();
                }
            }

            if ($request->update_type == 2) {
                if (!empty($request->students)) {
                    foreach ($request->students as $studentId => $stdVal) {
                        DB::table('contacts')->where('id', $studentId)
                            ->update([
                                'first_name' => $request->first_name[$studentId],
                                'last_name' => $request->last_name[$studentId],
                                'cp_phone_no' => $request->cp_phone_no[$studentId],
                                'gender' => $request->gender[$studentId],
                                'status' => $request->status[$studentId],
                                'updated_by' => Auth::user()->id,
                                'updated_at' => date('Y-m-d h:i:s'),
                            ]);
                    }
                    DB::commit();
                    toastr()->success("Student's Primary info updated successfully");
                    return back();
                } else {
                    toastr()->warning('Please check atleast one student');
                    return back();
                }
            }

            if ($request->update_type == 3) {
                if (!empty($request->students)) {
                    foreach ($request->students as $studentId => $stdVal) {
                        $father = DB::table('contact_hierarchy as father')->where('source_contactid', $studentId)->where('relationship_type_nodeid', 1)->select('father.target_contact as fatherId')->first();
                        DB::table('contacts')->where('id', $father->fatherId)->update([
                            'type' => 2,
                            'full_name' => $request->father_name[$studentId],
                            'education_qualification' => $request->father_education[$studentId],
                            'cp_phone_no' => $request->father_phone[$studentId],
                            'nid' => $request->father_nid[$studentId],
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    DB::commit();
                    toastr()->success("Student's Father info updated successfully");
                    return back();
                } else {
                    toastr()->warning('Please check atleast one student');
                    return back();
                }
            }

            if ($request->update_type == 4) {
                if (!empty($request->students)) {
                    foreach ($request->students as $studentId => $stdVal) {
                        $mother = DB::table('contact_hierarchy as mother')->where('source_contactid', $studentId)->where('relationship_type_nodeid', 2)->select('mother.target_contact as motherId')->first();
                        DB::table('contacts')->where('id', $mother->motherId)->update([
                            'type' => 3,
                            'full_name' => $request->mother_name[$studentId],
                            'education_qualification' => $request->mother_education[$studentId],
                            'cp_phone_no' => $request->mother_phone[$studentId],
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    DB::commit();
                    toastr()->success("Student's Mother info updated successfully");
                    return back();
                } else {
                    toastr()->warning('Please check atleast one student');
                    return back();
                }
            }

            if ($request->update_type == 5) {
                if (!empty($request->students)) {
                    foreach ($request->students as $studentId => $stdVal) {
                        $guardian = DB::table('contact_hierarchy as guardian')->where('source_contactid', $studentId)->where('relationship_type_nodeid', 3)->select('guardian.target_contact as guardianId')->first();
                        DB::table('contacts')->where('id', $guardian->guardianId)->update([
                            'type' => 4,
                            'full_name' => $request->guardian_name[$studentId],
                            'cp_phone_no' => $request->guardian_phone[$studentId],
                            'guardian_relation' => $request->guardian_relation[$studentId],
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    DB::commit();
                    toastr()->success("Student's Guardian info updated successfully");
                    return back();
                } else {
                    toastr()->warning('Please check atleast one student');
                    return back();
                }
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    public function paymentsetupstoreupdate(Request $request, $id)
    {
        $payable_contact = DB::table('contactwise_item_discount_price_list')->where('class_id', $id)->where('enum_month_id', 2)->where('academic_year_id', 6)->whereIn('product_id', [9, 19])->get();
        foreach ($payable_contact as $key => $value) {
            for ($i = 3; $i <= 12; $i++) {
                $checking = DB::table('contactwise_item_discount_price_list')->where('contact_id', $value->contact_id)->where('product_id', $value->product_id)->where('class_id', $id)->where('enum_month_id', $i)->where('academic_year_id', $value->academic_year_id)->first();
                // dd($checking);
                if (!$checking && $value->amount > 0) {
                    $store = DB::table('contactwise_item_discount_price_list')->insertGetId([
                        'academic_year_id' => $value->academic_year_id,
                        'class_id' => $value->class_id,
                        'contact_id' => $value->contact_id,
                        'product_id' => $value->product_id,
                        'actual_amount' => $value->actual_amount,
                        'amount' => $value->amount,
                        'created_by' => Auth::user()->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'enum_month_id' => $i,

                    ]);
                }
            }
        }
    }

    public function generateSID($digits, $totalStudent)
    {
        $response['sid'] = '';
        for ($i = 1; $i <= $digits; $i++) {
            for ($j = 1; $j <= $digits - strlen($totalStudent); $j++) {
                $count = $j;
            }
        }
        $sid = str_repeat('0', $count);
        $student_id = $sid . $totalStudent;

        $response['sid'] = $student_id;
        return $response;

    }

    // Studentwise Yearly Payment Price Setup List
    public function studentWisePaymentPriceList($id)
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
            ->select('contacts.full_name', 'contacts.contact_id', 'contacts.id', 'contacts.gender', 'classes.name as class_name', 'classes.id as class_id', 'sections.name as section_name', 'contact_academics.class_roll', 'contact_academics.registration_no', 'sections.name as section_name', 'shifts.name as shift_name', 'academic_years.year', 'guardian.full_name as guardian_name')
            ->first();

        $currentYear = (DB::table('academic_years')
                ->where('is_current', 1)
                ->first())->id;

        $details = DB::table('monthly_class_item')
            ->where('monthly_class_item.academic_year_id', $currentYear)
            ->where('monthly_class_item.class_id', $data->class_id)
            ->leftjoin('products', 'monthly_class_item.item_id', 'products.id')
            ->select('products.name', 'monthly_class_item.item_id as product_id', 'monthly_class_item.academic_year_id', 'monthly_class_item.class_id', 'monthly_class_item.item_price', 'monthly_class_item.month_id')
            ->get();

        $details2 = DB::table('contact_payable_items')
            ->where('contact_payable_items.academic_year_id', $currentYear)
            ->where('contact_payable_items.class_id', $data->class_id)
            ->where('contact_payable_items.contact_id', $id)
            ->leftjoin('products', 'contact_payable_items.product_id', 'products.id')
            ->select('products.name', 'contact_payable_items.product_id as product_id', 'contact_payable_items.academic_year_id', 'contact_payable_items.class_id', 'contact_payable_items.amount as item_price', 'contact_payable_items.month_id')
            ->get();

        $diff = $details2
            ->whereNotIn('product_id', $details->pluck('product_id'))
            ->all();

        $details = collect($details)->concat($diff);

        $monthList = DB::table('enum_month')
            ->select('enum_month.id', 'enum_month.name')
            ->groupBy('enum_month.id', 'enum_month.name')
            ->orderBy('enum_month.id')
            ->get();

        return view('Student::paymentPriceList.studentWisePaymentPriceList', compact('data', 'monthList', 'details'));
    }

}
