        <div class="lg:col-span-7">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <i data-lucide="map" class="w-5 h-5 text-indigo-600"></i> Peta Situasi Jalur & Kendala
                            Real-Time
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Klik area mana saja pada peta untuk menyalin koordinat
                            laporan kendala.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Titik Bahaya
                        </span>
                    </div>
                </div>

                <!-- CONTAINER PETA LEAFLET -->
                <div id="map" class="w-full shadow-inner border border-slate-200"></div>

                <!-- PANEL INFORMASI PERUBAHAN ESTIMASI RUTE (Dinamis) -->
                <div id="routeInfoPanel"
                    class="hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl transition-all duration-300">
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        <!-- Status Rute & Pengiriman -->
                        <div class="flex items-center gap-3">
                            <div id="routeStatusIcon" class="p-2.5 rounded-lg bg-emerald-100 text-emerald-600">
                                <i data-lucide="navigation" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span id="routeBadgeStatus"
                                    class="inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700 uppercase tracking-wider">
                                    Rute Langsung
                                </span>
                                <h4 id="routeTargetName" class="text-xs font-bold text-slate-800 mt-0.5">Posko Tujuan
                                </h4>
                            </div>
                        </div>

                        <!-- CONTAINER UTAMA KARTU METRIC -->
                        <div class="flex flex-wrap sm:flex-nowrap items-stretch gap-3 w-full sm:w-auto">

                            <!-- Jarak Tempuh -->
                            <div
                                class="flex-1 bg-white p-2.5 rounded-lg border border-slate-200/80 shadow-2xs min-w-[110px] flex flex-col justify-center">
                                <span
                                    class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Jarak
                                    Tempuh</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span id="valDistance" class="text-base font-bold text-slate-800">0</span>
                                    <span class="text-xs font-medium text-slate-500">km</span>
                                </div>
                            </div>

                            <!-- Estimasi Waktu -->
                            <div
                                class="flex-1 bg-white p-2.5 rounded-lg border border-slate-200/80 shadow-2xs min-w-[110px] flex flex-col justify-center">
                                <span
                                    class="text-[10px] font-semibold text-slate-400 block uppercase tracking-wider">Estimasi
                                    Waktu</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span id="valTime" class="text-base font-bold text-slate-800">0</span>
                                    <span class="text-xs font-medium text-slate-500">menit</span>
                                </div>
                            </div>

                            <!-- Selisih Pengalihan (Muncul Jika Dialihkan/Digeser) -->
                            <div id="wrapperDiff"
                                class="flex-1 bg-amber-50 p-2.5 rounded-lg border border-amber-200 shadow-2xs min-w-[130px] flex-col justify-center hidden">
                                <span
                                    class="text-[10px] font-semibold text-amber-700 block uppercase tracking-wider">Tambahan
                                    Memutar</span>
                                <div class="flex items-baseline gap-1 mt-0.5 text-amber-800">
                                    <span id="valDiffDist" class="text-xs font-bold">+0 km</span>
                                    <span class="text-xs text-amber-600">(<span id="valDiffTime">+0m</span>)</span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
