<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder untuk membuat akun Kabid dan Direktur
 *
 * Jalankan: php artisan db:seed --class=ReviewerAccountsSeeder
 *
 * PERHATIAN: Seeder ini membutuhkan data company dan department yang sudah ada.
 * Pastikan sudah ada data di tabel companies dan departments sebelum menjalankan seeder ini.
 */
class ReviewerAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Ambil company dan department pertama yang tersedia
        $firstCompany = DB::table('companies')->first();
        $firstDepartment = DB::table('departments')->first();

        if (! $firstCompany) {
            $this->command->error('❌ Tidak ada data Company. Buat company terlebih dahulu sebelum menjalankan seeder ini.');

            return;
        }

        // ── 1. Buat akun Kabid ─────────────────────────────────────────
        $kabidUser = User::firstOrCreate(
            ['email' => 'kabid@syifaglobalgroup.com'],
            [
                'name' => 'Kepala Bidang',
                'username' => 'kabid',
                'email' => 'kabid@syifaglobalgroup.com',
                'password' => Hash::make('kabid@12345'),
                'company_id' => $firstCompany->id,
                'department_id' => $firstDepartment?->id,
                'unit_id' => null,
                'email_verified_at' => $now,
                'is_active' => true,
            ]
        );

        // Assign role kabid
        if (! $kabidUser->hasRole('kabid')) {
            $kabidUser->assignRole('kabid');
        }

        $this->command->info('✅ Akun Kabid berhasil dibuat:');
        $this->command->line('   Email    : kabid@syifaglobalgroup.com');
        $this->command->line('   Password : kabid@12345');
        $this->command->line('   Panel    : /reviewer');

        // ── 2. Buat akun Direktur ──────────────────────────────────────
        $direkturUser = User::firstOrCreate(
            ['email' => 'direktur@syifaglobalgroup.com'],
            [
                'name' => 'Direktur',
                'username' => 'direktur',
                'email' => 'direktur@syifaglobalgroup.com',
                'password' => Hash::make('direktur@12345'),
                'company_id' => $firstCompany->id,
                'department_id' => null,
                'unit_id' => null,
                'email_verified_at' => $now,
                'is_active' => true,
            ]
        );

        // Assign role direktur
        if (! $direkturUser->hasRole('direktur')) {
            // Cek apakah role direktur ada, jika belum buat
            $guardName = 'web';
            $dirRole = DB::table('roles')
                ->where('name', 'direktur')
                ->where('guard_name', $guardName)
                ->first();

            if (! $dirRole) {
                DB::table('roles')->insert([
                    'name' => 'direktur',
                    'guard_name' => $guardName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $direkturUser->assignRole('direktur');
        }

        $this->command->info('✅ Akun Direktur berhasil dibuat:');
        $this->command->line('   Email    : direktur@syifaglobalgroup.com');
        $this->command->line('   Password : direktur@12345');
        $this->command->line('   Panel    : /reviewer');
        $this->command->newLine();
        $this->command->warn('⚠️  Harap ganti password setelah login pertama kali!');
        $this->command->newLine();
        $this->command->info('🔗 Akses Panel Reviewer: '.config('app.url').'/reviewer');
    }
}
