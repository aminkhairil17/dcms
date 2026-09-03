<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SyncSuperAdminAccount extends Command
{
    protected $signature = 'users:sync-superadmin
                            {--password=1234 : Password untuk akun superadmin}
                            {--email=superadmin@dcms.local : Email akun superadmin}
                            {--name=Super Admin : Nama akun superadmin}';

    protected $description = 'Membuat atau menyinkronkan akun superadmin agar memiliki role super_admin dan seluruh permission.';

    public function handle(): int
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::query()->get();

        if ($permissions->isNotEmpty()) {
            $role->syncPermissions($permissions);
        }

        $user = User::query()->firstOrNew([
            'username' => 'superadmin',
        ]);

        if (! $user->exists && filled($this->option('email'))) {
            $existingByEmail = User::query()
                ->where('email', $this->option('email'))
                ->first();

            if ($existingByEmail) {
                $user = $existingByEmail;
            }
        }

        $user->fill([
            'name' => $this->option('name'),
            'email' => $this->option('email'),
            'username' => 'superadmin',
            'password' => $this->option('password'),
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
        ]);

        $user->save();
        $user->syncRoles([$role]);

        if ($permissions->isNotEmpty()) {
            $user->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info('Akun superadmin berhasil disinkronkan.');
        $this->line("Username: {$user->username}");
        $this->line("Email: {$user->email}");
        $this->line("Role: {$role->name}");
        $this->line('Permission: semua izin aktif');

        return self::SUCCESS;
    }
}
