<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $permissions = [
        'review_document',
        'approve_document_kabid',
        'approve_document_direktur',
    ];

    public function up(): void
    {
        $guardName = 'web';
        $now = now();

        // 1. Create permissions
        foreach ($this->permissions as $name) {
            $exists = DB::table('permissions')
                ->where('name', $name)
                ->where('guard_name', $guardName)
                ->exists();

            if (!$exists) {
                DB::table('permissions')->insert([
                    'name' => $name,
                    'guard_name' => $guardName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 2. Create kabid role if not exists
        $kabidRole = DB::table('roles')
            ->where('name', 'kabid')
            ->where('guard_name', $guardName)
            ->first();

        if (!$kabidRole) {
            $kabidRoleId = DB::table('roles')->insertGetId([
                'name' => 'kabid',
                'guard_name' => $guardName,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $kabidRoleId = $kabidRole->id;
        }

        // 3. Assign review + kabid approval permissions to kabid role
        $kabidPermissions = ['review_document', 'approve_document_kabid'];
        foreach ($kabidPermissions as $permName) {
            $permission = DB::table('permissions')
                ->where('name', $permName)
                ->where('guard_name', $guardName)
                ->first();

            if ($permission) {
                $alreadyAssigned = DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->where('role_id', $kabidRoleId)
                    ->exists();

                if (!$alreadyAssigned) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $kabidRoleId,
                    ]);
                }
            }
        }

        // 4. Assign review + direktur approval permissions to direktur role
        $direkturRole = DB::table('roles')
            ->where('name', 'direktur')
            ->where('guard_name', $guardName)
            ->first();

        if ($direkturRole) {
            $direkturPermissions = ['review_document', 'approve_document_direktur'];
            foreach ($direkturPermissions as $permName) {
                $permission = DB::table('permissions')
                    ->where('name', $permName)
                    ->where('guard_name', $guardName)
                    ->first();

                if ($permission) {
                    $alreadyAssigned = DB::table('role_has_permissions')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $direkturRole->id)
                        ->exists();

                    if (!$alreadyAssigned) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $direkturRole->id,
                        ]);
                    }
                }
            }
        }

        // 5. Assign all permissions to super_admin
        $superAdminRole = DB::table('roles')
            ->where('name', 'super_admin')
            ->where('guard_name', $guardName)
            ->first();

        if ($superAdminRole) {
            foreach ($this->permissions as $permName) {
                $permission = DB::table('permissions')
                    ->where('name', $permName)
                    ->where('guard_name', $guardName)
                    ->first();

                if ($permission) {
                    $alreadyAssigned = DB::table('role_has_permissions')
                        ->where('permission_id', $permission->id)
                        ->where('role_id', $superAdminRole->id)
                        ->exists();

                    if (!$alreadyAssigned) {
                        DB::table('role_has_permissions')->insert([
                            'permission_id' => $permission->id,
                            'role_id' => $superAdminRole->id,
                        ]);
                    }
                }
            }
        }

        // Reset permission cache
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
                DB::table('role_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();

                DB::table('model_has_permissions')
                    ->where('permission_id', $permission->id)
                    ->delete();

                DB::table('permissions')
                    ->where('id', $permission->id)
                    ->delete();
            }
        }

        // Don't delete kabid role in down() as it might have other uses

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }
};
