<!-- MODAL REGISTRASI POSKO KOMANDO & AKUN KOMANDAN -->
<div id="modalCreatePosko" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl border border-slate-100 transform transition-all">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold">
                    🏛️
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Registrasi Posko Komando</h3>
                    <p class="text-[11px] text-slate-400">Buat unit posko utama & akun login komandan baru</p>
                </div>
            </div>
            <button type="button" onclick="closeModalCreate()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg font-bold transition">✕</button>
        </div>

        <!-- Form Modal -->
        <form action="{{ route('admin.posko.store') }}" method="POST" class="space-y-4">
            @csrf
            @if(isset($bencana) && $bencana)
                <input type="hidden" name="bencana_id" value="{{ $bencana->id }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Posko Komando *</label>
                    <input type="text" name="nama_posko" value="{{ old('nama_posko') }}" required placeholder="Posko Komando Lapangan Sleman" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Komandan / PJ *</label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required placeholder="Mayor Budi Santoso" 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
            </div>

            <!-- Card Section Akun Login -->
            <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-3">
                <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-900">
                    <span>🔑</span> KREDENSIAL AKUN LOGIN KOMANDAN
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">No. WA Darurat *</label>
                        <input type="text" name="kontak_hp" value="{{ old('kontak_hp') }}" required placeholder="08xxxxxxxxxx" 
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Email Login *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="komando@rescuelog.id" 
                               class="w-full px-3 py-2 bg-white border border-indigo-300 rounded-xl text-xs font-medium text-indigo-900 outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Password *</label>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full px-3 py-2 bg-white border border-indigo-300 rounded-xl text-xs outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Fisik Markas / Posko *</label>
                <textarea name="lokasi" required rows="2" placeholder="Alamat lengkap posko..." 
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ old('lokasi', $bpbd->alamat_kantor ?? '') }}</textarea>
            </div>

            <!-- Footer Action Buttons -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalCreate()" 
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-600/20 transition cursor-pointer">
                    Simpan Posko Komando
                </button>
            </div>
        </form>
    </div>
</div>