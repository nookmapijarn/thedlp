<x-teachers-layout>
  <div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="text-2xl font-bold w-full text-center">รายงานผลการเรียน</div>
        <form method="GET" action="{{ route('tgrade') }}" class="">
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-3 justify-items-center">
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
            <div class="min-w-full">
              <label>ระดับชั้น</label>
                <select required id="lavel" name="lavel" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="">เลือก</option>
                  <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                  <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                  <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full p-2 mt-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
        </form>

        {{-- Table --}}
    @if($data)
    <div class="flex flex-col-1 justify-center max-w-srceen-md">
        <div class="w-full overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden">
                <table class="w-full text-left text-sm font-light border-separate border border-slate-400 ...">
                  {{-- <div class="text-2xl font-bold w-full text-center">ตารางสรุปผลการเรียน</div> --}}
                <div class="flex flex-col-1 flex-row-1 justify-center p-4 bg-indigo-200 drop-shadow">
                  <div class="text-lg font-bold">ตำบล : <span class="font-normal">{{request()->get('tumbon')}}</span></div>
                  <div class="text-lg font-bold pl-4">ระดับชั้น : <span class=" font-normal">{{request()->get('lavel')}}</span></div>
                </div>
                <div class="flex flex-col-1 flex-row-1 justify-center p-4">
                  <div class="text-sm font-bold">ความหมาย : <span class="font-normal"> รหัสวิชา (เกรด/คะแนนรวม) </span></div>
                </div>
                <thead class="border-b font-medium bg-white drop-shadow text-xs md:text-sm">                 
                  <tr class="bg-gray-100">
                    <th scope="col" class="px-5 p-2 text-center">ลำดับ</th>
                    <th scope="col" class="px-5 p-2">รหัส</th>
                    <th scope="col" class="px-5 p-2">ชื่อ</th>
                    <th scope="col" class="px-5 p-2">นามสกุล</th>
                  </tr>
                </thead>
                <tbody class="text-xs md:text-sm">
                @foreach($data as $s)
                <tr
                  @if(request()->get('lavel')==1) class="bg-pink-50 hover:bg-pink-300" @endif
                  @if(request()->get('lavel')==2) class="bg-green-50 hover:bg-green-300" @endif
                  @if(request()->get('lavel')==3) class="bg-yellow-50 hover:bg-yellow-300" @endif
                >
                  <td class="p-0 text-center border border-slate-300 ...">{{$loop->iteration}}</td>
                  <td class="p-0 border border-slate-300 ...">{{$s['ID']}}</td>
                  <td class="p-0 border border-slate-300 ...">{{$s['NAME']}}</td>
                  <td class="p-0 w-15 border border-slate-300 ...">{{$s['SURNAME']}}</td>
                    @foreach($s['ALL_GRADE'] as $g)
                      <td class="text-green-600 p-0 text-left border border-slate-300 ... " >
                        <span
                          @if($g->GRADE == 0)class="text-red-600"@endif
                          @if($g->GRADE > 0 && !is_numeric($g->GRADE))class="text-yellow-600"@endif
                          @if($g->GRADE == '' || $g->GRADE == null)class="text-yellow-600"@endif  
                        >
                        @if($g->TYP_CODE == 7) **ซ่อม** @endif {{$g->SUB_CODE}} ({{$g->GRADE}}/{{$g->TOTAL}})
                        </span>
                      </td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      @endif

      @if($data==null && (request()->get('tumbon')!=''))
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
      <br>
      @if((request()->get('tumbon')==''))
      <div id="alert-border-1" class="flex items-center p-4 mb-4 text-blue-800 border-t-4 border-blue-300 bg-blue-50 dark:text-blue-400 dark:bg-gray-800 dark:border-blue-800" role="alert">
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
</x-teachers-layout>