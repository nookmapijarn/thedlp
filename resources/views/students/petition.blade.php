<x-app-layout>
    <div class="animate-in fade-in duration-500 text-slate-800 dark:text-slate-100 pb-16">
        
        <!-- Top Title Bar -->
        <div class="bg-gradient-to-r from-purple-700 via-purple-800 to-indigo-900 text-white pt-8 pb-12 px-6 sm:px-12 rounded-b-[2rem] relative">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight leading-none text-white">คำร้องออนไลน์</h1>
                    <p class="text-purple-300 text-[10px] font-bold tracking-[0.15em] uppercase mt-1.5 opacity-90">
                        Online Student Petitions & Request Portal
                    </p>
                </div>
                <a href="{{ route('home') }}" class="p-2.5 bg-white/10 hover:bg-white/20 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-6 mt-6" x-data="{ activeTab: 'history' }">
            
            <!-- Success/Error Alert -->
            @if(session('success'))
                <div class="mb-5 p-4.5 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/30 text-emerald-800 dark:text-emerald-400 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-xs font-black">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Tabs Toggles -->
            <div class="flex bg-slate-100 dark:bg-gray-800 p-1.5 rounded-2xl mb-6">
                <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'bg-white dark:bg-gray-700 text-purple-750 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400'"
                        class="flex-1 py-3 text-center rounded-xl text-xs font-black transition-all">
                    ประวัติการยื่นคำร้อง
                </button>
                <button @click="activeTab = 'new'" 
                        :class="activeTab === 'new' ? 'bg-white dark:bg-gray-700 text-purple-750 dark:text-white shadow-sm' : 'text-slate-500 dark:text-slate-400'"
                        class="flex-1 py-3 text-center rounded-xl text-xs font-black transition-all">
                    ยื่นคำร้องใหม่
                </button>
            </div>

            <!-- Tab Content: History List -->
            <div x-show="activeTab === 'history'" x-transition:enter="transition-all duration-300">
                <div class="space-y-4">
                    @forelse($petitions as $petition)
                        <div class="bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700/80 rounded-2xl p-5 shadow-sm space-y-4">
                            <!-- Header Info -->
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <span class="inline-flex px-2 py-0.5 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 text-[9px] font-black rounded-lg uppercase tracking-wider">
                                        {{ $petition->type }}
                                    </span>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white">{{ $petition->title }}</h4>
                                    <span class="text-[10px] text-slate-400 font-bold block">
                                        ยื่นเมื่อ: {{ $petition->created_at->addYears(543)->locale('th')->isoFormat('D MMM YYYY, HH:mm') }} น.
                                    </span>
                                </div>

                                <!-- Status Badge -->
                                @if($petition->status == 'pending')
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 text-[10px] font-black rounded-xl border border-amber-200/40">
                                        รอดำเนินการ
                                    </span>
                                @elseif($petition->status == 'in_progress')
                                    <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 text-[10px] font-black rounded-xl border border-blue-200/40">
                                        กำลังดำเนินการ
                                    </span>
                                @elseif($petition->status == 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black rounded-xl border border-emerald-200/40">
                                        อนุมัติแล้ว
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-[10px] font-black rounded-xl border border-rose-200/40">
                                        ปฏิเสธคำร้อง
                                    </span>
                                @endif
                            </div>

                            <!-- Description -->
                            <div class="bg-slate-50 dark:bg-gray-900/60 p-4 rounded-xl text-xs text-slate-600 dark:text-slate-350 leading-relaxed font-medium">
                                {{ $petition->description }}
                            </div>

                            <!-- Document Attachment link -->
                            @if($petition->file_path)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                    </svg>
                                    <a href="{{ asset('storage/' . $petition->file_path) }}" target="_blank" class="text-xs font-black text-purple-600 hover:underline">
                                        เปิดดูเอกสารแนบประกอบ
                                    </a>
                                </div>
                            @endif

                            <!-- Admin comment block -->
                            @if($petition->admin_comment)
                                <div class="border-t border-slate-100 dark:border-slate-700/80 pt-3">
                                    <span class="text-[9px] uppercase tracking-wider font-extrabold text-purple-700 dark:text-purple-400 block mb-1">ความคิดเห็นจากผู้ตรวจสอบ/ผู้บริหาร</span>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 bg-purple-50/50 dark:bg-purple-950/15 p-3 rounded-xl">
                                        {{ $petition->admin_comment }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700/80 rounded-2xl">
                            <span class="text-4xl">✉️</span>
                            <h5 class="text-sm font-black text-slate-800 dark:text-white mt-3">ยังไม่มีประวัติคำร้องของคุณ</h5>
                            <p class="text-xs text-slate-400 font-bold mt-1">หากมีเรื่องต้องการติดต่อสอบถามหรือยื่นคำร้อง ให้คลิกแถบ "ยื่นคำร้องใหม่"</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Content: New Request Form -->
            <div x-show="activeTab === 'new'" x-transition:enter="transition-all duration-300" style="display: none;">
                <form action="{{ route('คำร้องออนไลน์.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 border border-slate-150 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-6">
                    @csrf
                    
                    <!-- 1. ประเภทคำร้อง -->
                    <div class="space-y-2">
                        <label for="type" class="text-xs font-black text-slate-700 dark:text-slate-300 block">ประเภทคำร้อง</label>
                        <select name="type" id="type" required class="w-full text-xs font-bold bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-purple-500 focus:border-purple-500">
                            <option value="คำร้องทั่วไป">คำร้องทั่วไป (General Petition)</option>
                            <option value="คำร้องขอใบรับรอง">คำร้องขอใบรับรองผลการเรียน (Enrollment Certificate)</option>
                            <option value="คำร้องขอพักการเรียน">คำร้องขอพักการเรียน (Leave of Absence)</option>
                            <option value="คำร้องขอเปิดรายวิชา">คำร้องขอเปิดรายวิชาเพิ่มเติม (Course Request)</option>
                            <option value="คำร้องขอลาออก">คำร้องขอลาออกสถานะ (Resignation Request)</option>
                        </select>
                    </div>

                    <!-- 2. หัวข้อเรื่อง -->
                    <div class="space-y-2">
                        <label for="title" class="text-xs font-black text-slate-700 dark:text-slate-300 block">หัวข้อ/เรื่องที่ต้องการยื่น</label>
                        <input type="text" name="title" id="title" required placeholder="ระบุหัวข้อเรื่องสั้นๆ เช่น ขอใบรับรองประพฤติดี" class="w-full text-xs font-bold bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <!-- 3. รายละเอียดคำร้อง -->
                    <div class="space-y-2">
                        <label for="description" class="text-xs font-black text-slate-700 dark:text-slate-300 block">รายละเอียดและเหตุผลความจำเป็น</label>
                        <textarea name="description" id="description" rows="5" required placeholder="พิมพ์รายละเอียดความต้องการและเหตุผลของคำร้องนี้อย่างครบถ้วน..." class="w-full text-xs font-bold bg-slate-50 dark:bg-gray-900 border border-slate-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:ring-purple-500 focus:border-purple-500"></textarea>
                    </div>

                    <!-- 4. แนบไฟล์เอกสาร -->
                    <div class="space-y-2">
                        <label for="file" class="text-xs font-black text-slate-700 dark:text-slate-300 block">เอกสารแนบประกอบ (ถ้ามี เช่น ภาพถ่ายบัตร/วุฒิการศึกษา)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 dark:border-gray-700 hover:border-purple-500 dark:hover:border-purple-500 rounded-2xl cursor-pointer bg-slate-50 dark:bg-gray-900/60 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold">คลิกที่นี่เพื่อแนบไฟล์ประกอบ</p>
                                    <p class="text-[8px] text-slate-400 font-medium mt-1">ไฟล์ PDF, JPG, PNG ขนาดไม่เกิน 5MB</p>
                                </div>
                                <input type="file" name="file" id="file" class="hidden">
                            </label>
                        </div>
                    </div>

                    <!-- 5. Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 transition-colors text-white rounded-2xl text-xs font-black shadow-md">
                            ยืนยันและส่งคำร้องออนไลน์
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</x-app-layout>