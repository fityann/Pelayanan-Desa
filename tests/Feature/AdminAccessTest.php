<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    private static int $nikCounter = 0;

    protected function createUserWithRole(string $role): User
    {
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

        $user = User::create([
            'name' => 'User ' . $role,
            'email' => strtolower(str_replace(' ', '', $role)) . '@test.local',
            'nik' => '3201010101010' . str_pad((string) ++static::$nikCounter, 3, '0', STR_PAD_LEFT),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($role);

        return $user;
    }

    public function test_warga_cannot_access_admin_routes(): void
    {
        $warga = $this->createUserWithRole('Warga');

        $this->actingAs($warga)
            ->get(route('admin.penduduk.index'))
            ->assertForbidden();

        $this->actingAs($warga)
            ->get(route('admin.users.index'))
            ->assertForbidden();

        $this->actingAs($warga)
            ->get(route('admin.apbdes.create'))
            ->assertForbidden();
    }

    public function test_staff_can_access_admin_routes(): void
    {
        $admin = $this->createUserWithRole('Admin Desa');

        $this->actingAs($admin)
            ->get(route('admin.penduduk.index'))
            ->assertOk();
    }

    public function test_non_admin_roles_cannot_access_user_management(): void
    {
        $bendahara = $this->createUserWithRole('Bendahara');

        $this->actingAs($bendahara)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }
}
