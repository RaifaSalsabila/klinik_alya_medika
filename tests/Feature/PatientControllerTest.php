<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function patient_can_login_with_valid_credentials()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('patient.login'), [
            'login' => $patient->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertAuthenticatedAs($patient);
    }

    #[Test]
    public function patient_can_register_with_valid_data()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'address' => 'Jl. Test No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('patient.register'), $userData);

        $response->assertRedirect(route('patient.login'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'role' => 'pasien',
        ]);
    }

    #[Test]
    public function patient_can_access_dashboard()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);

        $response = $this->get(route('patient.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.dashboard');
        $response->assertViewHas(['user', 'appointments', 'medical_records', 'invoices', 'doctors']);
    }

    #[Test]
    public function patient_can_view_appointments()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $appointments = Appointment::factory()->count(3)->create(['patient_id' => $patient->id]);

        $this->actingAs($patient);

        $response = $this->get(route('patient.appointments'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.appointments');
        $response->assertViewHas('appointments');
    }

    #[Test]
    public function patient_can_create_appointment()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $doctor = Doctor::factory()->create([
            'is_active' => true,
            'schedule' => [
                'Senin' => ['start' => '08:00', 'end' => '16:00'],
                'Selasa' => ['start' => '08:00', 'end' => '16:00'],
                'Rabu' => ['start' => '08:00', 'end' => '16:00'],
                'Kamis' => ['start' => '08:00', 'end' => '16:00'],
                'Jumat' => ['start' => '08:00', 'end' => '16:00']
            ]
        ]);

        $this->actingAs($patient);

        // Get tomorrow's date and ensure it's a weekday (Monday to Friday)
        $tomorrow = now()->addDays(1);
        $dayOfWeek = $tomorrow->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

        // If tomorrow is Saturday or Sunday, add more days to get to Monday
        if ($dayOfWeek === 0) { // Sunday
            $tomorrow = $tomorrow->addDays(1); // Monday
        } elseif ($dayOfWeek === 6) { // Saturday
            $tomorrow = $tomorrow->addDays(2); // Monday
        }

        $appointmentData = [
            'doctor_id' => $doctor->id,
            'appointment_date' => $tomorrow->format('Y-m-d'),
            'appointment_time' => '10:00',
            'complaint' => 'Sakit kepala',
        ];

        $response = $this->post(route('patient.appointments.store'), $appointmentData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('appointments', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'complaint' => 'Sakit kepala',
            'status' => 'Menunggu',
        ]);
    }

    #[Test]
    public function patient_can_view_history()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $medicalRecords = MedicalRecord::factory()->count(2)->create(['patient_id' => $patient->id]);

        $this->actingAs($patient);

        $response = $this->get(route('patient.history'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.history');
        $response->assertViewHas('medical_records');
    }

    #[Test]
    public function patient_can_view_invoices()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $invoices = Invoice::factory()->count(2)->create(['patient_id' => $patient->id]);

        $this->actingAs($patient);

        $response = $this->get(route('patient.invoices'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.invoices');
        $response->assertViewHas('invoices');
    }

    #[Test]
    public function patient_can_view_profile()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);

        $response = $this->get(route('patient.profile'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.profile');
        $response->assertViewHas('user');
    }

    #[Test]
    public function patient_can_update_profile()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '081234567891',
            'address' => 'Updated Address',
        ];

        $response = $this->put(route('patient.profile.update'), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $patient->refresh();

        $this->assertEquals('Updated Name', $patient->name);
        $this->assertEquals('updated@example.com', $patient->email);
        $this->assertEquals('081234567891', $patient->phone);
        $this->assertEquals('Updated Address', $patient->address);
    }

    #[Test]
    public function patient_can_change_password()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'password' => Hash::make('oldpassword'),
        ]);

        $this->actingAs($patient);

        $passwordData = [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->put(route('patient.change-password'), $passwordData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $patient->refresh();
        $this->assertTrue(Hash::check('newpassword123', $patient->password));
    }

    #[Test]
    public function patient_can_logout()
    {
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($patient);

        $response = $this->post(route('patient.logout'));

        $response->assertRedirect(route('patient.login'));
        $this->assertGuest();
    }

    #[Test]
    public function patient_can_view_prescription()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);
        $medicalRecord = MedicalRecord::factory()->create([
            'appointment_id' => $appointment->id,
            'patient_id' => $patient->id,
            'service_type' => 'rawat_jalan',
            'prescription_data' => json_encode([
                ['name' => 'Paracetamol', 'dosage' => '500mg', 'quantity' => 10, 'instructions' => '3x1 sehari']
            ])
        ]);

        // Create a paid invoice
        Invoice::factory()->create([
            'medical_record_id' => $medicalRecord->id,
            'patient_id' => $patient->id,
            'status' => 'Lunas'
        ]);

        $this->actingAs($patient);

        $response = $this->get(route('patient.prescription.view', $medicalRecord->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'prescription' => [
                ['name' => 'Paracetamol', 'dosage' => '500mg', 'quantity' => 10, 'instructions' => '3x1 sehari']
            ]
        ]);
    }

    #[Test]
    public function patient_cannot_view_other_patients_prescription()
    {
        $patient1 = User::factory()->create(['role' => 'pasien']);
        $patient2 = User::factory()->create(['role' => 'pasien']);
        $appointment = Appointment::factory()->create(['patient_id' => $patient2->id]);
        $medicalRecord = MedicalRecord::factory()->create([
            'appointment_id' => $appointment->id,
            'patient_id' => $patient2->id,
            'service_type' => 'rawat_jalan',
            'prescription_data' => json_encode([['name' => 'Paracetamol', 'dosage' => '500mg']])
        ]);

        $this->actingAs($patient1);

        $response = $this->get(route('patient.prescription.view', $medicalRecord->id));

        $response->assertStatus(404);
        $response->assertJson([
            'success' => false,
            'message' => 'Rekam medis tidak ditemukan'
        ]);
    }

    #[Test]
    public function patient_can_view_doctor_schedule()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $doctors = Doctor::factory()->count(3)->create(['is_active' => true]);

        $this->actingAs($patient);

        $response = $this->get(route('patient.doctor-schedule'));

        $response->assertStatus(200);
        $response->assertViewIs('patient.doctor-schedule');
        $response->assertViewHas('doctors');
    }

    #[Test]
    public function unauthenticated_user_cannot_access_patient_routes()
    {
        $this->get(route('patient.dashboard'))->assertRedirect(route('login'));
        $this->get(route('patient.appointments'))->assertRedirect(route('login'));
        $this->get(route('patient.history'))->assertRedirect(route('login'));
        $this->get(route('patient.invoices'))->assertRedirect(route('login'));
        $this->get(route('patient.profile'))->assertRedirect(route('login'));
    }

    #[Test]
    public function admin_cannot_access_patient_routes()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        $this->get(route('patient.dashboard'))->assertStatus(403);
        $this->get(route('patient.appointments'))->assertStatus(403);
    }
}
