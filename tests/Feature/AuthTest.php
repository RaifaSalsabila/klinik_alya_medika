<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_login_with_valid_credentials()
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login'), [
            'username' => $admin->username,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    #[Test]
    public function admin_cannot_login_with_invalid_credentials()
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('admin.login'), [
            'username' => $admin->username,
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    #[Test]
    public function patient_can_login_with_email()
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
    public function patient_can_login_with_phone()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('patient.login'), [
            'login' => $patient->phone, // Using phone as login
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertAuthenticatedAs($patient);
    }

    #[Test]
    public function patient_cannot_login_with_invalid_credentials()
    {
        $patient = User::factory()->create([
            'role' => 'pasien',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('patient.login'), [
            'login' => $patient->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
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

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function patient_cannot_register_with_duplicate_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com', // Duplicate email
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'address' => 'Jl. Test No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('patient.register'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function patient_cannot_register_with_duplicate_phone()
    {
        User::factory()->create(['phone' => '081234567890']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890', // Duplicate phone
            'nik' => '1234567890123456',
            'address' => 'Jl. Test No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('patient.register'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('phone');
    }

    #[Test]
    public function patient_cannot_register_with_duplicate_nik()
    {
        User::factory()->create(['nik' => '1234567890123456']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456', // Duplicate NIK
            'address' => 'Jl. Test No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('patient.register'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nik');
    }

    #[Test]
    public function patient_cannot_register_with_invalid_nik_length()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'nik' => '123456789', // Invalid NIK length
            'address' => 'Jl. Test No. 123',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('patient.register'), $userData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('nik');
    }

    #[Test]
    public function user_can_logout()
    {
        $user = User::factory()->create(['role' => 'pasien']);

        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    #[Test]
    public function admin_login_page_can_be_accessed()
    {
        $response = $this->get(route('admin.login.show'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.admin-login');
    }

    #[Test]
    public function patient_login_page_can_be_accessed()
    {
        $response = $this->get(route('patient.login.show'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.patient-login');
    }

    #[Test]
    public function patient_register_page_can_be_accessed()
    {
        $response = $this->get(route('patient.register.show'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.patient-register');
    }

    #[Test]
    public function home_page_can_be_accessed()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.home');
    }
}
