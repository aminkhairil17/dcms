<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Seeder untuk menambahkan permission akses data berdasarkan company.
 *
 * Permission:
 *  - view_own_company_data  → user bisa melihat semua data dari company sendiri
 *  - view_all_companies_data → user bisa melihat data dari SEMUA company
 *
 * Jalankan dengan:
 *   php artisan db:seed --class=CompanyDataPermissionsSeeder
 */
class CompanyDataPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'web';

        $permissions = [
            'view_own_company_data',
            'view_all_companies_data',
            'direct_approve_document',
            'access_reminder_hub',
            'send_mandatory_read_reminder',
            'create_personal_reminder',
            'create_own_reminder',
            'send_meeting_reminder',
            'send_expiry_reminder',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name'       => $permissionName,
                'guard_name' => $guardName,
            ]);
        }

        $this->command->info('✅ Permission pengingat (reminders) berhasil dibuat.');

        // super_admin → bisa akses semua permission
        $superAdmin = Role::where('name', 'super_admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all());
            $this->command->info('✅ All permissions diberikan ke role super_admin.');
        }

        // direktur → bisa lihat data company sendiri, ACC dokumen langsung, & kelola semua pengingat
        $direktur = Role::where('name', 'direktur')->first();
        if ($direktur) {
            $direktur->givePermissionTo([
                'view_own_company_data',
                'direct_approve_document',
                'access_reminder_hub',
                'send_mandatory_read_reminder',
                'create_personal_reminder',
                'create_own_reminder',
                'send_meeting_reminder',
                'send_expiry_reminder',
            ]);
            $this->command->info('✅ Permissions diberikan ke role direktur.');
        }

        // kabid → kelola pengingat
        $kabid = Role::where('name', 'kabid')->first();
        if ($kabid) {
            $kabid->givePermissionTo([
                'access_reminder_hub',
                'send_mandatory_read_reminder',
                'create_personal_reminder',
                'create_own_reminder',
                'send_meeting_reminder',
                'send_expiry_reminder',
            ]);
            $this->command->info('✅ Permissions pengingat diberikan ke role kabid.');
        }

        // manager → kelola pengingat
        $manager = Role::where('name', 'manager')->first();
        if ($manager) {
            $manager->givePermissionTo([
                'access_reminder_hub',
                'send_mandatory_read_reminder',
                'create_personal_reminder',
                'create_own_reminder',
                'send_meeting_reminder',
                'send_expiry_reminder',
            ]);
            $this->command->info('✅ Permissions pengingat diberikan ke role manager.');
        }

        // staff → akses pengingat pribadi
        $staff = Role::where('name', 'staff')->first();
        if ($staff) {
            $staff->givePermissionTo([
                'access_reminder_hub',
                'create_personal_reminder',
                'create_own_reminder',
            ]);
            $this->command->info('✅ Permission pengingat pribadi diberikan ke role staff.');
        }

        $this->command->info('🎉 Seeder CompanyDataPermissions selesai!');
    }
}
