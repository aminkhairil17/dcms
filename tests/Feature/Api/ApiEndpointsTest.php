<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Meeting;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Department $department;
    protected Unit $unit;
    protected DocumentCategory $docCategory;
    protected DocumentCategory $sopCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create(['name' => 'Syifa Global', 'code' => 'SGI']);
        $this->department = Department::create(['name' => 'IT & Software', 'code' => 'ITS', 'company_id' => $this->company->id]);
        $this->unit = Unit::create(['name' => 'Development', 'code' => 'DEV', 'department_id' => $this->department->id]);

        $this->user = User::create([
            'name' => 'Test Admin',
            'username' => 'testadmin',
            'email' => 'admin@syifa.com',
            'password' => bcrypt('password'),
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'is_active' => true,
        ]);

        $this->docCategory = DocumentCategory::create([
            'name' => 'Kebijakan Perusahaan',
            'prefix' => 'KBJ',
            'company_id' => $this->company->id,
            'is_active' => true,
        ]);

        $this->sopCategory = DocumentCategory::create([
            'name' => 'Standard Operating Procedure',
            'prefix' => 'SOP',
            'company_id' => $this->company->id,
            'is_active' => true,
        ]);
    }

    public function test_can_list_documents()
    {
        Document::create([
            'title' => 'Dokumen HRD 2026',
            'code_number' => 'SGI-ITS-KBJ-001',
            'description' => 'Panduan kerja HRD',
            'file_path' => 'documents/sample.pdf',
            'file_name' => 'sample.pdf',
            'file_size' => '1024',
            'version' => '1.0',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'category_id' => $this->docCategory->id,
            'user_id' => $this->user->id,
            'status' => Document::STATUS_APPROVED,
        ]);

        $response = $this->getJson('/api/v1/documents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'code_number',
                        'version',
                        'status',
                    ]
                ],
                'meta' => ['page', 'limit', 'totalItems', 'totalPages']
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.totalItems', 1);
    }

    public function test_can_get_single_document_detail()
    {
        $doc = Document::create([
            'title' => 'Dokumen Spesifik',
            'code_number' => 'SGI-ITS-KBJ-002',
            'description' => 'Deskripsi detail',
            'file_path' => 'documents/detail.pdf',
            'file_name' => 'detail.pdf',
            'file_size' => '2048',
            'version' => '1.1',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'category_id' => $this->docCategory->id,
            'user_id' => $this->user->id,
            'status' => Document::STATUS_APPROVED,
        ]);

        $response = $this->getJson('/api/v1/documents/' . $doc->id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $doc->id)
            ->assertJsonPath('data.title', 'Dokumen Spesifik');
    }

    public function test_can_list_sops()
    {
        Document::create([
            'title' => 'SOP Deployment Server',
            'code_number' => 'SGI-ITS-SOP-001',
            'content' => "1. Persiapkan server\n2. Run deployment script",
            'file_path' => 'documents/sop.pdf',
            'file_name' => 'sop.pdf',
            'file_size' => '512',
            'version' => '1.0',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'category_id' => $this->sopCategory->id,
            'user_id' => $this->user->id,
            'status' => Document::STATUS_APPROVED,
        ]);

        $response = $this->getJson('/api/v1/sops');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.totalItems', 1);
    }

    public function test_can_get_sop_detail_with_procedures()
    {
        $sop = Document::create([
            'title' => 'SOP Backup Data',
            'code_number' => 'SGI-ITS-SOP-002',
            'content' => "1. Jalankan mysqldump\n2. Upload ke cloud",
            'file_path' => 'documents/backup.pdf',
            'file_name' => 'backup.pdf',
            'file_size' => '512',
            'version' => '1.0',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'category_id' => $this->sopCategory->id,
            'user_id' => $this->user->id,
            'status' => Document::STATUS_APPROVED,
        ]);

        $response = $this->getJson('/api/v1/sops/' . $sop->id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'SOP Backup Data')
            ->assertJsonPath('data.procedures.0.description', 'Jalankan mysqldump');
    }

    public function test_can_list_schedules()
    {
        Meeting::create([
            'title' => 'Rapat Evaluasi Mingguan',
            'doc_number' => 'NOT-2026-001',
            'agenda' => 'Pembahasan sprint backlog',
            'date_time' => now()->addDays(2),
            'location' => 'Ruang Rapat Utama',
            'status' => 'draft',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->getJson('/api/v1/schedules?status=upcoming');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.totalItems', 1);
    }

    public function test_can_get_schedule_detail()
    {
        $meeting = Meeting::create([
            'title' => 'Rapat Direksi',
            'doc_number' => 'NOT-2026-002',
            'agenda' => 'Evaluasi Q3',
            'date_time' => now()->addDays(1),
            'location' => 'Google Meet',
            'status' => 'draft',
            'company_id' => $this->company->id,
            'department_id' => $this->department->id,
            'unit_id' => $this->unit->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->getJson('/api/v1/schedules/' . $meeting->id);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $meeting->id)
            ->assertJsonPath('data.title', 'Rapat Direksi');
    }
}
