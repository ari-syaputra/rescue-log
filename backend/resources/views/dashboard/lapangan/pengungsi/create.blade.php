@extends('layouts.app-lapangan')

@section('content')
<div class="w-full space-y-6">

    <!-- HEADER PAGE -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-3.5">
            <!-- Tombol Kembali Bulat Ikonik -->
            <a href="{{ route('lapangan.pengungsi.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition shadow-xs shrink-0 cursor-pointer"
               title="Kembali ke Riwayat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">Form Pendataan Pengungsi</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pastikan data diisi sesuai kondisi real-time di lapangan untuk kalkulasi logistik AI.</p>
            </div>
        </div>

        <div id="offlineSyncBanner" class="hidden items-center gap-2 px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold shadow-xs">
            <span>🔄 Mode Offline Aktif</span>
        </div>
    </div>

    <!-- FORM DEDICATED (GRID 2 KOLOM) -->
    <form id="form-pendataan-pengungsi" action="{{ route('lapangan.pengungsi.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KOLOM KIRI: INPUT FORM (8 KOLOM) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 1. KONDISI DEMOGRAFI -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <h3 class="text-sm font-bold text-slate-800">1. Rincian Demografi Pengungsi</h3>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium"><span class="text-rose-500">*</span> Wajib Diisi</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Balita -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Anak Balita (0-5 th) <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <input type="number" name="balita" id="input-balita" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->balita ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                            </div>
                        </div>

                        <!-- Dewasa -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Dewasa (18-59 th) <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="number" name="dewasa" id="input-dewasa" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->dewasa ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                            </div>
                        </div>

                        <!-- Lansia -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lansia (&ge; 60 th) <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <input type="number" name="lansia" id="input-lansia" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->lansia ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                            </div>
                        </div>

                        <!-- Ibu Hamil -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ibu Hamil <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 p-1.5 bg-rose-50 text-rose-500 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </div>
                                <input type="number" name="ibu_hamil" id="input-ibu-hamil" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->ibu_hamil ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                            </div>
                        </div>

                        <!-- Disabilitas -->
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Disabilitas <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute left-3 p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                </div>
                                <input type="number" name="disabilitas" id="input-disabilitas" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->disabilitas ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. KONDISI & FASILITAS TEMPAT -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V12a2 2 0 012-2h2a2 2 0 012 2v9"/></svg>
                        <h3 class="text-sm font-bold text-slate-800">2. Kondisi & Fasilitas Tempat</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tipe Tempat -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Tempat <span class="text-rose-500">*</span></label>
                            <select name="tipe_tempat" id="input-tipe-tempat" onchange="cekValidasiForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none bg-white" required>
                                <option value="">Pilih tipe tempat</option>
                                @foreach(['Balai Desa', 'Masjid/Tempat Ibadah', 'Sekolah', 'Tenda/Lapangan'] as $tipe)
                                    <option value="{{ $tipe }}" {{ ($pendataan_terakhir->tipe_tempat ?? '') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Akses Air -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akses Air <span class="text-rose-500">*</span></label>
                            <select name="akses_air" id="input-akses-air" onchange="cekValidasiForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none bg-white" required>
                                <option value="">Pilih akses air</option>
                                @foreach(['Cukup', 'Terbatas', 'Tidak Ada'] as $air)
                                    <option value="{{ $air }}" {{ ($pendataan_terakhir->akses_air ?? '') == $air ? 'selected' : '' }}>{{ $air }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Akses Jalan -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akses Jalan <span class="text-rose-500">*</span></label>
                            <select name="akses_jalan" id="input-akses-jalan" onchange="cekValidasiForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none bg-white" required>
                                <option value="">Pilih akses jalan</option>
                                @foreach(['Mobil/Truk Bisa Masuk', 'Hanya Motor', 'Harus Jalan Kaki'] as $jalan)
                                    <option value="{{ $jalan }}" {{ ($pendataan_terakhir->akses_jalan ?? '') == $jalan ? 'selected' : '' }}>{{ $jalan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lama Pengungsian -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lama Pengungsian <span class="text-rose-500">*</span></label>
                            <select name="lama_pengungsian" id="input-lama-pengungsian" onchange="cekValidasiForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none bg-white" required>
                                <option value="">Pilih lama pengungsian</option>
                                @for($i = 1; $i <= 30; $i++)
                                    <option value="{{ $i }}" {{ ($pendataan_terakhir->lama_pengungsian ?? 1) == $i ? 'selected' : '' }}>{{ $i }} Hari</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: RINGKASAN & ACTION (4 KOLOM) -->
            <div class="lg:col-span-4 space-y-6">

                <!-- CARD BIRU RINGKASAN TOTAL -->
                <div class="bg-linear-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-md relative overflow-hidden flex flex-col justify-between">
                    <div class="relative z-10">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-blue-100 mb-1">TOTAL PENGUNGSI DIBUAT</div>
                        <div class="flex items-baseline gap-2">
                            <input type="number" 
                                   name="total_pengungsi" 
                                   id="input-total-pengungsi"
                                   value="{{ $pendataan_terakhir->total_pengungsi ?? '0' }}" 
                                   class="text-4xl font-black bg-transparent text-white border-none p-0 w-32 outline-none cursor-not-allowed" 
                                   readonly>
                            <span class="text-base font-bold text-blue-100">JIWA</span>
                        </div>
                    </div>
                    <div class="relative z-10 text-[11px] text-blue-100/90 pt-4 mt-4 border-t border-blue-400/40 font-medium">
                        Balita <strong id="lbl-balita">0</strong> • Dewasa <strong id="lbl-dewasa">0</strong> • Lansia <strong id="lbl-lansia">0</strong>
                    </div>
                </div>

                <!-- WIDGET CUACA OTOMATIS -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-blue-900 tracking-wider uppercase">INFORMASI LOKASI & CUACA</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-200/60 text-blue-700">OTOMATIS</span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-center py-1 border-b border-blue-100">
                            <span class="text-slate-500">Waktu Pendataan</span>
                            <span id="waktu-realtime" class="font-bold text-slate-800">-</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-blue-100">
                            <span class="text-slate-500">Suhu Lokasi</span>
                            <span id="cuaca-suhu" class="font-bold text-slate-800">Memuat...</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Kondisi Cuaca</span>
                            <span id="cuaca-kondisi" class="font-bold text-slate-800">Memuat...</span>
                        </div>
                    </div>

                    <input type="hidden" name="suhu_celcius" id="input-suhu">
                    <input type="hidden" name="cuaca" id="input-cuaca">
                </div>

                <!-- STATUS KELENGKAPAN & BUTTON SUBMIT -->
                <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
                    <div id="alert-form-status" class="flex items-center gap-3">
                        <div class="p-2 rounded-full bg-rose-100 text-rose-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h5 id="alert-status-title" class="text-xs font-bold text-slate-800">Beberapa data belum lengkap</h5>
                            <p id="alert-status-sub" class="text-[11px] text-slate-400">Lengkapi data untuk menyimpan.</p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 pt-2 border-t border-slate-100">
                        <button type="submit" id="btn-submit-pendataan" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            <span>Simpan & Hitung Logistik AI</span>
                        </button>
                        <a href="{{ route('lapangan.pengungsi.index') }}" class="w-full text-center px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                            Batal
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Realtime Clock & Weather API
        const now = new Date();
        const formatWaktu = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WIB`;
        document.getElementById('waktu-realtime').innerText = formatWaktu;

        const lat = "{{ $posko->latitude ?? -7.7956 }}";
        const lon = "{{ $posko->longitude ?? 110.3695 }}";

        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`)
            .then(res => res.json())
            .then(data => {
                if(data && data.current_weather) {
                    const temp = data.current_weather.temperature;
                    const code = data.current_weather.weathercode;

                    let deskripsi = "Cerah / Berawan";
                    if(code >= 51 && code <= 67) deskripsi = "Hujan Ringan/Sedang";
                    if(code >= 80 && code <= 99) deskripsi = "Hujan Deras / Badai";

                    document.getElementById('cuaca-suhu').innerText = temp + " °C";
                    document.getElementById('cuaca-kondisi').innerText = deskripsi;
                    document.getElementById('input-suhu').value = temp;
                    document.getElementById('input-cuaca').value = deskripsi;
                }
            })
            .catch(() => {
                document.getElementById('cuaca-suhu').innerText = "28.5 °C";
                document.getElementById('cuaca-kondisi').innerText = "Berawan";
                document.getElementById('input-suhu').value = 28.5;
                document.getElementById('input-cuaca').value = "Berawan";
            });

        hitungTotalPengungsi();

        // 2. Intersepsi Offline Form Submit
        const formElem = document.getElementById('form-pendataan-pengungsi');
        if (formElem) {
            formElem.addEventListener('submit', async function(e) {
                if (!navigator.onLine) {
                    e.preventDefault();

                    const formData = new FormData(formElem);
                    const formPayload = {};
                    formData.forEach((value, key) => {
                        if (key !== '_token') formPayload[key] = value;
                    });

                    const offlineRecord = {
                        id: 'OFFLINE-PENDATAAN-' + Date.now(),
                        timestamp: new Date().toISOString(),
                        payload: formPayload
                    };

                    let queue = await localforage.getItem('subposko_pendataan_queue') || [];
                    queue.push(offlineRecord);
                    await localforage.setItem('subposko_pendataan_queue', queue);

                    Swal.fire({
                        title: 'Tersimpan Secara Offline! 📡',
                        text: 'Koneksi terputus. Data pendataan berhasil disimpan di memori ponsel dan akan disinkronkan otomatis saat ada sinyal.',
                        icon: 'warning',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#D97706',
                        customClass: { popup: 'rounded-2xl', confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm' }
                    }).then(() => {
                        window.location.href = "{{ route('lapangan.pengungsi.index') }}";
                    });
                }
            });
        }
    });

    function hitungTotalPengungsi() {
        const balita = parseInt(document.getElementById('input-balita').value) || 0;
        const dewasa = parseInt(document.getElementById('input-dewasa').value) || 0;
        const lansia = parseInt(document.getElementById('input-lansia').value) || 0;

        const total = balita + dewasa + lansia;
        document.getElementById('input-total-pengungsi').value = total;

        document.getElementById('lbl-balita').innerText = balita;
        document.getElementById('lbl-dewasa').innerText = dewasa;
        document.getElementById('lbl-lansia').innerText = lansia;

        cekValidasiForm();
    }

    function cekValidasiForm() {
        const fields = [
            'input-balita', 'input-dewasa', 'input-lansia', 
            'input-ibu-hamil', 'input-disabilitas', 'input-tipe-tempat',
            'input-akses-air', 'input-akses-jalan', 'input-lama-pengungsian'
        ];

        let sisaKosong = 0;
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el || el.value === '' || el.value === null) sisaKosong++;
        });

        const alertTitle = document.getElementById('alert-status-title');
        const alertSub = document.getElementById('alert-status-sub');
        const alertBox = document.getElementById('alert-form-status');

        if (sisaKosong > 0) {
            alertTitle.innerText = `${sisaKosong} data wajib belum lengkap`;
            alertTitle.className = 'text-xs font-bold text-slate-800';
            alertSub.innerText = 'Lengkapi semua data untuk menyimpan.';
            alertBox.querySelector('div').className = 'p-2 rounded-full bg-rose-100 text-rose-600';
        } else {
            alertTitle.innerText = 'Semua data wajib lengkap';
            alertTitle.className = 'text-xs font-bold text-emerald-700';
            alertSub.innerText = 'Siap dikirim ke server AI.';
            alertBox.querySelector('div').className = 'p-2 rounded-full bg-emerald-100 text-emerald-600';
        }
    }
</script>
@endsection