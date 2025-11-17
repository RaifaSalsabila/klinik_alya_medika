<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Invoice;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_an_invoice()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $medicalRecord = MedicalRecord::factory()->create(['patient_id' => $patient->id]);

        $invoiceData = [
            'patient_id' => $patient->id,
            'medical_record_id' => $medicalRecord->id,
            'consultation_fee' => 50000,
            'medication_fee' => 25000,
            'treatment_fee' => 75000,
            'other_fee' => 10000,
            'total_amount' => 160000,
            'status' => 'Belum Lunas',
        ];

        $invoice = Invoice::create($invoiceData);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(160000, $invoice->total_amount);
        $this->assertEquals('Belum Lunas', $invoice->status);
    }

    #[Test]
    public function it_belongs_to_a_patient()
    {
        $invoice = Invoice::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $invoice->patient());
        $this->assertInstanceOf(User::class, $invoice->patient);
    }

    #[Test]
    public function it_belongs_to_a_medical_record()
    {
        $invoice = Invoice::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $invoice->medicalRecord());
        $this->assertInstanceOf(MedicalRecord::class, $invoice->medicalRecord);
    }

    #[Test]
    public function it_can_calculate_total_automatically()
    {
        $invoice = new Invoice();
        $invoice->consultation_fee = 50000;
        $invoice->medication_fee = 25000;
        $invoice->treatment_fee = 75000;
        $invoice->other_fee = 10000;

        $total = $invoice->calculateTotal();

        $this->assertEquals(160000, $total);
        $this->assertEquals(160000, $invoice->total_amount);
    }

    #[Test]
    public function it_can_update_status()
    {
        $invoice = Invoice::factory()->create(['status' => 'Belum Lunas']);

        $invoice->update(['status' => 'Lunas']);

        $this->assertEquals('Lunas', $invoice->fresh()->status);
    }

    #[Test]
    public function it_casts_fees_to_decimal()
    {
        $invoice = Invoice::factory()->create([
            'consultation_fee' => 50000.50,
            'medication_fee' => 25000.25,
            'treatment_fee' => 75000.75,
            'other_fee' => 10000.00,
            'total_amount' => 160000.50,
        ]);

        $this->assertIsFloat($invoice->consultation_fee);
        $this->assertIsFloat($invoice->medication_fee);
        $this->assertIsFloat($invoice->treatment_fee);
        $this->assertIsFloat($invoice->other_fee);
        $this->assertIsFloat($invoice->total_amount);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $invoice = new Invoice();
        $fillable = ['patient_id', 'medical_record_id', 'consultation_fee', 'medication_fee', 'treatment_fee', 'other_fee', 'total_amount', 'status'];

        $this->assertEquals($fillable, $invoice->getFillable());
    }

    #[Test]
    public function it_can_have_different_statuses()
    {
        $statuses = ['Belum Lunas', 'Lunas'];

        foreach ($statuses as $status) {
            $invoice = Invoice::factory()->create(['status' => $status]);
            $this->assertEquals($status, $invoice->status);
        }
    }

    #[Test]
    public function it_calculates_total_correctly_with_zero_fees()
    {
        $invoice = new Invoice();
        $invoice->consultation_fee = 0;
        $invoice->medication_fee = 0;
        $invoice->treatment_fee = 0;
        $invoice->other_fee = 0;

        $total = $invoice->calculateTotal();

        $this->assertEquals(0, $total);
    }

    #[Test]
    public function it_calculates_total_correctly_with_null_other_fee()
    {
        $invoice = new Invoice();
        $invoice->consultation_fee = 50000;
        $invoice->medication_fee = 25000;
        $invoice->treatment_fee = 75000;
        $invoice->other_fee = null;

        $total = $invoice->calculateTotal();

        $this->assertEquals(150000, $total);
    }
}
