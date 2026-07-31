@extends('layouts.admin')

@section('title', 'Manajemen Role & Permission - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-sm mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-primary/10 text-primary">Akses Kontrol Keamanan</span>
            </div>
            <h1 class="text-headline-md font-bold text-on-surface">Manajemen Pengguna & Role</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola role dan izin akses pengguna sistem SIPANDA</p>
        </div>
        <div class="flex gap-sm">
            <form method="POST" action="{{ route('admin.roles.sync') }}" class="inline">
                @csrf
                <button type="submit" class="bg-on-tertiary-container text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-on-tertiary-container/90 transition-all flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[18px]">sync</span>
                    Sinkronisasi Izin
                </button>
            </form>
            <a href="{{ route('admin.users.index') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
                <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                Kelola Pengguna
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-lg border border-outline-variant/10">
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center text-primary shadow-sm">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div>
                    <span class="text-label-sm text-on-surface-variant">Role Aktif</span>
                    <p class="font-headline-lg text-on-surface tracking-tight">{{ $roleCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-lg border border-outline-variant/10">
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-secondary/20 to-secondary/5 flex items-center justify-center text-secondary shadow-sm">
                    <span class="material-symbols-outlined">shield</span>
                </div>
                <div>
                    <span class="text-label-sm text-on-surface-variant">Izin Diberikan</span>
                    <p class="font-headline-lg text-on-surface tracking-tight">{{ $permissionCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-lg border border-outline-variant/10">
            <div class="flex items-center gap-md">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center text-success shadow-sm">
                    <span class="material-symbols-outlined">checklist</span>
                </div>
                <div>
                    <span class="text-label-sm text-on-surface-variant">Resource Terkelola</span>
                    <p class="font-headline-lg text-on-surface tracking-tight">{{ count($matrix) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Permission Matrix --}}
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
        <div class="p-lg border-b border-surface-variant/20">
            <h3 class="text-title-md font-bold text-on-surface">Matriks Izin Akses</h3>
            <p class="text-body-sm text-on-surface-variant mt-1">Centang/dicentang untuk memberikan atau mencabut izin</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/30">
                        <th class="text-left px-lg py-4 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest min-w-[180px]">Resource / Modul</th>
                        <th class="text-center px-3 py-4 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                        @foreach ($roles as $role)
                            <th class="text-center px-3 py-4 min-w-[100px]">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full {{ $role->name === 'Super Admin' ? 'bg-error' : ($role->name === 'Warga' ? 'bg-success' : 'bg-primary') }}"></span>
                                    <span class="text-label-sm font-bold text-on-surface">{{ $role->name }}</span>
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/10">
                    @foreach ($matrix as $resource => $actions)
                        <tr class="hover:bg-surface-container/20 transition-colors">
                            <td class="px-lg py-3 text-body-md font-semibold text-on-surface" rowspan="{{ count($actions) }}">
                                {{ $resource }}
                            </td>
                            @php $first = true; @endphp
                            @foreach ($actions as $action => $rolePerms)
                                @if (!$first)
                                    </tr><tr class="hover:bg-surface-container/20 transition-colors">
                                @endif
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-[11px] font-extrabold tracking-wide
                                        {{ $action === 'C' ? 'bg-error/10 text-error' : '' }}
                                        {{ $action === 'R' ? 'bg-primary/10 text-primary' : '' }}
                                        {{ $action === 'U' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $action === 'D' ? 'bg-surface-variant/30 text-on-surface-variant' : '' }}">
                                        {{ $action }}
                                    </span>
                                </td>
                                @foreach ($roles as $role)
                                    <td class="px-3 py-3 text-center">
                                        <form method="POST" action="{{ route('admin.roles.update') }}" class="inline permission-toggle" data-role-id="{{ $role->id }}" data-permission="{{ $action }} {{ $resource }}">
                                            @csrf
                                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                                            <input type="hidden" name="permission" value="{{ $action }} {{ $resource }}">
                                            <input type="hidden" name="granted" value="{{ $rolePerms[$role->id] ? 'false' : 'true' }}">
                                            <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                                                {{ $rolePerms[$role->id]
                                                    ? ($action === 'C' ? 'bg-error text-white shadow-sm shadow-error/30' : '')
                                                    . ($action === 'R' ? 'bg-primary text-white shadow-sm shadow-primary/30' : '')
                                                    . ($action === 'U' ? 'bg-indigo-500 text-white shadow-sm shadow-indigo-500/30' : '')
                                                    . ($action === 'D' ? 'bg-surface-variant/50 text-on-surface-variant' : '')
                                                    . ' opacity-100'
                                                    : 'bg-surface-container border border-dashed border-outline-variant/30 opacity-40 hover:opacity-70'
                                                }}"
                                                title="{{ $role->name }}: {{ $rolePerms[$role->id] ? 'Dicabut' : 'Beri izin' }} {{ $action }} {{ $resource }}">
                                                @if ($rolePerms[$role->id])
                                                    <span class="text-[14px] font-bold">{{ $action }}</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                @endforeach
                                @php $first = false; @endphp
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-lg border-t border-surface-variant/20 bg-surface-container/20">
            <div class="flex items-center gap-lg text-[10px] uppercase tracking-wider">
                <span class="font-semibold text-on-surface-variant">Keterangan:</span>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-error text-white flex items-center justify-center text-[9px] font-bold">C</span><span class="text-on-surface-variant">Create</span></div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-primary text-white flex items-center justify-center text-[9px] font-bold">R</span><span class="text-on-surface-variant">Read</span></div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-indigo-500 text-white flex items-center justify-center text-[9px] font-bold">U</span><span class="text-on-surface-variant">Update</span></div>
                <div class="flex items-center gap-1.5"><span class="w-4 h-4 rounded bg-surface-variant/50 text-on-surface-variant flex items-center justify-center text-[9px] font-bold">D</span><span class="text-on-surface-variant">Delete</span></div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.querySelectorAll('.permission-toggle button').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const form = this.closest('form');
        const isActive = form.querySelector('input[name="granted"]');
        const currentValue = isActive.value;
        // Optimistic visual update - will be reverted if server rejects
        if (currentValue === 'false') {
            this.classList.remove('opacity-40', 'hover:opacity-70', 'bg-surface-container', 'border', 'border-dashed', 'border-outline-variant/30');
            this.innerHTML = '<span class="text-[14px] font-bold">' + this.closest('form').querySelector('input[name="permission"]').value.charAt(0) + '</span>';
            const action = this.closest('form').querySelector('input[name="permission"]').value.charAt(0);
            const colorMap = { 'C': 'bg-error text-white shadow-sm shadow-error/30', 'R': 'bg-primary text-white shadow-sm shadow-primary/30', 'U': 'bg-indigo-500 text-white shadow-sm shadow-indigo-500/30', 'D': 'bg-surface-variant/50 text-on-surface-variant' };
            this.className = 'w-8 h-8 rounded-lg flex items-center justify-center transition-all ' + (colorMap[action] || '') + ' opacity-100';
        } else {
            this.className = 'w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-surface-container border border-dashed border-outline-variant/30 opacity-40 hover:opacity-70';
            this.innerHTML = '';
        }
        isActive.value = currentValue === 'true' ? 'false' : 'true';
    });
});
</script>
@endpush
@endsection
