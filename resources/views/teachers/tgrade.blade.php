<x-teachers-layout>
    <x-slot name="header">
        <div class="text-2xl font-bold w-full text-center">รายงานผลการเรียน</div>
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight m-2">
            {{ __('ภาคเรียนที่') }} {{$semestry}}
        </h6>
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
              @if($data==null) <section class="p-10 text-center text-lg">**ไม่พบข้อมูล กรุณาเลือกรายการใหม่**</section> @endif
            </div>
          </div>
        </div>
      </div>
</x-teachers-layout>