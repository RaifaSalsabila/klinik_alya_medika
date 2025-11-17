<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_invoices_list()
    {
        $this->actingAs($this->admin);

        $invoices = Invoice::factory()->count(3)->create();

        $response = $this->get(route('admin.invoices'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.invoices');
        $response->assertViewHas('invoices');
    }

    #[Test]
    public function admin_can_create_invoice()
    {
        $this->actingAs($this->admin);

        $medicalRecord = MedicalRecord::factory()->create();

        $invoiceData = [
            'medical_record_id' => $medicalRecord->id,
            'consultation_fee' => 50000,
            'medication_fee' => 25000,
            'treatment_fee' => 75000,
            'other_fee' => 10000,
        ];

        $response = $this->post(route('admin.invoices.store'), $invoiceData);

        $response->assertRedirect(route('admin.invoices'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'medical_record_id' => $medicalRecord->id,
            'patient_id' => $medicalRecord->patient_id,
            'consultation_fee' => 50000,
            'medication_fee' => 25000,
            'treatment_fee' => 75000,
            'other_fee' => 10000,
            'total_amount' => 160000, // 50000 + 25000 + 75000 + 10000
            'status' => 'Belum Lunas',
        ]);
    }

    #[Test]
    public function admin_can_update_invoice_status()
    {
        $this->actingAs($this->admin);

        $invoice = Invoice::factory()->create(['status' => 'Belum Lunas']);

        $response = $this->put(route('admin.invoices.status', $invoice), [
            'status' => 'Lunas',
        ]);

        $response->assertRedirect(route('admin.invoices'));
        $response->assertSessionHas('success');

        $invoice->refresh();
        $this->assertEquals('Lunas', $invoice->status);
    }

    #[Test]
    public function invoice_creation_calculates_total_correctly()
    {
        $this->actingAs($this->admin);

        $medicalRecord = MedicalRecord::factory()->create();

        $invoiceData = [
            'medical_record_id' => $medicalRecord->id,
            'consultation_fee' => 100000,
            'medication_fee' => 50000,
            'treatment_fee' => 75000,
            'other_fee' => 25000,
        ];

        $this->post(route('admin.invoices.store'), $invoiceData);

        $this->assertDatabaseHas('invoices', [
            'total_amount' => 250000, // 100000 + 50000 + 75000 + 25000
        ]);
    }

    #[Test]
    public function invoice_creation_handles_null_other_fee()
    {
        $this->actingAs($this->admin);

        $medicalRecord = MedicalRecord::factory()->create();

        $invoiceData = [
            'medical_record_id' => $medicalRecord->id,
            'consultation_fee' => 50000,
            'medication_fee' => 25000,
            'treatment_fee' => 75000,
            // other_fee is null
        ];

        $this->post(route('admin.invoices.store'), $invoiceData);

        $this->assertDatabaseHas('invoices', [
            'total_amount' => 150000, // 50000 + 25000 + 75000 + 0
            'other_fee' => 0,
        ]);
    }

    #[Test]
    public function invoice_creation_validation()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.invoices.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'medical_record_id',
            'consultation_fee',
            'medication_fee',
            'treatment_fee'
        ]);
    }

    #[Test]
    public function invoice_status_update_validation()
    {
        $this->actingAs($this->admin);

        $invoice = Invoice::factory()->create();

        $response = $this->put(route('admin.invoices.status', $invoice), [
            'status' => 'Invalid Status',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('status');
    }

    #[Test]
    public function invoice_status_can_be_updated_to_valid_values()
    {
        $this->actingAs($this->admin);

        $validStatuses = ['Belum Lunas', 'Lunas'];

        foreach ($validStatuses as $status) {
            $invoice = Invoice::factory()->create(['status' => 'Belum Lunas']);

            $response = $this->put(route('admin.invoices.status', $invoice), [
                'status' => $status,
            ]);

            $response->assertRedirect(route('admin.invoices'));
            $response->assertSessionHas('success');

            $invoice->refresh();
            $this->assertEquals($status, $invoice->status);
        }
    }

    #[Test]
    public function unauthenticated_user_cannot_access_invoice_routes()
    {
        $invoice = Invoice::factory()->create();
        $medicalRecord = MedicalRecord::factory()->create();

        $this->get(route('admin.invoices'))->assertRedirect(route('login'));
        $this->post(route('admin.invoices.store'), [])->assertRedirect(route('login'));
        $this->put(route('admin.invoices.status', $invoice), [])->assertRedirect(route('login'));
    }
}
