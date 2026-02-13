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
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="reportTable">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-100">
                                <th class="px-6 py-4">อันดับ</th>
                                <th class="px-6 py-4">ข้อมูลนักเรียน</th>
                                <th class="px-6 py-4 text-center">จำนวนครั้ง</th>
                                <th class="px-6 py-4 text-center">เวลาที่ใช้</th>
                                <th class="px-6 py-4 text-center">คะแนนสูงสุด</th>
                                <th class="px-6 py-4 text-center">สถานะ</th>
                                {{-- <th class="px-6 py-4 text-right">ใบรับรอง</th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($attempts as $index => $attempt)
                                @php 
                                    $scorePercent = ($attempt->total_score / ($quiz->total_score ?: 1)) * 100;
                                    $isPassed = $scorePercent >= $quiz->pass_percentage;
                                    $statusStr = $isPassed ? 'passed' : 'failed';
                                @endphp
                                <tr class="student-row hover:bg-slate-50/50 transition-colors" 
                                    data-name="{{ strtolower($attempt->full_name) }}" 
                                    data-id="{{ strtolower($attempt->student_id) }}"
                                    data-status="{{ $statusStr }}">
                                    
                                    <td class="px-6 py-4 text-slate-400 font-bold text-xs">#{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-bold text-slate-700">{{ $attempt->full_name }}</p>
                                            <p class="text-xs text-slate-400 font-mono">{{ $attempt->student_id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 bg-slate-100 rounded-md text-xs font-bold text-slate-600">{{ $attempt->attempt_count }} ครั้ง</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-xs font-mono text-slate-500">
                                        {{ $attempt->duration_text }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="font-black text-lg {{ $isPassed ? 'text-indigo-600' : 'text-slate-400' }}">
                                                {{ $attempt->total_score }}
                                            </span>
                                            <span class="text-[10px] text-slate-300">เต็ม {{ $quiz->total_score }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $isPassed ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                            {{ $isPassed ? 'ผ่านเกณฑ์' : 'ไม่ผ่าน' }}
                                        </span>
                                    </td>
                                    {{-- <td class="px-6 py-4 text-right">
                                        @if($isPassed)
                                            <button onclick="prepareAndDownloadPDF(
                                                '{{ $attempt->full_name }}', 
                                                '{{ $attempt->total_score }}', 
                                                '{{ $quiz->total_score }}', 
                                                '{{ $quiz->title }}',
                                                '{{ $quiz->certificate_image }}'
                                            )" class="text-indigo-600 bg-indigo-50 p-2 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span class="text-slate-200">-</span>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                            <tr id="noDataRow" class="hidden">
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                    <p>ไม่พบข้อมูลที่ค้นหา</p>
                                </td>
                            </tr>
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
</x-teachers-layout>