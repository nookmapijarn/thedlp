<x-app-layout>
    <style>
        /* --- [1] ส่วนของหน้าจอปกติ (Web View) --- */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .print-only { display: none; }

        .progress-container { background: rgba(255, 255, 255, 0.1); border-radius: 99px; height: 10px; width: 100%; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 99px; transition: width 1s ease-in-out; }

        /* สไตล์เพิ่มเติมสำหรับตารางผลการเรียน */
        .grade-row:last-child { border-bottom: none; }
        .semester-badge { background: #f1f5f9; color: #475569; padding: 2px 10px; border-radius: 8px; font-size: 11px; font-weight: bold; }

        /* --- [2] ส่วนของการตั้งค่าเครื่องพิมพ์ (คงเดิม) --- */
        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body * { visibility: hidden; }
            .no-print, header, nav, footer { display: none !important; }
            .print-only { display: block !important; visibility: visible !important; position: absolute; left: 0; top: 0; width: 100%; }
            .print-only * { visibility: visible !important; }
            body.print-front-mode .back-side { display: none !important; }
            body.print-back-mode .front-side { display: none !important; }
            @page { size: A4; margin: 0; }
            .page-break { display: flex; justify-content: center; padding-top: 1cm; page-break-after: always; }
            .official-card { width: 8.56cm; height: 5.4cm; border: 0.2pt solid #000; background: white !important; color: black !important; font-family: 'Sarabun', sans-serif; position: relative; box-sizing: border-box; overflow: hidden; }
            .card-content-print { padding: 0.2cm 0.3cm; height: 100%; display: flex; flex-direction: column; }
            .card-header-print { text-align: center; margin-bottom: 2px; }
            .card-header-print p { margin: 0; line-height: 1.2; }
            .card-body-print { display: flex; margin-top: 5px; }
            .photo-box-print { width: 2.1cm; height: 2.7cm; border: 0.5pt solid black; flex-shrink: 0; background: white; }
            .photo-box-print img { width: 100%; height: 100%; object-fit: cover; filter: none !important; }
            .info-box-print { flex-grow: 1; padding-left: 0.2cm; display: flex; flex-direction: column; align-items: center; }
            .digit-row { display: flex; margin-top: 2px; }
            .d-cell { width: 17px; height: 22px; border: 0.5pt solid black; border-right: none; display: flex; align-items: center; justify-content: center; font-size: 10pt; font-weight: bold; }
            .d-cell:last-child { border-right: 0.5pt solid black; }
            .name-display-print { font-size: 11pt; font-weight: bold; margin-top: 10px; text-align: center; }
            .card-footer-print { position: absolute; bottom: 0.3cm; left: 0.3cm; right: 0.3cm; display: flex; justify-content: space-between; font-size: 6.5pt; }
            .sign-underline { width: 1.8cm; border-bottom: 0.5pt solid black; margin: 0 auto 2px auto; }
        }
    </style>

    {{-- 1. ส่วนแสดงผลบนหน้าเว็บ --}}
    <div class="no-print bg-slate-100 min-h-screen pb-12">
        <section class="py-10 px-4">
            <div class="mx-auto max-w-screen-lg ">
                
                <div id="printable-card"
                    class="w-full max-w-3xl mx-auto bg-white shadow-2xl rounded-2xl
                            border border-slate-200 overflow-hidden
                            font-sans select-none mb-8
                            aspect-[1.585/1] flex flex-col relative">

                    {{-- ================= HEADER (คงที่ 25-28%) ================= --}}
                    <div class="h-[25%] bg-gradient-to-r from-purple-800 via-violet-700 to-purple-800
                                relative flex items-center px-6 lg:px-[5%] shrink-0">
                        <div class="relative z-10 flex items-center gap-4 w-full">
                            <div class="w-10 h-10 lg:w-16 lg:h-16 rounded-full bg-white
                                        flex items-center justify-center p-1 shadow-md shrink-0">
                                <img src="https://phothongdlec.ac.th/storage/logo.png"
                                    class="w-full h-full object-contain"
                                    alt="Logo">
                            </div>
                            <div class="text-white truncate">
                                <h1 class="text-sm lg:text-2xl font-bold leading-tight tracking-tight truncate">
                                    บัตรประจำตัวนักศึกษา
                                </h1>
                                <p class="text-purple-300 text-[8px] lg:text-[11px]
                                        font-medium tracking-[0.15em] uppercase opacity-90">
                                    ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ================= BODY (ยืดหยุ่นตามพื้นที่ที่เหลือ) ================= --}}
                    <div class="flex-1 flex flex-row gap-4 lg:gap-[4%] p-4 lg:p-[3%] overflow-hidden">

                        {{-- ===== LEFT : PHOTO (สัดส่วนคงที่) ===== --}}
                        <div class="w-[20%] flex flex-col items-center gap-2 shrink-0">
                        <div class="relative group w-full aspect-[3/4] bg-slate-100 rounded-lg overflow-hidden border-[3px] border-white shadow-[0_8px_20px_rgba(0,0,0,0.1)]">
                            
                            <img src="{{ auth()->user()->avatar ?? 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png' }}"
                                onerror="this.onerror=null;this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png';"
                                class="w-full h-full object-cover"
                                id="profile-avatar">

                            <a href="{{ url("profile") }}" 
                            class="absolute bottom-1 right-1 sm:bottom-3 sm:right-3 bg-white/90 hover:bg-white text-slate-700 sm:p-2 rounded-full shadow-md transition-all duration-200 hover:scale-110 flex items-center justify-center border border-slate-200"
                            title="แก้ไขรูปภาพ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </a>

                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                        </div>

                            <div class="flex flex-col items-center text-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-slate-500 text-[7px] lg:text-[16px] font-bold uppercase tracking-widest">
                                        @foreach($student as $std)
                                            @if($std->FIN_CAUSE=='' || $std->FIN_CAUSE==0 || $std->FIN_CAUSE==null) <span class="text-yellow-700">ศึกษาอยู่</span> @endif
                                            @if($std->FIN_CAUSE==1) <span class="text-green-400">จบหลักสูตร</span> @endif 
                                            @if($std->FIN_CAUSE==2) ลาออก @endif 
                                            @if($std->FIN_CAUSE==3) <span class="text-red-400">หมดสภาพ</span> @endif 
                                            @if($std->FIN_CAUSE==4) พ้นสภาพ @endif 
                                            @if($std->FIN_CAUSE==5) ศึกษาต่อที่อื่น @endif 
                                            @if($std->FIN_CAUSE==6) ศึกษาเพิ่งหลังจบ @endif 
                                            @if($std->FIN_CAUSE==7) จบตกหล่น @endif 
                                            @if($std->FIN_CAUSE==8) อื่นๆ @endif 
                                            @if($std->FIN_CAUSE==9) จบอยู่ระหว่างตรวจสอบวุฒิ @endif 
                                        @endforeach
                                    </span>
                                </div>
                                {{-- <p class="text-[7px] lg:text-[8px] text-slate-400 font-medium">สถานะ</p> --}}
                            </div>
                        </div>

                        {{-- ===== RIGHT : INFORMATION (ใช้ flex-1 เพื่อกินพื้นที่ที่เหลือ) ===== --}}
                        <div class="flex-1 flex flex-col min-w-0 h-full">
                            @foreach($student as $std)
                            <div class="flex-1 flex flex-col justify-between min-w-0">
                                
                                {{-- Name Section --}}
                                <div class="border-b border-slate-100 pb-1 lg:pb-2">
                                    <h2 class="text-base lg:text-2xl font-black text-slate-800 leading-tight truncate">
                                        {{ $std->PRENAME }}{{ $std->NAME }} {{ $std->SURNAME }}
                                    </h2>
                                    <p class="text-purple-600 font-bold text-[8px] lg:text-[10px] tracking-widest uppercase italic">
                                        Student / นักศึกษา
                                    </p>
                                </div>

                                {{-- Data Grid (ปรับให้กระชับขึ้น) --}}
                                <div class="grid grid-cols-2 gap-x-3 gap-y-1 lg:gap-y-2 mt-2">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-slate-400 text-[7px] lg:text-[16px] font-bold uppercase">รหัสประจำตัว</span>
                                        <span class="text-slate-800 font-mono font-bold text-xs lg:text-lg truncate">{{ $std->ID }}</span>
                                    </div>

                                    <div class="flex flex-col min-w-0">
                                        <span class="text-slate-400 text-[7px] lg:text-[16px] font-bold uppercase">เลขบัตรประชาชน</span>
                                        <span class="text-slate-800 font-mono font-bold text-xs lg:text-lg truncate">{{ $std->CARDID ?? 'X-XXXX-XXXXX-XX-X' }}</span>
                                    </div>

                                    <div class="flex flex-col min-w-0">
                                        <span class="text-slate-400 text-[7px] lg:text-[16px] font-bold uppercase">ระดับการศึกษา</span>
                                        <span class="text-slate-700 font-bold text-[12px] lg:text-sm truncate">
                                            @if($lavel==1) ประถม @elseif($lavel==2) มัธยมต้น @elseif($lavel==3) มัธยมปลาย @endif
                                        </span>
                                    </div>

                                    <div class="flex flex-col min-w-0">
                                        <span class="text-slate-400 text-[7px] lg:text-[16px] font-bold uppercase">วันเกิด</span>
                                        {{-- ใช้ parse แทน createFromFormat เพื่อความยืดหยุ่น --}}
                                        @php
                                            $birthdayDisplay = '-';
                                            if (!empty($std->BIRDAY)) {
                                                try {
                                                    // พยายามแปลงวันที่ ถ้า format d/m/Y พัง มันจะลองพยายามเดาจากรูปแบบอื่น
                                                    $birthdayDisplay = \Carbon\Carbon::parse(str_replace('/', '-', $std->BIRDAY))
                                                        ->addYears(543)
                                                        ->locale('th')
                                                        ->isoFormat('D MMM YY');
                                                } catch (\Exception $e) {
                                                    // ถ้าแปลงไม่สำเร็จจริงๆ ให้โชว์ข้อมูลดิบไปก่อน หน้าเว็บจะได้ไม่ล่ม
                                                    $birthdayDisplay = $std->BIRDAY; 
                                                }
                                            }
                                        @endphp

                                        <span class="text-slate-700 font-bold text-[12px] lg:text-sm truncate">
                                            {{ $birthdayDisplay }}
                                        </span>
                                    </div>

                                </div>

                                {{-- ===== FOOTER (ยึดติดขอบล่างของ Body) ===== --}}
                                <div class="flex items-end justify-between mt-auto border-t border-slate-50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 lg:w-12 lg:h-12 bg-white p-0.5 border border-slate-200 rounded shrink-0">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $std->ID }}"
                                                class="w-full h-full object-contain">
                                        </div>
                                        <div class="hidden lg:flex flex-col">
                                            <span class="text-[8px] lg:text-sm text-slate-400 font-bold">SCAN TO ID</span>
                                            <span class="text-[8px] lg:text-sm font-mono text-slate-500 font-bold">{{ $std->ID }}</span>
                                        </div>
                                    </div>

                                    <div class="text-center shrink-0">
                                        <div class="h-5 flex items-end justify-center">
                                            <p class="text-[8px] lg:text-sm text-indigo-900 font-serif italic opacity-40 leading-none">Authorized Signature</p>
                                        </div>
                                        <div class="h-[1px] w-20 lg:w-28 bg-slate-300 my-0.5"></div>
                                        <p class="text-[8px] lg:text-sm text-slate-400 font-bold uppercase">ผู้บริหารสถานศึกษา</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap justify-center gap-4 no-print">
                    <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-full text-xs font-bold transition-all shadow-lg active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        พิมพ์บัตรประจำตัว
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    {{-- [ส่วนเดิม] Radar Chart --}}
                    @if(isset($grade_analyze))
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-8 relative z-10">
                        <div class="w-1.5 h-8 bg-indigo-400 rounded-full"></div>
                        <h3 class="font-black  uppercase tracking-wider text-sm">คะแนนเฉลี่ยกลุ่มสาระ</h3>
                    </div>
                    <div class="max-w-xs mx-auto"><canvas id="gradeChart"></canvas></div>
                        <p class="text-xs">
                            5 กลุ่มสาระ หลักสูตร กศน. 2551 :
                            ทักษะการเรียนรู้: เน้น "เรียนรู้อื่นๆ เป็น" (เรียนรู้ด้วยตนเอง, คิดเป็น, จัดการความรู้)
                            ความรู้พื้นฐาน: วิชาหลักเพื่อเรียนต่อ (ไทย, อังกฤษ, คณิต, วิทย์)
                            การประกอบอาชีพ: ช่องทางทำกิน ทักษะงาน และการบริหารจัดการอาชีพ
                            ทักษะการดำเนินชีวิต: สุขภาพกาย-ใจ, ความปลอดภัย และคุณธรรม
                            การพัฒนาสังคม: หน้าที่พลเมือง, ประวัติศาสตร์ และสิ่งแวดล้อม
                        </p>
                    </div>
                    @endif
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/60 border border-slate-100 max-w-2xl mx-auto w-full">
                        
                        <div class="flex items-center gap-3 mb-8 relative z-10">
                            <div class="w-1.5 h-8 bg-indigo-400 rounded-full"></div>
                            <h3 class="font-black uppercase tracking-wider text-sm">สรุปภาพรวมการเรียน</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                            
                            <div class="flex items-center p-4 bg-slate-50 rounded-3xl border border-slate-100 group transition-all hover:bg-white hover:shadow-lg hover:shadow-blue-500/10"
                                x-data="{ circumference: 45 * 2 * Math.PI, percent: {{$credit_percent}} }">
                                <div class="relative flex items-center justify-center w-20 h-20">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle class="text-slate-200" stroke-width="6" stroke="currentColor" fill="transparent" r="35" cx="40" cy="40" />
                                        <circle class="text-blue-600 transition-all duration-1000" stroke-width="6" 
                                                :stroke-dasharray="circumference" 
                                                :stroke-dashoffset="circumference - percent / 100 * circumference" 
                                                stroke-linecap="round" stroke="currentColor" fill="transparent" r="35" cx="40" cy="40" />
                                    </svg>
                                    <span class="absolute text-sm font-bold text-slate-700" x-text="`${percent}%`"></span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">หน่วยกิต</p>
                                    <p class="text-sm font-black text-blue-600 leading-tight">{{$credit}} <span class="text-xs text-slate-400 font-medium">/ {{$allcredit}}</span></p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-slate-50 rounded-3xl border border-slate-100 group transition-all hover:bg-white hover:shadow-lg hover:shadow-amber-500/10"
                                x-data="{ circumference: 45 * 2 * Math.PI, percent: {{($act_sum*100)/200}} }">
                                <div class="relative flex items-center justify-center w-20 h-20">
                                    <svg class="w-full h-full transform -rotate-90">
                                        <circle class="text-slate-200" stroke-width="6" stroke="currentColor" fill="transparent" r="35" cx="40" cy="40" />
                                        <circle class="text-amber-500 transition-all duration-1000" stroke-width="6" 
                                                :stroke-dasharray="circumference" 
                                                :stroke-dashoffset="circumference - percent / 100 * circumference" 
                                                stroke-linecap="round" stroke="currentColor" fill="transparent" r="35" cx="40" cy="40" />
                                    </svg>
                                    <span class="absolute text-sm font-bold text-slate-700" x-text="`${percent}%`"></span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">กพช.</p>
                                    <p class="text-sm font-black text-amber-500 leading-tight">{{$act_sum}} <span class="text-xs text-slate-400 font-medium">/ 200</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-center mb-12 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em] mb-3">ระดับคุณธรรม</p>
                            <div class="flex gap-1">
                                @for ($i = 0; $i < 4; $i++)
                                    <svg class="w-7 h-7 {{ ($moral != null && $moral >= $i) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase">ระยะเวลาเรียน</span>
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold">เทอมปัจจุบัน: {{$timelerning/10}}</span>
                            </div>
                            
                            <div class="relative h-12">
                                <div class="absolute inset-0 top-4 h-3 bg-slate-100 rounded-full"></div>
                                
                                <div class="absolute inset-0 top-4 h-3 w-full flex overflow-hidden rounded-full">
                                    <div class="h-full bg-emerald-400/20 border-r border-emerald-500/30" style="width: 40%"></div>
                                    <div class="h-full bg-amber-400/20 border-r border-amber-500/30" style="width: 60%"></div>
                                    <div class="h-full bg-red-400/20" style="width: 5%"></div>
                                </div>

                                <div x-data="{ width: 0 }" x-init="setTimeout(() => width = {{$timelerning}}, 200)"
                                    class="absolute top-4 h-3 bg-indigo-600 rounded-full transition-all duration-[1500ms] shadow-lg shadow-indigo-200"
                                    :style="`width: ${width}%`" >
                                    <div class="absolute -right-2 top-1/2 -translate-y-1/2 w-4 h-4 bg-white border-4 border-indigo-600 rounded-full"></div>
                                </div>

                                <div class="absolute top-9 inset-x-0 flex justify-between px-1 text-[9px] font-bold uppercase tracking-tighter">
                                    <span class="text-emerald-600">จบปกติ (1-4)</span>
                                    <span class="text-amber-600">ล่าช้า (5-10)</span>
                                    <span class="text-red-500">หมดสภาพ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    {{-- ส่วนที่ 1: ผลการเรียนรายภาค --}}
                    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-100" x-data="{ activeTab : '{{ $semestrylist1->first()->SEMESTRY ?? '' }}' }">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-8 bg-indigo-600 rounded-full"></div>
                            <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm">ผลการเรียนรายภาค</h3>
                        </div>

                        <div class="flex overflow-x-auto gap-2 pb-4 mb-4 snap-x">
                            @foreach($semestrylist1 as $semestry)
                            <button @click="activeTab = '{{$semestry->SEMESTRY}}'" 
                                    :class="activeTab == '{{$semestry->SEMESTRY}}' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-slate-50 text-slate-500 hover:bg-slate-200'" 
                                    class="shrink-0 px-5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-300 snap-start">
                                ภาคเรียนที่ {{$semestry->SEMESTRY}}
                            </button>
                            @endforeach
                        </div>

                        <div class="space-y-3 max-h-[450px] overflow-y-auto pr-1">
                            @foreach($grade as $g)
                            <div x-show="activeTab == '{{$g['semestry']}}'" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95"
                                class="group bg-slate-50 hover:bg-white p-4 rounded-3xl border border-transparent hover:border-indigo-100 transition-all hover:shadow-md flex items-center justify-between">
                                
                                <div class="flex items-center gap-4">
                                    <div class="bg-white text-indigo-600 w-12 h-12 flex flex-col items-center justify-center rounded-2xl shadow-sm border border-slate-100 group-hover:border-indigo-200 shrink-0">
                                        <span class="text-[8px] font-bold text-slate-400 leading-none mb-1">รหัส</span>
                                        <span class="text-[10px] font-black">{{$g['sub_code']}}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-700 leading-tight group-hover:text-indigo-900 transition-colors">{{$g['sub_name']}}</h4>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $g['grade'] > 0 && $g['grade'] != 'ข' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                                            <span class="text-[10px] font-bold {{ $g['grade'] > 0 && $g['grade'] != 'ข' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $g['grade'] > 0 && $g['grade'] != 'ข' ? 'ผ่านการประเมิน' : 'ยังไม่ผ่าน' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 ml-4">
                                    <div class="text-right hidden sm:block">
                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">Grade</p>
                                    </div>
                                    <div class="w-10 h-10 flex items-center justify-center rounded-xl {{ $g['grade'] > 0 && $g['grade'] != 'ข' ? 'bg-indigo-100/50' : 'bg-rose-100/50' }}">
                                        <span class="text-lg font-black {{ $g['grade'] > 0 && $g['grade'] != 'ข' ? 'text-indigo-600' : 'text-rose-500' }}">
                                            {{ $g['grade'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ส่วนที่ 2: เกณฑ์การจบหลักสูตร --}}
                    <div class="bg-purple-900 rounded-[2.5rem] p-6 sm:p-8 shadow-xl shadow-slate-300 relative overflow-hidden text-slate-200">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                        
                        <div class="flex items-center gap-3 mb-8 relative z-10">
                            <div class="w-1.5 h-8 bg-indigo-400 rounded-full"></div>
                            <h3 class="font-black text-white uppercase tracking-wider text-sm">เกณฑ์การจบหลักสูตร</h3>
                        </div>

                        <div class="space-y-6 relative z-10">
                            <div class="flex gap-4">
                                <div class="flex-none w-8 h-8 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-black text-sm border border-indigo-500/30">1</div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-white mb-3">เรียนครบหน่วยกิตตามโครงสร้าง</h4>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="bg-slate-800/50 p-2 rounded-xl border border-slate-700 text-center">
                                            <p class="text-[9px] text-slate-200 uppercase font-bold">ประถมศึกษา</p>
                                            <p class="text-xs font-black text-indigo-400">48 นก.</p>
                                        </div>
                                        <div class="bg-slate-800/50 p-2 rounded-xl border border-slate-700 text-center">
                                            <p class="text-[9px] text-slate-200 uppercase font-bold">ม.ต้น</p>
                                            <p class="text-xs font-black text-indigo-400">56 นก.</p>
                                        </div>
                                        <div class="bg-slate-800/50 p-2 rounded-xl border border-slate-700 text-center">
                                            <p class="text-[9px] text-slate-200 uppercase font-bold">ม.ปลาย</p>
                                            <p class="text-xs font-black text-indigo-400">76 นก.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-none w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-black text-sm border border-emerald-500/30">2</div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">กิจกรรมพัฒนาคุณภาพชีวิต (กพช.)</h4>
                                    <p class="text-xs text-slate-200 mt-1">สะสมชั่วโมงกิจกรรม <span class="text-emerald-400 font-bold">ไม่น้อยกว่า 200 ชั่วโมง</span></p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-none w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center text-amber-400 font-black text-sm border border-amber-500/30">3</div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">การประเมินคุณธรรม</h4>
                                    <p class="text-xs text-slate-200 mt-1">ผ่านเกณฑ์การประเมินในระดับ <span class="text-amber-400 font-bold">"พอใช้"</span> ขึ้นไป</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-none w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center text-rose-400 font-black text-sm border border-rose-500/30">4</div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">ทดสอบระดับชาติ (N-NET)</h4>
                                    <p class="text-xs text-slate-200 mt-1">ต้องเข้าสอบ <span class="text-rose-400 font-bold">N-NET</span> ในภาคเรียนสุดท้าย</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                            <p class="text-[10px] text-slate-200 font-medium tracking-wide italic leading-relaxed">
                                * ข้อมูลเบื้องต้นตามหลักสูตร กศน. 2551 <br>
                                กรุณาตรวจสอบสถานะปัจจุบันกับงานทะเบียนสถานศึกษา
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    </div>

    {{-- 2. ส่วนพิมพ์ (คงเดิม) --}}
    <div id="print-area" class="print-only">
        @foreach($student as $std)
        <div class="page-break front-side">
            <div class="official-card">
                <div class="card-content-print">
                    <div class="card-header-print">
                        <p style="font-size: 8pt; font-weight: bold;">บัตรประจำตัวนักศึกษาระดับ @if($lavel==1)ประถมศึกษา @elseif($lavel==2)มัธยมศึกษาตอนต้น @elseif($lavel==3)มัธยมศึกษาตอนปลาย @endif</p>
                        <p style="font-size: 7pt;">ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง (กลุ่ม {{$std->GRP_CODE}})</p>
                    </div>
                    <div class="card-body-print">
                        <div class="photo-box-print"><img src="{{ auth()->user()->avatar }}"></div>
                        <div class="info-box-print">
                            <div style="font-size: 12pt; font-style: italic; width: 100%; text-align: center;">วิธีเรียน กศน. แบบพบกลุ่ม</div>
                            <div style="font-size: 8pt; margin-top: 2px;">รหัสประจำตัวนักศึกษา</div>
                            <div class="digit-row">
                                @php $id_split = str_split(str_pad($std->ID, 10, "0", STR_PAD_LEFT)); @endphp
                                @foreach($id_split as $char)<div class="d-cell">{{ $char }}</div>@endforeach
                            </div>
                            <div class="name-display-print">{{$std->PRENAME}}{{$std->NAME}} {{$std->SURNAME}}</div>
                        </div>
                    </div>
                    <div class="card-footer-print">
                        <div class="text-center"><div class="sign-underline"></div>ลายมือชื่อนักศึกษา</div>
                        <div class="text-center"><div class="sign-underline"></div>หัวหน้าสถานศึกษาลงนาม</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-break back-side">
            <div class="official-card">
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="relative mb-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{$std->ID}}" class="w-24 h-24">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <img src="https://phothongdlec.ac.th/storage/logo.png" class="w-5 h-5 bg-white p-0.5">
                        </div>
                    </div>
                    <p class="font-bold tracking-[0.2em]">{{$std->ID}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function printCard(side) {
            document.body.classList.remove('print-front-mode', 'print-back-mode');
            document.body.classList.add(side === 'front' ? 'print-front-mode' : 'print-back-mode');
            window.print();
        }

        const ctx = document.getElementById('gradeChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['ทักษะการเรียนรู้', 'ความรู้พื้นฐาน', 'การประกอบอาชีพ', 'ทักษะการดำเนินชีวิต', 'การพัฒนาสังคม'],
                    datasets: [{
                        label: 'คะแนนเฉลี่ย',
                        data: {!! json_encode($grade_analyze ?? []) !!},
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgb(79, 70, 229)',
                        pointBackgroundColor: 'rgb(79, 70, 229)',
                    }]
                },
                options: { 
                    scales: { r: { beginAtZero: true, max: 100, ticks: { display: false } } },
                    plugins: { legend: { display: false } }
                }
            });
        }
    </script>
</x-app-layout>