<x-teachers-layout>
    <x-slot name="header">
        <div class="text-2xl font-bold w-full text-center">รายงานผลการเรียน</div>
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight m-2">
            {{ __('ภาคเรียนที่') }} {{$semestry}}
        </h6>
        <form method="GET" action="{{ route('tgrade') }}" class="">
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
                <select required id="lavel" name="lavel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  {{-- @if(request()->get('studreport')!='') <option selected>{{request()->get('studreport')}}</option> @endif
                  @if(request()->get('studreport')=='') <option selected >เลือกรายงาน</option> @endif --}}
                  <option value="">ระดับชั้น</option>
                  <option value="1">1.ประถม</option>
                  <option value="2">2.มัธยมต้น</option>
                  <option value="3">3.มัธยมปลาย</option>
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full p-2 mt-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
        </form>
    </x-slot>

    <div class="flex flex-col max-w-srceen-lg">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light border-separate border border-slate-400 ...">
                  {{-- <div class="text-2xl font-bold w-full text-center">ตารางสรุปผลการเรียน</div> --}}
                <div class="flex flex-col-1 flex-row-1 justify-center p-4 bg-indigo-200 drop-shadow">
                  <div class="text-lg font-bold">ตำบล : <span class="font-normal">{{request()->get('tumbon')}}</span></div>
                  <div class="text-lg font-bold pl-4">ระดับชั้น : <span class=" font-normal">{{request()->get('lavel')}}</span></div>
                </div>
                <div class="flex flex-col-1 flex-row-1 justify-center p-4">
                  <div class="text-sm font-bold">ความหมาย : <span class="font-normal"> รหัสวิชา (เกรด/คะแนนรวม) </span></div>
                </div>
                {{-- <thead class="border-b font-medium bg-white drop-shadow text-xs md:text-sm">                 
                  <tr class="bg-gray-100">
                    <th scope="col" class="px-5 p-2 text-center">ลำดับ</th>
                    <th scope="col" class="px-5 p-2">รหัส</th>
                    <th scope="col" class="px-5 p-2">ชื่อ</th>
                    <th scope="col" class="px-5 p-2">นามสกุล</th>
                  </tr>
                </thead> --}}
                <tbody class="text-xs md:text-sm">
                @foreach($data as $s)
                <tr
                  @if(request()->get('lavel')==1) class="bg-pink-50 hover:bg-pink-300" @endif
                  @if(request()->get('lavel')==2) class="bg-green-50 hover:bg-green-300" @endif
                  @if(request()->get('lavel')==3) class="bg-yellow-50 hover:bg-yellow-300" @endif
                >
                  <td class="p-2 text-center border border-slate-300 ...">{{$loop->iteration}}</td>
                  <td class="p-2 border border-slate-300 ...">{{$s['ID']}}</td>
                  <td class="p-2 hidden md:block border border-slate-300 ...">{{$s['NAME']}}</td>
                  <td class="p-2 w-15 border border-slate-300 ...">{{$s['SURNAME']}}</td>
                    @foreach($s['ALL_GRADE'] as $g)
                      <td class="p-2 text-left border border-slate-300 ...">{{$g->SUB_CODE}} ({{$g->GRADE}}/{{$g->TOTAL}})</td>
                    @endforeach
                </tr>
                @endforeach
                </tbody>
              </table>
              @if($data==null) <section class="p-10 text-center text-lg">**ไม่พบข้อมูล กรุณาเลือกรายการใหม่**</section> @endif
            </div>
          </div>
        </div>
      </div>
</x-teachers-layout>