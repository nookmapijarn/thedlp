<x-app-layout>
    <style>
        /* Force parent container to be full fluid width */
        main > div.max-w-7xl {
            max-width: 100% !important;
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        .theme-card-shadow {
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.04), 0 8px 10px -6px rgba(99, 102, 241, 0.04);
        }
        
        .glass-gradient {
            background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.08) 100%);
            backdrop-filter: blur(10px);
        }
    </style>
    
    <div class="px-4 py-6 sm:px-6 lg:px-8 space-y-8 animate-in fade-in duration-500 text-slate-800 dark:text-slate-100 font-sans">
        
        <!-- 1. Full-Width Premium Welcome Hero (Light Soft Gradient with High-Contrast Dark Fonts) -->
        <div class="bg-gradient-to-r from-indigo-50/70 via-slate-50 to-purple-50/70 dark:from-slate-900/90 dark:via-indigo-950/80 dark:to-purple-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-12 shadow-sm border border-slate-200/80 dark:border-gray-700/60 relative overflow-hidden flex flex-col justify-between min-h-[280px] theme-card-shadow">
            <div class="absolute inset-0 bg-cover bg-center opacity-5 pointer-events-none" style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80&w=1200');"></div>
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 blur-[120px] rounded-full -mr-32 -mt-32 pointer-events-none"></div>
            <div class="absolute bottom-0 left-1/3 w-96 h-96 bg-purple-500/5 blur-[100px] rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                <!-- Welcome text -->
                <div class="space-y-4">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-300 text-xs font-black tracking-widest uppercase border border-indigo-200/60 dark:border-indigo-800/40 shadow-sm">
                        Student Portal Dashboard
                    </span>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white">
                        ยินดีต้อนรับเข้าสู่ระบบการเรียนรู้, <br class="hidden sm:inline">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-650 to-purple-600 dark:from-indigo-300 dark:to-purple-300 font-black">{{ auth()->user()->name }}</span>
                    </h1>
                    <p class="text-sm sm:text-base text-slate-650 dark:text-slate-300 leading-relaxed max-w-2xl font-semibold">
                        ยินดีต้อนรับกลับเข้าสู่ห้องเรียนอัจฉริยะ คุณสามารถเข้าศึกษาบทเรียนออนไลน์ ดาวน์โหลดสื่อสารเรียนรู้อิเล็กทรอนิกส์ หรือเริ่มต้นทำแบบทดสอบเพื่อสะสมชั่วโมงและประเมินระดับคะแนนของคุณ
                    </p>
                </div>
                
                <!-- Student Avatar Upload Block -->
                <div class="flex flex-col items-center space-y-3 shrink-0 self-center md:self-auto">
                    <a href="{{ route('profile.edit') }}" class="group relative block">
                        <div class="relative w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden ring-4 ring-indigo-500/20 dark:ring-indigo-400/20 group-hover:ring-indigo-500/60 group-hover:scale-105 transition-all duration-300 shadow-md">
                            <img src="{{ auth()->user()->avatar ? auth()->user()->avatar . '?v=' . time() : 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png' }}" 
                                 onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'" 
                                 alt="Avatar" 
                                 class="w-full h-full object-cover">
                            <!-- Hover Overlay -->
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Camera badge on bottom-right -->
                        <span class="absolute bottom-1 right-1 bg-indigo-600 dark:bg-indigo-500 text-white p-1.5 rounded-full shadow-lg border-2 border-white dark:border-slate-900 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="text-[11px] font-black text-indigo-650 dark:text-indigo-300 hover:underline">
                        อัปโหลดรูปโปรไฟล์
                    </a>
                </div>
            </div>
            
            <!-- Horizontal Quick Info chips at footer of banner (High-contrast Dark Slate Fonts) -->
            <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-6 pt-8 mt-10 border-t border-slate-200/80 dark:border-gray-700/60 text-xs sm:text-sm">
                <div class="space-y-1">
                    <span class="text-slate-500 dark:text-slate-400 font-extrabold block uppercase tracking-wider text-[11px]">กลุ่มเรียน</span>
                    <span class="font-extrabold text-slate-800 dark:text-white text-base sm:text-lg leading-tight">{{ $student->GRP_CODE ?? 'ไม่ระบุ' }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-500 dark:text-slate-400 font-extrabold block uppercase tracking-wider text-[11px]">ระดับชั้นการศึกษา</span>
                    <span class="font-extrabold text-slate-800 dark:text-white text-base sm:text-lg leading-tight">
                        @if(str_split(auth()->user()->student_id, 1)[3] == '1') ประถมศึกษา
                        @elseif(str_split(auth()->user()->student_id, 1)[3] == '2') มัธยมศึกษาตอนต้น
                        @else มัธยมศึกษาตอนปลาย @endif
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-500 dark:text-slate-400 font-extrabold block uppercase tracking-wider text-[11px]">รหัสนักศึกษา</span>
                    <span class="font-mono font-black text-indigo-650 dark:text-indigo-300 text-base sm:text-lg leading-tight">{{ $student->ID ?? auth()->user()->student_id }}</span>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-500 dark:text-slate-400 font-extrabold block uppercase tracking-wider text-[11px]">สถานะบัญชี</span>
                    <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400 font-black text-base leading-tight">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2 border border-slate-200 dark:border-slate-900 animate-pulse"></span>
                        ยืนยันสิทธิ์สำเร็จ
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Academic Metrics Progress Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Widget 1: GPA -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-slate-200/80 dark:border-gray-700/60 shadow-sm theme-card-shadow flex items-center justify-between hover:border-indigo-500/50 transition-colors font-sans">
                <div class="space-y-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider block font-sans">เกรดเฉลี่ยสะสม (GPA)</span>
                    <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400">{{ number_format($grade_avg, 2) }}</span>
                </div>
                <div class="p-3.5 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                </div>
            </div>

            <!-- Widget 2: Credits -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-slate-200/80 dark:border-gray-700/60 shadow-sm theme-card-shadow space-y-4 hover:border-indigo-500/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider block">หน่วยกิตสะสม</span>
                        <span class="text-xl font-black text-slate-900 dark:text-white">{{ $credit }} / {{ $allcredit }} นก.</span>
                    </div>
                    <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/40 px-2.5 py-1 rounded-xl">{{ $credit_percent }}%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-200/80 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $credit_percent }}%"></div>
                </div>
            </div>

            <!-- Widget 3: GPCH -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-slate-200/80 dark:border-gray-700/60 shadow-sm theme-card-shadow space-y-4 hover:border-purple-500/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider block">กิจกรรม กพช.</span>
                        <span class="text-xl font-black text-slate-900 dark:text-white">{{ $act_sum }} / 200 ชม.</span>
                    </div>
                    <span class="text-xs font-bold text-purple-700 dark:text-purple-300 bg-purple-100 dark:bg-purple-900/40 px-2.5 py-1 rounded-xl">{{ $act_percentage }}%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-200/80 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-purple-600 rounded-full" style="width: {{ $act_percentage }}%"></div>
                </div>
            </div>

            <!-- Widget 4: Registration status -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-slate-200/80 dark:border-gray-700/60 shadow-sm theme-card-shadow flex flex-col justify-between hover:border-green-500/50 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider block">สถานะภาคเรียนล่าสุด</span>
                    <span class="px-2.5 py-0.5 bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-300 text-[10px] font-extrabold rounded-lg">ลงทะเบียนแล้ว</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-sm font-extrabold text-slate-800 dark:text-slate-200 font-mono">
                        @if($semestrylist1->isNotEmpty())
                            ภาคเรียนที่ {{ $semestrylist1->last()->SEMESTRY }}
                        @else
                            -
                        @endif
                    </span>
                    <a href="{{ route('การลงทะเบียน') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 font-extrabold flex items-center">
                        รายละเอียด
                        <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                    </a>
                </div>
            </div>

        </div>

        <!-- 3. Quick Access Actions Panel -->
        <div class="bg-white dark:bg-gray-800 rounded-[2rem] p-6 border border-slate-200/80 dark:border-gray-700/60 shadow-sm flex flex-col lg:flex-row justify-between items-center gap-6 theme-card-shadow">
            <div class="space-y-1 text-center lg:text-left">
                <h4 class="text-base font-extrabold text-slate-900 dark:text-white">เมนูลัดสู่หน้าบริการและรายงานคำร้อง</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">เข้าถึงตารางสอบ กิจกรรม กพช. สมุดประวัติการเรียน และเอกสารทะเบียนออนไลน์ทันที</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('ประวัติการเรียน') }}" class="px-4.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 rounded-2xl text-xs font-bold transition-all text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    📄 ประวัติการเรียน
                </a>
                <a href="{{ route('ตารางสอบ') }}" class="px-4.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 rounded-2xl text-xs font-bold transition-all text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    📅 ตารางสอบ
                </a>
                <a href="{{ route('กพช') }}" class="px-4.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 rounded-2xl text-xs font-bold transition-all text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    🏆 บันทึก กพช.
                </a>
                <a href="{{ route('คำร้องออนไลน์') }}" class="px-4.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 rounded-2xl text-xs font-bold transition-all text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    ✉️ ส่งคำร้องออนไลน์
                </a>
                <a href="{{ route('help.index') }}" class="px-4.5 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700/80 rounded-2xl text-xs font-bold transition-all text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400">
                    💬 ศูนย์รับแจ้งปัญหา
                </a>
            </div>
        </div>

        <!-- 4. Recommended Courses Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center">
                    <span class="w-3.5 h-3.5 bg-indigo-600 rounded-full mr-2.5 shadow-sm shadow-indigo-500"></span>
                    หลักสูตรออนไลน์แนะนำ (Courses)
                </h2>
                <a href="{{ route('เรียนออนไลน์') }}" class="text-xs font-extrabold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center transition-colors">
                    ดูหลักสูตรทั้งหมด
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </a>
            </div>
            
            @if($courses->isEmpty())
            <div class="bg-white dark:bg-gray-800 p-10 rounded-3xl border border-slate-200/80 dark:border-gray-700/60 shadow-sm text-center">
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">ยังไม่มีหลักสูตรออนไลน์เปิดสอนในขณะนี้</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-slate-200/80 dark:border-gray-700/60 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-350 flex flex-col group theme-card-shadow">
                    
                    <!-- Cover Image -->
                    <div class="relative h-48 bg-slate-100 dark:bg-slate-900 overflow-hidden">
                        @if($course->cover_image)
                            <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-indigo-50/50 text-indigo-400 dark:bg-indigo-900/10">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 flex-grow flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                {{ $course->title }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed font-medium">
                                {{ $course->description }}
                            </p>
                        </div>
                        
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->teacher?->name ?? 'T') }}&color=7F9CF5&background=EBF4FF" alt="{{ $course->teacher?->name ?? 'Teacher' }}" class="w-6 h-6 rounded-full">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $course->teacher?->name ?? 'ผู้สอน' }}</span>
                            </div>
                            <a href="{{ route('classroom.show', $course->id) }}" class="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform flex items-center">
                                เข้าบทเรียน
                                <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                            </a>
                        </div>
                    </div>
                    
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- 5. Recommended Quizzes Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center">
                    <span class="w-3.5 h-3.5 bg-purple-600 rounded-full mr-2.5 shadow-sm shadow-purple-500"></span>
                    แบบทดสอบออนไลน์แนะนำ (Quizzes)
                </h2>
                <a href="{{ route('ทดสอบออนไลน์') }}" class="text-xs font-extrabold text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 flex items-center transition-colors">
                    ดูแบบทดสอบทั้งหมด
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Display Assigned Quizzes First -->
                @foreach($assignedQuizzes as $assigned)
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-slate-200/80 dark:border-gray-700/60 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-350 flex flex-col justify-between group theme-card-shadow">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="p-3 bg-purple-50 text-purple-600 rounded-2xl dark:bg-purple-900/30 dark:text-purple-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002-2.25V5.25A2.25 2.25 0 0018 3H6a2.25 2.25 0 00-2 2.25v13.5A2.25 2.25 0 006 21h3"></path></svg>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300">
                                มอบหมายพิเศษ
                            </span>
                        </div>
                        
                        <div class="space-y-2">
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white line-clamp-2 min-h-[3rem] leading-snug">
                                {{ $assigned->title }}
                            </h3>
                            <div class="flex flex-wrap gap-2 text-[10px] text-slate-600 dark:text-slate-400 font-bold">
                                <span class="bg-slate-100 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">เกณฑ์ผ่าน: {{ $assigned->pass_percentage }}%</span>
                                <span class="bg-slate-100 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">เวลา: {{ $assigned->time_limit }} นาที</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                        @if($assigned->is_completed)
                            <span class="text-sm font-bold text-green-600 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                ทำสำเร็จแล้ว
                            </span>
                        @else
                            <a href="{{ route('quizzes.start', $assigned->id) }}" class="w-full text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 py-2.5 rounded-xl transition-all shadow-sm hover:shadow">
                                เริ่มทำแบบทดสอบ
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <!-- Display General Level-specific Quizzes -->
                @foreach($quizzes as $quiz)
                <!-- Skip duplicate if already shown as assigned -->
                @if(!$assignedQuizzes->contains('id', $quiz->id))
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-slate-200/80 dark:border-gray-700/60 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-350 flex flex-col justify-between group theme-card-shadow">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl dark:bg-blue-900/30 dark:text-blue-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002-2.25V5.25A2.25 2.25 0 0018 3H6a2.25 2.25 0 00-2 2.25v13.5A2.25 2.25 0 006 21h3"></path></svg>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                ทั่วไป
                            </span>
                        </div>
                        
                        <div class="space-y-2">
                            <h3 class="text-base font-extrabold text-slate-900 dark:text-white line-clamp-2 min-h-[3rem] leading-snug">
                                {{ $quiz->title }}
                            </h3>
                            <div class="flex flex-wrap gap-2 text-[10px] text-slate-600 dark:text-slate-400 font-bold">
                                <span class="bg-slate-100 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">เกณฑ์ผ่าน: {{ $quiz->pass_percentage }}%</span>
                                <span class="bg-slate-100 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">เวลา: {{ $quiz->time_limit }} นาที</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                        @if($quiz->is_attempted > 0)
                            <span class="text-sm font-bold text-green-600 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                ทดสอบแล้ว
                            </span>
                        @else
                            <a href="{{ route('quizzes.start', $quiz->id) }}" class="w-full text-center text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 py-2.5 rounded-xl transition-all shadow-sm hover:shadow">
                                เริ่มทำแบบทดสอบ
                            </a>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
                
                @if($assignedQuizzes->isEmpty() && $quizzes->isEmpty())
                <div class="col-span-full bg-white dark:bg-gray-800 p-10 rounded-3xl border border-slate-200/80 dark:border-gray-700/60 shadow-sm text-center">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">ยังไม่มีแบบทดสอบในขณะนี้</p>
                </div>
                @endif
                
            </div>
        </div>

        <!-- 6. Library Bookcase Quick Access -->
        <div class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center">
                    <span class="w-3.5 h-3.5 bg-green-600 rounded-full mr-2.5 shadow-sm shadow-green-500"></span>
                    ห้องสมุดหนังสืออิเล็กทรอนิกส์ (E-Book)
                </h2>
                <a href="{{ route('สื่อการเรียนรู้') }}" class="text-xs font-extrabold text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 flex items-center transition-colors">
                    เปิดห้องสมุดทั้งหมด
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"></path></svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Box 1 -->
                <a href="{{ route('สื่อการเรียนรู้') }}" class="group relative overflow-hidden rounded-[2rem] border border-slate-200/80 dark:border-gray-700/60 shadow-md hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800 p-6 flex items-center space-x-6 theme-card-shadow">
                    <div class="flex-shrink-0 w-24 h-32 rounded-xl bg-gradient-to-br from-yellow-400 to-amber-500 shadow-md flex items-center justify-center text-white overflow-hidden relative group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=150');"></div>
                        <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                    <div class="space-y-2">
                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-950 dark:text-yellow-300">
                            Bookcase I
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white group-hover:text-amber-600 transition-colors">
                            ห้องสมุดตำราเรียนวิทยาศาสตร์และทั่วไป
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                            ชั้นหนังสืออิเล็กทรอนิกส์ รวบรวมตำราเรียนหลักวิชาวิทยาศาสตร์ ภาษาไทย และคู่มือแนะแนวการศึกษา
                        </p>
                    </div>
                </a>

                <!-- Box 2 -->
                <a href="{{ route('สื่อการเรียนรู้') }}" class="group relative overflow-hidden rounded-[2rem] border border-slate-200/80 dark:border-gray-700/60 shadow-md hover:shadow-2xl transition-all duration-300 bg-white dark:bg-gray-800 p-6 flex items-center space-x-6 theme-card-shadow">
                    <div class="flex-shrink-0 w-24 h-32 rounded-xl bg-gradient-to-br from-green-400 to-emerald-500 shadow-md flex items-center justify-center text-white overflow-hidden relative group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=150');"></div>
                        <svg class="w-10 h-10 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path></svg>
                    </div>
                    <div class="space-y-2">
                        <span class="inline-flex px-2 py-0.5 text-[9px] font-bold rounded bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300">
                            Bookcase II
                        </span>
                        <h3 class="text-base font-extrabold text-slate-900 dark:text-white group-hover:text-green-600 transition-colors">
                            ห้องสมุดสังคมศึกษาและภาษาอังกฤษ
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">
                            ชั้นหนังสือตำราสังคม วัฒนธรรม โครงงานคุณธรรม ภาษาต่างประเทศ และคู่มือสืบค้นประกอบเพิ่มเติม
                        </p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
