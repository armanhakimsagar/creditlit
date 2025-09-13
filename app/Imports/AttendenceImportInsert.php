<?php

namespace App\Imports;

use App\Modules\DataImport\Models\ImprAttendance;
use App\Modules\DataImport\Models\BatchTable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AttendenceImportInsert implements ToArray, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function array(array $array)
    {
        if (count($array) > 0) {
            // Convert the date format from M/D/Y to Y-M-D
            $array = array_map(function ($row) {
                $row['punch_date'] = Carbon::createFromFormat('m/d/Y', $row['punch_date'])->format('Y-m-d');
                return $row;
            }, $array);

            $batch = new BatchTable();
            $batch->name = null;
            $batch->type = 'attendance';
            $batch->save();

            ImprAttendance::insert($array);
        }
    }
}

