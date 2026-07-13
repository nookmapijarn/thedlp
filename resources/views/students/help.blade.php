<x-app-layout>
    <x-slot name="header">
        <h6 class="font-bold text-slate-800 dark:text-white leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A7 7 0 1110.5 3.5c1.1 0 2.12.253 3.03.7M14.828 9.172A4 4 0 1112 8.244m2.828.928a4 4 0 01-2.828-.928" />
            </svg>
            {{ __('ศูนย์รับแจ้งปัญหา (Student Help Center)') }}
        </h6>
    </x-slot>

    @php
        $totalCount = $helpRequests->count();
        $pendingCount = $helpRequests->where('status', 'pending')->count();
        $inProgressCount = $helpRequests->where('status', 'in_progress')->count();
        $resolvedCount = $helpRequests->where('status', 'resolved')->count();
        $rejectedCount = $helpRequests->where('status', 'rejected')->count();
        $completedCount = $resolvedCount + $rejectedCount;

        // Apply filters in Blade (matching Admin page logic)
        $statusFilter = request()->query('status', 'all');
        $filteredRequests = $helpRequests;
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'completed') {
                $filteredRequests = $helpRequests->whereIn('status', ['resolved', 'rejected']);
            } else {
                $filteredRequests = $helpRequests->where('status', $statusFilter);
            }
        }
    @endphp

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6 text-slate-800 dark:text-slate-150" x-data="{ showForm: false }">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- 2. Submit New Request Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-5 rounded-[2rem] border border-slate-150/80 dark:border-gray-700/60 shadow-sm transition-all duration-300">
            <div class="space-y-1">
                <h3 class="text-base font-black text-slate-900 dark:text-white">ต้องการส่งเรื่องแจ้งปัญหาการใช้งานใหม่?</h3>
                <p class="text-xs text-slate-550 dark:text-slate-400 font-semibold">คุณสามารถส่งข้อมูลเรื่องร้องเรียนปัญหาการเรียนหรือการใช้งานระบบเพื่อประสานครูผู้สอนและเจ้าหน้าที่</p>
            </div>
            <button @click="showForm = !showForm" 
                    class="px-4.5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all active:scale-95 flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span x-text="showForm ? 'ปิดแบบฟอร์ม' : 'ยื่นเรื่องแจ้งปัญหาใหม่'">แจ้งปัญหาใหม่</span>
            </button>
        </div>

        <!-- 3. Collapsible Create Request Form -->
        <div x-show="showForm" x-collapse x-cloak
             class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-indigo-200/50 dark:border-indigo-900/30 shadow-md space-y-6 animate-in slide-in-from-top-3 duration-250">
            <div class="space-y-1">
                <h3 class="text-lg font-black text-slate-900 dark:text-white">แจ้งปัญหา</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold">โปรดเลือกหมวดหมู่และกรอกรายละเอียดข้อมูลให้ครบถ้วนเพื่อผลลัพธ์การตรวจสอบที่รวดเร็ว</p>
            </div>
            
            <form action="{{ route('help.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Column 1: Category & Subject -->
                    <div class="md:col-span-1 space-y-4">
                        <!-- Category Selection -->
                        <div class="space-y-1.5">
                            <label for="category" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">หมวดหมู่ปัญหา</label>
                            <select name="category" id="category" required 
                                    class="w-full bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-650 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none text-slate-800 dark:text-slate-100">
                                <option value="">-- เลือกหมวดหมู่ --</option>
                                <option value="ปัญหาการใช้งานระบบ" {{ old('category') == 'ปัญหาการใช้งานระบบ' ? 'selected' : '' }}>ปัญหาการใช้งานระบบ / ล็อกอิน</option>
                                <option value="ปัญหาการเรียน / รายวิชา" {{ old('category') == 'ปัญหาการเรียน / รายวิชา' ? 'selected' : '' }}>ปัญหาการเรียน / บทเรียนออนไลน์ / ทดสอบออนไลน์</option>
                                <option value="ข้อมูลทะเบียนนักศึกษา" {{ old('category') == 'ข้อมูลทะเบียนนักศึกษา' ? 'selected' : '' }}>ข้อมูลทะเบียน / กลุ่มเรียน / ผลการเรียน</option>
                                <option value="กิจกรรม กพช." {{ old('category') == 'กิจกรรม กพช.' ? 'selected' : '' }}>กิจกรรม กพช. / ชั่วโมงกิจกรรม</option>
                                <option value="อาคารสถานที่ / ห้องน้ำ / ไฟฟ้า / อินเทอร์เน็ต / อื่นๆ" {{ old('category') == 'อาคารสถานที่ / ห้องน้ำ / ไฟฟ้า / อินเทอร์เน็ต / อื่นๆ' ? 'selected' : '' }}>อาคารสถานที่ / ห้องน้ำ / ไฟฟ้า / อินเทอร์เน็ต / อื่นๆ</option>
                                <option value="ยาเสพติด / บุหรี่ / อื่นๆ" {{ old('category') == 'ยาเสพติด / บุหรี่ / อื่นๆ' ? 'selected' : '' }}>ยาเสพติด / บุหรี่ / อื่นๆ</option>
                                <option value="ล่วงละเมิดทางเพศ" {{ old('category') == 'ล่วงละเมิดทางเพศ' ? 'selected' : '' }}>ล่วงละเมิดทางเพศ</option>
                                <option value="เรื่องอื่น ๆ" {{ old('category') == 'เรื่องอื่น ๆ' ? 'selected' : '' }}>เรื่องอื่น ๆ</option>
                            </select>
                            @error('category')
                                <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject Input -->
                        <div class="space-y-1.5">
                            <label for="subject" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">หัวข้อปัญหา</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required placeholder="ระบุหัวเรื่องปัญหา..."
                                   class="w-full bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-650 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400">
                            @error('subject')
                                <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Column 2 & 3: Message Text Area & Submit -->
                    <div class="md:col-span-2 flex flex-col justify-between space-y-4">
                        <div class="space-y-1.5 flex-1 flex flex-col">
                            <label for="message" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">รายละเอียดข้อความเพิ่มเติม</label>
                            <textarea name="message" id="message" rows="4" required placeholder="อธิบายรายละเอียดความช่วยเหลือที่ต้องการ..."
                                      class="w-full flex-1 bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-650 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400 resize-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs font-bold text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="w-full md:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-all shadow-md active:scale-95 text-xs flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126a59.768 59.768 0 0121.485 12 59.77 59.77 0 01-18.215 8.876L5.999 12zm0 0h7.5"></path></svg>
                                ส่งเรื่องยื่นคำร้องช่วยเหลือ
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 4. Tabbed status filters (Admin Style) -->
        <div class="p-2 bg-slate-100 dark:bg-gray-750 border border-slate-200/40 rounded-2xl flex flex-wrap gap-1">
            <a href="{{ route('help.index', ['status' => 'all']) }}" 
               class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
               {{ $statusFilter == 'all' ? 'bg-white dark:bg-gray-800 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                ทั้งหมด
                <span class="px-2 py-0.5 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px] font-black">{{ $totalCount }}</span>
            </a>
            
            <a href="{{ route('help.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
               {{ $statusFilter == 'pending' ? 'bg-white dark:bg-gray-800 text-red-650 dark:text-red-400 shadow-sm' : 'text-slate-500 hover:text-red-500' }}">
                รอตรวจสอบ
                <span class="px-2 py-0.5 rounded-lg bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 text-[10px] font-black">
                    {{ $pendingCount }}
                </span>
            </a>
            
            <a href="{{ route('help.index', ['status' => 'in_progress']) }}" 
               class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
               {{ $statusFilter == 'in_progress' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 hover:text-blue-500' }}">
                กำลังดำเนินการ
                <span class="px-2 py-0.5 rounded-lg bg-blue-50 dark:bg-blue-950 text-blue-650 dark:text-blue-300 text-[10px] font-black">{{ $inProgressCount }}</span>
            </a>
            
            <a href="{{ route('help.index', ['status' => 'resolved']) }}" 
               class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
               {{ $statusFilter == 'resolved' ? 'bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 shadow-sm' : 'text-slate-500 hover:text-green-500' }}">
                แก้ไขเสร็จสิ้น
                <span class="px-2 py-0.5 rounded-lg bg-green-50 dark:bg-green-950/50 text-green-650 dark:text-green-400 text-[10px] font-black">{{ $resolvedCount }}</span>
            </a>
            
            <a href="{{ route('help.index', ['status' => 'rejected']) }}" 
               class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
               {{ $statusFilter == 'rejected' ? 'bg-white dark:bg-gray-800 text-red-650 dark:text-red-400 shadow-sm' : 'text-slate-500 hover:text-red-500' }}">
                ปฏิเสธคำขอ
                <span class="px-2 py-0.5 rounded-lg bg-red-50 dark:bg-red-950/20 text-red-750 dark:text-red-400 text-[10px] font-black">{{ $rejectedCount }}</span>
            </a>
        </div>

        <!-- 5. Redesigned Ticket History list (Admin style) -->
        <div class="space-y-6">
            @if($filteredRequests->isEmpty())
                <div class="p-16 text-center bg-slate-50 dark:bg-slate-800/40 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-700 space-y-3">
                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002-2.25V5.25A2.25 2.25 0 0018 3H6a2.25 2.25 0 00-2 2.25v13.5A2.25 2.25 0 006 21h3"></path></svg>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-bold">ไม่พบข้อมูลคำร้องขอความช่วยเหลือในหมวดหมู่สถานะนี้</p>
                </div>
            @else
                <div class="space-y-6" x-data="{ activeTicket: {{ request()->query('ticket') ?? 'null' }} }" x-init="$watch('activeTicket', value => { if(value) { initChatPolling(value); } else { stopChatPolling(); } }); if (activeTicket) { initChatPolling(activeTicket); }">
                    @foreach($filteredRequests as $item)
                        <div id="ticket-{{ $item->id }}"
                             :class="activeTicket === {{ $item->id }} 
                             ? 'border-indigo-500 bg-slate-50/20 dark:bg-gray-800/20 dark:border-indigo-500 shadow-md' 
                             : 'border-slate-100 dark:border-gray-850 bg-white dark:bg-gray-850 shadow-sm'"
                             class="p-6 border rounded-[2.5rem] hover:shadow-md hover:border-slate-200 dark:hover:border-gray-700/80 transition-all duration-300 w-full">
                            
                            <!-- Ticket Header Metadata -->
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300">
                                            {{ $item->category }}
                                        </span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500 font-mono">#{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                        ผู้ส่ง: <span class="text-indigo-650 dark:text-indigo-400 font-extrabold">{{ auth()->user()->name }}</span> 
                                        (รหัส: {{ auth()->user()->student_id }})
                                    </div>
                                    <!-- Shrunk clock icon to w-3 h-3 -->
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($item->created_at)->addYears(543)->locale('th')->isoFormat('D MMM YY, HH:mm') }} น.
                                    </div>
                                </div>

                                <div class="flex items-center gap-2.5">
                                    <!-- Status Badges -->
                                    @if($item->status == 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300">รอตรวจสอบ</span>
                                    @elseif($item->status == 'in_progress')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">กำลังดำเนินการ</span>
                                    @elseif($item->status == 'resolved')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300">แก้ไขเสร็จสิ้น</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-red-50 text-red-750 dark:bg-red-950/20 dark:text-red-400">ปฏิเสธคำขอ</span>
                                    @endif

                                    <!-- Action Button on top right of the card -->
                                    <button type="button" @click="activeTicket === {{ $item->id }} ? activeTicket = null : activeTicket = {{ $item->id }}" 
                                            class="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-[10px] rounded-lg shadow-sm transition-all active:scale-95 flex items-center gap-1.5 dark:bg-gray-700 dark:hover:bg-gray-600">
                                        <span x-text="activeTicket === {{ $item->id }} ? 'ปิด' : 'ดูรายละเอียด'">ดูรายละเอียด</span>
                                        <svg class="w-3 h-3 transform transition-transform duration-200" :class="activeTicket === {{ $item->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Card Body -->
                            <div x-show="activeTicket === {{ $item->id }}" x-collapse x-cloak
                                 class="pt-5 border-t border-slate-100 dark:border-gray-700/60 space-y-5 animate-in fade-in duration-200">

                                <!-- Stepper Timeline Visual -->
                                <div class="py-4 border-b border-slate-100 dark:border-gray-700/60 max-w-lg mx-auto">
                                    <div class="flex items-center justify-between">
                                        <!-- Step 1: Created -->
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-green-500 text-white shadow-sm ring-4 ring-green-50 dark:ring-green-950">1</div>
                                            <span class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 mt-1.5">ส่งเรื่องแล้ว</span>
                                        </div>
                                        
                                        <!-- Connector line -->
                                        <div class="flex-1 h-0.5 {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? 'bg-green-500' : 'bg-slate-200 dark:bg-slate-700' }}"></div>

                                        <!-- Step 2: In Progress -->
                                        <div class="flex flex-col items-center flex-1">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs 
                                                {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? 'bg-blue-600 text-white shadow-sm ring-4 ring-blue-100 dark:ring-blue-950' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }}">2</div>
                                            <span class="text-[10px] font-extrabold {{ in_array($item->status, ['in_progress', 'resolved', 'rejected']) ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500' }} mt-1.5">กำลังดำเนินการ</span>
                                        </div>

                                        <!-- Connector line -->
                                        <div class="flex-1 h-0.5 {{ in_array($item->status, ['resolved', 'rejected']) ? 'bg-green-500' : 'bg-slate-200 dark:bg-slate-700' }}"></div>

                                        <!-- Step 3: Resolved / Rejected -->
                                        <div class="flex flex-col items-center flex-1">
                                            @if($item->status == 'rejected')
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-red-500 text-white shadow-sm ring-4 ring-red-100 dark:ring-red-950">X</div>
                                                <span class="text-[10px] font-extrabold text-red-500 mt-1.5">ปฏิเสธคำขอ</span>
                                            @else
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs 
                                                    {{ $item->status == 'resolved' ? 'bg-green-500 text-white shadow-sm ring-4 ring-green-100 dark:ring-green-950' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }}">3</div>
                                                <span class="text-[10px] font-extrabold {{ $item->status == 'resolved' ? 'text-green-600 dark:text-green-400' : 'text-slate-400 dark:text-slate-500' }} mt-1.5">เสร็จสิ้น</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Chat Area Container -->
                                <div class="space-y-4">
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">หัวข้อ: {{ $item->subject }}</h4>
                                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500">หมวดหมู่: {{ $item->category }}</p>
                                    </div>
                                    
                                    <!-- Chat Conversation Window -->
                                    <div class="border border-slate-200/60 dark:border-gray-700/60 rounded-[2rem] overflow-hidden flex flex-col bg-slate-50/20 dark:bg-slate-900/30 h-[400px]">
                                        <!-- Messages Scroll Area -->
                                        <div id="chat-messages-{{ $item->id }}" class="flex-1 overflow-y-auto p-4 space-y-3.5 scrollbar-hide">
                                            <!-- Loader -->
                                            <div class="flex items-center justify-center h-full">
                                                <div class="text-xs text-slate-450 dark:text-slate-500 font-bold flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    กำลังโหลดการสนทนา...
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Chat Input Bar -->
                                        <div class="border-t border-slate-200/60 dark:border-gray-700/60 p-3 bg-white dark:bg-gray-800 flex gap-2 items-center">
                                            <textarea id="chat-input-{{ $item->id }}" rows="1" placeholder="พิมพ์ข้อความตอบกลับ..." 
                                                      class="flex-1 bg-slate-50 dark:bg-slate-700/40 border border-slate-200/80 dark:border-slate-650 rounded-xl px-4 py-2.5 text-xs font-semibold focus:ring-2 focus:ring-indigo-500 outline-none text-slate-800 dark:text-slate-100 placeholder:text-slate-400 resize-none max-h-20" 
                                                      onkeydown="handleChatSubmit(event, {{ $item->id }})"></textarea>
                                            <button onclick="sendChatMessage({{ $item->id }})" 
                                                    class="p-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126a59.768 59.768 0 0121.485 12 59.77 59.77 0 01-18.215 8.876L5.999 12zm0 0h7.5"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <script>
        let activeChatInterval = null;
        let currentUserId = {{ auth()->id() }};

        function initChatPolling(ticketId) {
            if (activeChatInterval) {
                clearInterval(activeChatInterval);
            }
            fetchChatMessages(ticketId);
            activeChatInterval = setInterval(() => {
                fetchChatMessages(ticketId);
            }, 3000);
        }

        function stopChatPolling() {
            if (activeChatInterval) {
                clearInterval(activeChatInterval);
                activeChatInterval = null;
            }
        }

        function fetchChatMessages(ticketId) {
            const chatArea = document.getElementById(`chat-messages-${ticketId}`);
            if (!chatArea) return;

            fetch(`/help-requests/${ticketId}/messages`)
                .then(res => res.json())
                .then(data => {
                    if (data.messages) {
                        renderChatMessages(chatArea, data.messages);
                    }
                })
                .catch(err => console.error('Error fetching chat:', err));
        }

        function renderChatMessages(container, messages) {
            let htmlContent = '';
            
            messages.forEach(msg => {
                if (msg.type === 'system') {
                    htmlContent += `
                        <div class="flex justify-center my-2">
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-black rounded-full text-slate-550 dark:text-slate-400 border border-slate-200/30">
                                ${msg.text} • ${msg.time}
                            </span>
                        </div>
                    `;
                } else {
                    const isMe = msg.user_id === currentUserId;
                    if (isMe) {
                        htmlContent += `
                            <div class="flex justify-end gap-2 my-2.5">
                                <div class="flex flex-col items-end max-w-[80%]">
                                    <div class="px-4 py-2.5 bg-indigo-600 text-white rounded-2xl rounded-tr-none shadow-sm text-xs font-bold leading-relaxed whitespace-pre-wrap text-left">
                                        ${msg.text}
                                    </div>
                                    <span class="text-[9px] text-slate-400 font-bold mt-1">${msg.time}</span>
                                </div>
                            </div>
                        `;
                    } else {
                        htmlContent += `
                            <div class="flex justify-start gap-2 my-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[11px] font-black text-slate-650 dark:text-slate-350 shrink-0">
                                    ${msg.sender_name.charAt(0)}
                                </div>
                                <div class="flex flex-col items-start max-w-[80%]">
                                    <span class="text-[10px] text-slate-400 font-extrabold mb-1">${msg.sender_name}</span>
                                    <div class="px-4 py-2.5 bg-slate-100 dark:bg-slate-850 text-slate-800 dark:text-slate-200 rounded-2xl rounded-tl-none border border-slate-200/30 shadow-sm text-xs font-bold leading-relaxed whitespace-pre-wrap text-left">
                                        ${msg.text}
                                    </div>
                                    <span class="text-[9px] text-slate-400 font-bold mt-1">${msg.time}</span>
                                </div>
                            </div>
                        `;
                    }
                }
            });

            const wasScrolledToBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 60;
            const isFirstLoad = container.getAttribute('data-last-html') === null;

            if (container.getAttribute('data-last-html') !== htmlContent) {
                container.innerHTML = htmlContent;
                container.setAttribute('data-last-html', htmlContent);
                
                if (isFirstLoad || wasScrolledToBottom) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }

        function sendChatMessage(ticketId) {
            const inputEl = document.getElementById(`chat-input-${ticketId}`);
            if (!inputEl) return;

            const messageText = inputEl.value.trim();
            if (!messageText) return;

            inputEl.disabled = true;

            fetch(`/help-requests/${ticketId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: messageText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    inputEl.value = '';
                    fetchChatMessages(ticketId);
                }
                inputEl.disabled = false;
                inputEl.focus();
            })
            .catch(err => {
                console.error('Error sending message:', err);
                inputEl.disabled = false;
            });
        }

        function handleChatSubmit(event, ticketId) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendChatMessage(ticketId);
            }
        }
    </script>

    @if(request()->query('ticket'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const el = document.getElementById('ticket-{{ request()->query('ticket') }}');
                if (el) {
                    setTimeout(() => {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                }
            });
        </script>
    @endif
</x-app-layout>
