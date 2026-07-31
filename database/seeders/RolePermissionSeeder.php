<?php

namespace Database\Seeders;

use App\Support\PermissionDefinitions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat semua permission berdasarkan definisi resource + aksi
        foreach (PermissionDefinitions::RESOURCES as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . ' ' . $resource, 'guard_name' => 'web']);
            }
        }

        // Assign ke tiap role (Super Admin = semua izin)
        foreach (PermissionDefinitions::ROLE_PERMISSIONS as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            $role->syncPermissions(empty($perms) ? Permission::all() : $perms);
        }
    }
}
