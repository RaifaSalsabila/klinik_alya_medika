<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\ReferralLetter;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReferralLetterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_referral_letter()
    {
        $patient = User::factory()->create(['role' => 'pasien']);
        $medicalRecord = MedicalRecord::factory()->create(['patient_id' => $patient->id]);

        $referralData = [
            'medical_record_id' => $medicalRecord->id,
            'patient_id' => $patient->id,
            'referral_number' => 'REF000001',
            'referred_to' => 'RS Cipto Mangunkusumo',
            'reason' => 'Memerlukan perawatan intensif',
        ];

        $referral = ReferralLetter::create($referralData);

        $this->assertInstanceOf(ReferralLetter::class, $referral);
        $this->assertEquals('REF000001', $referral->referral_number);
        $this->assertEquals('RS Cipto Mangunkusumo', $referral->referred_to);
    }

    #[Test]
    public function it_belongs_to_a_medical_record()
    {
        $referral = ReferralLetter::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $referral->medicalRecord());
        $this->assertInstanceOf(MedicalRecord::class, $referral->medicalRecord);
    }

    #[Test]
    public function it_belongs_to_a_patient()
    {
        $referral = ReferralLetter::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $referral->patient());
        $this->assertInstanceOf(User::class, $referral->patient);
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $referral = new ReferralLetter();
        $fillable = ['medical_record_id', 'patient_id', 'referral_number', 'referred_to', 'reason'];

        $this->assertEquals($fillable, $referral->getFillable());
    }

    #[Test]
    public function it_can_store_various_referral_reasons()
    {
        $reasons = [
            'Memerlukan perawatan intensif',
            'Perlu konsultasi spesialis',
            'Memerlukan tindakan operasi',
            'Pemeriksaan lanjutan diperlukan',
        ];

        foreach ($reasons as $reason) {
            $referral = ReferralLetter::factory()->create(['reason' => $reason]);
            $this->assertEquals($reason, $referral->reason);
        }
    }

    #[Test]
    public function it_can_refer_to_different_hospitals()
    {
        $hospitals = [
            'RS Cipto Mangunkusumo',
            'RS Siloam Jakarta',
            'RS Pondok Indah',
            'RS Medistra',
        ];

        foreach ($hospitals as $hospital) {
            $referral = ReferralLetter::factory()->create(['referred_to' => $hospital]);
            $this->assertEquals($hospital, $referral->referred_to);
        }
    }

    #[Test]
    public function it_generates_unique_referral_numbers()
    {
        $referral1 = ReferralLetter::factory()->create();
        $referral2 = ReferralLetter::factory()->create();

        $this->assertNotEquals($referral1->referral_number, $referral2->referral_number);
    }
}
