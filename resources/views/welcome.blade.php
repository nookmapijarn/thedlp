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
    <body class="">
        @if(request()->get('roletype')!='')
        <section class="grid justify-items-center bg-red-500 sticky t-0">
            <div id="toast-danger" class="flex mt-2 items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ml-3 text-sm font-normal"> 
                    USER : {{request()->roletype}} ไม่มีสิทธิเข้าถึงหน้านี้ "กรุณาใช้หน้าอื่น" 
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-nav-link :href="route('logout')" onclick="event.preventDefault();
                                            this.closest('form').submit();" class="text-red-300">
                            {{ __('ออกจากระบบ') }}
                        </x-nav-link>
                    </form>
                </div>
                {{-- <div class="ml-3 text-sm font-normal">
                    <a href="{{ route('logout') }}" class="text-sm text-red-400 dark:text-gray-100 underline">ออกจากระบบ</a>
                </div> --}}
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-danger" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        </section>
        @endif
        {{-- <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0 ">
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

            <div class="max-w-36xl mx-auto p-5 sm:px-6 lg:px-8 ">
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
                    <h5 class="mb-2 text-2xl font-bold text-white">งานการศึกษาขั้นพื้นฐาน - ทะเบียนนักศึกษา</h5>
                    <p class="mb-5 text-base text-white sm:text-sm">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p>
                    <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5  ">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <div class="text-left">
                                <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">นักศึกษา</div>
                            </div>
                        </a>
                        <a href="{{ url('teachers') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg> 
                            <div class="text-left">
                                <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ครูผู้สอน</div>
                            </div>
                        </a>
                        <a href="{{ route('boss') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                            </svg>  
                            <div class="text-left">
                                <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ผู้บริหาร</div>
                            </div>
                        </a>
                        <a href="{{ url('help') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                            <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg> 
                            <div class="text-left">
                                <div class="mb-1 text-xs">เพิ่มเติม</div>
                                <div class="-mt-1 font-sans text-sm font-semibold">ช่วยเหลือ</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="flex justify-center mt-4 hidden sm:block">
                    <div class="ml-4 text-center w-full text-sm text-gray-100 sm:text-center sm:ml-0">
                        @include('aboutsystem')
                    </div>
                </div>
                <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                    <div class="ml-4 text-center w-full text-sm text-gray-100 sm:text-center sm:ml-0">
                        © นายนนทชัย  มาพิจารณ์  ครู สกร.อำเภอโพธิ์ทอง
                    </div>
                </div>
            </div>
        </div> --}}

        <section class="pt-15 bg-center bg-no-repeat bg-[url('storage/studentall.jpg')] background-animate bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"> 
            <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
                <div class="flex justify-center m-7">
                    <img src="{{asset('storage/logo.png');}}" width="120px" class="drop-shadow-2xl">
                </div>
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-4xl lg:text-5xl tracking-wider leading-relaxed drop-shadow-2xl">ศูนย์ส่งเสริมการเรียนรู้อำเภอโพธิ์ทอง</h1>
                <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48 drop-shadow-2xl">Phothong District Learning Encouragement Center</p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5  ">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">นักศึกษา</div>
                        </div>
                    </a>
                    <a href="{{ url('teachers') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg> 
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ครูผู้สอน</div>
                        </div>
                    </a>
                    <a href="{{ route('boss') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                        </svg>  
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ผู้บริหาร</div>
                        </div>
                    </a>
                    <a href="{{ url('help') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg> 
                        <div class="text-left">
                            <div class="mb-1 text-xs">สอบถาม</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ช่วยเหลือ</div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        <section class="hidden sm:block">
            <div class="flex justify-center p-10">
                <div class="text-center text-sm text-gray-100 sm:text-center sm:ml-0">
                    @include('aboutsystem')
                </div>
            </div>
        </section>

        <section class="bg-gray-100 dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
                {{-- <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</h1> --}}
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400"> "คิดเป็น" เชื่อว่ามนุษย์ทุกคนมีพื้นฐานชีวิตแตกต่างกันมีวิถีการดำเนินชีวิตที่แตกต่างกันมีความ ต้องการที่แตกต่างกัน  แต่ทุกคนล้วนมีความต้องการที่จะมีความสุขอย่างอัตภาพเหมือนกัน.</p>
            </div>
        </section>
        
    </body>
    @include('layouts.footer')
</html>
