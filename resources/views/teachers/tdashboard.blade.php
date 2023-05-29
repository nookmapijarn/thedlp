<x-teachers-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight m-2">
            {{ __('ข้อมูลนักศึกษา ภาคเรียนปัจจุบัน') }}
        </h6>
        <form method="GET" action="{{ route('tdashboard') }}" class="">
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-2 justify-items-center">
            <div class="min-w-full" >
                {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ศสกร.ตำบล</label> --}}
                <select id="tumbon" name="tumbon" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option selected>{{request()->get('tumbon')}}</option>
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
                  <option value="4117 พิการ">4117 พิการ</option>
                </select>
              </div>
            <div class="min-w-full">
                {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รายงาน</label> --}}
                <select id="studreport" name="studreport" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option selected>{{request()->get('studreport')}}</option>
                  <option value="นักศึกษาทั้งหมด">นักศึกษาทั้งหมด</option>
                  <option value="เฉพาะผู้คาดว่าจะจบ">เฉพาะผู้คาดว่าจะจบ</option>
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full p-2 m-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
        </form>
    </x-slot>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-srceen-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-sm font-light">
                <thead class="border-b font-medium dark:border-neutral-500 bg-white">                  
                  <tr class="bg-gray-200">
                    <th scope="col" class="p-2 text-center">ลำดับ</th>
                    <th scope="col" class="p-2">รหัส</th>
                    <th scope="col" class="p-2 hidden md:block">ระดับ</th>
                    <th scope="col" class="p-2">ชื่อ</th>
                    <th scope="col" class="p-2">นามสกุล</th>
                    <th scope="col" class="p-2">จะจบ</th>
                    <th scope="col" class="p-2 text-center">กพช.</th>
                    <th scope="col" class="p-2 text-center">NT/EX</th>
                  </tr>
                </thead>
                <tbody class="text-xs md:text-sm">
                @foreach($data as $d)
                  <tr 
                  @if($d['lavel']==1) class="border-b dark:border-neutral-500 bg-pink-200 shadow-md hover:bg-indigo-200" @endif
                  @if($d['lavel']==2) class="border-b dark:border-neutral-500 bg-green-200 shadow-md hover:bg-indigo-200" @endif
                  @if($d['lavel']==3) class="border-b dark:border-neutral-500 bg-yellow-200 shadow-md hover:bg-indigo-200" @endif
                  >
                    <td class="p-2 text-center">{{$loop->iteration}}</td>
                    <td class="p-2">{{$d['id']}}</td>
                    <td class="p-2 hidden md:block">{{$d['lavel']}}</td>
                    <td class="p-2 w-15">{{$d['name']}}</td>
                    <td class="p-2">{{$d['surname']}}</td>
                    <td class="p-2">{{$d['expfin']}}</td>
                    <td class="p-2 text-center">{{$d['activity']}}</td>
                    @if($d['nt_sem']=="เข้ารับแล้ว")
                    <td class="p-2 text-center text-green-500">{{$d['nt_sem']}}</td>
                    @elseif($d['nt_sem']=="ยังไม่เข้ารับ")
                    <td class="p-2 text-center text-red-500">{{$d['nt_sem']}}</td>
                    @elseif($d['nt_sem']=="E-Exam")
                    <td class="p-2 text-center text-yellow-500">{{$d['nt_sem']}}</td>
                    @else
                    <td class="p-2 text-center">{{$d['nt_sem']}}</td>
                    @endif
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</x-teachers-layout>