<x-teachers-layout>
    <div class="p-6 bg-[#f8fafc] min-h-screen">
        
        {{-- Search Section --}}
        <div class="mt-14 mb-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 shadow-indigo-100/50">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-2">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        ระบบสืบค้นข้อมูลโปรไฟล์นักศึกษา
                    </h2>
                    <div class="flex gap-2">
                        <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded uppercase">รหัสนักศึกษา</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded uppercase">เลขบัตรประชาชน</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-slate-100 text-slate-500 rounded uppercase">ชื่อ-นามสกุล</span>
                    </div>
                </div>

                <form action="{{ route('tstudentprofile') }}" method="POST" class="max-w-4xl">
                    @csrf
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="student_id" id="student_id" 
                               value="{{ old('student_id') }}" required autofocus
                               class="block w-full p-4 pl-12 text-sm text-slate-900 border border-slate-200 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" 
                               placeholder="ระบุชื่อ, นามสกุล, เลขบัตรประชาชน หรือรหัสนักศึกษา เพื่อค้นหา...">
                        
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-lg text-sm px-8 py-2 transition-all shadow-md">
                            ค้นหา
                        </button>
                    </div>
                    @if($errors->any())
                        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-medium">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- เพิ่มส่วน Suggestion List ตรงนี้ --}}
        @if(isset($suggestions) && count($suggestions) > 1)
        <div class="mb-6 bg-white border-2 border-indigo-100 rounded-2xl shadow-lg overflow-hidden animate-fade-in-down">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex items-center justify-between">
                <h3 class="text-indigo-800 font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                    พบรายชื่อที่ใกล้เคียง {{ count($suggestions) }} รายการ
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 font-bold border-b">
                        <tr>
                            <th class="px-6 py-3">ชื่อ-นามสกุล</th>
                            <th class="px-6 py-3">รหัสนักศึกษา</th>
                            <th class="px-6 py-3">กลุ่ม/ระดับ</th>
                            <th class="px-6 py-3 text-right">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($suggestions as $item)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $item->NAME }} {{ $item->SURNAME }}</div>
                                <div class="text-[10px] text-slate-400">เลขบัตร: {{ $item->CARDID }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono text-indigo-600 font-semibold">{{ $item->ID }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase">
                                    ระดับ {{ $item->level }} | {{ $item->GRP_CODE }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('tstudentprofile') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $item->ID }}">
                                    <input type="hidden" name="confirmed_id" value="{{ $item->ID }}">
                                    <input type="hidden" name="level" value="{{ $item->level }}">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-sm transition-all">
                                        เรียกดูข้อมูล
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Profile Display Section --}}
        @if(count($student_data) > 0)
        <div class="space-y-6">
            {{-- ข้อมูลพื้นฐาน --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-slate-800 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        ข้อมูลทะเบียนประวัตินักศึกษา
                    </h3>
                    <div class="flex gap-2">
                        <button onclick="window.print()" class="text-[10px] bg-slate-700 hover:bg-slate-600 px-3 py-1 rounded transition-colors flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            PRINT
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="flex-shrink-0 mx-auto md:mx-0 text-center">
                            <div class="relative inline-block">
                                <img class="w-40 h-40 rounded-2xl object-cover ring-4 ring-slate-100 shadow-md" src="{{ asset('storage/avatar/unkhonw.png') }}" alt="Student Avatar">
                                <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-white shadow-sm"></div>
                            </div>
                        </div>

<div class="w-full space-y-6">
    @foreach($student_data as $s)
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest px-1">รหัสนักศึกษา</label>
                <div class="flex items-center p-3 bg-slate-50 border border-slate-200 rounded-2xl">
                    <span class="text-indigo-600 mr-2"><i class="fas fa-id-badge"></i></span>
                    <p class="text-slate-900 font-bold tracking-wider">{{ $s->ID }}</p>
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest px-1">เลขประจำตัวประชาชน</label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 font-bold tracking-[0.2em]">
                    {{ $s->CARDID }}
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-5 rounded-2xl border border-indigo-100 mb-6">
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-indigo-400 uppercase">คำนำหน้า</label>
                    <p class="text-lg font-extrabold text-slate-800">{{ $s->PRENAME }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-indigo-400 uppercase">ชื่อ</label>
                    <p class="text-lg font-extrabold text-slate-800">{{ $s->NAME }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-indigo-400 uppercase">นามสกุล</label>
                    <p class="text-lg font-extrabold text-slate-800">{{ $s->SURNAME }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="col-span-2 md:col-span-1 space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase px-1">สถานะนักศึกษา</label>
                @php
                    $statuses = [1=>"จบหลักสูตร", 2=>"ลาออก", 3=>"หมดสภาพ", 4=>"พ้นสภาพ", 5=>"ศึกษาต่อที่อื่น", 6=>"ศึกษาเพิ่งหลังจบ", 7=>"จบตกหล่น", 8=>"อื่นๆ", 9=>"จบอยู่ระหว่างตรวจสอบวุฒิ"];
                    $statusText = $statuses[$s->FIN_CAUSE] ?? "กำลังศึกษา";
                    $statusClass = ($s->FIN_CAUSE == 0 || $s->FIN_CAUSE == '') 
                        ? 'bg-green-100 text-green-700 border-green-200' 
                        : 'bg-amber-100 text-amber-700 border-amber-200';
                @endphp
                <div class="p-3 border rounded-xl font-bold text-center shadow-sm {{ $statusClass }}">
                    {{ $statusText }}
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase px-1">ภาคเรียนที่จบ</label>
                <div class="p-3 bg-white border border-slate-200 rounded-xl font-bold text-center text-slate-700">
                    {{ $s->FIN_SEM ?: '-' }}
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase px-1">เลขที่/ลำดับ</label>
                <div class="p-3 bg-white border border-slate-200 rounded-xl font-bold text-center text-slate-700">
                    {{ $s->TRNRUN ?: '-' }}
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-400 uppercase px-1">กลุ่ม / อายุ</label>
                <div class="p-2.5 bg-slate-800 border border-slate-900 rounded-xl text-white text-sm text-center">
                    <span class="text-indigo-300">{{ $s->GRP_CODE }}</span> 
                    <span class="mx-1 text-slate-500">|</span> 
                    <span>{{ $s->AGE }} ปี</span>
                </div>
            </div>
        </div>

    </div>
    @endforeach
</div>
                    </div>
                </div>
            </div>

            {{-- Course and Activities Grid --}}
@php
    // แยกข้อมูลออกเป็น 2 ชุด
    // เปลี่ยน '1' และ '2' ให้ตรงกับค่า SUB_TYPE ในฐานข้อมูลของคุณ
    $compulsory_subjects = $grade_data->where('SUB_TYPE', '1');
    $elective_subjects = $grade_data->where('SUB_TYPE', '2');

    // คำนวณหน่วยกิตแยกการ์ด (สมมติชื่อคอลัมน์หน่วยกิตคือ CREDIT)
    $sum_compulsory = $compulsory_subjects->sum('SUB_CREDIT');
    $sum_elective = $elective_subjects->sum('SUB_CREDIT');
@endphp

{{-- ปรับ grid เป็น 3 คอลัมน์บนหน้าจอ XL --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    
    {{-- 1. การ์ดวิชาบังคับ (Compulsory) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center uppercase text-sm tracking-wide">
                <span class="w-1.5 h-4 bg-blue-600 rounded-full mr-3"></span>
                วิชาบังคับ
            </h3>
        </div>
        <div class="p-4 flex-grow overflow-y-auto max-h-[350px]">
            <ul class="space-y-3">
                @foreach($compulsory_subjects as $g)
                <li class="flex items-center p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center mr-3 text-blue-600 font-bold text-xs">
                        {{ $g->GRADE }}
                    </div>
                    <div class="flex-grow">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $g->SEMESTRY }} | {{ $g->SUB_CODE }}</p>
                        <p class="text-sm font-bold text-slate-700 line-clamp-1">{{ $g->SUB_NAME }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Compulsory Credits</span>
            <span class="text-xl font-black text-blue-400">{{ $sum_compulsory }} <small class="text-[10px] text-white">นก.</small></span>
        </div>
    </div>

    {{-- 2. การ์ดวิชาเลือก (Elective) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center uppercase text-sm tracking-wide">
                <span class="w-1.5 h-4 bg-purple-500 rounded-full mr-3"></span>
                วิชาเลือก
            </h3>
        </div>
        <div class="p-4 flex-grow overflow-y-auto max-h-[350px]">
            <ul class="space-y-3">
                @foreach($elective_subjects as $g)
                <li class="flex items-center p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center mr-3 text-purple-600 font-bold text-xs">
                        {{ $g->GRADE }}
                    </div>
                    <div class="flex-grow">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $g->SEMESTRY }} | {{ $g->SUB_CODE }}</p>
                        <p class="text-sm font-bold text-slate-700 line-clamp-1">{{ $g->SUB_NAME }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Elective Credits</span>
            <span class="text-xl font-black text-purple-400">{{ $sum_elective }} <small class="text-[10px] text-white">นก.</small></span>
        </div>
    </div>

    {{-- 3. การ์ดกิจกรรม (เดิม) --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center uppercase text-sm tracking-wide">
                <span class="w-1.5 h-4 bg-amber-500 rounded-full mr-3"></span>
                กิจกรรมพัฒนาคุณภาพชีวิต
            </h3>
        </div>
        <div class="p-4 flex-grow overflow-y-auto max-h-[350px]">
            <ul class="space-y-3">
                @foreach($activity_data as $a)
                <li class="flex items-center p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center mr-3 text-amber-600 font-bold text-xs text-center leading-tight">
                        {{ $a->HOUR }}<br><span class="text-[8px] uppercase">ชม.</span>
                    </div>
                    <div class="flex-grow">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $a->SEMESTRY }}</p>
                        <p class="text-sm font-bold text-slate-700 line-clamp-1">{{ $a->ACTIVITY }}</p>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="p-4 bg-slate-900 text-white flex justify-between items-center">
            <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Total Hours</span>
            <span class="text-xl font-black text-amber-400">{{ $sum_act }} <small class="text-[10px] text-white">ชม.</small></span>
        </div>
    </div>

</div>

            {{-- All Grade --}}
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="font-bold text-slate-800 flex items-center uppercase text-sm tracking-wide">
                            <span class="w-1.5 h-4 bg-indigo-500 rounded-full mr-3"></span>
                            ประวัติผลการเรียน
                        </h3>
                        
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" id="gradeSearch" placeholder="ค้นหาชื่อวิชาหรือรหัส..." 
                                class="w-full md:w-72 pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="gradeTable">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="sortable px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider cursor-pointer hover:text-indigo-600 transition-colors text-center" data-sort="grade">
                                        เกรด <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="sortable px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider cursor-pointer hover:text-indigo-600 transition-colors" data-sort="sem">
                                        ภาคเรียน <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="sortable px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider cursor-pointer hover:text-indigo-600 transition-colors" data-sort="code">
                                        รหัสวิชา <span class="sort-icon ml-1">↕</span>
                                    </th>
                                    <th class="sortable px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider cursor-pointer hover:text-indigo-600 transition-colors" data-sort="name">
                                        ชื่อวิชา <span class="sort-icon ml-1">↕</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="gradeBody">
                                @foreach($grade_all as $g)
                                @php
                                    $gradeVal = trim($g->GRADE);
                                    // เงื่อนไขสีตามโจทย์: 0=แดง, ข=ส้ม, อื่นๆ=เขียว/เทา
                                    if ($gradeVal === '0') {
                                        $colorClass = 'bg-red-50 text-red-600 border-red-100 ring-red-500/10';
                                    } elseif ($gradeVal === 'ข') {
                                        $colorClass = 'bg-orange-50 text-orange-600 border-orange-100 ring-orange-500/10';
                                    } elseif (is_numeric($gradeVal) && $gradeVal >= 2) {
                                        $colorClass = 'bg-green-50 text-green-600 border-green-100 ring-green-500/10';
                                    } else {
                                        $colorClass = 'bg-slate-50 text-slate-500 border-slate-100';
                                    }
                                @endphp
                                <tr class="grade-row border-b border-slate-50 hover:bg-indigo-50/30 transition-colors"
                                    data-grade="{{ $g->GRADE }}"
                                    data-sem="{{ $g->SEMESTRY }}"
                                    data-code="{{ $g->SUB_CODE }}"
                                    data-name="{{ $g->SUB_NAME }}">
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl border font-black text-sm shadow-sm ring-1 {{ $colorClass }}">
                                            {{ $g->GRADE }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[11px] font-bold text-indigo-500 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ $g->SEMESTRY }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs font-bold text-slate-400">
                                        {{ $g->SUB_CODE }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-700">
                                        {{ $g->SUB_NAME }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-teachers-layout>
<script>
    const gradeBody = document.getElementById('gradeBody');
    const searchInput = document.getElementById('gradeSearch');
    let currentSort = { column: '', order: 'asc' };

    // 1. ระบบค้นหา (Search)
    searchInput.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('.grade-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    // 2. ระบบ Sort เมื่อคลิกที่ Header
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', () => {
            const column = header.dataset.sort;
            const order = (currentSort.column === column && currentSort.order === 'asc') ? 'desc' : 'asc';
            
            // อัปเดตสถานะการ Sort
            currentSort = { column, order };
            
            // อัปเดต UI Icon
            document.querySelectorAll('.sort-icon').forEach(icon => icon.textContent = '↕');
            header.querySelector('.sort-icon').textContent = order === 'asc' ? '↑' : '↓';

            // ดำเนินการ Sort ข้อมูล
            const rows = Array.from(document.querySelectorAll('.grade-row'));
            rows.sort((a, b) => {
                let valA = a.dataset[column];
                let valB = b.dataset[column];

                // จัดการ Logic การเรียงเกรด (ให้ 0 และ ข อยู่ท้ายสุดเมื่อเรียงจากมากไปน้อย)
                if (column === 'grade') {
                    const getWeight = (v) => {
                        if (v === 'ข') return -2;
                        if (v === '0') return -1;
                        return parseFloat(v) || 0;
                    };
                    return order === 'asc' ? getWeight(valA) - getWeight(valB) : getWeight(valB) - getWeight(valA);
                }

                // การเรียงข้อความทั่วไป (รองรับภาษาไทย)
                return order === 'asc' 
                    ? valA.localeCompare(valB, 'th') 
                    : valB.localeCompare(valA, 'th');
            });

            // แสดงผลใหม่ลงในตาราง
            rows.forEach(row => gradeBody.appendChild(row));
        });
    });
</script>
@include('layouts.footer')