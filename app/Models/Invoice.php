<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_record_id',
        'consultation_fee',
        'medication_fee',
        'treatment_fee',
        'other_fee',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'consultation_fee' => 'float',
        'medication_fee' => 'float',
        'treatment_fee' => 'float',
        'other_fee' => 'float',
        'total_amount' => 'float'
    ];

    // Relasi dengan patient (user)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    // Relasi dengan medical record
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Method untuk menghitung total otomatis
    public function calculateTotal()
    {
        $this->total_amount = $this->consultation_fee + $this->medication_fee + $this->treatment_fee + $this->other_fee;
        return $this->total_amount;
    }
}
