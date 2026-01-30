<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('storage/olislogo.png');}}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'IBM Plex Sans Thai', sans-serif; 
                margin: 0;
                background-color: #ffffff; 
                min-height: 100vh;
                overflow-x: hidden;
                color: #334155;
            }

            /* --- Background Layer: Minimal Gradient --- */
            .bg-gradient-layer {
                position: fixed;
                inset: 0;
                /* background: radial-gradient(circle at 0% 0%, #ffe88c 0%, transparent 50%),
                            radial-gradient(circle at 100% 100%, #4000bf 0%, transparent 50%),
                            linear-gradient(180deg, #a525f0 0%, #ad17f84a 100%); */
                z-index: -2;
            }

            /* --- Sand Overlay: Subtly Premium --- */
            .sand-overlay {
                position: fixed;
                inset: 0;
                z-index: -1;
                opacity: 0.03; /* ปรับลดลงให้ดูเนียนที่สุด */
                filter: url(#noiseFilter);
                mix-blend-mode: overlay;
                pointer-events: none;
            }

            /* --- Menu Card: Minimal Glass --- */
            .menu-card {
                background: rgba(255, 255, 255, 0.4);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.6);
                transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
                border-radius: 2rem;
            }

            .menu-card:hover {
                transform: translateY(-12px);
                background: rgba(255, 255, 255, 0.9);
                border-color: #fcc600;
                box-shadow: 0 30px 60px -15px rgba(124, 58, 237, 0.1);
            }

            /* --- Icon Wrapper --- */
            .icon-wrapper {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                color: #7c3aed; /* ม่วง Premium */
                transition: all 0.4s ease;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }
            .menu-card:hover .icon-wrapper {
                background: linear-gradient(135deg, #7c3aed, #4f46e5);
                color: #ffffff;
                transform: scale(1.1) rotate(5deg);
                box-shadow: 0 10px 20px -5px rgba(124, 58, 237, 0.4);
            }

            /* --- Brand Button --- */
            .brand-gradient-btn {
                background: linear-gradient(135deg, #7c3aed 0%, #fcc600 100%);
                transition: all 0.3s ease;
            }
            .brand-gradient-btn:hover {
                filter: brightness(1.05);
                box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.3);
            }

            /* --- Text Styling --- */
            .text-premium {
                letter-spacing: 0.05em;
                background: linear-gradient(135deg, #0a00ca, #6700b7, #f5da0b);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .badge-role {
                background: #fefce8;
                color: #854d0e;
                border: 1px solid #fef3c7;
                font-size: 9px;
            }
        </style>
    </head>

    <body class="antialiased selection:bg-purple-100">
        <svg class="hidden">
            <filter id='noiseFilter'>
                <feTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/>
                <feColorMatrix type="saturate" values="0"/>
            </filter>
        </svg>
        
        <div class="bg-gradient-layer"></div>
        <div class="sand-overlay"></div>

        <div id="popup-modal" tabindex="-1" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/10 backdrop-blur-md">
            <div class="relative w-full max-w-sm animate-in fade-in zoom-in duration-500">
                <div class="bg-white/90 rounded-[2.5rem] shadow-[0_32px_64px_-15px_rgba(0,0,0,0.1)] p-10 text-center relative border border-white">
                    <button onclick="closeModal()" class="absolute top-6 right-6 text-slate-300 hover:text-slate-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-800 mb-2">ยืนยันการเข้าถึง</h3>
                    <div class="inline-block px-3 py-1 rounded-full badge-role font-bold uppercase tracking-widest mb-8">
                        @php $role = request()->roletype; @endphp
                        @if($role == 1) Student @elseif($role == 2) Instructor @elseif($role == 3) Executive @elseif($role == 4) Admin @endif
                    </div>
                    
                    <div class="space-y-4 mb-6">
                        @php
                            $route = '#';
                            if($role == 1) $route = route('login');
                            if($role == 2) $route = url('teachers');
                            if($role == 3) $route = route('boss');
                            if($role == 4) $route = route('admin');
                        @endphp
                        <a href="{{ $route }}" class="brand-gradient-btn block w-full text-white p-4 rounded-xl font-bold transition-all active:scale-95 shadow-lg">
                            เข้าใช้งานระบบ
                        </a>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-[10px] font-bold text-slate-400 hover:text-red-400 transition-colors uppercase tracking-[0.2em]">Sign Out Account</button></form>
                </div>
            </div>
        </div>

        <main class="relative z-10 min-h-screen flex flex-col items-center justify-center py-12 px-6">
            <div class="mb-8 transform transition-transform hover:scale-105 duration-700">
                <img class="w-48 md:w-56 drop-shadow-sm" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
            </div>

            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-2 opacity-60 mb-3">
                    <div class="h-[1px] w-8 bg-slate-300"></div>
                    <p class="text-premium text-slate-900 text-[12px] sm:text-[24px] font-bold tracking-[0.5em] uppercase">Digital Learning Center</p>
                    <div class="h-[1px] w-8 bg-slate-300"></div>
                </div>
                <p class="text-[12px] sm:text-[24px] font-medium text-slate-600 tracking-[0.2em] uppercase">{{ config('app.name_th') }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 w-full max-w-7xl mx-auto">
                @php
                    $menus = [
                        ['route' => route('login'), 'en' => 'Student', 'th' => 'ผู้เรียน', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                        ['route' => url('teachers'), 'en' => 'Instructor', 'th' => 'ครูผู้สอน', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                        ['route' => route('boss'), 'en' => 'Executive', 'th' => 'ผู้บริหาร', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h4a2 2 0 002-2zm6 0v-6a2 2 0 00-2-2h-4a2 2 0 00-2 2v6a2 2 0 002 2h4a2 2 0 002-2zM11 11V5a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => route('admin'), 'en' => 'Admin', 'th' => 'ผู้ดูแลระบบ', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ['route' => '#', 'en' => 'Assessment', 'th' => 'การทดสอบ', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                        ['route' => '#', 'en' => 'Counseling', 'th' => 'แนะแนวออนไลน์', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                        ['route' => '#', 'en' => 'E-Learning', 'th' => 'คลังความรู้', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['route' => '#', 'en' => 'Skills', 'th' => 'ทักษะชีวิต', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ];
                @endphp

                @foreach($menus as $menu)
                <a href="{{ $menu['route'] }}" class="menu-card p-8 group relative flex flex-col items-center justify-center">
                    <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $menu['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1 transition-colors group-hover:text-purple-500">{{ $menu['en'] }}</p>
                        <p class="text-base font-bold text-slate-600 transition-colors group-hover:text-slate-900">{{ $menu['th'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <div class="flex items-center justify-center gap-4 opacity-60">
                    <div class="h-[1px] w-8 bg-slate-300"></div>
                    <p class="text-slate-500 text-[10px] font-bold tracking-[0.5em] uppercase">จัดทำโดย - นายนนทชัย  มาพิจารณ์</p>
                    <div class="h-[1px] w-8 bg-slate-300"></div>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('roletype')) {
                    document.getElementById('popup-modal').classList.remove('hidden');
                }
            });
            function closeModal(){
                document.getElementById('popup-modal').classList.add('hidden');
            }
        </script>
    </body>
</html>