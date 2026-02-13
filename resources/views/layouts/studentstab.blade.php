@php
    use Illuminate\Support\Facades\Request;
    
    // รวมเมนูทั้งหมดไว้ที่เดียวเพื่อการจัดการที่ง่าย
    $menuItems = [
        ['url' => 'ประวัติการเรียน', 'label' => 'ประวัติการเรียน', 'icon' => 'M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z'],
        ['url' => 'การลงทะเบียน', 'label' => 'การลงทะเบียน', 'icon' => 'M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z'],
        ['url' => 'ตารางสอบ', 'label' => 'ตารางสอบ', 'icon' => 'M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z'],
        ['url' => 'กพช', 'label' => 'กิจกรรม กพช.', 'icon' => 'M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm5.495.93A.5.5 0 0 0 6.5 13c0 1.19.644 2.438 1.618 3.375C9.099 17.319 10.469 18 12 18c1.531 0 2.9-.681 3.882-1.625.974-.937 1.618-2.184 1.618-3.375a.5.5 0 0 0-.995-.07.764.764 0 0 1-.156.096c-.214.106-.554.208-1.006.295-.896.173-2.111.262-3.343.262-1.232 0-2.447-.09-3.343-.262-.452-.087-.792-.19-1.005-.295a.762.762 0 0 1-.157-.096ZM8.99 8a1 1 0 0 0 0 2H9a1 1 0 1 0 0-2h-.01Zm6 0a1 1 0 1 0 0 2H15a1 1 0 1 0 0-2h-.01Z'],
        ['url' => 'เรียนออนไลน์', 'label' => 'เรียนออนไลน์', 'icon' => 'M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z'],
        ['url' => 'olisai', 'label' => 'OLIS AI', 'icon' => 'M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z'],
    ];

    $mainMenus = array_slice($menuItems, 0, 3);
    $moreMenus = array_slice($menuItems, 3);
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen pt-24 transition-transform -translate-x-full bg-slate-50/50 backdrop-blur-xl border-r border-slate-100 sm:translate-x-0 hidden sm:block shadow-sm" aria-label="Sidebar">
    <div class="h-full px-4 pb-4 overflow-y-auto bg-transparent scrollbar-hide">
        <div class="mb-4 px-4">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">เมนูหลัก</p>
        </div>
        <ul class="space-y-2">
            @foreach($menuItems as $item)
                <li>
                    <a href="{{ url($item['url']) }}"
                       class="group flex items-center p-3 text-[13px] font-bold transition-all duration-200 rounded-2xl
                       {{ Request::is($item['url']) 
                            ? 'bg-white text-blue-600 shadow-md shadow-blue-100/50 translate-x-1' 
                            : 'text-slate-500 hover:bg-white/60 hover:text-blue-500 hover:translate-x-1' }}">
                        <div class="flex items-center justify-center w-8 h-8 rounded-xl {{ Request::is($item['url']) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-500' }}">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"/></svg>
                        </div>
                        <span class="ms-3 uppercase tracking-widest">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>

<nav class="fixed bottom-4 left-4 right-4 z-50 h-16 bg-white/80 backdrop-blur-lg border border-white/50 rounded-3xl shadow-2xl sm:hidden transition-all duration-300">
    <div class="grid h-full grid-cols-4 mx-auto font-medium">
        @foreach ($mainMenus as $item)
            <a href="{{ url($item['url']) }}" 
               class="inline-flex flex-col items-center justify-center group {{ Request::is($item['url']) ? 'text-blue-600' : 'text-slate-400' }}">
                <svg class="w-6 h-6 mb-1 transition-transform group-active:scale-75" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"/></svg>
                <span class="text-[10px] font-bold tracking-tighter">{{ $item['label'] }}</span>
                @if(Request::is($item['url']))
                    <div class="w-1 h-1 bg-blue-600 rounded-full mt-1"></div>
                @endif
            </a>
        @endforeach
        
        <button id="more-menu-toggle" type="button" class="inline-flex flex-col items-center justify-center text-slate-400 hover:text-blue-600">
            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM2 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 5a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM2 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm20-5a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM20 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM7 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM17 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-5 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm5 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-5 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm5 5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-5 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-tighter">เพิ่มเติม</span>
        </button>
    </div>
</nav>

<div id="more-menu-modal" class="fixed inset-0 z-[60] hidden transition-opacity duration-300">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div> <div class="fixed bottom-0 left-0 w-full bg-slate-50 rounded-t-[2.5rem] shadow-2xl p-6 transform transition-transform duration-300 translate-y-full" id="modal-content">
        <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-6"></div> <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">เมนูอื่นๆ</h3>
            <button id="close-menu-button" class="p-2 bg-slate-200 rounded-full text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="grid grid-cols-3 gap-4 pb-10">
            @foreach ($moreMenus as $item)
                <a href="{{ url($item['url']) }}" class="flex flex-col items-center p-4 bg-white rounded-3xl shadow-sm border border-slate-100 {{ Request::is($item['url']) ? 'text-blue-600 ring-2 ring-blue-100' : 'text-slate-500' }}">
                    <svg class="w-8 h-8 mb-2" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $item['icon'] }}"/></svg>
                    <span class="text-[10px] font-bold text-center leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('more-menu-toggle');
        const modal = document.getElementById('more-menu-modal');
        const content = document.getElementById('modal-content');
        const close = document.getElementById('close-menu-button');

        function showModal() {
            modal.classList.remove('hidden');
            setTimeout(() => content.classList.remove('translate-y-full'), 10);
        }

        function hideModal() {
            content.classList.add('translate-y-full');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        toggle.addEventListener('click', showModal);
        close.addEventListener('click', hideModal);
        modal.addEventListener('click', (e) => { if(e.target === modal.firstElementChild) hideModal(); });
    });
</script>