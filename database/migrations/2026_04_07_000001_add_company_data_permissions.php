<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Migration ini menambahkan 2 permission baru untuk mengontrol akses data per company:
 *
 *  - view_own_company_data   → user bisa melihat semua data dari company sendiri
 *  - view_all_companies_data → user bisa melihat data dari SEMUA company (tanpa filter)
 *
 * Permission ini digunakan oleh scopeAccess() pada model Meeting & Document.
 * Setelah migrate, permission akan langsung muncul di panel Roles & Permissions Filament.
 */
return new class extends Migration
{
    private array $permissions = [
        'view_own_company_data',
        'view_all_companies_data',
    ];

    public function up(): void
    {
        $guardName = 'web';
        $now = now();

        foreach ($this->permissions as $name) {
            // firstOrCreate agar tidak duplikat jika seeder pernah dijalankan
            $exists = DB::table('permissions')
                ->where('name', $name)
                ->where('guard_name', $guardName)
                ->exists();

            if (! $exists) {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'guard_name' => $guardName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ── Assign default ke role super_admin (kedua permission) ──────────────
        $superAdminRole = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('guard_name', $guardName)
            ->first();

        if ($superAdminRole) {
            foreach ($this->permissions as $name) {
                $permission = DB::table('permissions')
                    ->where('name', $name)
                    ->where('guard_name', $guardName)
                    ->first();

                if ($permission) {
                    $alreadyAssigned = DB::table('role_has_permissions')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $superAdminRole->id)
                        ->exists();

                    if (! $alreadyAssigned) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $superAdminRole->id,
                        ]);
                    }
                }
            }
        }

        // ── Assign view_own_company_data ke role direktur ─────────────────────
        $direkturRole = DB::table('roles')
            ->where('name', 'direktur')
            ->where('guard_name', $guardName)
            ->first();

        if ($direkturRole) {
            $permission = DB::table('permissions')
                ->where('name', 'view_own_company_data')
                ->where('guard_name', $guardName)
                ->first();

            if ($permission) {
                $alreadyAssigned = DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->where('role_id', $direkturRole->id)
                    ->exists();

                if (! $alreadyAssigned) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $direkturRole->id,
                    ]);
                }
            }
        }

        // Reset Laravel permission cache
        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    public function down(): void
    {
        $guardName = 'web';

        foreach ($this->permissions as $name) {
            $permission = DB::table('permissions')
                ->where('name', $name)
                ->where('guard_name', $guardName)
                ->first();

            if ($permission) {
                // Hapus dari pivot role_has_permissions terlebih dahulu
                DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();

                // Hapus dari model_has_permissions
                DB::table('model_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();

                // Hapus permission
                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->delete();
            }
        }

        // Reset cache
        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
