@extends('layouts.admin')

@section('title', 'Manajemen Pengguna - SIPANDA')

@section('content')
<div class="flex flex-col gap-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface">Manajemen Pengguna</h1>
            <p class="text-body-sm text-on-surface-variant">Kelola pengguna sistem dan role</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all flex items-center gap-sm">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Tambah Pengguna
        </a>
    </div>

    @if (session('success'))
        <div class="bg-success/10 border border-success/20 text-success px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl flex items-center gap-md">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/50">
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Nama</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">NIK</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Email</th>
                        <th class="text-left px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Role</th>
                        <th class="text-center px-lg py-3 text-label-sm font-bold text-on-surface-variant uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-variant/20">
                    @foreach ($users as $user)
                        <tr class="hover:bg-surface-container/30 transition-colors">
                            <td class="px-lg py-4 text-body-md font-semibold text-on-surface">{{ $user->name }}</td>
                            <td class="px-lg py-4 text-body-sm font-mono text-on-surface">{{ $user->nik }}</td>
                            <td class="px-lg py-4 text-body-sm text-on-surface-variant">{{ $user->email ?? '-' }}</td>
                            <td class="px-lg py-4">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-primary/10 text-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-lg py-4 text-center">
                                <div class="flex items-center justify-center gap-sm">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-primary text-label-sm font-bold hover:underline">Edit</a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-error text-label-sm font-bold hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="p-lg border-t border-surface-variant/20">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
