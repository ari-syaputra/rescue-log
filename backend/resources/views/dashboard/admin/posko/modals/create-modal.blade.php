<!-- MODAL OVERLAY: Tidak bisa ditutup dengan mengklik area luar -->
<div id="modalCreatePosko"
    class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center transition-opacity overflow-y-auto py-6 p-4">
    
    <!-- MODAL CARD CONTAINER (Diubah ke max-w-3xl agar lebih lebar & lega) -->
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-3xl overflow-hidden max-h-[90vh] flex flex-col my-auto">

        <!-- HEADER MODAL (WARNA INDIGO 700) -->
        <div class="bg-indigo-600 px-6 py-5 flex items-center justify-between border-b border-indigo-700 shrink-0">
            <h3 class="text-lg font-bold text-white flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-500/80 text-white rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-building-office-2 class="w-6 h-6" />
                </div>
                <span>Registrasi Posko Komando</span>
            </h3>
            
            <!-- TOMBOL SILANG (X) -->
            <button type="button" 
                onclick="closeModalCreate()" 
                class="text-indigo-200 hover:text-white hover:bg-indigo-500/60 p-2 rounded-xl transition cursor-pointer flex items-center justify-center">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </button>
        </div>

        <!-- FORM BODY (SCROLLABLE) -->
        <form method="POST" action="{{ route('admin.posko.store') }}" class="p-8 space-y-6 overflow-y-auto">
            @csrf
            @if(isset($bencana) && $bencana)
                <input type="hidden" name="bencana_id" value="{{ $bencana->id }}">
            @endif

            <!-- INFORMASI UTAMA POSKO & KOMANDAN -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Posko Komando <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_posko" value="{{ old('nama_posko') }}" required 
                        placeholder="Posko Komando Lapangan Sleman" 
                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Komandan / PJ <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required 
                        placeholder="Mayor Budi Santoso" 
                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-600 focus:bg-white transition-all">
                </div>
            </div>

            <!-- CARD SECTION KREDENSIAL AKUN LOGIN -->
            <div class="p-6 bg-indigo-50/50 rounded-2xl border border-indigo-100/80 space-y-4">
                <div class="flex items-center gap-2 text-xs font-bold text-indigo-900 tracking-wide uppercase">
                    <x-heroicon-s-key class="w-4 h-4 text-indigo-600 shrink-0" />
                    <span>Kredensial Akun Login Komandan</span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1.5">
                            No. WA Darurat <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="kontak_hp" value="{{ old('kontak_hp') }}" required 
                            placeholder="08xxxxxxxxxx" 
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 font-medium">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1.5">
                            Email Login <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                            placeholder="komando@rescuelog.id" 
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-indigo-200 rounded-xl text-indigo-900 font-medium focus:outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1.5">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" required 
                            placeholder="••••••••" 
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-indigo-200 rounded-xl focus:outline-none focus:border-indigo-600">
                    </div>
                </div>
            </div>

            <!-- ALAMAT FISIK POSKO -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat Fisik Markas / Posko <span class="text-rose-500">*</span>
                </label>
                <textarea name="lokasi" required rows="3" 
                    placeholder="Alamat lengkap posko..." 
                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-600 focus:bg-white transition-all resize-none">{{ old('lokasi', $bpbd->alamat_kantor ?? '') }}</textarea>
            </div>

            <!-- FOOTER ACTION BUTTONS -->
<!-- FOOTER ACTION BUTTONS -->
<div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
    <!-- TOMBOL BATAL -->
    <button type="button" 
        onclick="closeModalCreate()" 
        class="h-10 inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 active:bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-slate-200 transition shadow-sm whitespace-nowrap cursor-pointer">
        Batal
    </button>
    
    <!-- TOMBOL SIMPAN -->
    <button type="submit" 
        class="h-10 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-600 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-indigo-200 transition shadow-sm whitespace-nowrap cursor-pointer">
        <span>Simpan Posko Komando</span>
    </button>
</div>
        </form>
    </div>
</div>