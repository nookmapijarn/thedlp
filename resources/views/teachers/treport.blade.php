<x-teachers-layout>
  <div class="p-2 sm:ml-64">
    <div class="p-0 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
      <form method="POST" action="{{ route('treport') }}" class="mx-auto mt-4 max-w-6xl sm:mt-6">
        <p class="text-xl">รายงานนักศึกษาตามกลุ่ม</p>
        @csrf
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-4 justify-items-center">
          <div class="min-w-full">
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
          <div class="min-w-full" >
            <label>ศกร.ตำบล</label>
            <select required id="tumbon" name="tumbon" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
              <option value="">เลือก</option>
              {{-- <option value="0000">ทุกตำบล</option> --}}
              @foreach($all_tumbon as $tm)
                  <option value="{{ $tm->GRP_CODE }}"
                    @if($tumbon == $tm->GRP_CODE) selected @endif  
                    >
                      {{ $tm->GRP_CODE }} {{ $tm->GRP_NAME }}
                  </option>
              @endforeach    
            </select>
          </div>
          <div class="min-w-full">
            <label>ระดับชั้น</label>
              <select required id="lavel" name="lavel" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">เลือก</option>
                <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
              </select>
          </div>
          <div class="min-w-full">
            <label>รายงาน</label>
              {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รายงาน</label> --}}
              <select required id="studreport" name="studreport" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                {{-- @if(request()->get('studreport')!='') <option selected>{{request()->get('studreport')}}</option> @endif
                @if(request()->get('studreport')=='') <option selected >เลือกรายงาน</option> @endif --}}
                <option value="">เลือก</option>
                <option @if($studreport == "นักศึกษาทั้งหมด") selected @endif value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                <option @if($studreport == "เฉพาะผู้คาดว่าจะจบ") selected @endif value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                <option @if($studreport == "ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว) ย้อนหลัง 4 ปี") selected @endif value="ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว) ย้อนหลัง 4 ปี">ไม่จบตกค้าง (ที่ไม่ได้ลงทะเบียนแล้ว)  ย้อนหลัง 4 ปี</option>
              </select>
          </div>
        </div>
        <button type="submit" class="rounded-full p-2 mt-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
      </form>
      {{-- คำอธิบาย --}}
      <div class="grid grid-cols-3 gap-2 mt-2 mx-auto mt-4 max-w-4xl sm:mt-6">  
        <div class="font-semibold text-sm truncate"> <span class="text-indigo-500">N-NET</span> : มีสิทธิสอบ N-NET ภาคเรียนปัจจุบัน </div> 
        <div class="font-semibold pl-2 text-sm truncate"> <span class="text-yellow-500">E-EXAM</span> : มีสิทธิสอบ E-EXAM ภาคเรียนปัจจุบัน </div>
        <!-- Tag 1-->
        <div class="flex items-center text-xs">
          <div class="text-yellow-600 mr-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
            </svg>                            
          </div>
          <span class="text-indigo font-semibold truncate"> : คาดว่าจะจบหลักสูตร</span>
        </div>
      </div>
      @if($data==null && (request()->get('tumbon')!=''))
      <br>
      <div id="alert-additional-content-2" class="p-4 mb-4 text-yellow-800 border border-red-300 rounded-lg bg-yellow-50 dark:bg-yellow-800 dark:text-yellow-400 dark:border-yellow-800" role="alert">
        <div class="flex justify-center">
          <svg class="flex-shrink-0 w-6 h-6 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="sr-only">Info</span>
          <h3 class="text-lg font-medium">ไม่พบข้อมูล</h3>
        </div>
        <div class="flex justify-center mt-2 mb-4 text-sm">
          กรุณาเลือกรายการใหม่อีกครั้ง.
        </div>
        <div class="flex justify-center">
          {{-- <button type="button" class="text-white bg-red-800 hover:bg-red-900 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
              <path d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
            </svg>
            View more
          </button> --}}
          <button type="button" class="w-24 text-yellow-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800" data-dismiss-target="#alert-additional-content-2" aria-label="Close">
            ปิด
          </button>
        </div>
      </div>
        @endif
        @if((request()->get('tumbon')==''))
        <div id="alert-border-1" class="mt-2 flex items-center p-4 mb-4 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div class="ms-3 text-sm font-medium">
              กรุณาเลือกรายการที่คุณต้องดู
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-blue-50 text-blue-500 rounded-lg focus:ring-2 focus:ring-blue-400 p-1.5 hover:bg-blue-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-blue-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-border-1" aria-label="Close">
              <span class="sr-only">Dismiss</span>
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
              </svg>
            </button>
        </div>
        @endif
      </div>
    </div>
    {{-- Content body --}}
    @if($data) 
      <div class="flex flex-col-1 flex-row-1 justify-center drop-shadow sm:ml-64 max-w-auto p-2">
        <div class="text-md md:text-2xl font-bold bg-indigo-200 w-full p-4 text-center">
          ตำบล : <span class="font-normal">{{request()->get('tumbon')}}</span>
          รายงาน : <span class=" font-normal">{{request()->get('studreport')}}</span>
          ระดับ : <span class="font-normal">
            {{$req_lavel = request()->get('lavel')}}
            @if($req_lavel == 1)  ประถมศึกษา @endif
            @if($req_lavel == 2)  มัธยมต้น @endif
            @if($req_lavel == 3)  มัธยมปลาย @endif
          </span>
          <button onclick="printAllCards({{ json_encode($data) }})" type="button" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
            </svg>  
            พิมพ์บัตรทั้งหมด
          </button>
        </div>
      </div>
      <div class="overflow-auto flex flex-col-1 justify-center sm:ml-64 max-w-auto p-2">
        <table class="min-w-full text-left text-sm font-light">
          <thead class="border-b font-medium bg-white drop-shadow text-xs md:text-sm">                 
            <tr class="bg-gray-200">
              <th scope="col" class="p-2 text-center">ลำดับ</th>
              <th scope="col" class="p-2 text-center">รูปถ่าย</th>
              <th scope="col" class="p-2 text-center">ตำบล</th>
              <th scope="col" class="p-2">รหัส</th>
              <th scope="col" class="p-2">ชื่อ</th>
              <th scope="col" class="p-2">นามสกุล</th>
              <th scope="col" class="p-2 text-center">กพช.</th>
              <th scope="col" class="p-2 text-center">คุณธรรม</th>
              <th scope="col" class="p-2 text-center">คาดว่าจะจบ</th>
              <th scope="col" class="p-2 text-center">N-NET</th>
              <th scope="col" class="p-2 text-center">สถานะ/จบ</th>
              <th scope="col" class="p-2 text-center">พิมพ์บัตร</th>
            </tr>
          </thead>
          <tbody class="text-xs md:text-sm">
            @foreach($data as $d)
              <tr 
                @if($d['lavel']==1) class="border-b bg-pink-100 shadow-md hover:bg-pink-300" @endif
                @if($d['lavel']==2) class="border-b bg-green-100 shadow-md hover:bg-green-300" @endif
                @if($d['lavel']==3) class="border-b bg-yellow-100 shadow-md hover:bg-yellow-300" @endif
              >
                <td class="p-2 text-center">{{$loop->iteration}}</td>
                <td class="flex justify-center items-center p-2">
                  <img class="w-12 h-13 object-cover md:aspect-auto aspect-[7/8]"
                      src="https://phothongdlec.ac.th/storage/images/avatar/{{$d['id']}}.png" 
                      alt="Preview Image" 
                      onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                </td>
                <td class="p-2 text-center">{{$d['grp_code']}}</td>
                <td class="p-2">{{$d['id']}}</td>
                <td class="p-2 w-15">{{$d['name']}}</td>
                <td class="p-2">{{$d['surname']}}</td>
                <td 
                  @if($d['activity']>=200) class="p-2 text-center text-green-400" @endif
                  @if($d['activity']<200) class="p-2 text-center text-yellow-600" @endif
                >{{$d['activity']}}</td>
                <td class="p-2 text-center">
                  @if($d['ablevel1']===null) <span class="text-yellow-700">-</span> @endif
                  @if($d['ablevel1']===0) ปรับปรุง @endif 
                  @if($d['ablevel1']===1) พอใช้ @endif 
                  @if($d['ablevel1']===2) ดี @endif 
                  @if($d['ablevel1']===3) ดีมาก @endif 
                </td>            
                @if($d['expfin']==1)
                  <td class="p-2 text-yellow-600 text-center">
                    <div class="flex justify-center items-center">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </td>              
                @else
                  <td class="p-2 text-center">  </td>
                @endif
                @if($d['nt_sem']=="ผ่านแล้ว")
                  <td class="p-2 text-center text-green-500">{{$d['nt_sem']}}</td>
                @elseif($d['nt_sem']=="มีสิทธิ")
                  <td class="p-2 text-center text-indigo-500">N-NET</td>
                @elseif($d['nt_sem']=="E-Exam")
                  <td class="p-2 text-center text-yellow-500">{{$d['nt_sem']}}</td>
                @else
                  <td class="p-2 text-center">{{$d['nt_sem']}}</td>
                @endif
                <td class="p-2 text-center">
                  @if($d['fin_cause']=='' || $d['fin_cause']==0 || $d['fin_cause']==null) <span class="text-yellow-700">ศึกษาอยู่</span> @endif
                  @if($d['fin_cause']==1) <span class="text-green-400">จบหลักสูตร</span> @endif 
                  @if($d['fin_cause']==2) ลาออก @endif 
                  @if($d['fin_cause']==3) <span class="text-red-400">หมดสภาพ</span> @endif 
                  @if($d['fin_cause']==4) พ้นสภาพ @endif 
                  @if($d['fin_cause']==5) ศึกษาต่อที่อื่น @endif 
                  @if($d['fin_cause']==6) ศึกษาเพิ่งหลังจบ @endif 
                  @if($d['fin_cause']==7) จบตกหล่น @endif 
                  @if($d['fin_cause']==8) อื่นๆ @endif 
                  @if($d['fin_cause']==9) จบอยู่ระหว่างตรวจสอบวุฒิ @endif 
                </td>
                <td class="p-2 text-center">
                  <button onclick="printCard({{ json_encode($d) }})" type="button" class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path fill-rule="evenodd" d="M8 3a2 2 0 0 0-2 2v3h12V5a2 2 0 0 0-2-2H8Zm-3 7a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h1v-4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v4h1a2 2 0 0 0 2-2v-5a2 2 0 0 0-2-2H5Zm4 11a1 1 0 0 1-1-1v-4h8v4a1 1 0 0 1-1 1H9Z" clip-rule="evenodd"/>
                    </svg>                  
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  
  <script>
    function printCard(studentData) {
      // ตัวอย่างการใช้งานข้อมูลนักศึกษา
      console.log("ข้อมูลนักศึกษา:", studentData);
  
      // ตัวอย่างการพิมพ์บัตร
      alert(`พิมพ์บัตรสำหรับนักศึกษา: ${studentData.name} ${studentData.surname}`);
      
      // คุณสามารถเพิ่มโค้ดเพื่อพิมพ์บัตรที่นี่
      // เช่น เปิดหน้าต่างใหม่และแสดงข้อมูลบัตร
    }
  </script>
  </div>
