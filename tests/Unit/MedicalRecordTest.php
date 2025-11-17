<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\ReferralLetter;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicalRecordTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_medical_record()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $doctor = Doctor::factory()->create();
        $appointment = Appointment::factory()->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        $recordData = [
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'complaint' => 'Sakit kepala',
            'diagnosis' => 'Migrain',
            'treatment' => 'Pemberian obat pereda nyeri',
            'service_type' => 'rawat_jalan',
            'prescription_data' => json_encode(['Paracetamol 500mg 3x1']),
            'notes' => 'Pasien dalam kondisi baik',
        ];

        $record = MedicalRecord::create($recordData);

        $this->assertInstanceOf(MedicalRecord::class, $record);
        $this->assertEquals('rawat_jalan', $record->service_type);
        $this->assertEquals('Migrain', $record->diagnosis);
    }

    #[Test]
    public function it_belongs_to_an_appointment()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $record->appointment());
        $this->assertInstanceOf(Appointment::class, $record->appointment);
    }

    #[Test]
    public function it_belongs_to_a_patient()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $record->patient());
        $this->assertInstanceOf(User::class, $record->patient);
    }

    #[Test]
    public function it_belongs_to_a_doctor()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $record->doctor());
        $this->assertInstanceOf(Doctor::class, $record->doctor);
    }

    #[Test]
    public function it_has_one_prescription()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $record->prescription());
    }

    #[Test]
    public function it_has_one_referral_letter()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $record->referralLetter());
    }

    #[Test]
    public function it_has_one_invoice()
    {
        $record = MedicalRecord::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $record->invoice());
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $record = new MedicalRecord();
        $fillable = ['appointment_id', 'patient_id', 'doctor_id', 'complaint', 'diagnosis', 'treatment', 'service_type', 'prescription_data', 'notes'];

        $this->assertEquals($fillable, $record->getFillable());
    }

    #[Test]
    public function it_can_have_different_service_types()
    {
        $types = ['rawat_jalan', 'rawat_inap'];

        foreach ($types as $type) {
            $record = MedicalRecord::factory()->create(['service_type' => $type]);
            $this->assertEquals($type, $record->service_type);
        }
    }

    #[Test]
    public function it_can_store_prescription_as_json()
    {
        $prescription = [
            ['name' => 'Paracetamol', 'dosage' => '500mg', 'quantity' => 10],
            ['name' => 'Amoxicillin', 'dosage' => '500mg', 'quantity' => 20],
        ];

        $record = MedicalRecord::factory()->create([
            'prescription_data' => json_encode($prescription),
        ]);

        $this->assertEquals($prescription, json_decode($record->prescription_data, true));
    }

    #[Test]
    public function it_can_be_filtered_by_service_type()
    {
        MedicalRecord::factory()->count(3)->create(['service_type' => 'rawat_jalan']);
        MedicalRecord::factory()->count(2)->create(['service_type' => 'rawat_inap']);

        $outpatientRecords = MedicalRecord::where('service_type', 'rawat_jalan')->get();
        $inpatientRecords = MedicalRecord::where('service_type', 'rawat_inap')->get();

        $this->assertCount(3, $outpatientRecords);
        $this->assertCount(2, $inpatientRecords);
    }
}
