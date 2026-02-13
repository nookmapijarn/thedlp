<x-teachers-layout>
  <style>
      /* --- ส่วนหน้าจอปกติ --- */
      .no-print { display: block; }
      #print-section { display: none; }

      /* --- ส่วนการตั้งค่าการพิมพ์ --- */
      @media print {
          body * { visibility: hidden; }
          #print-section { 
              display: block !important; 
              visibility: visible !important; 
              position: absolute; 
              left: 0; top: 0; width: 100%; 
          }
          #print-section * { visibility: visible !important; }
          @page { size: A4; margin: 1cm; }

          /* การจัดเรียงบัตร */
          .print-grid {
              display: grid;
              grid-template-columns: 8.56cm 8.56cm;
              justify-content: center;
              gap: 10px;
          }

          /* คำสั่งขึ้นหน้าใหม่ (สำหรับพิมพ์คนละหน้า) */
          .page-break { page-break-before: always; }

          .official-card { 
              width: 8.56cm; height: 5.4cm; 
              border: 0.5pt solid #000; 
              background: white !important; 
              position: relative; box-sizing: border-box; 
              overflow: hidden; page-break-inside: avoid;
              margin-bottom: 10px;
          }
          
          .card-content-print { padding: 0.2cm 0.3cm; height: 100%; display: flex; flex-direction: column; }
          .card-header-print { text-align: center; margin-bottom: 2px; border-bottom: 0.2pt solid #eee; }
          .card-header-print p { margin: 0; line-height: 1.2; font-size: 8pt; font-weight: bold; }
          .card-body-print { display: flex; margin-top: 5px; }
          .photo-box-print { width: 2.1cm; height: 2.7cm; border: 0.5pt solid black; flex-shrink: 0; overflow: hidden; }
          .photo-box-print img { width: 100%; height: 100%; object-fit: cover; }
          .info-box-print { flex-grow: 1; padding-left: 0.2cm; display: flex; flex-direction: column; align-items: center; }
          .digit-row { display: flex; margin-top: 2px; }
          .d-cell { width: 16px; height: 20px; border: 0.5pt solid black; border-right: none; display: flex; align-items: center; justify-content: center; font-size: 9pt; font-weight: bold; }
          .d-cell:last-child { border-right: 0.5pt solid black; }
          .name-display-print { font-size: 10pt; font-weight: bold; margin-top: 5px; text-align: center; }
          
          /* ลายเซ็น */
          .card-footer-print { position: absolute; bottom: 0.4cm; left: 0.3cm; right: 0.3cm; display: flex; justify-content: space-between; font-size: 6pt; }
          .sign-underline { width: 1.8cm; border-bottom: 0.5pt solid black; margin-bottom: 2px; height: 10px; }
          
          .card-back { display: flex; flex-direction: column; align-items: center; justify-content: center; }
          .qr-code-print { width: 2.5cm; height: 2.5cm; display: block; }
      }
  </style>

  <div id="print-section"></div>

  <div class="p-2 no-print">
    <div class="mt-14">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="bg-indigo-600 p-4">
                <h3 class="text-white text-lg font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    รายงานสำหรับครู
                </h3>
            </div>

            <form method="POST" action="{{ route('treport') }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">ภาคเรียน</label>
                        <div class="relative">
                            <select required name="semestry" class="appearance-none w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all">
                                <option value="">เลือกภาคเรียน</option>
                                @foreach($all_semestry as $sem)
                                    <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>{{ $sem->SEMESTRY }}</option>
                                @endforeach    
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">ศกร.ระดับตำบล</label>
                        <div class="relative">
                            <select required name="tumbon" class="appearance-none w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all">
                                <option value="">เลือกตำบล</option>
                                @foreach($all_tumbon as $tm)
                                    <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>{{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}</option>
                                @endforeach    
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">ระดับชั้น</label>
                        <div class="relative">
                            <select required name="lavel" class="appearance-none w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all">
                                <option value="">เลือกระดับชั้น</option>
                                <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                                <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                                <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 ml-1">รายงาน</label>
                        <div class="relative">
                            <select required name="studreport" class="appearance-none w-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all">
                                <option value="">เลือกประเภทรายงาน</option>
                                <option @if($studreport == "นักศึกษาทั้งหมด") selected @endif value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                                <option @if($studreport == "เฉพาะผู้คาดว่าจะจบ") selected @endif value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="group relative flex justify-center w-full md:w-64 mx-auto p-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 dark:shadow-none transition-all duration-200 hover:scale-[1.02] active:scale-95">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            ดึงข้อมูลรายงาน
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>


@if($data) 
    <div class="mt-4 p-4 bg-white rounded-t-xl border border-b-0 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm">
        <div>
            <div class="text-xl font-bold text-slate-800">รายชื่อกลุ่ม: {{ $tumbon }}</div>
            <p class="text-sm text-gray-500">จัดการข้อมูลนักศึกษาและพิมพ์บัตร</p>
        </div>
        
        <div class="flex flex-wrap gap-2 justify-center">

            <button onclick="prepareAndPrint('front_all', {{ json_encode($data) }})" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                พิมพ์หน้าบัตรทั้งหมด
            </button>
            <button onclick="prepareAndPrint('back_all', {{ json_encode($data) }})" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-700 shadow-lg shadow-orange-200 transition">
                พิมพ์หลังบัตรทั้งหมด
            </button>
        </div>
    </div>

    <div class="shadow-xl border border-gray-100 rounded-b-2xl bg-white overflow-hidden">
        <div class="p-4 border-b bg-gray-50/50">
            <input type="text" id="tableSearch" placeholder="🔍 ค้นหาชื่อ หรือ รหัสนักศึกษา..." 
                   class="w-full md:w-80 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
        </div>

        <div class="overflow-x-auto">
            <table id="studentTable" class="min-w-full text-sm">
                <thead>
                    <tr class="bg-slate-800 text-white uppercase tracking-wider">
                        <th class="p-4 font-semibold no-sort">รูป</th>
                        <th class="p-4 font-semibold cursor-pointer">รหัสนักศึกษา ↕</th>
                        <th class="p-4 font-semibold text-left cursor-pointer">ชื่อ-นามสกุล ↕</th>
                        <th class="p-4 font-semibold cursor-pointer text-center">จะจบ ↕</th>
                        <th class="p-4 font-semibold cursor-pointer text-center">กพช. ↕</th>
                        <th class="p-4 font-semibold cursor-pointer text-center">N-NET ↕</th>
                        <th class="p-4 font-semibold cursor-pointer text-center">สถานะ ↕</th>
                        <th class="p-4 font-semibold no-sort text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($data as $d)
                    <tr class="hover:bg-gray-50 transition-colors @if($d['lavel']==1) bg-pink-50/30 @elseif($d['lavel']==2) bg-green-50/30 @else bg-yellow-50/30 @endif">
                        <td class="p-3 text-center">
                            <img class="w-10 h-12 object-cover rounded shadow-sm border border-gray-200 mx-auto" 
                                 src="https://phothongdlec.ac.th/storage/images/avatar/{{$d['id']}}.png" 
                                 onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                        </td>
                        <td class="p-3 text-center font-mono font-medium text-gray-700">{{$d['id']}}</td>
                        <td class="p-3 font-semibold text-gray-800">{{$d['prename']}}{{$d['name']}} {{$d['surname']}}</td>
                        <td class="p-3 text-center" data-order="{{$d['expfin']}}">
                            @if($d['expfin']==1) <span class="text-indigo-600 font-bold">✓</span> @else <span class="text-gray-300">-</span> @endif
                        </td>
                        <td class="p-3 text-center font-bold {{ $d['activity'] >= 200 ? 'text-green-600' : 'text-orange-500' }}">
                            {{$d['activity']}}
                        </td>
                        <td class="p-3 text-center font-bold text-blue-600">{{$d['nt_sem']}}</td>
                        <td class="p-3 text-center" data-order="{{$d['fin_cause']}}">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold {{ $d['fin_cause']==1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $d['fin_cause']==1 ? 'จบ' : 'ศึกษาอยู่' }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <button onclick="prepareAndPrint('single', {{ json_encode($d) }})" class="px-3 py-1.5 bg-slate-700 text-white rounded-lg text-xs hover:bg-slate-900 transition-all shadow-md">
                                พิมพ์
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

  </div>

  <script>
    function getCardFront(s) {
        const idCells = (s.id || '0000000000').toString().split('').map(char => `<div class="d-cell">${char}</div>`).join('');
        const lv = s.lavel == 3 ? 'มัธยมศึกษาตอนปลาย' : (s.lavel == 2 ? 'มัธยมศึกษาตอนต้น' : 'ประถมศึกษา');
        return `
        <div class="official-card">
            <div class="card-content-print">
                <div class="card-header-print"><p>บัตรประจำตัวนักศึกษาระดับ ${lv}</p><p>{{ config('app.name_th') }} (กลุ่ม {{ $tumbon }})</p></div>
                <div class="card-body-print">
                    <div class="photo-box-print"><img src="https://phothongdlec.ac.th/storage/images/avatar/${s.id}.png" onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'"></div>
                    <div class="info-box-print">
                        <div style="font-size: 6pt; font-style: italic; width: 100%; text-align: center;">วิธีเรียน กศน. แบบพบกลุ่ม</div>
                        <div style="font-size:7pt;">รหัสประจำตัวนักศึกษา</div>
                        <div class="digit-row">${idCells}</div>
                        <div class="name-display-print">${s.prename || ''}${s.name} ${s.surname}</div>
                    </div>
                </div>
                <div class="card-footer-print">
                    <div style="text-align:center"><div class="sign-underline"></div>ลงชื่อนักศึกษา</div>
                    <div style="text-align:center"><div class="sign-underline"></div>ผู้อำนวยการสถานศึกษา</div>
                </div>
            </div>
        </div>`;
    }

    function getCardBack(s) {
        return `
        <div class="official-card card-back">
            <img class="qr-code-print" src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${s.id}">
            <div style="font-weight:bold; margin-top:5px; font-size:12pt;">${s.id}</div>
            <div style="font-size:7pt; text-align:center;">{{ config('app.name_th') }}</div>
        </div>`;
    }

    async function prepareAndPrint(mode, data) {
        const container = document.getElementById('print-section');
        let html = '';
        
        if (mode === 'single') {
            // กรณีพิมพ์รายคน: หน้าบัตรอยู่แผ่น 1, หลังบัตรอยู่แผ่น 2
            html = `<div class="print-grid">${getCardFront(data)}</div>`;
            html += `<div class="page-break print-grid">${getCardBack(data)}</div>`;
        } else if (mode === 'front_all') {
            html = '<div class="print-grid">' + data.map(s => getCardFront(s)).join('') + '</div>';
        } else if (mode === 'back_all') {
            html = '<div class="print-grid">' + data.map(s => getCardBack(s)).join('') + '</div>';
        }
        
        container.innerHTML = html;

        // รอโหลดภาพและ QR
        const images = container.getElementsByTagName('img');
        await Promise.all(Array.from(images).map(img => {
            return new Promise(resolve => {
                if (img.complete) resolve();
                else { img.onload = resolve; img.onerror = resolve; }
            });
        }));

        window.print();
    }
  </script>


<style>
    /* CSS สำหรับการจัดระเบียบเวลาพิมพ์ */
    @media print {
        /* ซ่อนทุกอย่างที่ไม่ต้องการ */
        header, footer, nav, .no-print, #control-panel, #tableSearch, .dataTables_paginate, .dataTables_info {
            display: none !important;
        }

        /* ปรับแต่งพื้นที่ที่จะพิมพ์ */
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        #printable-area {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            position: absolute;
            left: 0;
            top: 0;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        th, td {
            border: 1px solid #ddd !important; /* เพิ่มเส้นขอบเวลาพิมพ์ */
            padding: 8px !important;
        }

        th {
            background-color: #1e293b !important; /* สี Slate-800 ให้ติดมาด้วย */
            color: white !important;
            -webkit-print-color-adjust: exact;
        }

        /* บังคับให้สีพื้นหลังแถวติดมาด้วย */
        tr {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* ปรับแต่ง Scrollbar สำหรับมือถือให้ดูดี */
    .scrollbar-thin::-webkit-scrollbar { height: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: #f1f1f1; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function() {
    var table = $('#studentTable').DataTable({
        "dom": 'rtp',
        "pageLength": -1, // แสดงทุกคนในหน้าเดียวเพื่อให้พิมพ์ได้ครบ
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Thai.json"
        },
        "columnDefs": [
            { "targets": 'no-sort', "orderable": false }
        ]
    });

    $('#tableSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
});

// ฟังก์ชันสั่งพิมพ์เฉพาะส่วน
function printTable() {
    window.print();
}
</script>

</x-teachers-layout>