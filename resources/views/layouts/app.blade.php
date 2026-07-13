<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
        <meta name="theme-color" content="#7e22ce">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="#7e22ce">
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo.png') }}">
        <link rel="manifest" href="/manifest.json">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { 
                font-family: 'IBM Plex Sans Thai', sans-serif; 
                overscroll-behavior-y: contain; /* ป้องกันการดึงหน้าจอเพื่อรีเฟรชบนมือถือ (Elastic bounce) */
                -webkit-tap-highlight-color: transparent;
            }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            html { scroll-behavior: smooth; }
        </style>
    </head>
    
    <body class="antialiased bg-slate-50 text-slate-900 overflow-x-hidden selection:bg-blue-100"> 
        
        @include('layouts.navigation')

        @include('layouts.studentssidebar')

        <div class="ml-0 sm:ml-72 min-h-screen transition-all duration-300 flex flex-col">
            
            <div class="flex-grow">
                {{-- @if (isset($header))
                    <header class="bg-white/50 backdrop-blur-md border-b border-slate-200/60">
                        <div class="max-w-7xl mx-auto py-5 px-6 sm:px-8">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight uppercase">
                                {{ $header }}
                            </h1>
                        </div>
                    </header>
                @endif --}}
 
                <main> <div class="max-w-7xl mx-auto pb-24 sm:pb-10">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            <footer class="mt-auto border-t border-slate-100 bg-white/30 hidden sm:block">
                @include('layouts.footer')
            </footer>
        </div>

        <!-- Bottom Tab Bar for Mobile PWA (Exactly 5 tabs) -->
        <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-slate-200/80 dark:border-slate-800/80 shadow-[0_-4px_24px_rgba(0,0,0,0.06)] sm:hidden pb-safe">
            <div class="grid grid-cols-5 h-16 max-w-md mx-auto items-center justify-center">
                
                <!-- Tab 1: หน้าหลัก -->
                <a href="{{ url('home') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('home') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                    <svg class="w-6 h-6 mb-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-wider leading-none">หน้าหลัก</span>
                </a>

                <!-- Tab 2: คลิปสั้น -->
                <a href="{{ route('shorts.index') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('shorts*') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                    <svg class="w-6 h-6 mb-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-wider leading-none">คลิปสั้น</span>
                </a>

                <!-- Tab 3: ประวัติเรียน -->
                <a href="{{ url('ประวัติการเรียน') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('ประวัติการเรียน') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                    <svg class="w-6 h-6 mb-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-wider leading-none">ประวัติเรียน</span>
                </a>

                <!-- Tab 4: ตารางสอบ -->
                <a href="{{ url('ตารางสอบ') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('ตารางสอบ') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                    <svg class="w-6 h-6 mb-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-wider leading-none">ตารางสอบ</span>
                </a>

                <!-- Tab 5: เรียนออนไลน์ -->
                <a href="{{ url('เรียนออนไลน์') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('เรียนออนไลน์') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                    <svg class="w-6 h-6 mb-0.5 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/>
                    </svg>
                    <span class="text-[9px] font-black tracking-wider leading-none">เรียนออนไลน์</span>
                </a>

            </div>
        </div>

        @include('layouts.fcm_initializer')
        @include('layouts.pwa_installer')
        @include('layouts.page_loader')
    </body>
</html>