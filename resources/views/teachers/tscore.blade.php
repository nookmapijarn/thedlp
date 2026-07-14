<x-teachers-layout>
  <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6 no-print">
      
      <!-- Header Banner Block -->
      <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
          <div class="relative z-10 space-y-3">
              <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[11px] font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                  OLIS Student Analytics
              </span>
              <h1 class="text-3xl font-black text-slate-900 dark:text-white">ผลการพัฒนาคุณภาพผู้เรียน (กศน.4)</h1>
              <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-bold max-w-3xl mt-1.5">
                  บันทึกและประเมินผลการเรียนเฉลี่ยรายวิชา คะแนนการสอบปลายภาคและสอบซ่อมของผู้เรียนรายกลุ่มระดับตำบล
              </p>
          </div>
      </div>

      <!-- Single Unified Report Card -->
      <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] shadow-sm overflow-hidden">
          
          <!-- Top Control Header (Form selectors) -->
          <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/20">
              <form method="GET" action="{{ route('tscore') }}" class="w-full flex flex-col md:flex-row md:items-end gap-4">
                  
                  <div class="grid grid-cols-2 md:grid-cols-5 gap-4 flex-1 w-full">
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ศกร.ตำบล</label>
                          <select required id="tumbon" name="tumbon" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกตำบล</option>
                              @foreach($all_tumbon as $tm)
                                  <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>
                                      {{ $tm->GRP_NAME }}
                                  </option>
                              @endforeach    
                          </select>
                      </div>
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ภาคเรียน</label>
                          <select required id="semestry" name="semestry" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกภาคเรียน</option>
                              @foreach($all_semestry as $sem)
                                  <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>
                                      {{ $sem->SEMESTRY }}
                                  </option>
                              @endforeach    
                          </select>
                      </div>
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ระดับชั้น</label>
                          <select required onchange="if(this.value != '') { this.form.submit(); }" id="lavel" name="lavel" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกระดับชั้น</option>
                              <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                              <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                              <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                          </select>
                      </div>
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">รายวิชา</label>
                          <select required id="subject" name="subject" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกวิชา</option>
                              @foreach($all_subject as $sub)
                                  <option value="{{ $sub->SUB_CODE }}" @if($subject === $sub->SUB_CODE) selected @endif>
                                      {{ $sub->SUB_CODE }} {{ $sub->SUB_NAME }}
                                  </option>
                              @endforeach    
                          </select>
                      </div>
                      <div class="w-full">
                          <label class="text-[11.5px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest ml-1 block mb-1">ประเภทการสอบ</label>
                          <select required id="type" name="type" class="w-full px-3 py-2 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none">
                              <option value="">เลือกประเภท</option>
                              <option @if($type == 0) selected @endif value="0">สอบปลายภาค</option>
                              <option @if($type == 7) selected @endif value="7">สอบซ่อม</option>
                          </select>
                      </div>
                  </div>
                  
                  <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-800 hover:to-indigo-800 text-white rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 h-10 w-full md:w-auto shrink-0">
                      🔍 ค้นหา
                  </button>
              </form>
          </div>

          @if($data != null)
              <!-- Target Header details -->
              <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 bg-slate-55/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                  <div class="flex-grow space-y-1">
                      <div class="flex items-center gap-2 flex-wrap">
                          <span class="text-sm font-black text-slate-700 dark:text-slate-300">กลุ่ม:</span>
                          <span class="px-2.5 py-1 rounded-lg text-sm font-black bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300">ตำบล {{ $tumbon }}</span>
                          <span class="px-2.5 py-1 rounded-lg text-sm font-black @if($lavel==1) bg-pink-100 text-pink-700 @elseif($lavel==2) bg-emerald-100 text-emerald-700 @else bg-amber-100 text-amber-700 @endif">
                              @if($lavel==1) ประถมศึกษา @elseif($lavel==2) มัธยมต้น @else มัธยมปลาย @endif
                          </span>
                          <span class="px-2.5 py-1 rounded-lg text-sm font-black bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300">วิชา: {{ $subject }}</span>
                          <span class="px-2.5 py-1 rounded-lg text-sm font-black bg-slate-100 text-slate-750">
                              @if($type == 0) สอบปลายภาค @elseif($type == 7) สอบซ่อม @endif
                          </span>
                      </div>
                  </div>
                  
                  {{-- Print Button --}}
                  <button onclick="printCover({{ json_encode(['all_grade' => $all_grade, 'pass_grade' => $all_grade - ($grade_0 + $grade_not + $grade_null), 'notpass_grade' => $grade_0 + $grade_not + $grade_null, 'tumbon' => $tumbon, 'lavel' => $lavel, 'subject' => $subject, 'data' => $data]) }})" 
                          class="flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-black tracking-widest uppercase transition-all shadow-sm active:scale-95 shrink-0 w-full sm:w-auto justify-center">
                      📇 พิมพ์ กศน.4
                  </button>
              </div>

              <!-- Stats summary grid -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 border-b border-slate-150 dark:border-slate-800 bg-slate-50/50">
                  <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-850 flex items-center gap-3">
                      <div class="p-2.5 bg-blue-100 dark:bg-blue-950/50 rounded-xl text-blue-650 dark:text-blue-400 font-bold">👥</div>
                      <div class="text-left">
                          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">นักศึกษาทั้งหมด</p>
                          <p class="text-lg font-black text-blue-600 dark:text-blue-450">{{$all_grade}} <span class="text-xs font-bold text-slate-400">ราย</span></p>
                      </div>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-850 flex items-center gap-3">
                      <div class="p-2.5 bg-emerald-100 dark:bg-emerald-950/50 rounded-xl text-emerald-650 dark:text-emerald-400 font-bold">✅</div>
                      <div class="text-left">
                          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">ผ่านเกณฑ์</p>
                          <p class="text-lg font-black text-emerald-600 dark:text-emerald-450">{{$all_grade-($grade_0+$grade_not+$grade_null)}} <span class="text-xs font-bold text-slate-400">ราย</span></p>
                      </div>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-850 flex items-center gap-3">
                      <div class="p-2.5 bg-rose-100 dark:bg-rose-950/50 rounded-xl text-rose-650 dark:text-rose-450 font-bold">❌</div>
                      <div class="text-left">
                          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">ไม่ผ่านเกณฑ์</p>
                          <p class="text-lg font-black text-rose-600 dark:text-rose-450">{{$grade_0+$grade_not+$grade_null}} <span class="text-xs font-bold text-slate-400">ราย</span></p>
                      </div>
                  </div>
                  <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-850 flex items-center gap-3">
                      <div class="p-2.5 bg-amber-100 dark:bg-amber-950/50 rounded-xl text-amber-650 dark:text-amber-400 font-bold">⭐</div>
                      <div class="text-left">
                          <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">เกรด 2 ขึ้นไป</p>
                          <p class="text-lg font-black text-amber-600 dark:text-amber-400">{{$grade_2_up-$grade_not}} <span class="text-xs font-bold text-slate-400">ราย</span></p>
                      </div>
                  </div>
              </div>

              <!-- Live Search input -->
              <div class="p-4 sm:p-5 border-b border-slate-150 dark:border-slate-800 flex items-center justify-between">
                  <input type="text" id="searchInput" onkeyup="filterTable()" 
                         placeholder="🔍 ค้นหาชื่อ หรือ รหัสนักศึกษา..." 
                         class="px-3.5 py-2 bg-slate-100/50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-350 focus:ring-2 focus:ring-purple-500 outline-none w-full sm:w-64">
              </div>

              <!-- Table Content -->
              <div class="overflow-x-auto scrollbar-thin">
                  <table id="gradeTable" class="min-w-full text-base text-left">
                      <thead>
                          <tr class="bg-slate-900 text-white text-xs font-black uppercase tracking-wider">
                              <th class="p-3 text-center w-12 border-r border-slate-800">ที่</th>
                              <th class="p-3 w-32 border-r border-slate-800">รหัส</th>
                              <th class="p-3 w-64 border-r border-slate-800">ชื่อ-นามสกุล</th>
                              @foreach(['บันทึกเรียน','ฝึกทักษะ','รายงาน','แบบฝึก','แฟ้มงาน','ชิ้นงาน','โครงงาน','ทดสอบ','อื่นๆ','รวมรป.','ปลายภาค','รวมสุทธิ','เกรด'] as $head)
                                  <th class="p-2 text-center whitespace-nowrap border-r border-slate-800">{{$head}}</th>
                              @endforeach
                              <th class="p-3 text-center">หมายเหตุ</th>
                          </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                          @foreach($data as $s)
                          <tr class="hover:bg-purple-50/20 dark:hover:bg-slate-800/40 transition-colors">
                              <td class="p-2 text-center text-slate-450 border-r border-slate-100 dark:border-slate-850">{{$loop->iteration}}</td>
                              <td class="p-2 font-mono font-bold text-sm text-slate-700 dark:text-slate-300 border-r border-slate-100 dark:border-slate-850">{{$s->ID}}</td>
                              <td class="p-2 font-black text-slate-800 dark:text-white border-r border-slate-100 dark:border-slate-850">{{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM1}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM2}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM3}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM4}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM5}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM6}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM7}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM8}}</td>
                              <td class="p-2 text-center border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM9}}</td>
                              <td class="p-2 text-center font-bold bg-slate-50/50 dark:bg-slate-950/25 border-r border-slate-100 dark:border-slate-850">{{$s->MIDTERM}}</td>
                              <td class="p-2 text-center font-bold bg-slate-50/50 dark:bg-slate-950/25 border-r border-slate-100 dark:border-slate-850">{{$s->FINAL}}</td>
                              <td class="p-2 text-center font-black text-indigo-700 bg-indigo-50 dark:bg-indigo-950/30 dark:text-indigo-300 border-r border-slate-100 dark:border-slate-850">{{$s->TOTAL}}</td>
                              <td class="p-2 text-center font-black border-r border-slate-100 dark:border-slate-850">
                                  <span @class([
                                      'text-red-650' => $s->GRADE == '0' || $s->GRADE == '' || $s->GRADE == null || !is_numeric($s->GRADE),
                                      'text-emerald-700' => is_numeric($s->GRADE) && $s->GRADE > 0
                                  ])>
                                      {{$s->GRADE ?? 'N/A'}}
                                  </span>
                              </td>
                              <td class="p-2 text-center text-xs">
                                  @if($s->TYP_CODE == 1) <span class="bg-blue-100 text-blue-800 px-2.5 py-0.5 rounded text-[11.5px] font-black">ทอ*</span> @endif
                              </td>
                          </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          @endif
      </div>
  </div>

  {{-- Search Script --}}
  <script>
  function filterTable() {
      const input = document.getElementById("searchInput");
      const filter = input.value.toUpperCase();
      const table = document.getElementById("gradeTable");
      const tr = table.getElementsByTagName("tr");

      for (let i = 1; i < tr.length; i++) {
          const tdName = tr[i].getElementsByTagName("td")[2]; // Column ชื่อ-สกุล
          const tdID = tr[i].getElementsByTagName("td")[1];   // Column รหัส
          if (tdName || tdID) {
              const txtValueName = tdName.textContent || tdName.innerText;
              const txtValueID = tdID.textContent || tdID.innerText;
              if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueID.toUpperCase().indexOf(filter) > -1) {
                  tr[i].style.display = "";
              } else {
                  tr[i].style.display = "none";
              }
          }
      }
  }
  </script>

  <style>
      @media (max-width: 640px) {
          /* Hide table header */
          #gradeTable thead {
              display: none;
          }
          
          /* Make table rows look like cards */
          #gradeTable tbody tr {
              display: block;
              margin-bottom: 1rem;
              background-color: #ffffff;
              border: 1px solid #e2e8f0;
              border-radius: 1.5rem;
              padding: 1rem;
              box-shadow: 0 1px 3px rgba(0,0,0,0.05);
          }
          
          /* Make table cells block */
          #gradeTable tbody tr td {
              display: block;
              padding: 0.25rem 0 !important;
              border: none !important;
              text-align: left !important;
              width: 100% !important;
          }
          
          /* Iteration row */
          #gradeTable tbody tr td:nth-child(1) {
              font-size: 0.75rem;
              color: #94a3b8;
              font-weight: 800;
              text-transform: uppercase;
              border-bottom: 1px dashed #e2e8f0 !important;
              padding-bottom: 0.5rem !important;
              margin-bottom: 0.5rem;
          }
          #gradeTable tbody tr td:nth-child(1)::before {
              content: "ลำดับที่: ";
          }
          
          /* Student ID */
          #gradeTable tbody tr td:nth-child(2) {
              font-family: monospace;
              font-size: 0.75rem;
              color: #64748b;
          }
          #gradeTable tbody tr td:nth-child(2)::before {
              content: "รหัสนักศึกษา: ";
              font-weight: bold;
              color: #475569;
          }
          
          /* Name-surname */
          #gradeTable tbody tr td:nth-child(3) {
              font-weight: 950;
              font-size: 0.95rem;
              color: #1e293b;
              white-space: normal !important;
              margin-bottom: 0.5rem;
          }
          
          /* Detailed score cells - grid or list */
          #gradeTable tbody tr td:nth-child(4),
          #gradeTable tbody tr td:nth-child(5),
          #gradeTable tbody tr td:nth-child(6),
          #gradeTable tbody tr td:nth-child(7),
          #gradeTable tbody tr td:nth-child(8),
          #gradeTable tbody tr td:nth-child(9),
          #gradeTable tbody tr td:nth-child(10),
          #gradeTable tbody tr td:nth-child(11),
          #gradeTable tbody tr td:nth-child(12) {
              display: inline-block;
              width: auto !important;
              margin-right: 0.35rem;
              font-size: 0.75rem;
              background-color: #f8fafc;
              border: 1px solid #f1f5f9 !important;
              padding: 0.25rem 0.5rem !important;
              border-radius: 0.5rem;
          }
          
          #gradeTable tbody tr td:nth-child(4)::before { content: "บันทึกเรียน: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(5)::before { content: "ฝึกทักษะ: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(6)::before { content: "รายงาน: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(7)::before { content: "แบบฝึก: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(8)::before { content: "แฟ้มงาน: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(9)::before { content: "ชิ้นงาน: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(10)::before { content: "โครงงาน: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(11)::before { content: "ทดสอบ: "; font-weight: bold; }
          #gradeTable tbody tr td:nth-child(12)::before { content: "อื่นๆ: "; font-weight: bold; }
          
          /* Highlighted columns */
          #gradeTable tbody tr td:nth-child(13),
          #gradeTable tbody tr td:nth-child(14),
          #gradeTable tbody tr td:nth-child(15),
          #gradeTable tbody tr td:nth-child(16) {
              display: inline-block;
              width: auto !important;
              margin-right: 0.35rem;
              font-size: 0.75rem;
              padding: 0.25rem 0.5rem !important;
              border-radius: 0.5rem;
          }
          
          #gradeTable tbody tr td:nth-child(13) { background-color: #f1f5f9; color: #334155; }
          #gradeTable tbody tr td:nth-child(13)::before { content: "รวมรป.: "; font-weight: bold; }
          
          #gradeTable tbody tr td:nth-child(14) { background-color: #f1f5f9; color: #334155; }
          #gradeTable tbody tr td:nth-child(14)::before { content: "ปลายภาค: "; font-weight: bold; }
          
          #gradeTable tbody tr td:nth-child(15) { background-color: #e0e7ff; color: #3730a3; }
          #gradeTable tbody tr td:nth-child(15)::before { content: "รวมสุทธิ: "; font-weight: bold; }
          
          #gradeTable tbody tr td:nth-child(16) { background-color: #ecfdf5; color: #065f46; font-size: 0.85rem; }
          #gradeTable tbody tr td:nth-child(16)::before { content: "เกรด: "; font-weight: bold; }
          
          /* Note column */
          #gradeTable tbody tr td:nth-child(17) {
              margin-top: 0.5rem;
              border-top: 1px dashed #e2e8f0 !important;
              padding-top: 0.5rem !important;
          }
      }
  </style>
