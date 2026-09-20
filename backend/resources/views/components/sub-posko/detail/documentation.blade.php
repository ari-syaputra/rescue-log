@props(['subPosko' => null])

@php
    $fotos = [];
    if (!empty($subPosko) && is_object($subPosko) && !empty($subPosko->fotos)) {
        if (is_array($subPosko->fotos)) {
            $fotos =$subPosko->fotos;
        } elseif (method_exists($subPosko->fotos, 'toArray')) {
            $fotos =$subPosko->fotos->toArray();
        }
    }
    $totalFoto = count($fotos);
@endphp

<div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between h-full space-y-3 font-sans">
    <!-- Header Card Dokumentasi -->
    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
        <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Dokumentasi Posko</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Catatan visual kondisi posko untuk BPBD</p>
        </div>

        <?php if ($totalFoto > 0): ?>
            <!-- Tombol Tambah Ringkas di Header saat Foto Sudah Ada -->
            <label for="fotoInputDokumentasi" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/80 rounded-xl text-xs font-bold transition cursor-pointer shrink-0" 
                   title="Tambah Foto Dokumentasi">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Foto</span>
            </label>
        <?php endif; ?>

        <!-- Hidden File Input -->
        <form id="formUploadFoto" action="{{ route('lapangan.dokumentasi.upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="fotoInputDokumentasi" name="fotos[]" accept="image/*" capture="environment" multiple onchange="document.getElementById('formUploadFoto').submit()">
        </form>
    </div>

    <!-- Area Konten Utama / Galeri Foto -->
    <div class="flex-1 min-h-[220px] flex items-center justify-center">
        <?php if ($totalFoto > 0): ?>
            <!-- Grid Foto Proporsional -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full my-auto">
                <?php foreach ($fotos as$itemFoto): 
                    $fotoObj = is_array($itemFoto) ? (object)$itemFoto :$itemFoto;
                    $path =$fotoObj->path_file ?? '';
                    $idFoto =$fotoObj->id ?? 0;
                ?>
                    <div class="aspect-4/3 w-full rounded-xl overflow-hidden bg-slate-100 border border-slate-200 relative group cursor-pointer shadow-2xs">
                        <img src="{{ asset('storage/' . $path) }}" 
                             alt="Dokumentasi Posko" 
                             onclick="openLightbox('{{ asset('storage/' . $path) }}', '{{ basename($path) }}')"
                             class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
                        
                        <!-- Overlay saat di-hover -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-between p-2">
                            <button type="button" 
                                    onclick="openLightbox('{{ asset('storage/' . $path) }}', '{{ basename($path) }}')"
                                    class="p-1.5 bg-white/20 hover:bg-white/40 rounded-lg text-white backdrop-blur-xs transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                </svg>
                            </button>

                            <!-- Form Hapus Foto dengan SweetAlert2 -->
                            <form id="formHapusFoto-<?php echo $idFoto; ?>" action="{{ route('lapangan.dokumentasi.hapus', $idFoto) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="konfirmasiHapusFoto('<?php echo $idFoto; ?>')"
                                        class="p-1.5 bg-rose-600/80 hover:bg-rose-700 text-white rounded-lg backdrop-blur-xs transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- EMPTY STATE: Bingkai Gambar Kosong dengan (+) di Tengah -->
            <div class="w-full h-full flex flex-col items-center justify-center my-auto">
                <label for="fotoInputDokumentasi" 
                       class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl border-2 border-dashed border-indigo-200 hover:border-indigo-500 bg-indigo-50/40 hover:bg-indigo-50 transition duration-200 flex flex-col items-center justify-center gap-1.5 cursor-pointer group active:scale-95 shadow-2xs">
                    
                    <div class="relative">
                        <svg class="w-9 h-9 text-indigo-400 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-xs">
                            +
                        </span>
                    </div>

                    <span class="text-[11px] font-bold text-indigo-600 group-hover:text-indigo-700 transition">Tambah Foto</span>
                </label>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer Status Dokumentasi -->
    <div class="flex items-center justify-between text-[11px] sm:text-xs pt-2 border-t border-slate-100 text-slate-500">
        <span>Total Foto: <strong class="text-slate-700"><?php echo $totalFoto; ?> Berkas</strong></span>
        <span class="text-indigo-600 font-medium">Terhubung BPBD Kab/Kota</span>
    </div>
</div>

<!-- SweetAlert2 CDN & Script Konfirmasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiHapusFoto(idFoto) {
    Swal.fire({
        title: 'Hapus Foto Dokumentasi?',
        text: "Foto yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus Foto!',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl font-bold px-4 py-2',
            cancelButton: 'rounded-xl font-bold px-4 py-2'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formHapusFoto-' + idFoto).submit();
        }
    });
}

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