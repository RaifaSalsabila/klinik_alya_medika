<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'patient_id',
        'referral_number',
        'referred_to',
        'reason'
    ];

    // Relasi dengan medical record
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Relasi dengan patient (user)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}


