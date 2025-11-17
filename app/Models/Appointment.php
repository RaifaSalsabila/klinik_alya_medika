<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'queue_number',
        'status',
        'complaint'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i'
    ];

    // Relasi dengan patient (user)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relasi dengan doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Relasi dengan medical record
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    // Relasi dengan invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}


