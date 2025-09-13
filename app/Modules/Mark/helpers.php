<?php 
use Illuminate\Support\Facades\DB;

function getGrade($totalMksInPercentage)
{
    $grade = 'N/A';
    $graddingSystemInfo = DB::table('mark_grades')->where('is_trash',0)->where('status','active')
    ->select('start_mark','end_mark','grade')->get();

    if(!$graddingSystemInfo->isEmpty()){
        foreach ($graddingSystemInfo as $key => $value) {
            if($value->start_mark <= $totalMksInPercentage && $totalMksInPercentage <= $value->end_mark){
                $grade = $value->grade;
            }
        }
    }else{
        $grade = 'N/A';
    }

    return $grade;

}
?>