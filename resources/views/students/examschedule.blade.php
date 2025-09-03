<x-app-layout>
  <x-slot name="header">
    <h6 class="font-semibold text-gray-800  leading-tight">
        {{ __('ตารางสอบปลายภาค') }}
    </h6>      
</x-slot>
{{-- ปลายภาค --}}
<div class="flex justify-center mt-4 p-4 sm:ml-64">
  <div class="max-w-screen-lg w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="p-4 text-xl text-gray-100 dark:text-white bg-violet-800">
      <div class="text-yellow-500 flex flex-col-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        </svg>               
        <div class="pl-2">ตารางสอบ ปลายภาค {{$semestry}} </div>
      </div>
      @foreach($student as $s)
        <div class=" text-left text-lg p-4" > 
          <div>ชื่อ-สกุล : <span class="text-gray-100"> {{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}} </span>  </div>
          <div>รหัสนักศึกษา : <span class="text-gray-100"> {{$s->ID}} </span> </div>
          <div>สนามสอบ : <span class="text-gray-100"> ตามประกาศสถานศึกษา </span>  </div>
        </div>
      @endforeach
    </div>
    <table class=" w-full text-sm md:text-lg text-left rtl:text-right text-gray-500 dark:text-gray-400">
      @if($schedule)
      <thead class="text-sm text-gray-700 uppercase bg-violet-300 dark:bg-gray-700 dark:text-gray-400">                  
        <tr>
          <th scope="col" class="py-3 text-center">ลำดับ</th>
          <th scope="col" class="px-2 py-3">รหัสวิชา</th>
          <th scope="col" class="px-2 py-3">รายวิชา</th>
          <th scope="col" class="px-2 py-3">วัน</th>
          <th scope="col" class="px-2 py-3">เวลา</th>
          <th scope="col" class="px-2 py-3 text-center">ห้อง</th>
        </tr>
      </thead>
      <tbody class="text-xs md:text-sm">
          @foreach($schedule as $s)
            <tr class="bg-violet-50 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 hover:bg-violet-200 active:bg-violet-200">
              <td class="text-center">{{$loop->iteration}}</td>
              <td class="px-2 py-3">{{$s['sub_code']}}</td>
              <td class="px-2 py-3 text-ellipsis overflow-hidden">{{$s['sub_name']}}</td>
              <td class="px-2 py-3">@if($s['exam_day']!=0)  {{$s['exam_day']}} @endif</td>
              <td class="px-2 py-3">@if($s['exam_start']!=0) {{$s['exam_start']}}-{{$s['exam_end']}} น. @endif</td>
              <td class="px-2 py-3 text-center">{{$s['exam_room']}}</td>
            </tr>
          @endforeach
      </tbody>
      @endif
      @if(!$schedule) 
      <tbody class="text-xs md:text-sm">
          <tr>
            <h4 class="font-semibold text-lg text-gray-400 leading-tight text-center p-5">
              {{ __('"ยังไม่มีตารางสอบ"') }} {{$nnet}}
            </h4>
          <tr>
      </tbody>
      @endif
    </table>
  </div>
</div>

  {{-- N-NET --}}
  @if($nnet === 'N-NET') 
  <div class="flex justify-center mt-4 p-4 sm:ml-64">
    <div class="max-w-screen-lg w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
      <div class="p-4 text-xl text-gray-100 dark:text-white bg-indigo-800">
        <p>ตารางสอบ N-NET {{$semestry}}</p>
        @foreach($student as $s)
          <div class=" text-left text-lg p-4" > 
            <div>ชื่อ-สกุล : <span class="text-gray-100"> {{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}} </span>  </div>
            <div>รหัสนักศึกษา : <span class="text-gray-100"> {{$s->ID}} </span> </div>
            <div>สนามสอบ : <span class="text-gray-100"> - </span>  </div>
          </div>
        @endforeach
      </div>
      <div class="font-semibold text-lg text-green-400 leading-tight text-center p-5">
        {{ __('"คุณมีสิทธิสอบ"') }} รายชื่อผู้มีสิทธิสอบในตาราง
      </div>
      <div class=" text-center text-sm" >  สนามสอบ</div>
      <div class=" text-center text-sm" >  ประถม และ ม.ต้น สอบระบบปกติ</div>
      <div class=" text-center text-sm" >  ม.ปลาย สอบระบบคอมพิวเตอร์</div>
        <table class="p-15 text-left text-xs md:text-sm font-light max-w-screen-lg w-full">
            <thead class="border-b font-medium dark:border-neutral-500 bg-white">                  
              <tr>
                <th scope="col" class="p-2">รหัส</th>
                <th scope="col" class="p-2 text-ellipsis overflow-hidden">คำนำหน้า</th>
                <th scope="col" class="p-2">ชื่อ</th>
                <th scope="col" class="p-2">สกุล</th>
                <th scope="col" class="p-2">วัน</th>
                <th scope="col" class="p-2">เวลา</th>
                <th scope="col" class="p-2 text-center">ห้อง</th>
              </tr>
            </thead>
            <tbody class="text-xs md:text-sm">
              @foreach($student as $s)
              <tr class="border-b dark:border-neutral-500 bg-white hover:bg-violet-200 active:bg-violet-200">
                <td class="p-2">{{$s->ID}}</td>
                <td class="p-2">{{$s->PRENAME}}</td>
                <td class="p-2">{{$s->NAME}}</td>
                <td class="p-2">{{$s->SURNAME}}</td>  
                <td class="p-2">ตามประกาศ สทศ.</td>
                <td class="p-2">-</td>
                <td class="p-2 text-center">-</td>
              </tr>
              @endforeach
            </tbody>
        </table>
      @if($nnet === 'ผ่านแล้ว') 
        <h4 class="font-semibold text-lg text-gray-400 leading-tight text-center p-5">
          {{ __('คุณสอบผ่านแล้ว') }} {{$nnet}}
        </h4>
      @endif
      @if($nnet === 'E-Exam') 
        <h4 class="font-semibold text-lg text-gray-400 leading-tight text-center p-5">
          {{ __('คุณต้องสอบ E-Exam') }} {{$nnet}}
        </h4>
      @endif
      @if($nnet === null) 
        <h4 class="font-semibold text-lg text-gray-400 leading-tight text-center p-5">
          {{ __('"คุณยังไม่มีสิทธิสอบ"') }} {{$nnet}}
        </h4>
      @endif
    </div>
  </div>
  @endif
</x-app-layout>
