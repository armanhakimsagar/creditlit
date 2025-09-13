<?php

namespace App\Modules\Stuff\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
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

    public function salaryIndex(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('employee.salary.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $data = DB::table('employee_salary')->leftjoin('contacts as employee', 'employee.id', 'employee_salary.contact_id')->where('employee.is_trash', 0)->where('employee.type', 5)->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id')->whereNot('employee_salary.status', 'cancel')->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name as full_name', 'employee_salary.gross_salary as gross_salary', 'employee_salary.total_salary', 'employee_salary.basic_salary', 'employee_salary.deduction_salary', 'employee_salary.other_salary', 'employee_salary.id as id', 'employee_salary.status as status')->get();

        if ($request->ajax()) {
            return Datatables::of($data)->addIndexColumn()
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
                    $btn = '<a href="' . route('employee.salary.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" id="delete"><i class="fas fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Stuff::salary.index');
    }
    public function salaryCreate(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('employee.salary.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }
        $academic_year = ['0' => 'Select Academic Year'] + DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        $salaryGeneratedEmployees = DB::table('employee_salary')->whereNot('status', 'cancel')->pluck('contact_id')->toArray();
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        if ($request->ajax()) {
            if (!empty($request->academicYearId)) {
                $data = DB::table('contacts as employee')
                    ->where('employee.is_trash', 0)
                    ->where('employee.type', 5)
                    ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
                    ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');
                if ($request->departmentId != '0') {
                    $data->where('employee.employee_department_id', $request->departmentId);
                }
                if ($request->designationId != '0') {
                    $data->where('employee.employee_designation_id', $request->designationId);
                }
                $data = $data->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.*')
                    ->get();

                return Datatables::of($data)->addIndexColumn()
                    ->editColumn('gross_salary', function ($row) {
                        if (DB::table('employee_salary')->where('contact_id', $row->id)->where('status', 'active')->exists()) {
                            $empSalary = DB::table('employee_salary')->where('contact_id', $row->id)->where('status', 'active')->first();
                            $gross = '<input type="hidden" class="form-control" name="employee_id[]" id="" value="' . $row->id . '"><input type="text" class="form-control" name="gross_salary[' . $row->id . '][]" id="" value="' . $empSalary->total_salary . '">';
                        } else {
                            $gross = '<input type="hidden" class="form-control" name="employee_id[]" id="" value="' . $row->id . '"><input type="text" class="form-control" name="gross_salary[' . $row->id . '][]" id="" value="">';
                        }
                        return $gross;
                    })
                    ->editColumn('total_salary', function ($row) {
                        $total = '<input type="text" class="form-control" name="total_salary[' . $row->id . '][]" id="" value="">';
                        return $total;
                    })
                    ->rawColumns(['gross_salary', 'total_salary'])
                    ->make(true);
            }
        }
        return view('Stuff::salary.create', compact('academic_year', 'currentYear', 'department', 'designation'));
    }
    public function salaryStore(Request $request)
    {
        $input = $request->all();
        $salaryArr = [];
        DB::beginTransaction();
        try {
            $employeeId = $input['employee_id'];
            foreach ($employeeId as $key => $empId) {
                if (DB::table('employee_salary')->where('contact_id', $empId)->where('status', 'active')->doesntExist()) {
                    $salaryArr[$key]['contact_id'] = $empId;
                    $salaryArr[$key]['gross_salary'] = $input['gross_salary'][$empId][0];
                    $salaryArr[$key]['total_salary'] = $input['gross_salary'][$empId][0];
                    $salaryArr[$key]['created_by'] = Auth::id();
                    $salaryArr[$key]['updated_by'] = Auth::id();
                    $salaryArr[$key]['status'] = 'active';
                    $salaryArr[$key]['academic_year_id'] = $input['academic_year'];
                    $salaryArr[$key]['created_at'] = date('Y-m-d h:i:s');
                } else {
                    $empSalary = DB::table('employee_salary')->where('contact_id', $empId)->where('status', 'active')->first();
                    DB::table('employee_salary_version')->insert([
                        'contact_id' => $empId,
                        'gross_salary' => $empSalary->gross_salary,
                        'total_salary' => $empSalary->gross_salary,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'status' => 'active',
                        'academic_year_id' => $empSalary->academic_year_id,
                        'created_at' => date('Y-m-d h:i:s'),
                        'employee_salary_id' => $empSalary->id,
                        'flag' => 'Updated',
                    ]);

                    DB::table('employee_salary')->where('id', $empSalary->id)->update([
                        'contact_id' => $empId,
                        'gross_salary' => $input['gross_salary'][$empId][0],
                        'total_salary' => $input['gross_salary'][$empId][0],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'status' => 'active',
                        'academic_year_id' => $input['academic_year'],
                        'updated_at' => date('Y-m-d h:i:s'),
                    ]);
                }
            }
            if (!empty($salaryArr)) {
                DB::table('employee_salary')->insert($salaryArr);
            }
            DB::commit();
            Session::flash('success', 'Employee Salary Stored Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('employee.salary.index');
    }
    public function salaryDestroy($id)
    {
        DB::beginTransaction();
        try {
            $empSalary = DB::table('employee_salary')->where('id', $id)->where('status', 'active')->first();
            DB::table('employee_salary_version')->insert([
                'contact_id' => $empSalary->contact_id,
                'gross_salary' => $empSalary->gross_salary,
                'total_salary' => $empSalary->gross_salary,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status' => 'active',
                'academic_year_id' => $empSalary->academic_year_id,
                'created_at' => date('Y-m-d h:i:s'),
                'employee_salary_id' => $empSalary->id,
                'flag' => 'Deleted',
            ]);
            DB::table('employee_salary')->where('id', $id)->delete();
            DB::commit();
            Session::flash('success', 'Data Deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('employee.salary.index');
    }
    public function index(Request $request)
    {
        $data = DB::table('generate_payroll')->join('academic_years', 'academic_years.id', 'generate_payroll.academic_year_id')->join('enum_month', 'enum_month.id', 'generate_payroll.month_id')->select('academic_years.year', 'enum_month.name as month_name', 'generate_payroll.status', 'generate_payroll.academic_year_id', 'generate_payroll.month_id')->groupBy('month_name', 'year', 'status', 'academic_year_id', 'month_id')->get();
        if ($request->ajax()) {
            return Datatables::of($data)->addIndexColumn()
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
                    $btn = '<a href="' . route('employee.payroll.view', ['academicYearId' => $row->academic_year_id, 'monthId' => $row->month_id]) . '" class="btn btn-outline-primary btn-xs" >View</a> <a href="' . route('pay.employee.salary', ['academicYearId' => $row->academic_year_id, 'monthId' => $row->month_id]) . '" class="btn btn-outline-info btn-xs" >Pay</a> <a href="' . route('employee.payroll.report', ['academicYearId' => $row->academic_year_id, 'monthId' => $row->month_id]) . '" class="btn btn-outline-secondary btn-xs" >Report</a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('Stuff::payroll.index');
    }
    public function create(Request $request)
    {
        $months = DB::table('enum_month')->pluck('name', 'id')->toArray();
        $academic_year = ['0' => 'Select Academic Year'] + DB::table('academic_years')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $currentYear = DB::table('academic_years')->where('is_current', '1')->first();
        if ($request->ajax()) {
            if (!empty($request->academicYearId)) {
                $data = DB::table('employee_salary')->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
                    ->where('employee.is_trash', 0)
                    ->where('employee.type', 5)
                    ->where('employee.status', 'active')
                    ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
                    ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');
                // $data->where('employee_salary.academic_year_id', $request->academicYearId);
                $data = $data->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee_salary.gross_salary', 'employee_salary.*')->get();
                // <input type="hidden" class="form-control" name="employee_salary_id[]" id="" value="' . $row->id . '">
                return Datatables::of($data)->addIndexColumn()
                    ->editColumn('gross_salary', function ($row) {
                        $gross = '<input type="text" class="form-control" name="gross_salary[' . $row->id . '][]" id="" value="' . $row->total_salary . '" readonly>';
                        return $gross;
                    })
                    ->editColumn('total_salary', function ($row) {
                        $total = '<input type="text" class="form-control" name="total_salary[' . $row->id . '][]" id="" value="">';
                        return $total;
                    })
                    ->addColumn('checkbox', function ($row) use ($request) {
                        if (DB::table('employee_payroll')->where('employee_salary_id', $row->id)->where('academic_year_id', $request->academicYearId)->where('month_id', $request->monthId)->where('is_disbursed', 1)->exists()) {
                            $btn = '<input type="checkbox" checked="" disabled class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_salary_id[]]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                            return $btn;
                        } else if (DB::table('employee_payroll')->where('employee_salary_id', $row->id)->where('academic_year_id', $request->academicYearId)->where('month_id', $request->monthId)->exists()) {
                            $btn = '<input type="checkbox" checked="" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_salary_id[]]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                            return $btn;
                        } else {
                            $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_salary_id[]]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                            return $btn;
                        }
                    })
                    ->rawColumns(['gross_salary', 'total_salary', 'checkbox'])
                    ->make(true);
            }
        }
        return view('Stuff::payroll.create', compact('months', 'academic_year', 'currentYear'));
    }
    public function store(Request $request)
    {
        $input = $request->all();
        $salaryIdArr = $input['employee_salary_id'];
        $input['academic_year_id'] = $input['academic_year'];
        $input['month_id'] = $input['month'];
        $employeePayroll = [];
        DB::beginTransaction();
        try {
            if (DB::table('generate_payroll')->where('academic_year_id', $input['academic_year_id'])->where('month_id', $input['month_id'])->exists()) {
                $payrollGeneratedId = DB::table('generate_payroll')->where('academic_year_id', $input['academic_year_id'])->where('month_id', $input['month_id'])->first()->id;
            } else {
                $payrollGeneratedId = DB::table('generate_payroll')->insertGetId([
                    'academic_year_id' => $input['academic_year_id'],
                    'created_at' => date('Y-m-d h:i:s'),
                    'created_by' => Auth::id(),
                    'status' => 'active',
                    'date' => date('Y-m-d'),
                    'month_id' => $input['month_id'],
                ]);
            }

            foreach ($salaryIdArr as $key => $salaryId) {
                $salaryDetails = DB::table('employee_salary')->where('id', $salaryId)->first();
                $salaryItemDetails = DB::table('employee_salary_item_details')
                    ->where('salary_id', $salaryDetails->id)
                    ->get();

                if (DB::table('employee_payroll')->where('employee_id', $salaryDetails->contact_id)->where('academic_year_id', $input['academic_year_id'])->where('month_id', $input['month_id'])->where('status', 'active')->exists()) {
                    $employeePayrollUpdate = DB::table('employee_payroll')->where('employee_id', $salaryDetails->contact_id)->where('academic_year_id', $input['academic_year_id'])->where('month_id', $input['month_id'])->where('status', 'active')->update([
                        'employee_salary_id' => $salaryId,
                        'employee_id' => $salaryDetails->contact_id,
                        'academic_year_id' => $input['academic_year_id'],
                        'month_id' => $input['month_id'],
                        'is_disbursed' => '0',
                        'status' => 'active',
                        'updated_at' => date('Y-m-d h:i:s'),
                        'updated_by' => Auth::id(),
                        'total_salary' => $input['gross_salary'][$salaryId][0],
                        'basic_salary' => $salaryDetails->basic_salary,
                        'gross_salary' => $salaryDetails->gross_salary,
                        'deduction_salary' => $salaryDetails->deduction_salary,
                        'other_salary' => $salaryDetails->other_salary,
                        'allowance_salary' => $salaryDetails->allowance_salary,
                        'generate_payroll_id' => $payrollGeneratedId,
                    ]);
                    $employeePayrollId = DB::table('employee_payroll')->where('employee_id', $salaryDetails->contact_id)->where('academic_year_id', $input['academic_year_id'])->where('month_id', $input['month_id'])->where('status', 'active')->value('id');
                } else {
                    $employeePayrollId = DB::table('employee_payroll')->insertGetId([
                        'employee_salary_id' => $salaryId,
                        'employee_id' => $salaryDetails->contact_id,
                        'academic_year_id' => $input['academic_year_id'],
                        'month_id' => $input['month_id'],
                        'is_disbursed' => '0',
                        'status' => 'active',
                        'created_at' => date('Y-m-d h:i:s'),
                        'created_by' => Auth::id(),
                        'total_salary' => $input['gross_salary'][$salaryId][0],
                        'basic_salary' => $salaryDetails->basic_salary,
                        'gross_salary' => $salaryDetails->gross_salary,
                        'deduction_salary' => $salaryDetails->deduction_salary,
                        'other_salary' => $salaryDetails->other_salary,
                        'allowance_salary' => $salaryDetails->allowance_salary,
                        'generate_payroll_id' => $payrollGeneratedId,
                    ]);
                }

                foreach ($salaryItemDetails as $key => $value) {
                    if (DB::table('employee_payroll_item_details')->where('employee_id', $salaryDetails->contact_id)->where('item_id', $salaryItemDetails[$key]->item_id)->where('payroll_id', $employeePayrollId)->exists()) {
                        $employeePayrollItemDeatails = DB::table('employee_payroll_item_details')->where('employee_id', $salaryDetails->contact_id)->where('item_id', $salaryItemDetails[$key]->item_id)->where('payroll_id', $employeePayrollId)->update([
                            'salary_id' => $salaryItemDetails[$key]->salary_id,
                            'amount_type' => $salaryItemDetails[$key]->amount_type,
                            'amount' => $salaryItemDetails[$key]->amount,
                            'total_amount' => $salaryItemDetails[$key]->total_amount,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $employeePayrollItemDeatails = DB::table('employee_payroll_item_details')->insertGetId([
                            'employee_id' => $salaryItemDetails[$key]->employee_id,
                            'payroll_id' => $employeePayrollId,
                            'salary_id' => $salaryItemDetails[$key]->salary_id,
                            'item_id' => $salaryItemDetails[$key]->item_id,
                            'amount_type' => $salaryItemDetails[$key]->amount_type,
                            'amount' => $salaryItemDetails[$key]->amount,
                            'total_amount' => $salaryItemDetails[$key]->total_amount,
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }

            // exit();
            DB::commit();
            Session::flash('success', 'Payroll Generated Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('employee.payroll.index');
    }
    public function createPaySalary(Request $request, $academicYearId, $monthId)
    {
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        if ($request->ajax()) {
            $data = DB::table('employee_payroll')
                ->join('employee_salary', 'employee_salary.id', 'employee_payroll.employee_salary_id')
                ->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
                ->where('employee.is_trash', 0)
                ->where('employee.type', 5)
                ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
                ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');
            $data->where('employee_payroll.academic_year_id', $academicYearId);
            $data->where('employee_payroll.month_id', $monthId);
            if ($request->departmentId != 0) {
                $data->where('employee.employee_department_id', $request->departmentId);
            }
            if ($request->designationId != 0) {
                $data->where('employee.employee_designation_id', $request->designationId);
            }
            $data = $data->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee.payment_type as payments_type', 'employee_payroll.*')->get();
            return Datatables::of($data)->addIndexColumn()
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
                ->addColumn('checkbox', function ($row) {
                    if (DB::table('employee_payroll')->where('id', $row->id)->where('is_disbursed', '1')->exists()) {
                        $btn = '<input type="checkbox" checked="" disabled class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_payroll_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    } else {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_payroll_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
                    }
                    return $btn;
                })
                ->addColumn('payment_type', function ($row) {
                    if (DB::table('employee_payroll')->where('id', $row->id)->where('is_disbursed', '1')->exists()) {
                        $cashSelected = $row->payment_type == 'cash' ? 'selected' : '';
                        $bankSelected = $row->payment_type == 'bank' ? 'selected' : '';

                        $btn = '<select id="payment_type" class="form-control select3" name="payment_type[]" disabled>';
                        $btn .= '<option value="cash" ' . $cashSelected . '>Cash</option>';
                        $btn .= '<option value="bank" ' . $bankSelected . '>Bank</option>';
                        $btn .= '</select>';
                    } else {
                        $cashSelected = $row->payments_type == 'cash' ? 'selected' : '';
                        $bankSelected = $row->payments_type == 'bank' ? 'selected' : '';

                        $btn = '<select id="payment_type" class="form-control select3" name="payment_type[]">';
                        $btn .= '<option value="cash" ' . $cashSelected . '>Cash</option>';
                        $btn .= '<option value="bank" ' . $bankSelected . '>Bank</option>';
                        $btn .= '</select>';
                    }

                    return $btn;
                })

                ->rawColumns(['status', 'checkbox', 'payment_type'])
                ->make(true);
        }
        return view('Stuff::payroll.paySalary', compact('department', 'designation', 'academicYearId', 'monthId'));
    }

    public function updateEmployeeSalary(Request $request)
    {
        if (empty($request->employee_payroll_id)) {
            Session::flash('warning', 'Please Check an Payroll');
            return redirect()->back()->withInput($request->all());
        }
        $employee_payroll_id = $request->employee_payroll_id;
        DB::beginTransaction();
        try {
            foreach ($employee_payroll_id as $key => $payroll_id) {
                DB::table('employee_payroll')->where('academic_year_id', $request->academic_year_id)->where('month_id', $request->month_id)->where('id', $payroll_id)->update([
                    'is_disbursed' => 1,
                    'payment_type' => $request->payment_type[$key],
                ]);
            }
            DB::commit();
            Session::flash('success', 'Payroll Updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
        }
        return redirect()->route('employee.payroll.index');
    }

    // Employee Payroll View
    public function employeePayrollView(Request $request, $academicYearId, $monthId)
    {
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        if ($request->ajax()) {
            $data = DB::table('employee_payroll')->join('employee_salary', 'employee_salary.id', 'employee_payroll.employee_salary_id')->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
                ->where('employee.is_trash', 0)
                ->where('employee.type', 5)
                ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
                ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id');
            $data->where('employee_payroll.academic_year_id', $academicYearId);
            $data->where('employee_payroll.month_id', $monthId);
            $data = $data->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee_payroll.*', 'employee_salary.gross_salary', 'employee_salary.basic_salary', 'employee_salary.deduction_salary', 'employee_payroll.allowance_salary')->get();
            return Datatables::of($data)->addIndexColumn()
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

                ->editColumn('payment_status', function ($row) {
                    if ($row->is_disbursed == 1) {
                        $status = '<span class="badge badge-success">Paid</span>';
                    } elseif ($row->is_disbursed == 0) {
                        $status = '<span class="badge badge-warning">Not Paid</span>';
                    }
                    return $status;
                })

            // ->addColumn('checkbox', function ($row) {
            //     if (DB::table('employee_payroll')->where('id', $row->id)->where('is_disbursed', '1')->exists()) {
            //         $btn = '<input type="checkbox" checked="" disabled class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_payroll_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
            //     } else {
            //         $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkSection_' . $row->id . '" name="employee_payroll_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked()">';
            //     }
            //     return $btn;
            // })

                ->addColumn('paySlipCheckbox', function ($row) {
                    if (DB::table('employee_payroll')->where('id', $row->id)->where('is_disbursed', '1')->exists()) {
                        $btn = '<input type="checkbox" class="allCheck all-check-box" id="checkEmployee_' . $row->id . '" name="employee_pay_slip_id[]" value="' . $row->id . '" keyValue="' . $row->id . '" onclick="unCheck(this.id);isChecked();">';
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })

                ->addColumn('allowance', function ($row) {
                    if (DB::table('employee_payroll')->where('id', $row->id)->where('is_disbursed', '1')->exists()) {
                        $btn = '';
                    } else {
                        $btn = '<a href="' . route('class.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs allowance-add-btn" data-toggle="modal" data-target="#exampleModalCenter" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'allowance', 'payment_status', 'paySlipCheckbox'])
                ->make(true);
        }
        return view('Stuff::payroll.view', compact('department', 'designation', 'academicYearId', 'monthId'));
    }

    // Employee Payslip
    public function employeePayslip(Request $request)
    {
        $employeePaySlipId = $request->employee_pay_slip_id;
        $yearName = DB::table('academic_years')->where('id', $request->academic_year_id)->pluck('year')->first();
        $monthName = DB::table('enum_month')->where('id', $request->month_id)->pluck('short_name')->first();

        if (!empty($employeePaySlipId)) {
            $employeeList = fetchPayrollDetails($employeePaySlipId);
        }

        // echo "<pre>";
        // print_r($employeeList);
        // exit();

        return view("Stuff::payroll.paySlip", compact('yearName', 'monthName', 'employeeList'));
    }

    // allowance add
    public function allowanceAdd($id)
    {
        $academicYearId = request()->input('academicYearId');
        $monthId = request()->input('monthId');
        $data = DB::table('employee_payroll')
            ->where('employee_payroll.id', $id)
            ->join('employee_salary', 'employee_salary.id', 'employee_payroll.employee_salary_id')
            ->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
            ->where('employee.is_trash', 0)
            ->where('employee.type', 5)
            ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
            ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id')
            ->where('employee_payroll.academic_year_id', $academicYearId)
            ->where('employee_payroll.month_id', $monthId)
            ->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee_payroll.*', 'employee_salary.gross_salary', 'employee_salary.basic_salary', 'employee_salary.deduction_salary', 'employee_salary.allowance_salary', 'employee_salary.other_salary')
            ->first();
        $data2 = DB::table('salary_item')->where('salary_item.type', 3)->where('is_trash', 0)->get();
        $sum = $data2->sum('amount');
        $data1 = '';

        $data1 .= '<div class="col-md-12"><hr><center><h3>Allowance</h3></center><hr></div>';
        foreach ($data2 as $key => $item) {
            $data3 = DB::table('employee_payroll')->where('employee_payroll.id', $id)->leftjoin('employee_payroll_item_details', 'employee_payroll_item_details.employee_id', 'employee_payroll.employee_id')->where('item_id', $item->id)->where('employee_payroll.academic_year_id', $academicYearId)
                ->where('employee_payroll.month_id', $monthId)->first();
            // dd($data3);

            $data1 .= '
            <input type="hidden" name="allowance_item_id[]" value="' . $item->id . '">
            <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="" class="col-form-label">Salary Item Name</label>
                            <input id="allowanceItemNameId_0" class="form-control item-name valid" placeholder="Enter Salary Item name" oninput="checkItemDuplicate(0)" readonly="" name="allowance_item_name[]" type="text" value="' . $item->name . '" autocomplete="off" aria-invalid="false">
                            <span class="error" id="nameError_0"> </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="" class="col-form-label">Select Amount Type</label>
                            <select name="allowance_amount_type[]" id="allowanceAmountType_" class="form-control select3 allowance-amount-type" data-key="">
                                <option value="flat">Flat</option>
                            </select>
                            <span class="error" id="amountTypeError"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="amount" class="col-form-label">Item Amount</label>

                            <div class="input-group">
                                <input id="allowanceItemAmount_' . $key . '" class="form-control allowance-item-amount" placeholder="Enter Amount" oninput="calculateSalary()" data-key="' . $key . '" name="allowance_item_amount[]" type="number" value="' . ($data3 ? $data3->amount : 0) . '">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="inputGroupText_0">TK</span>
                                </div>
                            </div>
                            <span class="error"> </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="allowanceAmount" class="col-form-label">Amount</label>

                            <input id="allowanceAmount_' . $key . '" class="form-control allowance-amount" placeholder="Enter Amount" readonly="true" oninput="calculateSalary()" data-key="0" name="allowance_amount[]" type="number" value="' . ($data3 ? $data3->amount : 0) . '">
                            <span class="error"> </span>
                        </div>
                    </div>
                </div>';
        }

        $deduction_item = DB::table('salary_item')->where('salary_item.type', 2)->where('is_trash', 0)->get();

        $data1 .= '<div class="col-md-12"><hr><center><h3>Deduction</h3></center><hr></div>';
        foreach ($deduction_item as $key => $deduction_items) {
            $deduction3 = DB::table('employee_payroll')->where('employee_payroll.id', $id)->leftjoin('employee_payroll_item_details', 'employee_payroll_item_details.employee_id', 'employee_payroll.employee_id')->where('item_id', $deduction_items->id)->where('employee_payroll.academic_year_id', $academicYearId)
                ->where('employee_payroll.month_id', $monthId)->first();
            // dd($data3);

            $data1 .= '
            <input type="hidden" name="allowance_item_id[]" value="' . $deduction_items->id . '">
            <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="" class="col-form-label">Salary Item Name</label>
                            <input id="allowanceItemNameId_0" class="form-control item-name valid" placeholder="Enter Salary Item name" oninput="checkItemDuplicate(0)" readonly="" name="allowance_item_name[]" type="text" value="' . $deduction_items->name . '" autocomplete="off" aria-invalid="false">
                            <span class="error" id="nameError_0"> </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="" class="col-form-label">Select Amount Type</label>
                            <select name="allowance_amount_type[]" id="allowanceAmountType_' . (count($data2) + $key) . '" onchange="calculateSalary()" class="form-control select3 allowance-amount-type" data-key="">
                                <option value="flat">Flat</option>
                                <option value="percentage">Percentage</option>
                            </select>
                            <span class="error" id="amountTypeError"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="amount" class="col-form-label">Item Amount</label>

                            <div class="input-group">
                                <input id="allowanceItemAmount_' . (count($data2) + $key) . '" class="form-control deduction-item-amount" placeholder="Enter Amount" oninput="calculateSalary()" data-key="' . (count($data2) + $key) . '" name="allowance_item_amount[]" type="number" value="' . ($deduction3 ? $deduction3->amount : 0) . '">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="inputGroupText_0">TK</span>
                                </div>
                            </div>
                            <span class="error"> </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <div class="form-line">
                            <label for="allowanceAmount" class="col-form-label">Amount</label>

                            <input id="allowanceAmount_' . (count($data2) + $key) . '" class="form-control allowance-amount" placeholder="Enter Amount" readonly="true" oninput="calculateSalary()" data-key="0" name="allowance_amount[]" type="number" value="' . ($deduction3 ? ($deduction3->total_amount * -1) : 0) . '">
                            <span class="error"> </span>
                        </div>
                    </div>
                </div>';
        }

        return response()->json([
            'data' => $data,
            'data1' => $data1,
            'sum' => $sum,
        ]);
    }

    // Allowance Update
    public function allowanceUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            // For Allowance update
            if (isset($request->allowance_item_id)) {

                // Employee Payroll add
                if (DB::table('employee_payroll')->where('id', $request->employee_payroll_id)->exists()) {
                    $employeeSalary = DB::table('employee_payroll')->where('id', $request->employee_payroll_id)->update([
                        'basic_salary' => $request->basic_salary ? $request->basic_salary : 0,
                        'gross_salary' => $request->basic_salary ? $request->gross_salary : 0,
                        'other_salary' => $request->basic_salary ? $request->other_salary : 0,
                        'deduction_salary' => $request->basic_salary ? $request->deduction_salary : 0,
                        'total_salary' => $request->basic_salary ? $request->total_salary : 0,
                        'allowance_salary' => $request->allowance_salary ? $request->allowance_salary : 0,
                        'updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                foreach ($request->allowance_item_id as $key => $value) {
                    // Employee Payroll Item Details
                    $item_check = DB::table('salary_item')->where('id', $value)->first();
                    if ($item_check->type == 2) {
                        $total_allowance = $request->allowance_amount[$key] ? ($request->allowance_amount[$key] * -1) : 0;
                    } else {
                        $total_allowance = $request->allowance_amount[$key] ? $request->allowance_amount[$key] : 0;
                    }

                    if (DB::table('employee_payroll_item_details')->where('payroll_id', $request->employee_payroll_id)->where('item_id', $value)->exists()) {
                        $employeeSalaryGrossItemDeatails = DB::table('employee_payroll_item_details')->where('payroll_id', $request->employee_payroll_id)->where('item_id', $value)->update([
                            'amount_type' => $request->allowance_amount_type[$key],
                            'amount' => $request->allowance_item_amount[$key] ? $request->allowance_item_amount[$key] : 0,
                            'total_amount' => $total_allowance,
                            'updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    } else {
                        $employeeSalaryGrossItemDeatails = DB::table('employee_payroll_item_details')->insertGetId([
                            'payroll_id' => $request->employee_payroll_id,
                            'item_id' => $value,
                            'amount_type' => $request->allowance_amount_type[$key],
                            'amount' => $request->allowance_item_amount[$key] ? $request->allowance_item_amount[$key] : 0,
                            'total_amount' => $total_allowance,
                            'created_by' => Auth::user()->id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                }
            }

            DB::commit();
            Session::flash('success', 'Payroll Updated Successfully done!!!!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }
    }

    // Payroll Report
    public function employeePayrollReport(Request $request, $academicYearId, $monthId)
    {
        $page = "Without-type";
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $academic_year = DB::table('academic_years')->where('id', $academicYearId)->select('year')->first();
        $months = DB::table('enum_month')->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $month_name = DB::table('enum_month')->where('id', $monthId)->select('name')->first();
        $deduction_list = DB::table('salary_item')->where('type', 2)->where('status', 'active')->where('is_trash', 0)->get();
        $allowance_list = DB::table('salary_item')->where('type', 3)->where('status', 'active')->where('is_trash', 0)->get();
        $data = DB::table('employee_payroll')
            ->join('employee_salary', 'employee_salary.id', 'employee_payroll.employee_salary_id')
            ->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
            ->where('employee.is_trash', 0)
            ->where('employee.type', 5)
            ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
            ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id')
            ->where('employee_payroll.academic_year_id', $academicYearId)
            ->where('employee_payroll.month_id', $monthId)
            ->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee_payroll.*', 'employee_salary.gross_salary', 'employee_salary.basic_salary', 'employee_salary.deduction_salary', 'employee_payroll.allowance_salary', 'employee.bank_account_number')->get();

        return view('Stuff::payroll.report', compact('department', 'designation', 'academicYearId', 'monthId', 'months', 'academic_year_list', 'deduction_list', 'allowance_list', 'data', 'page', 'month_name', 'academic_year'));
    }

    // Payroll Report
    public function employeePayrollReportWithType(Request $request, $academicYearId, $monthId, $paymentType)
    {
        $page = "With-type";
        $department = ['0' => 'All'] + DB::table('enum_department_types')->pluck('name', 'id')->toArray();
        $designation = ['0' => 'All'] + DB::table('enum_employee_types')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $academic_year = DB::table('academic_years')->where('id', $academicYearId)->select('year')->first();
        $months = DB::table('enum_month')->orderBy('id', 'ASC')->pluck('name', 'id')->toArray();
        $month_name = DB::table('enum_month')->where('id', $monthId)->select('name')->first();
        $deduction_list = DB::table('salary_item')->where('type', 2)->where('status', 'active')->where('is_trash', 0)->get();
        $allowance_list = DB::table('salary_item')->where('type', 3)->where('status', 'active')->where('is_trash', 0)->get();
        $data = DB::table('employee_payroll')
            ->join('employee_salary', 'employee_salary.id', 'employee_payroll.employee_salary_id')
            ->join('contacts as employee', 'employee_salary.contact_id', 'employee.id')
            ->where('employee.is_trash', 0)
            ->where('employee.type', 5)
            ->leftjoin('enum_department_types', 'enum_department_types.id', 'employee.employee_department_id')
            ->leftjoin('enum_employee_types', 'enum_employee_types.id', 'employee.employee_designation_id')
            ->where('employee_payroll.academic_year_id', $academicYearId)
            ->where('employee_payroll.month_id', $monthId);
        if ($paymentType) {
            $data = $data->where('employee.payment_type', $paymentType);
        }
        $data = $data->select('enum_department_types.name as department_name', 'enum_employee_types.name as designation_name', 'employee.full_name', 'employee_payroll.*', 'employee_salary.gross_salary', 'employee_salary.basic_salary', 'employee_salary.deduction_salary', 'employee_payroll.allowance_salary', 'employee.bank_account_number')->get();

        return view('Stuff::payroll.report', compact('department', 'designation', 'academicYearId', 'monthId', 'months', 'academic_year_list', 'deduction_list', 'allowance_list', 'data', 'page', 'month_name', 'academic_year'));
    }

    // Employee Payroll Filter
    public function employeePayrollFilter(Request $request)
    {
        $url = 'employee-payroll-report/{academicYearId}/{monthId}/{paymentType}';
        $url = str_replace('{academicYearId}', $request->academic_year_id, $url);
        $url = str_replace('{monthId}', $request->month_id, $url);
        $url = str_replace('{paymentType}', $request->payment_type, $url);
        return redirect($url);
    }

}
