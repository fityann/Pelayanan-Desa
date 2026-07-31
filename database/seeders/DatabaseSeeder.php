<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles for Puspamukti Smart Village (Fase 1)
        $roles = [
            'Super Admin',        // Tim Pengembang
            'Kepala Desa',        // Kepala Desa
            'Sekretaris Desa',    // Sekretaris Desa
            'Bendahara',          // Bendahara Desa
            'Admin Desa',         // Admin Desa / Operator
            'Warga',              // Masyarakat Desa Puspamukti
        ];

        foreach ($roles as $roleName) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // ==== CREATE DEFAULT USERS ====
        $users = [
            [
                'name' => 'Admin Puspamukti',
                'email' => 'admin@puspamukti.local',
                'nik' => '0000000000000000',
                'password' => 'admin123',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Kepala Desa Puspamukti',
                'email' => 'kepaladesa@puspamukti.local',
                'nik' => '3201010101010101',
                'phone' => '081234567890',
                'address' => 'Kantor Desa Puspamukti',
                'rt' => '01',
                'rw' => '01',
                'password' => 'kepaladesa123',
                'role' => 'Kepala Desa',
            ],
            [
                'name' => 'Sekretaris Desa Puspamukti',
                'email' => 'sekdes@puspamukti.local',
                'nik' => '3201010101010107',
                'phone' => '081234567891',
                'address' => 'Kantor Desa Puspamukti',
                'rt' => '01',
                'rw' => '01',
                'password' => 'sekdes123',
                'role' => 'Sekretaris Desa',
            ],
            [
                'name' => 'Bendahara Desa Puspamukti',
                'email' => 'bendahara@puspamukti.local',
                'nik' => '3201010101010108',
                'phone' => '081234567892',
                'address' => 'Kantor Desa Puspamukti',
                'rt' => '01',
                'rw' => '01',
                'password' => 'bendahara123',
                'role' => 'Bendahara',
            ],
            [
                'name' => 'Admin Desa Puspamukti',
                'email' => 'admindesa@puspamukti.local',
                'nik' => '3201010101010109',
                'phone' => '081234567893',
                'address' => 'Kantor Desa Puspamukti',
                'rt' => '01',
                'rw' => '01',
                'password' => 'admindesa123',
                'role' => 'Admin Desa',
            ],
            [
                'name' => 'Warga Contoh',
                'email' => 'warga@puspamukti.local',
                'nik' => '3201010101010102',
                'phone' => '081234567894',
                'address' => 'Kp. Cigalontang RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
                'password' => 'warga123',
                'role' => 'Warga',
            ],
            [
                'name' => 'Warga Dua',
                'email' => 'warga2@puspamukti.local',
                'nik' => '3201010101010103',
                'phone' => '081234567895',
                'address' => 'Kp. Bojong RT 02 RW 01',
                'rt' => '02',
                'rw' => '01',
                'password' => 'warga123',
                'role' => 'Warga',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'nik' => $userData['nik'],
                    'phone' => $userData['phone'] ?? null,
                    'address' => $userData['address'] ?? null,
                    'rt' => $userData['rt'] ?? null,
                    'rw' => $userData['rw'] ?? null,
                    'email_verified_at' => now(),
                    'password' => bcrypt($userData['password']),
                ]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        $this->command->info('Roles dan users berhasil dibuat');

        // ==== DATA PENDUKUNG (Sample Fase 1) ====
        $this->call([
            JenisSuratSeeder::class,
            KeluargaSeeder::class,
            PendudukSeeder::class,
            PengajuanSuratSeeder::class,
            ApbdesSeeder::class,
            PengaduanSeeder::class,
            InformasiSeeder::class,
        ]);
    }
}
