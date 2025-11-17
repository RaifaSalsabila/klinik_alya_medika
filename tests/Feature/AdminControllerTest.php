<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_access_dashboard()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas(['stats', 'recent_appointments']);
    }

    #[Test]
    public function admin_can_logout()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login.show'));
        $this->assertGuest();
    }

    #[Test]
    public function dashboard_shows_correct_statistics()
    {
        $this->actingAs($this->admin);

        // Clean database to ensure accurate counts
        \App\Models\PrescriptionItem::query()->delete();
        \App\Models\Prescription::query()->delete();
        \App\Models\ReferralLetter::query()->delete();
        \App\Models\MedicalRecord::query()->delete();
        Invoice::query()->delete();
        Appointment::query()->delete();
        Doctor::query()->delete();
        User::query()->delete();

        // Recreate admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);

        // Create test data
        $patients = User::factory()->count(5)->patient()->create();
        $doctors = Doctor::factory()->count(3)->create(['is_active' => true]);
        Doctor::factory()->count(2)->create(['is_active' => false]);

        // Create appointments using existing patients to avoid creating extra patients
        $appointments = [];
        for ($i = 0; $i < 10; $i++) {
            $appointments[] = Appointment::factory()->create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'status' => 'Menunggu'
            ]);
        }

        $completedAppointments = [];
        for ($i = 0; $i < 7; $i++) {
            $completedAppointments[] = Appointment::factory()->create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'status' => 'Selesai'
            ]);
        }

        // Create medical records for completed appointments
        $completedAppointmentIds = collect($completedAppointments)->pluck('id');
        $medicalRecords = [];
        foreach ($completedAppointmentIds as $appointmentId) {
            $medicalRecords[] = \App\Models\MedicalRecord::factory()->create(['appointment_id' => $appointmentId]);
        }

        // Create invoices using existing medical records
        $paidInvoices = [];
        for ($i = 0; $i < 5; $i++) {
            $paidInvoices[] = Invoice::factory()->create([
                'medical_record_id' => $medicalRecords[$i]->id,
                'patient_id' => $medicalRecords[$i]->patient_id,
                'status' => 'Lunas',
                'total_amount' => 100000
            ]);
        }

        $unpaidInvoices = [];
        for ($i = 5; $i < 7; $i++) {
            $unpaidInvoices[] = Invoice::factory()->create([
                'medical_record_id' => $medicalRecords[$i]->id,
                'patient_id' => $medicalRecords[$i]->patient_id,
                'status' => 'Belum Lunas'
            ]);
        }

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $stats = $response->viewData('stats');

        $this->assertEquals(5, $stats['total_patients']); // 5 patients
        $this->assertEquals(5, $stats['total_doctors']);
        $this->assertEquals(3, $stats['active_doctors']);
        $this->assertEquals(17, $stats['total_appointments']); // 10 + 7
        $this->assertEquals(7, $stats['completed_appointments']);
        $this->assertEquals(500000, $stats['total_revenue']); // 5 * 100000
        $this->assertEquals(7, $stats['total_invoices']); // 5 + 2
        $this->assertEquals(5, $stats['paid_invoices']);
        $this->assertEquals(2, $stats['unpaid_invoices']);
    }

    #[Test]
    public function dashboard_shows_recent_appointments()
    {
        $this->actingAs($this->admin);

        $appointments = Appointment::factory()->count(6)->create();

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $recentAppointments = $response->viewData('recent_appointments');

        $this->assertCount(5, $recentAppointments); // Limited to 5
    }

    #[Test]
    public function unauthenticated_user_cannot_access_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function patient_cannot_access_admin_dashboard()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }
}
