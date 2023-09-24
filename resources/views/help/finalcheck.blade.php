<x-help-layout>

    <div class="relative pb-4">
        <div class="px-4 mx-auto mt-10 max-w-7xl sm:mt-14">
          <div class="flex justify-center">
            <img class="w-20 h-20 rounded-full" src="{{asset('storage/logo-app.jpg');}}" alt="Rounded avatar">
        </div>
          <div class="text-center"> 
              <h1 class="font-display mt-4 text-4xl tracking-tight font-extrabold text-gray-900">
                ระบบสืบค้นข้อมูลผู้จบหลักสูตร
              </h1>
            <p class="max-w-md mx-auto mt-3 text-base text-gray-500 sm:text-sm md:mt-5 md:text-lg md:max-w-3xl">
              หลักสูตรการศึกษานอกระบบระดับการศึกษาขั้นพื้นฐาน พุทธศักราช 2551
              ของสถานศึกษาในสังกัดสำนักงานส่งเสริมการศึกษาจังหวัดอ่างทอง
            </p>
            <div class="relative max-w-3xl px-4 mx-auto mt-10 sm:px-6">

                <form action="{{ route('finalcheck.search') }}" method="POST">   
                    @csrf
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" name="search" autofocus maxlength="13" id="default-search" value="{{$old}}" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="เลขบัตรประชาชน 13 หลัก" required>
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                    </div>
                </form>
            </div>
          </div>
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
            @if($value)
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            รหัสนักศึกษา
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ชื่อ
                        </th>
                        <th scope="col" class="px-6 py-3">
                            นามสกุล
                        </th>
                        <th scope="col" class="px-6 py-3">
                            สถานะปัจุบัน
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ระดับชั้น
                        </th>
                        <th scope="col" class="px-6 py-3">
                            ภาคเรียน
                        </th>
                        <th scope="col" class="px-6 py-3">
                            เลขที่วุฒิ
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($value as $v)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$v->ID}}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$v->NAME}}
                        </th>
                        <td class="px-6 py-4">
                            {{$v->SURNAME}}
                        </td>
                        <td class="px-6 py-4">
                            @if($v->FIN_CAUSE==1) <span class="text-green-400">จบหลักสูตร</span> @endif 
                            @if($v->FIN_CAUSE==2) ลาออก @endif 
                            @if($v->FIN_CAUSE==3) หมดสภาพ @endif 
                            @if($v->FIN_CAUSE==4) พ้นสภาพ @endif 
                            @if($v->FIN_CAUSE==5) ศึกษาต่อที่อื่น @endif 
                            @if($v->FIN_CAUSE==6) ศึกษาเพิ่งหลังจบ @endif 
                            @if($v->FIN_CAUSE==7) จบตกหล่น @endif 
                            @if($v->FIN_CAUSE==8) อื่นๆ @endif 
                            @if($v->FIN_CAUSE==9) จบอยู่ระหว่างตรวจสอบวุฒิ @endif 
                            @if($v->FIN_CAUSE=='') ยังไม่ระบุ @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($v->LAVEL==1) ประถม @endif 
                            @if($v->LAVEL==2) มัธยมต้น @endif 
                            @if($v->LAVEL==3) มัธยมปลาย @endif 
                        </td>
                        <td class="px-6 py-4">
                            {{$v->FIN_SEM}}
                            @if($v->FIN_SEM=='') - @endif
                        </td>
                        <td class="px-6 py-4">
                            {{$v->TRNRUN}}
                            @if($v->TRNRUN=='') - @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
              @if(empty($value)) 
                <div class="text-center p-5">
                    <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                        <span class="font-medium">ไม่พบข้อมูล!</span>
                      </div>
                </div>
              @endif
            @endif 
        </div>
    </div>
      </div>        
    
</x-help-layout>