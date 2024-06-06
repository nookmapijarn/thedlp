<x-teachers-layout>
  <div class="p-2 sm:ml-64">
    <div class="p-0 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
      <form method="POST" action="{{ route('treport') }}" class="mx-auto mt-4 max-w-4xl sm:mt-6">
        @csrf
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-3 justify-items-center">
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
            <label>รายงาน</label>
              {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รายงาน</label> --}}
              <select required id="studreport" name="studreport" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                {{-- @if(request()->get('studreport')!='') <option selected>{{request()->get('studreport')}}</option> @endif
                @if(request()->get('studreport')=='') <option selected >เลือกรายงาน</option> @endif --}}
                <option value="">เลือก</option>
                <option value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                <option value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                <option value="ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว)">ไม่จบตกค้าง (ที่ไม่ได้ลงทะเบียนแล้ว)</option>
              </select>
          </div>
        </div>
        <button type="submit" class="rounded-full p-4 mt-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
      </form>
      {{-- คำอธิบาย --}}
      <div class="grid grid-cols-3 gap-2 mt-2 mx-auto mt-4 max-w-4xl sm:mt-6">  
        <div class="font-semibold text-xs truncate"> <span class="text-indigo-500">N-NET</span> : มีสิทธิสอบ N-NET ภาคเรียนปัจจุบัน </div> 
        <div class="font-semibold pl-2 text-xs truncate"> <span class="text-yellow-500">E-EXAM</span> : มีสิทธิสอบ E-EXAM ภาคเรียนปัจจุบัน </div>
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
        <div class="text-sm font-bold bg-indigo-200 w-full p-4 text-center">
          ตำบล : <span class="font-normal">{{request()->get('tumbon')}}</span>
          รายงาน : <span class=" font-normal">{{request()->get('studreport')}}</span>
        </div>
      </div>
      <div class="overflow-auto flex flex-col-1 justify-center sm:ml-64 max-w-auto p-2">
          <table class="min-w-full text-left text-sm font-light">
          <thead class="border-b font-medium bg-white drop-shadow text-xs md:text-sm">                 
            <tr class="bg-gray-200">
              <th scope="col" class="p-2 text-center">ลำดับ</th>
              <th scope="col" class="p-2 text-center">ตำบล</th>
              <th scope="col" class="p-2">รหัส</th>
              {{-- <th scope="col" class="p-2 hidden md:block text-center">ระดับ</th> --}}
              <th scope="col" class="p-2">ชื่อ</th>
              <th scope="col" class="p-2">นามสกุล</th>
              <th scope="col" class="p-2 text-center">สถานะ/จบ</th>
              <th scope="col" class="p-2 text-center">คาดว่าจะจบ</th>
              <th scope="col" class="p-2 text-center">กพช.</th>
              <th scope="col" class="p-2 text-center">N-NET</th>
              {{-- <th scope="col" class="p-2 text-center">จัดการ</th> --}}
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
              <td class="p-2 text-center">{{$d['grp_code']}}</td>
              <td class="p-2">{{$d['id']}}</td>
              {{-- <td class="p-2 hidden md:block text-center">{{$d['lavel']}}</td> --}}
              <td class="p-2 w-15">{{$d['name']}}</td>
              <td class="p-2">{{$d['surname']}}</td>
              {{-- สถานะ --}}
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
              {{-- คาดว่าจะจบ --}}
              @if($d['expfin']==1)
              <td class="p-2 text-yellow-600 text-center flex flex-col-1 justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-center">
                  <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                </svg>                                                                                  
              </td>
              @else
              <td class="p-2 text-center">-</td>
              @endif
              {{-- กพช --}}
              <td 
                @if($d['activity']>=200) class="p-2 text-center text-green-400" @endif
                @if($d['activity']<200) class="p-2 text-center text-yellow-600" @endif
              >{{$d['activity']}}</td>
              {{-- ประเมินระดับชาติ --}}
              @if($d['nt_sem']=="ผ่านแล้ว")
              <td class="p-2 text-center text-green-500">{{$d['nt_sem']}}</td>
              @elseif($d['nt_sem']=="มีสิทธิ")
              <td class="p-2 text-center text-indigo-500">N-NET</td>
              @elseif($d['nt_sem']=="E-Exam")
              <td class="p-2 text-center text-yellow-500">{{$d['nt_sem']}}</td>
              @else
              <td class="p-2 text-center">{{$d['nt_sem']}}</td>
              @endif
              {{-- <td><button id="openModalButton">Open Modal</button></td> --}}
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</x-teachers-layout>
