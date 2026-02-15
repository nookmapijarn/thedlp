<x-teachers-layout>
  <div class="p-4">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="text-2xl font-bold w-full text-center">ผลการพัฒนาคุณภาพผู้เรียน (กศน.4)</div>
        <form method="GET" action="{{ route('tscore') }}" class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-5">
                    
                    <div class="space-y-2">
                        <label for="tumbon" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="flex items-center gap-1">📍 ศกร.ตำบล</span>
                        </label>
                        <select required id="tumbon" name="tumbon" 
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">เลือกตำบล</option>
                            @foreach($all_tumbon as $tm)
                                <option value="{{ $tm->GRP_CODE }}" @if($tumbon == $tm->GRP_CODE) selected @endif>
                                    {{ $tm->GRP_NAME }}
                                </option>
                            @endforeach    
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="semestry" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            🗓️ ภาคเรียน
                        </label>
                        <select required id="semestry" name="semestry" 
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">เลือกภาคเรียน</option>
                            @foreach($all_semestry as $sem)
                                <option value="{{ $sem->SEMESTRY }}" @if($semestry === $sem->SEMESTRY) selected @endif>
                                    {{ $sem->SEMESTRY }}
                                </option>
                            @endforeach    
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="lavel" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            🎓 ระดับชั้น
                        </label>
                        <select required onchange="if(this.value != '') { this.form.submit(); }" id="lavel" name="lavel" 
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">เลือกระดับชั้น</option>
                            <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                            <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                            <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="subject" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            📚 รายวิชา
                        </label>
                        <select required id="subject" name="subject" 
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">เลือกวิชา</option>
                            @foreach($all_subject as $sub)
                                <option value="{{ $sub->SUB_CODE }}" @if($subject === $sub->SUB_CODE) selected @endif>
                                    {{ $sub->SUB_CODE }} {{ $sub->SUB_NAME }}
                                </option>
                            @endforeach    
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            📝 ประเภทการสอบ
                        </label>
                        <select required id="type" name="type" 
                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">เลือกประเภท</option>
                            <option @if($type == 0) selected @endif value="0">สอบปลายภาค</option>
                            <option @if($type == 7) selected @endif value="7">สอบซ่อม</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-center">
                    <button type="submit" 
                        class="flex items-center justify-center gap-2 w-full md:w-1/3 py-3 px-6 text-white font-bold bg-gradient-to-r from-indigo-600 to-blue-500 rounded-xl shadow-md hover:from-indigo-700 hover:to-blue-600 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        ดูรายงานผลคะแนน
                    </button>
                </div>
            </div>
        </form>

