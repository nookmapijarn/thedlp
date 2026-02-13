<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"> <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/logo.png') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'IBM Plex Sans Thai', sans-serif; }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            html { scroll-behavior: smooth; }
            
            /* เทคนิคทำให้เนื้อหาไม่กระตุกเวลาซ่อน/แสดง Bottom Tab */
            body { -webkit-tap-highlight-color: transparent; }
        </style>
    </head>
    
    <body class="antialiased bg-slate-50 text-slate-900 overflow-x-hidden selection:bg-blue-100"> 
        
        @include('layouts.navigation')

        @include('layouts.studentssidebar')

        <div class="ml-0 sm:ml-72 min-h-screen transition-all duration-300 flex flex-col">
            
            <div class="sticky top-[64px] z-20"> 
                @include('layouts.studentstab')
            </div>

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

                <main> <div class="max-w-7xl mx-auto pb-10">
                        {{ $slot }}
                    </div>
                </main>
            </div>

            <footer class="mt-auto border-t border-slate-100 bg-white/30 hidden sm:block">
                @include('layouts.footer')
            </footer>
        </div>

    </body>
</html>