<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\PermissionDefinitions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        // Pastikan permission tersedia (lazy sync saat pertama kali dibuka)
        if (Permission::count() === 0) {
            $this->seedPermissions();
        }

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? $p->name);

        $roleCount = $roles->count();
        $permissionCount = $permissions->flatten()->count();

        $matrix = [];
        foreach (PermissionDefinitions::RESOURCES as $resource => $actions) {
            foreach ($actions as $action) {
                $permName = $action . ' ' . $resource;
                $matrix[$resource][$action] = [];
                foreach ($roles as $role) {
                    $matrix[$resource][$action][$role->id] = $role->permissions->contains('name', $permName);
                }
            }
        }

        return view('admin.roles.index', compact('roles', 'permissions', 'roleCount', 'permissionCount', 'matrix'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'permission' => ['required', 'string'],
            'granted' => ['required', 'in:true,false'],
        ]);

        $role = Role::findById($request->role_id);
        $permissionName = $request->permission;

        abort_unless(Permission::where('name', $permissionName)->exists(), 422, 'Permission tidak ditemukan.');

        if ($request->granted === 'true') {
            $role->givePermissionTo($permissionName);
        } else {
            $role->revokePermissionTo($permissionName);
        }

        return back()->with('success', 'Izin berhasil diperbarui');
    }

    public function syncAll(): RedirectResponse
    {
        $this->seedPermissions();

        return back()->with('success', 'Semua izin berhasil disinkronkan');
    }

    private function seedPermissions(): void
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
