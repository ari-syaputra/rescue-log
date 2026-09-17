@props(['subPosko'])

<div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-full space-y-3">
    <!-- Header Card Dokumentasi -->
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900">Dokumentasi Posko</h3>
        <span class="text-xs text-slate-400 font-medium">Foto Lapangan</span>
    </div>

    <!-- Area Grid Foto -->
    <div class="grid grid-cols-3 gap-3 flex-1 min-h-[260px]">
        @forelse($subPosko->fotos ?? [] as $foto)
            <div class="h-28 w-full rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative group cursor-pointer"
                 onclick="openLightbox('{{ asset('storage/' . $foto->path_file) }}', '{{ basename($foto->path_file) }}')"
                 title="Klik untuk memperbesar">
                <img src="{{ asset('storage/' . $foto->path_file) }}" alt="Dokumentasi" class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-slate-400 text-xs flex flex-col items-center justify-center space-y-2">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Belum ada foto dokumentasi diunggah dari lapangan.</span>
            </div>
        @endforelse
    </div>

    <!-- Footer Status Dokumentasi -->
    <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100 text-slate-500">
        <span>Total Foto: <strong class="text-slate-700">{{ count($subPosko->fotos ?? []) }} Berkas</strong></span>
        <span class="text-indigo-600 font-medium">Posko Komando View</span>
    </div>
</div>

<script>
if (typeof window.openLightbox !== 'function') {
    window.openLightbox = function (src, fileName) {
        let modal = document.getElementById('lightboxModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'lightboxModal';
            modal.className = 'fixed inset-0 z-[9999] hidden bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center" id="lightboxInner">
                    <button type="button" id="lightboxCloseBtn" class="absolute -top-10 right-0 text-white hover:text-slate-300 font-bold text-2xl leading-none">&times;</button>
                    <img id="lightboxImage" src="" alt="Dokumentasi Full" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl bg-white">
                    <div class="mt-3 bg-white/95 rounded-xl px-4 py-2 text-xs text-slate-700 flex items-center gap-3 shadow">
                        <span id="lightboxFileName" class="font-semibold text-slate-900">-</span>
                        <span class="text-slate-300">|</span>
                        <span id="lightboxDimensions" class="font-mono text-slate-500">memuat ukuran...</span>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            modal.addEventListener('click', function (e) {
                if (e.target === modal) window.closeLightbox();
            });
            modal.querySelector('#lightboxCloseBtn').addEventListener('click', window.closeLightbox);
        }

        const img = modal.querySelector('#lightboxImage');
        const nameEl = modal.querySelector('#lightboxFileName');
        const dimEl = modal.querySelector('#lightboxDimensions');

        nameEl.innerText = fileName || '-';
        dimEl.innerText = 'memuat ukuran...';
        img.src = src;
        img.onload = function () {
            dimEl.innerText = img.naturalWidth + ' x ' + img.naturalHeight + ' px';
        };

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeLightbox = function () {
        const modal = document.getElementById('lightboxModal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    };
}
</script>