</x-teachers-layout>
@include('layouts.footer')

<!-- Include html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // คำนวณวันหมดอายุ (เพิ่มปี)
    function calculateExpiryDate(currentDate, yearsToAdd) {
        const expiryDate = new Date(currentDate);
        expiryDate.setFullYear(expiryDate.getFullYear() + yearsToAdd);
        return expiryDate;
    }

    // จัดรูปแบบวันที่ (dd/mm/yyyy)
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
    // Print Card Function
    function printCard(student_data) {
        const currentDate = new Date();
        const expiryDate = calculateExpiryDate(currentDate, 5); // หมดอายุใน 5 ปี
        let level;

        // กำหนดระดับชั้น
        if (student_data['lavel'] == 3) {
            level = 'มัธยมปลาย';
        } else if (student_data['lavel'] == 2) {
            level = 'มัธยมต้น';
        } else {
            level = 'ประถมศึกษา';
        }

        // ข้อมูลนักศึกษา
        const studentData = {
            prename: student_data['prename'], // คำนำหน้า (ถ้ามี)
            name: student_data['name'] || 'ไม่ระบุ',
            surname: student_data['surname'] || 'ไม่ระบุ',
            id: student_data['id'] || '000000000', // รหัสนักศึกษา
            cardid: student_data['cardid'],
            level: level, // ระดับชั้น
            department: 'สกร.ระดับอำเภอโพธิ์ทอง', // สถานศึกษา
            issueDate: formatDate(currentDate), // วันที่ออกบัตร
            expiryDate: formatDate(expiryDate), // วันที่หมดอายุ
        };

        // URL รูปภาพโปรไฟล์
        const avatarUrl = student_data['user_avatar'] || 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png';

        // HTML สำหรับบัตร
        const printContent = `
            <style>
                @media print {
                    /* บังคับให้พิมพ์ background image และ background color */
                    .print-background {
                        -webkit-print-color-adjust: exact; /* สำหรับ Chrome/Safari */
                        color-adjust: exact; /* สำหรับ Firefox */
                        print-color-adjust: exact; /* มาตรฐานใหม่ */
                    }
                }
            </style>
            <div id="student-card" style="width: 8.6cm; height: 5.4cm; border: 1px solid #000; padding: 0px; font-family: 'Prompt', sans-serif; font-size: 12px; position: relative; box-sizing: border-box;">
                <!-- Background Logo with Reduced Opacity -->
                <div class="print-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://phothongdlec.ac.th/storage/logo.png'); background-size: contain; background-repeat: no-repeat; background-position: center; opacity: 0.1; z-index: 1;"></div>
                <!-- Card Content -->
                <div style="position: relative; z-index: 2;">
                    <canvas id="qr-code" style="position: absolute; top: 10px; right: 10px;">
                    QR CODE
                    </canvas>
                    <h1 style="text-align: center; font-size: 16px; margin-bottom: 5px;">บัตรประจำตัวนักศึกษา</h1>
                    <div style="display: flex; height: calc(100% - 30px); justify-content: space-between; align-items: flex-start;">
                        <!-- Profile Image Section -->
                        <div style="width: 40%; display: flex; justify-content: center; align-items: center;">
                            <div style="width: 70%; aspect-ratio: 7 / 8; border: 1px solid #000; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img id="preview" src="${avatarUrl}" alt="Student Image" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                            </div>
                        </div>
                        <!-- Information Section -->
                        <div style="width: calc(100% - 3cm); display: flex; flex-direction: column; justify-content: space-between; padding-left: 10px; box-sizing: border-box;">
                            <p style="margin: 0;"><strong>ชื่อ:</strong> ${studentData.prename}${studentData.name} ${studentData.surname}</p>
                            <p style="margin: 0;"><strong>รหัสนักศึกษา:</strong> ${studentData.id}</p>
                            <p style="margin: 0;"><strong>รหัสบัตรประชาชน:</strong> ${studentData.cardid}</p>
                            <p style="margin: 0;"><strong>ระดับชั้น:</strong> ${studentData.level}</p>
                            <p style="margin: 0;"><strong>สถานศึกษา:</strong> ${studentData.department}</p>
                            <p style="margin: 0; display: none;"><strong>ออกบัตร:</strong> ${studentData.issueDate}</p>
                            <p style="margin: 0; display: none;"><strong>หมดอายุ:</strong> ${studentData.expiryDate}</p>
                        </div>
                    </div>
                    <!-- Signature Section -->
                    <div style="width: 100%; display: flex; justify-content: space-between; margin-top: -45px;">
                        <div style="width: 48%; text-align: center; padding: 10px;">
                            <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                            <p style="margin-top: 5px; margin-bottom: 0;">ลงชื่อนักศึกษา</p>
                        </div>
                        <div style="width: 48%; text-align: center; padding: 10px;">
                            <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                            <p style="margin-top: 5px; margin-bottom: 0;">ลงชื่อผู้อำนวยการฯ</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Print and Download Buttons -->
            <div style="text-align: center; margin-top: 20px; width: 8.6cm;">
                <button onclick="window.print()" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
                    กดพิมพ์บัตร
                </button>
            </div>
            <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
        `;

        // เปิดหน้าต่างใหม่และแสดงบัตร
        const newWindow = window.open('', '', 'width=800,height=600');
        newWindow.document.write(printContent);

        // สร้าง QR Code
        const qrCanvas = newWindow.document.getElementById('qr-code');
        QRCode.toCanvas(qrCanvas, JSON.stringify(studentData), {
            width: 80,
            margin: 2,
        })
        .then(() => {
            newWindow.document.close();
        })
        .catch((error) => {
            console.error('Error generating QR code:', error);
            newWindow.document.close();
        });
    }



