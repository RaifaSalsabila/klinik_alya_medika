<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ReferralLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MedicalRecordControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function admin_can_view_medical_records_list()
    {
        $this->actingAs($this->admin);

        $records = MedicalRecord::factory()->count(3)->create();

        $response = $this->get(route('admin.medical-records'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medical-records');
        $response->assertViewHas('medical_records');
    }

    #[Test]
    public function admin_can_view_medical_record_detail()
    {
        $this->actingAs($this->admin);

        $record = MedicalRecord::factory()->create();

        $response = $this->get(route('admin.medical-records.show', $record));

        $response->assertStatus(200);
        $response->assertViewIs('admin.medical-record-detail');
        $response->assertViewHas('record');
    }

    #[Test]
    public function admin_can_create_medical_record_for_outpatient()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create();

        $recordData = [
            'appointment_id' => $appointment->id,
            'complaint' => 'Sakit kepala dan demam',
            'diagnosis' => 'Infeksi saluran pernapasan atas',
            'treatment' => 'Pemberian antibiotik dan istirahat',
            'service_type' => 'rawat_jalan',
            'medicines' => [
                [
                    'name' => 'Paracetamol 500mg',
                    'dosage' => '500mg',
                    'quantity' => 10,
                    'instructions' => '3x1 sehari setelah makan'
                ],
                [
                    'name' => 'Amoxicillin 500mg',
                    'dosage' => '500mg',
                    'quantity' => 20,
                    'instructions' => '3x1 sehari selama 7 hari'
                ]
            ],
            'notes' => 'Pasien dalam kondisi stabil'
        ];

        $response = $this->post(route('admin.medical-records.store'), $recordData);

        $response->assertRedirect('http://localhost:8000/admin/medical-records');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('medical_records', [
            'appointment_id' => $appointment->id,
            'complaint' => 'Sakit kepala dan demam',
            'diagnosis' => 'Infeksi saluran pernapasan atas',
            'service_type' => 'rawat_jalan',
            'notes' => 'Pasien dalam kondisi stabil'
        ]);

        // Check if prescription and items were created
        $medicalRecord = MedicalRecord::with('prescription.prescriptionItems')->where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($medicalRecord->prescription);
        $this->assertCount(2, $medicalRecord->prescription->prescriptionItems);

        // Check if appointment status was updated
        $appointment->refresh();
        $this->assertEquals('Selesai', $appointment->status);
    }

    #[Test]
    public function admin_can_create_medical_record_for_inpatient()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create();

        $recordData = [
            'appointment_id' => $appointment->id,
            'complaint' => 'Nyeri dada berat',
            'diagnosis' => 'Infark miokard akut',
            'treatment' => 'Perawatan intensif dan monitoring jantung',
            'service_type' => 'rawat_inap',
            'referral_to' => 'RS Cipto Mangunkusumo',
            'referral_reason' => 'Memerlukan perawatan intensif dan monitoring 24 jam',
            'notes' => 'Pasien dalam kondisi kritis'
        ];

        $response = $this->post(route('admin.medical-records.store'), $recordData);

        $response->assertRedirect('http://localhost:8000/admin/medical-records');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('medical_records', [
            'appointment_id' => $appointment->id,
            'service_type' => 'rawat_inap',
            'notes' => 'Pasien dalam kondisi kritis'
        ]);

        // Check if referral letter was created
        $medicalRecord = MedicalRecord::where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($medicalRecord->referralLetter);
        $this->assertEquals('RS Cipto Mangunkusumo', $medicalRecord->referralLetter->referred_to);

        // Check if appointment status was updated
        $appointment->refresh();
        $this->assertEquals('Selesai', $appointment->status);
    }

    #[Test]
    public function medical_record_creation_requires_valid_data()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.medical-records.store'), []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['appointment_id', 'complaint', 'diagnosis', 'treatment', 'service_type']);
    }

    #[Test]
    public function outpatient_medical_record_requires_medicines()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create();

        $recordData = [
            'appointment_id' => $appointment->id,
            'complaint' => 'Sakit kepala',
            'diagnosis' => 'Migrain',
            'treatment' => 'Pemberian obat',
            'service_type' => 'rawat_jalan',
            // Missing medicines
            'notes' => 'Test notes'
        ];

        $response = $this->post(route('admin.medical-records.store'), $recordData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('medicines');
    }

    #[Test]
    public function inpatient_medical_record_requires_referral_data()
    {
        $this->actingAs($this->admin);

        $appointment = Appointment::factory()->create();

        $recordData = [
            'appointment_id' => $appointment->id,
            'complaint' => 'Nyeri dada',
            'diagnosis' => 'Angina',
            'treatment' => 'Perawatan intensif',
            'service_type' => 'rawat_inap',
            // Missing referral data
            'notes' => 'Test notes'
        ];

        $response = $this->post(route('admin.medical-records.store'), $recordData);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['referral_to', 'referral_reason']);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_medical_record_routes()
    {
        $record = MedicalRecord::factory()->create();

        $this->get(route('admin.medical-records'))->assertRedirect(route('login'));
        $this->get(route('admin.medical-records.show', $record))->assertRedirect(route('login'));
        $this->post(route('admin.medical-records.store'), [])->assertRedirect(route('login'));
    }
}
