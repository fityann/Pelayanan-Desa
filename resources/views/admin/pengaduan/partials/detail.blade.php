<div class="p-lg">
    <!-- Header -->
    <div class="bg-primary-container px-lg py-md flex justify-between items-center -m-lg mb-lg">
        <div>
            <h3 class="text-headline-sm font-semibold text-on-primary-container">Detail Pengaduan</h3>
            <p class="text-label-sm text-on-primary-container/70 mt-xs">{{ $pengaduan->tiket_id }}</p>
        </div>
        <button onclick="closeDetailModal()" class="text-on-primary-container hover:text-on-primary p-2 rounded-lg hover:bg-primary/20 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    
    <div class="space-y-lg">
        <!-- Status & Info Bar -->
        <div class="flex flex-wrap gap-sm items-center justify-between">
            <div class="flex items-center gap-sm">
                @php $status = $pengaduan->status_display; @endphp
                <span class="text-label-sm px-3 py-1.5 rounded-full {{ $status['color'] }}">
                    {{ $status['label'] }}
                </span>
                
                <span class="text-label-sm px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface flex items-center gap-xs">
                    <span class="material-symbols-outlined text-sm">
                        {{ $pengaduan->sumber_akses == 'qr_code' ? 'qr_code' : 'public' }}
                    </span>
                    {{ $pengaduan->sumber_akses == 'qr_code' ? 'QR Code' : 'Web' }}
                </span>
            </div>
            
            <div class="text-label-sm text-on-surface-variant">
                {{ $pengaduan->tanggal_diterima->format('d F Y H:i') }}
            </div>
        </div>
        
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
            <!-- Left Column: Pengaduan Details -->
            <div class="space-y-md">
                <!-- Judul & Kategori -->
                <div class="bg-surface-container-low p-md rounded-xl">
                    <h4 class="text-label-md font-medium text-on-surface mb-sm">Judul Pengaduan</h4>
                    <p class="text-body-md text-on-surface">{{ $pengaduan->judul }}</p>
                    
                    <div class="flex items-center gap-sm mt-md">
                        <span class="material-symbols-outlined text-on-surface-variant">
                            @php
                                $icons = [
                                    'sampah' => 'delete',
                                    'jalan' => 'directions',
                                    'drainase' => 'water_damage',
                                    'penerangan' => 'lightbulb',
                                    'air' => 'water_drop',
                                    'lainnya' => 'campaign'
                                ];
                            @endphp
                            {{ $icons[$pengaduan->kategori] ?? 'campaign' }}
                        </span>
                        <span class="text-label-sm text-on-surface">{{ $pengaduan->kategori_display }}</span>
                    </div>
                </div>
                
                <!-- Deskripsi -->
                <div class="bg-surface-container-low p-md rounded-xl">
                    <h4 class="text-label-md font-medium text-on-surface mb-sm">Deskripsi Lengkap</h4>
                    <p class="text-body-md text-on-surface whitespace-pre-line">{{ $pengaduan->deskripsi }}</p>
                </div>
                
                <!-- Foto -->
                @if(count($pengaduan->foto_list) > 0)
                    <div class="bg-surface-container-low p-md rounded-xl">
                        <h4 class="text-label-md font-medium text-on-surface mb-sm">Foto Bukti ({{ count($pengaduan->foto_list) }})</h4>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($pengaduan->foto_list as $foto)
                                <img src="{{ Storage::url($foto) }}"
                                     class="w-full h-24 object-cover rounded-lg"
                                     onclick="viewPhoto('{{ $foto }}')"
                                     style="cursor: zoom-in">
                            @endforeach
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-xs">Klik untuk memperbesar</p>
                    </div>
                @endif
            </div>
            
            <!-- Right Column: Info & Actions -->
            <div class="space-y-md">
                <!-- Info Pelapor -->
                <div class="bg-surface-container-low p-md rounded-xl">
                    <h4 class="text-label-md font-medium text-on-surface mb-sm">Informasi Pelapor</h4>
                    <div class="space-y-sm">
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">Nama</span>
                            <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->nama_pelapor ?? ($pengaduan->user?->name ?? '-') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">No. WhatsApp</span>
                            <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->whatsapp ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">Email</span>
                            <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->user->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Lokasi -->
                <div class="bg-surface-container-low p-md rounded-xl">
                    <h4 class="text-label-md font-medium text-on-surface mb-sm">Lokasi Pengaduan</h4>
                    <div class="space-y-sm">
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">RT</span>
                            <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->lokasi_display }}</span>
                        </div>
                        @if($pengaduan->latitude && $pengaduan->longitude)
                            <div class="flex justify-between">
                                <span class="text-body-sm text-on-surface-variant">Koordinat</span>
                                <span class="text-label-sm font-medium text-on-surface">
                                    {{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}
                                </span>
                            </div>
                            <a href="https://maps.google.com/?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}" 
                               target="_blank"
                               class="inline-flex items-center gap-xs text-primary hover:text-primary/80 text-label-sm">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                Lihat di Google Maps
                            </a>
                        @endif
                        @if($pengaduan->lokasi_qr)
                            <div class="flex justify-between">
                                <span class="text-body-sm text-on-surface-variant">Kode QR</span>
                                <span class="text-label-sm font-medium text-on-surface">{{ $pengaduan->lokasi_qr }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Timeline -->
                <div class="bg-surface-container-low p-md rounded-xl">
                    <h4 class="text-label-md font-medium text-on-surface mb-sm">Timeline</h4>
                    <div class="space-y-sm">
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">Diterima</span>
                            <span class="text-label-sm text-on-surface">{{ $pengaduan->tanggal_diterima->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($pengaduan->tanggal_diproses)
                            <div class="flex justify-between">
                                <span class="text-body-sm text-on-surface-variant">Diproses</span>
                                <span class="text-label-sm text-on-surface">{{ $pengaduan->tanggal_diproses->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($pengaduan->tanggal_selesai)
                            <div class="flex justify-between">
                                <span class="text-body-sm text-on-surface-variant">Diselesaikan</span>
                                <span class="text-label-sm text-on-surface">{{ $pengaduan->tanggal_selesai->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Tanggapan -->
                @if($pengaduan->tanggapan)
                    <div class="bg-surface-container-low p-md rounded-xl">
                        <h4 class="text-label-md font-medium text-on-surface mb-sm">Tanggapan Resmi</h4>
                        <p class="text-body-md text-on-surface whitespace-pre-line">{{ $pengaduan->tanggapan }}</p>
                        @if($pengaduan->processedBy)
                            <p class="text-[11px] text-on-surface-variant mt-sm">
                                Oleh: {{ $pengaduan->processedBy->name }} • {{ $pengaduan->tanggal_selesai->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="flex flex-col gap-sm">
                    @if($pengaduan->status == 'diterima')
                        <button onclick="processPengaduan({{ $pengaduan->id }})" 
                                class="w-full bg-yellow-500 text-on-yellow hover:bg-yellow-600 px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                            <span class="material-symbols-outlined">play_arrow</span>
                            Proses Pengaduan
                        </button>
                    @endif
                    
                    @if($pengaduan->status == 'diproses')
                        <div class="bg-surface-container-low p-md rounded-xl">
                            <h4 class="text-label-md font-medium text-on-surface mb-sm">Berikan Tanggapan</h4>
                            <textarea id="tanggapanInput{{ $pengaduan->id }}" 
                                      rows="3"
                                      class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm mb-sm resize-none"
                                      placeholder="Masukkan tanggapan atau solusi untuk pengaduan ini..."></textarea>
                            <button onclick="completeWithResponse({{ $pengaduan->id }})" 
                                    class="w-full bg-green-500 text-on-green hover:bg-green-600 px-lg py-2 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                                <span class="material-symbols-outlined">check</span>
                                Selesaikan dengan Tanggapan
                            </button>
                        </div>
                    @endif
                    
                    @if($pengaduan->status == 'selesai')
                        <div class="text-center">
                            <span class="material-symbols-outlined text-green-500 text-4xl mb-sm">check_circle</span>
                            <p class="text-label-sm text-on-surface">Pengaduan telah diselesaikan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function completeWithResponse(id) {
    const tanggapan = document.getElementById(`tanggapanInput${id}`).value;
    
    if (!tanggapan.trim()) {
        alert('Harap masukkan tanggapan terlebih dahulu');
        return;
    }
    
    if (confirm('Selesaikan pengaduan dengan tanggapan ini?')) {
        fetch(`/admin/pengaduan/${id}/selesai`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tanggapan: tanggapan })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Pengaduan berhasil diselesaikan', 'success');
                setTimeout(() => {
                    closeDetailModal();
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message || 'Gagal menyelesaikan pengaduan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan', 'error');
        });
    }
}
</script>