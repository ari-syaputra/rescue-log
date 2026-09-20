@extends('layouts.guest')

@section('title', 'Login - Sistem Penanganan Bencana BPBD')

@section('content')
    <div class="min-h-screen w-full flex flex-col md:flex-row bg-slate-50 font-sans">

        <!-- ========================================== -->
        <!-- SISI KIRI: Hero Section (Lebih Luas & Proporsional) -->
        <!-- ========================================== -->
        <div class="hidden md:flex md:w-7/12 lg:w-8/12 xl:w-8/12 relative bg-slate-900 justify-between flex-col p-8 lg:p-12 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-40 bg-cover bg-center"
                style="background-image: url('{{ asset('img/login.png.png') }}');">
            </div>
            <div></div>

            <!-- Header Brand -->
            <div class="relative z-10 flex items-center space-x-2.5 bg-transparent">
                <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo BPBD"
                    class="w-[70px] h-[90px] lg:w-[85px] lg:h-[105px] object-contain flex-shrink-0 bg-transparent">

                <div class="bg-transparent">
                    <h2 class="text-white font-bold text-base lg:text-xl tracking-wider leading-none">BPBD</h2>
                    <p class="text-amber-400 text-xs font-semibold tracking-widest mt-0.5">RESCUE-LOG</p>
                </div>
            </div>

            <!-- Body Hero -->
            <div class="relative z-10 my-auto py-8 lg:py-12 max-w-2xl">
                <h1 class="text-3xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight mb-4">
                    Sistem Penanganan Bencana
                </h1>
                <p class="text-slate-300 text-sm lg:text-base leading-relaxed mb-8 lg:mb-10 max-w-xl">
                    Kelola informasi bencana, posko pengungsian, stok logistik, dan distribusi bantuan secara terintegrasi dan real-time.
                </p>

                <div class="space-y-5 lg:space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="p-2.5 lg:p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10 shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5 lg:w-6 lg:h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-sm lg:text-base">Cepat</h4>
                            <p class="text-slate-400 text-xs lg:text-sm">Respon cepat dalam penanganan darurat bencana.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="p-2.5 lg:p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10 shrink-0">
                            <i data-lucide="users" class="w-5 h-5 lg:w-6 lg:h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-sm lg:text-base">Terkoordinasi</h4>
                            <p class="text-slate-400 text-xs lg:text-sm">Koordinasi terstruktur antara Posko Komando dan Sub Posko Lapangan.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="p-2.5 lg:p-3 bg-white/10 backdrop-blur-md rounded-xl text-amber-400 border border-white/10 shrink-0">
                            <i data-lucide="box" class="w-5 h-5 lg:w-6 lg:h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-semibold text-sm lg:text-base">Terintegrasi</h4>
                            <p class="text-slate-400 text-xs lg:text-sm">Data pengungsi dan penyaluran logistik terkelola dengan akurat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Hero -->
            <div class="relative z-10 text-xs text-slate-400 border-t border-white/10 pt-4">
                &copy; 2026 BPBD - RESCUE-LOG System
            </div>
        </div>

        <!-- ========================================== -->
        <!-- SISI KANAN: Form Login Ringkas & Pas (25-30% Width Desktop) -->
        <!-- ========================================== -->
        <div class="w-full md:w-5/12 lg:w-4/12 xl:w-4/12 bg-slate-50/80 flex items-center justify-center p-4 sm:p-6 lg:p-8 min-h-screen md:min-h-0">
            <div class="w-full max-w-sm sm:max-w-md bg-white p-6 sm:p-8 rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100">

                <!-- Header Form -->
                <div class="text-center mb-5 sm:mb-6">
                    <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo Rescue Log" class="w-16 h-16 sm:w-18 sm:h-18 object-contain mx-auto mb-2">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Portal Log In</h3>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">Pilih metode akses sesuai kewenangan operasional Anda</p>
                </div>

                <!-- TAB SWITCHER ROLE LOGIN -->
                <div class="grid grid-cols-2 gap-2 mb-5 p-1 bg-slate-100/90 rounded-2xl border border-slate-200/60">
                    <button type="button" id="tab-komando" onclick="switchLoginMode('komando')" 
                            class="p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-amber-500 border-amber-600 text-white shadow-md active:scale-[0.98]">
                        <div id="icon-komando-wrapper" class="w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1">
                            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="block text-[11px] sm:text-xs font-bold leading-tight">Posko Utama / BPBD</span>
                    </button>

                    <button type="button" id="tab-subposko" onclick="switchLoginMode('subposko')" 
                            class="p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100 active:scale-[0.98]">
                        <div id="icon-subposko-wrapper" class="w-6 h-6 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1">
                            <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="block text-[11px] sm:text-xs font-bold leading-tight">Access Key Sub-Posko</span>
                    </button>
                </div>

                <!-- Alert Errors Login -->
                @if ($errors->any())
                    <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-xl flex items-center space-x-2">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- FORM UTAMA LOGIN -->
                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- SECTION A: LOGIN EMAIL & PASSWORD -->
                    <div id="section-komando" class="space-y-3.5">
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username / Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition"
                                    placeholder="Masukkan email petugas">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="key-round" class="w-4 h-4"></i>
                                </div>
                                <input type="password" name="password" id="password"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition"
                                    placeholder="Masukkan password">
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs py-0.5">
                            <label class="flex items-center text-slate-600 cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded text-amber-600 focus:ring-amber-500 border-slate-300">
                                <span class="ml-2 font-semibold">Ingat saya</span>
                            </label>
                            <a href="#" class="font-bold text-amber-600 hover:text-amber-700 transition">Lupa password?</a>
                        </div>
                    </div>

                    <!-- SECTION B: LOGIN ACCESS KEY -->
                    <div id="section-subposko" class="space-y-3.5 hidden">
                        <div>
                            <label for="kode_undangan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Access Key Sub-Posko</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i data-lucide="key" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="kode_undangan" id="kode_undangan" value="{{ old('kode_undangan') }}"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm font-mono uppercase tracking-widest font-bold focus:bg-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none transition"
                                    placeholder="PSK-A8K29X">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" id="btn-submit"
                        class="w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-amber-600/25 flex items-center justify-center space-x-2 transition duration-200 cursor-pointer active:scale-[0.99] mt-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        <span id="btn-text">Masuk Posko Komando</span>
                    </button>
                </form>

                <!-- Footer khusus Smartphone -->
                <div class="block md:hidden text-center text-[11px] text-slate-400 border-t border-slate-100 pt-4 mt-6">
                    &copy; 2026 BPBD - RESCUE-LOG System
                </div>

            </div>
        </div>

    </div>

    <!-- Script Switch Mode Login & SweetAlert tetap sama di bawah -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function switchLoginMode(mode) {
            const tabKomando = document.getElementById('tab-komando');
            const tabSubposko = document.getElementById('tab-subposko');
            
            const iconKomandoWrapper = document.getElementById('icon-komando-wrapper');
            const iconSubposkoWrapper = document.getElementById('icon-subposko-wrapper');

            const sectionKomando = document.getElementById('section-komando');
            const sectionSubposko = document.getElementById('section-subposko');

            const inputEmail = document.getElementById('email');
            const inputPassword = document.getElementById('password');
            const inputKode = document.getElementById('kode_undangan');

            const btnSubmit = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-text');

            if (mode === 'komando') {
                tabKomando.className = "p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-amber-500 border-amber-600 text-white shadow-md active:scale-[0.98]";
                iconKomandoWrapper.className = "w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1";
                
                tabSubposko.className = "p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100 active:scale-[0.98]";
                iconSubposkoWrapper.className = "w-6 h-6 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1";

                sectionKomando.classList.remove('hidden');
                sectionSubposko.classList.add('hidden');

                inputEmail.required = true;
                inputPassword.required = true;
                inputKode.required = false;

                btnSubmit.className = "w-full py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-amber-600/25 flex items-center justify-center space-x-2 transition duration-200 cursor-pointer active:scale-[0.99] mt-2";
                btnText.innerText = "Masuk Posko Komando";

            } else {
                tabSubposko.className = "p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-blue-600 border-blue-700 text-white shadow-md active:scale-[0.98]";
                iconSubposkoWrapper.className = "w-6 h-6 bg-white/20 rounded-lg flex items-center justify-center mx-auto mb-1";

                tabKomando.className = "p-2.5 sm:p-3 rounded-xl border text-center transition-all duration-200 cursor-pointer focus:outline-none bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100 active:scale-[0.98]";
                iconKomandoWrapper.className = "w-6 h-6 bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center mx-auto mb-1";

                sectionKomando.classList.add('hidden');
                sectionSubposko.classList.remove('hidden');

                inputEmail.required = false;
                inputPassword.required = false;
                inputKode.required = true;

                btnSubmit.className = "w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/25 flex items-center justify-center space-x-2 transition duration-200 cursor-pointer active:scale-[0.99] mt-2";
                btnText.innerText = "Masuk Sub Posko Lapangan";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            @if(old('kode_undangan'))
                switchLoginMode('subposko');
            @else
                switchLoginMode('komando');
            @endif

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
        });
    </script>
@endsection