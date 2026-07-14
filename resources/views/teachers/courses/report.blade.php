<x-teachers-layout>
    @php
        if (!function_exists('formatDuration')) {
            function formatDuration($seconds) {
                if ($seconds <= 0) return '0 วินาที';
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;
                
                $output = '';
                if ($hours > 0) {
                    $output .= $hours . ' ชม. ';
                }
                if ($minutes > 0) {
                    $output .= $minutes . ' น. ';
                }
                if ($secs > 0 || empty($output)) {
                    $output .= $secs . ' วิ.';
                }
                return trim($output);
            }
        }
    @endphp

    <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6">
        
        <!-- Header Banner Block -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[11px] font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    Course Tracking Report
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">รายงานผู้เรียนตามหลักสูตร</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-bold max-w-3xl mt-1.5">
                    ตรวจสอบความก้าวหน้าการเรียน ระยะเวลาการศึกษาในบทเรียนปกติ และการเรียนรู้เพิ่มเติมผ่านวิดีโอคลิปสั้น
                </p>
            </div>
            
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 mt-4 text-xs font-bold text-slate-400 dark:text-slate-550 relative z-10">
                <a href="{{ route('courses.manage') }}" class="hover:text-purple-650 transition-colors">หลักสูตรทั้งหมด</a>
                <span>/</span>
                <span class="text-slate-650 dark:text-slate-300">รายงานข้อมูลผู้เรียน</span>
            </div>
        </div>

        <!-- Course Meta Details Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] p-6 shadow-sm flex flex-col md:flex-row gap-6 items-center">
            <img class="w-32 h-20 md:w-44 md:h-28 object-cover rounded-2xl flex-shrink-0 shadow-sm border border-slate-100 dark:border-slate-800" src="{{ $course->cover_image ? $course->cover_image : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" onerror="this.src='https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg'">
            <div class="flex-grow text-center md:text-left space-y-2">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full {{ $course->is_published ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500' }} text-[10px] font-black uppercase tracking-wider">
                        {{ $course->is_published ? 'เปิดสอน' : 'แบบร่าง' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 text-[10px] font-black uppercase tracking-wider">
                        {{ $course->modules->count() }} บทเรียน
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 text-[10px] font-black uppercase tracking-wider">
                        {{ $totalLessons }} ตอนทั้งหมด
                    </span>
                </div>
                <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white leading-tight">{{ $course->title }}</h2>
                <p class="text-xs text-slate-450 dark:text-slate-500 font-bold block">
                    ผู้ลงทะเบียนเรียนทั้งหมด: <span class="text-sm text-purple-650 font-black">{{ count($reportData) }}</span> คน
                </p>
            </div>
            <a href="{{ route('courses.edit', $course->id) }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-sm active:scale-95 flex items-center gap-1.5">
                ✏️ แก้ไขหลักสูตร
            </a>
        </div>

        <!-- Student Progress Table Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] shadow-sm overflow-hidden">
            
            <div class="p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-base font-black text-slate-800 dark:text-white leading-tight">ความคืบหน้าและเวลาเรียนรายบุคคล</h3>
                    <p class="text-xs text-slate-450 dark:text-slate-500 font-bold mt-1">สถิติเข้าชั้นเรียนปกติรวมถึงการรับชมวิดีโอคลิปสั้น</p>
                </div>
                <input type="text" id="searchInput" onkeyup="filterTable()" 
                       placeholder="🔍 ค้นหารายชื่อผู้เรียน..." 
                       class="px-3.5 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none w-full sm:w-64">
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table id="reportTable" class="min-w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-900 text-white text-xs font-black uppercase tracking-wider">
                            <th class="p-4 text-center w-12 border-r border-slate-800">ลำดับ</th>
                            <th class="p-4 border-r border-slate-800">ชื่อ-นามสกุล</th>
                            <th class="p-4 border-r border-slate-800 text-center">ความก้าวหน้า</th>
                            <th class="p-4 border-r border-slate-800 text-center">เข้าเรียนล่าสุด</th>
                            <th class="p-4 border-r border-slate-800 text-center">ออกเรียนล่าสุด</th>
                            <th class="p-4 border-r border-slate-800 text-right">เวลาในห้องเรียน</th>
                            <th class="p-4 border-r border-slate-800 text-right">เวลาดูคลิปสั้น</th>
                            <th class="p-4 border-r border-slate-800 text-center">แบบทดสอบ (ผ่าน/ไม่ผ่าน)</th>
                            <th class="p-4 text-right">เวลารวมทั้งหมด</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($reportData as $index => $row)
                            <tr class="hover:bg-purple-50/20 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 text-center font-mono text-slate-400 border-r border-slate-100 dark:border-slate-850">{{ $index + 1 }}</td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850">
                                    <div class="font-black text-slate-800 dark:text-white">{{ $row['user']->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold font-mono mt-0.5">{{ $row['user']->email }}</div>
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850">
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden shrink-0">
                                            <div style="width: {{ $row['progress'] }}%" class="bg-purple-650 h-full rounded-full"></div>
                                        </div>
                                        <span class="text-xs font-black text-slate-800 dark:text-white shrink-0">{{ $row['progress'] }}%</span>
                                        <span class="text-[10px] text-slate-400 font-bold shrink-0">({{ $row['completed_count'] }}/{{ $totalLessons }})</span>
                                    </div>
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850 text-center font-mono text-xs text-slate-700 dark:text-slate-350">
                                    {{ $row['accessed_at'] ? \Carbon\Carbon::parse($row['accessed_at'])->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850 text-center font-mono text-xs text-slate-700 dark:text-slate-350">
                                    {{ $row['exited_at'] ? \Carbon\Carbon::parse($row['exited_at'])->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850 text-right font-mono text-xs font-bold text-slate-700 dark:text-slate-300">
                                    {{ formatDuration($row['classroom_duration']) }}
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850 text-right font-mono text-xs font-bold text-purple-700 dark:text-purple-300">
                                    {{ formatDuration($row['shorts_duration']) }}
                                </td>
                                <td class="p-4 border-r border-slate-100 dark:border-slate-850 text-center font-bold text-xs">
                                    @if($row['total_quizzes'] > 0)
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">ผ่าน: {{ $row['passed_quizzes_count'] }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">ไม่ผ่าน: {{ $row['failed_quizzes_count'] }}</span>
                                            @php
                                                $unattempted = $row['total_quizzes'] - ($row['passed_quizzes_count'] + $row['failed_quizzes_count']);
                                            @endphp
                                            @if($unattempted > 0)
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold tracking-tight">(ยังไม่ทำ: {{ $unattempted }})</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right font-mono text-xs font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50/20">
                                    {{ formatDuration($row['total_duration']) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 dark:text-slate-500 font-bold">
                                    ❌ ยังไม่มีผู้ลงทะเบียนเรียนในหลักสูตรนี้
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Search Script --}}
    <script>
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toUpperCase();
        const table = document.getElementById("reportTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            const tdName = tr[i].getElementsByTagName("td")[1]; // Column ชื่อ-สกุล
            if (tdName) {
                const txtValue = tdName.textContent || tdName.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>
</x-teachers-layout>
