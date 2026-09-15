@props(['pengajuans'])

<div class="bg-white rounded-2xl shadow-xs border border-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead class="bg-slate-100/80 border-b border-slate-200/60">
                <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider">
                    <th class="py-4 px-6 w-48">NO. PENGAJUAN</th>
                    <th class="py-4 px-6 w-44">SUB POSKO</th>
                    <th class="py-4 px-6 w-40">BENCANA</th>
                    <th class="py-4 px-6 w-40">STATUS</th>
                    <th class="py-4 px-6">RINGKASAN ITEM</th>
                    <th class="py-4 px-6 text-right w-48">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($pengajuans as $item)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <!-- KODE PENGAJUAN -->
                        <td class="py-4 px-6 align-middle font-semibold text-blue-600 whitespace-nowrap">
                            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-100">
                                {{ $item->kode_pengajuan }}
                            </span>
                        </td>

                        <!-- SUB POSKO -->
                        <td class="py-4 px-6 align-middle font-semibold text-slate-800 whitespace-nowrap">
                            {{ $item->posko->nama_posko ?? $item->user->name ?? '-' }}
                        </td>

                        <!-- BENCANA -->
                        <td class="py-4 px-6 align-middle text-slate-700">
                            {{ $item->bencana->nama_bencana ?? $item->posko->bencana->nama_bencana ?? '-' }}
                        </td>

                        <!-- STATUS -->
                        <td class="py-4 px-6 align-middle whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-50 text-amber-700 rounded-lg border border-amber-200/60">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <!-- RINGKASAN ITEM -->
                        <td class="py-4 px-6 align-middle text-xs">
                            <div class="grid grid-cols-2 gap-1">
                                @if(($item->beras_kg ?? 0) > 0) <div><b>Beras:</b> {{ $item->beras_kg }} kg</div> @endif
                                @if(($item->air_minum_dus ?? 0) > 0) <div><b>Air:</b> {{ $item->air_minum_dus }} dus</div> @endif
                                @if(($item->mie_instan_dus ?? 0) > 0) <div><b>Mie:</b> {{ $item->mie_instan_dus }} dus</div> @endif
                            </div>
                        </td>

                        <!-- AKSI -->
                        <td class="py-4 px-6 text-right align-middle whitespace-nowrap">
                            @if(strtolower($item->status) == 'pending')
                                <form action="{{ route('komando.validasi.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition shadow-xs cursor-pointer">
                                        Setujui
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-slate-400 italic text-sm">
                            Belum ada pengajuan masuk dari Sub-Posko.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($pengajuans, 'hasPages') && $pengajuans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pengajuans->links() }}
        </div>
    @endif
</div>