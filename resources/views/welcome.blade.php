<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/olislogo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'IBM Plex Sans Thai', 'Figtree', sans-serif; 
                margin: 0;
                min-height: 100vh;
                background-color: #ffffff;
                color: #334155;
            }

            /* --- Menu Card Minimal --- */
            .menu-card {
                background: #fffcd7;
                border: 1px solid #f1f5f9;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 1.5rem;
            }

            .menu-card:hover {
                transform: translateY(-5px);
                border-color: #e2e8f0;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
                background: #ffc812;
            }

            /* --- Icon Wrapper --- */
            .icon-wrapper {
                background: #6000a9;
                color: #ffffff;
                transition: all 0.3s ease;
            }
            .menu-card:hover .icon-wrapper {
                background: #6000a9;
                color: #ffffff;
            }

            .btn-confirm {
                background: #334155;
                color: white;
                transition: all 0.2s ease;
            }
            .btn-confirm:hover {
                background: #0f172a;
                transform: translateY(-1px);
            }

            .badge-role-minimal {
                background: #f1f5f9;
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            /* Minimal background (remove heavy gradients) */
            .bg-soft-gray {
                background: radial-gradient(circle at 50% 50%, #f8fafc 0%, #ffffff 100%);
            }
        </style>
    </head>

    <body class="bg-soft-gray">
        
        <div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4 bg-white/80 backdrop-blur-sm">
            <div class="relative w-full max-w-sm">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xl p-8 text-center relative">
                    <button onclick="closeModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="w-12 h-12 bg-slate-50 text-slate-500 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    
                    <h3 class="text-xl font-semibold text-slate-800 mb-2">ยืนยันการเข้าถึง</h3>
                    
                    <div class="text-xs px-4 py-2 rounded-full badge-role-minimal mb-8 inline-block">
                        @php 
                            $role = request()->query('roletype'); 
                            $roleText = [1 => 'ระบบผู้เรียน', 2 => 'ระบบครู', 3 => 'ระบบผู้บริหาร', 4 => 'ระบบผู้ดูแลระบบ'];
                            $targetRoute = '#';
                            if($role == 1) $targetRoute = route('login');
                            elseif($role == 2) $targetRoute = url('teachers');
                            elseif($role == 3) $targetRoute = route('boss');
                            elseif($role == 4) $targetRoute = route('admin');
                        @endphp
                        ต้องการเข้าสู่ <strong>{{ $roleText[$role] ?? 'ระบบ' }}</strong>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ $targetRoute }}" class="btn-confirm block w-full py-3 rounded-xl font-medium shadow-sm">
                            ตกลง
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-[10px] font-medium text-slate-400 hover:text-red-500 transition-colors tracking-widest uppercase mt-2">
                                ยกเลิกและออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <main class="relative z-10 min-h-screen flex flex-col items-center justify-center py-12 px-6">
            <div class="mb-8">
                <img class="w-32 md:w-40 transition-all duration-700" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
            </div>

            <div class="text-center mb-16">
                <h1 class="text-slate-800 text-2xl md:text-3xl font-light tracking-[0.2em] uppercase mb-2">Digital Learning Center</h1>
                <p class="text-[11px] font-medium text-slate-400 tracking-[0.3em]">{{ config('app.name_th') }}</p>
            </div>

