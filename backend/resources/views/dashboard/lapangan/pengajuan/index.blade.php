@extends('layouts.app-lapangan')

@section('title', 'Pengajuan Kebutuhan Logistik')

@section('content')
    <div class="w-full space-y-6 font-sans">

        <!-- 1. TAMPILAN HEADER KHUSUS MOBILE -->
        <div class="flex flex-col gap-3 mb-4 sm:hidden">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('lapangan.dashboard') }}"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:text-blue-600 transition shadow-2xs shrink-0 cursor-pointer">
                        <x-heroicon-s-arrow-left class="w-4 h-4" />
                    </a>

                    <div>
                        <h1 class="text-base font-bold text-slate-900 leading-tight">Pengajuan Logistik</h1>
                        <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">Kalkulasi rekomendasi AI berdasarkan data pengungsi.</p>
                    </div>
                </div>

                <a href="{{ route('lapangan.pengungsi.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition shrink-0 cursor-pointer">
                    <x-heroicon-s-arrow-path class="w-3.5 h-3.5 text-white" />
                    <span>Perbarui</span>
                </a>
            </div>

            <!-- BANNER DRAF OFFLINE MOBILE -->
            <div id="offlineSyncBannerMobile" class="hidden items-center justify-between px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold">
                <span>🔄 <span id="offlineQueueCountMobile">0</span> Draf Offline</span>
            </div>
        </div>

        <!-- 2. TAMPILAN HEADER KHUSUS DESKTOP & TABLET -->
        <div class="hidden sm:flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('lapangan.dashboard') }}"
                    class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition shadow-xs shrink-0 cursor-pointer">
                    <x-heroicon-s-arrow-left class="w-5 h-5 text-slate-600" />
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Kebutuhan Logistik</h1>
                    <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">
                        Angka kebutuhan di bawah dikalkulasi otomatis oleh Machine Learning AI berdasarkan data pengungsi, kategori usia, dan kondisi cuaca terkini.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                <div id="offlineSyncBanner" class="hidden items-center gap-2 px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold">
                    <span>🔄 <span id="offlineQueueCount">0</span> Draf Offline</span>
                </div>

                <a href="{{ route('lapangan.pengungsi.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-md shadow-emerald-500/20 transition cursor-pointer w-full sm:w-auto">
                    <x-heroicon-s-arrow-path class="w-5 h-5 text-white" />
                    <span>Perbarui Data</span>
                </a>
            </div>
        </div>

        <div x-data="{ isLoading: true }" x-init="setTimeout(() => { isLoading = false; }, 800)" class="space-y-6">

            <div x-show="isLoading" class="space-y-6">
                @include('components.skeleton.pengajuan')
            </div>

            <div x-show="!isLoading" style="display: none;" class="space-y-6">
                <div>
                    <x-sub-posko.pengajuan.stats-overview 
                        :pendataan="$pendataan" 
                        :estimasi="$estimasi" 
                        :totalJenis="$totalJenis ?? 12" 
                        :totalPenerimaManfaat="$totalPenerimaManfaat" 
                    />
                </div>

                <!-- SECTION DAFTAR ANTREAN PENGAJUAN OFFLINE -->
                <div id="offlineQueueContainer" class="hidden space-y-4 bg-amber-50/60 border border-amber-200/80 p-5 rounded-2xl shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-200/60 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-amber-500 text-white rounded-xl shadow-xs">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-amber-900">Daftar Pengajuan Tersimpan di HP (Offline Queue)</h3>
                                <p class="text-xs text-amber-700">Data di bawah tersimpan aman di memori HP & otomatis dikirim saat terhubung internet.</p>
                            </div>
                        </div>

                        <button type="button" onclick="triggerManualSync()" 
                            class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Paksa Sync Sekarang</span>
                        </button>
                    </div>

                    <div id="offlineQueueList" class="space-y-3">
                        <!-- Rendered via JavaScript -->
                    </div>
                </div>

                <form id="form-pengajuan" action="{{ route('lapangan.pengajuan.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @include('components.sub-posko.pengajuan.makanan-logistik-section')

                    @include('components.sub-posko.pengajuan.hgiene-kesehatan-section')

                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 md:p-8 space-y-3">
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-pencil-square class="w-4 h-4 text-blue-600" />
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide">Catatan Tambahan Posko (Opsional)</label>
                        </div>
                        <textarea id="catatan_posko" name="catatan_posko" rows="3" maxlength="500"
                            placeholder="Tuliskan catatan khusus atau alasan jika ada penyesuaian angka di atas..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50/40 text-slate-900"></textarea>
                        <div class="text-right text-[11px] text-slate-400 font-medium">0/500 karakter</div>
                    </div>

                    <div class="flex justify-end w-full">
                        <button type="submit" id="btnSubmitPengajuan"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 transition cursor-pointer w-full sm:w-auto">
                            <x-heroicon-s-paper-airplane class="w-5 h-5 text-white" />
                            <span id="btnSubmitText">Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function updateQty(name, step) {
            const input = document.getElementById('input-' + name);
            if (!input) return;

            let currentVal = parseFloat(input.value) || 0;
            let newVal = currentVal + step;

            if (newVal < 0) newVal = 0;
            input.value = Number(newVal.toFixed(2));
        }

        document.addEventListener('DOMContentLoaded', async function() {
            const form = document.getElementById('form-pengajuan');

            // 1. CEK STATUS KONEKSI & SINKRONISASI PENDING QUEUE
            function checkNetworkAndSync() {
                updateOfflineQueueBadge();
                if (navigator.onLine) {
                    syncOfflineQueue();
                }
            }

            window.addEventListener('online', checkNetworkAndSync);
            window.addEventListener('offline', updateOfflineQueueBadge);
            checkNetworkAndSync();

            // 2. INTERCEPT SUBMIT FORM PADA KONDISI OFFLINE
            if (form) {
                form.addEventListener('submit', async function(e) {
                    if (!navigator.onLine) {
                        e.preventDefault(); // Mencegah submit HTTP biasa saat offline

                        const formData = new FormData(form);
                        const formPayload = {};

                        formData.forEach((value, key) => {
                            if (key !== '_token') {
                                formPayload[key] = value;
                            }
                        });

                        const offlineRecord = {
                            id: 'OFFLINE-' + Date.now(),
                            timestamp: new Date().toISOString(),
                            payload: formPayload
                        };

                        let queue = await localforage.getItem('subposko_pengajuan_queue') || [];
                        queue.push(offlineRecord);
                        await localforage.setItem('subposko_pengajuan_queue', queue);

                        updateOfflineQueueBadge();
                        renderOfflineQueueUI();

                        Swal.fire({
                            title: 'Disimpan Secara Offline! 📡',
                            text: 'Sinyal terputus di lokasi sub-posko. Data pengajuan berhasil disimpan di memori HP & akan otomatis terkirim begitu terhubung internet.',
                            icon: 'warning',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#D97706',
                            customClass: {
                                popup: 'rounded-2xl',
                                confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                            }
                        });

                        form.reset();
                    }
                });
            }

            renderOfflineQueueUI();
        });

        // 3. FUNGSI SINKRONISASI ANTREAN OFFLINE KE SERVER
        async function syncOfflineQueue() {
            let queue = await localforage.getItem('subposko_pengajuan_queue') || [];
            if (queue.length === 0) return;

            console.log("⚡ [Offline Sync] Menyinkronkan pengajuan ke server...", queue);

            for (let i = 0; i < queue.length; i++) {
                const item = queue[i];
                try {
                    const response = await fetch("{{ route('lapangan.pengajuan.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(item.payload)
                    });

                    if (!response.ok) {
                        console.error("Gagal sync item:", item.id);
                    }
                } catch (err) {
                    console.error("Error sync network:", err);
                    return; // Hentikan loop jika koneksi belum stabil
                }
            }

            await localforage.removeItem('subposko_pengajuan_queue');
            updateOfflineQueueBadge();
            renderOfflineQueueUI();

            Swal.fire({
                title: 'Sinkronisasi Sukses! 🚀',
                text: 'Seluruh draf pengajuan logistik offline berhasil terkirim ke Posko Komando Utama.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#059669',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                }
            }).then(() => window.location.reload());
        }

        // 4. RENDER UI LIST ANTREAN OFFLINE
        async function renderOfflineQueueUI() {
            const queueContainer = document.getElementById('offlineQueueContainer');
            const queueList = document.getElementById('offlineQueueList');
            if (!queueContainer || !queueList) return;

            let queue = await localforage.getItem('subposko_pengajuan_queue') || [];

            if (queue.length === 0) {
                queueContainer.classList.add('hidden');
                queueList.innerHTML = '';
                return;
            }

            queueContainer.classList.remove('hidden');
            queueList.innerHTML = '';

            queue.forEach((item) => {
                const payload = item.payload;
                const timeFormatted = new Date(item.timestamp).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                let itemsSummary = [];
                if (payload.beras_kg > 0) itemsSummary.push(`Beras: ${payload.beras_kg} Kg`);
                if (payload.air_minum_dus > 0) itemsSummary.push(`Air: ${payload.air_minum_dus} Dus`);
                if (payload.makanan_kaleng_pack > 0) itemsSummary.push(`Mkn Kaleng: ${payload.makanan_kaleng_pack} Pack`);
                if (payload.makanan_bayi_pack > 0) itemsSummary.push(`Mkn Bayi: ${payload.makanan_bayi_pack} Pack`);
                if (payload.minyak_goreng_liter > 0) itemsSummary.push(`Minyak: ${payload.minyak_goreng_liter} L`);
                if (payload.popok_bayi_pcs > 0) itemsSummary.push(`Popok Bayi: ${payload.popok_bayi_pcs} Pcs`);
                if (payload.popok_dewasa_pcs > 0) itemsSummary.push(`Popok Dewasa: ${payload.popok_dewasa_pcs} Pcs`);
                if (payload.pembalut_wanita_pack > 0) itemsSummary.push(`Pembalut: ${payload.pembalut_wanita_pack} Pack`);
                if (payload.hygiene_kit_paket > 0) itemsSummary.push(`Hygiene: ${payload.hygiene_kit_paket} Pkt`);
                if (payload.selimut_pcs > 0) itemsSummary.push(`Selimut: ${payload.selimut_pcs} Pcs`);
                if (payload.matras_terpal_pcs > 0) itemsSummary.push(`Matras/Terpal: ${payload.matras_terpal_pcs} Pcs`);
                if (payload.obat_p3k_paket > 0) itemsSummary.push(`P3K: ${payload.obat_p3k_paket} Pkt`);

                const cardHtml = `
                    <div class="bg-white p-4 rounded-xl border border-amber-200 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 font-mono font-bold text-[10px]">
                                    ${item.id}
                                </span>
                                <span class="text-xs text-slate-400">Dibuat jam ${timeFormatted} WIB</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Internet
                                </span>
                            </div>
                            <div class="text-xs font-semibold text-slate-800 leading-relaxed">
                                📦 <strong>Rincian:</strong> ${itemsSummary.join(' • ') || 'Tidak ada item terpilih'}
                            </div>
                            ${payload.catatan_posko ? `<p class="text-[11px] text-slate-500 italic">"Catatan: ${payload.catatan_posko}"</p>` : ''}
                        </div>
                        <button type="button" onclick="deleteOfflineItem('${item.id}')" 
                            class="text-rose-600 hover:text-rose-800 hover:bg-rose-50 p-2 rounded-lg text-xs font-bold transition flex items-center gap-1 shrink-0 self-end md:self-center">
                            🗑️ Batal & Hapus
                        </button>
                    </div>
                `;
                queueList.insertAdjacentHTML('beforeend', cardHtml);
            });
        }

        async function deleteOfflineItem(itemId) {
            let queue = await localforage.getItem('subposko_pengajuan_queue') || [];
            queue = queue.filter(item => item.id !== itemId);
            await localforage.setItem('subposko_pengajuan_queue', queue);
            renderOfflineQueueUI();
            updateOfflineQueueBadge();
        }

        async function triggerManualSync() {
            if (!navigator.onLine) {
                Swal.fire({
                    title: 'Masih Offline 📡',
                    text: 'Perangkat Anda belum terhubung ke jaringan internet.',
                    icon: 'warning',
                    confirmButtonColor: '#D97706'
                });
                return;
            }
            await syncOfflineQueue();
        }

        async function updateOfflineQueueBadge() {
            const queue = await localforage.getItem('subposko_pengajuan_queue') || [];
            const count = queue.length;

            ['offlineQueueCount', 'offlineQueueCountMobile'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = count;
            });

            ['offlineSyncBanner', 'offlineSyncBannerMobile'].forEach(id => {
                const banner = document.getElementById(id);
                if (banner) {
                    if (count > 0) {
                        banner.classList.remove('hidden');
                        banner.classList.add('flex');
                    } else {
                        banner.classList.add('hidden');
                        banner.classList.remove('flex');
                    }
                }
            });
        }
    </script>
@endpush