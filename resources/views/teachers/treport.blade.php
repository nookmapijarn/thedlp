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

  <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6 no-print">
      
      <!-- Header Banner Block -->
      <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
          <div class="relative z-10 space-y-3">
              <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[11px] font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                  OLIS Student Analytics
              </span>
              <h1 class="text-3xl font-black text-slate-900 dark:text-white">รายงานผู้เรียนในกลุ่ม</h1>
              <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-bold max-w-3xl mt-1.5">
                  สืบค้นข้อมูลความก้าวหน้า รายชื่อผู้คาดว่าจะจบการศึกษา ชั่วโมงหน่วยกิตกิจกรรมสะสม (กพช.) ผลสอบ N-NET และบริการจัดการบัตรประจำตัวนักศึกษา
              </p>
          </div>
      </div>

      <!-- Single Unified Report Card -->
      <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] shadow-sm overflow-hidden">
          
          <!-- Top Control Header (Form selectors) -->
          <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
              <form method="POST" action="{{ route('treport') }}" class="w-full flex flex-col md:flex-row md:items-end gap-4">
                  @csrf
                  
                  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 flex-1 w-full">
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ภาคเรียน</label>
                          <select required name="semestry" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกภาคเรียน</option>
                              @foreach($all_semestry as $sem)
                                  <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>{{ $sem->SEMESTRY }}</option>
                              @endforeach    
                          </select>
                      </div>

                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ศกร.ระดับตำบล</label>
                          <select required name="tumbon" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกตำบล</option>
                              @foreach($all_tumbon as $tm)
                                  <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>{{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}</option>
                              @endforeach    
                          </select>
                      </div>

                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ระดับชั้น</label>
                          <select required name="lavel" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกระดับชั้น</option>
                              <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                              <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                              <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                          </select>
                      </div>

                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ประเภทรายงาน</label>
                          <select required name="studreport" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกประเภทรายงาน</option>
                              <option @if($studreport == "นักศึกษาทั้งหมด") selected @endif value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                              <option @if($studreport == "เฉพาะผู้คาดว่าจะจบ") selected @endif value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                          </select>
                      </div>
                  </div>

                  <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-sm active:scale-95 flex items-center justify-center gap-1.5 h-[38px] flex-shrink-0">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                      </svg>
                      ดึงรายงาน
                  </button>
              </form>
          </div>

          <!-- Inline Statistics Summary Bar -->
          @if($data)
              @php
                  $totalStudents = count($data);
                  $expFinCount = collect($data)->where('expfin', 1)->count();
                  $passedActivity = collect($data)->where('activity', '>=', 200)->count();
                  $passedNnet = collect($data)->filter(function($item) {
                      return str_contains($item['nt_sem'] ?? '', 'ผ่านแล้ว') || str_contains($item['nt_sem'] ?? '', 'E-Exam');
                  })->count();
              @endphp
              <div class="p-4 sm:p-5 bg-purple-50/40 dark:bg-purple-950/10 border-b border-slate-150 dark:border-slate-800 grid grid-cols-2 md:flex md:flex-wrap gap-3 md:gap-x-6 items-center text-sm font-black text-slate-500 dark:text-slate-400">
                  <span class="text-[11.5px] uppercase text-purple-700 dark:text-purple-400 tracking-wider col-span-2 md:col-span-1">📊 สรุปรายงาน:</span>
                  <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 md:bg-transparent dark:md:bg-transparent p-2 md:p-0 rounded-xl border border-slate-100 dark:border-slate-800 md:border-0 shadow-sm md:shadow-none">
                      <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                      <span>นักศึกษาทั้งหมด: <strong class="text-slate-800 dark:text-white font-black">{{ $totalStudents }}</strong> คน</span>
                  </div>
                  <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 md:bg-transparent dark:md:bg-transparent p-2 md:p-0 rounded-xl border border-slate-100 dark:border-slate-800 md:border-0 shadow-sm md:shadow-none">
                      <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                      <span>คาดว่าจะจบ: <strong class="text-purple-700 dark:text-purple-300 font-black">{{ $expFinCount }}</strong> คน</span>
                  </div>
                  <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 md:bg-transparent dark:md:bg-transparent p-2 md:p-0 rounded-xl border border-slate-100 dark:border-slate-800 md:border-0 shadow-sm md:shadow-none">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                      <span>กพช. ผ่านเกณฑ์: <strong class="text-emerald-700 dark:text-emerald-300 font-black">{{ $passedActivity }}</strong> คน</span>
                  </div>
                  <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 md:bg-transparent dark:md:bg-transparent p-2 md:p-0 rounded-xl border border-slate-100 dark:border-slate-800 md:border-0 shadow-sm md:shadow-none">
                      <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                      <span>N-NET ผ่านเกณฑ์: <strong class="text-indigo-700 dark:text-indigo-300 font-black">{{ $passedNnet }}</strong> คน</span>
                  </div>
              </div>

              <!-- Table Action & Print Controls -->
              <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-slate-55/10">
                  <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-3">
                      <h4 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-wider">
                          กลุ่มตำบล: {{ $tumbon }}
                      </h4>
                      <span class="hidden sm:inline w-px h-4 bg-slate-200 dark:bg-slate-700"></span>
                      <span class="text-sm text-slate-450 dark:text-slate-500 font-bold">เลือกเช็กบ็อกซ์เพื่อเลือกพิมพ์เฉพาะเจาะจงได้</span>
                  </div>
                  
                  <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                      <input type="text" id="tableSearch" placeholder="🔍 ค้นหาชื่อ หรือ รหัสนักศึกษา..." 
                             class="px-3.5 py-2 bg-slate-100/50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none w-full sm:w-56">
                      
                      <div class="grid grid-cols-2 gap-2 w-full sm:w-auto sm:flex sm:gap-3">
                          <button onclick="printSelectedFront()" class="px-4 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                              📇 <span id="printFrontBtnText">พิมพ์หน้าบัตร</span>
                          </button>
                          <button onclick="printSelectedBack()" class="px-4 py-2.5 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-sm active:scale-95 flex items-center justify-center gap-1.5">
                              🔄 <span id="printBackBtnText">พิมพ์หลังบัตร</span>
                          </button>
                      </div>
                  </div>
              </div>

              <!-- Table Data List -->
              <div class="overflow-x-auto scrollbar-thin">
                  <table id="studentTable" class="min-w-full text-base">
                      <thead>
                          <tr class="bg-slate-900 text-white">
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center no-sort w-12">
                                  <input type="checkbox" id="selectAllStudents" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 w-4 h-4 cursor-pointer">
                              </th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center no-sort w-16">รูป</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center">รหัสนักศึกษา ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-left">ชื่อ-นามสกุล ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center">คาดว่าจะจบ ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center">ชั่วโมง กพช. ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center">ผล N-NET ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center">สถานะภาพ ↕</th>
                              <th class="p-4 text-sm font-black uppercase tracking-wider text-center no-sort">เครื่องมือ</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                          @foreach($data as $d)
                          <tr class="hover:bg-purple-50/20 dark:hover:bg-slate-800/40 transition-colors @if($d['lavel']==1) bg-pink-50/10 @elseif($d['lavel']==2) bg-emerald-50/10 @else bg-amber-50/10 @endif">
                              <td class="p-4 text-center">
                                  <input type="checkbox" class="student-select-checkbox rounded border-slate-350 dark:border-slate-750 text-purple-650 focus:ring-purple-500 w-4 h-4 cursor-pointer" data-student="{{ json_encode($d) }}">
                              </td>
                              <td class="p-4 text-center">
                                  <img class="w-10 h-12 object-cover rounded-xl shadow-sm border border-slate-200/60 dark:border-slate-800 mx-auto" 
                                       src="https://phothongdlec.ac.th/storage/images/avatar/{{$d['id']}}.png" 
                                       onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                              </td>
                              <td class="p-4 text-center font-mono font-bold text-slate-700 dark:text-slate-300">{{$d['id']}}</td>
                              <td class="p-4 font-black text-slate-800 dark:text-white text-left">{{$d['prename']}}{{$d['name']}} {{$d['surname']}}</td>
                              <td class="p-4 text-center" data-order="{{$d['expfin']}}">
                                  @if($d['expfin']==1) 
                                      <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-950/80 text-purple-700 dark:text-purple-300 font-bold text-sm">✓</span> 
                                  @else 
                                      <span class="text-slate-300 dark:text-slate-650 font-bold">-</span> 
                                  @endif
                              </td>
                              <td class="p-4 text-center font-black {{ $d['activity'] >= 200 ? 'text-emerald-600' : 'text-amber-500' }}">
                                  {{$d['activity']}}
                              </td>
                              <td class="p-4 text-center font-bold text-purple-650 dark:text-purple-400">{{$d['nt_sem']}}</td>
                              <td class="p-4 text-center" data-order="{{$d['fin_cause']}}">
                                  <span class="px-3 py-1.5 rounded-full text-[12.5px] font-black tracking-wide {{ $d['fin_cause']==1 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                      {{ $d['fin_cause']==1 ? 'จบหลักสูตร' : 'กำลังศึกษา' }}
                                  </span>
                              </td>
                              <td class="p-4 text-center">
                                  <button onclick="prepareAndPrint('single', {{ json_encode($d) }})" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                      พิมพ์บัตร
                                  </button>
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          @endif
      </div>

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

    // Checkbox print selection logic
    function updatePrintButtons() {
        const checkedBoxes = $('.student-select-checkbox:checked');
        const count = checkedBoxes.length;
        if (count > 0) {
            $('#printFrontBtnText').text(`พิมพ์หน้าบัตรที่เลือก (${count} คน)`);
            $('#printBackBtnText').text(`พิมพ์หลังบัตรที่เลือก (${count} คน)`);
        } else {
            $('#printFrontBtnText').text('พิมพ์หน้าบัตรทั้งหมด');
            $('#printBackBtnText').text('พิมพ์หลังบัตรทั้งหมด');
        }
    }

    $(document).on('change', '.student-select-checkbox', function() {
        updatePrintButtons();
    });

    $(document).on('change', '#selectAllStudents', function() {
        $('.student-select-checkbox').prop('checked', this.checked);
        updatePrintButtons();
    });

    window.printSelectedFront = function() {
        const checkedBoxes = $('.student-select-checkbox:checked');
        let list = [];
        if (checkedBoxes.length > 0) {
            checkedBoxes.each(function() {
                list.push($(this).data('student'));
            });
        } else {
            list = @json($data ?? []);
        }
        prepareAndPrint('front_all', list);
    };

    window.printSelectedBack = function() {
        const checkedBoxes = $('.student-select-checkbox:checked');
        let list = [];
        if (checkedBoxes.length > 0) {
            checkedBoxes.each(function() {
                list.push($(this).data('student'));
            });
        } else {
            list = @json($data ?? []);
        }
        prepareAndPrint('back_all', list);
    };
});

