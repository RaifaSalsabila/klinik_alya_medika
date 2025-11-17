<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'nik',
        'role',
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi dengan appointments sebagai patient
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // Relasi dengan medical records sebagai patient
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }

    // Relasi dengan prescriptions sebagai patient
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    // Relasi dengan referral letters sebagai patient
    public function referralLetters()
    {
        return $this->hasMany(ReferralLetter::class, 'patient_id');
    }

    // Relasi dengan invoices sebagai patient
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id');
    }

    // Relasi dengan doctor schedule
    public function doctorSchedule()
    {
        return $this->hasOne(Doctor::class);
    }

    // Method untuk mengecek apakah user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method untuk mengecek apakah user adalah pasien
    public function isPatient()
    {
        return $this->role === 'pasien';
    }

    // Method untuk mengecek apakah user adalah dokter
    public function isDokter()
    {
        return $this->role === 'dokter';
    }
}