{{-- Table Section --}}
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    
    {{-- Header & Summary Section --}}
    <div class="mb-6 rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-white dark:bg-gray-800">
        <div @class([
            'p-6 text-center border-b border-slate-200 transition-colors',
            'bg-pink-50' => $lavel == 1,
            'bg-green-50' => $lavel == 2,
            'bg-yellow-50' => $lavel == 3,
            'bg-blue-50' => !in_array($lavel, [1,2,3])
        ])>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-left">
                    <h2 class="text-xl font-bold text-gray-800">
                        ศกร.ตำบล: <span class="text-indigo-600">{{$tumbon}}</span>
                    </h2>
                    <p class="text-gray-600">
                        <strong>ระดับ:</strong> 
                        @if($lavel==1) ประถมศึกษา @elseif($lavel==2) มัธยมต้น @elseif($lavel==3) มัธยมปลาย @endif
                        | <strong>วิชา:</strong> {{$subject}} 
                        <span class="text-sm font-medium">
                            @if($type == 0) (สอบปลายภาค) @elseif ($type == 7) (สอบซ่อม) @endif
                        </span>
                    </p>
                </div>
                
                {{-- Print Button --}}
                <button onclick="printCover({{ json_encode(['all_grade' => $all_grade, 'pass_grade' => $all_grade - ($grade_0 + $grade_not + $grade_null), 'notpass_grade' => $grade_0 + $grade_not + $grade_null, 'tumbon' => $tumbon, 'lavel' => $lavel, 'subject' => $subject, 'data' => $data]) }})" 
                        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-md transition-all active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    พิมพ์ กศน.4
                </button>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                <div class="bg-white p-3 rounded-xl shadow-sm border border-blue-100 flex items-center gap-3">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">👥</div>
                    <div class="text-left">
                        <p class="text-xs text-gray-500">ทั้งหมด</p>
                        <p class="text-lg font-bold text-blue-600">{{$all_grade}} <span class="text-sm font-normal">ราย</span></p>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-sm border border-green-100 flex items-center gap-3">
                    <div class="p-2 bg-green-100 rounded-lg text-green-600">✅</div>
                    <div class="text-left">
                        <p class="text-xs text-gray-500">ผ่าน</p>
                        <p class="text-lg font-bold text-green-600">{{$all_grade-($grade_0+$grade_not+$grade_null)}} <span class="text-sm font-normal">ราย</span></p>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-sm border border-red-100 flex items-center gap-3">
                    <div class="p-2 bg-red-100 rounded-lg text-red-600">❌</div>
                    <div class="text-left">
                        <p class="text-xs text-gray-500">ไม่ผ่าน</p>
                        <p class="text-lg font-bold text-red-600">{{$grade_0+$grade_not+$grade_null}} <span class="text-sm font-normal">ราย</span></p>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-xl shadow-sm border border-yellow-100 flex items-center gap-3">
                    <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">⭐</div>
                    <div class="text-left">
                        <p class="text-xs text-gray-500">เกรด 2 ขึ้นไป</p>
                        <p class="text-lg font-bold text-yellow-600">{{$grade_2_up-$grade_not}} <span class="text-sm font-normal">ราย</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Input --}}
        <div class="p-4 bg-gray-50 border-b border-slate-200">
            <div class="relative max-w-sm">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="searchInput" onkeyup="filterTable()" 
                    class="block w-full pl-10 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" 
                    placeholder="ค้นหาชื่อ หรือ รหัสนักศึกษา...">
            </div>
        </div>

        {{-- Table Container --}}
        @if($data != null)
        <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
            <table id="gradeTable" class="w-full text-sm text-left text-gray-500">
                <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-100 z-10">
                    <tr>
                        <th class="border p-3 text-center w-12">ที่</th>
                        <th class="border p-3 w-32">รหัส</th>
                        <th class="border p-3 w-64">ชื่อ-นามสกุล</th>
                        {{-- คะแนนย่อย --}}
                        @foreach(['บันทึกเรียน','ฝึกทักษะ','รายงาน','แบบฝึก','แฟ้มงาน','ชิ้นงาน','โครงงาน','ทดสอบ','อื่นๆ','รวมรป.','ปลายภาค','รวมสุทธิ','เกรด'] as $head)
                        <th class="border p-2 text-center text-[10px] whitespace-nowrap">{{$head}}</th>
                        @endforeach
                        <th class="border p-3 text-center">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $s)
                    <tr class="hover:bg-indigo-50 transition-colors">
                        <td class="border p-2 text-center">{{$loop->iteration}}</td>
                        <td class="border p-2 font-mono text-xs">{{$s->ID}}</td>
                        <td class="border p-2 font-medium text-gray-900">{{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM1}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM2}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM3}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM4}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM5}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM6}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM7}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM8}}</td>
                        <td class="border p-2 text-center">{{$s->MIDTERM9}}</td>
                        <td class="border p-2 text-center bg-gray-50 font-bold">{{$s->MIDTERM}}</td>
                        <td class="border p-2 text-center bg-gray-50 font-bold">{{$s->FINAL}}</td>
                        <td class="border p-2 text-center bg-indigo-50 font-bold text-indigo-700">{{$s->TOTAL}}</td>
                        <td class="border p-2 text-center font-bold">
                            <span @class([
                                'text-red-500' => $s->GRADE == '0' || $s->GRADE == '' || $s->GRADE == null || !is_numeric($s->GRADE),
                                'text-green-600' => is_numeric($s->GRADE) && $s->GRADE > 0
                            ])>
                                {{$s->GRADE ?? 'N/A'}}
                            </span>
                        </td>
                        <td class="border p-2 text-center text-xs">
                            @if($s->TYP_CODE == 1) <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">ทอ*</span> @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-20 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <p class="text-xl text-red-500 font-medium">- ไม่พบข้อมูล กรุณาเลือกรายการใหม่ -</p>
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

  </div>
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