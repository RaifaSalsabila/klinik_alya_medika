<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\User;
use App\Models\PrescriptionItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrescriptionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_prescription()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $medicalRecord = MedicalRecord::factory()->create(['patient_id' => $patient->id]);

        $prescriptionData = [
            'medical_record_id' => $medicalRecord->id,
            'patient_id' => $patient->id,
            'prescription_number' => 'RX000001',
        ];

        $prescription = Prescription::create($prescriptionData);

        $this->assertInstanceOf(Prescription::class, $prescription);
        $this->assertEquals('RX000001', $prescription->prescription_number);
    }

    #[Test]
    public function it_belongs_to_a_medical_record()
    {
        $prescription = Prescription::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $prescription->medicalRecord());
        $this->assertInstanceOf(MedicalRecord::class, $prescription->medicalRecord);
    }

    #[Test]
    public function it_belongs_to_a_patient()
    {
        $prescription = Prescription::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $prescription->patient());
        $this->assertInstanceOf(User::class, $prescription->patient);
    }

    #[Test]
    public function it_has_many_prescription_items()
    {
        $prescription = Prescription::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $prescription->prescriptionItems());
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $prescription = new Prescription();
        $fillable = ['medical_record_id', 'patient_id', 'prescription_number'];

        $this->assertEquals($fillable, $prescription->getFillable());
    }

    #[Test]
    public function it_can_have_multiple_prescription_items()
    {
        $prescription = Prescription::factory()->create();
        $items = PrescriptionItem::factory()->count(3)->create(['prescription_id' => $prescription->id]);

        $this->assertCount(3, $prescription->prescriptionItems);
        $this->assertEquals($items->pluck('id')->sort(), $prescription->prescriptionItems->pluck('id')->sort());
    }

    #[Test]
    public function it_generates_unique_prescription_numbers()
    {
        $prescription1 = Prescription::factory()->create();
        $prescription2 = Prescription::factory()->create();

        $this->assertNotEquals($prescription1->prescription_number, $prescription2->prescription_number);
    }
}