</x-teachers-layout>
@include('layouts.footer')

<script>
function printCover(data) {
    try {
        // ตรวจสอบว่าข้อมูลที่ส่งเข้ามามีค่าหรือไม่
        if (!data || !data.data) {
            throw new Error("ข้อมูลไม่ถูกต้องหรือไม่มีข้อมูลนักเรียน");
        }

        // ตรวจสอบว่า data.data เป็น array หรือไม่
        let students = Array.isArray(data.data) ? data.data : Object.values(data.data);

        // กำหนดระดับชั้น
        let level;
        if (data.lavel == 3) {
            level = 'มัธยมปลาย';
        } else if (data.lavel == 2) {
            level = 'มัธยมต้น';
        } else {
            level = 'ประถมศึกษา';
        }

        // ดึงข้อมูลจาก select elements
        const tumbonSelect = document.getElementById('tumbon');
        if (!tumbonSelect) {
            throw new Error("ไม่พบ element 'tumbon'");
        }
        const tumbonText = tumbonSelect.options[tumbonSelect.selectedIndex].textContent;

        const subjectSelect = document.getElementById('subject');
        if (!subjectSelect) {
            throw new Error("ไม่พบ element 'subject'");
        }
        const subjectText = subjectSelect.options[subjectSelect.selectedIndex].textContent;

        const semesterValue = document.getElementById('semestry').value;
        if (!semesterValue) {
            throw new Error("ไม่พบค่า 'semestry'");
        }
        const [year, semester] = semesterValue.split('/');
        const fullYear = `25${year}`;
        const semesterText = `ภาคเรียนที่ ${semester} ปีการศึกษา ${fullYear}`;

        // ข้อมูลที่ต้องแสดง
        const studentData = {
            students: students,
            tumbon: tumbonText,
            subject: subjectText,
            semestry: semesterText,
            type: document.getElementById('type').value,
            totalStudents: data.all_grade,
            passedStudents: data.pass_grade,
            failedStudents: data.notpass_grade,
        };

        // HTML สำหรับเอกสาร
        const printContent = `
            <!DOCTYPE html>
            <html lang="th">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Print Preview</title>
                <!-- เพิ่มฟอนต์ Sarabun จาก Google Fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
                <style>
                    @media print {
                        /* ตั้งค่าหน้ากระดาษ */
                        @page :first {
                            margin: 0 10mm; /* ขอบกระดาษหน้าแรก */
                            size: A4 portrait; /* ขนาด A4 แนวตั้ง */
                        }
                        @page {
                            margin: 10mm; /* ขอบกระดาษหน้าอื่น ๆ */
                            size: A4 landscape; /* ขนาด A4 แนวนอน */
                        }

                        /* บังคับให้พิมพ์ background image และ background color */
                        .print-background {
                            -webkit-print-color-adjust: exact; /* สำหรับ Chrome/Safari */
                            color-adjust: exact; /* สำหรับ Firefox */
                            print-color-adjust: exact; /* มาตรฐานใหม่ */
                        }

                        /* ตั้งค่าฟอนต์และขนาด */
                        body {
                            margin: 0;
                            padding: 0;
                            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ Sarabun */
                            font-size: 14px;
                            text-align: center;
                        }

                        /* สไตล์สำหรับ container */
                        .container {
                            width: 100%;
                            height: 100%;
                            margin: 0 auto;
                            padding: 20px;
                            box-sizing: border-box;
                            position: relative;
                        }

                        /* สไตล์สำหรับโลโก้ */
                        .logo {
                            width: 150px;
                            margin: 0 auto;
                        }

                        /* สไตล์สำหรับ header */
                        .header {
                            margin-top: 20px;
                        }

                        /* สไตล์สำหรับ content */
                        .content {
                            margin-left: 75px;
                            margin-top: 30px;
                            text-align: left;
                        }

                        /* สไตล์สำหรับ footer */
                        .footer {
                            margin-top: 50px;
                            text-align: center;
                            page-break-inside: avoid; /* ป้องกันไม่ให้ footer ถูกตัดระหว่างหน้า */
                        }

                        /* สไตล์สำหรับลายเซ็น */
                        .signature {
                            margin-top: 20px;
                            text-align: center;
                            page-break-inside: avoid; /* ป้องกันไม่ให้ลายเซ็นถูกตัดระหว่างหน้า */
                        }

                        /* สไตล์สำหรับเส้นคั่นลายเซ็น */
                        .signature-line {
                            border-top: 1px solid #000;
                            width: 200px;
                            margin: 10px auto;
                        }

                        /* สไตล์สำหรับตาราง */
                        table {
                            font-size: 12px;
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                            table-layout: auto;
                        }

                        th, td {
                            border: 1px solid #000;
                            padding: 8px;
                            text-align: center;
                            box-sizing: border-box;
                        }

                        th {
                            background-color: #f0f0f0;
                        }

                        /* สไตล์สำหรับคอลัมน์แนวตั้ง */
                        .textAlignVer {
                            writing-mode: vertical-rl;
                            transform: rotate(180deg);
                            white-space: nowrap;
                        }

                        /* สไตล์สำหรับคอลัมน์รหัส */
                        .id-column {
                            width: auto;
                            min-width: 50px;
                        }

                        /* สไตล์สำหรับคอลัมน์ชื่อ-สกุล */
                        .name-column {
                            width: auto;
                            min-width: 150px;
                            white-space: nowrap;
                            text-overflow: ellipsis;
                            overflow: hidden;
                        }

                        /* สไตล์สำหรับคอลัมน์ความกว้างคงที่ */
                        .fixed-width-column {
                            width: 12mm;
                        }

                        /* ทำให้หัวตารางแสดงซ้ำในทุกหน้า */
                        thead {
                            display: table-header-group;
                        }

                        /* ป้องกันไม่ให้แถวถูกตัดระหว่างหน้า */
                        tr {
                            page-break-inside: avoid;
                        }

                        /* สไตล์สำหรับ caption ตาราง */
                        caption {
                            text-align: center;
                            font-size: 16px;
                            background-color: #e0e0e0;
                            padding: 10px;
                            border: 1px solid #000;
                            caption-side: top;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <!-- Header -->
                    <div class="header">
                        <p style="text-align: right; margin: 0;">กศน.4</p>
                        <!-- Logo -->
                        <div class="logo">
                            <img src="https://phothongdlec.ac.th/storage/images/Garuda.png" alt="Logo" style="width: 100%;">
                        </div>
                        <h4 style="margin: 10px 0;">เอกสารบันทึกผลการพัฒนาคุณภาพผู้เรียน</h4>
                        <p>หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551</p>
                        <p>ระดับ ${level} ${studentData.semestry ?? ""}</p>
                    </div>

                    <!-- Content -->
                    <div class="content">
                        <p><strong>สถานศึกษา:</strong> ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอ{{ config('app.name_district') }} </p>
                        <p><strong>อำเภอเขต:</strong>  {{ config('app.name_district') }}  <strong>จังหวัด:</strong>  {{ config('app.name_province') }}  </p>
                        <p><strong>ชื่อกลุ่ม:</strong> ${studentData.tumbon ?? ""} <strong>รายวิชา:</strong> ${studentData.subject ?? ""} ${studentData.type == 7 ? "(ประเมินสอบซ่อม)" : ""}</p>
                        <h4>สรุปผลการเรียน</h4>
                        <p style="padding-left: 25px;">จำนวนนักศึกษาทั้งหมด:         ${studentData.totalStudents ?? ""}  คน</p>
                        <p style="padding-left: 25px;">จำนวนนักศึกษาผ่านการประเมิน:   ${studentData.passedStudents ?? ""} คน</p>
                        <p style="padding-left: 25px;">จำนวนนักศึกษาไม่ผ่านการประเมิน: ${studentData.failedStudents ?? ""} คน</p>
                        <h4>การตัดสินผลการประเมิน</h4>
                        <p>.............................................................. ครู</p>
                        <p>.............................................................. นายทะเบียน</p>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <p>อนุมัติผลการเรียน เมื่อวันที่ ...... เดือน .................. พ.ศ. ...........</p>
                        <br>
                        <div class="signature">
                            <p>(ลงชื่อ) .................................................. ผู้อนุมัติ</p>
                            <p>(..................................)</p>
                            <p>ผู้อำนวยการศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอ{{ config('app.name_district') }}</p>
                        </div>
                    </div>
                </div>

                <!-- หน้าถัดไปสำหรับตาราง -->
                <div class="container" style="page-break-before: always; margin-top: 10mm;">
                    <h4>การประเมินผลการเรียน</h4>
                    <h4>อัตราส่วนคะแนนระหว่างภาคเรียน : ปลายภาค = 60 : 40</h4>
                    <table>
                        <caption>การประเมินผลการเรียนรายวิชา ${studentData.subject ?? ""} ${studentData.type == 7 ? "(สอบซ่อม)" : ""}</caption>
                        <thead>
                            <tr>
                                <th class="fixed-width-column">ลำดับ</th>
                                <th class="id-column">รหัส</th>
                                <th class="name-column">ชื่อ-สกุล<br> ${studentData.subject ?? ""} ${studentData.type == 7 ? "(สอบซ่อม)" : ""}</th>
                                <th class="fixed-width-column"><div class="textAlignVer">บันทึกการเรียนรู้</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">บันทึกการฝึกทักษะ</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">รายงาน</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">แบบฝึกหัด</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">แฟ้มสะสมงาน</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">ผลงานชิ้นงาน</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">โครงงาน</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">ทดสอบย่อย</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">อื่นๆ</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">รวมระหว่างภาค</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">คะแนนปลายภาค ${studentData.type == 7 ? "(สอบซ่อม)" : ""}</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">รวมคะแนนทั้งสิ้น</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">เกรด</div></th>
                                <th class="fixed-width-column"><div class="textAlignVer">หมายเหตุ</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            ${studentData.students.map((s, index) => `
                                <tr>
                                    <td class="fixed-width-column">${index + 1}</td>
                                    <td class="id-column">${s.ID ?? ""}</td>
                                    <td class="name-column">${s.PRENAME ?? ""}${s.NAME ?? ""} ${s.SURNAME ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM1 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM2 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM3 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM4 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM5 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM6 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM7 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM8 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM9 ?? ""}</td>
                                    <td class="fixed-width-column">${s.MIDTERM ?? ""}</td>
                                    <td class="fixed-width-column">${s.FINAL ?? ""}</td>
                                    <td class="fixed-width-column">${s.TOTAL ?? ""}</td>
                                    <td class="fixed-width-column">
                                      <span style="${s.GRADE === 'ข' || s.GRADE == 0 || s.GRADE == '' || s.GRADE == null ? 'color: red;' : ''}">
                                        ${s.GRADE ?? ""}
                                      </span>
                                    </td>
                                    <td class="fixed-width-column">
                                      ${s.TYP_CODE == 1 ? 'ทอ*' : s.TYP_CODE == 7 ? 'สอบซ่อม' : ''}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                    <!-- Footer -->
                    <div class="footer">
                        <p>ข้าพเจ้าขอรับรองว่าถูกต้องและเป็นจริง</p>
                        <br>
                        <div class="signature">
                            <p>(ลงชื่อ) .................................................. ครู</p>
                            <p>(........................................................)</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        `;

        // สร้าง iframe ชั่วคราว
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);

        // เขียนเนื้อหาลงใน iframe
        const iframeDoc = iframe.contentWindow.document;
        iframeDoc.open();
        iframeDoc.write(printContent);
        iframeDoc.close();

        // รอให้รูปภาพและฟอนต์โหลดเสร็จก่อนพิมพ์
        const logoImg = iframeDoc.querySelector('.logo img');

        // ฟังก์ชันตรวจสอบการโหลดฟอนต์
        const checkFontLoaded = () => {
            iframeDoc.fonts.ready.then(() => {
                // พิมพ์เนื้อหาใน iframe
                iframe.contentWindow.focus(); // ให้ iframe โฟกัส
                iframe.contentWindow.print(); // พิมพ์

                // ลบ iframe หลังจากพิมพ์เสร็จ
                document.body.removeChild(iframe);
            }).catch((error) => {
                console.error('เกิดข้อผิดพลาดในการโหลดฟอนต์:', error);
            });
        };

        // รอให้รูปภาพโหลดเสร็จก่อนตรวจสอบฟอนต์
        logoImg.onload = () => {
            checkFontLoaded();
        };

        // หากรูปภาพไม่โหลด (เช่น URL ไม่ถูกต้อง) ให้ตรวจสอบฟอนต์โดยไม่รอ
        logoImg.onerror = () => {
            checkFontLoaded();
        };
    } catch (error) {
        // แสดง alert พร้อมแจ้งสาเหตุที่ไม่ทำงาน
        alert(`เกิดข้อผิดพลาด: ${error.message}`);
    }
}
</script>