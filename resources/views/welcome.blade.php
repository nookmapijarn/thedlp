<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/olislogo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'IBM Plex Sans Thai', 'Figtree', sans-serif; 
                margin: 0;
                min-height: 100vh;
                background-color: #0f172a;
                color: #e2e8f0;
                overflow-x: hidden;
            }

            /* --- Premium Background --- */
            .premium-bg {
                background: radial-gradient(circle at top right, #3e0089 0%, transparent 40%),
                            radial-gradient(circle at bottom left, #7e22ce 0%, transparent 40%),
                            linear-gradient(to bottom, #5a0d9e, #7e22ce);
                background-attachment: fixed;
            }

            /* --- Glass Card for Stats --- */
            .glass-stat {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            .glass-stat:hover {
                background: rgba(255, 255, 255, 0.08);
                transform: translateY(-5px);
                border-color: rgba(255, 215, 0, 0.3); /* Gold border on hover */
                box-shadow: 0 10px 40px -10px rgba(251, 191, 36, 0.2);
            }

            /* --- Menu Card Premium --- */
            .menu-card {
                background: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.1);
                position: relative;
                z-index: 1;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }

            .menu-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #e0e7ff 0%, #ffffff 100%);
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .menu-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 20px 40px -5px rgba(99, 102, 241, 0.4); /* Blue/Purple glow */
                border-color: #fbbf24; /* Yellow/Gold border */
            }

            .menu-card:hover::before {
                opacity: 1;
            }

            /* --- Icon Wrapper Gradient --- */
            .icon-wrapper-gradient {
                background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%); /* Blue to Purple */
                color: white;
                box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
            }

            .menu-card:hover .icon-wrapper-gradient {
                background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); /* Gold on hover */
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 4px 15px rgba(245, 158, 11, 0.5);
            }

            /* --- Text Gradients --- */
            .text-gradient-gold {
                background: linear-gradient(to right, #fcd34d, #fbbf24);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .text-gradient-purple {
                background: linear-gradient(to right, #a855f7, #6366f1);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            /* --- Animations --- */
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>

    <body class="premium-bg antialiased selection:bg-yellow-400 selection:text-slate-900">
        
        <div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
            <div class="relative w-full max-w-sm transform transition-all scale-100">
                <div class="bg-white rounded-[2rem] shadow-2xl p-8 text-center relative border-2 border-yellow-400/50 overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 via-purple-600 to-yellow-400"></div>

                    <button onclick="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">ยืนยันการเข้าถึง</h3>
                    
                    <div class="text-sm px-6 py-2 rounded-full bg-slate-50 border border-slate-200 text-slate-600 mb-8 inline-block shadow-sm">
                        @php 
                            $role = request()->query('roletype'); 
                            $roleText = [1 => 'ระบบผู้เรียน', 2 => 'ระบบครู', 3 => 'ระบบผู้บริหาร', 4 => 'ระบบผู้ดูแลระบบ'];
                            $targetRoute = '#';
                            if($role == 1) $targetRoute = route('login');
                            elseif($role == 2) $targetRoute = url('teachers');
                            elseif($role == 3) $targetRoute = route('boss');
                            elseif($role == 4) $targetRoute = route('admin');
                        @endphp
                        ต้องการเข้าสู่ <strong class="text-purple-700">{{ $roleText[$role] ?? 'ระบบ' }}</strong>
                    </div>
                    
                    <div class="space-y-4">
                        <a href="{{ $targetRoute }}" class="block w-full py-3.5 rounded-xl font-bold shadow-lg shadow-blue-500/30 bg-gradient-to-r from-blue-600 to-purple-600 text-white hover:from-blue-700 hover:to-purple-700 hover:shadow-xl hover:scale-[1.02] transition-all duration-200">
                            ตกลง / ยืนยัน
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors tracking-widest uppercase py-2">
                                ยกเลิกและออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <main class="relative z-10 min-h-screen flex flex-col items-center justify-center py-16 px-4 sm:px-6">
            
            <div class="mb-10 animate-float relative group">
                <div class="absolute inset-0 bg-yellow-900 blur-3xl opacity-50 group-hover:opacity-40 transition-opacity duration-500 rounded-full"></div>
                <img class="relative w-36 md:w-48 drop-shadow-2xl transition-all duration-700 transform group-hover:rotate-3" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
            </div>

            <div class="text-center mb-16 relative">
                <h1 class="text-3xl md:text-5xl font-bold tracking-widest uppercase mb-4 text-white drop-shadow-lg">
                    OL<span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">I</span>S
                    <br><span class="text-sm">"Digital Learning Center"</span>
                </h1>
                <p class="text-sm md:text-base font-light text-blue-200 tracking-[0.5em] uppercase border-t border-blue-500/30 pt-4 inline-block">
                    {{ config('app.name_th') }}
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 w-full max-w-5xl mx-auto">
                @php
                    $menus = [
                        ['route' => route('login'), 'en' => 'Student', 'th' => 'ผู้เรียน', 'icon' => 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342'],
                        ['route' => url('teachers'), 'en' => 'Instructor', 'th' => 'ครูผู้สอน', 'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                        ['route' => route('boss'), 'en' => 'Executive', 'th' => 'ผู้บริหาร', 'icon' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5'],
                        ['route' => route('admin'), 'en' => 'Admin', 'th' => 'ผู้ดูแลระบบ', 'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z'],
                        // ['route' => route('ทดสอบออนไลน์'), 'en' => 'Assessment', 'th' => 'การทดสอบ', 'icon' => 'm16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125'],
                        // ['route' => route('olisai'), 'en' => 'Counseling', 'th' => 'แนะแนว', 'icon' => 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091'],
                        // ['route' => route('เรียนออนไลน์'), 'en' => 'E-Learning', 'th' => 'เรียนออนไลน์', 'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292'],
                        // ['route' => route('login'), 'en' => 'Learning-Map', 'th' => 'แหล่งเรียนรู้', 'icon' => 'M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006'],
                    ];
                @endphp

                @foreach($menus as $menu)
                <a href="{{ $menu['route'] }}" class="menu-card rounded-[2rem] p-6 flex flex-col items-center justify-center no-underline group h-48 md:h-56">
                    
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-purple-400/20 blur-2xl rounded-full group-hover:bg-yellow-400/30 transition-all duration-500"></div>

                    <div class="icon-wrapper-gradient w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}"/>
                        </svg>
                    </div>

                    <div class="text-center relative z-10">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1 group-hover:text-purple-600 transition-colors duration-300">
                            {{ $menu['en'] }}
                        </p>
                        <p class="text-lg font-bold text-slate-700 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-purple-600 group-hover:to-blue-600 transition-all">
                            {{ $menu['th'] }}
                        </p>
                    </div>

                    <div class="absolute bottom-0 w-full h-1 bg-gradient-to-r from-purple-500 via-blue-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-20 w-full max-w-5xl mx-auto px-2">
                @php
                    $stats = [
                        ['label' => 'จำนวนผู้เรียน', 'value' => $allusers, 'unit' => 'คน', 'icon_color' => 'text-blue-400', 'bar_color' => 'bg-blue-500'],
                        ['label' => 'ผู้ใช้งานวันนี้', 'value' => $todayUsers, 'unit' => 'คน', 'icon_color' => 'text-green-400', 'bar_color' => 'bg-emerald-500'],
                        ['label' => 'ออนไลน์ขณะนี้', 'value' => $onlineCount, 'unit' => 'คน', 'icon_color' => 'text-rose-400', 'bar_color' => 'bg-rose-500'],
                        ['label' => 'สมาชิกทั้งหมด', 'value' => $totalMembers, 'unit' => 'คน', 'icon_color' => 'text-yellow-400', 'bar_color' => 'bg-yellow-500'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="glass-stat rounded-3xl p-6 relative group overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-white/10 to-transparent rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                    
                    <div class="flex flex-col h-full justify-between relative z-10">
                        <p class="text-blue-200 text-xs font-bold uppercase tracking-wider mb-2">
                            {{ $stat['label'] }}
                        </p>
                        <div class="flex items-end gap-2 mb-4">
                            <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight group-hover:text-yellow-300 transition-colors">
                                {{ number_format($stat['value']) }}
                            </h2>
                            <span class="text-sm text-slate-400 mb-2 font-medium">{{ $stat['unit'] }}</span>
                        </div>
                        
                        <div class="w-full h-1.5 bg-slate-700/50 rounded-full overflow-hidden">
                            <div class="h-full {{ $stat['bar_color'] }} w-2/3 rounded-full shadow-[0_0_10px_currentColor] opacity-80 group-hover:w-full transition-all duration-700 ease-out"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-24 text-center">
                <div class="inline-block px-6 py-2 rounded-full glass-stat border-slate-700">
                    <p class="text-slate-400 text-[10px] font-medium tracking-[0.2em]">
                        จัดทำโดย <span class="text-yellow-400 font-bold">นายนนทชัย มาพิจารณ์</span>
                    </p>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('roletype')) {
                    const modal = document.getElementById('popup-modal');
                    modal.classList.remove('hidden');
                    // Add slight delay for animation
                    setTimeout(() => {
                        modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
                    }, 10);
                }
            });

            function closeModal(){
                const modal = document.getElementById('popup-modal');
                // Animation out
                modal.firstElementChild.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    const url = new URL(window.location);
                    url.searchParams.delete('roletype');
                    window.history.replaceState({}, '', url);
                }, 200);
            }
        </script>
    </body>
</html>