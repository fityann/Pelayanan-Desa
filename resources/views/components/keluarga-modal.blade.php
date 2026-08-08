<!-- Modal Form Data Keluarga -->
<div id="keluargaModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-surface-container w-full max-w-xl rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden">
        <!-- Header Modal -->
        <div class="bg-primary-container px-lg py-md flex justify-between items-center">
            <div>
                <h3 class="text-headline-sm font-semibold text-on-primary-container" id="keluargaModalTitle">Tambah Data Keluarga</h3>
                <p class="text-label-sm text-on-primary-container/70 mt-xs">Data Kartu Keluarga</p>
            </div>
            <button onclick="closeKeluargaModal()" class="text-on-primary-container hover:text-on-primary p-2 rounded-lg hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-lg overflow-y-auto" style="max-height: calc(90vh - 80px)">
            <form id="keluargaForm" action="{{ route('admin.keluarga.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="keluargaId">
                
                <!-- No. KK -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Nomor KK <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="no_kk" id="no_kk" 
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               placeholder="Contoh: 332616xxxxxxxxxx"
                               pattern="[0-9]{16}"
                               maxlength="16"
                               required>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">numbers</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-xs">16 digit angka</p>
                </div>
                
                <!-- Kepala Keluarga -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Nama Kepala Keluarga <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="kepala_keluarga" id="kepala_keluarga" 
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               placeholder="Nama lengkap kepala keluarga"
                               required>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">person</span>
                        </div>
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Alamat Lengkap <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <textarea name="alamat" id="alamat" rows="3"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                               placeholder="Jalan, Dusun, Desa"
                               required></textarea>
                        <div class="absolute right-3 top-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">home</span>
                        </div>
                    </div>
                </div>
                
                <!-- RT -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        RT <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="rt" id="rt" 
                               min="1" max="99"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               placeholder="01"
                               required>
                        <input type="hidden" name="rw" id="rw" value="01">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">location_on</span>
                        </div>
                    </div>
                </div>
                
                <!-- Wilayah -->
                <div class="grid grid-cols-2 gap-md mb-lg">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Desa/Kelurahan
                        </label>
                        <div class="relative">
                            <input type="text" name="desa" id="desa" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   value="Puspamukti">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">location_city</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Kecamatan
                        </label>
                        <div class="relative">
                            <input type="text" name="kecamatan" id="kecamatan" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   value="Bojong">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">map</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Modal -->
                <div class="flex gap-sm pt-md border-t border-outline-variant/20">
                    <button type="button" onclick="closeKeluargaModal()" 
                            class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-3 rounded-lg text-label-sm font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-primary text-on-primary hover:bg-primary/90 px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">save</span>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showKeluargaModal(keluarga = null) {
    const modal = document.getElementById('keluargaModal');
    const title = document.getElementById('keluargaModalTitle');
    const form = document.getElementById('keluargaForm');
    
    if (keluarga) {
        // Edit mode
        title.textContent = 'Edit Data Keluarga';
        document.getElementById('keluargaId').value = keluarga.id;
        document.getElementById('no_kk').value = keluarga.no_kk;
        document.getElementById('kepala_keluarga').value = keluarga.kepala_keluarga;
        document.getElementById('alamat').value = keluarga.alamat || '';
        document.getElementById('rt').value = keluarga.rt || '';
        document.getElementById('rw').value = keluarga.rw || '';
        document.getElementById('desa').value = keluarga.desa || 'Puspamukti';
        document.getElementById('kecamatan').value = keluarga.kecamatan || 'Bojong';
        
        // Update form action for update
        form.action = `/admin/keluarga/${keluarga.id}`;
        form.method = 'POST';
        form.innerHTML = form.innerHTML.replace('@method('PUT')', '');
        form.innerHTML += '@method('PUT')';
    } else {
        // Add mode
        title.textContent = 'Tambah Data Keluarga';
        form.reset();
        document.getElementById('keluargaId').value = '';
        document.getElementById('desa').value = 'Puspamukti';
        document.getElementById('kecamatan').value = 'Bojong';
        
        // Reset form action for create
        form.action = '{{ route("admin.keluarga.store") }}';
        form.method = 'POST';
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeKeluargaModal() {
    document.getElementById('keluargaModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Handle form submission with AJAX
document.getElementById('keluargaForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Menyimpan...';
    
    try {
        const response = await fetch(this.action, {
            method: this.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Success
            showToast('Data keluarga berhasil disimpan!', 'success');
            closeKeluargaModal();
            
            // Refresh page after delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Error
            let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
            if (result.message) {
                errorMessage = result.message;
            } else if (result.errors) {
                errorMessage = Object.values(result.errors).join('<br>');
            }
            showToast(errorMessage, 'error');
        }
    } catch (error) {
        showToast('Koneksi jaringan bermasalah.', 'error');
        console.error('Form submission error:', error);
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast fixed top-4 right-4 px-lg py-md rounded-lg shadow-lg z-50 flex items-center gap-sm animate-slide-in ${
        type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' :
        type === 'error' ? 'bg-red-50 text-red-700 border border-red-200' :
        'bg-blue-50 text-blue-700 border border-blue-200'
    }`;
    
    const icon = type === 'success' ? 'check_circle' : type === 'error' ? 'error' : 'info';
    
    toast.innerHTML = `
        <span class="material-symbols-outlined">${icon}</span>
        <span class="text-label-sm font-medium">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Close modal with ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('keluargaModal').classList.contains('hidden')) {
        closeKeluargaModal();
    }
});

// Close modal with click outside
document.getElementById('keluargaModal').addEventListener('click', function(e) {
    if (e.target.id === 'keluargaModal') {
        closeKeluargaModal();
    }
});
</script>

<style>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-slide-out {
    animation: slide-out 0.3s ease-in;
}

/* Custom scrollbar for modal */
#keluargaModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#keluargaModal .overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

#keluargaModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

#keluargaModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}
</style>