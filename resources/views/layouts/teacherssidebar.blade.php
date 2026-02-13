@php
    use Illuminate\Support\Facades\Request;
    use Illuminate\Support\Facades\Route;
    $currentPath = Request::path();
@endphp

<aside id="logo-sidebar" 
       class="fixed top-0 left-0 z-40 w-72 h-screen pt-24 transition-transform -translate-x-full bg-slate-50/40 backdrop-blur-xl border-r border-white/50 sm:translate-x-0 shadow-sm" 
       aria-label="Sidebar">
    
    <div class="h-full px-4 pb-4 overflow-y-auto bg-transparent scrollbar-hide">
        <div class="mb-6 px-4 flex items-center justify-between">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">เมนูสำหรับคุณครู</p>
            <div class="h-1.5 w-1.5 rounded-full bg-amber-400 animate-pulse shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
        </div>

        <ul class="space-y-1.5">

            {{-- 1. Dashboard --}}
            <li>
                <a href="{{ url('teachers/') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z"/><path d="M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z"/></svg>
                    </div>
                    <span class="ms-3">Dashboard (ภาพรวม)</span>
                    @if($currentPath == 'teachers') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 2. ค้นหาข้อมูลผู้เรียน --}}
            <li>
                <a href="{{ url('teachers/tstudentprofile') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/tstudentprofile' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/tstudentprofile' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16Z"/><path fill-rule="evenodd" d="M21.707 21.707a1 1 0 0 1-1.414 0l-3.5-3.5a1 1 0 0 1 1.414-1.414l3.5 3.5a1 1 0 0 1 0 1.414Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3">ค้นหาข้อมูลผู้เรียน</span>
                    @if($currentPath == 'teachers/tstudentprofile') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 3. รายงาน --}}
            <li>
                <a href="{{ url('teachers/treport') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/treport' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/treport' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7h1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h11.5M7 14h6m-6 3h6m0-10h.5m-.5 3h.5M7 7h3v3H7V7Z"/></svg>
                    </div>
                    <span class="ms-3">รายงาน</span>
                    @if($currentPath == 'teachers/treport') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 4. ผลการเรียน --}}
            <li>
                <a href="{{ url('teachers/tgrade') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/tgrade' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/tgrade' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3">ผลการเรียน</span>
                    @if($currentPath == 'teachers/tgrade') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 5. กศน.4 --}}
            <li>
                <a href="{{ url('teachers/tscore') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/tscore' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/tscore' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z"/></svg>
                    </div>
                    <span class="ms-3">กศน.4</span>
                    @if($currentPath == 'teachers/tscore') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 6. จัดการแบบทดสอบ --}}
            <li>
                <a href="{{ url('teachers/ttest') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/ttest' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/ttest' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3">จัดการแบบทดสอบ</span>
                    @if($currentPath == 'teachers/ttest') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

            {{-- 7. จัดการหนังสือ --}}
            {{-- <li>
                <a href="{{ url('teachers/tbooks') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ $currentPath == 'teachers/tbooks' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ $currentPath == 'teachers/tbooks' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11 4.717c-2.286-.58-4.16-.756-7.045-.71A1.99 1.99 0 0 0 2 6v11c0 1.133.934 2.022 2.044 2.007 2.759-.038 4.5.16 6.956.791V4.717Zm2 15.081c2.456-.631 4.198-.829 6.956-.791A2.013 2.013 0 0 0 22 16.999V6a1.99 1.99 0 0 0-1.955-1.993c-2.885-.046-4.76.13-7.045.71v15.081Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3">จัดการหนังสือ</span>
                    @if($currentPath == 'teachers/tbooks') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li> --}}

            {{-- 8. จัดการหลักสูตร --}}
            <li>
                <a href="{{ route('courses.manage') }}"
                   class="group flex items-center p-3 text-[15px] font-bold tracking-wide transition-all duration-300 rounded-2xl
                   {{ Route::currentRouteName() == 'courses.manage' ? 'bg-white text-purple-600 shadow-md translate-x-1 ring-1 ring-purple-100' : 'text-slate-500 hover:bg-white/60 hover:text-purple-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl transition-colors {{ Route::currentRouteName() == 'courses.manage' ? 'bg-purple-50 text-purple-600' : 'bg-slate-100/50 text-slate-400 group-hover:bg-purple-50 group-hover:text-purple-500' }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/></svg>
                    </div>
                    <span class="ms-3">จัดการหลักสูตร</span>
                    @if(Route::currentRouteName() == 'courses.manage') <div class="ms-auto w-1.5 h-1.5 rounded-full bg-amber-400"></div> @endif
                </a>
            </li>

        </ul>

        {{-- Footer Card --}}
        <div class="mt-8 mx-2 p-5 bg-gradient-to-br from-white/80 to-white/40 rounded-[2rem] border border-white shadow-sm group hover:shadow-md transition-all duration-300">
            <div class="flex items-center space-x-2 mb-2">
                <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">OLIS SYSTEM V 5.0</p>
            </div>
            <p class="text-[12px] font-bold text-slate-600 tracking-tight">โหมดจัดการข้อมูล (Teacher)</p>
            <div class="mt-3 flex justify-end">
                <span class="text-[10px] px-2 py-1 bg-amber-100 text-amber-600 rounded-lg font-bold">Authorized Access</span>
            </div>
        </div>
    </div>
</aside>