@extends('layouts.admin')

@section('title', 'Tambah Role - SILAPU')

@section('content')
<div class="flex flex-col gap-lg max-w-3xl">
    <div>
        <h1 class="text-headline-md font-bold text-on-surface">Tambah Role Baru</h1>
        <p class="text-body-sm text-on-surface-variant">Buat role baru dan atur izin aksesnya</p>
    </div>

    @if ($errors->any())
        <div class="bg-error/10 border border-error/20 text-error px-lg py-3 rounded-xl">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.roles.store') }}" class="bg-surface-container-lowest rounded-xl shadow-sm p-lg">
        @csrf
        <div class="mb-lg">
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Nama Role</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" class="w-full bg-surface-container rounded-xl px-lg py-3 text-body-md outline-none focus:ring-2 focus:ring-primary/20 border border-outline-variant" placeholder="Contoh: Staff Keuangan">
            @error('name')
                <p class="text-error text-body-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-lg">
            <label class="text-label-sm font-bold text-on-surface block mb-xs">Izin Akses</label>
            <p class="text-body-sm text-on-surface-variant mb-3">Pilih izin yang diberikan ke role ini. Kosongkan = tidak ada izin.</p>

            @php
                $actionColors = [
                    'C' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'R' => 'bg-orange-100 text-orange-700 border-orange-200',
                    'U' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'D' => 'bg-red-100 text-red-700 border-red-200',
                ];
                $actionLabels = ['C' => 'Create', 'R' => 'Read', 'U' => 'Update', 'D' => 'Delete'];
            @endphp

            <div class="space-y-4">
                @foreach ($permissions as $resource => $perms)
                    <div class="bg-surface-container/50 rounded-xl p-md border border-outline-variant/20">
                        <h4 class="text-label-md font-bold text-on-surface mb-3 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-primary/10 text-primary">{{ $resource }}</span>
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($perms as $perm)
                                @php
                                    $action = explode(' ', $perm->name)[0];
                                    $colorClass = $actionColors[$action] ?? 'bg-surface-container text-on-surface-variant';
                                @endphp
                                <label class="inline-flex items-center gap-1.5 cursor-pointer px-3 py-2 rounded-lg border {{ $colorClass }} hover:bg-surface-variant/30 transition-all">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-primary" {{ old('permissions', []) && in_array($perm->name, old('permissions')) ? 'checked' : '' }}>
                                    <span class="text-[11px] font-bold uppercase tracking-wider">{{ $action }}</span>
                                    <span class="text-[11px] text-on-surface-variant/70">({{ $actionLabels[$action] ?? $action }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-md justify-end mt-lg pt-md border-t border-surface-variant/30">
            <a href="{{ route('admin.roles.index') }}" class="px-lg py-2 rounded-full text-label-md font-bold text-on-surface-variant hover:bg-surface-container transition-all">Batal</a>
            <button type="submit" class="bg-primary text-on-primary px-lg py-2 rounded-full text-label-md font-bold hover:bg-primary/90 transition-all">Simpan Role</button>
        </div>
    </form>
</div>
@endsection