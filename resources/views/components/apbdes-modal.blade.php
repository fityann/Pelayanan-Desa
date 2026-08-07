<!-- Modal Form APBDes -->
<div id="apbdesModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-surface-container w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden">
        <!-- Header Modal -->
        <div class="bg-primary-container px-lg py-md flex justify-between items-center">
            <div>
                <h3 class="text-headline-sm font-semibold text-on-primary-container">Tambah Data APBDes</h3>
                <p class="text-label-sm text-on-primary-container/70 mt-xs">Anggaran Pendapatan dan Belanja Desa</p>
            </div>
            <button onclick="closeApbdesModal()" class="text-on-primary-container hover:text-on-primary p-2 rounded-lg hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-lg overflow-y-auto" style="max-height: calc(90vh - 80px)">
            <form id="apbdesForm" action="{{ route('admin.apbdes.store') }}" method="POST">
                @csrf
                
                <!-- Tahun Anggaran -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Tahun Anggaran <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="tahun" id="tahun" 
                               min="2020" max="2030" 
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               value="{{ date('Y') }}"
                               required>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">calendar_today</span>
                        </div>
                    </div>
                </div>
                
                <!-- Kategori -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Kategori <span class="text-error">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-sm">
                        <label class="relative">
                            <input type="radio" name="kategori" value="Pendapatan" class="sr-only peer" checked>
                            <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                <span class="material-symbols-outlined block text-lg mb-xs">trending_up</span>
                                <span class="text-label-sm font-medium">Pendapatan</span>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="kategori" value="Belanja" class="sr-only peer">
                            <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                <span class="material-symbols-outlined block text-lg mb-xs">trending_down</span>
                                <span class="text-label-sm font-medium">Belanja</span>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="kategori" value="Pembiayaan" class="sr-only peer">
                            <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                <span class="material-symbols-outlined block text-lg mb-xs">swap_horiz</span>
                                <span class="text-label-sm font-medium">Pembiayaan</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <!-- Bidang dan Sub Bidang -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Bidang
                        </label>
                        <div class="relative">
                            <input type="text" name="bidang" id="bidang" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: Pendidikan, Kesehatan, Infrastruktur">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">category</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Sub Bidang
                        </label>
                        <div class="relative">
                            <input type="text" name="sub_bidang" id="sub_bidang" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="Contoh: Pembangunan Jalan, Bantuan Sosial">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">subdirectory_arrow_right</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Uraian Kegiatan -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Uraian Kegiatan <span class="text-error">*</span>
                    </label>
                    <div class="relative">
                        <textarea name="uraian" id="uraian" rows="3"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                               placeholder="Deskripsi lengkap kegiatan"
                               required></textarea>
                        <div class="absolute right-3 top-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-base">description</span>
                        </div>
                    </div>
                </div>
                
                <!-- Anggaran dan Realisasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Anggaran (Rp) <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="anggaran" id="anggaran" 
                                   step="0.01" min="0"
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="0"
                                   required>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">payments</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Realisasi (Rp)
                        </label>
                        <div class="relative">
                            <input type="number" name="realisasi" id="realisasi" 
                                   step="0.01" min="0"
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="0">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">account_balance</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Auto-calculate Persentase -->
                <div class="mb-lg">
                    <div class="bg-surface-container-high/50 p-md rounded-lg">
                        <div class="flex justify-between items-center mb-xs">
                            <span class="text-label-sm text-on-surface-variant">Persentase Realisasi</span>
                            <span id="persentaseDisplay" class="text-label-sm font-medium text-primary">0%</span>
                        </div>
                        <div class="h-2 bg-outline-variant/20 rounded-full overflow-hidden">
                            <div id="persentaseBar" class="h-full bg-primary rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Modal -->
                <div class="flex gap-sm pt-md border-t border-outline-variant/20">
                    <button type="button" onclick="closeApbdesModal()" 
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

@push('scripts')
<script>
    function showApbdesModal() {
        document.getElementById('apbdesModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeApbdesModal() {
        document.getElementById('apbdesModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        resetApbdesForm();
    }
    
    function resetApbdesForm() {
        document.getElementById('apbdesForm').reset();
        updatePersentase();
    }
    
    function updatePersentase() {
        const anggaran = parseFloat(document.getElementById('anggaran').value) || 0;
        const realisasi = parseFloat(document.getElementById('realisasi').value) || 0;
        
        let persentase = 0;
        if (anggaran > 0) {
            persentase = (realisasi / anggaran) * 100;
        }
        
        const roundedPersentase = Math.min(Math.round(persentase * 100) / 100, 100);
        
        document.getElementById('persentaseDisplay').textContent = roundedPersentase.toFixed(2) + '%';
        document.getElementById('persentaseBar').style.width = roundedPersentase + '%';
        
        // Warna berdasarkan persentase
        const bar = document.getElementById('persentaseBar');
        if (persentase >= 100) {
            bar.classList.remove('bg-primary', 'bg-yellow-500', 'bg-red-500');
            bar.classList.add('bg-green-500');
        } else if (persentase >= 75) {
            bar.classList.remove('bg-primary', 'bg-yellow-500', 'bg-red-500', 'bg-green-500');
            bar.classList.add('bg-primary');
        } else if (persentase >= 50) {
            bar.classList.remove('bg-primary', 'bg-yellow-500', 'bg-red-500', 'bg-green-500');
            bar.classList.add('bg-yellow-500');
        } else {
            bar.classList.remove('bg-primary', 'bg-yellow-500', 'bg-red-500', 'bg-green-500');
            bar.classList.add('bg-red-500');
        }
    }
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Update persentase saat input berubah
        document.getElementById('anggaran').addEventListener('input', updatePersentase);
        document.getElementById('realisasi').addEventListener('input', updatePersentase);
        
        // Format number inputs
        document.getElementById('anggaran').addEventListener('blur', function(e) {
            if (e.target.value) {
                e.target.value = parseFloat(e.target.value).toFixed(2);
            }
        });
        
        document.getElementById('realisasi').addEventListener('blur', function(e) {
            if (e.target.value) {
                e.target.value = parseFloat(e.target.value).toFixed(2);
            }
        });
        
        // Close modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('apbdesModal').classList.contains('hidden')) {
                closeApbdesModal();
            }
        });
        
        // Close modal dengan klik di luar
        document.getElementById('apbdesModal').addEventListener('click', function(e) {
            if (e.target.id === 'apbdesModal') {
                closeApbdesModal();
            }
        });
    });
    
    // Submit form dengan AJAX
    document.getElementById('apbdesForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Menyimpan...';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Success
                showToast('Data berhasil disimpan!', 'success');
                closeApbdesModal();
                
                // Refresh page atau update data
                if (typeof refreshApbdesTable === 'function') {
                    refreshApbdesTable();
                } else {
                    location.reload();
                }
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
    
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast').forEach(toast => toast.remove());
        
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
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('animate-slide-out');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }
</script>

<style>
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
#apbdesModal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

#apbdesModal .overflow-y-auto::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

#apbdesModal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

#apbdesModal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}
</style>
@endpush