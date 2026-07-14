<x-teachers-layout>
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="p-4 sm:p-6 mt-20 max-w-7xl mx-auto space-y-5">
        
        {{-- Alerts --}}
        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-950/30 dark:text-red-300 border border-red-200 dark:border-red-800/40 flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-xs font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    <i data-lucide="folder-tree" class="w-3.5 h-3.5 mr-1.5"></i> Course Workspace
                </span>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">จัดการโครงสร้างหลักสูตร</h1>
                        <p class="text-sm text-purple-700 dark:text-purple-400 font-bold mt-1">{{ $course->title }}</p>
                    </div>
                    <a href="{{ route('courses.manage') }}" class="inline-flex items-center gap-1.5 px-4.5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-2xl text-xs font-black shadow-sm transition-all active:scale-95 flex-shrink-0">
                        ← กลับไปที่แดชบอร์ด
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== MAIN WORKSPACE: 2 COLUMNS ===== --}}
        <div class="flex flex-col lg:flex-row gap-5">

            {{-- LEFT: Folder Tree Navigation --}}
            <div class="w-full lg:w-[340px] xl:w-[380px] flex-shrink-0 space-y-4">

                {{-- Course Root Node --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <button onclick="showPanel('course')" id="tree-btn-course" class="tree-btn w-full text-left p-4 flex items-center gap-3 hover:bg-purple-50/60 dark:hover:bg-purple-950/30 transition-colors rounded-2xl group">
                        <span class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="book-open" class="w-4 h-4 text-purple-700 dark:text-purple-400"></i>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-800 dark:text-white truncate">📚 {{ $course->title }}</p>
                            <p class="text-[10px] font-bold text-slate-400">ข้อมูลหลักสูตร</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-purple-500 transition-colors"></i>
                    </button>
                </div>

                {{-- Modules & Lessons Tree --}}
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-700 dark:text-slate-200 uppercase tracking-wider flex items-center gap-2">
                            <i data-lucide="layers" class="w-3.5 h-3.5 text-purple-600"></i>
                            โมดูลและบทเรียน
                        </h3>
                        <button onclick="showPanel('add-module')" class="text-[10px] font-black text-purple-700 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 flex items-center gap-1 transition-colors">
                            <i data-lucide="plus" class="w-3 h-3"></i> เพิ่มโมดูล
                        </button>
                    </div>

                    <div class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @if ($modules->isEmpty())
                            <div class="p-6 text-center">
                                <span class="text-3xl block mb-2">📁</span>
                                <p class="text-xs font-bold text-slate-400">ยังไม่มีโมดูล</p>
                                <p class="text-[10px] text-slate-350">คลิก "เพิ่มโมดูล" ด้านบน</p>
                            </div>
                        @else
                            @foreach ($modules as $module)
                                <div class="module-tree-node">
                                    {{-- Module folder row --}}
                                    <div class="flex items-center">
                                        <button onclick="toggleFolder(this)" class="p-2 pl-3 text-slate-400 hover:text-purple-600 transition-colors flex-shrink-0">
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 folder-chevron transition-transform"></i>
                                        </button>
                                        <button onclick="showPanel('module-{{ $module->id }}')" id="tree-btn-module-{{ $module->id }}" class="tree-btn flex-1 text-left py-3 pr-3 flex items-center gap-2.5 hover:bg-amber-50/50 dark:hover:bg-amber-950/20 transition-colors rounded-lg group min-w-0">
                                            <span class="w-6 h-6 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="folder" class="w-3 h-3 text-amber-700 dark:text-amber-400"></i>
                                            </span>
                                            <span class="text-[11px] font-black text-slate-700 dark:text-slate-200 truncate">{{ $module->order_number }}. {{ $module->title }}</span>
                                            <span class="text-[9px] font-bold text-slate-350 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded-full flex-shrink-0">{{ $module->lessons->count() }}</span>
                                        </button>
                                    </div>

                                    {{-- Lessons list inside module --}}
                                    <div class="folder-children pl-8 pr-2 pb-1">
                                        @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                            <button onclick="showPanel('lesson-{{ $lesson->id }}')" id="tree-btn-lesson-{{ $lesson->id }}" class="tree-btn w-full text-left py-2 px-2.5 flex items-center gap-2 hover:bg-sky-50/60 dark:hover:bg-sky-950/20 transition-colors rounded-lg group">
                                                <i data-lucide="file-text" class="w-3 h-3 text-sky-600 dark:text-sky-400 flex-shrink-0"></i>
                                                <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300 truncate">{{ $lesson->order_number }}. {{ $lesson->title }}</span>
                                                @if ($lesson->quiz)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500 flex-shrink-0" title="มีแบบทดสอบ"></span>
                                                @endif
                                                @if ($lesson->short_video_id)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 flex-shrink-0" title="เชื่อมคลิปสั้น"></span>
                                                @endif
                                            </button>
                                        @endforeach

                                        {{-- Add lesson button inside folder --}}
                                        <button onclick="showPanel('add-lesson-{{ $module->id }}')" class="w-full text-left py-2 px-2.5 flex items-center gap-2 hover:bg-emerald-50/60 dark:hover:bg-emerald-950/20 transition-colors rounded-lg text-emerald-600 dark:text-emerald-400">
                                            <i data-lucide="plus" class="w-3 h-3"></i>
                                            <span class="text-[10px] font-black">เพิ่มบทเรียน</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Editor Workspace --}}
            <div class="flex-1 min-w-0">

                {{-- Default empty state --}}
                <div id="panel-default" class="editor-panel bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm p-10 text-center">
                    <div class="max-w-sm mx-auto">
                        <span class="text-5xl block mb-4">🗂️</span>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white mb-2">เลือกรายการจากเมนูด้านซ้าย</h3>
                        <p class="text-xs text-slate-400 font-bold leading-relaxed">คลิกที่ชื่อหลักสูตร โมดูล หรือบทเรียน เพื่อแก้ไขข้อมูล</p>
                    </div>
                </div>

                {{-- ====== Panel: Course Edit ====== --}}
                <div id="panel-course" class="editor-panel hidden">
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-purple-50/70 dark:bg-purple-950/40 px-6 py-4 border-b border-purple-100 dark:border-purple-900/40">
                            <h3 class="text-sm font-black text-purple-900 dark:text-purple-200 flex items-center gap-2">
                                <i data-lucide="book-open" class="w-4 h-4"></i> แก้ไขข้อมูลหลักสูตร
                            </h3>
                        </div>
                        <form action="{{ route('courses.update', $course->id) }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">ชื่อหลักสูตร <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">รูปภาพปก</label>
                                <div class="mb-3">
                                    <div id="ws_preview_container" class="{{ $course->cover_image ? '' : 'hidden' }}">
                                        <img id="ws_image_preview" src="{{ $course->cover_image }}" alt="Cover" class="w-full max-w-md rounded-xl shadow-sm border mb-2">
                                    </div>
                                </div>
                                <input type="file" id="ws_image_selector" accept="image/*" class="w-full text-xs text-slate-600 py-2">
                                <input type="hidden" name="cover_image_base64" id="ws_cover_image_base64">
                                <p class="text-[10px] text-slate-400 mt-1">อัปโหลดไฟล์ใหม่เพื่อแทนที่ (JPEG, PNG)</p>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">คำอธิบาย</label>
                                <textarea name="description" rows="4" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">{{ old('description', $course->description) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">ราคา (บาท)</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" step="0.01" min="0" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center gap-1.5">
                                    <i data-lucide="save" class="w-4 h-4"></i> บันทึกหลักสูตร
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ====== Panel: Add Module ====== --}}
                <div id="panel-add-module" class="editor-panel hidden">
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-amber-50/70 dark:bg-amber-950/40 px-6 py-4 border-b border-amber-100 dark:border-amber-900/40">
                            <h3 class="text-sm font-black text-amber-900 dark:text-amber-200 flex items-center gap-2">
                                <i data-lucide="folder-plus" class="w-4 h-4"></i> เพิ่มโมดูลใหม่
                            </h3>
                        </div>
                        <form action="{{ route('courses.store_module', $course->id) }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">ชื่อโมดูล <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" placeholder="เช่น บทที่ 1: แนะนำพื้นฐานเบื้องต้น..." required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center gap-1.5">
                                    <i data-lucide="plus" class="w-4 h-4"></i> บันทึกโมดูล
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ====== Panels: Module Edit (one per module) ====== --}}
                @foreach ($modules as $module)
                <div id="panel-module-{{ $module->id }}" class="editor-panel hidden">
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-amber-50/70 dark:bg-amber-950/40 px-6 py-4 border-b border-amber-100 dark:border-amber-900/40 flex items-center justify-between">
                            <h3 class="text-sm font-black text-amber-900 dark:text-amber-200 flex items-center gap-2">
                                <i data-lucide="folder" class="w-4 h-4"></i> แก้ไขโมดูล: {{ $module->title }}
                            </h3>
                            <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบโมดูลนี้? บทเรียนทั้งหมดในโมดูลจะถูกลบออกด้วย')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-black text-rose-500 hover:text-rose-700 flex items-center gap-1 transition-colors">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> ลบโมดูล
                                </button>
                            </form>
                        </div>
                        <form action="{{ route('modules.update', $module->id) }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">ชื่อโมดูล <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" value="{{ $module->title }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-950/50 rounded-xl p-4">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider mb-2">สรุป</p>
                                <div class="flex gap-4">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">ลำดับที่: <span class="text-purple-700 dark:text-purple-400">{{ $module->order_number }}</span></span>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">บทเรียน: <span class="text-purple-700 dark:text-purple-400">{{ $module->lessons->count() }} รายการ</span></span>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center gap-1.5">
                                    <i data-lucide="save" class="w-4 h-4"></i> บันทึกการแก้ไข
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach

                {{-- ====== Panels: Lesson Edit (one per lesson) ====== --}}
                @foreach ($modules as $module)
                    @foreach ($module->lessons->sortBy('order_number') as $lesson)
                    <div id="panel-lesson-{{ $lesson->id }}" class="editor-panel hidden">
                        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                            <div class="bg-sky-50/70 dark:bg-sky-950/40 px-6 py-4 border-b border-sky-100 dark:border-sky-900/40 flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-black text-sky-900 dark:text-sky-200 flex items-center gap-2">
                                        <i data-lucide="file-text" class="w-4 h-4"></i> แก้ไขบทเรียน
                                    </h3>
                                    <p class="text-[10px] font-bold text-sky-600/80 dark:text-sky-400/60 mt-0.5">โมดูล: {{ $module->title }}</p>
                                </div>
                                <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบบทเรียนนี้?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-black text-rose-500 hover:text-rose-700 flex items-center gap-1 transition-colors">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> ลบ
                                    </button>
                                </form>
                            </div>
                            <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" class="p-6 space-y-5">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">หัวข้อบทเรียน <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="{{ $lesson->title }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">URL วิดีโอ</label>
                                        <input type="url" name="video_url" value="{{ $lesson->video_url }}" placeholder="https://youtube.com/..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เชื่อมต่อแบบทดสอบ</label>
                                        <select name="quiz_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                            <option value="">--- ไม่ระบุ ---</option>
                                            @foreach($quizzes as $quiz)
                                                <option value="{{ $quiz->id }}" {{ $lesson->quiz_id == $quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เชื่อมต่อคลิปสั้น (OLIS Shorts)</label>
                                    <select name="short_video_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                        <option value="">--- ไม่เชื่อมคลิปสั้น ---</option>
                                        @foreach($shortVideos as $video)
                                            <option value="{{ $video->id }}" {{ $lesson->short_video_id == $video->id ? 'selected' : '' }}>{{ $video->title }} ({{ $video->type === 'video' ? 'วิดีโอ' : 'ภาพสไลด์' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เนื้อหาบทเรียน <span class="text-rose-500">*</span></label>
                                    <textarea name="content" rows="10" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="พิมพ์เนื้อหาบทเรียนที่นี่...">{{ $lesson->content }}</textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center gap-1.5">
                                        <i data-lucide="save" class="w-4 h-4"></i> อัปเดตบทเรียน
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @endforeach

                {{-- ====== Panels: Add Lesson (one per module) ====== --}}
                @foreach ($modules as $module)
                <div id="panel-add-lesson-{{ $module->id }}" class="editor-panel hidden">
                    <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                        <div class="bg-emerald-50/70 dark:bg-emerald-950/40 px-6 py-4 border-b border-emerald-100 dark:border-emerald-900/40">
                            <h3 class="text-sm font-black text-emerald-900 dark:text-emerald-200 flex items-center gap-2">
                                <i data-lucide="file-plus" class="w-4 h-4"></i> เพิ่มบทเรียนใหม่
                            </h3>
                            <p class="text-[10px] font-bold text-emerald-600/80 dark:text-emerald-400/60 mt-0.5">ในโมดูล: {{ $module->title }}</p>
                        </div>
                        <form action="{{ route('modules.store_lesson', $module->id) }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">ชื่อบทเรียน <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" required placeholder="เช่น พื้นฐานการติดตั้งซอฟต์แวร์" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">URL วิดีโอ</label>
                                    <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เชื่อมต่อแบบทดสอบ</label>
                                    <select name="quiz_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                        <option value="">--- ไม่ระบุ ---</option>
                                        @foreach($quizzes as $quiz)
                                            <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เชื่อมต่อคลิปสั้น (OLIS Shorts)</label>
                                <select name="short_video_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all">
                                    <option value="">--- ไม่เชื่อมคลิปสั้น ---</option>
                                    @foreach($shortVideos as $video)
                                        <option value="{{ $video->id }}">{{ $video->title }} ({{ $video->type === 'video' ? 'วิดีโอ' : 'ภาพสไลด์' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-black text-slate-700 dark:text-slate-200 mb-1.5">เนื้อหาบทเรียน <span class="text-rose-500">*</span></label>
                                <textarea name="content" rows="10" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-100 focus:ring-purple-500 focus:border-purple-500 transition-all" placeholder="ใส่เนื้อหา รายละเอียดบทเรียนที่นี่..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black transition-all shadow-md active:scale-95 flex items-center gap-1.5">
                                    <i data-lucide="save" class="w-4 h-4"></i> บันทึกบทเรียน
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Panel switching
        function showPanel(panelName) {
            // Hide all panels
            document.querySelectorAll('.editor-panel').forEach(p => p.classList.add('hidden'));
            // Deactivate all tree buttons
            document.querySelectorAll('.tree-btn').forEach(b => {
                b.classList.remove('bg-purple-100/60', 'dark:bg-purple-950/40', 'bg-amber-100/60', 'dark:bg-amber-950/40', 'bg-sky-100/60', 'dark:bg-sky-950/40');
            });

            // Show target panel
            const panel = document.getElementById('panel-' + panelName);
            if (panel) {
                panel.classList.remove('hidden');
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Highlight active tree button
            const btn = document.getElementById('tree-btn-' + panelName);
            if (btn) {
                if (panelName === 'course') {
                    btn.classList.add('bg-purple-100/60', 'dark:bg-purple-950/40');
                } else if (panelName.startsWith('module')) {
                    btn.classList.add('bg-amber-100/60', 'dark:bg-amber-950/40');
                } else if (panelName.startsWith('lesson')) {
                    btn.classList.add('bg-sky-100/60', 'dark:bg-sky-950/40');
                }
            }

            // Re-init lucide icons for the new panel
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Toggle folder expand/collapse
        function toggleFolder(button) {
            const node = button.closest('.module-tree-node');
            const children = node.querySelector('.folder-children');
            const chevron = button.querySelector('.folder-chevron');

            if (children.classList.contains('hidden')) {
                children.classList.remove('hidden');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                children.classList.add('hidden');
                chevron.style.transform = 'rotate(-90deg)';
            }
        }

        // Cover image base64 handler
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const selector = document.getElementById('ws_image_selector');
            if (selector) {
                selector.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.size > 2 * 1024 * 1024) {
                            alert('ไฟล์มีขนาดใหญ่เกิน 2MB กรุณาเลือกไฟล์ใหม่');
                            e.target.value = '';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onloadend = function() {
                            document.getElementById('ws_cover_image_base64').value = reader.result;
                            document.getElementById('ws_image_preview').src = reader.result;
                            document.getElementById('ws_preview_container').classList.remove('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-teachers-layout>