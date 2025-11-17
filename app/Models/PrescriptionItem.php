<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'medicine_name',
        'dosage',
        'quantity',
        'instructions'
    ];

    // Relasi dengan prescription
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}


