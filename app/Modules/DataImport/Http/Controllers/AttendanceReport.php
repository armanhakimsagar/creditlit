<?php

namespace App\Modules\DataImport\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceReport extends Controller
{
    // Daily Student Attendance Report
    public function studentDailyAttendance(Request $request)
    {

        $pageTitle = "Student Daily Attendance";
        $shift_list = ['' => 'Select Shift'] + DB::table('shifts')->where('is_trash', '0')->pluck('name', 'id')->toArray();
        $class_list = ['0' => 'Select Class'] + DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academic_year_list = DB::table('academic_years')->orderBy('id', 'DESC')->where('is_trash', '0')->pluck('year', 'id')->toArray();
        $data = '';
        $attendance_date = 0;
        $className = '';
        $yearName = '';
        $sectionName = '';
        $shiftName = '';
        $date = '';
        $day = '';

        // If anyone hit search button
        if ($request->search == 'true') {
            $attendance_date = $request->attendance_date;
            $shift = $request->shift_id;
            $section = $request->section_id;
            $className = DB::table('classes')->where('id', $request->class_id)->pluck('name')->first();
            $yearName = DB::table('academic_years')->where('id', $request->yearId)->pluck('year')->first();
            $sectionName = DB::table('sections')->where('id', $request->section_id)->pluck('name')->first();
            $shiftName = DB::table('shifts')->where('id', $request->shift_id)->pluck('name')->first();
            $date = (DateTime::createFromFormat('d-m-Y', $request->attendance_date))->format('jS F Y');
            $day = date('l', strtotime($request->attendance_date));
            $datam = DB::table('contacts as student')
                ->where('student.type', 1)
                ->where('student.is_trash', 0)
                ->leftjoin('contact_hierarchy as guardian_relation', 'student.id', 'guardian_relation.source_contactid')
                ->leftjoin('contacts as guardian', 'guardian_relation.target_contact', 'guardian.id')
                ->where('guardian.type', 4)
                ->leftjoin('contact_academics', 'student.id', 'contact_academics.contact_id')
                ->leftjoin('classes', 'contact_academics.class_id', 'classes.id')
                ->leftjoin('versions', 'contact_academics.version_id', 'versions.id')
                ->leftjoin('sections', 'contact_academics.section_id', 'sections.id')
                ->leftjoin('shifts', 'contact_academics.shift_id', 'shifts.id')
                ->leftjoin('transports', 'contact_academics.transport_id', 'transports.id')
                ->leftjoin('groups', 'contact_academics.group_id', 'groups.id')
                ->leftjoin('academic_years', 'contact_academics.academic_year_id', 'academic_years.id');

            if ($request->yearId) {
                $datam->where('contact_academics.academic_year_id', $request->yearId);
            }
            if ($request->class_id) {
                $datam->where('contact_academics.class_id', $request->class_id);
            }
            if ($request->section_id) {
                $datam->where('contact_academics.section_id', $request->section_id);
            }
            if ($request->shift_id) {
                $datam->where('contact_academics.shift_id', $request->shift_id);
            }
            $data = $datam->select('student.id', 'student.fingerprint_card_serial_no', 'student.full_name as student_name', 'student.cp_phone_no as student_phone', 'student.cp_email as student_email', 'student.status', 'student.gender', 'classes.name as class_name', 'contact_academics.class_roll', 'versions.name as version_name', 'sections.name as section_name', 'shifts.name as shift_name', 'transports.name as transport_name', 'groups.name as group_name', 'contact_academics.registration_no', 'academic_years.id as academic_year_id', 'academic_years.year as academic_year', 'student.contact_id', 'contact_academics.class_id', 'student.photo', 'guardian.cp_phone_no as guardian_phone')
                ->orderBy('contact_academics.class_roll', 'ASC')
                ->get();

            $attendanceData = DB::table('attendance')->where('punch_date', date('Y-m-d', strtotime($request->attendance_date)))->get();
            $weekendData = DB::table('weekend_configurations')->where('is_weekend', 1)->get();
            $holidayData = DB::table('holiday')->where('from_date', '<=', date('Y-m-d', strtotime($request->attendance_date)))->where('to_date', '>=', date('Y-m-d', strtotime($request->attendance_date)))->where('is_trash',0)->get();
            
            // Flag to track if any student is present on the weekend or holiday
            $weekendPresent = false;
            $holidayPresent = false;
            $isWeekend = $weekendData->contains('day_name', date('l', strtotime($request->attendance_date)));

            foreach ($data as $student) {
                $student->attendance_status = 'Absent';

                // Check if it's a holiday
                $matchingHoliday = $holidayData->first();
                if ($matchingHoliday) {
                    $student->attendance_status = 'Holiday';
                    $holidayPresent = true; // Set the flag to true if any student is on holiday
                } else {
                    // If it's not a holiday, check regular attendance and then weekend
                    $matchingAttendance = $attendanceData->where('card_no', $student->fingerprint_card_serial_no)->first();
                    if ($matchingAttendance) {
                        $student->attendance_status = 'Present';
                    } elseif ($isWeekend) {
                        $student->attendance_status = 'Weekend';
                        $weekendPresent = true; // Set the flag to true if any student is present on the weekend
                    }
                }
            }

            // If no student is present on the weekend and it's a weekend, set all students' attendance status to 'Weekend'
            if (!$weekendPresent && $isWeekend && !$holidayPresent) {
                foreach ($data as $student) {
                    $student->attendance_status = 'Weekend';
                }
            }

            // If no student is on holiday and it's a holiday, set all students' attendance status to 'Holiday'
            if (!$holidayPresent && $matchingHoliday) {
                foreach ($data as $student) {
                    $student->attendance_status = 'Holiday';
                }
            }

        }

        return view('DataImport::StudentDailyAttendance.index', compact('pageTitle', 'data', 'attendance_date', 'request', 'shift_list', 'class_list', 'academic_year_list', 'className', 'yearName', 'date', 'day', 'sectionName', 'shiftName'));
    }

    // Filter Daily Student Attendance
    public function studentDailyAttendanceFilter(Request $request)
    {

        $url = 'student-daily-attendance?search=true&attendance_date=' . $request->attendance_date . '&yearId=' . $request->academic_year_id . '&class_id=' . $request->class_id . '&shift_id=' . $request->shift_id . '&section_id=' . $request->section_id;
        return redirect($url);

    }
}
