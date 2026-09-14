@props(['stoks' => []])

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-4 sm:p-6">
    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <x-heroicon-s-cube class="w-5 h-5 text-blue-600 shrink-0" />
        <span>Stok Logistik Tersedia di Pos Lapangan</span>
    </h3>

    <!-- 1. TAMPILAN MOBILE: KARTU (Tampil khusus di HP) -->
    <div class="block sm:hidden space-y-3">
        @forelse($stoks as $stok)
            @php
                $jumlah = $stok->jumlah ?? 0;
            @endphp
            <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-100 flex flex-col gap-2.5">
                <!-- Header Kartu: Nama Barang & Badge Kondisi -->
                <div class="flex items-start justify-between gap-2 border-b border-gray-200/60 pb-2">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm leading-tight">
                            {{ $stok->nama_barang ?? $stok->nama }}
                        </h4>
                        <span class="text-[11px] text-gray-500 font-medium">
                            {{ $stok->kategori ?? 'Logistik Umum' }}
                        </span>
                    </div>
                    <div>
                        @if ($jumlah <= 0)
                            <span class="px-2.5 py-0.5 text-[11px] bg-rose-100 text-rose-700 rounded-full font-semibold">Habis</span>
                        @elseif($jumlah <= 10)
                            <span class="px-2.5 py-0.5 text-[11px] bg-amber-100 text-amber-700 rounded-full font-semibold">Menipis</span>
                        @else
                            <span class="px-2.5 py-0.5 text-[11px] bg-emerald-100 text-emerald-700 rounded-full font-semibold">Aman</span>
                        @endif
                    </div>
                </div>

                <!-- Info Jumlah Stok & Terakhir Diperbarui -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Jumlah Stok</span>
                        <span class="font-bold text-sm {{ $jumlah <= 10 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $jumlah }} {{ $stok->satuan ?? 'Unit' }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Diperbarui</span>
                        <span class="text-gray-500 font-medium">
                            {{ $stok->updated_at ? $stok->updated_at->format('d M Y, H:i') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-400 text-xs font-medium bg-gray-50 rounded-xl border border-dashed border-gray-200">
                Belum ada data stok inventaris yang terdaftar di pos lapangan ini.
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP: TABEL STANDAR (Sembunyi di HP, Tampil di Desktop/Tablet) -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="py-3 px-4">Nama Barang</th>
                    <th class="py-3 px-4">Kategori</th>
                    <th class="py-3 px-4">Jumlah / Stok</th>
                    <th class="py-3 px-4">Kondisi</th>
                    <th class="py-3 px-4 text-right">Terakhir Diperbarui</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($stoks as $stok)
                    <tr class="hover:bg-gray-50/50 transition">
                        <!-- Nama Barang -->
                        <td class="py-3.5 px-4 font-bold text-gray-900">
                            {{ $stok->nama_barang ?? $stok->nama }}
                        </td>

                        <!-- Kategori -->
                        <td class="py-3.5 px-4 text-gray-600">
                            {{ $stok->kategori ?? 'Logistik Umum' }}
                        </td>

                        <!-- Jumlah / Stok -->
                        <td class="py-3.5 px-4 font-semibold {{ ($stok->jumlah ?? 0) <= 10 ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $stok->jumlah ?? 0 }} {{ $stok->satuan ?? 'Unit' }}
                        </td>

                        <!-- Kondisi / Status Stok -->
                        <td class="py-3.5 px-4">
                            @if (($stok->jumlah ?? 0) <= 0)
                                <span class="px-2.5 py-0.5 text-xs bg-rose-50 text-rose-700 rounded-md font-medium">Habis</span>
                            @elseif(($stok->jumlah ?? 0) <= 10)
                                <span class="px-2.5 py-0.5 text-xs bg-amber-50 text-amber-700 rounded-md font-medium">Menipis</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs bg-emerald-50 text-emerald-700 rounded-md font-medium">Aman</span>
                            @endif
                        </td>

                        <!-- Terakhir Diperbarui -->
                        <td class="py-3.5 px-4 text-right text-xs text-gray-400">
                            {{ $stok->updated_at ? $stok->updated_at->format('d M Y, H:i') . ' WIB' : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400 text-xs font-medium">
                            Belum ada data stok inventaris yang terdaftar di pos lapangan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>