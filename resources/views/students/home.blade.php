<x-app-layout>
    <style>
        /* Force parent container to be full fluid width and remove standard top padding */
        main > div.max-w-7xl {
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
        }

        /* Pao Tang bright/deep royal purple gradient top header */
        .paotang-header-bg {
            background: linear-gradient(135deg, #7e22ce 0%, #4c1d95 100%);
        }

        /* Floating digital student wallet card styling (Vibrant purple theme) */
        .paotang-wallet-card {
            background: linear-gradient(135deg, #c084fc 0%, #7e22ce 100%);
            box-shadow: 0 15px 35px -5px rgba(126, 34, 206, 0.25), 0 10px 15px -10px rgba(126, 34, 206, 0.20);
        }

        /* Standard service circle icon shadow */
        .service-circle-shadow {
            box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.08);
        }

        /* Dark mode overrides for purple paotang style */
        .dark .paotang-header-bg {
            background: linear-gradient(135deg, #1e1b4b 0%, #311042 100%);
        }
        .dark .paotang-wallet-card {
            background: linear-gradient(135deg, #7e22ce 0%, #581c87 100%);
            box-shadow: 0 15px 35px -5px rgba(126, 34, 206, 0.2), 0 10px 15px -10px rgba(126, 34, 206, 0.15);
        }
    </style>

    <div class="animate-in fade-in duration-500 text-slate-800 dark:text-slate-100 font-sans pb-16">
        
        <!-- SECTION 1: Top Gradient Header (Pao Tang Header) -->
        <div class="paotang-header-bg text-white pt-8 pb-20 px-6 sm:px-12 rounded-b-[2.5rem] relative overflow-hidden">
            <!-- Decorative circle shapes -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute top-12 left-1/4 w-72 h-72 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
            
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <!-- User Profile Intro -->
                <div class="flex items-center space-x-3.5">
                    <div class="w-12 h-12 rounded-full border-2 border-white/80 overflow-hidden shadow-md">
                        <img src="{{ auth()->user()->avatar ? auth()->user()->avatar . '?v=' . time() : 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png' }}" 
                             onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'" 
                             alt="Avatar" 
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-white/60 block">สวัสดีตอนเช้า</span>
                        <h1 class="text-base font-black tracking-tight leading-none text-white">{{ auth()->user()->name }}</h1>
                    </div>
                </div>

                <!-- Right top quick actions (QR / Bell) -->
                <div class="flex items-center space-x-2">
                    <!-- Notification Button -->
                    @php
                        $unreadNotifCount = \App\Models\HelpNotification::where('user_id', Auth::id())->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('help.index') }}" class="relative p-2.5 bg-white/10 hover:bg-white/20 rounded-full transition-colors">
                        <svg class="w-5.5 h-5.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                        </svg>
                        @if($unreadNotifCount > 0)
                            <span class="absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[9px] font-black text-white ring-2 ring-white shadow-md">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Profile Settings Button -->
                    <a href="{{ route('profile.edit') }}" class="p-2.5 bg-white/10 hover:bg-white/20 rounded-full transition-colors" title="ตั้งค่าข้อมูล">
                        <svg class="w-5.5 h-5.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Floating General User Info Panel (แทนบัตรนักศึกษาเดิม) -->
        <div class="px-6 -mt-14 max-w-lg mx-auto">
            <div class="bg-white dark:bg-gray-800 border border-slate-200/80 dark:border-gray-750/80 rounded-[2.5rem] p-6 shadow-xl relative overflow-hidden transition-all duration-300">
                
                <!-- Section Header -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3 mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-wider">ข้อมูลผู้ใช้งานทั่วไป</h3>
                    </div>
                    <span class="px-2.5 py-0.5 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[9px] font-black rounded-lg uppercase tracking-wider">
                        บัญชีใช้งานปกติ
                    </span>
                </div>

                <!-- 3x2 Metadata Grid -->
                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <!-- 1. รหัสนักศึกษา -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">รหัสนักศึกษา</span>
                        <span class="text-xs font-mono font-black text-slate-700 dark:text-purple-300 leading-tight">
                            {{ $student->ID ?? auth()->user()->student_id }}
                        </span>
                    </div>

                    <!-- 2. ระดับการศึกษา -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">ระดับชั้น</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-200 leading-tight">
                            @if(str_split($student->ID ?? auth()->user()->student_id, 1)[3] == '1') ประถมศึกษา
                            @elseif(str_split($student->ID ?? auth()->user()->student_id, 1)[3] == '2') มัธยมศึกษาตอนต้น
                            @else มัธยมศึกษาตอนปลาย @endif
                        </span>
                    </div>

                    <!-- 3. กลุ่มเรียน -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">กลุ่มเรียน</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-200 leading-tight">
                            {{ $student->GRP_CODE ?? 'ไม่ระบุกลุ่ม' }}
                        </span>
                    </div>

                    <!-- 4. สถานะภาพ -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">สถานะบัญชี</span>
                        <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-black text-xs leading-tight">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                            ศึกษาอยู่
                        </span>
                    </div>

                    <!-- 5. เกรดเฉลี่ยสะสม -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">เกรดเฉลี่ย (GPA)</span>
                        <span class="text-xs font-black text-purple-700 dark:text-purple-400 font-mono leading-tight">
                            {{ number_format($grade_avg, 2) }}
                        </span>
                    </div>

                    <!-- 6. กพช. -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 block">กิจกรรม กพช.</span>
                        <span class="text-xs font-black text-slate-700 dark:text-slate-200 font-mono leading-tight">
                            {{ $act_sum }} / 200 ชม.
                        </span>
                    </div>
                </div>

                <!-- Action buttons footer -->
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <!-- Action 1: เข้าเรียนออนไลน์ -->
                    <a href="{{ route('เรียนออนไลน์') }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 transition-all text-white rounded-2xl text-[10px] font-black shadow-md border border-purple-650/40">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 13c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                        </svg>
                        เข้าเรียนออนไลน์
                    </a>

                    <!-- Action 2: ตารางเรียน/ตารางสอบ -->
                    <a href="{{ route('ตารางสอบ') }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700 dark:hover:bg-slate-650/80 transition-all text-slate-700 dark:text-slate-200 rounded-2xl text-[10px] font-black border border-slate-200 dark:border-slate-600 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                        </svg>
                        เช็คตารางสอบ
                    </a>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Popular Services (บริการยอดนิยม) - Circular Badges -->
        <div class="px-6 py-8 max-w-4xl mx-auto space-y-4">
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest block">บริการยอดนิยม</h4>
            <div class="grid grid-cols-4 gap-4 text-center">
                <!-- 1. คลิปสั้น OLIS -->
                <a href="{{ route('shorts.index') }}" class="group flex flex-col items-center gap-2">
                    <div class="w-13 h-13 rounded-full bg-red-50 text-red-500 dark:bg-red-950/20 dark:text-red-400 border border-red-100 dark:border-red-900/30 flex items-center justify-center service-circle-shadow group-hover:scale-105 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">คลิปสั้น OLIS</span>
                </a>

                <!-- 2. เรียนออนไลน์ -->
                <a href="{{ route('เรียนออนไลน์') }}" class="group flex flex-col items-center gap-2">
                    <div class="w-13 h-13 rounded-full bg-purple-50 text-purple-500 dark:bg-purple-950/20 dark:text-purple-400 border border-purple-100 dark:border-purple-900/30 flex items-center justify-center service-circle-shadow group-hover:scale-105 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">เรียนออนไลน์</span>
                </a>

                <!-- 3. ตารางสอบ -->
                <a href="{{ route('ตารางสอบ') }}" class="group flex flex-col items-center gap-2">
                    <div class="w-13 h-13 rounded-full bg-amber-50 text-amber-500 dark:bg-amber-950/20 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30 flex items-center justify-center service-circle-shadow group-hover:scale-105 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">ตารางสอบ</span>
                </a>

                <!-- 4. ทดสอบออนไลน์ -->
                <a href="{{ route('ทดสอบออนไลน์') }}" class="group flex flex-col items-center gap-2">
                    <div class="w-13 h-13 rounded-full bg-emerald-50 text-emerald-500 dark:bg-emerald-950/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 flex items-center justify-center service-circle-shadow group-hover:scale-105 transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7V7h10v2zm0 8H7v-2h10v2z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 dark:text-slate-300">ทดสอบออนไลน์</span>
                </a>
            </div>
        </div>

        <!-- SECTION 4: News & Announcements Banner Slider (ประชาสัมพันธ์) -->
        @if($courses->isNotEmpty())
            <div class="px-6 py-2 max-w-4xl mx-auto space-y-4">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest block">หลักสูตรออนไลน์ใหม่น่าสนใจ</h4>
                <div class="flex overflow-x-scroll gap-4 pb-4 scrollbar-none snap-x snap-mandatory">
                    @foreach($courses as $course)
                        <a href="{{ route('classroom.show', $course->id) }}" class="flex-shrink-0 w-80 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700/80 rounded-2xl p-4.5 shadow-sm snap-start flex items-start space-x-4">
                            <div class="w-16 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-black text-xl shadow-inner">
                                📚
                            </div>
                            <div class="space-y-1.5 flex-1 min-w-0">
                                <span class="inline-flex px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[8px] font-extrabold rounded-md uppercase tracking-wider">
                                    {{ $course->subject_code ?? 'ONLINE' }}
                                </span>
                                <h5 class="text-xs font-black text-slate-900 dark:text-white truncate">{{ $course->title }}</h5>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    สอนโดย {{ $course->teacher->name ?? 'ไม่ระบุชื่อผู้สอน' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- SECTION 5: All Services List Categories (บริการในแอปอื่นๆ) -->
        <div class="px-6 py-6 max-w-4xl mx-auto space-y-8">
            
            <!-- Category 1: บริการด้านวิชาการ (Academic Services) -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-150 dark:border-slate-800 pb-2">
                    <span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span>
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">บริการวิชาการและการเรียน</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- 1. ประวัติการเรียน -->
                    <a href="{{ route('ประวัติการเรียน') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.5 2c-.178 0-.356.013-.492.022l-.074.005a1 1 0 0 0-.934.998V11a1 1 0 0 0 1 1h7.975a1 1 0 0 0 .998-.934l.005-.074A7.04 7.04 0 0 0 22 10.5 8.5 8.5 0 0 0 13.5 2Z M11 6.025a1 1 0 0 0-1.065-.998 8.5 8.5 0 1 0 9.038 9.039A1 1 0 0 0 17.975 13H11V6.025Z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">ประวัติการเรียน</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Academic Portfolio</span>
                        </div>
                    </a>

                    <!-- 2. กิจกรรม กพช. -->
                    <a href="{{ route('กพช') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v3c0 2.44 1.72 4.48 4 4.81V18h-3v2h16v-2h-3v-3.19c2.28-.33 4-2.37 4-4.81V7c0-1.1-.9-2-2-2zM5 10V7h2v3H5zm14 0h-2V7h2v3z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">บันทึก กพช.</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Activity Hours</span>
                        </div>
                    </a>

                    <!-- 3. การลงทะเบียน -->
                    <a href="{{ route('การลงทะเบียน') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">การลงทะเบียน</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Registrations</span>
                        </div>
                    </a>

                    <!-- 4. เกณฑ์การจบหลักสูตร -->
                    <a href="{{ route('ประวัติการเรียน') }}#graduation-criteria" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 13c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">เกณฑ์การจบหลักสูตร</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Graduation Criteria</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Category 2: บริการสื่อสารและสื่อออนไลน์ (Communication & Support) -->
            <div class="space-y-4">
                <div class="flex items-center space-x-2 border-b border-slate-150 dark:border-slate-800 pb-2">
                    <span class="w-2.5 h-2.5 bg-purple-600 rounded-full"></span>
                    <h3 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">บริการแชทและติดต่อคำร้อง</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <!-- 1. คำร้องออนไลน์ -->
                    <a href="{{ route('คำร้องออนไลน์') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">ส่งคำร้องออนไลน์</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Online Petitions</span>
                        </div>
                    </a>

                    <!-- 2. รับแจ้งปัญหา -->
                    <a href="{{ route('help.index') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group relative">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
                            </svg>
                        </span>
                        @if($unreadNotifCount > 0)
                            <span class="absolute top-2.5 right-2.5 inline-flex items-center justify-center w-5 h-5 text-[9px] font-black text-white bg-red-500 rounded-full border border-white dark:border-slate-800 shadow-sm animate-bounce">
                                {{ $unreadNotifCount }}
                            </span>
                        @endif
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">รับแจ้งปัญหา/แชท</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Student Helpdesk</span>
                        </div>
                    </a>

                    <!-- 3. สื่อการเรียนรู้ -->
                    <a href="{{ route('สื่อการเรียนรู้') }}" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H8V4h12v12z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">สื่อ/ตำราอิเล็กทรอนิกส์</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">Digital Libraries</span>
                        </div>
                    </a>

                    <!-- 4. OLIS AI Chat -->
                    <a href="javascript:void(0)" onclick="alert('ฟีเจอร์ OLIS AI ยังไม่เปิดใช้งานในขณะนี้ ขออภัยในความไม่สะดวกครับ')" class="flex items-center p-3.5 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all space-x-3 group">
                        <span class="p-2 bg-purple-50 dark:bg-purple-950/30 rounded-xl shrink-0 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 8h-2v3h2V8zm-2 5H7v2h10v-2zm-6-5h2v3h-2V8zm8.3-2.7l-1.9-1.9c-.4-.4-1-.4-1.4 0L14.3 5c-.7-.3-1.4-.5-2.3-.5s-1.6.2-2.3.5L8 3.4c-.4-.4-1-.4-1.4 0L4.7 5.3c-.4.4-.4 1 0 1.4L6.1 8c-.6.9-1 2-1 3.2V19c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2v-7.8c0-1.2-.4-2.3-1-3.2l1.4-1.3c.4-.4.4-1 0-1.4zM15 17H9v-2h6v2zm0-4H9v-2h6v2z"/>
                            </svg>
                        </span>
                        <div>
                            <span class="text-[11px] font-black text-slate-800 dark:text-white block leading-tight">OLIS AI</span>
                            <span class="text-[8px] text-slate-400 font-bold block uppercase tracking-wider">AI Teaching Assistant</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
