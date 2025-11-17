<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_doctor()
    {
        $user = \App\Models\User::factory()->create(['role' => 'dokter']);

        $doctorData = [
            'user_id' => $user->id,
            'specialty' => 'Umum',
            'schedule' => [
                'Senin' => ['start' => '08:00', 'end' => '16:00'],
                'Rabu' => ['start' => '08:00', 'end' => '16:00']
            ],
            'is_active' => true,
        ];

        $doctor = Doctor::create($doctorData);

        $this->assertInstanceOf(Doctor::class, $doctor);
        $this->assertEquals($user->name, $doctor->name); // Via accessor
        $this->assertEquals('Umum', $doctor->specialty);
        $this->assertTrue($doctor->is_active);
    }

    #[Test]
    public function it_has_many_appointments()
    {
        $doctor = Doctor::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $doctor->appointments());
    }

    #[Test]
    public function it_has_many_medical_records()
    {
        $doctor = Doctor::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $doctor->medicalRecords());
    }

    #[Test]
    public function it_can_be_activated_and_deactivated()
    {
        $doctor = Doctor::factory()->create(['is_active' => true]);

        $this->assertTrue($doctor->is_active);

        $doctor->update(['is_active' => false]);
        $this->assertFalse($doctor->fresh()->is_active);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $doctor = new Doctor();
        $fillable = ['user_id', 'specialty', 'schedule', 'is_active'];

        $this->assertEquals($fillable, $doctor->getFillable());
    }

    #[Test]
    public function it_casts_is_active_to_boolean()
    {
        $doctor = Doctor::factory()->create(['is_active' => 1]);
        $this->assertTrue($doctor->is_active);
        $this->assertIsBool($doctor->is_active);

        $doctor->update(['is_active' => 0]);
        $this->assertFalse($doctor->fresh()->is_active);
    }

    #[Test]
    public function it_can_have_multiple_appointments()
    {
        $doctor = Doctor::factory()->create();
        $appointments = Appointment::factory()->count(3)->create(['doctor_id' => $doctor->id]);

        $this->assertCount(3, $doctor->appointments);
        $this->assertEquals($appointments->pluck('id')->sort(), $doctor->appointments->pluck('id')->sort());
    }

    #[Test]
    public function it_can_have_multiple_medical_records()
    {
        $doctor = Doctor::factory()->create();
        $appointment1 = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $appointment2 = Appointment::factory()->create(['doctor_id' => $doctor->id]);
        $medicalRecord1 = MedicalRecord::factory()->create([
            'appointment_id' => $appointment1->id,
            'doctor_id' => $doctor->id
        ]);
        $medicalRecord2 = MedicalRecord::factory()->create([
            'appointment_id' => $appointment2->id,
            'doctor_id' => $doctor->id
        ]);

        $this->assertCount(2, $doctor->medicalRecords);
        $this->assertEquals(collect([$medicalRecord1->id, $medicalRecord2->id])->sort(), $doctor->medicalRecords->pluck('id')->sort());
    }
}