// ***********************************
function printAllCards(students) {
    if (!students || students.length === 0) {
        alert('ไม่มีข้อมูลนักศึกษา');
        return;
    }

    // สร้าง HTML สำหรับบัตรทั้งหมด
    let allCardsContent = `
        <style>
            @media print {
                /* บังคับให้พิมพ์ background image และ background color */
                .print-background {
                    -webkit-print-color-adjust: exact; /* สำหรับ Chrome/Safari */
                    color-adjust: exact; /* สำหรับ Firefox */
                    print-color-adjust: exact; /* มาตรฐานใหม่ */
                }
                .student-card {
                    page-break-inside: avoid; /* ป้องกันบัตรถูกตัดระหว่างหน้า */
                    margin-bottom: 20px; /* ระยะห่างระหว่างบัตร */
                }
                .print-button {
                    display: none; /* ซ่อนปุ่มพิมพ์เมื่อพิมพ์ */
                }
            }
            /* จัดรูปแบบบัตรใน 2 คอลัมน์ */
            .card-container {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 20px;
            }
            /* ปุ่มพิมพ์ที่มุมบนขวา */
            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: green;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                z-index: 1000;
            }
        </style>
        <!-- ปุ่มพิมพ์ -->
        <button class="print-button" onclick="window.print()">พิมพ์</button>
        <!-- บัตรทั้งหมด -->
        <div class="card-container">
    `;

    // เพิ่มบัตรแต่ละใบเข้าไปใน HTML
    students.forEach((student) => {
        const cardContent = generateCardContent(student);
        allCardsContent += `<div class="student-card">${cardContent}</div>`;
    });

    allCardsContent += `</div>`; // ปิดแท็ก div หลัก

    // เปิดหน้าต่างใหม่และแสดงบัตรทั้งหมด
    const newWindow = window.open('', '', 'width=800,height=600');
    newWindow.document.write(allCardsContent);
    newWindow.document.close();
}

