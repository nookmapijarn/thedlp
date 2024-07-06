<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="2pi9TzWSjw9fEzla9XMTbb3-lTnVb26X8BK6X8sbx0A"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ทะเบียนนักศึกษา - 'app.name_th', การเรียนการสอน, ตารางสอบ {{ config('app.name_th') }}, นักศึกษา {{ config('app.name_th') }}, ผลการเรียน {{ config('app.name_th') }}">
        <meta name="robots" content="ทะเบียนนักศึกษา - 'app.name_th', การเรียนการสอน, ตารางสอบ {{ config('app.name_th') }}, นักศึกษา {{ config('app.name_th') }}, ผลการเรียน {{ config('app.name_th') }}">
        <title>{{ config('app.name') }} {{ config('app.name_th') }}</title>
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
        <button data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="hidden block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Toggle modal</button>
        <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full flex" aria-modal="true" role="dialog">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button onclick="closeModal()" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                        </svg>
                        <h3 class="mb-5 text-xl font-normal text-indigo-500 dark:text-gray-400">
                            สิทธิผู้ใช้ : {{$role = request()->roletype}}@if($role == 1)(ผู้เรียน)@endif @if($role == 2)(ครูผู้สอน)@endif @if($role == 3)(ผู้บริหาร)@endif @if($role == 4)(ผู้ดูแลระบบ)@endif
                        </h3>
                        @if(request()->studentnull) 
                            <p class="text-red-600"> "ไม่พบข้อมูลผู้เรียนรายนี้ โปรดติดต่อเจ้าหน้าที่" </p>
                        @endif
                        " คุณกำลังเข้าระบบส่วนที่ไม่มีสิทธิ กรุณาเลือกเมนูตามสิทธิ หรือ ออกจากระบบ เมนูด้านล่างระบบที่คุณสามารถเข้าได้ "
                        <div class="grid grid-cols-1 gap-4 mt-2">
                            @if($role == 1)
                            <a href="{{ route('login') }}" class=" sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center ">
                                <svg class=" w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <div class="text-left">
                                    <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                    <div class="-mt-1 font-sans text-sm font-semibold">ผู้เรียน</div>
                                </div>
                            </a>
                            @endif
                            @if($role == 2)
                            <a href="{{ url('teachers') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                                <svg class=" w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg> 
                                <div class="text-left">
                                    <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                    <div class="-mt-1 font-sans text-sm font-semibold">ครูผู้สอน</div>
                                </div>
                            </a>
                            @endif
                            @if($role == 3)
                            <a href="{{ route('boss') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                                <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                </svg>  
                                <div class="text-left">
                                    <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                    <div class="-mt-1 font-sans text-sm font-semibold">ผู้บริหาร</div>
                                </div>
                            </a>
                            @endif
                            @if($role == 4)
                            <a href="{{ route('admin') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                                <svg  class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                                </svg>                                  
                                <div class="text-left">
                                    <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                                    <div class="-mt-1 font-sans text-sm font-semibold">ผู้ดูแลระบบ</div>
                                </div>
                            </a>
                            @endif
                        </div>
                        <button class="mt-2 p-2 text-sm font-medium text-gray-900 focus:outline-none bg- -800rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <div :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('ออกจากระบบ') }}
                                </div>
                            </form>                                
                        </button> 
                        <button onclick="closeModal()" data-modal-hide="popup-modal" type="button" class="p-2 text-sm font-medium text-gray-900 focus:outline-none bg- -800rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100  ">ปิดหน้านี้</button>
                    </div>
                </div>
            </div>
        </div>        
        <div onclick="closeModal()" id="backdrop-modal" modal-backdrop="popup-modal" class="hidden bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40"></div>

        <section class="pt-15 bg-center bg-no-repeat bg-[url('storage/studentall.jpg')] background-animate bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-700"> 
            <div class="px-4 mx-auto max-w-screen-xl text-center py-12 lg:py-56">
                <div class="flex justify-center mb-3 md:m-5">
                    <img src="{{asset('storage/logo.png');}}" width="120px" class="drop-shadow-2xl">
                </div>
                <h1 class="md:mb-4 text-xl font-extrabold tracking-tight leading-none text-white md:text-4xl lg:text-5xl tracking-wider leading-relaxed drop-shadow-2xl">{{ config('app.name') }}</h1>
                <p class="md:mb-4 text-sm font-extrabold tracking-tight leading-none text-white md:text-4xl lg:text-2xl tracking-wider leading-relaxed drop-shadow-2xl">{{ config('app.name_system') }}</p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5  ">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ผู้เรียน</div>
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
                    <a href="{{ route('admin') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                        <svg  class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>  
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ผู้ดูแลระบบ</div>
                        </div>
                    </a>
                    {{-- <a href="{{ url('help') }}" class=" w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5">
                        <svg class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg> 
                        <div class="text-left">
                            <div class="mb-1 text-xs">สอบถาม</div>
                            <div class="-mt-1 font-sans text-sm font-semibold">ช่วยเหลือ</div>
                        </div>
                    </a> --}}
                </div>
            </div>
        </section>

        <section class="bg-gray-100 dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">OLIS DLEC</h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">" เพียงแค่ Upload ไฟล์ Backup IT ไฟล์เดียวระบบก็ใช้งานได้  : จากระบบบริหารจัดการข้อมูลผู้เรียนออนไลน์ Phothong DLEC Version 3.0 สู่ OLIS DLEC Version 3.1 (Online Learner Information System : OLIS) เรียกย่อว่า โอลิส ดีเลค เปลี่ยนแปลงเพื่อความเป็นสากล และเพื่อขยายผลสู่สถานศึกษาอื่นนำไปใช้ได้ง่าย ภายใต้มาตรการการป้องกันข้อมูลส่วนบุคคล PDPA "</p>
            </div>
        </section>

        <section class="hidden sm:block bg-gray-200">
            <div class="flex justify-center p-10">
                <div class=" text-center text-sm text-gray-100 sm:text-center sm:ml-0">
                    @include('aboutsystem')
                </div>
            </div>
        </section>
        @include('layouts.footer')
    </body>
</html>

<!-- Script to show modal on load if data is empty -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Simulated data check
        <?php if(isset($_GET['roletype'])): ?>
            var roletype = "<?php echo htmlspecialchars($_GET['roletype']); ?>";
        <?php else: ?>
            var roletype = null;
        <?php endif; ?>
        if (roletype) {
            var popup_modal = document.getElementById('popup-modal');
            var backdrop_modal = document.getElementById('backdrop-modal');
            popup_modal.classList.remove('hidden');
            backdrop_modal.classList.remove('hidden');
        }
    });
  
    function closeModal(){
      var popup_modal = document.getElementById('popup-modal');
            var backdrop_modal = document.getElementById('backdrop-modal');
            popup_modal.classList.add('hidden');
            backdrop_modal.classList.add('hidden');
    }
  
</script>