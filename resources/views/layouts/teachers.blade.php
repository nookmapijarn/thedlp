<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="สำหรับครู รายงานผู้คาดว่าจะจบ รายชื่อผู้มีสิทธิสอบ N-Net, รายชื่อผู้มีสิทธิสอบ E-Exam, รายงานชั่วโมง กพช.">
        <meta name="robots" content="สำหรับครู รายงานผู้คาดว่าจะจบ รายชื่อผู้มีสิทธิสอบ N-Net, รายชื่อผู้มีสิทธิสอบ E-Exam, รายงานชั่วโมง กพช.">
        <title>{{ config('app.name', 'สำหรับครู-สกร.อำเภอโพธิ์ทอง') }}</title>
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

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 bg-indio-500">
            @include('layouts.teachersnavigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    @include('layouts.footer')
</html>
