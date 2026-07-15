<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ __('ตารางสอบปลายภาค') }}
        </h6>
    </x-slot>

    <div x-data="{ search: '' }" class="py-6 px-4 sm:px-0">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ส่วนข้อมูลนักศึกษาและตารางสอบ --}}
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-2xl shadow-slate-100 dark:shadow-none rounded-3xl border border-slate-100 dark:border-slate-800/80">
                
                {{-- Header Card --}}
                <div class="p-6 bg-gradient-to-br from-violet-850 via-indigo-900 to-purple-950 text-white rounded-t-3xl relative overflow-hidden">
                    <div class="absolute -top-24 -right-24 w-56 h-56 rounded-full bg-violet-600/20 blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-56 h-56 rounded-full bg-pink-500/10 blur-3xl"></div>
                    
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-yellow-400 text-slate-900 rounded-2xl shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black tracking-tight">ตารางสอบปลายภาค</h3>
                                <p class="text-violet-200 text-xs font-bold mt-0.5">ภาคเรียนที่ {{$semestry}}</p>
                            </div>
                        </div>

                        @foreach($student as $s)
                        <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/20 text-sm w-full lg:w-auto flex items-center gap-4 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-black text-lg">
                                {{ mb_substr($s->NAME, 0, 1) }}
                            </div>
                            <div class="flex-grow">
                                <p class="font-black text-white leading-tight">{{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</p>
                                <p class="text-violet-200 text-xs font-bold mt-1">รหัสนักศึกษา: {{$s->ID}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="p-5 bg-slate-50/50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800/80 flex items-center gap-2">
                    <div class="relative w-full max-w-md">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <input x-model="search" type="text" placeholder="ค้นหารหัสวิชา หรือ ชื่อวิชา..." 
                            class="w-full pl-11 pr-4 py-2.5 border border-slate-200 dark:border-slate-700/80 dark:bg-slate-850 rounded-2xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 focus:outline-none text-sm transition-all shadow-sm placeholder-slate-400 dark:text-white">
                    </div>
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        @if($schedule)
                        <thead class="bg-gradient-to-r from-violet-600 to-indigo-600 dark:from-violet-800 dark:to-indigo-800 text-white text-xs uppercase font-black border-b border-slate-100 dark:border-slate-800/80">
                            <tr>
                                <th class="px-6 py-4 text-center w-20">ลำดับ</th>
                                <th class="px-6 py-4 w-40">รหัสวิชา</th>
                                <th class="px-6 py-4">รายวิชา</th>
                                <th class="px-6 py-4 w-48">วันสอบ</th>
                                <th class="px-6 py-4 w-48">เวลา</th>
                                <th class="px-6 py-4 text-center w-36">ห้องสอบ</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600 dark:text-slate-350 text-sm">
                            @foreach($schedule as $s)
                            <tr x-show="'{{$s['sub_code']}} {{$s['sub_name']}}'.toLowerCase().includes(search.toLowerCase())"
                                x-transition.opacity
                                class="border-b border-slate-50 dark:border-slate-850 hover:bg-violet-50/40 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 text-center text-slate-405 font-bold">{{$loop->iteration}}</td>
                                <td class="px-6 py-4 font-mono font-black text-violet-700 dark:text-violet-400">{{$s['sub_code']}}</td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-200">{{$s['sub_name']}}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-400 rounded-full text-xs font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        {{$s['exam_day'] != 0 ? $s['exam_day'] : '-'}}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-750 dark:text-blue-400 rounded-full text-xs font-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{$s['exam_start'] != 0 ? $s['exam_start'].'-'.$s['exam_end'].' น.' : '-'}}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-400 rounded-full text-xs font-black">
                                        {{$s['exam_room'] ?? '-'}}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <div class="flex flex-col items-center text-slate-400">
                                        <svg class="w-16 h-16 text-slate-350 dark:text-slate-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="text-base font-black text-slate-500">ยังไม่มีตารางสอบปลายภาค</p>
                                        <p class="text-xs text-slate-405 mt-1">ตารางสอบจะปรากฏที่นี่เมื่อสถาบันประกาศสอบอย่างเป็นทางการ</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>

                {{-- Mobile Card List View --}}
                <div class="block md:hidden p-4 space-y-4 bg-slate-50/40 dark:bg-slate-900/40">
                    @if($schedule)
                        @foreach($schedule as $s)
                        <div x-show="'{{$s['sub_code']}} {{$s['sub_name']}}'.toLowerCase().includes(search.toLowerCase())"
                             x-transition.opacity
                             class="bg-white dark:bg-slate-850 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-[0_4px_20px_rgb(0,0,0,0.015)] p-4 relative overflow-hidden transition-all active:scale-[0.985] hover:border-violet-200 dark:hover:border-violet-800 border-l-4 border-l-violet-600">
                             
                             <div class="flex justify-between items-start gap-2 mb-2.5">
                                 <span class="px-2.5 py-1 bg-violet-50 dark:bg-violet-900/20 text-violet-750 dark:text-violet-400 rounded-xl text-[10px] font-black tracking-wider uppercase font-mono leading-none">
                                     {{$s['sub_code']}}
                                 </span>
                                 <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-400 rounded-xl text-[10px] font-black leading-none">
                                     ห้องสอบ: {{$s['exam_room'] ?? '-'}}
                                 </span>
                             </div>

                             <h4 class="text-sm font-black text-slate-800 dark:text-slate-200 leading-snug mb-4">
                                 {{$s['sub_name']}}
                             </h4>

                             <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs">
                                 <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                     <div class="p-1.5 bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 rounded-xl flex-shrink-0">
                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                         </svg>
                                     </div>
                                     <div>
                                         <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase leading-none">วันสอบ</p>
                                         <p class="font-black text-slate-700 dark:text-slate-300 mt-1 leading-none">{{$s['exam_day'] != 0 ? $s['exam_day'] : '-'}}</p>
                                     </div>
                                 </div>
                                 
                                 <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                     <div class="p-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-xl flex-shrink-0">
                                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                         </svg>
                                     </div>
                                     <div>
                                         <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase leading-none">เวลาสอบ</p>
                                         <p class="font-black text-slate-700 dark:text-slate-300 mt-1 leading-none">{{$s['exam_start'] != 0 ? $s['exam_start'].'-'.$s['exam_end'].' น.' : '-'}}</p>
                                     </div>
                                 </div>
                             </div>
                        </div>
                        @endforeach
                    @else
                        <div class="py-12 text-center">
                            <svg class="w-16 h-16 text-slate-300 dark:text-slate-700 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-sm font-black text-slate-500">ยังไม่มีตารางสอบปลายภาค</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- N-NET Section --}}
            @if($nnet === 'N-NET')
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-xl rounded-3xl border border-indigo-100 dark:border-slate-800 transition-all hover:shadow-2xl">
                <div class="p-6 bg-gradient-to-r from-indigo-750 to-violet-850 text-white flex justify-between items-center relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-yellow-400 text-slate-950 rounded-lg text-[9px] font-black tracking-wider uppercase">National Test</span>
                            <h3 class="text-lg font-black italic">N-NET / E-Exam</h3>
                        </div>
                        <p class="text-indigo-200 text-xs mt-1">สิทธิการเข้าสอบระดับชาติ ภาคเรียนที่ {{$semestry}}</p>
                    </div>
                    <span class="px-4 py-1.5 bg-emerald-500 text-white rounded-full text-xs font-black tracking-wide shadow-lg shadow-emerald-500/20 animate-pulse relative z-10">มีสิทธิสอบ</span>
                </div>
                
                <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-2xl border border-amber-100/60 dark:border-amber-900/30 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 font-black tracking-wider uppercase">ประกาศสนามสอบ</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-1 leading-snug">ติดตามประกาศสนามสอบจากทางสถานศึกษาโดยตรง</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">กรุณาเตรียมบัตรประจำตัวประชาชนและบัตรนักศึกษาในวันเข้าสอบ</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Message Alerts for N-NET Status --}}
            @if(in_array($nnet, ['ผ่านแล้ว', 'E-Exam', null]) && $nnet !== 'N-NET')
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-[0_8px_30px_rgb(0,0,0,0.015)] flex items-center justify-center gap-3 text-center">
                @if($nnet === 'ผ่านแล้ว')
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-emerald-700 dark:text-emerald-400 font-black text-sm">ผ่านการทดสอบ N-NET เรียบร้อยแล้ว</p>
                        <p class="text-slate-400 dark:text-slate-500 text-xs mt-0.5">ระบบได้บันทึกประวัติการทดสอบของคุณสำเร็จแล้ว</p>
                    </div>
                @elseif($nnet === 'E-Exam')
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25M19.5 5.25a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25m15 0V15M3 5.25a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 5.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-amber-700 dark:text-amber-400 font-black text-sm">คุณมีรายการสอบแบบ E-Exam</p>
                        <p class="text-slate-400 dark:text-slate-500 text-xs mt-0.5">กรุณาตรวจสอบตารางเวลาและห้องสอบออนไลน์ของคุณอย่างรอบคอบ</p>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-405 font-bold italic text-sm">"คุณยังไม่มีสิทธิสอบ N-NET ในภาคเรียนนี้"</p>
                @endif
            </div>
            @endif

        </div>
    </div>
</x-app-layout>