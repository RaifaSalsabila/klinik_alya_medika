<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'complaint',
        'diagnosis',
        'treatment',
        'service_type',
        'prescription_data',
        'notes'
    ];

    // Relasi dengan appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

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

    // Relasi dengan prescription
    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    // Relasi dengan referral letter
    public function referralLetter()
    {
        return $this->hasOne(ReferralLetter::class);
    }

    // Relasi dengan invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