// สร้าง HTML สำหรับบัตรแต่ละใบ
function generateCardContent(student_data) {
    const currentDate = new Date();
    const expiryDate = calculateExpiryDate(currentDate, 5); // หมดอายุใน 5 ปี
    let level;

    // กำหนดระดับชั้น
    if (student_data['lavel'] == 3) {
        level = 'มัธยมปลาย';
    } else if (student_data['lavel'] == 2) {
        level = 'มัธยมต้น';
    } else {
        level = 'ประถมศึกษา';
    }

    // ข้อมูลนักศึกษา
    const studentData = {
        prename: student_data['prename'], // คำนำหน้า (ถ้ามี)
        name: student_data['name'] || 'ไม่ระบุ',
        surname: student_data['surname'] || 'ไม่ระบุ',
        id: student_data['id'] || '000000000', // รหัสนักศึกษา
        cardid: student_data['cardid'],
        level: level, // ระดับชั้น
        department: 'สกร.ระดับอำเภอโพธิ์ทอง', // สถานศึกษา
        issueDate: formatDate(currentDate), // วันที่ออกบัตร
        expiryDate: formatDate(expiryDate), // วันที่หมดอายุ
    };

    // URL รูปภาพโปรไฟล์
    const avatarUrl = `https://phothongdlec.ac.th/storage/images/avatar/${studentData.id}.png` || 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png';

    // HTML สำหรับบัตร
    return `
            <div id="student-card" style="width: 8.6cm; height: 5.4cm; border: 1px solid #000; padding: 0px; font-family: 'Prompt', sans-serif; font-size: 12px; font-weight: bold; position: relative; box-sizing: border-box; color: black;">
                <!-- Background Logo with Reduced Opacity -->
                <div class="print-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://phothongdlec.ac.th/storage/logo.png'); background-size: contain; background-repeat: no-repeat; background-position: center; opacity: 0.05; z-index: 1;"></div>
                <!-- Card Content -->
                <div style="position: relative; z-index: 2;">
                    <canvas id="qr-code" style="position: absolute; top: 10px; right: 10px;">
                    QR CODE
                    </canvas>
                    <h1 style="text-align: center; font-size: 16px; margin-bottom: 5px; color: black;">บัตรประจำตัวนักศึกษา</h1>
                    <div style="display: flex; height: calc(100% - 30px); justify-content: space-between; align-items: flex-start;">
                        <!-- Profile Image Section -->
                        <div style="width: 40%; display: flex; justify-content: center; align-items: center;">
                            <div style="width: 70%; aspect-ratio: 7 / 8; border: 1px solid #000; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                <img id="preview" src="${avatarUrl}" alt="Student Image" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                            </div>
                        </div>
                        <!-- Information Section -->
                        <div style="width: calc(100% - 3cm); display: flex; flex-direction: column; justify-content: space-between; padding-left: 3px; box-sizing: border-box;">
                            <p style="margin: 0; color: black;"><strong>ชื่อ:</strong> ${studentData.prename}${studentData.name} ${studentData.surname}</p>
                            <p style="margin: 0; color: black;"><strong>รหัสนักศึกษา:</strong> ${studentData.id}</p>
                            <p style="margin: 0; color: black;"><strong>รหัสบัตรประชาชน:</strong> ${studentData.cardid}</p>
                            <p style="margin: 0; color: black;"><strong>ระดับชั้น:</strong> ${studentData.level}</p>
                            <p style="margin: 0; color: black;"><strong>สถานศึกษา:</strong> ${studentData.department}</p>
                            <p style="margin: 0; display: none; color: black;"><strong>ออกบัตร:</strong> ${studentData.issueDate}</p>
                            <p style="margin: 0; display: none; color: black;"><strong>หมดอายุ:</strong> ${studentData.expiryDate}</p>
                        </div>
                    </div>
                    <!-- Signature Section -->
                    <div style="width: 100%; display: flex; justify-content: space-between; margin-top: -45px;">
                        <div style="width: 48%; text-align: center; padding: 10px;">
                            <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                            <p style="margin-top: 5px; margin-bottom: 0; color: black;">ลงชื่อนักศึกษา</p>
                        </div>
                        <div style="width: 48%; text-align: center; padding: 10px;">
                            <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                            <p style="margin-top: 5px; margin-bottom: 0; color: black;">ลงชื่อผู้อำนวยการฯ</p>
                        </div>
                    </div>
                </div>
            </div>
    `;
}
</script>


