<x-teachers-layout>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="text-2xl font-bold w-full text-center">ผลการพัฒนาคุณภาพผู้เรียน (กศน.4)</div>
        <form method="GET" action="{{ route('tscore') }}" class="text-sm max-w-6xl mx-auto">
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-5 justify-items-center">
            <div class="min-w-full" >
              <label>ศกร.ตำบล</label>
                <select required id="tumbon" name="tumbon" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  @foreach($all_tumbon as $tm)
                      <option value="{{ $tm->GRP_CODE }}"
                        @if($tumbon == $tm->GRP_CODE) selected @endif
                        >
                          {{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}
                      </option>
                  @endforeach    
                </select>
            </div>
            <div class="min-w-full" >
              <label>ภาคเรียน</label>
                <select required id="semestry" name="semestry" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  @foreach($all_semestry as $sem)
                      <option value="{{ $sem->SEMESTRY }}"
                        @if($semestry === $sem->SEMESTRY) selected @endif
                        >
                          {{ $sem->SEMESTRY }}
                      </option>
                  @endforeach    
                </select>
            </div>
            <div class="min-w-full">
              <label>ระดับชั้น</label>
                <select required onchange="if(this.value != '') { this.form.submit(); }" id="lavel" name="lavel" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="">เลือก</option>
                  <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                  <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                  <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                </select>
            </div>
            <div class="min-w-full" >
              <label>รายวิชา</label>
                <select required id="subject" name="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  @foreach($all_subject as $sub)
                      <option value="{{ $sub->SUB_CODE }}"
                        @if($subject === $sub->SUB_CODE) selected @endif
                        >
                          {{ $sub->SUB_CODE }} {{ $sub->SUB_NAME }}
                      </option>
                  @endforeach    
                </select>
            </div>
            <div class="min-w-full" >
              <label>สอบปกติ/ซ่อม</label>
                <select required  id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  <option @if($type == 0) selected @endif value="0">สอบปลายภาค</option>
                  <option @if($type == 7) selected @endif value="7">สอบซ่อม</option>
                  {{-- <option @if($type == 1) selected @endif value="1">เทียบโอน</option> --}}
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full  mt-4 p-2 min-w-full bg-indigo-500 hover:border-blue-500 text-white">ดูรายงาน</button> 
        </form>

        {{-- Table --}}

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-scroll">
          <div class="flex flex-col max-w-srceen-lg ">
              <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
                  <div class="overflow-hidden">
                    <div
                    @if($lavel == 1) 
                      class="text-center text-xl p-4 bg-pink-100 border border-slate-300 mb-2" 
                    @elseif($lavel == 2) 
                      class="text-center text-xl p-4 bg-green-100 border border-slate-300 mb-2"
                    @elseif($lavel == 3) 
                      class="text-center text-xl p-4 bg-yellow-100 border border-slate-300 mb-2" 
                    @else 
                      class="text-center text-xl p-4 bg-blue-100 border border-slate-300 mb-2" 
                    @endif
                    >
                      <p>
                        <strong> ศกร.ตำบล : </strong> {{$tumbon}}
                        <strong> ระดับ : </strong> 
                        @if($lavel==1) ประถมศึกษา 
                        @elseif($lavel==2) มัธยมต้น 
                        @elseif($lavel==3) มัธยมปลาย 
                        @endif
                        <strong> รายวิชา : </strong> {{$subject}}
                        @if($type == 0) (สอบปลายภาค) 
                        @elseif ($type == 7) (สอบซ่อม) 
                        @endif
                        {{-- Print --}}
                        <button onclick="printCover({{ json_encode([
                          'all_grade' => $all_grade,
                          'pass_grade' => $all_grade - ($grade_0 + $grade_not + $grade_null),
                          'notpass_grade' => $grade_0 + $grade_not + $grade_null,
                          'tumbon' => $tumbon, 
                          'lavel' => $lavel, 
                          'subject' => $subject, 
                          'data' => $data]) 
                        }})" type="button" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                          <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
                          </svg>  
                          พิมพ์ กศน.4
                        </button>
                      </p>
                      <div class="flex flex-col-4 gap-4 mt-2">
                        {{-- <strong> ทั้งหมด = <span class="text-blue-600 underline"> {{$all_grade}} </span></strong>
                        <strong> ผ่าน = <span class="text-green-600 underline">{{$all_grade-($grade_0+$grade_not)}}</span></strong>
                        <strong> ไม่ผ่าน = <span class="text-red-600 underline">{{$grade_0+$grade_not}}</span></strong>
                        <strong> เกรด 2 ขึ้นไป = <span class="text-yellow-600 underline">{{$grade_2_up-$grade_not}}</span></strong>  --}}
                        {{-- ขาดสอบ : {{$grade_not}} --}}
                        <div id="toast-simple" class="flex items-center w-full font-size-32 max-w-xs p-4 space-x-4 rtl:space-x-reverse text-blue-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
                          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                          </svg>
                            <div class="ps-4 text-sm font-normal">ทั้งหมด <span class="underline text-lg">{{$all_grade}}</span> ราย</div>
                        </div>     
                        <div id="toast-simple" class="flex items-center w-full max-w-xs p-4 space-x-4 rtl:space-x-reverse text-green-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                            </svg>
                            <div class="ps-4 text-sm font-normal">ผ่าน <span class="underline text-lg">{{$all_grade-($grade_0+$grade_not+$grade_null)}} </span>ราย</div>
                        </div> 
                        <div id="toast-simple" class="flex items-center w-full max-w-xs p-4 space-x-4 rtl:space-x-reverse text-red-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                            </svg>
                            <div class="ps-4 text-sm font-normal">ไม่ผ่าน <span class="underline text-lg">{{$grade_0+$grade_not+$grade_null}} </span>ราย</div>
                        </div> 
                        <div id="toast-simple" class="flex items-center w-full max-w-xs p-4 space-x-4 rtl:space-x-reverse text-yellow-500 bg-white divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg shadow dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                            </svg>                    
                            <div class="ps-4 text-sm font-normal">เกรด 2 ขึ้นไป <span class="underline text-lg"> {{$grade_2_up-$grade_not}} </span> ราย</div>
                        </div>            
                      </div>
                    </div>
                    @if($data!=null)
                    <table class="max-w-full table-fixed bg-blue-100">
                      <thead class="h-32 text-xs text-gray-900 uppercase dark:bg-gray-700 dark:text-gray-400">  
                          <tr class="">
                            <th scope="col" class="border border-slate-300 ... w-12 text-center">ลำดับ</th>
                            <th scope="col" class="border border-slate-300 ... w-32">รหัส</th>
                            <th scope="col" class="border border-slate-300 ... w-56">ชื่อ-สกุล</th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">บันทึกการเรียนรู้</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">บันทึกการฝึกทักษะ</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รายงาน</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">แบบฝึกหัด</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">แฟ้มสะสมงาน</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">ผลงานชิ้นงาน</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">โครงงาน</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">ทดสอบย่อย</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">อื่นๆ</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รวมระหว่างภาค</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">คะแนนปลายภาค</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รวมคะแนนทั้งสิ้น</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">เกรด</div></th>
                            <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-24 md:w-24">หมายเหตุ</div></th>
                          </tr>
                        </thead>
                        <tbody class="max-h-48">
                          @foreach($data as $s)
                          <tr  class="bg-white hover:bg-blue-200 border-b dark:bg-gray-800 dark:border-gray-700">
                              <td class="border border-slate-300 ... text-center">{{$loop->iteration}}</td>
                              <td class="border border-slate-300 ... pr-2">{{$s->ID}}</td>
                              <td class="border border-slate-300 ... truncate">{{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM1}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM2}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM3}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM4}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM5}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM6}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM7}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM8}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM9}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->MIDTERM}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->FINAL}}</td>
                              <td class="border border-slate-300 ... text-center">{{$s->TOTAL}}</td>
                              <td class="border border-slate-300 ... text-center">
                                <span
                                @if($s->GRADE == 0)class="text-red-300"@endif
                                @if($s->GRADE > 0 && !is_numeric($s->GRADE))class="text-red-600"@endif
                                @if($s->GRADE == '' || $s->GRADE == null)class="text-red-600"@endif  
                                >
                                {{$s->GRADE}}
                                </span>
                              </td>
                              <td class="border border-slate-300 ... text-center">
                                @if($s->TYP_CODE == 1) ทอ* @endif
                              </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
                    @endif
                    @if($data==null) <section class="p-10 text-center text-xl text-red-500">-ไม่พบข้อมูล กรุณาเลือกรายการใหม่-</section> @endif
                  {{-- </div>
                </div>
              </div>
            </div> --}}
          </div>
    </div>
  </div>
