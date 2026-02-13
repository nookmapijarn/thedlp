<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container max-w-7xl mx-auto space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-600 rounded-2xl text-white shadow-lg shadow-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">รายการแบบทดสอบ</h1>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-tighter">Management Dashboard</p>
                    </div>
                </div>
                <a href="{{ route('ttest.create') }}" class="w-full md:w-auto px-8 py-3.5 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 flex items-center justify-center gap-2 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-90 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    สร้างข้อสอบใหม่
                </a>
            </div>

            {{-- การแจ้งเตือน --}}
            @if (session('success'))
                <div class="bg-emerald-50 border-2 border-emerald-100 text-emerald-600 p-4 rounded-2xl flex items-center gap-3 animate-fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="py-5 px-6 text-left text-xs font-black text-slate-400 uppercase tracking-widest">เปิด/ปิด</th>
                                <th class="py-5 px-6 text-left text-xs font-black text-slate-400 uppercase tracking-widest">วิชา / ชื่อแบบทดสอบ</th>
                                <th class="py-5 px-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">สถิติ</th>
                                <th class="py-5 px-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">เกณฑ์ / เวลา</th>
                                <th class="py-5 px-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($quizzes as $quiz)
                                <tr class="hover:bg-slate-50/80 transition-all group">
                                    <td class="py-5 px-6">
                                        <form action="{{ route('ttest.toggle', $quiz->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="relative inline-flex items-center cursor-pointer transition-all">
                                                <div class="w-11 h-6 {{ $quiz->is_active ? 'bg-emerald-500' : 'bg-slate-300' }} rounded-full transition-colors"></div>
                                                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform transform {{ $quiz->is_active ? 'translate-x-5' : '' }}"></div>
                                            </button>
                                        </form>
                                    </td>
                                    
                                    <td class="py-5 px-6">
                                        <div class="flex flex-col gap-1.5">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-600 font-black rounded-md uppercase tracking-tighter">{{ $quiz->subject_code }}</span>
                                                <div class="flex items-center gap-1">
                                                    @if($quiz->require_location)
                                                        <div class="p-1 bg-indigo-50 text-indigo-500 rounded-md" title="เปิดระบบบังคับ GPS">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                    @if($quiz->require_snapshot)
                                                        <div class="p-1 bg-emerald-50 text-emerald-500 rounded-md" title="เปิดระบบสุ่มถ่ายรูป">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-slate-700 font-bold text-base group-hover:text-indigo-600 transition-colors line-clamp-1 italic-edit">
                                                {{ $quiz->title }}
                                            </span>
                                        </div>
                                    </td>

<td class="py-5 px-6">
    <div class="flex items-center justify-center gap-6">
        {{-- จำนวนข้อ --}}
        <div class="text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-1">จำนวนข้อ</p>
            <p class="text-sm font-black text-slate-700">{{ $quiz->questions_count ?? 0 }}</p>
        </div>
        
        <div class="w-px h-8 bg-slate-100"></div>

        {{-- สถิติการสอบ: เน้นรายคน (Unique Students) --}}
        <div class="text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-1">เข้าสอบ/ผ่าน(คน)</p>
            <p class="text-sm font-black flex items-center justify-center gap-1">
                {{-- จำนวนคนเข้าสอบทั้งหมด (ไม่ซ้ำ) --}}
                <span class="text-blue-600" title="จำนวนนักเรียนที่เข้าสอบ (รายคน)">
                    {{ $quiz->unique_students_count ?? 0 }}
                </span>
                
                <span class="text-slate-300 mx-0.5">/</span>
                
                {{-- จำนวนคนที่สอบผ่านอย่างน้อย 1 ครั้ง --}}
                <span class="text-emerald-600" title="จำนวนนักเรียนที่สอบผ่าน (รายคน)">
                    {{ $quiz->unique_passed_count ?? 0 }}
                </span>
                
                <span class="text-[10px] text-slate-400 font-bold ml-1">คน</span>
            </p>
        </div>

        <div class="w-px h-8 bg-slate-100"></div>

        {{-- ร้อยละผู้ผ่าน --}}
        <div class="text-left min-w-[100px]">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter mb-1">ร้อยละผู้ผ่าน</p>
            <div class="flex items-center gap-2">
                <span class="text-sm font-black {{ ($quiz->pass_rate ?? 0) >= 50 ? 'text-emerald-600' : 'text-rose-500' }}">
                    {{ number_format($quiz->pass_rate ?? 0, 1) }}%
                </span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden">
                <div class="h-full transition-all duration-500 {{ ($quiz->pass_rate ?? 0) >= 50 ? 'bg-emerald-500' : 'bg-rose-500' }}" 
                    style="width: {{ $quiz->pass_rate ?? 0 }}%"></div>
            </div>
        </div>
    </div>
</td>

                                    <td class="py-5 px-6 text-center">
                                        <div class="inline-flex items-center gap-2 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-xl text-slate-600 font-black text-xs">
                                            <span title="เกณฑ์ผ่าน">{{ $quiz->pass_percentage }}%</span>
                                            <span class="text-slate-300">|</span>
                                            <span title="เวลาที่ใช้">{{ $quiz->time_limit }}'</span>
                                        </div>
                                    </td>

                                    <td class="py-5 px-6 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('ttest.report.summary', $quiz->id) }}" class="p-2.5 bg-white border border-slate-200 text-green-500 hover:bg-amber-50 rounded-xl transition-all shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('ttest.assign', $quiz->id) }}" class="p-2.5 bg-white border border-slate-200 text-blue-500 hover:bg-amber-50 rounded-xl transition-all shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('ttest.edit', $quiz->id) }}" class="p-2.5 bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 rounded-xl transition-all shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('ttest.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-2.5 bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 rounded-xl transition-all shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-teachers-layout>