<div class="flex flex-wrap justify-center gap-6 mb-16 w-full max-w-6xl mx-auto px-4">
    @php
        $stats = [
            ['label' => 'จำนวนผู้เรียน', 'value' => $allusers, 'unit' => 'คน', 'color' => 'bg-blue-400'],
            ['label' => 'ผู้ใช้งานวันนี้', 'value' => $todayUsers, 'unit' => 'คน', 'color' => 'bg-emerald-400'],
            // ['label' => 'ผู้เรียน (วันนี้)', 'value' => $todayStudents, 'unit' => 'คน', 'color' => 'bg-indigo-400'],
            // ['label' => 'คุณครู (วันนี้)', 'value' => $todayTeachers, 'unit' => 'คน', 'color' => 'bg-amber-400'],
            ['label' => 'ออนไลน์ขณะนี้', 'value' => $onlineCount, 'unit' => 'คน', 'color' => 'bg-rose-400'],
            ['label' => 'สมาชิกทั้งหมด', 'value' => $totalMembers, 'unit' => 'คน', 'color' => 'bg-slate-400'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="group relative bg-white border border-slate-100 rounded-3xl px-8 py-6 flex flex-col items-center min-w-[200px] flex-1 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-2 hover:border-transparent">
        
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-1 {{ $stat['color'] }} rounded-b-full group-hover:opacity-100 group-hover:w-20 transition-all duration-300"></div>

        <p class="text-slate-400 text-[13px] uppercase tracking-[0.15em] font-bold mb-3 group-hover:text-slate-600 transition-colors text-center">
            {{ $stat['label'] }}
        </p>
        
        <div class="flex items-baseline gap-1">
            <p class="text-slate-800 text-4xl font-black tracking-tight">
                {{ number_format($stat['value']) }}
            </p>
            <p class="text-slate-400 text-xs font-medium">{{ $stat['unit'] }}</p>
        </div>

        <div class="mt-4 flex gap-1">
            <span class="w-1.5 h-1.5 rounded-full {{ $stat['color'] }} opacity-40"></span>
            <span class="w-1.5 h-1.5 rounded-full {{ $stat['color'] }} opacity-20"></span>
            <span class="w-1.5 h-1.5 rounded-full {{ $stat['color'] }} opacity-10"></span>
        </div>
    </div>
    @endforeach
</div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 w-full max-w-5xl mx-auto">
                @php
                    $menus = [
                        ['route' => route('login'), 'en' => 'Student', 'th' => 'ผู้เรียน', 'icon' => 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342'],
                        ['route' => url('teachers'), 'en' => 'Instructor', 'th' => 'ครูผู้สอน', 'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z'],
                        ['route' => route('boss'), 'en' => 'Executive', 'th' => 'ผู้บริหาร', 'icon' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5'],
                        ['route' => route('admin'), 'en' => 'Admin', 'th' => 'ผู้ดูแลระบบ', 'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z'],
                        ['route' => route('ทดสอบออนไลน์'), 'en' => 'Assessment', 'th' => 'การทดสอบ', 'icon' => 'm16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125'],
                        ['route' => route('olisai'), 'en' => 'Counseling', 'th' => 'แนะแนว', 'icon' => 'M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091'],
                        ['route' => route('เรียนออนไลน์'), 'en' => 'E-Learning', 'th' => 'เรียนออนไลน์', 'icon' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292'],
                        ['route' => route('login'), 'en' => 'Learning-Map', 'th' => 'แหล่งเรียนรู้', 'icon' => 'M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006'],
                    ];
                @endphp

                @foreach($menus as $menu)
                <a href="{{ $menu['route'] }}" class="relative menu-card p-6 group flex flex-col items-center justify-center no-underline overflow-hidden bg-white border border-slate-100 rounded-[2rem] transition-all duration-300 hover:border-purple-200 hover:shadow-xl hover:shadow-purple-500/5">
                    
                    <div class="absolute -top-10 -right-10 w-20 h-20 bg-yellow-100/30 rounded-full blur-2xl group-hover:bg-yellow-200/50 transition-all duration-500"></div>

                    <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center mb-4 bg-purple-500 text-white-400 group-hover:bg-purple-600 group-hover:text-white group-hover:rotate-3 transition-all duration-300 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}"/>
                        </svg>
                    </div>

                    <div class="text-center relative z-10">
                        <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-1 group-hover:text-purple-500 transition-colors duration-300">
                            {{ $menu['en'] }}
                        </p>
                        <p class="text-base font-bold text-slate-700 group-hover:text-slate-900 transition-colors">
                            {{ $menu['th'] }}
                        </p>
                    </div>

                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-1 bg-yellow-400 rounded-t-full group-hover:w-12 transition-all duration-300"></div>
                </a>
                @endforeach
            </div>

            <div class="mt-20 opacity-50">
                <p class="text-slate-400 text-[10px] font-medium tracking-[0.2em]">จัดทำโดย <span class="text-slate-600">นายนนทชัย มาพิจารณ์</span></p>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('roletype')) {
                    document.getElementById('popup-modal').classList.remove('hidden');
                }
            });

            function closeModal(){
                const modal = document.getElementById('popup-modal');
                modal.classList.add('hidden');
                const url = new URL(window.location);
                url.searchParams.delete('roletype');
                window.history.replaceState({}, '', url);
            }
        </script>
    </body>
</html>