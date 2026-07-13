<x-admin-layout>
    @php
        $totalRequests = \App\Models\HelpRequest::count();
        $pendingRequests = \App\Models\HelpRequest::where('status', 'pending')->count();
        $inProgressRequests = \App\Models\HelpRequest::where('status', 'in_progress')->count();
        $resolvedRequests = \App\Models\HelpRequest::where('status', 'resolved')->count();
        $rejectedRequests = \App\Models\HelpRequest::where('status', 'rejected')->count();

        // Categories breakdown for pending requests
        $categories = [
            'ปัญหาการใช้งานระบบ' => 'ปัญหาการใช้งานระบบ / ล็อกอิน',
            'ปัญหาการเรียน / รายวิชา' => 'ปัญหาการเรียน / บทเรียนออนไลน์',
            'ข้อมูลทะเบียนนักศึกษา' => 'ข้อมูลทะเบียนนักศึกษา',
            'กิจกรรม กพช.' => 'กิจกรรม กพช. / ชั่วโมงกิจกรรม',
            'อาคารสถานที่ / ห้องน้ำ / ไฟฟ้า / อินเทอร์เน็ต / อื่นๆ' => 'อาคารสถานที่ / ห้องน้ำ / ไฟฟ้า / อินเทอร์เน็ต / อื่นๆ',
            'ยาเสพติด / บุหรี่ / อื่นๆ' => 'ยาเสพติด / บุหรี่ / อื่นๆ',
            'ล่วงละเมิดทางเพศ' => 'ล่วงละเมิดทางเพศ',
            'เรื่องอื่น ๆ' => 'เรื่องอื่น ๆ'
        ];
        
        $pendingByCategory = [];
        foreach (array_keys($categories) as $catKey) {
            $pendingByCategory[$catKey] = \App\Models\HelpRequest::where('category', $catKey)->where('status', 'pending')->count();
        }
    @endphp

    <div class="p-4 sm:ml-64">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-6 mt-14">
            
            <!-- Welcome Header Panel -->
            <div class="flex items-center space-x-3 pb-2">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl dark:bg-indigo-900/30 dark:text-indigo-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A7 7 0 1110.5 3.5c1.1 0 2.12.253 3.03.7M14.828 9.172A4 4 0 1112 8.244m2.828.928a4 4 0 01-2.828-.928"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white">ศูนย์รับแจ้งปัญหา (Helpdesk Admin)</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ตรวจสอบความก้าวหน้า บันทึกการดำเนินการ และวิเคราะห์ข้อมูลความต้องการช่วยเหลือของผู้เรียนในระบบ</p>
                </div>
            </div>

            <!-- Dashboard Summary Statistics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                
                <!-- Stat 1: Total Requests -->
                <div class="p-5 bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-700/60 rounded-[2rem] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">คำร้องทั้งหมด</span>
                        <span class="p-2 bg-slate-50 dark:bg-slate-700 text-slate-500 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-black text-slate-800 dark:text-white">{{ $totalRequests }}</span>
                        <span class="text-xs font-bold text-slate-400 block mt-1">รายการสะสมรวมทั้งหมด</span>
                    </div>
                </div>

                <!-- Stat 2: Pending Requests (Red alert count) -->
                <div class="p-5 bg-red-50/30 dark:bg-red-950/10 border border-red-100 dark:border-red-950/45 rounded-[2rem] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-red-500 dark:text-red-400 uppercase tracking-widest">กำลังรอแก้ไข (Unresolved)</span>
                        <span class="p-2 bg-red-100 dark:bg-red-950 text-red-650 dark:text-red-300 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <!-- Unresolved Count in bold Red -->
                        <span class="text-3xl font-black text-red-600 dark:text-red-400">{{ $pendingRequests }}</span>
                        <span class="text-xs font-bold text-red-400 block mt-1 mb-3">คำร้องที่ต้องเร่งดำเนินการ</span>
                        
                        <!-- Categories Breakdown -->
                        <div class="pt-2 border-t border-red-100 dark:border-red-950/45 space-y-1 text-[10px]">
                            @foreach($pendingByCategory as $catName => $count)
                                <div class="flex justify-between items-center text-red-850 dark:text-red-300 font-bold">
                                    <span class="truncate pr-2">{{ $catName }}</span>
                                    <span class="px-1.5 py-0.2 rounded-full bg-red-100 dark:bg-red-950 text-red-650 dark:text-red-400 text-[9px] font-black min-w-[16px] text-center">
                                        {{ $count }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Stat 3: In Progress -->
                <div class="p-5 bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-700/60 rounded-[2rem] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">กำลังดำเนินการ</span>
                        <span class="p-2 bg-blue-50 dark:bg-blue-950 text-blue-500 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $inProgressRequests }}</span>
                        <span class="text-xs font-bold text-slate-400 block mt-1">รับเรื่องแล้วและอยู่ระหว่างการประสานงาน</span>
                    </div>
                </div>

                <!-- Stat 4: Resolved -->
                <div class="p-5 bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-700/60 rounded-[2rem] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">แก้ไขสำเร็จแล้ว</span>
                        <span class="p-2 bg-green-50 dark:bg-green-950 text-green-500 rounded-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-3xl font-black text-green-600 dark:text-green-400">{{ $resolvedRequests }}</span>
                        <span class="text-xs font-bold text-slate-400 block mt-1">รายการที่ดำเนินการเสร็จสิ้นสำเร็จ</span>
                    </div>
                </div>

            </div>

            <!-- Tabbed navigation filter -->
            <div class="p-2 bg-slate-100 dark:bg-gray-750 border border-slate-200/40 rounded-2xl flex flex-wrap gap-1">
                <a href="{{ route('admin.help.index', ['status' => 'all']) }}" 
                   class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
                   {{ $statusFilter == 'all' ? 'bg-white dark:bg-gray-800 text-slate-800 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                    ทั้งหมด
                    <span class="px-2 py-0.5 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[10px]">{{ $totalRequests }}</span>
                </a>
                
                <a href="{{ route('admin.help.index', ['status' => 'pending']) }}" 
                   class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
                   {{ $statusFilter == 'pending' ? 'bg-white dark:bg-gray-800 text-red-600 dark:text-red-400 shadow-sm' : 'text-slate-500 hover:text-red-500' }}">
                    รอตรวจสอบ
                    <span class="px-2 py-0.5 rounded-lg bg-red-100 dark:bg-red-950 text-red-665 dark:text-red-400 text-[10px] font-black">
                        {{ $pendingRequests }}
                    </span>
                </a>
                
                <a href="{{ route('admin.help.index', ['status' => 'in_progress']) }}" 
                   class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
                   {{ $statusFilter == 'in_progress' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 hover:text-blue-500' }}">
                    กำลังดำเนินการ
                    <span class="px-2 py-0.5 rounded-lg bg-blue-50 dark:bg-blue-950 text-blue-650 dark:text-blue-300 text-[10px] font-black">{{ $inProgressRequests }}</span>
                </a>
                
                <a href="{{ route('admin.help.index', ['status' => 'resolved']) }}" 
                   class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
                   {{ $statusFilter == 'resolved' ? 'bg-white dark:bg-gray-800 text-green-600 dark:text-green-400 shadow-sm' : 'text-slate-500 hover:text-green-500' }}">
                    แก้ไขเสร็จสิ้น
                    <span class="px-2 py-0.5 rounded-lg bg-green-50 dark:bg-green-950/50 text-green-650 dark:text-green-400 text-[10px] font-black">{{ $resolvedRequests }}</span>
                </a>
                
                <a href="{{ route('admin.help.index', ['status' => 'rejected']) }}" 
                   class="px-4 py-2 text-xs font-black rounded-xl transition-all flex items-center gap-2
                   {{ $statusFilter == 'rejected' ? 'bg-white dark:bg-gray-800 text-red-655 dark:text-red-400 shadow-sm' : 'text-slate-500 hover:text-red-500' }}">
                    ปฏิเสธคำขอ
                    <span class="px-2 py-0.5 rounded-lg bg-red-50 dark:bg-red-950/20 text-red-750 dark:text-red-400 text-[10px] font-black">{{ $rejectedRequests }}</span>
                </a>
            </div>

            <!-- List of Support Tickets -->
            @if($helpRequests->isEmpty())
                <div class="p-16 text-center bg-slate-50 dark:bg-slate-800/40 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-700 space-y-3">
                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002-2.25V5.25A2.25 2.25 0 0018 3H6a2.25 2.25 0 00-2 2.25v13.5A2.25 2.25 0 006 21h3"></path></svg>
                    <p class="text-sm text-slate-555 dark:text-slate-400 font-bold">ไม่พบข้อมูลคำร้องขอความช่วยเหลือตามสถานะที่เลือก</p>
                </div>
            @else
                <div class="space-y-6" x-data="{ activeTicket: {{ request()->query('ticket') ?? 'null' }} }" x-init="$watch('activeTicket', value => { if(value) { initChatPolling(value); } else { stopChatPolling(); } }); if (activeTicket) { initChatPolling(activeTicket); }">
                    @foreach($helpRequests as $item)
                        <div id="ticket-{{ $item->id }}"
                             :class="activeTicket === {{ $item->id }} 
                             ? 'border-indigo-500 bg-slate-50/20 dark:bg-gray-800/20 dark:border-indigo-500 shadow-md' 
                             : 'border-slate-100 dark:border-gray-850 bg-white dark:bg-gray-850 shadow-sm'"
                             class="p-6 border rounded-[2.5rem] hover:shadow-md hover:border-slate-200 dark:hover:border-gray-700/80 transition-all duration-300">
                            
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
                                        ผู้ส่ง: <span class="text-indigo-650 dark:text-indigo-400 font-extrabold">{{ $item->user?->name ?? 'ไม่ระบุชื่อ' }}</span> 
                                        (รหัส: {{ $item->student_id ?? 'ไม่มีรหัส' }})
                                    </div>
                                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-bold">
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
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-red-50 text-red-755 dark:bg-red-950/20 dark:text-red-400">ปฏิเสธคำขอ</span>
                                    @endif

                                    <!-- Reply button relocated -->
                                    <button type="button" @click="activeTicket === {{ $item->id }} ? activeTicket = null : activeTicket = {{ $item->id }}" 
                                            class="px-3.5 py-2 bg-slate-900 hover:bg-black text-white font-extrabold text-[11px] rounded-xl shadow transition-all active:scale-95 flex items-center gap-1 dark:bg-gray-700 dark:hover:bg-gray-650">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span x-text="activeTicket === {{ $item->id }} ? 'ปิด' : 'จัดการ'">จัดการ</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Card Body -->
                            <div x-show="activeTicket === {{ $item->id }}" x-collapse x-cloak
                                 class="pt-5 border-t border-slate-100 dark:border-gray-700/60 space-y-5 animate-in fade-in duration-200">

                                <!-- Chat Area Container -->
                                <div class="space-y-4 w-full">
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">หัวข้อ: {{ $item->subject }}</h4>
                                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500">หมวดหมู่: {{ $item->category }}</p>
                                    </div>
                                    
                                    <!-- Chat Conversation Window -->
                                    <div class="border border-slate-200/60 dark:border-gray-700/60 rounded-[2rem] overflow-hidden flex flex-col bg-slate-50/20 dark:bg-slate-900/30 h-[420px]">
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
                                        
                                        <!-- Chat Input Bar with Status controls -->
                                        <div class="border-t border-slate-200/60 dark:border-gray-700/60 p-3 bg-white dark:bg-gray-800 flex flex-col gap-2.5">
                                            <div class="flex items-center gap-2">
                                                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wide">ปรับสถานะตั๋ว:</label>
                                                <select id="chat-status-{{ $item->id }}" class="bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg px-2 py-0.5 text-[10px] font-black outline-none text-slate-955 dark:text-white">
                                                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>รอตรวจสอบ</option>
                                                    <option value="in_progress" {{ $item->status == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                                                    <option value="resolved" {{ $item->status == 'resolved' ? 'selected' : '' }}>แก้ไขเสร็จสิ้น</option>
                                                    <option value="rejected" {{ $item->status == 'rejected' ? 'selected' : '' }}>ปฏิเสธคำขอ</option>
                                                </select>
                                            </div>
                                            <div class="flex gap-2 items-center">
                                                <textarea id="chat-input-{{ $item->id }}" rows="1" placeholder="พิมพ์ชี้แจงคำร้อง หรือระบุข้อมูลความคืบหน้าแจ้งผู้เรียน..." 
                                                          class="flex-1 bg-slate-100 dark:bg-slate-900 border border-slate-350 dark:border-slate-600 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-slate-955 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 resize-none max-h-20" 
                                                          onkeydown="handleChatSubmit(event, {{ $item->id }})"></textarea>
                                                <button onclick="sendChatMessage({{ $item->id }})" 
                                                        class="p-2.5 bg-indigo-700 hover:bg-indigo-800 text-white rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center shrink-0">
                                                    <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126a59.768 59.768 0 0121.485 12 59.77 59.77 0 01-18.215 8.876L5.999 12zm0 0h7.5"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                             </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination block -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    {{ $helpRequests->appends(['status' => $statusFilter])->links() }}
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
                                    <div class="px-4 py-2.5 bg-indigo-700 text-white rounded-2xl rounded-tr-none shadow-sm text-xs font-bold leading-relaxed whitespace-pre-wrap text-left">
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
                                    <div class="px-4 py-2.5 bg-slate-200/80 dark:bg-slate-800 text-slate-955 dark:text-slate-50 rounded-2xl rounded-tl-none border border-slate-300/80 dark:border-slate-700/50 shadow-sm text-xs font-bold leading-relaxed whitespace-pre-wrap text-left">
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

            const statusEl = document.getElementById(`chat-status-${ticketId}`);
            const statusVal = statusEl ? statusEl.value : null;

            inputEl.disabled = true;

            fetch(`/help-requests/${ticketId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ 
                    message: messageText,
                    status: statusVal
                })
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
</x-admin-layout>
