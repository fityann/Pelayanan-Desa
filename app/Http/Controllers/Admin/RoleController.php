<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private array $resources = [
        'Penduduk' => ['C', 'R', 'U', 'D'],
        'Keluarga' => ['C', 'R', 'U', 'D'],
        'Surat' => ['C', 'R', 'U', 'D'],
        'Pengajuan Surat' => ['C', 'R', 'U', 'D'],
        'Arsip Surat' => ['R'],
        'Pengaduan' => ['C', 'R', 'U', 'D'],
        'Informasi' => ['C', 'R', 'U', 'D'],
        'APBDes' => ['C', 'R', 'U', 'D'],
        'Manajemen User' => ['C', 'R', 'U', 'D'],
        'Role & Permission' => ['R', 'U'],
        'Pengaturan' => ['R', 'U'],
    ];

    public function index(): View
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? $p->name);

        $roleCount = $roles->count();
        $permissionCount = $permissions->flatten()->count();

        $matrix = [];
        foreach ($this->resources as $resource => $actions) {
            foreach ($actions as $action) {
                $permName = $action . ' ' . $resource;
                $matrix[$resource][$action] = [];
                foreach ($roles as $role) {
                    $matrix[$resource][$action][$role->id] = $role->hasPermissionTo($permName);
                }
            }
        }

        return view('admin.roles.index', compact('roles', 'permissions', 'roleCount', 'permissionCount', 'matrix'));
    }

    public function update(Request $request): RedirectResponse
    {
        $role = Role::findById($request->role_id);
        $permissionName = $request->permission;

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
        $rolePermissions = [
            'Super Admin' => [], // all permissions
            'Kepala Desa' => ['R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat', 'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'R APBDes', 'U APBDes'],
            'Sekretaris Desa' => ['R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat', 'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'R APBDes', 'U APBDes'],
            'Bendahara' => ['R Penduduk', 'R Keluarga', 'R Surat', 'R Pengajuan Surat', 'R Arsip Surat', 'R Pengaduan', 'R Informasi', 'C APBDes', 'R APBDes', 'U APBDes'],
            'Admin Desa' => ['C Penduduk', 'R Penduduk', 'U Penduduk', 'D Penduduk', 'C Keluarga', 'R Keluarga', 'U Keluarga', 'D Keluarga', 'C Surat', 'R Surat', 'U Surat', 'D Surat', 'R Pengajuan Surat', 'U Pengajuan Surat', 'R Arsip Surat', 'C Pengaduan', 'R Pengaduan', 'U Pengaduan', 'D Pengaduan', 'C Informasi', 'R Informasi', 'U Informasi', 'D Informasi', 'C APBDes', 'R APBDes'],
            'Warga' => ['R Penduduk', 'C Pengaduan', 'R Informasi', 'C Pengajuan Surat'],
            'RT' => ['R Penduduk', 'R Pengajuan Surat', 'R Pengaduan', 'R Informasi'],
            'RW' => ['R Penduduk', 'R Pengajuan Surat', 'R Pengaduan', 'R Informasi'],
        ];

        // Create permissions
        foreach ($this->resources as $resource => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $action . ' ' . $resource, 'guard_name' => 'web']);
            }
        }

        // Assign to roles
        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            if (empty($perms)) {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($perms);
            }
        }
    }
}
