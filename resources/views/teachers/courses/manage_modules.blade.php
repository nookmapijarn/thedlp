<x-teachers-layout>
    <script src="https://unpkg.com/lucide@latest"></script>

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

        @if(session('error'))
            <div class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-950/30 dark:text-red-300 border border-red-200 dark:border-red-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                </svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Header Banner Block -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[160px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-xs font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    Course Structure Manage
                </span>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white">จัดการโมดูลและบทเรียน</h1>
                        <p class="text-sm text-purple-700 dark:text-purple-400 font-bold mt-1">
                            วิชา: {{ $course->title }}
                        </p>
                    </div>
                    <a href="{{ route('courses.manage') }}" class="inline-flex items-center gap-1.5 px-4.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-2xl text-xs font-black shadow-sm transition-all active:scale-95 flex-shrink-0">
                        ← กลับไปที่แดชบอร์ด
                    </a>
                </div>
            </div>
        </div>

        <!-- Add Module Form Section -->
        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
            <h2 class="text-sm font-black text-slate-800 dark:text-white mb-4 uppercase tracking-wider">เพิ่มโมดูล/บทเรียนบทใหม่</h2>
            <form action="{{ route('courses.store_module', $course->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="title" placeholder="ชื่อโมดูล (เช่น บทที่ 1: แนะนำพื้นฐานเบื้องต้น...)" required class="flex-1 px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                <button type="submit" class="py-3 px-6 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    บันทึกโมดูล
                </button>
            </form>
        </div>

        <!-- Modules List Section -->
        <div class="space-y-6">
            @if ($modules->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl">
                    <span class="text-5xl block mb-3">📁</span>
                    <h3 class="text-sm font-black text-slate-800 dark:text-white">หลักสูตรนี้ยังไม่มีโมดูลหลักสูตร</h3>
                    <p class="text-xs text-slate-450 font-bold mt-1">กรอกชื่อโมดูลด้านบนเพื่อเริ่มออกแบบแผนบทเรียน</p>
                </div>
            @else
                @foreach ($modules as $module)
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                        <!-- Module Header -->
                        <div class="bg-slate-50/70 dark:bg-slate-950/50 p-5 border-b border-slate-150 dark:border-slate-800/80 flex flex-wrap justify-between items-center gap-4">
                            <div class="flex-1 mr-4">
                                <div id="module-title-container-{{ $module->id }}" class="flex items-center space-x-2">
                                    <h3 class="text-base font-black text-slate-800 dark:text-white">
                                        {{ $module->order_number }}. {{ $module->title }}
                                    </h3>
                                    <button onclick="toggleEditModule({{ $module->id }})" class="text-slate-400 hover:text-purple-600 transition-colors p-1 rounded-lg">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <form id="module-edit-form-{{ $module->id }}" action="{{ route('modules.update', $module->id) }}" method="POST" class="hidden flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="title" value="{{ $module->title }}" class="px-3 py-1.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold focus:ring-purple-500 focus:border-purple-500">
                                    <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white px-3 py-1.5 rounded-lg text-[10px] font-black transition-colors">บันทึก</button>
                                    <button type="button" onclick="toggleEditModule({{ $module->id }})" class="text-slate-500 text-[10px] font-bold px-2 py-1.5">ยกเลิก</button>
                                </form>
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('modules.create_lesson', $module->id) }}" class="text-xs px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black transition-all flex items-center shadow-sm">
                                    <i data-lucide="plus" class="w-3.5 h-3.5 mr-1"></i> เพิ่มบทเรียน
                                </a>
                                
                                <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบโมดูลนี้? บทเรียนทั้งหมดในโมดูลจะถูกลบออกด้วย')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Lessons List inside Module -->
                        <div class="p-5">
                            @if ($module->lessons->isEmpty())
                                <p class="text-slate-400 italic text-xs font-bold text-center py-6">ยังไม่มีบทเรียนย่อยในโมดูลนี้</p>
                            @else
                                <div class="space-y-2">
                                    @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                        <div class="flex justify-between items-center p-3 bg-slate-50 dark:bg-slate-950/30 rounded-2xl border border-slate-100/60 dark:border-slate-800/40 group hover:bg-white dark:hover:bg-slate-900 transition-all hover:shadow-sm">
                                            <div class="flex items-center">
                                                <i data-lucide="play-circle" class="w-4 h-4 text-purple-600 mr-3 flex-shrink-0"></i>
                                                <span class="text-xs font-black text-slate-750 dark:text-slate-200">
                                                    {{ $lesson->order_number }}. {{ $lesson->title }}
                                                    @if ($lesson->quiz)
                                                        <span class="ml-2 text-[9px] bg-purple-50 text-purple-700 dark:bg-purple-950 dark:text-purple-400 px-2 py-0.5 rounded-full font-black">มีควิซแบบทดสอบ</span>
                                                    @endif
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('lessons.edit', $lesson->id) }}" class="p-1.5 text-slate-450 hover:text-purple-650 hover:bg-slate-100 dark:hover:bg-slate-850 rounded-lg transition-all" title="แก้ไขบทเรียน">
                                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                                </a>
                                                
                                                <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหน่วยบทเรียนนี้?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-rose-450 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg transition-all" title="ลบบทเรียน">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>

    <!-- Edit Module Inline JavaScript Trigger -->
    <script>
        function toggleEditModule(id) {
            const container = document.getElementById(`module-title-container-${id}`);
            const form = document.getElementById(`module-edit-form-${id}`);
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                form.classList.add('hidden');
            } else {
                container.classList.add('hidden');
                form.classList.remove('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</x-teachers-layout>