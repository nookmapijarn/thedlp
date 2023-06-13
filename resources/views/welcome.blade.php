<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="2pi9TzWSjw9fEzla9XMTbb3-lTnVb26X8BK6X8sbx0A"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ทะเบียนนักศึกษา - สกร.อำเภอโพธิ์ทอง, การเรียนการสอน, ตารางสอบ สกร.อำเภอโพธิ์ทอง, นักศึกษา สกร.อำเภอโพธิ์ทอง, ผลการเรียน สกร.อำเภอโพธิ์ทอง">
        <meta name="robots" content="ทะเบียนนักศึกษา - สกร.อำเภอโพธิ์ทอง, การเรียนการสอน, ตารางสอบ สกร.อำเภอโพธิ์ทอง, นักศึกษา สกร.อำเภอโพธิ์ทอง, ผลการเรียน สกร.อำเภอโพธิ์ทอง">
        <title>{{ config('app.name', 'งานการศึกษาพื้นฐาน-สกร.อำเภอโพธิ์ทอง') }}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('storage/logo.png');}}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- google font --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class=" antialiased background-animate bg-gradient-to-r from-purple-500 via-violet-600 via-violet-300 to-purple-500">
        <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0 ">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                    @auth
                        <a href="{{ url('/ประวัติการเรียน') }}" class="text-sm text-gray-100 dark:text-gray-100 underline">ประวัติการเรียน</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-100 dark:text-gray-100 underline">เข้าสู่ระบบ</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-100 dark:text-gray-100 underline">สมัครสมาชิก</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-36xl mx-auto sm:px-6 lg:px-8 p-4">
                <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                    <div class="flex items-center space-x-4">
                        <img src="{{asset('storage/logo.png');}}" width="55px">
                        <div class="text-1lg text-white">
                            <h1 class="tracking-widest font-semibold">ศูนย์ส่งเสริมการเรียนรู้อำเภอโพธิ์ทอง</h1>
                            <div class="text-xs text-gray-100 tracking-wider">กลุ่มจัดการศึกษานอกระบบและการศึกษาตามอัธยาศัย</div>
                        </div>
                    </div>
                </div>

                <div class="w-full p-5 text-center bg-white-100 rounded-lg sm:p-8">
                    {{-- <h5 class="mb-2 text-2xl font-bold text-white">งานการศึกษาขั้นพื้นฐาน - ทะเบียนนักศึกษา</h5>
                    <p class="mb-5 text-base text-white sm:text-sm">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p> --}}
                    <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <div class="text-left">
                                <div class="mb-1 text-xs">ทะเบียนนักศึกษา</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">นักศึกษา</div>
                            </div>
                        </a>
                        <a href="{{ url('teachers') }}" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg> 
                            <div class="text-left">
                                <div class="mb-1 text-xs">จัดการนักศึกษา</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ครูผู้สอน</div>
                            </div>
                        </a>
                        <a href="{{ route('boss') }}" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>  
                            <div class="text-left">
                                <div class="mb-1 text-xs">ข้อมูล Big Data</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ผู้บริหาร</div>
                            </div>
                        </a>
                        <a href="{{ url('help') }}" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg> 
                            <div class="text-left">
                                <div class="mb-1 text-xs">สมัครเรียน, สอบถาม</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ช่วยเหลือ</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="ml-4 text-center w-full text-sm text-gray-100 sm:text-center sm:ml-0">
                        © ผู้พัฒนาระบบ นายนนทชัย  มาพิจารณ์  ครู สกร.อำเภอโพธิ์ทอง
                    </div>
                </div>
                <div class="flex justify-center mt-4 hidden sm:block">
                    <div class="ml-4 text-center w-full text-sm text-gray-100 sm:text-center sm:ml-0">
                        @include('aboutsystem')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
