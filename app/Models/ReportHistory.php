<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportHistory extends Model
{
    protected $fillable = [
        'report_type',
        'start_date',
        'end_date',
        'format',
        'filter',
        'file_name',
        'file_path',
        'record_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function getReportTypeNameAttribute()
    {
        $types = [
            'doctors' => 'Laporan Dokter',
            'appointments' => 'Laporan Appointment',
            'revenue' => 'Laporan Pendapatan'
        ];

        return $types[$this->report_type] ?? $this->report_type;
    }
}
