@php
    use Illuminate\Support\Facades\Request;
    // $menuItems คงเดิมตาม Logic ของคุณ
     $menuItems = [];
    // $menuItems = [
    //     ['url' => 'ประวัติการเรียน', 'label' => 'ประวัติการเรียน'],
    //     ['url' => 'ประวัติการลงทะเบียน', 'label' => 'ประวัติการลงทะเบียน'],
    //     // ... เพิ่มรายการอื่นได้ตามเดิม
    // ];
@endphp

<nav x-data="{ open: false }" 
     class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-white shadow-sm transition-all duration-300">
    
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('welcome') }}" class="flex items-center group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-400/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <x-application-logo class="block h-10 w-auto relative z-10 fill-current text-blue-600 transition-transform group-hover:scale-105" />
                        </div>
                        <strong class="ml-3 text-gray-900 text-sm font-black tracking-[0.15em] uppercase leading-none"> 
                            {{ config('app.name') }} 
                        </strong>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-12 sm:flex">
                    @foreach ($menuItems as $item)
                        <x-nav-link :href="route($item['url'])" :active="request()->routeIs($item['url'])" 
                                    class="text-[12px] font-bold uppercase tracking-[0.2em] transition-all duration-300 border-b-2
                                    {{ request()->routeIs($item['url']) ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-200' }}">
                            {{ __($item['label']) }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Notification Bell Dropdown -->
                @php
                  $unreadNotifications = \App\Models\HelpNotification::where('user_id', Auth::id())->where('is_read', false)->latest()->take(5)->get();
                @endphp
                <div class="relative mr-3" x-data="{ open: false }">
                  <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-slate-50 rounded-xl focus:outline-none transition-colors">
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
                       class="absolute right-0 mt-2 w-80 bg-white border border-slate-150 rounded-2xl shadow-xl z-50 py-2 divide-y divide-slate-100 animate-in fade-in duration-200">
                    <div class="px-4 py-2 font-bold text-slate-700 flex justify-between items-center text-xs">
                      <span>การแจ้งเตือน</span>
                      @if($unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.read_all') }}" method="POST">
                          @csrf
                          <button type="submit" class="text-[10px] text-blue-600 hover:underline">อ่านทั้งหมด</button>
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

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center px-4 py-2 bg-slate-50/50 border border-slate-100 rounded-2xl hover:bg-white hover:shadow-md transition-all duration-300 focus:outline-none">
                            <div class="text-right mr-3">
                                <div class="text-[11px] font-black text-gray-800 uppercase tracking-widest">{{ Auth::user()->name }}</div>
                                <div class="text-[9px] text-blue-500 font-bold tracking-widest">ONLINE</div>
                            </div>
                            <div class="ml-1 text-gray-400">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl text-xs font-bold tracking-wide uppercase py-3">
                                {{ __('ตั้งค่าผู้ใช้งาน') }}
                            </x-dropdown-link>
                            <div class="h-[1px] bg-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" 
                                        class="rounded-xl text-xs font-bold tracking-wide uppercase py-3 text-red-500 hover:bg-red-50">
                                    {{ __('ออกจากระบบ') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <!-- Mobile notification bell -->
                <div class="relative mr-2" x-data="{ open: false }">
                  <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-slate-50 rounded-xl focus:outline-none transition-colors">
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
                       class="absolute right-0 mt-2 w-72 bg-white border border-slate-150 rounded-2xl shadow-xl z-50 py-2 divide-y divide-slate-100 animate-in fade-in duration-200">
                    <div class="px-4 py-2 font-bold text-slate-700 flex justify-between items-center text-xs">
                      <span>การแจ้งเตือน</span>
                      @if($unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.read_all') }}" method="POST">
                          @csrf
                          <button type="submit" class="text-[10px] text-blue-600 hover:underline">อ่านทั้งหมด</button>
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
                
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-gray-400 hover:bg-slate-50 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-50 shadow-2xl rounded-b-[2rem] overflow-hidden">
        <div class="pt-4 pb-3 space-y-2 px-4">
            @foreach ($menuItems as $item)
                <x-responsive-nav-link :href="route($item['url'])" :active="request()->routeIs($item['url'])" 
                                        class="rounded-2xl text-[12px] font-bold uppercase tracking-[0.2em] py-4 
                                        {{ request()->routeIs($item['url']) ? 'bg-blue-50 text-blue-600' : 'text-gray-400' }}">
                    {{ __($item['label']) }}
                </x-responsive-nav-link>
            @endforeach
        </div>

        <div class="pt-4 pb-6 border-t border-slate-100 bg-slate-50/50 px-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-blue-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="ml-3">
                    <div class="font-black text-sm text-gray-800 uppercase tracking-widest">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-[10px] text-gray-400 uppercase tracking-widest">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-center py-3 bg-white border border-slate-200 rounded-xl text-[11px] font-black uppercase tracking-[0.2em] text-red-500 shadow-sm">
                        {{ __('ออกจากระบบ') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>