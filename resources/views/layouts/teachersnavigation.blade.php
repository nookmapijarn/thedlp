<nav class="fixed top-0 z-50 w-full bg-white/60 backdrop-blur-xl border-b border-white/50 shadow-sm transition-all duration-300">
    <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
            
            {{-- Left Section: Logo & Hamburger --}}
            <div class="flex items-center">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" 
                        class="hidden p-2 text-slate-500 rounded-xl hover:bg-purple-50 hover:text-purple-600 transition-all focus:outline-none">
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

                <!-- PWA Install Button -->
                <button type="button" style="display: none;" class="pwa-install-btn p-2 text-slate-500 hover:text-slate-750 hover:bg-slate-100/80 rounded-xl focus:outline-none transition-colors" title="ติดตั้งแอปพลิเคชัน">
                  <svg class="w-6 h-6 text-purple-600 animate-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                  </svg>
                </button>

                <!-- Notification Bell Dropdown -->
                @php
                  $unreadNotifications = \App\Models\HelpNotification::where('user_id', Auth::id())->where('is_read', false)->latest()->take(5)->get();
                @endphp
                <div class="relative flex items-center" x-data="{ open: false }">
                  <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-slate-750 hover:bg-slate-100/80 rounded-xl focus:outline-none transition-colors">
                    <!-- Bell Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                    </svg>
                    @if($unreadNotifications->count() > 0)
                      <span class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-black text-white ring-2 ring-white shadow-md">
                        {{ $unreadNotifications->count() }}
                      </span>
                    @endif
                  </button>
                  
                  <div x-show="open" @click.outside="open = false" x-cloak
                       class="absolute -right-12 sm:right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] sm:max-w-xs bg-white border border-slate-150 rounded-2xl shadow-xl z-50 py-2 divide-y divide-slate-100 animate-in fade-in duration-200">
                    <div class="px-4 py-2 font-bold text-slate-700 flex justify-between items-center text-xs">
                      <span>การแจ้งเตือน</span>
                      @if($unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.read_all') }}" method="POST">
                          @csrf
                          <button type="submit" class="text-[10px] text-purple-600 hover:underline">อ่านทั้งหมด</button>
                        </form>
                      @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto divide-y divide-slate-50 text-xs">
                      @forelse($unreadNotifications as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}" class="flex px-4 py-3 hover:bg-slate-50 transition-colors">
                          <div class="flex-1">
                            <p class="text-[11px] font-bold text-slate-750 leading-snug">{{ $notif->message }}</p>
                            <span class="text-[9px] text-slate-400 font-bold block mt-1">{{ $notif->created_at->diffForHumans() }}</span>
                          </div>
                        </a>
                      @empty
                        <div class="p-6 text-center text-slate-400 font-black text-xs">ไม่มีการแจ้งเตือนใหม่</div>
                      @endforelse
                    </div>
                  </div>
                </div>

                <div class="flex items-center ms-3">
                    <button type="button" class="flex text-sm bg-white rounded-2xl ring-2 ring-purple-50 focus:ring-4 focus:ring-purple-100 transition-all duration-300 overflow-hidden shadow-sm" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-9 h-9 object-cover" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" alt="teacher photo">
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
                                <a href="{{ route('teachers.profile.edit') }}" class="w-full text-left flex items-center px-5 py-2.5 text-sm text-slate-650 font-bold hover:bg-purple-50 hover:text-purple-600 transition-colors group">
                                    <svg class="w-4 h-4 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-7 9a7 7 0 0 1 14 0H5Z"/>
                                    </svg>
                                    จัดการโปรไฟล์
                                </a>
                            </li>
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