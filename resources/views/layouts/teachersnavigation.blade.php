{{-- <nav x-data="{ open: false }" class="bg-purple-800 shadow-100 border-b border-gray-100 dark:bg-gray-800 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-24">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('welcome') }}">
                        <x-application-logo class="block w-auto fill-current text-gray-600" />                        
                    </a>
                    <strong class="mr-2 text-gray-100 text-lg tracking-wide"> (สำหรับครู) </strong>
                </div>
                @php
                $currentPath = Request::path();
                @endphp
                <!-- Navigation Links space-x-8 sm:-my-px sm:ml-10 sm:flex text-lg -->
                <div class="hidden space-x-0 sm:flex"> 
                    <x-nav-link href="{{ url('teachers/') }}" class="flex justify-center text-white hover:bg-purple-900 w-32 {{ $currentPath == 'teachers' ? 'bg-purple-900 ' : '' }}">
                        <svg class="w-6 h-6 text-gray-100 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                        </svg>                          
                        <div class="text-white text-sm pl-2">รายงาน</div>
                    </x-nav-link>
                    <x-nav-link href="{{ url('teachers/tgrade') }}" class="flex justify-center text-white hover:bg-purple-900 w-32  {{ $currentPath == 'teachers/tgrade' ? 'bg-purple-900 ' : '' }}">
                        <svg class="w-6 h-6 text-gray-800 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm2-2a1 1 0 1 0 0 2h3a1 1 0 1 0 0-2h-3Zm0 3a1 1 0 1 0 0 2h3a1 1 0 1 0 0-2h-3Zm-6 4a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1v-6Zm8 1v1h-2v-1h2Zm0 3h-2v1h2v-1Zm-4-3v1H9v-1h2Zm0 3H9v1h2v-1Z" clip-rule="evenodd"/>
                          </svg>                                                  
                        <div class="text-white text-sm pl-2">ผลการเรียน</div>
                    </x-nav-link>
                    <x-nav-link href="{{route('tstudentprofile')}}" class="flex justify-center text-white hover:bg-purple-900 w-32  {{ $currentPath == 'teachers/tstudentprofile' ? 'bg-purple-900 ' : '' }}">
                        <svg class="w-6 h-6 text-gray-800 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>                                                  
                        <div class="text-white text-sm pl-2">ข้อมูลผู้เรียน</div>
                    </x-nav-link>
                    <x-nav-link href="{{route('tscore')}}" class="flex justify-center text-white hover:bg-purple-900 w-32  {{ $currentPath == 'teachers/tscore' ? 'bg-purple-900 ' : '' }}">
                        <svg class="w-6 h-6 text-gray-800 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 5V4a1 1 0 0 0-1-1H8.914a1 1 0 0 0-.707.293L4.293 7.207A1 1 0 0 0 4 7.914V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-5M9 3v4a1 1 0 0 1-1 1H4m11.383.772 2.745 2.746m1.215-3.906a2.089 2.089 0 0 1 0 2.953l-6.65 6.646L9 17.95l.739-3.692 6.646-6.646a2.087 2.087 0 0 1 2.958 0Z"/>
                        </svg>                                                  
                        <div class="text-white text-sm pl-2">กศน.4</div>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6  text-white">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-100 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div></div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" class=" text-white">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-gray-400">
                                {{ __('ออกจากระบบ') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-100 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden  text-white">
        <div class="pt-0 pb-0 space-y-1">
            <x-nav-link href="{{ url('teachers/') }}" class="text-white">
                {{ __('รายงานนักศึกษา') }}
            </x-nav-link>
            <x-nav-link href="{{ url('teachers/tgrade') }}" class="text-white">
                {{ __('ผลการเรียน') }}
            </x-nav-link>
            <x-nav-link href="{{route('tstudentprofile')}}" class="text-white">
                {{ __('ค้นหาประวัติผู้เรียน') }}
            </x-nav-link>
            <x-nav-link href="{{route('tscore')}}" class="text-white">
                {{ __('กศน.4') }}
            </x-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-100">xxx</div>
                <div class="font-medium text-sm text-gray-100">xxx</div>
            </div>

            <div class="mt-3 space-y-1 text-white">
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-white">
                        {{ __('ออกจากระบบ') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> --}}

  