</x-teachers-layout>
@include('layouts.footer')

<script>
function printCover(data) {
    // กำหนดระดับชั้น
    let level;
    if (data.lavel == 3) {
        level = 'มัธยมปลาย';
    } else if (data.lavel == 2) {
        level = 'มัธยมต้น';
    } else {
        level = 'ประถมศึกษา';
    }
    // ดึง element ของ select
    const tumbonSelect = document.getElementById('tumbon');
    // ดึง option ที่ถูกเลือก
    const tumbonOption = tumbonSelect.options[tumbonSelect.selectedIndex];
    // ดึงข้อความที่แสดงอยู่ใน option ที่ถูกเลือก
    const tumbonText = tumbonOption.textContent;

    // ดึง element ของ select
    const subjectSelect = document.getElementById('subject');
    // ดึง option ที่ถูกเลือก
    const subjectOption = subjectSelect.options[subjectSelect.selectedIndex];
    // ดึงข้อความที่แสดงอยู่ใน option ที่ถูกเลือก
    const subjectText = subjectOption.textContent;

    const semesterValue = document.getElementById('semestry').value;
    const [year, semester] = semesterValue.split('/');
    const fullYear = `25${year}`; // หรือใช้ `20${year}` หากเป็นปี ค.ศ.
    const semesterText = `ภาคเรียนที่ ${semester} ปีการศึกษา ${fullYear}`;


    // ข้อมูลที่ต้องแสดง
    const studentData = {
        students: data.data,
        tumbon: tumbonText,
        subject: subjectText,
        semestry: semesterText,
        type: document.getElementById('type').value,
        totalStudents: data.all_grade, // จำนวนนักศึกษาทั้งหมด (ตัวอย่าง)
        passedStudents: data.pass_grade, // จำนวนนักศึกษาผ่านการประเมิน (ตัวอย่าง)
        failedStudents: data.notpass_grade, // จำนวนนักศึกษาไม่ผ่านการประเมิน (ตัวอย่าง)
    };

    // HTML สำหรับเอกสาร
    const printContent = `
    <style>
        @media print {
            /* ซ่อน URL, วันที่ และเวลา */
            @page :first {
                margin: 0 10mm; /* ลดขอบกระดาษด้านซ้ายและขวาเหลือ 10mm สำหรับหน้าแรก */
                size: A4 portrait; /* กำหนดขนาดกระดาษเป็น A4 แนวตั้งสำหรับหน้าแรก */
            }
            @page {
                margin: 10mm 0mm 10mm 0mm; /* บน ขาว ล่าง ซ้าย ลดขอบกระดาษด้านซ้ายและขวาเหลือ 10mm และเว้นขอบด้านบน 10mm สำหรับหน้าอื่นๆ */
                size: A4 landscape; /* กำหนดขนาดกระดาษเป็น A4 แนวนอนสำหรับหน้าอื่นๆ */
            }
            /* บังคับให้พิมพ์ background image และ background color */
            .print-background {
                -webkit-print-color-adjust: exact; /* สำหรับ Chrome/Safari */
                color-adjust: exact; /* สำหรับ Firefox */
                print-color-adjust: exact; /* มาตรฐานใหม่ */
            }
            body {
                margin: 0;
                padding: 0;
                font-family: 'TH Sarabun SPK', sans-serif; /* ใช้ฟอนต์ Thai Sarabun SPK */
                font-size: 16px; /* ขนาดฟอนต์ */
                text-align: center;
            }
            .container {
                width: 100%; /* ใช้ความกว้างเต็มหน้า */
                height: 100%; /* ใช้ความสูงเต็มหน้า */
                margin: 0 auto;
                padding: 20px;
                box-sizing: border-box;
                position: relative;
            }
            .logo {
                width: 150px;
                margin: 0 auto;
            }
            .header {
                margin-top: 20px;
            }
            .content {
                margin-left: 75px;
                margin-top: 30px;
                text-align: left;
            }
            .footer {
                margin-top: 50px;
                text-align: center;
            }
            .signature {
                margin-top: 20px;
                text-align: center;
            }
            .signature-line {
                border-top: 1px solid #000;
                width: 200px;
                margin: 10px auto;
            }
            table {
                width: 100%; /* ตารางเต็มความกว้าง */
                border-collapse: collapse;
                margin-top: 20px;
                table-layout: auto; /* ปรับความกว้างคอลัมน์ตามเนื้อหา */
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
                box-sizing: border-box; /* รวม padding และ border ในความกว้าง */
            }
            th {
                background-color: #f0f0f0;
            }
            .textAlignVer {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                white-space: nowrap;
            }
            /* ปรับช่อง "รหัส" และ "ชื่อ-สกุล" */
            .id-column {
                width: auto; /* ปรับความกว้างตามเนื้อหา */
                min-width: 50px; /* ความกว้างขั้นต่ำ */
            }
            .name-column {
                width: auto; /* ปรับความกว้างตามเนื้อหา */
                min-width: 150px; /* ความกว้างขั้นต่ำ */
                white-space: nowrap; /* ป้องกันการขึ้นบรรทัดใหม่ */
                text-overflow: ellipsis; /* แสดง ... หากข้อความยาวเกิน */
                overflow: hidden; /* ซ่อนข้อความที่เกิน */
            }
            /* ปรับความกว้างของคอลัมน์อื่นๆ */
            .fixed-width-column {
                width: 12mm; /* ความกว้างคงที่ */
            }
        }
    </style>
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
            <p>ระดับ ${level} ${studentData.semestry ?? ""} </p>
        </div>

        <!-- Content -->
        <div class="content">
            <p><strong>สถานศึกษา:</strong> ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง </p>
            <p><strong>อำเภอเขต:</strong> โพธิ์ทอง <strong>จังหวัด:</strong> อ่างทอง </p>
            <p><strong>ชื่อกลุ่ม:</strong> ${studentData.tumbon ?? ""} <strong>รายวิชา:</strong> ${studentData.subject ?? ""}</p>
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
                <p>(นางสาวกุหลาบ  อ่อนระทวย)</p>
                <p>ผู้อำนวยการศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง</p>
            </div>
        </div>
    </div>

    <!-- หน้าถัดไปสำหรับตาราง -->
    <div class="container" style="page-break-before: always; margin-top: 10mm;">
        <h4>การประเมินผลการเรียน</h4>
        <h4>อัตราส่วนคะแนนระหว่างภาคเรียน : ปลายภาค = 60 : 40</h4>
        <table>
            <thead>
                <tr>
                  การประเมินผลการเรียนรายวิชา ${studentData.subject ?? ""}
                </tr>
                <tr>
                    <th class="fixed-width-column">ลำดับ</th>
                    <th class="id-column">รหัส</th>
                    <th class="name-column">ชื่อ-สกุล</th>
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
                    <th class="fixed-width-column"><div class="textAlignVer">คะแนนปลายภาค</div></th>
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
                            <span style="${s.GRADE == 0 || s.GRADE == '' || s.GRADE == null ? 'color: red;' : ''}">
                                ${s.GRADE ?? ""}
                            </span>
                        </td>
                        <td class="fixed-width-column">${s.TYP_CODE == 1 ? 'ทอ*' : ''}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
        <!-- Footer -->
        <div class="footer">
            <p>ข้าพเจ้าขอรับรองว่าถูกต้องและเป็นจริง</p>
            <br>
            <div class="signature">
                <p>(ลงชื่อ) .................................................. ครูผู้สอน</p>
                <p>(........................................................)</p>
            </div>
        </div>
    </div>

    <!-- เพิ่มฟอนต์ Thai Sarabun SPK -->
    <link href="https://fonts.googleapis.com/css2?family=TH+Sarabun+SPK:wght@400;700&display=swap" rel="stylesheet">
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

    // รอให้รูปภาพโหลดเสร็จก่อนพิมพ์
    const logoImg = iframeDoc.querySelector('.logo img');
    logoImg.onload = () => {
        // พิมพ์เนื้อหาใน iframe
        iframe.contentWindow.focus(); // ให้ iframe โฟกัส
        iframe.contentWindow.print(); // พิมพ์

        // ลบ iframe หลังจากพิมพ์เสร็จ
        document.body.removeChild(iframe);
    };

    // หากรูปภาพไม่โหลด (เช่น URL ไม่ถูกต้อง) ให้พิมพ์โดยไม่รอ
    logoImg.onerror = () => {
        iframe.contentWindow.focus(); // ให้ iframe โฟกัส
        iframe.contentWindow.print(); // พิมพ์

        // ลบ iframe หลังจากพิมพ์เสร็จ
        document.body.removeChild(iframe);
    };
}
</script>