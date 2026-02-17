<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] font-sans antialiased text-slate-900">
        
        {{-- Header Section: Premium Look --}}
        <div class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center py-5 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">
                            คลังข้อสอบ<span class="text-indigo-600">ออนไลน์</span>
                        </h1>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-100 text-sm font-semibold">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Active: {{ $quizzes->where('is_active', 1)->count() }}
                        </div>
                    </div>
                </div>

                {{-- Premium Tab Navigation --}}
                <nav class="flex space-x-1" aria-label="Tabs">
                    <button onclick="switchTab('assigned')" id="tab-btn-assigned"
                        class="tab-link relative py-4 px-6 font-semibold text-sm transition-all duration-300">
                        📋 ที่ครูมอบหมาย
                        @if(isset($assignedCount) && $assignedCount > 0)
                            <span class="ml-2 py-0.5 px-2 rounded-full text-[10px] bg-red-500 text-white">{{ $assignedCount }}</span>
                        @endif
                    </button>

                    <button onclick="switchTab('all')" id="tab-btn-all"
                        class="tab-link relative py-4 px-6 font-semibold text-sm transition-all duration-300">
                        📚 ทั้งหมด
                    </button>

                    <button onclick="switchTab('history')" id="tab-btn-history"
                        class="tab-link relative py-4 px-6 font-semibold text-sm transition-all duration-300">
                        🕒 ประวัติ
                    </button>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="mb-8 bg-white border-l-4 border-rose-500 p-5 rounded-xl shadow-xl shadow-rose-100 flex items-start gap-4">
                    <div class="bg-rose-100 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-rose-800">พบข้อผิดพลาดบางประการ</h3>
                        <ul class="mt-1 text-xs text-rose-600/80 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ================= TAB: ASSIGNED ================= --}}
            <div id="tab-assigned" class="tab-content hidden animate-in fade-in slide-in-from-bottom-4">
                {{-- Header Banner --}}
                <div class="bg-gradient-to-r from-indigo-600 to-violet-700 rounded-3xl p-10 mb-10 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold">ข้อสอบที่ครูมอบหมาย</h3>
                        <p class="opacity-80 mt-2 font-light">รายการข้อสอบสำคัญที่คุณต้องทำให้เสร็จตามกำหนดการ</p>
                    </div>
                    <div class="absolute right-[-20px] top-[-20px] opacity-10">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                </div>

                @if (count($assignedQuizzes) > 0)
                    {{-- Quiz Grid สำหรับงานที่ได้รับมอบหมาย --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($assignedQuizzes as $quiz)
                            <div class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 border border-slate-100 overflow-hidden flex flex-col h-full relative">
                                
                                {{-- Status Badge (มุมขวาบน) --}}
                                <div class="absolute top-4 right-4 z-20">
                                    @if($quiz->is_completed)
                                        <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-full shadow-lg">
                                            <i class="fas fa-check mr-1"></i> ทำเสร็จแล้ว
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-orange-500 text-white text-[10px] font-bold rounded-full shadow-lg animate-pulse">
                                            รอดำเนินการ
                                        </span>
                                    @endif
                                </div>

                                {{-- Quiz Cover --}}
                                <div class="relative h-44 overflow-hidden bg-indigo-100">
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center transition-transform duration-700 group-hover:scale-110">
                                        <svg class="w-16 h-16 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="p-8 flex-grow">
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @php
                                            $gradeConfig = [
                                                1 => ['l' => 'ประถม', 'c' => 'bg-rose-50 text-rose-600 border-rose-100'],
                                                2 => ['l' => 'มัธยมต้น', 'c' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                                3 => ['l' => 'มัธยมปลาย', 'c' => 'bg-amber-50 text-amber-600 border-amber-100']
                                            ];
                                            $curr = $gradeConfig[$quiz->grade_level] ?? ['l' => 'ทั่วไป', 'c' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                        @endphp
                                        <span class="px-3 py-1 {{ $curr['c'] }} text-[10px] font-bold rounded-lg uppercase tracking-widest border">
                                            {{ $curr['l'] }}
                                        </span>
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg border border-indigo-100">
                                            {{ $quiz->subject_table_type }}
                                        </span>
                                    </div>

                                    <h2 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                                        {{ $quiz->title }}
                                    </h2>

                                    <div class="flex items-center gap-2 mb-4 text-slate-400">
                                        <span class="text-xs">โดย {{ $quiz->created_by_name ?? 'ไม่ระบุชื่อครู' }}</span>
                                    </div>

                                    @if($quiz->due_date)
                                    <p class="text-rose-500 text-xs mb-4 font-medium">
                                        <i class="far fa-calendar-alt mr-1"></i> กำหนดส่ง: {{ date('d/m/Y', strtotime($quiz->due_date)) }}
                                    </p>
                                    @endif

                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <span class="text-xs font-medium">{{ $quiz->total_score }} คะแนน</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500 text-right justify-end">
                                            <span class="text-xs font-medium">{{ $quiz->time_limit }} นาที</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Footer --}}
                                <div class="px-8 pb-8">
                                    @if(!$quiz->is_completed)
                                        <a href="{{ route('quizzes.start', $quiz->id) }}" 
                                        class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-2xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-100 hover:-translate-y-1 transition-all duration-300">
                                            เริ่มทำข้อสอบ
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        </a>
                                    @else
                                        <button disabled class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-2xl text-sm font-bold text-slate-400 bg-slate-100 cursor-not-allowed">
                                            ส่งงานเรียบร้อยแล้ว
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State เมื่อไม่มีงานที่ได้รับมอบหมาย --}}
                    <div class="flex flex-col items-center justify-center py-20 text-slate-400 bg-white rounded-[3rem] border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="font-medium text-lg text-slate-500">ไม่มีข้อสอบที่ได้รับมอบหมายในขณะนี้</p>
                        <p class="text-sm">คุณทำหน้าที่ได้ยอดเยี่ยมมาก! พักผ่อนได้เลย</p>
                    </div>
                @endif
            </div>

            {{-- ================= TAB: ALL QUIZZES ================= --}}
            <div id="tab-all" class="tab-content hidden animate-in fade-in">
                
                {{-- Search Toolbar --}}
                <form method="GET" action="{{ route('ทดสอบออนไลน์') }}" class="bg-white/70 backdrop-blur-sm p-6 rounded-3xl shadow-xl shadow-slate-200/50 border border-white mb-10">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                        <div class="md:col-span-4 relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="ค้นหาวิชาหรือชื่อข้อสอบ..." 
                                class="pl-11 block w-full rounded-2xl border-slate-100 bg-slate-50/50 text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all sm:text-sm h-12"
                                value="{{ request('search') }}">
                        </div>

                        {{-- <div class="md:col-span-2">
                            <select name="subject" class="block w-full rounded-2xl border-slate-100 bg-slate-50/50 text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 h-12 sm:text-sm">
                                <option value="">ทุกวิชา</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="md:col-span-2">
                            <select name="grade" class="block w-full rounded-2xl border-slate-100 bg-slate-50/50 text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 h-12 sm:text-sm">
                                {{-- ตรวจสอบว่าถ้าไม่มีการส่งค่า request('grade') มา หรือค่าเป็นว่าง ให้เลือก "ทั้งหมด" --}}
                                <option value="" {{ request('grade') === null || request('grade') === '' ? 'selected' : '' }}>ทั้งหมด</option>
                                
                                @php
                                    // กำหนดชื่อระดับชั้นตามตัวเลข ID
                                    $gradeNames = [
                                        0 => 'ข้อสอบทั่วไป',
                                        1 => 'เฉพาะประถม',
                                        2 => 'เฉพาะมัธยมต้น',
                                        3 => 'เฉพาะมัธยมปลาย'
                                    ];
                                @endphp

                                @foreach($grades as $grade)
                                    {{-- ตรวจสอบค่าที่ส่งมาจาก Request เพื่อรักษาค่าที่เลือกไว้หลังกดค้นหา --}}
                                    <option value="{{ $grade }}" {{ request('grade') !== null && request('grade') != '' && request('grade') == $grade ? 'selected' : '' }}>
                                        {{ $gradeNames[$grade] ?? 'ระดับ ' . $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <select name="creator" class="block w-full rounded-2xl border-slate-100 bg-slate-50/50 text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 h-12 sm:text-sm">
                                <option value="">ผู้สร้างทั้งหมด</option>
                                @foreach($creators as $creator)
                                    <option value="{{ $creator->id }}" {{ request('creator') == $creator->id ? 'selected' : '' }}>{{ $creator->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <button type="submit" class="w-full h-12 inline-flex items-center justify-center rounded-2xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                                ค้นหา
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Quiz Grid --}}
                @if (count($quizzes) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($quizzes as $quiz)
                            <div class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 border border-slate-100 overflow-hidden flex flex-col h-full relative">
                                
                                {{-- Quiz Cover Image --}}
                                <div class="relative h-22 sm:h-44 overflow-hidden bg-indigo-100">
                                    @if($quiz->is_attempted > 0)
                                        <div class="absolute top-4 left-4 z-20">
                                            <span class="px-3 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg shadow-lg flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                                ทำแล้ว
                                            </span>
                                        </div>
                                    @endif
                                    @if($quiz->cover_image)
                                        <img src="{{ $quiz->cover_image }}" alt="{{ $quiz->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center transition-transform duration-700 group-hover:scale-110">
                                            <svg class="w-16 h-16 text-white/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>

                                {{-- Card Body --}}
                                <div class="p-8 flex-grow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg uppercase tracking-widest border border-indigo-100">
                                                {{ $quiz->subject_code ?? 'CODE' }}
                                            </span>
                                            @php
                                                $gradeConfig = [
                                                    1 => ['l' => 'ประถม', 'c' => 'bg-rose-50 text-rose-600 border-rose-100'],
                                                    2 => ['l' => 'มัธยมต้น', 'c' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                                    3 => ['l' => 'มัธยมปลาย', 'c' => 'bg-amber-50 text-amber-600 border-amber-100']
                                                ];
                                                $curr = $gradeConfig[$quiz->grade_level] ?? ['l' => 'ทั่วไป', 'c' => 'bg-slate-50 text-slate-600 border-slate-100'];
                                            @endphp
                                            <span class="px-3 py-1 {{ $curr['c'] }} text-[10px] font-bold rounded-lg uppercase tracking-widest border">
                                                {{ $curr['l'] }}
                                            </span>
                                        </div>
                                        @if($quiz->is_active)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                        @endif
                                    </div>

                                    <h2 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">
                                        {{ $quiz->title }}
                                    </h2>

                                    {{-- Creator Info --}}
                                    <div class="flex items-center gap-2 mb-4 text-slate-400">
                                        <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <span class="text-xs">โดย {{ $quiz->creator_name ?? 'ไม่ระบุชื่อครู' }}</span>
                                    </div>

                                    <p class="้etext-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                                        {{ $quiz->description ?? 'ไม่มีคำอธิบายเพิ่มเติมสำหรับแบบทดสอบนี้' }}
                                    </p>

                                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <div class="p-1.5 bg-slate-50 rounded-lg text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <span class="text-xs font-medium">{{ $quiz->time_limit > 0 ? $quiz->time_limit . ' m' : 'No limit' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-500">
                                            <div class="p-1.5 bg-slate-50 rounded-lg text-slate-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <span class="text-xs font-medium">{{ $quiz->total_score }} คะแนน</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Footer --}}
                                <div class="px-8 pb-8">
                                    <a href="{{ route('quizzes.start', $quiz->id) }}" 
                                       class="w-full flex items-center justify-center gap-2 py-4 px-6 rounded-2xl text-sm font-bold text-white bg-slate-900 group-hover:bg-indigo-600 group-hover:shadow-xl group-hover:shadow-indigo-200 transition-all duration-300">
                                        เริ่มทำข้อสอบ
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-[3rem] p-20 border border-dashed border-slate-200 text-center">
                        <h3 class="text-xl font-bold text-slate-800">ไม่พบสิ่งที่คุณกำลังค้นหา</h3>
                        <p class="text-slate-400 mt-2">ลองเปลี่ยนคำค้นหาหรือตัวกรองใหม่อีกครั้ง</p>
                    </div>
                @endif
            </div>

        {{-- ================= TAB: HISTORY with FULL SORT & SEARCH ================= --}}
        <div id="tab-history" class="tab-content hidden animate-in fade-in">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                
                {{-- Header Section with Search --}}
                <div class="px-8 py-6 border-b border-slate-50 bg-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">ประวัติการสอบ</h2>
                        <p class="text-xs text-slate-400 mt-1">* คลิกที่หัวตารางเพื่อเรียงลำดับ หรือค้นหาจากชื่อวิชา</p>
                    </div>

                    {{-- Search Input --}}
                    <div class="relative w-full md:w-72">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="historySearchInput" onkeyup="filterHistoryTable()" 
                            class="block w-full pl-10 pr-3 py-2.5 border border-slate-100 rounded-2xl leading-5 bg-slate-50/50 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 sm:text-sm transition-all" 
                            placeholder="ค้นหาชื่อข้อสอบ...">
                    </div>
                </div>

                @if (count($quizAttemptsHistory) > 0)
                    <div class="w-full">
                        <table id="historyTable" class="w-full text-left border-collapse">
                            {{-- Header: Hidden on Mobile --}}
                            <thead class="hidden md:table-header-group">
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th onclick="sortHistoryTable(0)" class="cursor-pointer px-8 py-5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors group">
                                        ชื่อข้อสอบ <span class="sort-icon opacity-0 group-hover:opacity-100 ml-1">↕</span>
                                    </th>
                                    <th onclick="sortHistoryTable(1)" class="cursor-pointer px-8 py-5 text-left text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors group">
                                        วันที่สอบ <span class="sort-icon opacity-0 group-hover:opacity-100 ml-1">↕</span>
                                    </th>
                                    <th onclick="sortHistoryTable(2)" class="cursor-pointer px-8 py-5 text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors group">
                                        คะแนน <span class="sort-icon opacity-0 group-hover:opacity-100 ml-1">↕</span>
                                    </th>
                                    <th onclick="sortHistoryTable(3)" class="cursor-pointer px-8 py-5 text-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] hover:text-indigo-600 transition-colors group">
                                        สถานะ <span class="sort-icon opacity-0 group-hover:opacity-100 ml-1">↕</span>
                                    </th>
                                    <th class="px-8 py-5 text-right text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">จัดการ</th>
                                </tr>
                            </thead>
                            
                            <tbody class="block md:table-row-group divide-y divide-slate-50">
                                @foreach ($quizAttemptsHistory as $attempt)
                                    @php
                                        $percent = ($attempt->user_score / $attempt->quiz_total_score) * 100;
                                        $passed = $percent >= 50;
                                    @endphp
                                    {{-- Row: Becomes a Card on Mobile --}}
                                    <tr class="block md:table-row hover:bg-slate-50/80 transition-all group overflow-hidden">
                                        
                                        {{-- ชื่อข้อสอบ --}}
                                        <td class="block md:table-cell px-8 py-4 md:py-6">
                                            <span class="md:hidden text-[10px] text-slate-400 font-bold uppercase block mb-1">วิชา</span>
                                            <div class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $attempt->quiz_title }}</div>
                                            <div class="text-[10px] text-slate-400 uppercase mt-1">Total: {{ $attempt->quiz_total_score }} pts</div>
                                        </td>

                                        {{-- วันที่สอบ --}}
                                        <td class="block md:table-cell px-8 py-3 md:py-6">
                                            <div class="flex justify-between md:block">
                                                <span class="md:hidden text-[10px] text-slate-400 font-bold uppercase">วันที่สอบ</span>
                                                <div>
                                                    <div class="text-sm text-slate-500 font-medium">
                                                        {{-- ตรวจสอบก่อนว่ามีข้อมูลไหม ถ้าไม่มีให้แสดง '-' --}}
                                                        {{ $attempt->finished_at 
                                                            ? \Carbon\Carbon::parse($attempt->finished_at)->translatedFormat('j M Y') 
                                                            : 'ยังไม่ระบุ' }}
                                                    </div>
                                                    <div class="text-[10px] text-slate-400">
                                                        {{-- ทำเช่นเดียวกันกับส่วนของเวลา --}}
                                                        @if($attempt->finished_at)
                                                            {{ \Carbon\Carbon::parse($attempt->finished_at)->format('H:i') }} น.
                                                        @else
                                                            --:-- น.
                                                        @endif
                                                    </div>
                                                    {{-- Hidden timestamp for sorting --}}
                                                    <span class="hidden sort-data">{{ $attempt->finished_at }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- คะแนน --}}
                                        <td class="block md:table-cell px-8 py-3 md:py-6 text-center">
                                            <div class="flex justify-between md:flex-col items-center">
                                                <span class="md:hidden text-[10px] text-slate-400 font-bold uppercase">คะแนน</span>
                                                <div class="inline-flex flex-col items-end md:items-center">
                                                    <span class="text-base font-black {{ $passed ? 'text-emerald-500' : 'text-rose-500' }}">
                                                        {{ $attempt->user_score }}
                                                    </span>
                                                    <div class="w-16 bg-slate-100 h-1 rounded-full mt-1 overflow-hidden mx-auto">
                                                        <div class="{{ $passed ? 'bg-emerald-400' : 'bg-rose-400' }} h-full" style="width: {{ $percent }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- สถานะ --}}
                                        <td class="block md:table-cell px-8 py-3 md:py-6 text-center">
                                            <div class="flex justify-between md:block items-center">
                                                <span class="md:hidden text-[10px] text-slate-400 font-bold uppercase">สถานะ</span>
                                                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold {{ $passed ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                                    {{ $passed ? 'PASSED' : 'FAILED' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- จัดการ --}}
                                        <td class="block md:table-cell px-8 py-5 md:py-6 text-right">
                                            @if ($percent >= 80)
                                                <button onclick="showCertificateModal('{{ $attempt->quiz_title }}', '{{ $attempt->user_score }}', '{{ $attempt->quiz_total_score }}', '{{ $attempt->certificate_image }}')"
                                                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 text-indigo-600 hover:text-indigo-800 font-bold text-xs bg-indigo-50 px-4 py-2 rounded-xl transition-all border border-indigo-100 md:border-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    เกียรติบัตร
                                                </button>
                                            @else
                                                <div class="flex justify-between md:block items-center">
                                                    <span class="md:hidden text-[10px] text-slate-400 font-bold uppercase tracking-wider">เกียรติบัตร</span>
                                                    <span class="text-slate-300 text-[10px] italic">ไม่ถึงเกณฑ์ (80%)</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-20 text-center">
                        <p class="text-slate-400 font-medium">ยังไม่มีประวัติการสอบของคุณ</p>
                    </div>
                @endif
            </div>
        </div>

        </div>
    </div>

    {{-- Certificate Modal & Other parts remains same as previous --}}
<div id="certificate-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" onclick="hideCertificateModal()"></div>
        
        <div class="relative w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl transition-all">
            <div class="flex items-center justify-between border-b border-slate-100 bg-white px-8 py-5">
                <h3 class="text-xl font-bold text-slate-800">ตัวอย่างเกียรติบัตร</h3>
                <div class="flex gap-3">
                    <button onclick="downloadPDF()" class="pdf-btn-main flex items-center gap-2 rounded-xl bg-rose-600 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-rose-700 shadow-lg shadow-rose-200">
                        โหลด PDF (HQ)
                    </button>
                    <button onclick="hideCertificateModal()" class="rounded-xl bg-white border border-slate-200 px-6 py-2.5 text-sm font-bold text-slate-600">ปิด</button>
                </div>
            </div>

            <div class="bg-slate-100 p-4 md:p-8 flex justify-center items-center">
                <div id="cert-render-holder" class="w-full shadow-2xl border bg-white rounded-lg overflow-hidden">
                    <canvas id="cert-canvas" style="width: 100%; height: auto; display: block;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-link').forEach(el => {
                el.classList.remove('text-indigo-600');
                el.classList.add('text-slate-400');
                const underline = el.querySelector('.tab-underline');
                if (underline) underline.remove();
            });

            document.getElementById(`tab-${tabName}`).classList.remove('hidden');
            const activeBtn = document.getElementById(`tab-btn-${tabName}`);
            activeBtn.classList.remove('text-slate-400');
            activeBtn.classList.add('text-indigo-600');
            activeBtn.insertAdjacentHTML('beforeend', '<span class="tab-underline absolute bottom-0 left-0 w-full h-1 bg-indigo-600 rounded-t-full"></span>');
        }

        // --- Improved Table Sorting ---
        function sortTable(n) {
            let table = document.getElementById("historyTable");
            let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            switching = true;
            dir = "asc";
            while (switching) {
                switching = false;
                rows = table.rows;
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    
                    let xVal = x.innerText.toLowerCase().trim();
                    let yVal = y.innerText.toLowerCase().trim();

                    // Check if numeric
                    let xNum = parseFloat(xVal.replace(/[^\d.-]/g, ''));
                    let yNum = parseFloat(yVal.replace(/[^\d.-]/g, ''));

                    if (!isNaN(xNum) && !isNaN(yNum) && n === 2) { // Score column
                        if (dir == "asc" ? xNum > yNum : xNum < yNum) {
                            shouldSwitch = true; break;
                        }
                    } else {
                        if (dir == "asc" ? xVal > yVal : xVal < yVal) {
                            shouldSwitch = true; break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }


let currentCertData = null;

async function renderCertificate(canvasId, data) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext('2d');
    
    // 1. ตั้งขนาด A4 แนวนอน (Pixels @ 96-300 DPI scale)
    canvas.width = 1123;
    canvas.height = 794;

    // 2. เตรียมรูปภาพ
    const imgBg = new Image();
    const imgLogo = new Image();
    
    // ตั้งค่า CORS เพื่อให้สามารถดึงรูปจาก URL อื่นมาวาดลง PDF ได้โดยไม่ติด Security Error
    imgBg.crossOrigin = "anonymous";
    imgLogo.crossOrigin = "anonymous";

    // แหล่งที่มารูปภาพ
    imgBg.src = data.base64;
    imgLogo.src = '{{ asset("storage/logo.png") ?? asset("storage/olislogo.png") }}';

    return new Promise((resolve) => {
        let loadedCount = 0;
        const checkLoaded = () => {
            loadedCount++;
            if (loadedCount === 2) draw();
        };

        imgBg.onload = checkLoaded;
        imgLogo.onload = checkLoaded;
        
        // กรณีโหลดโลโก้ไม่สำเร็จ ให้ทำงานต่อโดยไม่ค้าง
        imgLogo.onerror = () => {
            console.warn("Logo failed to load, proceeding with layout only.");
            checkLoaded();
        };
        imgBg.onerror = () => {
            console.error("Background image failed to load.");
            resolve();
        };

        function draw() {
            // --- เริ่มการวาด ---
            // 1. วาดพื้นหลัง
            ctx.drawImage(imgBg, 0, 0, canvas.width, canvas.height);

            const centerX = canvas.width / 2;
            const fontFamily = "'Prompt', sans-serif";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            // 2. วาด Logo (กึ่งกลางบรรทัดแรกสุด)
            const logoSize = 100; // ขนาดโลโก้
            const logoY = 85;    // ตำแหน่งความสูงของโลโก้
            if (imgLogo.complete && imgLogo.naturalWidth > 0) {
                ctx.drawImage(imgLogo, centerX - (logoSize / 2), logoY - (logoSize / 2), logoSize, logoSize);
            }

            // 3. ส่วนหัวข้อ (ขยับระยะลงมาเพื่อหลบโลโก้)
            ctx.font = `bold 60px ${fontFamily}`;
            ctx.fillStyle = "#1e3a8a"; 
            ctx.fillText("ประกาศนียบัตร", centerX, 210);

            ctx.font = `bold 24px ${fontFamily}`;
            ctx.fillText("ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอ{{ config('app.name_district') }}", centerX, 265);

            // 4. ส่วนชื่อผู้รับ
            ctx.font = `24px ${fontFamily}`;
            ctx.fillStyle = "#4338ca";
            ctx.fillText("ให้ไว้เพื่อแสดงว่า", centerX, 310);

            ctx.font = `bold 44px ${fontFamily}`;
            ctx.fillStyle = "#1e293b";
            ctx.fillText(data.name, centerX, 400);
            
            // เส้นใต้ชื่อ
            // ctx.beginPath();
            // ctx.strokeStyle = "#cbd5e1";
            // ctx.lineWidth = 2;
            // ctx.moveTo(centerX - 250, 480);
            // ctx.lineTo(centerX + 250, 480);
            // ctx.stroke();

            // 5. รายละเอียดแบบทดสอบ
            ctx.font = `30px ${fontFamily}`;
            ctx.fillStyle = "#334155";
            ctx.fillText("ได้ผ่านการทำแบบทดสอบ", centerX, 500);

            ctx.font = `900 34px ${fontFamily}`;
            ctx.fillStyle = "#1e3a8a";
            ctx.fillText(`“ ${data.title} ”`, centerX, 570);

            // 6. ส่วนวันที่ (เว้นระยะบรรทัดให้โปร่งขึ้น)
            ctx.font = `24px ${fontFamily}`;
            ctx.fillStyle = "#64748b";
            ctx.fillText(`ให้ไว้ ณ วันที่ ${data.date}`, centerX, 630);

            // 7. พื้นที่ลงนามและคะแนน (ชิดขอบล่าง พร้อมเว้นที่ลงนาม)
            const marginSide = 140; 
            const footerY = 750; // บรรทัดล่างสุด

            // ฝั่งซ้าย: คะแนน
            ctx.textAlign = "left";
            ctx.font = `bold 14px ${fontFamily}`;
            ctx.fillStyle = "#94a3b8";
            ctx.fillText("ผลคะแนนที่ได้", marginSide, footerY - 35);
            
            ctx.font = `900 48px ${fontFamily}`;
            ctx.fillStyle = "#ffffff";
            ctx.fillText(data.score, marginSide, footerY);
            
            ctx.font = `24px ${fontFamily}`;
            ctx.fillText(` / ${data.total}`, marginSide + ctx.measureText(data.score).width + 5, footerY);

            // ฝั่งขวา: พื้นที่ลงนาม (เว้นช่องว่างระหว่าง "วันที่" กับ "ผู้บริหาร" ไว้ให้เซ็น)
            ctx.textAlign = "center";
            ctx.beginPath(); 
            ctx.strokeStyle = "#94a3b8";
            ctx.lineWidth = 2;
            ctx.moveTo(canvas.width - marginSide - 280, footerY - 10);
            ctx.lineTo(canvas.width - marginSide, footerY - 10);
            ctx.stroke();

            ctx.font = `bold 22px ${fontFamily}`;
            ctx.fillStyle = "#334155";
            ctx.fillText("ผู้บริหารสถานศึกษา", canvas.width - marginSide - 140, footerY + 15);

            // สิ้นสุดการวาด
            resolve();
        }
    });
}

async function showCertificateModal(title, score, total, bgUrl) {
    const modal = document.getElementById('certificate-modal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    try {
        const response = await fetch(`/get-cert-base64?url=${encodeURIComponent(bgUrl)}`);
        const result = await response.json();
        
        currentCertData = {
            base64: result.base64,
            name: "{{ auth()->user()->name }}",
            title: title,
            score: score,
            total: total,
            date: new Date().toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric' })
        };

        // วาดลง Canvas ทันทีที่ข้อมูลพร้อม
        await renderCertificate('cert-canvas', currentCertData);

    } catch (e) {
        console.error("Render Error:", e);
    }
}

async function downloadPDF() {
    if (!currentCertData) return;
    
    const btn = document.querySelector('.pdf-btn-main');
    btn.innerText = 'กำลังส่งออก...';
    btn.disabled = true;

    try {
        const canvas = document.getElementById('cert-canvas');
        const imgData = canvas.toDataURL('image/jpeg', 1.0);
        
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'px',
            format: [1123, 794]
        });

        pdf.addImage(imgData, 'JPEG', 0, 0, 1123, 794);
        pdf.save(`Certificate-${currentCertData.name}.pdf`);
    } finally {
        btn.innerText = 'โหลด PDF (HQ)';
        btn.disabled = false;
    }
}

function hideCertificateModal() {
    document.getElementById('certificate-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
                
                document.addEventListener('DOMContentLoaded', () => {
                switchTab('all');
                });


// --- High-Performance Sorting (Fixed Freeze) ---
    let currentSortDir = {}; // เก็บสถานะการ Sort ของแต่ละคอลัมน์

    function sortHistoryTable(n) {
        const table = document.getElementById("historyTable");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.rows);
        
        // กำหนดทิศทาง (Toggle asc/desc)
        let dir = currentSortDir[n] === 'asc' ? 'desc' : 'asc';
        currentSortDir = { [n]: dir }; // Reset คอลัมน์อื่น และเก็บค่าปัจจุบัน

        // ฟังก์ชันเปรียบเทียบ
        const sortedRows = rows.sort((a, b) => {
            let x = a.getElementsByTagName("td")[n];
            let y = b.getElementsByTagName("td")[n];
            let xVal, yVal;

            if (n === 1) { // วันที่
                const xDate = x.querySelector('.sort-data');
                const yDate = y.querySelector('.sort-data');
                xVal = xDate ? xDate.innerText : '';
                yVal = yDate ? yDate.innerText : '';
            } else if (n === 2) { // คะแนน
                xVal = parseFloat(x.innerText.replace(/[^\d.-]/g, '')) || 0;
                yVal = parseFloat(y.innerText.replace(/[^\d.-]/g, '')) || 0;
            } else { // ข้อความทั่วไป
                xVal = x.innerText.toLowerCase().trim();
                yVal = y.innerText.toLowerCase().trim();
            }

            if (dir === 'asc') {
                return xVal > yVal ? 1 : (xVal < yVal ? -1 : 0);
            } else {
                return xVal < yVal ? 1 : (xVal > yVal ? -1 : 0);
            }
        });

        // ลบแถวเก่าและใส่แถวที่เรียงแล้วลงไป (ทำทีเดียวเพื่อประสิทธิภาพ)
        while (tbody.firstChild) tbody.removeChild(tbody.firstChild);
        tbody.append(...sortedRows);

        // Update Icons UI
        updateSortIcons(table, n, dir);
    }

    function updateSortIcons(table, n, dir) {
        const icons = table.querySelectorAll('.sort-icon');
        icons.forEach(icon => {
            icon.innerText = '↕';
            icon.style.opacity = '0.3';
        });
        const currentIcon = table.querySelectorAll('th')[n].querySelector('.sort-icon');
        if (currentIcon) {
            currentIcon.innerText = dir === "asc" ? "↑" : "↓";
            currentIcon.style.opacity = '1';
        }
    }

    // --- Search Filter ---
    function filterHistoryTable() {
        const input = document.getElementById("historySearchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("historyTable");
        const tr = table.querySelector("tbody").getElementsByTagName("tr");

        for (let i = 0; i < tr.length; i++) {
            // ค้นจากชื่อวิชา (คอลัมน์ 0)
            const td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
</script>

<style>
    /* ส่วนของ Modal และการย่อโชว์ */
    .cert-scale-container {
        width: 100%;
        overflow: hidden;
        display: flex;
        justify-content: center;
        background: #f8fafc;
        border-radius: 1rem;
    }

    /* ขนาดจริงของ A4 แนวนอน */
    #certificate-content {
        width: 297mm;
        height: 210mm;
        min-width: 297mm;
        min-height: 210mm;
        background-color: white;
        background-size: 100% 100% !important; /* บังคับพื้นหลังเต็ม */
        background-repeat: no-repeat;
        background-position: center;
        position: relative;
        /* ย่อหน้าจอ Preview */
        transform: scale(0.35); 
        transform-origin: top center;
    }

    /* ปรับขนาดตามหน้าจอผู้ใช้ */
    @media (min-width: 768px) { #certificate-content { transform: scale(0.5); } }
    @media (min-width: 1280px) { #certificate-content { transform: scale(0.65); } }

    /* ป้องกันการแตกของตัวอักษร */
    #certificate-content * {
        -webkit-font-smoothing: antialiased;
    }
</style>
</x-app-layout>