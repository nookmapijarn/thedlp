<x-teachers-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Banner Block -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[160px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-xs font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    OLIS Courses Studio
                </span>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white">จัดการหลักสูตรและห้องเรียน</h1>
                        <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-semibold max-w-3xl mt-1.5">
                            บริหารจัดการบทเรียน คอร์สออนไลน์ สารบัญเนื้อหาโมดูล และวิดีโอแนะนำคลิปสั้นประจำหลักสูตรสำหรับนักเรียนนักศึกษา
                        </p>
                    </div>
                    <a href="{{ route('courses.create') }}" class="inline-flex items-center gap-2 px-5 py-3.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-2xl text-xs font-black tracking-widest uppercase transition-all shadow-md active:scale-95 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                        </svg>
                        สร้างหลักสูตรใหม่
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Course List Section -->
        <div class="space-y-4">
            <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                </svg>
                รายวิชา/หลักสูตรที่ฉันรับผิดชอบ
            </h2>

            @if ($courses->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl space-y-4">
                    <span class="text-5xl block">📖</span>
                    <h3 class="text-base font-black text-slate-800 dark:text-white">ยังไม่มีหลักสูตรที่คุณสร้างไว้</h3>
                    <p class="text-xs text-slate-450 font-bold max-w-sm mx-auto leading-relaxed">
                        เริ่มสร้างหลักสูตรวิชาใหม่ของคุณเพื่อเผยแพร่บทเรียน แบบทดสอบ และเนื้อหาคลิปสั้นลงสู่ระบบห้องเรียนออนไลน์
                    </p>
                    <a href="{{ route('courses.create') }}" class="inline-flex py-2.5 px-5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-black transition-all">
                        เริ่มต้นสร้างหลักสูตร
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($courses as $course)
                        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-md hover:scale-[1.02] transition-all flex flex-col justify-between">
                            
                            <!-- Cover Image Header -->
                            <div class="relative w-full h-44 bg-slate-100 dark:bg-slate-950 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if ($course->cover_image)
                                    <img src="{{ $course->cover_image }}" alt="Course Cover" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-950/40 dark:to-indigo-950/40 flex flex-col items-center justify-center text-purple-700 dark:text-purple-400 gap-1.5">
                                        <svg class="w-10 h-10 opacity-70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"></path>
                                        </svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">No Cover Image</span>
                                    </div>
                                @endif
                                
                                <!-- Status Badge Overlay -->
                                <span class="absolute top-4 right-4 px-2.5 py-1 bg-black/60 backdrop-blur-md text-white text-[9px] font-black rounded-lg uppercase tracking-wider">
                                    {{ $course->is_published ? 'เปิดให้บริการ' : 'ฉบับร่าง' }}
                                </span>
                            </div>

                            <!-- Course Info Body -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div class="space-y-2">
                                    <h3 class="text-base font-black text-slate-800 dark:text-white leading-snug line-clamp-1">
                                        {{ $course->title }}
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-bold leading-relaxed line-clamp-2">
                                        {{ $course->description ?? 'ไม่มีข้อมูลคำอธิบายหลักสูตรย่อ...' }}
                                    </p>
                                </div>

                                <!-- Course Stats Grid -->
                                <div class="grid grid-cols-3 gap-2 py-3.5 border-y border-slate-100 dark:border-slate-800 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">📦 โมดูล</span>
                                        <span class="text-sm font-black text-slate-750 dark:text-slate-200 mt-0.5">{{ $course->modules_count }}</span>
                                    </div>
                                    <div class="flex flex-col items-center border-x border-slate-100 dark:border-slate-800">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">📝 บทเรียน</span>
                                        <span class="text-sm font-black text-slate-750 dark:text-slate-200 mt-0.5">{{ $course->lessons_count }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">🎥 คลิปสั้น</span>
                                        <span class="text-sm font-black text-purple-700 dark:text-purple-400 mt-0.5">{{ $course->short_videos_count }}</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2.5 pt-1">
                                    <a href="{{ route('courses.manage_modules', $course->id) }}" class="w-full py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5-5.25h16.5m-16.5 10.5h16.5"></path>
                                        </svg>
                                        จัดการโครงสร้างและบทเรียน
                                    </a>
                                    
                                    <div class="flex justify-between items-center gap-2">
                                        <a href="{{ route('courses.edit', $course->id) }}" class="flex-1 py-2 bg-slate-50 hover:bg-slate-100 dark:bg-slate-950 dark:hover:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 rounded-xl text-[11px] font-black transition-all text-center flex items-center justify-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                                            </svg>
                                            แก้ไขคอร์ส
                                        </a>
                                        
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="flex-1" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหลักสูตรนี้และเนื้อหาภายในทั้งหมด?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-2 bg-rose-50/50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/30 border border-rose-200/50 dark:border-rose-900/40 text-rose-600 rounded-xl text-[11px] font-black transition-all flex items-center justify-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9 9m-4.788-3L9 9m-4.788-3h9.75M9 9h6m-9-3h12a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h12M9 4.5V3a1.5 1.5 0 011.5-1.5h3a1.5 1.5 0 011.5 1.5v1.5M9 4.5h6"></path>
                                                </svg>
                                                ลบหลักสูตร
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-teachers-layout>