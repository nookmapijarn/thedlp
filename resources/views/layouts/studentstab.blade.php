@php
    use Illuminate\Support\Facades\Request;
    $menuItems = [
        ['url' => 'ประวัติการเรียน', 'label' => 'ประวัติการเรียน', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z"/><path d="M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z"/></svg>'],
        ['url' => 'การลงทะเบียน', 'label' => 'การลงทะเบียน', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd"/></svg>'],
        ['url' => 'ตารางสอบ', 'label' => 'ตารางสอบ', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/></svg>'],
        ['url' => 'กพช', 'label' => 'กิจกรรม กพช.', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm5.495.93A.5.5 0 0 0 6.5 13c0 1.19.644 2.438 1.618 3.375C9.099 17.319 10.469 18 12 18c1.531 0 2.9-.681 3.882-1.625.974-.937 1.618-2.184 1.618-3.375a.5.5 0 0 0-.995-.07.764.764 0 0 1-.156.096c-.214.106-.554.208-1.006.295-.896.173-2.111.262-3.343.262-1.232 0-2.447-.09-3.343-.262-.452-.087-.792-.19-1.005-.295a.762.762 0 0 1-.157-.096ZM8.99 8a1 1 0 0 0 0 2H9a1 1 0 1 0 0-2h-.01Zm6 0a1 1 0 1 0 0 2H15a1 1 0 1 0 0-2h-.01Z" clip-rule="evenodd"/></svg>'],
        ['url' => 'เรียนออนไลน์', 'label' => 'เรียนออนไลน์', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/></svg>'],
        ['url' => 'olisai', 'label' => 'OLIS AI', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0-.502.203-.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z" clip-rule="evenodd"/></svg>'],
        ['url' => 'สื่อการเรียนรู้', 'label' => 'สื่อการเรียนรู้', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M11 4.717c-2.286-.58-4.16-.756-7.045-.71A1.99 1.99 0 0 0 2 6v11c0 1.133.934 2.022 2.044 2.007 2.759-.038 4.5.16 6.956.791V4.717Zm2 15.081c2.456-.631 4.198-.829 6.956-.791A2.013 2.013 0 0 0 22 16.999V6a1.99 1.99 0 0 0-1.955-1.993c-2.885-.046-4.76.13-7.045.71v15.081Z" clip-rule="evenodd"/></svg>'],
        ['url' => 'ทดสอบออนไลน์', 'label' => 'ทดสอบออนไลน์', 'icon' => '<svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/></svg>'],
    ];

    $mainMenus = array_slice($menuItems, 0, 3);
    $moreMenus = array_slice($menuItems, 3);
@endphp

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700 hidden sm:block" aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
            @foreach($menuItems as $item)
                <li>
                    <a href="{{ url($item['url']) }}"
                       class="{{ Request::is($item['url']) ? 'bg-blue-100 text-blue-800 dark:bg-gray-700' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-50 hover:text-blue-800 dark:hover:bg-gray-700 group">
                        {!! $item['icon'] !!}
                        <span class="ms-3">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</aside>
---
<nav class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:hidden">
    <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
        {{-- เมนูหลัก 3 รายการ --}}
        @foreach ($mainMenus as $item)
            <a href="{{ url($item['url']) }}" class="inline-flex flex-col items-center justify-center px-5 group {{ Request::is($item['url']) ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400' }}">
                {!! $item['icon'] !!}
                <span class="text-xs">{{ $item['label'] }}</span>
            </a>
        @endforeach
        
        {{-- ปุ่ม More/เพิ่มเติม --}}
        <button id="more-menu-toggle" type="button" class="inline-flex flex-col items-center justify-center px-5 text-gray-500 dark:text-gray-400 group hover:text-blue-600 dark:hover:text-blue-500">
            <svg class="w-6 h-6 mb-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM2 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm10 5a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM2 17a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm20-5a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM20 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4ZM7 12a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM17 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-5 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm5 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm-5 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm5 5a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-5 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/>
            </svg>
            <span class="text-xs">เพิ่มเติม</span>
        </button>
    </div>
</nav>

<div id="more-menu-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 sm:hidden">
    <div class="fixed bottom-0 left-0 w-full bg-white dark:bg-gray-800 rounded-t-lg shadow-lg">
        <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">เพิ่มเติม</h3>
            <button id="close-menu-button" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close menu</span>
            </button>
        </div>
        <ul class="p-4 space-y-2 font-medium">
            @foreach ($moreMenus as $item)
                <li>
                    <a href="{{ url($item['url']) }}" class="{{ Request::is($item['url']) ? 'bg-blue-100 text-blue-800 dark:bg-gray-700' : '' }} flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-blue-50 hover:text-blue-800 dark:hover:bg-gray-700 group">
                        {!! $item['icon'] !!}
                        <span class="ms-3">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const moreMenuToggle = document.getElementById('more-menu-toggle');
        const moreMenuModal = document.getElementById('more-menu-modal');
        const closeMenuButton = document.getElementById('close-menu-button');

        moreMenuToggle.addEventListener('click', function() {
            moreMenuModal.classList.toggle('hidden');
        });

        closeMenuButton.addEventListener('click', function() {
            moreMenuModal.classList.add('hidden');
        });

        // Optional: Close modal when clicking outside
        moreMenuModal.addEventListener('click', function(event) {
            if (event.target === moreMenuModal) {
                moreMenuModal.classList.add('hidden');
            }
        });
    });
</script>