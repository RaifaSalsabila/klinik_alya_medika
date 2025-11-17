<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty',
        'schedule',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'schedule' => 'array'
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relasi dengan medical records
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
    
    // Accessor untuk mendapatkan email dari user
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }
    
    // Accessor untuk mendapatkan phone dari user
    public function getPhoneAttribute()
    {
        return $this->user ? $this->user->phone : null;
    }
    
    // Accessor untuk mendapatkan name dari user
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableSlots($date)
    {
        $dayName = $this->getDayName($date);

        if (!$this->schedule || !isset($this->schedule[$dayName])) {
            return [];
        }

        $daySchedule = $this->schedule[$dayName];
        $startTime = strtotime($daySchedule['start']);
        $endTime = strtotime($daySchedule['end']);

        $slots = [];
        $currentTime = $startTime;

        while ($currentTime < $endTime) {
            $timeString = date('H:i', $currentTime);
            $slots[] = $timeString;
            $currentTime = strtotime('+30 minutes', $currentTime);
        }

        // Remove slots that are already booked
        $bookedSlots = Appointment::where('doctor_id', $this->id)
            ->where('appointment_date', $date)
            ->whereIn('status', ['Menunggu', 'Diterima'])
            ->pluck('appointment_time')
            ->map(function ($time) {
                return date('H:i', strtotime($time));
            })
            ->toArray();

        return array_diff($slots, $bookedSlots);
    }

    /**
     * Check if doctor is available on a specific date and time
     */
    public function isAvailable($date, $time)
    {
        $dayName = $this->getDayName($date);

        if (!$this->schedule || !isset($this->schedule[$dayName])) {
            return false;
        }

        $daySchedule = $this->schedule[$dayName];
        $startTime = strtotime($daySchedule['start']);
        $endTime = strtotime($daySchedule['end']);
        $requestedTime = strtotime($time);

        // Check if time is within schedule
        if ($requestedTime < $startTime || $requestedTime >= $endTime) {
            return false;
        }

        // Check if slot is exactly 30 minutes aligned
        $minutes = date('i', $requestedTime);
        if ($minutes % 30 !== 0) {
            return false;
        }

        // Check if slot is already booked
        $existingAppointment = Appointment::where('doctor_id', $this->id)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereIn('status', ['Menunggu', 'Diterima'])
            ->exists();

        return !$existingAppointment;
    }

    /**
     * Get day name in Indonesian
     */
    private function getDayName($date)
    {
        $dayNames = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $englishDay = date('l', strtotime($date));
        return $dayNames[$englishDay] ?? $englishDay;
    }
}
