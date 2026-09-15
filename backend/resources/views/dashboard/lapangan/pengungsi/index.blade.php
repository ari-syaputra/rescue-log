@extends('layouts.app-lapangan')

@section('content')
    <div class="w-full space-y-6" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 400)">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <x-sub-posko.page-header title="Pendataan Pengungsi"
                description="Pantau demografi dan riwayat pengungsi di posko Anda.">
            </x-sub-posko.page-header>

            <div class="flex items-center gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                <!-- BANNER INDIKATOR PENDING OFFLINE QUEUE -->
                <div id="offlineSyncBanner" class="hidden items-center gap-2 px-3 py-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl text-xs font-bold shadow-xs">
                    <span>🔄 <span id="offlineQueueCount">0</span> Draf Offline</span>
                </div>

                <button onclick="openPendataanModal()"
                    class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-sm font-medium transition shadow-sm gap-2 cursor-pointer w-full sm:w-auto">
                    <x-heroicon-s-plus class="w-5 h-5 text-white shrink-0 stroke-[3.5]" />
                    <span>Perbarui Data Pengungsi</span>
                </button>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl text-sm font-medium mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div x-show="isLoading" class="space-y-6 w-full" style="display: none;">
            <x-skeleton.loading />
        </div>

        <div x-show="!isLoading" class="w-full space-y-6">
            @if ($isFirstTime)
                <x-skeleton.kosong />
            @else
                <div class="space-y-6 w-full">
                    <x-sub-posko.stat-card :pendataan-terakhir="$pendataan_terakhir" />

                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full p-4 sm:p-0">
                        <div
                            class="px-2 py-2 sm:px-6 sm:py-4 sm:border-b sm:border-gray-200 sm:bg-gray-50 flex items-center justify-between mb-3 sm:mb-0">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                                    <x-heroicon-s-clock class="w-5 h-5" />
                                </div>
                                <h3 class="font-bold text-gray-800 text-base sm:text-lg">Riwayat Perubahan Data</h3>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Total: {{ count($riwayat_pendataan) }}
                                data</span>
                        </div>

                        <!-- 1. TAMPILAN MOBILE: KARTU (Tampil di HP) -->
                        <div class="block sm:hidden space-y-3">
                            @forelse ($riwayat_pendataan as $riwayat)
                                <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-200/80 flex flex-col gap-2.5">
                                    <!-- Header Kartu: Tanggal Update & Badge Total -->
                                    <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                                        <span class="flex items-center gap-1.5 text-xs font-semibold text-gray-600">
                                            <x-heroicon-s-calendar class="w-4 h-4 text-gray-400" />
                                            {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : '-' }}
                                        </span>
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-bold bg-blue-100 text-blue-700 rounded-full">
                                            {{ $riwayat->total_pengungsi ?? 0 }} Jiwa
                                        </span>
                                    </div>

                                    <!-- Detail Fasilitas & Cuaca -->
                                    <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                                        <div>
                                            <span
                                                class="text-gray-400 block text-[10px] uppercase font-semibold flex items-center gap-1">
                                                <x-heroicon-s-building-office-2 class="w-3.5 h-3.5 text-emerald-500" /> Tipe
                                                Fasilitas
                                            </span>
                                            <span class="font-medium text-gray-800">
                                                {{ $riwayat->tipe_tempat ?? '-' }}
                                            </span>
                                        </div>

                                        <div class="text-right">
                                            <span
                                                class="text-gray-400 block text-[10px] uppercase font-semibold flex items-center justify-end gap-1">
                                                <x-heroicon-s-sun class="w-3.5 h-3.5 text-amber-500" /> Cuaca Tercatat
                                            </span>
                                            <span class="font-medium text-gray-700">
                                                {{ $riwayat->cuaca ?? '-' }}
                                                @if (isset($riwayat->suhu_celcius))
                                                    <span
                                                        class="text-[11px] text-gray-400 font-normal">({{ $riwayat->suhu_celcius }}°C)</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="py-8 text-center text-gray-400 italic text-xs bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                    Belum ada riwayat perubahan data.
                                </div>
                            @endforelse
                        </div>

                        <!-- 2. TAMPILAN DESKTOP: TABEL STANDAR (Sembunyi di HP) -->
                        <div class="hidden sm:block overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="text-gray-500 bg-white border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-calendar class="w-4 h-4 text-gray-400" /> Tanggal Update
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-users class="w-4 h-4 text-blue-500" /> Total Pengungsi
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold">
                                            <span class="flex items-center gap-1.5">
                                                <x-heroicon-s-building-office-2 class="w-4 h-4 text-emerald-500" /> Tipe
                                                Fasilitas
                                            </span>
                                        </th>
                                        <th class="px-6 py-3 font-semibold text-right">
                                            <span class="inline-flex items-center justify-end gap-1.5">
                                                <x-heroicon-s-sun class="w-4 h-4 text-amber-500" /> Cuaca Tercatat
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse ($riwayat_pendataan as $riwayat)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-blue-600 font-bold">
                                                {{ $riwayat->total_pengungsi ?? 0 }} Jiwa
                                            </td>
                                            <td class="px-6 py-4 text-gray-700">
                                                {{ $riwayat->tipe_tempat ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-500">
                                                {{ $riwayat->cuaca ?? '-' }}
                                                @if (isset($riwayat->suhu_celcius))
                                                    <span
                                                        class="text-xs text-gray-400">({{ $riwayat->suhu_celcius }}°C)</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                                Belum ada riwayat perubahan data.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @include('dashboard.lapangan.pengungsi._modal_form')

    </div>

    <!-- Script Storage Offline LocalForage & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/localforage@1.10.0/dist/localforage.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalForm = document.getElementById('form-pendataan-pengungsi');
            const offlineBanner = document.getElementById('offlineSyncBanner');
            const queueCountElem = document.getElementById('offlineQueueCount');

            // Update badge hitung draf offline saat halaman dimuat
            async function updateOfflineQueueBadge() {
                const queue = await localforage.getItem('subposko_pendataan_queue') || [];
                if (queue.length > 0) {
                    offlineBanner.classList.remove('hidden');
                    offlineBanner.classList.add('flex');
                    queueCountElem.textContent = queue.length;
                } else {
                    offlineBanner.classList.add('hidden');
                    offlineBanner.classList.remove('flex');
                }
            }
            updateOfflineQueueBadge();

            // 1. MONITORING KONEKSI INTERNET
            window.addEventListener('online', handleNetworkChange);
            window.addEventListener('offline', handleNetworkChange);
            
            function handleNetworkChange() {
                if (navigator.onLine) {
                    syncOfflinePendataanQueue();
                }
            }

            // 2. INTERSEPSI SUBMIT FORM SAAT OFFLINE
            if (modalForm) {
                modalForm.addEventListener('submit', async function(e) {
                    if (!navigator.onLine) {
                        e.preventDefault(); // Cegah error jaringan bawaan browser

                        const formData = new FormData(modalForm);
                        const formPayload = {};
                        formData.forEach((value, key) => {
                            if (key !== '_token') {
                                formPayload[key] = value;
                            }
                        });

                        const offlineRecord = {
                            id: 'OFFLINE-PENDATAAN-' + Date.now(),
                            timestamp: new Date().toISOString(),
                            payload: formPayload
                        };

                        // Simpan ke IndexedDB browser via localforage
                        let queue = await localforage.getItem('subposko_pendataan_queue') || [];
                        queue.push(offlineRecord);
                        await localforage.setItem('subposko_pendataan_queue', queue);

                        updateOfflineQueueBadge();

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Tersimpan Secara Offline! 📡',
                                text: 'Koneksi internet terputus. Data pendataan pengungsi berhasil disimpan di memori HP dan akan otomatis disinkronkan begitu ada sinyal.',
                                icon: 'warning',
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#D97706',
                                customClass: {
                                    popup: 'rounded-2xl',
                                    confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                                }
                            });
                        } else {
                            alert("Tersimpan Secara Offline! Data disimpan di memori ponsel.");
                        }

                        modalForm.reset();
                        if (typeof closePendataanModal === 'function') {
                            closePendataanModal();
                        }
                    }
                });
            }

            // 3. FUNGSI OTOMATIS SYNC KE SERVER SAAT KONEKSI KEMBALI
            async function syncOfflinePendataanQueue() {
                let queue = await localforage.getItem('subposko_pendataan_queue') || [];
                if (queue.length === 0) return;

                for (let i = 0; i < queue.length; i++) {
                    const item = queue[i];
                    try {
                        const response = await fetch("{{ route('lapangan.pengungsi.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(item.payload)
                        });

                        if (!response.ok) {
                            console.error("Gagal menyinkronkan draf pendataan:", item.id);
                            return;
                        }
                    } catch (err) {
                        console.error("Sinkronisasi gagal karena jaringan terputus kembali:", err);
                        return;
                    }
                }

                // Bersihkan antrean lokal setelah sukses terkirim ke database PostgreSQL
                await localforage.removeItem('subposko_pendataan_queue');
                updateOfflineQueueBadge();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Sinkronisasi Otomatis Sukses! 🚀',
                        text: 'Data pendataan pengungsi offline berhasil dikirim ke server pusat.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#059669',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-sm'
                        }
                    }).then(() => {
                        window.location.reload();
                    });
                }
            }
        });
    </script>
@endsection