<?php

namespace App\Modules\Student\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdmissionReportController extends Controller
{
    public function admissionReportIndex(Request $request)
    {

        $classList = DB::table('classes')->where('is_trash', '0')->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->pluck('name', 'id')->toArray();
        $academicYearList = DB::table('academic_years')->where('is_trash', '0')->latest('id')->pluck('year', 'id')->toArray();
        $academicYear = DB::table('academic_years')->where('is_trash', '0')->where('id', $request->academic_year_id)->first();
        $className = DB::table('classes')->where('is_trash',0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->get();
        $studentArr = [];
        if($request->search == 'true'){
           $classId = json_decode($request->class_id);
            $studentInfo = DB::table('contacts')->where('contacts.is_trash',0)
            ->join('contact_academics','contacts.id','contact_academics.contact_id')
            ->select('contacts.type','contacts.gender','contact_academics.class_id','contact_academics.academic_year_id','contact_academics.admission_type','contact_academics.admission_date','contact_academics.shift_id')
            ->where('contact_academics.academic_year_id',$request->academic_year_id)
            ->where('contact_academics.status','active')
            ->where('contacts.status','active');

            if(!empty($classId)){
                $studentInfo = $studentInfo->whereIn('contact_academics.class_id',$classId);
            }
            $studentInfo = $studentInfo->get();

            if(!$studentInfo->isEmpty()){
                foreach ($studentInfo as $key => $value) {
                    if($value->admission_type == 1 && $value->gender == 'male'){
                    $studentArr[$value->class_id]['boy_new'] = !empty($studentArr[$value->class_id]['boy_new']) ? $studentArr[$value->class_id]['boy_new'] : 0;
                    $studentArr[$value->class_id]['boy_new'] += 1;
                    }
                    if($value->admission_type == 2 && $value->gender == 'male'){
                    $studentArr[$value->class_id]['boy_old'] = !empty($studentArr[$value->class_id]['boy_old']) ? $studentArr[$value->class_id]['boy_old'] : 0;
                    $studentArr[$value->class_id]['boy_old'] += 1;
                    }
                    if($value->admission_type == 1 && $value->gender == 'female'){
                    $studentArr[$value->class_id]['girl_new'] = !empty($studentArr[$value->class_id]['girl_new']) ? $studentArr[$value->class_id]['girl_new'] : 0;
                    $studentArr[$value->class_id]['girl_new'] += 1;
                    }
                    if($value->admission_type == 2 && $value->gender == 'female'){
                    $studentArr[$value->class_id]['girl_old'] = !empty($studentArr[$value->class_id]['girl_old']) ? $studentArr[$value->class_id]['girl_old'] : 0;
                    $studentArr[$value->class_id]['girl_old'] += 1;
                    }
                    if($value->admission_type == 1 && $value->admission_date == date('Y-m-d') && $value->gender == 'male'){
                    $studentArr['today_total_boy_new'] = !empty($studentArr['today_total_boy_new']) ? $studentArr['today_total_boy_new'] : 0;
                    $studentArr['today_total_boy_new'] += 1;
                    }
                    if($value->admission_type == 1 && $value->admission_date == date('Y-m-d') && $value->gender == 'female'){
                    $studentArr['today_total_girl_new'] = !empty($studentArr['today_total_girl_new']) ? $studentArr['today_total_girl_new'] : 0;
                    $studentArr['today_total_girl_new'] += 1;
                    }
                    if($value->admission_type == 2 && $value->admission_date == date('Y-m-d') && $value->gender == 'male'){
                    $studentArr['today_total_boy_old'] = !empty($studentArr['today_total_boy_old']) ? $studentArr['today_total_boy_old'] : 0;
                    $studentArr['today_total_boy_old'] += 1;
                    }
                    if($value->admission_type == 2 && $value->admission_date == date('Y-m-d') && $value->gender == 'female'){
                    $studentArr['today_total_girl_old'] = !empty($studentArr['today_total_girl_old']) ? $studentArr['today_total_girl_old'] : 0;
                    $studentArr['today_total_girl_old'] += 1;
                    }
                    
                    $studentArr['total_today'] = !empty($studentArr['total_today']) ? $studentArr['total_today'] : 0;
                    $studentArr['total_today'] = ( !empty($studentArr['today_total_boy_new']) ? $studentArr['today_total_boy_new'] : 0 ) + ( !empty($studentArr['today_total_girl_new']) ? $studentArr['today_total_girl_new'] : 0 ) 
                                                + ( !empty($studentArr['today_total_boy_old']) ? $studentArr['today_total_boy_old'] : 0 ) + (!empty($studentArr['today_total_girl_old']) ? $studentArr['today_total_girl_old'] : 0 );
                   
                    $studentArr[$value->class_id]['total_old'] = !empty($studentArr[$value->class_id]['total_old']) ? $studentArr[$value->class_id]['total_old'] : 0;
                    $studentArr[$value->class_id]['total_old'] = ( !empty($studentArr[$value->class_id]['boy_old']) ? $studentArr[$value->class_id]['boy_old'] : 0 ) + ( !empty($studentArr[$value->class_id]['girl_old']) ? $studentArr[$value->class_id]['girl_old'] : 0 );
                   
                    $studentArr[$value->class_id]['total_new'] = !empty($studentArr[$value->class_id]['total_new']) ? $studentArr[$value->class_id]['total_new'] : 0;
                    $studentArr[$value->class_id]['total_new'] = ( !empty($studentArr[$value->class_id]['boy_new']) ? $studentArr[$value->class_id]['boy_new'] : 0 ) + ( !empty($studentArr[$value->class_id]['girl_new']) ? $studentArr[$value->class_id]['girl_new'] : 0 );
                   
                    $studentArr[$value->class_id]['total_boy'] = !empty($studentArr[$value->class_id]['total_boy']) ? $studentArr[$value->class_id]['total_boy'] : 0;
                    $studentArr[$value->class_id]['total_boy'] = ( !empty($studentArr[$value->class_id]['boy_new']) ? $studentArr[$value->class_id]['boy_new'] : 0 ) + ( !empty($studentArr[$value->class_id]['boy_old']) ? $studentArr[$value->class_id]['boy_old'] : 0 );
                    
                    $studentArr[$value->class_id]['total_girl'] = !empty($studentArr[$value->class_id]['total_girl']) ? $studentArr[$value->class_id]['total_girl'] : 0;
                    $studentArr[$value->class_id]['total_girl'] = ( !empty($studentArr[$value->class_id]['girl_new']) ? $studentArr[$value->class_id]['girl_new'] : 0 ) + ( !empty($studentArr[$value->class_id]['girl_old']) ? $studentArr[$value->class_id]['girl_old'] : 0 );
                    
                    $studentArr[$value->class_id]['total_grand'] = !empty($studentArr[$value->class_id]['total_grand']) ? $studentArr[$value->class_id]['total_grand'] : 0;
                    $studentArr[$value->class_id]['total_grand'] = ( !empty($studentArr[$value->class_id]['total_boy']) ? $studentArr[$value->class_id]['total_boy'] : 0 ) + ( !empty($studentArr[$value->class_id]['total_girl']) ? $studentArr[$value->class_id]['total_girl'] : 0 );
                    
                    if($value->shift_id == 1 ){
                    $studentArr[$value->class_id]['dcf'] = !empty($studentArr[$value->class_id]['dcf']) ? $studentArr[$value->class_id]['dcf'] : 0;
                    $studentArr[$value->class_id]['dcf'] += 1;
                    }
                    if($value->shift_id == 2 ){
                    $studentArr[$value->class_id]['dc'] = !empty($studentArr[$value->class_id]['dc']) ? $studentArr[$value->class_id]['dc'] : 0;
                    $studentArr[$value->class_id]['dc'] += 1;
                    }
                    if($value->shift_id == 3 ){
                    $studentArr[$value->class_id]['ac'] = !empty($studentArr[$value->class_id]['ac']) ? $studentArr[$value->class_id]['ac'] : 0;
                    $studentArr[$value->class_id]['ac'] += 1;
                    }
                    if($value->shift_id == 4 ){
                    $studentArr[$value->class_id]['res'] = !empty($studentArr[$value->class_id]['res']) ? $studentArr[$value->class_id]['res'] : 0;
                    $studentArr[$value->class_id]['res'] += 1;
                    }
                   
     
                }
            }
        }
 
        return view('Student::admissionReport.admissionReport',compact('classList','className','academicYearList','studentArr','request','academicYear'));
    }

    // public function admissionReportCreate(Request $request)
    // {
    //     $classList=[];
    //     $className = DB::table('classes')->where('is_trash',0)->orderByRaw('ISNULL(classes.weight),classes.weight ASC')->get();
    //     $arrCount=[];
    //     $studentInfo = DB::table('contacts')->where('contacts.is_trash',0)
    //                 ->join('contact_academics','contacts.id','contact_academics.contact_id')
    //                 ->join('classes','contact_academics.class_id','classes.id')
    //                 ->where('contact_academics.academic_year_id',$request->YearId)
    //                 ->where('contact_academics.status','active')
    //                 ->where('contacts.status','active')->get();
        

    //     $view = view('Student::admissionReport.admissionCount',compact('arrCount','className'))->render();
    //     return response()->json(['html'=>$view]);
    // }

    public function admissionReportFilter(Request $request){
        $validatedData = $request->validate([
            'academic_year_id' => 'required',
            // 'class_id' => 'required|not_in:0',
        ]);

        $url = 'admission-report-index?search=true&academic_year_id='.$request->academic_year_id.'&class_id='.urlencode(json_encode($request->class_id));
        return redirect($url);
    }

}
