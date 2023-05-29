<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-sm text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('การลงทะเบียน') }}
        </h6>      
    </x-slot>
{{-- Content --}}
<div class="grid grid-cols-1 gap-2 justify-items-center max-w-screen-lg md:grid md:grid-cols-2 md:py-6 md:px-6 pt-0">
  <div class="overflow-hidden shadow-md">
    <table class="min-w-full text-left text-xs font-light">
      <div scope="col" class="p-4 text-center text-lg shdow-md bg-yellow-200 w-full">รายวิชาที่ยังไม่ผ่าน</div>
      <thead class="border-b font-medium dark:border-neutral-500 bg-white">               
        <tr>
          <th scope="col" class="p-2 text-center">ลำดับ</th>
          <th scope="col" class="p-2">รหัสวิชา</th>
          <th scope="col" class="p-2">รายวิชา</th>
          <th scope="col" class="p-2">ประเภท</th>
          <th scope="col" class="p-2 text-center">หน่วยกิจ</th>
        </tr>
      </thead>
      <tbody class="text-xs">
      @foreach($notlearned as $l)
        <tr class="border-b dark:border-neutral-500 bg-white shadow-md hover:bg-violet-200 active:bg-violet-200">
          <td class="p-2 text-center">{{$loop->iteration}}</td>
          <td class="p-2">{{$l['sub_code']}}</td>
          <td class="p-2 text-ellipsis overflow-hidden">{{$l['sub_name']}}</td>
          <td class="p-2 text-left">@if ($l['sub_type']==1) บังคับ @else เลือก @endif </td>
          <td class="p-2 text-center">{{$l['sub_credit']}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <div class="overflow-x-auto shadow-md">
    <table class="min-w-full text-left text-xs font-light">
      <div scope="col" class="p-4 text-center text-lg shdow-md bg-green-200 w-full">รายวิชาผ่านแล้ว</div>
      <thead class="border-b font-medium dark:border-neutral-500 bg-white">             
        <tr>
          <th scope="col" class="p-2 text-center">ลำดับ</th>
          <th scope="col" class="p-2">รหัสวิชา</th>
          <th scope="col" class="p-2">รายวิชา</th>
          <th scope="col" class="p-2">ประเภท</th>
          <th scope="col" class="p-2 text-center">หน่วยกิจ</th>
          <th scope="col" class="p-2 text-center">ผลการเรียน</th>
        </tr>
      </thead>
      <tbody class="text-xs">
      @foreach($learned as $l)
        <tr class="border-b dark:border-neutral-500 bg-white shadow-md hover:bg-violet-200 active:bg-violet-200">
          <td class="p-2 text-center">{{$loop->iteration}}</td>
          <td class="p-2">{{$l['sub_code']}}</td>
          <td class="p-2 text-ellipsis overflow-hidden">{{$l['sub_name']}}</td>
          <td class="p-2 text-left">@if ($l['sub_type']==1) บังคับ @else เลือก @endif </td>
          <td class="p-2 text-center">{{$l['sub_credit']}}</td>
          <td class="p-2 text-center text-green-400">{{$l['grade']}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
</x-app-layout>
