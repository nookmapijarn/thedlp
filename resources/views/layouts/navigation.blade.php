@php
    use Illuminate\Support\Facades\Request;
    $menuItems = [];
    // $menuItems = [
    //     ['url' => 'ประวัติการเรียน', 'label' => 'ประวัติการเรียน'],
    //     ['url' => 'ประวัติการลงทะเบียน', 'label' => 'ประวัติการลงทะเบียน'],
    //     ['url' => 'ตารางสอบ', 'label' => 'ตารางสอบ'],
    //     ['url' => 'กพช', 'label' => 'กิจกรรม กพช.'],
    //     ['url' => 'เรียนออนไลน์', 'label' => 'เรียนออนไลน์'],
    //     ['url' => 'olisai', 'label' => 'OLIS AI'],
    //     ['url' => 'สื่อการเรียนรู้', 'label' => 'สื่อการเรียนรู้'],
    //     ['url' => 'ทดสอบออนไลน์', 'label' => 'ทดสอบออนไลน์'],
    // ];
@endphp

<nav x-data="{ open: false }" class="bg-opacity-50 background-animate bg-gradient-to-r from-purple-500 via-violet-800 to-purple-500 shadow-10 border-b border-gray-100 dark:bg-gray-800 sticky top-0 z-50">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('welcome') }}">
                        <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
                    </a>
                    <strong class="ml-2 text-gray-100 text-lg tracking-wide"> {{ config('app.name') }} </strong>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex text-lg">
                    @foreach ($menuItems as $item)
                        <x-nav-link :href="route($item['url'])" :active="request()->routeIs($item['url'])" class="text-white">
                            {{ __($item['label']) }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6 text-white">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center text-sm font-medium text-gray-100 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
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
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('ตั้งค่าผู้ใช้งาน') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}" class="text-white">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                             this.closest('form').submit();" class="text-gray-400">
                                {{ __('ออกจากระบบ') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

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

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden text-white">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($menuItems as $item)
                <x-responsive-nav-link :href="route($item['url'])" :active="request()->routeIs($item['url'])" class="{{ request()->routeIs($item['url']) ? 'text-indigo-800' : 'text-white' }}">
                    {{ __($item['label']) }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <a href="{{ url('profile') }}">
                    <div class="font-medium text-base text-gray-100">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-100">{{ Auth::user()->email }}</div>
                </a>
            </div>

            <div class="mt-3 space-y-1 text-white">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                     this.closest('form').submit();" class="text-white">
                        {{ __('ออกจากระบบ') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>