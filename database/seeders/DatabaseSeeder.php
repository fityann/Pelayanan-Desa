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
        // Create roles for Puspamukti Smart Village
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

        // Create default admin user (Super Admin)
        $adminUser = User::factory()->create([
            'name' => 'Admin Puspamukti',
            'email' => 'admin@puspamukti.local',
            'nik' => '0000000000000000', // Default NIK for admin
            'password' => bcrypt('admin123'),
        ]);

        $adminUser->assignRole('Super Admin');

        // Create example users for other roles
        $kepalaDesa = User::factory()->create([
            'name' => 'Kepala Desa Puspamukti',
            'email' => 'kepaladesa@puspamukti.local',
            'nik' => '3201010101010101',
            'phone' => '081234567890',
            'address' => 'Kantor Desa Puspamukti',
            'rt' => '01',
            'rw' => '01',
            'password' => bcrypt('kepaladesa123'),
        ]);
        $kepalaDesa->assignRole('Kepala Desa');

        $warga1 = User::factory()->create([
            'name' => 'Warga Contoh',
            'email' => 'warga@puspamukti.local',
            'nik' => '3201010101010102',
            'phone' => '081234567891',
            'address' => 'Jl. Desa Puspamukti No. 1',
            'rt' => '01',
            'rw' => '01',
            'password' => bcrypt('warga123'),
        ]);
        $warga1->assignRole('Warga');
    }
}
