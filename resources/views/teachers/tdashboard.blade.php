<x-teachers-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight m-2">
            {{ __('ข้อมูลนักศึกษา ภาคเรียนปัจจุบัน') }} {{$semestry}}
        </h6>
        <form method="GET" action="{{ route('tdashboard') }}" class="">
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-2 justify-items-center">
            <div class="min-w-full" >
                {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ศสกร.ตำบล</label> --}}
                <select required id="tumbon" name="tumbon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  {{-- @if(request()->get('tumbon')!='') <option selected>{{request()->get('tumbon')}}</option> @endif
                  @if(request()->get('tumbon')=='') <option selected>เลือกตำบล</option> @endif --}}
                  <option value="">เลือกตำบล</option>
                  <option value="4011 บางพลับ">4011 บางพลับ</option>
                  <option value="4012 บางพลับ">4012 บางพลับ</option>
                  <option value="4021 อ่างแก้ว">4021 อ่างแก้ว</option>
                  <option value="4031 หนองแม่ไก่">4031 หนองแม่ไก่</option>
                  <option value="4041 ยางช้าย">4041 ยางช้าย</option>
                  <option value="4051 โพธิ์รังนก">4051 โพธิ์รังนก</option>
                  <option value="4061 รำมะสัก">4061 รำมะสัก</option>
                  <option value="4071 บางระกำ">4071 บางระกำ</option>
                  <option value="4081 บ่อแร่">4081 บ่อแร่</option>
                  <option value="4091 สามง่าม">4091 สามง่าม</option>
                  <option value="4101 ทางพระ">4101 ทางพระ</option>
                  <option value="4102 ทางพระ">4102 ทางพระ</option>
                  <option value="4111 อินทประมูล">4111 อินทประมูล</option>
                  <option value="4121 องครักษ์">4121 องครักษ์</option>
                  <option value="4131 โคกพุทรา">4131 โคกพุทรา</option>
                  <option value="4141 บางเจ้าฉ่า">4141 บางเจ้าฉ่า</option>
                  <option value="4151 คำหยาด">4151 คำหยาด</option>
                  <option value="4171 พิการ">4171 พิการ</option>
                </select>
              </div>
            <div class="min-w-full">
                {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รายงาน</label> --}}
                <select required id="studreport" name="studreport" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  {{-- @if(request()->get('studreport')!='') <option selected>{{request()->get('studreport')}}</option> @endif
                  @if(request()->get('studreport')=='') <option selected >เลือกรายงาน</option> @endif --}}
                  <option value="">เลือกรายงาน</option>
                  <option value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                  <option value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                  <option value="ไม่จบตกค้าง(ที่ไม่ได้ลงทะเบียนแล้ว)">ไม่จบตกค้าง (ที่ไม่ได้ลงทะเบียนแล้ว)</option>
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full p-2 mt-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
        </form>
        {{-- <div>
          <span class="text-red-500 font-medium">หมายเหตุ</span>
        </div> --}}
        <div class="flex flex-col-1 flex-row-1 mt-2 justify-center">
          <span class="text-indigo-500 font-semibold text-xs truncate">มีสิทธิ <span class="text-gray-700 "> : มีสิทธิสอบ N-NET </span></span> 
          <span class="text-yellow-500 font-semibold pl-2 text-xs truncate">E-Exam <span class="text-gray-700"> : มีสิทธิสอบ E-Exam </span></span>
          <span class="text-green-500 font-semibold pl-2 text-xs truncate">ผ่านแล้ว <span class="text-gray-700"> : สอบผ่านแล้ว</span></span>  
        </div>
    </x-slot>

    <div class="flex flex-col max-w-srceen-lg">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light">
                <div class="flex flex-col-1 flex-row-1 justify-center p-4 bg-indigo-200 drop-shadow">
                  <div class="text-sm font-bold">ตำบล : <span class="font-normal">{{request()->get('tumbon')}}</span></div>
                  <div class="text-sm font-bold pl-4">รายงาน : <span class=" font-normal">{{request()->get('studreport')}}</span></div>
                </div>
                <thead class="border-b font-medium bg-white drop-shadow">                 
                  <tr class="bg-gray-200">
                    <th scope="col" class="p-2 text-center">ลำดับ</th>
                    <th scope="col" class="p-2">รหัส</th>
                    <th scope="col" class="p-2 hidden md:block">ระดับ</th>
                    <th scope="col" class="p-2">ชื่อ</th>
                    <th scope="col" class="p-2">นามสกุล</th>
                    <th scope="col" class="p-2">คาดว่าจะจบ</th>
                    <th scope="col" class="p-2 text-center">กพช.</th>
                    <th scope="col" class="p-2 text-center">N-NET</th>
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
                    <td class="p-2">{{$d['id']}}</td>
                    <td class="p-2 hidden md:block">{{$d['lavel']}}</td>
                    <td class="p-2 w-15">{{$d['name']}}</td>
                    <td class="p-2">{{$d['surname']}}</td>
                    @if($d['expfin']==1)
                    <td class="p-2 text-violet-500">คาดว่าจะจบ</td>
                    @else
                    <td class="p-2">-</td>
                    @endif
                    <td class="p-2 text-center">{{$d['activity']}}</td>
                    @if($d['nt_sem']=="ผ่านแล้ว")
                    <td class="p-2 text-center text-green-500">{{$d['nt_sem']}}</td>
                    @elseif($d['nt_sem']=="มีสิทธิ")
                    <td class="p-2 text-center text-indigo-500">{{$d['nt_sem']}}</td>
                    @elseif($d['nt_sem']=="E-Exam")
                    <td class="p-2 text-center text-yellow-500">{{$d['nt_sem']}}</td>
                    @else
                    <td class="p-2 text-center">{{$d['nt_sem']}}</td>
                    @endif
                  </tr>
                @endforeach
                </tbody>
              </table>
              @if($data==null) <section class="p-10 text-center text-lg">**ไม่มีข้อมูลรายงานนี้**</section> @endif
            </div>
          </div>
        </div>
      </div>
</x-teachers-layout>