<x-teachers-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        /* ซ่อน PDF Generator */
        #pdf-generator-wrapper { position: fixed; top: -9999px; left: -9999px; visibility: hidden; z-index: -1; }
    </style>

    <div class="p-6 mt-16 animate-fade-in">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h1 class="text-2xl font-black text-slate-800">{{ $quiz->title }}</h1>
                    <p class="text-slate-500">รายงานผลการสอบ (แสดงคะแนนสูงสุดรายบุคคล)</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="exportTableToCSV('report-{{ $quiz->title }}.csv')" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Export CSV
                    </button>
                    <div class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl font-bold text-sm flex items-center">
                        รวม: {{ $attempts->count() }} คน
                    </div>
                </div>
            </div>

            {{-- ค้นหาและกรอง --}}
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 justify-between items-center">
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="searchInput" onkeyup="filterTable()" 
                           placeholder="ค้นหาชื่อ หรือ รหัสนักเรียน..." 
                           class="pl-10 w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <label class="text-sm font-bold text-slate-600 whitespace-nowrap">สถานะ:</label>
                    <select id="statusFilter" onchange="filterTable()" class="rounded-xl border-slate-200 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 w-full md:w-48">
                        <option value="all">ทั้งหมด (All)</option>
                        <option value="passed">ผ่าน (Passed)</option>
                        <option value="failed">ไม่ผ่าน (Failed)</option>
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100">
                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
                    <table class="w-full text-left border-collapse" id="reportTable">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-600 font-semibold text-[11px] uppercase tracking-widest border-b border-slate-200">
                                <th class="px-6 py-4 text-center w-16">อันดับ</th>
                                <th class="px-6 py-4 w-32">ภาพถ่ายขณะสอบ</th>
                                <th class="px-6 py-4">ข้อมูลนักเรียน</th>
                                <th class="px-6 py-4 text-center">พิกัด (Location)</th>
                                <th class="px-6 py-4 text-center">ทำครั้ง</th>
                                <th class="px-6 py-4 text-center">พฤติกรรม</th>
                                <th class="px-6 py-4 text-center text-nowrap">เวลาที่ใช้</th>
                                <th class="px-6 py-4 text-center">คะแนนสูงสุด</th>
                                <th class="px-6 py-4 text-center w-28">สถานะ</th>
                                <th class="px-6 py-4 text-center w-20">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($attempts as $index => $attempt)
                                @php 
                                    $scorePercent = ($attempt->total_score / ($quiz->total_score ?: 1)) * 100;
                                    $isPassed = $scorePercent >= $quiz->pass_percentage;
                                    $statusStr = $isPassed ? 'passed' : 'failed';
                                    $hasCheatingRisk = $attempt->tab_switch_count > 0;
                                @endphp
                                <tr class="student-row hover:bg-slate-50 transition-all duration-200 group" 
                                    data-name="{{ strtolower($attempt->full_name) }}" 
                                    data-id="{{ strtolower($attempt->student_id) }}"
                                    data-status="{{ $statusStr }}">
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-slate-400 font-bold text-sm">#{{ $loop->iteration }}</span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="relative group/photo cursor-pointer">
                                            <img src="{{ $attempt->start_photo }}" 
                                                class="w-24 h-16 rounded shadow-sm object-cover border border-slate-200 group-hover/photo:border-indigo-400 transition-all">
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-bold text-slate-800 leading-none mb-1">{{ $attempt->full_name }}</p>
                                            <p class="text-[10px] text-slate-500 font-mono">ID: {{ $attempt->student_id }}</p>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <a href="https://www.google.com/maps?q={{ $attempt->latitude }},{{ $attempt->longitude }}" 
                                            target="_blank"
                                            class="flex items-center gap-1 text-[10px] font-mono text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                Map
                                            </a>
                                            <span class="text-[9px] text-slate-400 font-mono mt-1">{{ number_format($attempt->latitude, 4) }}, {{ number_format($attempt->longitude, 4) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                         {{ $attempt->attempt_count }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs {{ $hasCheatingRisk ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                            สลับจอ: {{ $attempt->tab_switch_count }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-mono text-slate-500">{{ $attempt->duration_text }}</span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center leading-none">
                                            <span class="text-lg font-black {{ $isPassed ? 'text-indigo-600' : 'text-slate-400' }}">
                                                {{ $attempt->total_score }}
                                            </span>
                                            <span class="text-[9px] text-slate-400 uppercase">Score</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $isPassed ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $isPassed ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button type="button" 
                                                onclick="confirmDeleteAll('{{ $attempt->user_id }}', '{{ $quiz->id }}', '{{ $attempt->full_name }}')" 
                                                class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <form id="delete_all_form" action="{{ route('attempts.delete_all') }}" method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="user_id" id="delete_user_id">
                                            <input type="hidden" name="quiz_id" id="delete_quiz_id">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Template สำหรับสร้าง PDF (ซ่อนไว้) --}}
    <div id="pdf-generator-wrapper">
        <div id="certificate-content" style="width: 297mm; height: 210mm; position: relative; background: white; overflow: hidden;">
            {{-- เปลี่ยนเป็นแท็ก img โดยตรงเพื่อให้ JS เข้าถึงง่าย --}}
            <img id="cert-bg" src="" style="position: absolute; inset: 0; width: 100%; height: 100%; z-index: 0; object-fit: cover;">
            
            <div style="position: relative; z-index: 10; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; text-align: center; padding-top: 55mm; color: #1e293b;">
                <h1 style="font-size: 54pt; font-weight: bold; color: #1e3a8a; margin: 0 0 10pt 0; line-height: 1;">ประกาศนียบัตร</h1>
                <p style="font-size: 24pt; color: #4338ca; margin: 0;">ใบประกาศนียบัตรให้ไว้เพื่อแสดงว่า</p>
                <div style="margin: 25pt 0;">
                    <h2 id="pdf-name" style="font-size: 58pt; font-weight: bold; color: #0f172a; border-bottom: 3pt solid #cbd5e1; display: inline-block; padding: 0 40pt; line-height: 1.2;">ชื่อ-นามสกุล</h2>
                </div>
                <div style="max-width: 80%;">
                    <p style="font-size: 26pt; color: #475569; margin: 0;">ได้ผ่านการทำแบบทดสอบหลักสูตร</p>
                    <p id="pdf-title" style="font-size: 32pt; font-weight: 900; color: #3730a3; margin: 15pt 0; line-height: 1.1;">ชื่อหลักสูตร</p>
                    <p style="font-size: 18pt; color: #64748b; margin-top: 20pt;">ให้ไว้ ณ วันที่ {{ now()->locale('th')->isoFormat('D MMMM YYYY') }}</p>
                </div>
                <div style="position: absolute; bottom: 30mm; left: 30mm; right: 30mm; display: flex; justify-content: space-between; align-items: flex-end;">
                    <div style="text-align: left; background: rgba(255,255,255,0.8); padding: 15pt; border-radius: 15pt; border: 1pt solid #e2e8f0;">
                        <p style="font-size: 12pt; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin: 0;">ผลคะแนนที่ได้</p>
                        <p style="font-size: 28pt; font-weight: 900; color: #4f46e5; margin: 0;"><span id="pdf-score">0</span> <span style="font-size: 16pt; color: #cbd5e1; font-weight: normal;">/ <span id="pdf-total">0</span> คะแนน</span></p>
                    </div>
                    <div style="text-align: center;">
                        <div style="width: 250pt; border-bottom: 2pt solid #94a3b8; margin-bottom: 10pt;"></div>
                        <p style="font-size: 18pt; font-weight: bold; color: #334155; margin: 0;">ประธานการจัดงาน</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.student-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = (row.getAttribute('data-name') || '');
                const id = (row.getAttribute('data-id') || '');
                const status = row.getAttribute('data-status');
                const matchesSearch = name.includes(searchText) || id.includes(searchText);
                const matchesStatus = statusFilter === 'all' || status === statusFilter;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataRow = document.getElementById('noDataRow');
            if (noDataRow) visibleCount === 0 ? noDataRow.classList.remove('hidden') : noDataRow.classList.add('hidden');
        }

        async function prepareAndDownloadPDF(name, score, total, title, bgUrl) {
            const btn = event.currentTarget; 
            const originalBtnHtml = btn.innerHTML;
            const element = document.getElementById('certificate-content');
            const certBg = document.getElementById('cert-bg');

            // 1. อัปเดตข้อมูล
            document.getElementById('pdf-name').innerText = name;
            document.getElementById('pdf-score').innerText = score;
            document.getElementById('pdf-total').innerText = total;
            document.getElementById('pdf-title').innerText = title;
            certBg.src = bgUrl;

            btn.innerHTML = '<span class="text-[10px] animate-pulse">⏳...</span>';
            btn.disabled = true;

            try {
                // รอรูปโหลด
                await new Promise((resolve, reject) => {
                    if (certBg.complete) resolve();
                    certBg.onload = resolve;
                    certBg.onerror = () => reject("ไม่สามารถโหลดรูปพื้นหลังได้");
                    setTimeout(() => reject("โหลดรูปนานเกินไป"), 8000);
                });

                const opt = {
                    margin: 0,
                    filename: `Cert-${name}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, letterRendering: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
                };

                await html2pdf().set(opt).from(element).save();
            } catch (error) {
                alert("เกิดข้อผิดพลาด: " + error);
            } finally {
                btn.innerHTML = originalBtnHtml;
                btn.disabled = false;
            }
        }

        function exportTableToCSV(filename) {
            const csv = [];
            const rows = document.querySelectorAll("#reportTable tr");
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display === 'none' || rows[i].id === 'noDataRow') continue;
                const row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length - 1; j++) {
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    row.push('"' + data.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }
            const csvBlob = new Blob(["\ufeff" + csv.join("\n")], { type: "text/csv;charset=utf-8;" });
            const link = document.createElement("a");
            link.href = URL.createObjectURL(csvBlob);
            link.download = filename;
            link.click();
        }
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDeleteAll(userId, quizId, name) {
    Swal.fire({
        title: 'ยืนยันลบข้อมูลทั้งหมด?',
        text: `ประวัติการสอบทั้งหมด (${name}) ในวิชานี้จะถูกลบออกทั้งหมดและไม่สามารถกู้คืนได้`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'ยืนยันลบประวัติการสอบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete_user_id').value = userId;
            document.getElementById('delete_quiz_id').value = quizId;
            document.getElementById('delete_all_form').submit();
        }
    })
}
</script>
<style>
        .swal2-confirm {background-color: red !important;}
        .swal2-cancel {background-color: rgb(196, 196, 196) !important;}
</style>
</x-teachers-layout>