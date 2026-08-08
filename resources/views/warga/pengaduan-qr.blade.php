@extends('layouts.admin')

@section('title', 'Pengaduan via QR Code')

@section('content')
<div class="space-y-lg">
    <!-- Header -->
    <div class="bg-surface-container-low p-lg rounded-2xl shadow-sm border border-outline-variant/10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-md">
            <div>
                <h1 class="text-headline-md font-semibold text-on-surface mb-xs">Pengaduan via QR Code</h1>
                <p class="text-body-md text-on-surface-variant">Laporkan masalah langsung dari lokasi kejadian</p>
            </div>
            <div class="text-label-sm px-md py-2 bg-primary/10 text-primary rounded-lg">
                Lokasi: <span id="locationStatus">Mendeteksi lokasi...</span>
            </div>
        </div>
    </div>

    <!-- Scanner & Form Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
        <!-- QR Scanner & Location Info -->
        <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
            <h3 class="text-label-lg font-medium text-on-surface mb-md">Scan QR & Data Lokasi</h3>
            
            <!-- QR Scanner Preview -->
            <div class="aspect-square bg-black rounded-xl overflow-hidden mb-md relative" id="scannerContainer">
                <div id="qrScanner" class="w-full h-full"></div>
                <div class="absolute inset-0 flex items-center justify-center" id="scannerOverlay">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-white text-6xl mb-sm">qr_code_scanner</span>
                        <p class="text-white text-label-sm">Arahkan kamera ke QR Code</p>
                    </div>
                </div>
            </div>
            
            <!-- Location Info -->
            <div class="space-y-sm">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary">location_on</span>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Lokasi Terdeteksi:</span>
                        <span id="currentLocation" class="text-label-sm font-medium text-on-surface block">Sedang mendeteksi...</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-primary">qr_code_2</span>
                    <div>
                        <span class="text-label-sm text-on-surface-variant">Kode Lokasi:</span>
                        <span id="qrCodeData" class="text-label-sm font-medium text-on-surface block">Menunggu scan...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengaduan Form -->
        <div class="bg-surface-container-low p-lg rounded-2xl border border-outline-variant/10">
            <h3 class="text-label-lg font-medium text-on-surface mb-md">Form Pengaduan</h3>
            
            <form id="pengaduanForm" action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Hidden Fields for QR Data -->
                <input type="hidden" name="sumber_akses" value="qr_code">
                <input type="hidden" name="lokasi_qr" id="lokasiQr">
                <input type="hidden" name="latitude" id="latitudeInput">
                <input type="hidden" name="longitude" id="longitudeInput">
                <input type="hidden" name="rt" id="rtInput">
                <input type="hidden" name="rw" id="rwInput">
                
                <!-- Nama & Kontak -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Nama Lengkap <span class="text-error">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ auth('warga')->user()?->name }}" 
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            No. WhatsApp <span class="text-error">*</span>
                        </label>
                        <input type="tel" name="whatsapp" 
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                               placeholder="0812-3456-7890"
                               required>
                    </div>
                </div>
                
                <!-- Kategori Pengaduan -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Kategori Pengaduan <span class="text-error">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-sm">
                        @php
                            $kategories = [
                                'sampah' => 'Sampah Menumpuk',
                                'jalan' => 'Kerusakan Jalan',
                                'drainase' => 'Drainase Tersumbat',
                                'penerangan' => 'Lampu Jalan Rusak',
                                'air' => 'Masalah Air Bersih',
                                'lainnya' => 'Lainnya'
                            ];
                        @endphp
                        @foreach($kategories as $key => $label)
                            <label class="relative">
                                <input type="radio" name="kategori" value="{{ $key }}" 
                                       class="sr-only peer" {{ $key == 'sampah' ? 'checked' : '' }}>
                                <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                    <span class="material-symbols-outlined block text-lg mb-xs">
                                        {{ $key == 'sampah' ? 'delete' : 
                                           ($key == 'jalan' ? 'directions' : 
                                           ($key == 'drainase' ? 'water_damage' : 
                                           ($key == 'penerangan' ? 'lightbulb' : 
                                           ($key == 'air' ? 'water_drop' : 'campaign')))) }}
                                    </span>
                                    <span class="text-label-sm font-medium">{{ $label }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Judul Pengaduan -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Judul Pengaduan <span class="text-error">*</span>
                    </label>
                    <input type="text" name="judul" id="judulInput"
                           class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                           placeholder="Contoh: Sampah menumpuk di RT 05"
                           required>
                </div>
                
                <!-- Deskripsi Detail -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Deskripsi Lengkap <span class="text-error">*</span>
                    </label>
                    <textarea name="deskripsi" rows="4"
                           class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                           placeholder="Jelaskan secara detail masalah yang ditemukan..."
                           required></textarea>
                </div>
                
                <!-- Upload Foto -->
                <div class="mb-md">
                    <label class="block text-label-sm font-medium text-on-surface mb-xs">
                        Foto Bukti (Opsional)
                    </label>
                    <div class="relative">
                        <input type="file" name="foto[]" id="fotoInput" accept="image/*" capture="environment" multiple
                               class="sr-only"
                               onchange="previewImage(event)">
                        <label for="fotoInput" class="block w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm cursor-pointer hover:bg-surface-container transition-colors text-center">
                            <span class="material-symbols-outlined inline-block mr-sm">add_photo_alternate</span>
                            Ambil/Gambar Foto (maks. 5)
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-sm hidden">
                        <div id="imagePreviewGrid" class="grid grid-cols-3 gap-2"></div>
                    </div>
                </div>
                
                <!-- Location Preview -->
                <div class="mb-lg bg-surface-container-high/50 p-md rounded-lg">
                    <div class="flex items-center gap-sm mb-sm">
                        <span class="material-symbols-outlined text-primary">location_on</span>
                        <span class="text-label-sm font-medium text-on-surface">Lokasi Pengaduan</span>
                    </div>
                    <div id="locationDetails" class="space-y-xs">
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">RT</span>
                            <span id="rtRwDisplay" class="text-label-sm font-medium text-on-surface">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">Alamat</span>
                            <span id="addressDisplay" class="text-label-sm font-medium text-on-surface">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-body-sm text-on-surface-variant">Koordinat</span>
                            <span id="coordsDisplay" class="text-label-sm font-medium text-on-surface">-</span>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex gap-sm">
                    <button type="button" onclick="useManualLocation()" 
                            class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">edit_location</span>
                        Atur Lokasi Manual
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-primary text-on-primary hover:bg-primary/90 px-lg py-3 rounded-lg text-label-sm font-medium transition-colors flex items-center justify-center gap-sm">
                        <span class="material-symbols-outlined">send</span>
                        Kirim Pengaduan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Location Manual -->
    <div id="locationModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-surface-container w-full max-w-md rounded-2xl shadow-2xl">
            <div class="bg-primary-container px-lg py-md flex justify-between items-center">
                <h3 class="text-headline-sm font-semibold text-on-primary-container">Atur Lokasi Manual</h3>
                <button onclick="closeLocationModal()" class="text-on-primary-container hover:text-on-primary p-2 rounded-lg hover:bg-primary/20">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-lg">
                <div class="space-y-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">RT</label>
                        <input type="number" id="manualRT" min="1" max="99"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm">
                    </div>
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">RW</label>
                        <input type="number" id="manualRW" min="1" max="99"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm">
                    </div>
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">Alamat Detail</label>
                        <textarea id="manualAddress" rows="3"
                               class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-sm mt-lg pt-md border-t border-outline-variant/20">
                    <button type="button" onclick="closeLocationModal()" 
                            class="flex-1 bg-surface border border-outline-variant text-on-surface hover:bg-surface-container-high px-lg py-3 rounded-lg text-label-sm font-medium">
                        Batal
                    </button>
                    <button type="button" onclick="saveManualLocation()" 
                            class="flex-1 bg-primary text-on-primary hover:bg-primary/90 px-lg py-3 rounded-lg text-label-sm font-medium">
                        Simpan Lokasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrCode = null;
    let currentLocation = null;
    let currentAddress = '';
    
    document.addEventListener('DOMContentLoaded', async function() {
        // Initialize QR Scanner
        initQRScanner();
        
        // Get user location
        getCurrentLocation();
        
        // Auto-fill form with user data
        const user = {!! json_encode(auth('warga')->user()) !!};
        if (user) {
            // You might want to add phone number to user model
        }
        
        // Update form based on category
        document.querySelectorAll('input[name="kategori"]').forEach(radio => {
            radio.addEventListener('change', function() {
                updateFormByCategory(this.value);
            });
        });
    });
    
    function initQRScanner() {
        const qrScanner = new Html5Qrcode("qrScanner");
        html5QrCode = qrScanner;
        
        const qrScanSuccess = (decodedText, decodedResult) => {
            // Stop scanning
            qrScanner.stop().then(() => {
                document.getElementById('scannerOverlay').classList.add('hidden');
                document.getElementById('qrCodeData').textContent = decodedText;
                document.getElementById('lokasiQr').value = decodedText;
                
                // Parse QR data (assuming format: RT:05|RW:02|Lokasi:Sampah RT 05)
                parseQRData(decodedText);
                
                // Show success message
                showToast('QR Code berhasil di-scan!', 'success');
            });
        };
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        qrScanner.start(
            { facingMode: "environment" },
            config,
            qrScanSuccess,
            () => {} // Error callback
        );
    }
    
    function parseQRData(qrData) {
        try {
            // Assuming QR data format: "RT:05|RW:02|Lokasi:Sampah RT 05|Type:complaint"
            const parts = qrData.split('|');
            const data = {};
            
            parts.forEach(part => {
                const [key, value] = part.split(':');
                if (key && value) {
                    data[key.trim()] = value.trim();
                }
            });
            
            // Update form fields
            if (data.RT) {
                document.getElementById('rtInput').value = data.RT;
                document.getElementById('rtRwDisplay').textContent = `RT ${data.RT}`;
            }
            
            if (data.RW) {
                document.getElementById('rwInput').value = data.RW;
            }
            
            if (data.Lokasi) {
                document.getElementById('addressDisplay').textContent = data.Lokasi;
            }
            
            if (data.Type) {
                const categoryMap = {
                    'sampah': 'sampah',
                    'jalan': 'jalan',
                    'drainase': 'drainase',
                    'penerangan': 'penerangan',
                    'air': 'air'
                };
                
                if (categoryMap[data.Type.toLowerCase()]) {
                    document.querySelector(`input[name="kategori"][value="${categoryMap[data.Type.toLowerCase()]}"]`).checked = true;
                    updateFormByCategory(categoryMap[data.Type.toLowerCase()]);
                }
            }
            
        } catch (error) {
            console.error('Error parsing QR data:', error);
            showToast('Format QR tidak valid', 'error');
        }
    }
    
    async function getCurrentLocation() {
        if (!navigator.geolocation) {
            document.getElementById('locationStatus').textContent = 'Geolocation tidak didukung';
            return;
        }
        
        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            });
            
            currentLocation = position.coords;
            document.getElementById('latitudeInput').value = currentLocation.latitude;
            document.getElementById('longitudeInput').value = currentLocation.longitude;
            
            // Update display
            document.getElementById('coordsDisplay').textContent = 
                `${currentLocation.latitude.toFixed(6)}, ${currentLocation.longitude.toFixed(6)}`;
            
            // Get address from coordinates
            await getAddressFromCoords(currentLocation.latitude, currentLocation.longitude);
            
            document.getElementById('locationStatus').textContent = 'Lokasi terdeteksi';
            document.getElementById('locationStatus').parentElement.classList.remove('bg-primary/10');
            document.getElementById('locationStatus').parentElement.classList.add('bg-green-500/10', 'text-green-600');
            
        } catch (error) {
            console.error('Error getting location:', error);
            document.getElementById('locationStatus').textContent = 'Gagal mendapatkan lokasi';
            document.getElementById('locationStatus').parentElement.classList.remove('bg-primary/10');
            document.getElementById('locationStatus').parentElement.classList.add('bg-red-500/10', 'text-red-600');
        }
    }
    
    async function getAddressFromCoords(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
            const data = await response.json();
            
            if (data.address) {
                const address = data.address;
                currentAddress = data.display_name;
                
                // Update RT/RW if available
                let rt = '01', rw = '01'; // Default values
                
                // Try to extract RT/RW from address
                const rtMatch = currentAddress.match(/RT\s*(\d+)/i);
                const rwMatch = currentAddress.match(/RW\s*(\d+)/i);
                
                if (rtMatch) rt = rtMatch[1];
                if (rwMatch) rw = rwMatch[1];
                
                // Update form fields
                document.getElementById('rtInput').value = rt;
                document.getElementById('rwInput').value = rw;
                document.getElementById('rtRwDisplay').textContent = `RT ${rt}`;
                document.getElementById('addressDisplay').textContent = currentAddress;
                
                // Update current location display
                document.getElementById('currentLocation').textContent = currentAddress.substring(0, 50) + '...';
            }
        } catch (error) {
            console.error('Error getting address:', error);
        }
    }
    
    function updateFormByCategory(category) {
        const judulInput = document.getElementById('judulInput');
        const kategoriMap = {
            'sampah': 'Sampah Menumpuk',
            'jalan': 'Kerusakan Jalan',
            'drainase': 'Drainase Tersumbat',
            'penerangan': 'Lampu Jalan Rusak',
            'air': 'Masalah Air Bersih',
            'lainnya': 'Laporan Lainnya'
        };
        
        if (category !== 'lainnya') {
            judulInput.placeholder = `Contoh: ${kategoriMap[category]} di RT ${document.getElementById('rtInput').value || '05'}`;
        } else {
            judulInput.placeholder = 'Masukkan judul pengaduan';
        }
    }
    
    function previewImage(event) {
        const files = Array.from(event.target.files || []);
        const grid = document.getElementById('imagePreviewGrid');
        grid.innerHTML = '';

        if (files.length === 0) {
            document.getElementById('imagePreview').classList.add('hidden');
            return;
        }

        files.slice(0, 5).forEach((file, i) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const wrap = document.createElement('div');
                wrap.className = 'relative rounded-lg overflow-hidden border border-outline-variant';
                wrap.innerHTML = `
                    <img src="${e.target.result}" class="h-24 w-full object-cover" alt="Preview ${i + 1}">
                    <span class="absolute top-1 right-1 bg-emerald-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px]">${i + 1}</span>
                `;
                grid.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('imagePreview').classList.remove('hidden');
    }
    
    function removeImage() {
        document.getElementById('fotoInput').value = '';
        document.getElementById('imagePreviewGrid').innerHTML = '';
        document.getElementById('imagePreview').classList.add('hidden');
    }
    
    function useManualLocation() {
        document.getElementById('locationModal').classList.remove('hidden');
    }
    
    function closeLocationModal() {
        document.getElementById('locationModal').classList.add('hidden');
    }
    
    function saveManualLocation() {
        const rt = document.getElementById('manualRT').value;
        const rw = document.getElementById('manualRW').value;
        const address = document.getElementById('manualAddress').value;
        
        if (rt && rw && address) {
            document.getElementById('rtInput').value = rt;
            document.getElementById('rwInput').value = rw;
            document.getElementById('rtRwDisplay').textContent = `RT ${rt}`;
            document.getElementById('addressDisplay').textContent = address;
            
            closeLocationModal();
            showToast('Lokasi manual berhasil disimpan', 'success');
        } else {
            showToast('Harap lengkapi semua field lokasi', 'error');
        }
    }
    
    // Form submission
    document.getElementById('pengaduanForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate location
        if (!document.getElementById('rtInput').value || !document.getElementById('rwInput').value) {
            showToast('Harap scan QR atau atur lokasi manual terlebih dahulu', 'error');
            return;
        }
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> Mengirim...';
        
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
                showToast('Pengaduan berhasil dikirim! No. Tiket: ' + result.tiket_id, 'success');
                
                // Reset form
                this.reset();
                removeImage();
                
                // Update location display
                document.getElementById('rtRwDisplay').textContent = '-';
                document.getElementById('addressDisplay').textContent = '-';
                document.getElementById('coordsDisplay').textContent = '-';
                document.getElementById('lokasiQr').value = '';
                document.getElementById('qrCodeData').textContent = 'Menunggu scan...';
                
                // Restart scanner
                if (html5QrCode) {
                    html5QrCode.stop();
                    initQRScanner();
                }
                
                // Show success modal
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
                
            } else {
                // Error
                let errorMessage = 'Terjadi kesalahan saat mengirim pengaduan.';
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
        // Toast implementation (same as before)
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 px-lg py-md rounded-lg shadow-lg z-50 flex items-center gap-sm ${
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
            toast.remove();
        }, 5000);
    }
</script>

<style>
#scannerContainer {
    background: linear-gradient(45deg, #333, #666);
}

#scannerOverlay {
    background: rgba(0, 0, 0, 0.7);
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush
@endsection
