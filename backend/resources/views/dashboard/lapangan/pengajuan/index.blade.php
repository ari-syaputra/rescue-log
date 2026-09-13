@extends('layouts.app-lapangan')

@section('title', 'Pengajuan Kebutuhan Logistik')

@section('content')
    <div class="w-full space-y-6 font-sans">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('lapangan.dashboard') }}"
                    class="p-2.5 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition shadow-xs shrink-0">
                    <x-heroicon-s-arrow-left class="w-5 h-5 text-slate-600" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Kebutuhan Logistik</h1>
                    </div>
                    <p class="text-sm text-slate-500 mt-0.5 leading-relaxed">
                        Angka kebutuhan di bawah dikalkulasi otomatis oleh Machine Learning AI berdasarkan jumlah pengungsi, kategori usia, dan kondisi cuaca terkini.
                    </p>
                </div>
            </div>

            <div class="flex justify-end w-full sm:w-auto shrink-0">
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
                        <textarea name="catatan_posko" rows="3" maxlength="500"
                            placeholder="Tuliskan catatan khusus atau alasan jika ada penyesuaian angka di atas..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 outline-none bg-slate-50/40 text-slate-900"></textarea>
                        <div class="text-right text-[11px] text-slate-400 font-medium">0/500 karakter</div>
                    </div>

                    <div class="flex justify-end w-full">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-500/20 transition cursor-pointer w-full sm:w-auto">
                            <x-heroicon-s-paper-airplane class="w-5 h-5 text-white" />
                            <span>Kirim Pengajuan</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

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
            @if (session('error'))
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
            @endif

            @if (session('warning'))
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
            @endif
        });
    </script>
@endsection