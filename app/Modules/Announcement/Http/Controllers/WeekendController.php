<?php

namespace App\Modules\Announcement\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Announcement\Models\Weekend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class WeekendController extends Controller
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

    // To show all class data
    public function index(Request $request)
    {
        // Show permission check
        if (is_null($this->user) || !$this->user->can('holiday.index')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        if ($request->ajax()) {
            $data = DB::table('holiday')->where('is_trash', 0)
                ->orderByRaw('ISNULL(holiday.from_date),holiday.from_date ASC')
                ->get();
            return Datatables::of($data)
                ->startsWithSearch()
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action_btn = '<a href="' . route('holiday.edit', [$row->id]) . '" class="btn btn-outline-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit" data-id= "' . $row->id . '"><i class="fas fa-edit"></i></a> <a href="' . route('holiday.delete', [$row->id]) . '" class="btn btn-outline-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete" id= "delete"><i class="fas fa-trash"></i></a>';
                    return $action_btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('Announcement::holiday.index');
    }

    // To show Class create page
    public function create()
    {
        // Create Permission check
        if (is_null($this->user) || !$this->user->can('holiday.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        $selectedDays = DB::table('weekend_configurations')->where('is_weekend',1)->get();
        $pageTitle = "Add Holiday";
        return view("Announcement::weekend.create", compact('pageTitle','selectedDays'));
    }

    // To Store Class Data
    public function store(Request $request)
    {
        // Store Permission Check
        if (is_null($this->user) || !$this->user->can('holiday.create')) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        // Get the selected weekend days from the form
        $selectedDays = $request->input('weekend');

        try {
            // Clear the existing weekend days from the database
            Weekend::truncate();

            // Save the selected weekend days in the database
            foreach ($selectedDays as $day) {
                Weekend::create([
                    'day_name' => $day,
                    'is_weekend' => true,
                ]);
            }

            // Save the remaining non-selected days as non-weekend
            $allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $nonSelectedDays = array_diff($allDays, $selectedDays);

            foreach ($nonSelectedDays as $day) {
                Weekend::create([
                    'day_name' => $day,
                    'is_weekend' => false,
                ]);
            }

            Session::flash('success', "Weekend Set Successfully ");
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            return redirect()->back();
        }

    }

}
