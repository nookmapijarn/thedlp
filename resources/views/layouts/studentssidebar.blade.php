@php
    use Illuminate\Support\Facades\Request;
@endphp

<aside id="logo-sidebar" 
       class="fixed top-0 left-0 z-40 w-72 h-screen pt-24 transition-transform -translate-x-full bg-slate-50/40 backdrop-blur-xl border-r border-white/50 sm:translate-x-0 hidden sm:block shadow-sm" 
       aria-label="Sidebar">
    
    <div class="h-full px-4 pb-4 overflow-y-auto bg-transparent scrollbar-hide">
        <div class="mb-6 px-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">เมนูหลัก (PWA)</p>
        </div>

        <ul class="space-y-1.5">

            {{-- 1. หน้าแรก --}}
            <li>
                <a href="{{ url('home') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('home') ? 'bg-white text-purple-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('home') ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
                        </svg>
                    </div>
                    <span class="ms-3 tracking-widest text-xs font-black">หน้าแรก</span>
                </a>
            </li>

            {{-- 2. คลิปสั้น OLIS --}}
            <li>
                <a href="{{ route('shorts.index') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('shorts*') ? 'bg-white text-purple-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('shorts*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                    </div>
                    <span class="ms-3 tracking-widest text-xs font-black">คลิปสั้น OLIS</span>
                </a>
            </li>

            {{-- 3. ประวัติการเรียน --}}
            <li>
                <a href="{{ url('ประวัติการเรียน') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('ประวัติการเรียน') ? 'bg-white text-purple-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('ประวัติการเรียน') ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="ms-3 tracking-widest text-xs font-black">ประวัติการเรียน</span>
                </a>
            </li>

            {{-- 4. ตารางสอบ --}}
            <li>
                <a href="{{ url('ตารางสอบ') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('ตารางสอบ') ? 'bg-white text-purple-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('ตารางสอบ') ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"></path>
                        </svg>
                    </div>
                    <span class="ms-3 tracking-widest text-xs font-black">ตารางสอบ</span>
                </a>
            </li>

            {{-- 5. เรียนออนไลน์ --}}
            <li>
                <a href="{{ url('เรียนออนไลน์') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('เรียนออนไลน์') ? 'bg-white text-purple-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('เรียนออนไลน์') ? 'text-purple-600' : 'text-slate-400 group-hover:text-purple-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c2.785 0 5.5-1.44 7.74-3.313a.47.47 0 00-.495-6.347m-15.006-.111l2.5-4.103a48.58 48.58 0 0112.502 0l2.5 4.102m-15.002.001A48.653 48.653 0 0012 12.75a48.638 48.638 0 007.752-2.602"></path>
                        </svg>
                    </div>
                    <span class="ms-3 tracking-widest text-xs font-black">เรียนออนไลน์</span>
                </a>
            </li>

        </ul>
    </div>
</aside>