<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_appointments_list()
    {
        $this->actingAs($this->admin);

        $appointments = Appointment::factory()->count(3)->create();

        $response = $this->get(route('admin.appointments'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.appointments');
        $response->assertViewHas('appointments');
    }

    #[Test]
    public function admin_can_filter_appointments_by_status()
    {
        $this->actingAs($this->admin);

        Appointment::factory()->count(2)->create(['status' => 'Menunggu']);
        Appointment::factory()->count(3)->create(['status' => 'Selesai']);

        $response = $this->get(route('admin.appointments', ['status' => 'Menunggu']));

        $response->assertStatus(200);

        $appointments = $response->viewData('appointments');
        $this->assertCount(2, $appointments);
        $appointments->each(function ($appointment) {
            $this->assertEquals('Menunggu', $appointment->status);
        });
    }

    #[Test]
    public function admin_can_update_appointment_status()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create(['status' => 'Menunggu']);

        $response = $this->put(route('admin.appointments.status', $appointment), [
            'status' => 'Diterima',
        ]);

        $response->assertRedirect(route('admin.appointments'));
        $response->assertSessionHas('success');

        $appointment->refresh();
        $this->assertEquals('Diterima', $appointment->status);
    }

    #[Test]
    public function appointment_status_update_validation()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create();

        $response = $this->put(route('admin.appointments.status', $appointment), [
            'status' => 'Invalid Status',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('status');
    }

    #[Test]
    public function appointment_status_can_be_updated_to_valid_values()
    {
        $this->actingAs($this->admin);

        $validStatuses = ['Menunggu', 'Diterima', 'Selesai', 'Batal'];

        foreach ($validStatuses as $status) {
            $appointment = Appointment::factory()->create(['status' => 'Menunggu']);

            $response = $this->put(route('admin.appointments.status', $appointment), [
                'status' => $status,
            ]);

            $response->assertRedirect(route('admin.appointments'));
            $response->assertSessionHas('success');

            $appointment->refresh();
            $this->assertEquals($status, $appointment->status);
        }
    }

    #[Test]
    public function unauthenticated_user_cannot_access_appointment_routes()
    {
        $appointment = Appointment::factory()->create();

        $this->get(route('admin.appointments'))->assertRedirect(route('login'));
        $this->put(route('admin.appointments.status', $appointment), [])->assertRedirect(route('login'));
    }

    #[Test]
    public function patient_cannot_access_admin_appointment_routes()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $appointment = Appointment::factory()->create();

        $this->actingAs($patient);

        $this->get(route('admin.appointments'))->assertStatus(403);
        $this->put(route('admin.appointments.status', $appointment), [])->assertStatus(403);
    }
}
