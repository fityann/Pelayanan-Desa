<!-- Modal Form Data Penduduk -->
<div id="pendudukModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-surface-container w-full max-w-2xl rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden">
        <!-- Header Modal -->
        <div class="bg-primary-container px-lg py-md flex justify-between items-center">
            <div>
                <h3 class="text-headline-sm font-semibold text-on-primary-container" id="pendudukModalTitle">Tambah Data Penduduk</h3>
                <p class="text-label-sm text-on-primary-container/70 mt-xs">Data Individu Penduduk Desa</p>
            </div>
            <button onclick="closePendudukModal()" class="text-on-primary-container hover:text-on-primary p-2 rounded-lg hover:bg-primary/20 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-lg overflow-y-auto" style="max-height: calc(90vh - 80px)">
            @if ($errors->any())
                <div class="mb-md p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
                    <div class="font-bold mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <span>Terdapat kesalahan pengisian:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="pendudukForm" action="{{ route('admin.penduduk.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="pendudukId">
                
                <!-- NIK & KK -->
                <div class="grid grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            NIK <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="nik" id="nik" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-mono"
                                   placeholder="16 digit angka"
                                   maxlength="16"
                                   inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   required>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">badge</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            No. KK
                        </label>
                        <div class="relative">
                            <input type="text" name="no_kk" id="no_kk" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-mono"
                                   placeholder="Kosongkan jika belum"
                                   maxlength="16"
                                   inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">group</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Nama & Jenis Kelamin -->
                <div class="grid grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Nama Lengkap <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="nama" id="nama" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="Nama lengkap"
                                   required>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">person</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Jenis Kelamin <span class="text-error">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-sm">
                            <label class="relative">
                                <input type="radio" name="jenis_kelamin" value="L" class="sr-only peer" checked>
                                <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                    <span class="material-symbols-outlined block text-lg mb-xs">male</span>
                                    <span class="text-label-sm font-medium">Laki-laki</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="jenis_kelamin" value="P" class="sr-only peer">
                                <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-pink-500 peer-checked:bg-pink-50 peer-checked:text-pink-600 transition-all">
                                    <span class="material-symbols-outlined block text-lg mb-xs">female</span>
                                    <span class="text-label-sm font-medium">Perempuan</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Tempat & Tanggal Lahir -->
                <div class="grid grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Tempat Lahir
                        </label>
                        <div class="relative">
                            <input type="text" name="tempat_lahir" id="tempat_lahir" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   placeholder="Kota/Kabupaten">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">location_on</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Tanggal Lahir
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                   class="w-full bg-surface border border-outline-variant rounded-lg px-md py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                
                <!-- Alamat & RT/RW -->
                <div class="grid grid-cols-2 gap-md mb-md">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">
                            Alamat
                        </label>
                        <div class="relative">
                            <textarea name="alamat" id="alamat" rows="3"
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-14 py-3 text-body-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all resize-none"
                                   placeholder="Alamat lengkap"></textarea>
                            <div class="absolute right-4 top-3 text-on-surface-variant">
                                <span class="material-symbols-outlined text-base">home</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-md">
                            <label class="block text-label-sm font-medium text-on-surface mb-xs">RT</label>
                            <input type="number" name="rt" id="rt" 
                                   min="1" max="99" placeholder="cth: 01"
                                   class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm">
                            <input type="hidden" name="rw" id="rw" value="01">
                        </div>
                        
                        <div class="mb-md">
                            <label class="block text-label-sm font-medium text-on-surface mb-xs">Agama</label>
                            <select name="agama" id="agama" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Status Perkawinan & Pendidikan -->
                <div class="grid grid-cols-2 gap-md mb-lg">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">Status Perkawinan</label>
                        <select name="status_perkawinan" id="status_perkawinan" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                            <option value="">Pilih Status</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" id="pendidikan" class="w-full bg-surface border border-outline-variant rounded-lg px-md py-2 text-body-sm">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D1/D2/D3">D1/D2/D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                </div>
                
                <!-- Pekerjaan & Kewarganegaraan -->
                <div class="grid grid-cols-2 gap-md mb-lg">
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" 
                               class="w-full bg-surface border border-outline-variant rounded-lg pl-md pr-11 py-3 text-body-sm"
                               placeholder="Pekerjaan">
                    </div>
                    
                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-xs">Kewarganegaraan</label>
                        <div class="grid grid-cols-2 gap-sm">
                            <label class="relative">
                                <input type="radio" name="kewarganegaraan" value="WNI" class="sr-only peer" checked>
                                <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all">
                                    <span class="material-symbols-outlined block text-lg mb-xs">flag</span>
                                    <span class="text-label-sm font-medium">WNI</span>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="kewarganegaraan" value="WNA" class="sr-only peer">
                                <div class="bg-surface border border-outline-variant rounded-lg p-sm text-center cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all">
                                    <span class="material-symbols-outlined block text-lg mb-xs">language</span>
                                    <span class="text-label-sm font-medium">WNA</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Modal -->
                <div class="flex gap-sm pt-md border-t border-outline-variant/20">
                    <button type="button" onclick="closePendudukModal()" 
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