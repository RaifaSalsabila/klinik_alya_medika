<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DoctorControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_doctors_list()
    {
        $this->actingAs($this->admin);

        $doctors = Doctor::factory()->count(3)->create();

        $response = $this->get(route('admin.doctors'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.doctors');
        $response->assertViewHas('doctors');
    }

    #[Test]
    public function admin_can_create_doctor()
    {
        $this->actingAs($this->admin);

        $user = \App\Models\User::factory()->create(['role' => 'dokter']);

        $doctorData = [
            'user_id' => $user->id,
            'specialty' => 'Umum',
            'days' => ['Senin', 'Rabu', 'Jumat'],
            'start_time' => '08:00',
            'end_time' => '16:00',
        ];

        $response = $this->post(route('admin.doctors.store'), $doctorData);

        $response->assertRedirect(route('admin.doctors'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('doctors', [
            'user_id' => $user->id,
            'specialty' => 'Umum',
            'schedule' => json_encode([
                'Senin' => ['start' => '08:00', 'end' => '16:00'],
                'Rabu' => ['start' => '08:00', 'end' => '16:00'],
                'Jumat' => ['start' => '08:00', 'end' => '16:00']
            ]),
            'is_active' => true,
        ]);
    }

    #[Test]
    public function admin_can_view_doctor_detail()
    {
        $this->actingAs($this->admin);

        $doctor = Doctor::factory()->create();

        $response = $this->get(route('admin.doctors.show', $doctor));

        $response->assertStatus(200);
        $response->assertViewIs('admin.doctor-detail');
        $response->assertViewHas('doctor');
    }

    #[Test]
    public function admin_can_update_doctor()
    {
        $this->actingAs($this->admin);

        $doctor = Doctor::factory()->create();

        $updateData = [
            'name' => 'Dr. Updated Doctor',
            'specialty' => 'Spesialis',
            'days' => ['Senin', 'Rabu', 'Jumat'],
            'start_time' => '09:00',
            'end_time' => '17:00',
            'phone' => '081234567891',
            'email' => 'updated@test.com',
            'is_active' => '1',
        ];

        $response = $this->put(route('admin.doctors.update', $doctor), $updateData);

        $response->assertRedirect(route('admin.doctors'));
        $response->assertSessionHas('success');

        $doctor->refresh();

        $this->assertEquals('Dr. Updated Doctor', $doctor->name);
        $this->assertEquals('Spesialis', $doctor->specialty);
        $this->assertTrue($doctor->is_active);
    }

    #[Test]
    public function admin_can_delete_doctor()
    {
        $this->actingAs($this->admin);

        $doctor = Doctor::factory()->create();

        $response = $this->delete(route('admin.doctors.delete', $doctor));

        $response->assertRedirect(route('admin.doctors'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('doctors', ['id' => $doctor->id]);
    }

    #[Test]
    public function admin_can_get_doctor_edit_data()
    {
        $this->actingAs($this->admin);

        $doctor = Doctor::factory()->create();

        $response = $this->get(route('admin.doctors.edit', $doctor));

        $response->assertStatus(200);
        $response->assertJson($doctor->toArray());
    }

    #[Test]
    public function doctor_creation_requires_valid_data()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.doctors.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['user_id', 'specialty', 'days', 'start_time', 'end_time']);
    }

    #[Test]
    public function doctor_user_id_must_be_unique()
    {
        $this->actingAs($this->admin);

        $existingUser = \App\Models\User::factory()->create(['role' => 'dokter']);
        $existingDoctor = Doctor::factory()->create(['user_id' => $existingUser->id]);

        $newUser = \App\Models\User::factory()->create(['role' => 'dokter']);

        $doctorData = [
            'user_id' => $existingUser->id, // Duplicate user_id
            'specialty' => 'Umum',
            'days' => ['Senin', 'Rabu', 'Jumat'],
            'start_time' => '08:00',
            'end_time' => '16:00',
        ];

        $response = $this->post(route('admin.doctors.store'), $doctorData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('user_id');
    }

    #[Test]
    public function doctor_update_user_id_must_be_unique_excluding_current()
    {
        $this->actingAs($this->admin);

        $user1 = \App\Models\User::factory()->create(['role' => 'dokter']);
        $user2 = \App\Models\User::factory()->create(['role' => 'dokter']);
        $doctor1 = Doctor::factory()->create(['user_id' => $user1->id]);
        $doctor2 = Doctor::factory()->create(['user_id' => $user2->id]);

        $updateData = [
            'name' => 'Dr. Updated Doctor',
            'specialty' => 'Spesialis',
            'days' => ['Senin', 'Rabu', 'Jumat'],
            'start_time' => '09:00',
            'end_time' => '17:00',
            'phone' => '081234567891',
            'is_active' => '1',
        ];

        $response = $this->put(route('admin.doctors.update', $doctor1), $updateData);

        $response->assertRedirect(route('admin.doctors'));
        $response->assertSessionHas('success');
    }

    #[Test]
    public function unauthenticated_user_cannot_access_doctor_routes()
    {
        $doctor = Doctor::factory()->create();

        $this->get(route('admin.doctors'))->assertRedirect(route('login'));
        $this->post(route('admin.doctors.store'), [])->assertRedirect(route('login'));
        $this->get(route('admin.doctors.show', $doctor))->assertRedirect(route('login'));
        $this->put(route('admin.doctors.update', $doctor), [])->assertRedirect(route('login'));
        $this->delete(route('admin.doctors.delete', $doctor))->assertRedirect(route('login'));
    }
}
