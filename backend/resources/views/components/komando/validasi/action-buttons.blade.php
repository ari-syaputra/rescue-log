@props(['item'])

@if(strtolower($item->status) == 'pending')
    <div class="flex items-center justify-end gap-1.5">
        <form action="{{ route('komando.validasi.approve', $item->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition cursor-pointer">
                Setujui
            </button>
        </form>

        <form action="{{ route('komando.validasi.reject', $item->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Tolak pengajuan dari Sub-Posko ini?')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs rounded-xl border border-rose-200 transition cursor-pointer">
                Tolak
            </button>
        </form>
    </div>
@elseif(strtolower($item->status) == 'disetujui')
    <a href="{{ route('komando.distribusi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-xl shadow-xs transition">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Atur Armada
    </a>
@else
    <span class="text-xs text-slate-400 italic">Selesai/Telah diproses</span>
@endif