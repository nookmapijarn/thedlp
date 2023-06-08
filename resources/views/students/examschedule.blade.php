<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ตารางสอบ - ภาคเรียน 1/2566') }}
        </h6>      
        <div class="" >  สนามสอบ โรงเรียนโพธิ์ทอง"จินดามณี"</div>
    </x-slot>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <table class="min-w-full text-left text-xs md:text-sm font-light">
                <thead class="border-b font-medium dark:border-neutral-500 bg-white">                  
                  <tr>
                    <th scope="col" class="p-2 text-center">ลำดับ</th>
                    <th scope="col" class="p-2">รหัสวิชา</th>
                    <th scope="col" class="p-2">รายวิชา</th>
                    <th scope="col" class="p-2">วัน</th>
                    <th scope="col" class="p-2">เวลา</th>
                    <th scope="col" class="p-2 text-center">ห้อง</th>
                  </tr>
                </thead>
                <tbody class="text-xs md:text-sm">
                @foreach($schedule as $s)
                  <tr class="border-b dark:border-neutral-500 bg-white shadow-md hover:bg-violet-200 active:bg-violet-200">
                    <td class="p-2 text-center">{{$loop->iteration}}</td>
                    <td class="p-2">{{$s['sub_code']}}</td>
                    <td class="p-2 text-ellipsis overflow-hidden">{{$s['sub_name']}}</td>
                    <td class="p-2 w-15">@if($s['exam_day']!=0)  {{$s['exam_day']}} @endif</td>
                    <td class="p-2">@if($s['exam_start']!=0) {{$s['exam_start']}}-{{$s['exam_end']}} น. @endif</td>
                    <td class="p-2 text-center">{{$s['exam_room']}}</td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</x-app-layout>
