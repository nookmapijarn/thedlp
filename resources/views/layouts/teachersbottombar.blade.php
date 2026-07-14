<div x-data="{ openReports: false, openCourses: false }" class="sm:hidden">
    <!-- Bottom Tab Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-slate-200/80 dark:border-slate-800/80 shadow-[0_-4px_24px_rgba(0,0,0,0.06)] pb-safe no-print">
        <div class="grid grid-cols-5 h-16 max-w-md mx-auto items-center justify-center">
            
            <!-- Tab 1: หน้าหลัก (Dashboard) -->
            <a href="{{ url('teachers/') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ (Request::is('teachers') || Request::is('teachers/tdashboard')) ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                <svg class="w-6 h-6 mb-0.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="text-[9px] font-black tracking-wider leading-none">Dashboard (ภาพรวม)</span>
            </a>

            <!-- Tab 2: รายงาน -->
            <button @click="openReports = true; openCourses = false" type="button" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ (Request::is('teachers/treport') || Request::is('teachers/tgrade') || Request::is('teachers/tscore')) ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7h1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h11.5M7 14h6m-6 3h6m0-10h.5m-.5 3h.5M7 7h3v3H7V7Z"/>
                </svg>
                <span class="text-[9px] font-black tracking-wider leading-none">รายงาน</span>
            </button>

            <!-- Tab 3: จัดการคลิปสั้น -->
            <a href="{{ route('teachers.shorts.index') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('teachers/shorts*') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                <div class="relative -top-4 w-12 h-12 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 flex items-center justify-center shadow-lg text-white transform active:scale-95 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <span class="text-[9px] font-black tracking-wider leading-none -mt-3.5">จัดการคลิปสั้น</span>
            </a>

            <!-- Tab 4: จัดการหลักสูตร -->
            <button @click="openCourses = true; openReports = false" type="button" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ (Request::is('teachers/courses*') || Request::is('teachers/ttest*')) ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <span class="text-[9px] font-black tracking-wider leading-none">จัดการหลักสูตร</span>
            </button>

            <!-- Tab 5: ศูนย์รับแจ้งปัญหา -->
            <a href="{{ route('teachers.help.index') }}" class="flex flex-col items-center justify-center gap-1 text-center transition-all duration-200 {{ Request::is('teachers/help-requests*') ? 'text-purple-700 dark:text-purple-400 font-bold scale-105' : 'text-slate-400 dark:text-slate-500 hover:text-slate-650' }}">
                <div class="relative">
                    <svg class="w-6 h-6 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A7 7 0 1110.5 3.5c1.1 0 2.12.253 3.03.7M14.828 9.172A4 4 0 1112 8.244m2.828.928a4 4 0 01-2.828-.928"/>
                    </svg>
                    @php
                        $teacherPendingCount = \App\Models\HelpRequest::where('status', 'pending')->count();
                    @endphp
                    @if($teacherPendingCount > 0)
                        <span class="absolute -top-1 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[9px] font-black text-white ring-2 ring-white">
                            {{ $teacherPendingCount }}
                        </span>
                    @endif
                </div>
                <span class="text-[9px] font-black tracking-wider leading-none">ศูนย์รับแจ้งปัญหา</span>
            </a>

        </div>
    </div>

    <!-- Reports Bottom Sheet -->
    <div x-show="openReports" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.outside="openReports = false" 
             x-show="openReports"
             x-transition:enter="transition ease-out duration-350"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 rounded-t-[2.5rem] p-6 shadow-2xl space-y-4 pb-10 max-w-md mx-auto">
            
            <!-- Drag Indicator -->
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mb-2"></div>
            
            <div class="text-center space-y-1">
                <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">เมนูย่อย: รายงาน</h4>
                <p class="text-xs text-slate-400 font-bold">เลือกประเภทรายงานที่คุณต้องการเข้าถึง</p>
            </div>

            <div class="grid grid-cols-1 gap-2.5 pt-2">
                <a href="{{ url('teachers/tgrade') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold">🎓</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">ผลการเรียน</span>
                        <span class="text-[10px] text-slate-400 font-bold">จัดการแก้ไข บันทึก เกรด/ผลการเรียนรายคน</span>
                    </div>
                </a>
                <a href="{{ url('teachers/treport') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center font-bold">📄</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">รายงานผู้เรียน กศน.4</span>
                        <span class="text-[10px] text-slate-400 font-bold">ดึงรายงานรายตำบล สรุปผลสอบ และพิมพ์บัตรนักศึกษา</span>
                    </div>
                </a>
                <a href="{{ url('teachers/tscore') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold">📝</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">บันทึกคะแนน กศน.4</span>
                        <span class="text-[10px] text-slate-400 font-bold">บันทึกผลคะแนนเฉลี่ยการศึกษา กศน.4</span>
                    </div>
                </a>
            </div>

            <button @click="openReports = false" class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-650 dark:text-slate-300 rounded-2xl text-xs font-black uppercase tracking-widest active:scale-95 transition-all">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>

    <!-- Courses Bottom Sheet -->
    <div x-show="openCourses" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.outside="openCourses = false" 
             x-show="openCourses"
             x-transition:enter="transition ease-out duration-350"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 rounded-t-[2.5rem] p-6 shadow-2xl space-y-4 pb-10 max-w-md mx-auto">
            
            <!-- Drag Indicator -->
            <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mb-2"></div>
            
            <div class="text-center space-y-1">
                <h4 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">เมนูย่อย: จัดการหลักสูตร</h4>
                <p class="text-xs text-slate-450 dark:text-slate-500 font-bold">เลือกการจัดการบทเรียน สื่อการสอน หรือวิดีโอคลิปสั้น</p>
            </div>

            <div class="grid grid-cols-1 gap-2.5 pt-2">
                <a href="{{ route('courses.manage') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center font-bold">📚</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">จัดการหลักสูตร / บทเรียน</span>
                        <span class="text-[10px] text-slate-400 font-bold">สร้างบทเรียน จัดวิชาเรียน จัดการเนื้อหา/หัวข้อการสอน</span>
                    </div>
                </a>
                <a href="{{ url('teachers/ttest') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center font-bold">📝</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">จัดการแบบทดสอบ</span>
                        <span class="text-[10px] text-slate-400 font-bold">สร้างข้อสอบ แบบทดสอบก่อน-หลังเรียน ตรวจประเมินผล</span>
                    </div>
                </a>
                <a href="{{ route('teachers.shorts.index') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-600 flex items-center justify-center font-bold">🎞️</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">จัดการคลิปสั้น (OLIS Shorts)</span>
                        <span class="text-[10px] text-slate-400 font-bold">อัปโหลดคลิปสั้นความรู้ วิดีโอแนวตั้ง หรือภาพสไลด์โชว์</span>
                    </div>
                </a>
                <a href="{{ route('teachers.profile.edit') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 hover:bg-purple-50 hover:text-purple-600 transition-colors border border-slate-100 dark:border-slate-850">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-teal-600 flex items-center justify-center font-bold">👤</div>
                    <div class="text-left">
                        <span class="text-xs font-black block">จัดการโปรไฟล์</span>
                        <span class="text-[10px] text-slate-400 font-bold">แก้ไขข้อมูลส่วนตัว อัปโหลดภาพประจำตัว และเปลี่ยนรหัสผ่าน</span>
                    </div>
                </a>
            </div>

            <button @click="openCourses = false" class="w-full py-3 bg-slate-100 dark:bg-slate-800 text-slate-650 dark:text-slate-300 rounded-2xl text-xs font-black uppercase tracking-widest active:scale-95 transition-all">
                ปิดหน้าต่าง
            </button>
        </div>
    </div>
</div>
