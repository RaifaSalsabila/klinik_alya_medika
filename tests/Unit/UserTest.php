<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'role' => 'pasien',
            'address' => 'Jl. Test No. 123',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('pasien', $user->role);
    }

    #[Test]
    public function it_can_check_if_user_is_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($patient->isAdmin());
    }

    #[Test]
    public function it_can_check_if_user_is_patient()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $patient = User::factory()->create(['role' => 'pasien']);

        $this->assertFalse($admin->isPatient());
        $this->assertTrue($patient->isPatient());
    }

    #[Test]
    public function it_has_many_appointments()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->appointments());
    }

    #[Test]
    public function it_has_many_medical_records()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->medicalRecords());
    }

    #[Test]
    public function it_has_many_prescriptions()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->prescriptions());
    }

    #[Test]
    public function it_has_many_referral_letters()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->referralLetters());
    }

    #[Test]
    public function it_has_many_invoices()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $user->invoices());
    }

    #[Test]
    public function it_hashes_password_when_setting()
    {
        $user = new User();
        $user->password = 'plainpassword';

        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $user = new User();
        $fillable = ['name', 'username', 'email', 'phone', 'nik', 'role', 'address', 'password'];

        $this->assertEquals($fillable, $user->getFillable());
    }

    #[Test]
    public function it_has_hidden_attributes()
    {
        $user = new User();
        $hidden = ['password', 'remember_token'];

        $this->assertEquals($hidden, $user->getHidden());
    }
}
