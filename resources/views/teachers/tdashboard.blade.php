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
                  <option value="US" selected>เลือก ศสกร.ตำบล</option>
                  <option value="4011">4011 บางพลับ</option>
                  <option value="4021">4021 อ่างแก้ว</option>
                  <option value="4031">4031 หนองแม่ไก่</option>
                  <option value="4041">4041 ยางช้าย</option>
                  <option value="4051">4051 โพธิ์รังนก</option>
                  <option value="4061">4061 รำมะสัก</option>
                  <option value="4071">4071 บางระกำ</option>
                  <option value="4081">4081 บ่อแร่</option>
                  <option value="4091">4091 สามง่าม</option>
                  <option value="4101">4101 ทางพระ</option>
                  <option value="4111">4111 อินทประมูล</option>
                  <option value="4121">4121 องครักษ์</option>
                  <option value="4131">4131 โคกพุทรา</option>
                  <option value="4141">4141 บางเจ้าฉ่า</option>
                  <option value="4151">4151 คำหยาด</option>
                  <option value="4117">4117 พิการ</option>
                </select>
              </div>
            <div class="min-w-full">
                {{-- <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">รายงาน</label> --}}
                <select id="studreport" name="studreport" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option selected>เลือกรายงาน</option>
                  <option value="all">รายงานนักศึกษาทั้งหมด</option>
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full p-2 m-2 min-w-full bg-indigo-500 text-white">ดูรายงาน</button> 
        </form>
    </x-slot>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-xs font-light">
                <thead class="border-b font-medium dark:border-neutral-500 bg-white">                  
                  <tr>
                    <th scope="col" class="p-2 text-center">ลำดับ</th>
                    <th scope="col" class="p-2">รหัส</th>
                    <th scope="col" class="p-2">ระดับ</th>
                    <th scope="col" class="p-2">ชื่อ</th>
                    <th scope="col" class="p-2">นามสกุล</th>
                    <th scope="col" class="p-2">คาดว่าจะจบ</th>
                    <th scope="col" class="p-2 text-center">กพช.</th>
                    <th scope="col" class="p-2 text-center">N-NET/E-Exam</th>
                  </tr>
                </thead>
                <tbody class="text-xs">
                @foreach($data as $d)
                  <tr class="border-b dark:border-neutral-500 bg-white shadow-md">
                    <td class="p-2 text-center">{{$loop->iteration}}</td>
                    <td class="p-2">{{$d['id']}}</td>
                    <td class="p-2">{{$d['lavel']}}</td>
                    <td class="p-2 w-15">{{$d['name']}}</td>
                    <td class="p-2">{{$d['surname']}}</td>
                    <td class="p-2">{{$d['expfin']}}</td>
                    <td class="p-2 text-center">{{$d['activity']}}</td>
                    <td class="p-2 text-center">{{$d['nt_sem']}}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</x-teachers-layout>