<x-sub-posko.distribusi.detail-modal />

<form id="directConfirmForm" method="POST" class="hidden">
    @csrf
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-lg border border-slate-100'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: "{{ session('warning') }}"
            });
        @endif
    });

    let activeConfirmRoute = '';

    function openDetailModal(kode, routeUrl, items, status, catatan) {
        const modalKode = document.getElementById('modalKodePengajuan');
        if (modalKode) modalKode.innerText = kode;
        
        activeConfirmRoute = routeUrl;
        
        const catatanContainer = document.getElementById('modalCatatanContainer');
        if (catatanContainer) {
            if (catatan && catatan.trim() !== '') {
                document.getElementById('modalCatatanText').innerText = catatan;
                catatanContainer.classList.remove('hidden');
            } else {
                catatanContainer.classList.add('hidden');
            }
        }

        const tbody = document.getElementById('modalTableBody');
        if (tbody) {
            tbody.innerHTML = '';

            if (items && items.length > 0) {
                items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50/80';
                    tr.innerHTML = `
                        <td class="py-2.5 px-3 font-bold text-gray-900">${item.nama}</td>
                        <td class="py-2.5 px-3 text-gray-500">${item.kategori}</td>
                        <td class="py-2.5 px-3 text-right font-semibold text-blue-600">${item.jumlah}</td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="3" class="py-4 text-center text-gray-400 italic">Rincian item tidak tersedia</td></tr>`;
            }
        }

        // Atur Tombol Terima Sekarang di Modal Detail
        const btnConfirm = document.getElementById('btnOpenConfirmModal');
        if (btnConfirm) {
            const statusLower = (status || '').toLowerCase();
            if (routeUrl && !['selesai', 'diterima di posko'].includes(statusLower)) {
                btnConfirm.classList.remove('hidden');
                btnConfirm.setAttribute('onclick', 'submitTerimaLangsung()');
            } else {
                btnConfirm.classList.add('hidden');
            }
        }

        const modalDetail = document.getElementById('detailLogistikModal');
        if (modalDetail) modalDetail.classList.remove('hidden');
    }

    function closeDetailModal() {
        const modalDetail = document.getElementById('detailLogistikModal');
        if (modalDetail) modalDetail.classList.add('hidden');
    }

    // FUNGSI EKSEKUSI SEGERA KETIKA TOMBOL TERIMA DIKLIK
    function submitTerimaLangsung() {
        if (!activeConfirmRoute) return;

        const form = document.getElementById('directConfirmForm');
        form.action = activeConfirmRoute;
        form.submit();
    }
</script>