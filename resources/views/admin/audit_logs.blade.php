<x-admin-layout>
    <div class="p-4 sm:ml-64">
        <!-- Main container -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-xl dark:bg-gray-800 dark:border-gray-700 mt-14">
            
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        ระบบจัดการและตรวจสอบบันทึกการใช้งาน
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        รายงานการปฏิบัติตามกฎหมายคุ้มครองข้อมูลส่วนบุคคล (PDPA) และ พ.ร.บ. ว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์ฯ
                    </p>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                <ul class="flex flex-wrap -mb-px text-sm font-semibold text-center" id="logTab" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg tab-link active transition-all" id="pdpa-tab" type="button" onclick="switchTab('pdpa')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            บันทึกการเข้าถึงข้อมูลส่วนบุคคล (PDPA Audit Trail)
                        </button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-flex items-center p-4 border-b-2 rounded-t-lg tab-link text-gray-500 hover:text-gray-600 hover:border-gray-300 dark:text-gray-400 transition-all" id="traffic-tab" type="button" onclick="switchTab('traffic')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            ประวัติการจราจรคอมพิวเตอร์ (พ.ร.บ.คอมพิวเตอร์ฯ)
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Contents -->
            <div id="logTabContent">
                
                <!-- PDPA Audit Trail Tab -->
                <div class="tab-pane" id="pdpa-content">
                    <!-- Search Filter -->
                    <form action="{{ route('admin.audit_logs') }}" method="GET" class="mb-6 flex flex-wrap gap-3 max-w-2xl">
                        <input type="hidden" name="tab" value="pdpa">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อครู, กิจกรรม, หรือข้อมูลเป้าหมาย..." class="block w-full ps-10 pe-4 py-2.5 text-sm text-gray-900 border border-gray-300 rounded-xl bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all focus:ring-4 focus:ring-blue-300">
                            ค้นหาบันทึก
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.audit_logs', ['tab' => 'pdpa']) }}" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-semibold rounded-xl text-sm px-5 py-2.5 transition-all flex items-center">
                                ล้างคำค้นหา
                            </a>
                        @endif
                    </form>

                    <!-- Table -->
                    <div class="relative overflow-x-auto border border-gray-100 rounded-xl shadow-sm dark:border-gray-700">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-4">ผู้ใช้งาน (User)</th>
                                    <th scope="col" class="px-6 py-4">กิจกรรม (Action)</th>
                                    <th scope="col" class="px-6 py-4">เป้าหมายข้อมูล (Target)</th>
                                    <th scope="col" class="px-6 py-4 text-center">IP Address</th>
                                    <th scope="col" class="px-6 py-4">วันเวลาอ้างอิง (Timestamp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($auditLogs as $log)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ mb_substr($log->user_name, 0, 1, 'UTF-8') }}
                                                </div>
                                                <div>
                                                    <div>{{ $log->user_name }}</div>
                                                    <div class="text-[10px] font-normal text-gray-400">ID: {{ $log->user_id ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-full 
                                                @if(str_contains($log->action, 'clear')) bg-red-100 text-red-800 dark:bg-red-950 dark:text-red-300
                                                @elseif(str_contains($log->action, 'import')) bg-green-100 text-green-800 dark:bg-green-950 dark:text-green-300
                                                @elseif(str_contains($log->action, 'view') || str_contains($log->action, 'search')) bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                                @endif border border-transparent">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-gray-900 dark:text-gray-200 font-medium">{{ $log->target_name }}</div>
                                            @if($log->target_code)
                                                <div class="text-xs text-gray-400 font-mono">คีย์: {{ $log->target_code }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-xs text-gray-600 dark:text-gray-400">
                                            {{ $log->ip_address }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium text-gray-600 dark:text-gray-400">
                                            {{ $log->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 italic bg-gray-50/30 dark:bg-gray-900/10">
                                            ไม่พบรายการบันทึกที่ค้นหาในประวัติระบบ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-5">
                        {{ $auditLogs->links() }}
                    </div>
                </div>

                <!-- Traffic Logs Tab -->
                <div class="tab-pane hidden" id="traffic-content">
                    <form action="{{ route('admin.audit_logs') }}" method="GET" class="mb-6 flex flex-wrap items-end gap-4">
                        <input type="hidden" name="tab" value="traffic">
                        <div>
                            <label for="file" class="block mb-2 text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">เลือกไฟล์ประวัติระบบรายวัน (Traffic Logs):</label>
                            <select id="file" name="file" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white w-72 shadow-sm font-semibold">
                                @forelse($trafficFiles as $file)
                                    <option value="{{ $file }}" @if($file === $selectedFile) selected @endif>
                                        📅 {{ str_replace(['traffic-', '.log'], '', $file) }} ({{ $file }})
                                    </option>
                                @empty
                                    <option>ไม่พบไฟล์ประวัติการจราจรคอมพิวเตอร์</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="pb-1.5 text-xs text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            แสดงข้อมูลสูงสุด 500 บรรทัดล่าสุดย้อนหลัง โดยจัดรูปแบบเป็นสีกำหนดตามองค์ประกอบเพื่อความชัดเจน
                        </div>
                    </form>

                    <!-- macOS style Terminal window container -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 shadow-2xl">
                        <!-- window title bar -->
                        <div class="bg-gray-950 px-4 py-3 flex items-center justify-between border-b border-gray-800">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full bg-red-500 shadow-inner"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-inner"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500 shadow-inner"></div>
                                <span class="text-xs text-gray-400 ml-4 font-mono select-none">{{ $selectedFile }}</span>
                            </div>
                            <span class="text-[10px] text-gray-600 font-mono select-none">logs / traffic-console</span>
                        </div>
                        
                        <!-- Terminal Log Lines console view -->
                        <div class="text-gray-300 p-5 overflow-y-auto max-h-[550px] font-mono text-[11px] shadow-inner bg-gray-950 space-y-1">
                            @if(empty($logLines))
                                <div class="text-gray-600 italic py-6 text-center">ไม่มีประวัติจราจรในไฟล์ที่เลือก หรือยังไม่มีการเปิดใช้หน้าเว็บในขณะนี้</div>
                            @else
                                @foreach($logLines as $line)
                                    @php
                                        // Parse log line structure:
                                        // [2026-07-03 15:51:35] IP: 127.0.0.1 | User: - | Method: GET | URL: http://... | Status: 200 | Agent: ...
                                        $timestamp = '';
                                        $ip = '';
                                        $user = '';
                                        $method = '';
                                        $url = '';
                                        $status = '';
                                        $agent = '';
                                        
                                        if (preg_match('/^\[(.*)\]\s+IP:\s+([^\s|]+)\s+\|\s+User:\s+([^\s|]+)\s+\|\s+Method:\s+([^\s|]+)\s+\|\s+URL:\s+([^\s|]+)\s+\|\s+Status:\s+(\d+)\s+\|\s+Agent:\s+(.*)$/U', $line, $matches)) {
                                            $timestamp = $matches[1];
                                            $ip = $matches[2];
                                            $user = $matches[3];
                                            $method = $matches[4];
                                            $url = $matches[5];
                                            $status = (int)$matches[6];
                                            $agent = $matches[7];
                                        }
                                    @endphp
                                    @if($timestamp)
                                        <div class="py-1 border-b border-gray-900/40 hover:bg-gray-900/40 transition-colors flex flex-wrap gap-2 items-center leading-relaxed">
                                            <span class="text-yellow-500/90 font-medium">[{{ $timestamp }}]</span>
                                            <span class="bg-gray-800 text-gray-300 px-1.5 py-0.5 rounded font-mono select-all">IP: {{ $ip }}</span>
                                            <span class="text-gray-500">User: <strong class="text-gray-300 font-semibold">{{ $user }}</strong></span>
                                            <span class="px-1.5 py-0.5 rounded font-bold uppercase text-[10px]
                                                @if($method === 'GET') bg-green-950/60 text-green-400 border border-green-900/50
                                                @elseif($method === 'POST') bg-blue-950/60 text-blue-400 border border-blue-900/50
                                                @elseif($method === 'DELETE') bg-red-950/60 text-red-400 border border-red-900/50
                                                @else bg-yellow-950/60 text-yellow-400 border border-yellow-900/50
                                                @endif">{{ $method }}</span>
                                            <span class="text-blue-400/90 truncate max-w-xs md:max-w-md select-all" title="{{ $url }}">{{ parse_url($url, PHP_URL_PATH) ?: $url }}</span>
                                            <span class="px-1.5 py-0.5 rounded font-bold text-[10px]
                                                @if($status >= 200 && $status < 300) bg-green-950/40 text-green-400
                                                @elseif($status >= 300 && $status < 400) bg-yellow-950/40 text-yellow-400
                                                @else bg-red-950/40 text-red-400
                                                @endif">Status: {{ $status }}</span>
                                        </div>
                                    @else
                                        <div class="py-1 border-b border-gray-900/40 hover:bg-gray-900/40 transition-colors text-gray-500 font-mono break-all">{{ $line }}</div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- JS สำหรับการควบคุมสลับหน้าแถบแท็บ (Tab toggling) -->
    <script>
        function switchTab(tabId) {
            // ซ่อน content ทั้งหมด
            document.getElementById('pdpa-content').classList.add('hidden');
            document.getElementById('traffic-content').classList.add('hidden');
 
            // ลบสีปุ่ม Active ทั้งหมด
            const tabs = document.querySelectorAll('.tab-link');
            tabs.forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600', 'border-b-2');
                tab.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300');
            });
 
            // แสดง content ที่เลือก และระบุสถานะ Active ให้ปุ่ม
            if (tabId === 'pdpa') {
                document.getElementById('pdpa-content').classList.remove('hidden');
                const btn = document.getElementById('pdpa-tab');
                btn.classList.add('border-blue-600', 'text-blue-600', 'border-b-2');
                btn.classList.remove('text-gray-500', 'border-transparent');
            } else if (tabId === 'traffic') {
                document.getElementById('traffic-content').classList.remove('hidden');
                const btn = document.getElementById('traffic-tab');
                btn.classList.add('border-blue-600', 'text-blue-600', 'border-b-2');
                btn.classList.remove('text-gray-500', 'border-transparent');
            }
        }
 
        // โหลดหน้าจอให้ตรงกับแท็บดั้งเดิมที่มีอยู่ใน URL Query
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'pdpa';
            switchTab(activeTab);
        });
    </script>
</x-admin-layout>