// ฟังก์ชันสั่งพิมพ์เฉพาะส่วน
function printTable() {
    window.print();
}
</script>

<style>
    @media (max-width: 640px) {
        /* Hide table header */
        #studentTable thead {
            display: none;
        }
        
        /* Make table rows look like cards */
        #studentTable tbody tr {
            display: block;
            margin-bottom: 1rem;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: relative;
        }
        
        /* Make table cells act as block elements */
        #studentTable tbody tr td {
            display: block;
            padding: 0.25rem 0 !important;
            border: none !important;
            text-align: left !important;
            width: 100% !important;
        }
        
        /* Checkbox absolute top-right */
        #studentTable tbody tr td:nth-child(1) {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: auto !important;
            z-index: 10;
            padding: 0 !important;
        }
        
        /* Avatar left floating */
        #studentTable tbody tr td:nth-child(2) {
            float: left;
            width: 60px !important;
            margin-right: 0.75rem;
            margin-bottom: 0.5rem;
            padding: 0 !important;
        }
        
        /* Student ID */
        #studentTable tbody tr td:nth-child(3) {
            font-family: monospace;
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.125rem;
        }
        #studentTable tbody tr td:nth-child(3)::before {
            content: "รหัสนักศึกษา: ";
            font-weight: bold;
        }
        
        /* Name-surname */
        #studentTable tbody tr td:nth-child(4) {
            font-weight: 950;
            font-size: 0.95rem;
            color: #1e293b;
            white-space: normal !important;
            margin-bottom: 0.5rem;
            line-height: 1.25;
            padding-right: 2.5rem !important; /* Avoid overlap with checkbox */
        }
        
        /* Clear floating */
        #studentTable tbody tr td:nth-child(4)::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Expected graduation, Activity, N-NET, Status - inline inline-block */
        #studentTable tbody tr td:nth-child(5),
        #studentTable tbody tr td:nth-child(6),
        #studentTable tbody tr td:nth-child(7),
        #studentTable tbody tr td:nth-child(8) {
            display: inline-block;
            width: auto !important;
            margin-right: 0.35rem;
            font-size: 0.75rem;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9 !important;
            padding: 0.25rem 0.5rem !important;
            border-radius: 0.5rem;
        }
        
        /* Add labels */
        #studentTable tbody tr td:nth-child(5)::before {
            content: "คาดจบ: ";
            font-weight: bold;
            color: #64748b;
        }
        
        #studentTable tbody tr td:nth-child(6)::before {
            content: "กพช: ";
            font-weight: bold;
            color: #64748b;
        }
        
        #studentTable tbody tr td:nth-child(7)::before {
            content: "N-NET: ";
            font-weight: bold;
            color: #64748b;
        }
        
        /* Action button */
        #studentTable tbody tr td:nth-child(9) {
            margin-top: 0.75rem;
            border-top: 1px dashed #f1f5f9 !important;
            padding-top: 0.75rem !important;
            display: flex;
            justify-content: flex-end;
        }
    }
</style>

</x-teachers-layout>