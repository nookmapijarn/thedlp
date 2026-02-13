@php
    use Illuminate\Support\Facades\Request;
@endphp

<aside id="logo-sidebar" 
       class="fixed top-0 left-0 z-40 w-72 h-screen pt-24 transition-transform -translate-x-full bg-slate-50/40 backdrop-blur-xl border-r border-white/50 sm:translate-x-0 hidden sm:block shadow-sm" 
       aria-label="Sidebar">
    
    <div class="h-full px-4 pb-4 overflow-y-auto bg-transparent scrollbar-hide">
        <div class="mb-6 px-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">เมนูหลัก</p>
        </div>

        <ul class="space-y-1.5">

            {{-- 1. ประวัติการเรียน --}}
            <li>
                <a href="{{ url('ประวัติการเรียน') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('ประวัติการเรียน') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('ประวัติการเรียน') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z"/><path d="M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">ประวัติการเรียน</span>
                </a>
            </li>

            {{-- 2. การลงทะเบียน --}}
            <li>
                <a href="{{ url('การลงทะเบียน') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('การลงทะเบียน') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('การลงทะเบียน') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">การลงทะเบียน</span>
                </a>
            </li>

            {{-- 3. ตารางสอบ --}}
            <li>
                <a href="{{ url('ตารางสอบ') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('ตารางสอบ') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('ตารางสอบ') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">ตารางสอบ</span>
                </a>
            </li>

            {{-- 4. กิจกรรม กพช. --}}
            <li>
                <a href="{{ url('กพช') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('กพช') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('กพช') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm5.495.93A.5.5 0 0 0 6.5 13c0 1.19.644 2.438 1.618 3.375C9.099 17.319 10.469 18 12 18c1.531 0 2.9-.681 3.882-1.625.974-.937 1.618-2.184 1.618-3.375a.5.5 0 0 0-.995-.07.764.764 0 0 1-.156.096c-.214.106-.554.208-1.006.295-.896.173-2.111.262-3.343.262-1.232 0-2.447-.09-3.343-.262-.452-.087-.792-.19-1.005-.295a.762.762 0 0 1-.157-.096ZM8.99 8a1 1 0 0 0 0 2H9a1 1 0 1 0 0-2h-.01Zm6 0a1 1 0 1 0 0 2H15a1 1 0 1 0 0-2h-.01Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">กิจกรรม กพช.</span>
                </a>
            </li>

            {{-- 5. เรียนออนไลน์ --}}
            <li>
                <a href="{{ url('เรียนออนไลน์') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('เรียนออนไลน์') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('เรียนออนไลน์') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">เรียนออนไลน์</span>
                </a>
            </li>

            {{-- 6. OLIS AI --}}
            {{-- <li>
                <a href="{{ url('olisai') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('olisai') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('olisai') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">OLIS AI</span>
                </a>
            </li> --}}

            {{-- 7. สื่อการเรียนรู้ --}}
            <li>
                <a href="{{ url('สื่อการเรียนรู้') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('สื่อการเรียนรู้') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('สื่อการเรียนรู้') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11 4.717c-2.286-.58-4.16-.756-7.045-.71A1.99 1.99 0 0 0 2 6v11c0 1.133.934 2.022 2.044 2.007 2.759-.038 4.5.16 6.956.791V4.717Zm2 15.081c2.456-.631 4.198-.829 6.956-.791A2.013 2.013 0 0 0 22 16.999V6a1.99 1.99 0 0 0-1.955-1.993c-2.885-.046-4.76.13-7.045.71v15.081Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">สื่อการเรียนรู้</span>
                </a>
            </li>

            {{-- 8. ทดสอบออนไลน์ --}}
            <li>
                <a href="{{ url('ทดสอบออนไลน์') }}"
                   class="group flex items-center p-3 text-[16px] font-bold uppercase tracking-wider transition-all duration-200 rounded-2xl
                   {{ Request::is('ทดสอบออนไลน์') ? 'bg-white text-blue-600 shadow-md translate-x-1' : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is('ทดสอบออนไลน์') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="ms-3 tracking-widest">ทดสอบออนไลน์</span>
                </a>
            </li>

        </ul>

        {{-- Footer Card --}}
        <div class="mt-8 mx-2 p-5 bg-white/50 rounded-[2rem] border border-white shadow-sm">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Online Learner Information System (OLIS) Version 5.0</p>
            <p class="text-[11px] font-bold text-slate-600 tracking-wider italic">นายนนทชัย มาพิจารณ์</p>
        </div>
    </div>
</aside>