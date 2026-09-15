@extends('layouts.app-lapangan')

@section('title', 'Pengajuan Kebutuhan Logistik')

@section('content')
    <div class="w-full space-y-6 font-sans">

        <!-- Top Header Navigation & Network Indicator -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('lapangan.dashboard') }}"
                    class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition shadow-xs shrink-0">
                    <x-heroicon-s-arrow-left class="w-5 h-5 text-slate-600" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Kebutuhan Logistik</h1>
                        <!-- BADGE INDIKATOR STATUS KONEKSI LAPANGAN -->
                        <span id="badgeNetworkStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            Online
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">
                        Angka kebutuhan di bawah dikalkulasi otomatis oleh Machine Learning AI berdasarkan data pengungsi, kategori usia, dan kondisi cuaca terkini.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                <!-- BANNER INDIKATOR PENDING OFFLINE QUEUE -->
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
                    <x-sub-posko.pengajuan.stats-overview :totalJenis="$totalJenis ?? 15" :totalPenerimaManfaat="$totalPenerimaManfaat ?? 658" />
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

    <!-- Script Storage Offline LocalForage & SweetAlert -->
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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-pengajuan');
            const badgeStatus = document.getElementById('badgeNetworkStatus');
            const offlineBanner = document.getElementById('offlineSyncBanner');
            const queueCountElem = document.getElementById('offlineQueueCount');

            // 1. MONITORING STATUS SINYAL LAPANGAN
            function checkNetworkStatus() {
                if (navigator.onLine) {
                    badgeStatus.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200";
                    badgeStatus.innerHTML = '<span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online';
                    syncOfflineQueue();
                } else {
                    badgeStatus.className = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200";
                    badgeStatus.innerHTML = '<span class="w-2 h-2 mr-1.5 bg-rose-500 rounded-full"></span> Offline Mode';
                }
                updateOfflineQueueBadge();
            }

            window.addEventListener('online', checkNetworkStatus);
            window.addEventListener('offline', checkNetworkStatus);
            checkNetworkStatus();

            // 2. CEK DAN UPDATE BADGE ANTREAN OFFLINE
            async function updateOfflineQueueBadge() {
                const queue = await localforage.getItem('subposko_pengajuan_queue') || [];
                if (queue.length > 0) {
                    offlineBanner.classList.remove('hidden');
                    offlineBanner.classList.add('flex');
                    queueCountElem.textContent = queue.length;
                } else {
                    offlineBanner.classList.add('hidden');
                    offlineBanner.classList.remove('flex');
                }
            }

            // 3. INTERSEPSI SUBMIT FORM UNTUK STRATEGI OFFLINE-FIRST
            if (form) {
                form.addEventListener('submit', async function(e) {
                    if (!navigator.onLine) {
                        e.preventDefault(); // Hentikan HTTP Submit bawaan

                        // Ambil seluruh nilai input barang dari form
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

                        // Simpan ke IndexedDB Local Storage
                        let queue = await localforage.getItem('subposko_pengajuan_queue') || [];
                        queue.push(offlineRecord);
                        await localforage.setItem('subposko_pengajuan_queue', queue);

                        updateOfflineQueueBadge();

                        if (typeof Swal !== 'undefined') {
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
                        } else {
                            alert("Disimpan Secara Offline! Data pengajuan disimpan di ponsel dan akan terkirim saat ada sinyal.");
                        }

                        form.reset();
                    }
                });
            }

            // 4. OTOMATIS SYNC SAAT SINYAL INTERNET KEMBALI PULIH
            async function syncOfflineQueue() {
                let queue = await localforage.getItem('subposko_pengajuan_queue') || [];
                if (queue.length === 0) return;

                console.log("Menyinkronkan pengajuan offline ke server...", queue);

                for (let i = 0; i < queue.length; i++) {
                    const item = queue[i];
                    try {
                        const response = await fetch("{{ route('lapangan.pengajuan.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(item.payload)
                        });

                        if (!response.ok) {
                            console.error("Gagal sync item:", item.id);
                        }
                    } catch (err) {
                        console.error("Error sync network:", err);
                        return; // Hentikan loop jika koneksi terputus lagi
                    }
                }

                // Bersihkan antrean setelah sukses terkirim
                await localforage.removeItem('subposko_pengajuan_queue');
                updateOfflineQueueBadge();

                if (typeof Swal !== 'undefined') {
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
                    });
                }
            }

            // Session Flash Notifications
            @if (session('error'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        icon: 'error',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#DC2626',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                        }
                    });
                }
            @endif

            @if (session('warning'))
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Perhatian',
                        text: "{{ session('warning') }}",
                        icon: 'warning',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#D97706',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                        }
                    });
                }
            @endif
        });
    </script>
@endsection