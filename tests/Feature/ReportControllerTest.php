<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\ReportHistory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create storage link if not exists
        if (!file_exists(public_path('storage'))) {
            $this->artisan('storage:link');
        }

        // Create reports directory
        Storage::disk('public')->makeDirectory('reports');

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    public function test_admin_can_access_reports_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertViewHas('recentReports');
    }

    public function test_generate_doctors_report()
    {
        $data = [
            'report_type' => 'doctors',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all'
        ];

        $response = $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('report_histories', [
            'report_type' => 'doctors',
            'format' => 'pdf'
        ]);

        // Check if file exists in storage
        $report = ReportHistory::where('report_type', 'doctors')->first();
        $this->assertTrue(Storage::disk('public')->exists($report->file_path));
    }

    public function test_generate_appointments_report()
    {
        $data = [
            'report_type' => 'appointments',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all'
        ];

        $response = $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('report_histories', [
            'report_type' => 'appointments',
            'format' => 'pdf'
        ]);

        // Check if file exists in storage
        $report = ReportHistory::where('report_type', 'appointments')->first();
        $this->assertTrue(Storage::disk('public')->exists($report->file_path));
    }

    public function test_generate_revenue_report()
    {
        $data = [
            'report_type' => 'revenue',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all'
        ];

        $response = $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('report_histories', [
            'report_type' => 'revenue',
            'format' => 'pdf'
        ]);

        // Check if file exists in storage
        $report = ReportHistory::where('report_type', 'revenue')->first();
        $this->assertTrue(Storage::disk('public')->exists($report->file_path));
    }

    public function test_download_existing_report()
    {
        // First generate a report
        $data = [
            'report_type' => 'doctors',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all'
        ];

        $this->actingAs($this->admin)->post('/admin/reports/generate', $data);
        $report = ReportHistory::where('report_type', 'doctors')->first();

        // Now try to download it
        $response = $this->actingAs($this->admin)->get('/admin/reports/download/' . $report->id);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename=' . $report->file_name);
    }

    public function test_download_nonexistent_report()
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports/download/999');

        $response->assertStatus(404);
    }

    public function test_download_report_with_missing_file()
    {
        // Create a report history entry but delete the file
        $report = ReportHistory::create([
            'report_type' => 'doctors',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all',
            'file_name' => 'test.pdf',
            'file_path' => 'reports/test.pdf',
            'record_count' => 0
        ]);

        // Delete the file if it exists
        Storage::disk('public')->delete($report->file_path);

        $response = $this->actingAs($this->admin)->get('/admin/reports/download/' . $report->id);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'File tidak ditemukan']);
    }

    public function test_generate_report_validation()
    {
        $data = [
            'report_type' => 'invalid',
            'start_date' => 'invalid-date',
            'end_date' => '2024-01-01',
            'format' => 'invalid',
            'filter' => 'invalid'
        ];

        $response = $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        $response->assertStatus(302); // Redirect back with validation errors
        $response->assertSessionHasErrors(['report_type', 'start_date', 'format']);
    }

    public function test_duplicate_report_prevention()
    {
        $data = [
            'report_type' => 'doctors',
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
            'format' => 'pdf',
            'filter' => 'all'
        ];

        // Generate first report
        $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        // Wait more than 2 seconds to avoid duplicate prevention
        sleep(3);

        // Try to generate the same report again
        $this->actingAs($this->admin)->post('/admin/reports/generate', $data);

        // Should have two reports in database since they are not considered duplicates
        $this->assertEquals(2, ReportHistory::where('report_type', 'doctors')->count());
    }
}
