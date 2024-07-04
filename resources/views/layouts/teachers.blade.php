<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="2pi9TzWSjw9fEzla9XMTbb3-lTnVb26X8BK6X8sbx0A"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ทะเบียนนักศึกษา - 'app.name_th' {{ config('app.name_th') }}, นักศึกษา {{ config('app.name_th') }}, ผลการเรียน {{ config('app.name_th') }}">
        <meta name="robots" content="ทะเบียนนักศึกษา - 'app.name_th' {{ config('app.name_th') }}, นักศึกษา {{ config('app.name_th') }}, ผลการเรียน {{ config('app.name_th') }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }} (สำหรับครู)</title>
        <link rel="icon" type="image/x-icon" href="{{asset('storage/logo.png');}}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- google font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    
    <body class="font-sans antialiased min-h-screen">
        <div class="min-h-screen">
            @include('layouts.teacherssidebar')
            @include('layouts.teachersnavigation')
            <main class="h-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
