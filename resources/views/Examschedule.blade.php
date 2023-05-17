<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-sm text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ตารางสอบ') }}<label class="px-1 py-1" >สนามสอบ โรงเรียนโพธิ์ทอง"จินดามณี"</label>
        </h6>      
    </x-slot>
    <div class="flex flex-col ">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-xs font-light">
                <thead class="border-b font-medium dark:border-neutral-500">                  
                  <tr>
                    <th scope="col" class="p-2">ลำดับ</th>
                    <th scope="col" class="p-2">รหัสวิชา</th>
                    <th scope="col" class="p-2">รายวิชา</th>
                    <th scope="col" class="p-2">วันที่</th>
                    <th scope="col" class="p-2">เวลา</th>
                    <th scope="col" class="p-2">ห้อง</th>
                  </tr>
                </thead>
                <tbody class="text-xs">
                @foreach($schedule as $s)
                  <tr class="border-b dark:border-neutral-500">
                    <td class="p-2">{{$loop->iteration}}</td>
                    <td class="p-2">{{$s['sub_code']}}</td>
                    <td class="p-2 max-w-20">{{$s['sub_name']}}</td>
                    <td class="p-2">{{$s['exam_day']}}</td>
                    <td class="p-2">{{$s['exam_start']}}-{{$s['exam_end']}}</td>
                    <td class="p-2">{{$s['exam_room']}}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</x-app-layout>
