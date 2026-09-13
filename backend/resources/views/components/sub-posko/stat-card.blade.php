@props(['pendataanTerakhir'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex items-center space-x-4">
        <div class="p-3.5 bg-blue-50 text-blue-700 rounded-2xl flex-shrink-0">
            <x-heroicon-s-users class="w-7 h-7" />
        </div>
        <div class="flex-grow">
            <div class="text-gray-500 text-sm font-medium">Total Pengungsi</div>
            <div class="text-2xl font-extrabold text-gray-900 mt-0.5">
                {{ $pendataanTerakhir->total_pengungsi ?? 0 }} <span class="text-xs font-normal text-gray-500">Jiwa</span>
            </div>
            <div class="text-xs text-blue-700 font-medium mt-1">
                Update: {{ $pendataanTerakhir && $pendataanTerakhir->created_at ? $pendataanTerakhir->created_at->diffForHumans() : '-' }}
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex items-center space-x-4">
        <div class="p-3.5 bg-sky-50 text-sky-700 rounded-2xl flex-shrink-0">
            <x-heroicon-s-face-smile class="w-7 h-7" />
        </div>
        <div class="flex-grow">
            <div class="text-gray-500 text-sm font-medium">Anak Balita</div>
            <div class="text-2xl font-bold text-gray-900 mt-0.5">
                {{ $pendataanTerakhir->balita ?? 0 }} <span class="text-xs font-normal text-gray-500">Jiwa</span>
            </div>
            <div class="text-xs text-sky-700 font-medium mt-1">Usia 0 - 5 tahun</div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex items-center space-x-4">
        <div class="p-3.5 bg-amber-50 text-amber-700 rounded-2xl flex-shrink-0">
            <x-heroicon-s-heart class="w-7 h-7" />
        </div>
        <div class="flex-grow">
            <div class="text-gray-500 text-sm font-medium">Lansia dan Rentan</div>
            <div class="text-2xl font-bold text-gray-900 mt-0.5">
                {{ ($pendataanTerakhir->lansia ?? 0) + ($pendataanTerakhir->disabilitas ?? 0) + ($pendataanTerakhir->ibu_hamil ?? 0) }} 
                <span class="text-xs font-normal text-gray-500">Jiwa</span>
            </div>
            <div class="text-xs text-amber-700 font-medium mt-1">Lansia, Bumil & Disabilitas</div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex items-center space-x-4">
        <div class="p-3.5 bg-emerald-50 text-emerald-700 rounded-2xl flex-shrink-0">
            <x-heroicon-s-calendar class="w-7 h-7" />
        </div>
        <div class="flex-grow">
            <div class="text-gray-500 text-sm font-medium">Lama Pengungsian</div>
            <div class="text-2xl font-bold text-gray-900 mt-0.5">
                {{ $pendataanTerakhir->lama_pengungsian ?? 0 }} <span class="text-xs font-normal text-gray-500">Hari</span>
            </div>
            <div class="text-xs text-emerald-700 font-medium mt-1 truncate max-w-[150px]">
                Fasilitas: {{ $pendataanTerakhir->tipe_tempat ?? '-' }}
            </div>
        </div>
    </div>

</div>