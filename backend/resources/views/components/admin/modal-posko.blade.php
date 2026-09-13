<!-- resources/views/components/admin/modal-posko.blade.php -->
<div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div @click.away="openModal = false" class="bg-white rounded-2xl shadow-xl border border-gray-100 max-w-md w-full p-6 transition-all transform">
        
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Aktivasi Posko Komando Utama</h3>
            <button type="button" @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        @if(Route::has('admin.posko.store'))
            <form action="{{ route('admin.posko.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Bencana ID Hidden (Jika dikirim dari controller/query) -->
                <input type="hidden" name="bencana_id" :value="selectedBencanaId">

                <!-- Nama Posko -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Posko Komando</label>
                    <input type="text" name="nama_posko" value="{{ old('nama_posko', 'Posko Komando Lapangan Utama') }}" required
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>

                <!-- Penanggung Jawab & HP -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" required placeholder="Nama Komandan Insiden"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. HP / WA</label>
                        <input type="text" name="kontak_hp" required placeholder="08xxxxxxxxxx"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                    </div>
                </div>

                <!-- Lokasi Operasional Posko -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Fisik Posko</label>
                    <input type="text" name="lokasi" required placeholder="Misal: Lapangan Balai Desa / Kantor BPBD"
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 text-sm">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm">
                        Aktifkan Posko & Rilis Access Key
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>