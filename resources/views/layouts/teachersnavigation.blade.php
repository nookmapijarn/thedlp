<nav class="fixed top-0 z-50 w-full bg-white/60 backdrop-blur-xl border-b border-white/50 shadow-sm transition-all duration-300">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
            
            {{-- Left Section: Logo & Hamburger --}}
            <div class="flex items-center">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" 
                        class="inline-flex items-center p-2 text-slate-500 rounded-xl sm:hidden hover:bg-purple-50 hover:text-purple-600 transition-all focus:outline-none">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <a href="{{ url('teachers/') }}" class="flex ms-3 items-center space-x-3 group">
                    <div class="p-1.5 bg-white rounded-xl shadow-sm border border-slate-100 group-hover:scale-105 transition-transform duration-300">
                        <x-application-logo class="w-8 h-8" />
                    </div>
                    <div class="flex flex-col">
                        <span class="self-center text-lg font-black tracking-tight text-slate-700">
                            {{ config('app.name') }}
                        </span>
                        <span class="text-[10px] font-bold text-purple-500 uppercase tracking-widest -mt-1 leading-none">Teacher Edition</span>
                    </div>
                </a>
            </div>

            {{-- Right Section: User Profile --}}
            <div class="flex items-center space-x-4">
                
                {{-- Status Badge (Desktop Only) --}}
                <div class="hidden md:flex items-center px-3 py-1 bg-amber-50 border border-amber-100 rounded-full">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Teacher Online</span>
                </div>

                <div class="flex items-center ms-3">
                    <button type="button" class="flex text-sm bg-white rounded-2xl ring-2 ring-purple-50 focus:ring-4 focus:ring-purple-100 transition-all duration-300 overflow-hidden shadow-sm" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-9 h-9 object-cover" src="https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg" alt="teacher photo">
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div class="z-50 hidden my-4 text-base list-none bg-white/90 backdrop-blur-lg divide-y divide-slate-100 rounded-2xl shadow-xl border border-white/50" id="dropdown-user">
                        <div class="px-5 py-4" role="none">
                            <p class="text-sm font-bold text-slate-700" role="none">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs font-medium text-slate-400 truncate" role="none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <ul class="py-2" role="none">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-5 py-2.5 text-sm text-red-500 font-bold hover:bg-red-50 transition-colors group">
                                        <svg class="w-4 h-4 mr-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('ออกจากระบบ') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>