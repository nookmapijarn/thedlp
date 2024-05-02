<x-teachers-layout>
    <x-slot name="header" class="absolute top">
        <div class="text-2xl font-bold w-full text-center">ผลการพัฒนาคุณภาพผู้เรียน (กศน.4)</div>
        <br>
        <form method="GET" action="{{ route('tscore') }}" class="text-sm">
        <div class="grid grid-cols-1 gap-2 md:grid md:grid-cols-5 justify-items-center">
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
            <div class="min-w-full">
              <label>ระดับชั้น</label>
                <select required onchange="if(this.value != '') { this.form.submit(); }" id="lavel" name="lavel" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                  <option value="">เลือก</option>
                  <option @if($lavel == 1) selected @endif value="1">ประถมศึกษา</option>
                  <option @if($lavel == 2) selected @endif value="2">มัธยมต้น</option>
                  <option @if($lavel == 3) selected @endif value="3">มัธยมปลาย</option>
                </select>
            </div>
            <div class="min-w-full" >
              <label>รายวิชา</label>
                <select required id="subject" name="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  @foreach($all_subject as $sub)
                      <option value="{{ $sub->SUB_CODE }}"
                        @if($subject === $sub->SUB_CODE) selected @endif
                        >
                          {{ $sub->SUB_CODE }} {{ $sub->SUB_NAME }}
                      </option>
                  @endforeach    
                </select>
            </div>
            <div class="min-w-full" >
              <label>สอบปกติ/ซ่อม</label>
                <select required  id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-xl rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 ">
                  <option value="">เลือก</option>
                  <option @if($type == 0) selected @endif value="0">สอบปลายภาค</option>
                  <option @if($type == 7) selected @endif value="7">สอบซ่อม</option>
                  {{-- <option @if($type == 1) selected @endif value="1">เทียบโอน</option> --}}
                </select>
            </div>
          </div>
          <button type="submit" class="rounded-full  mt-4 p-2 min-w-full bg-indigo-500 hover:bg-indigo-200 text-white">ดูรายงาน</button> 
        </form>
    </x-slot>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 overflow-scroll">
    <div class="flex flex-col max-w-srceen-lg ">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full  py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
              <div
              @if($lavel == 1) 
                class="text-center text-xl p-4 bg-pink-100 border border-slate-300 mb-2" 
              @elseif($lavel == 2) 
                class="text-center text-xl p-4 bg-green-100 border border-slate-300 mb-2"
              @elseif($lavel == 3) 
                class="text-center text-xl p-4 bg-yellow-100 border border-slate-300 mb-2" 
              @else 
                class="text-center text-xl p-4 bg-blue-100 border border-slate-300 mb-2" 
              @endif
              >
                <p>
                  <strong> ระดับ : </strong> @if($lavel==1) ประถมศึกษา @elseif($lavel==2) มัธยมต้น @elseif($lavel==3) มัธยมปลาย @endif
                  <strong> รายวิชา : </strong> {{$subject}}
                  @if($type == 0) (สอบปลายภาค) @elseif ($type == 7) (สอบซ่อม) @endif
                </p>
                <p class="p-2">
                  <strong> ทั้งหมด : </strong>{{$all_grade}} คน  
                  <strong> ผ่าน : </strong>{{$all_grade-($grade_0+$grade_not)}} คน  
                  <strong> ไม่ผ่าน : </strong>{{$grade_0+$grade_not}} คน  
                  {{-- ขาดสอบ : {{$grade_not}} --}}
                </p>
              </div>
              @if($data!=null)
              <table class="max-w-full table-fixed bg-blue-100">
                <thead class="h-32 text-xs text-gray-900 uppercase dark:bg-gray-700 dark:text-gray-400">  
                    <tr class="">
                      <th scope="col" class="border border-slate-300 ... w-12 text-center">ลำดับ</th>
                      <th scope="col" class="border border-slate-300 ... w-32">รหัส</th>
                      <th scope="col" class="border border-slate-300 ... w-56">ชื่อ-สกุล</th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">บันทึกการเรียนรู้</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">บันทึกการฝึกทักษะ</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รายงาน</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">แบบฝึกหัด</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">แฟ้มสะสมงาน</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">ผลงานชิ้นงาน</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">โครงงาน</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">ทดสอบย่อย</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">อื่นๆ</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รวมระหว่างภาค</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">คะแนนปลายภาค</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">รวมคะแนนทั้งสิ้น</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-12">เกรด</div></th>
                      <th scope="col" class="border border-slate-300 ... w-12"><div class="textAlignVer h-0 w-36 md:w-48">หมายเหตุ</div></th>
                    </tr>
                  </thead>
                  <tbody class="max-h-48">
                    @foreach($data as $s)
                    <tr  class="bg-white hover:bg-blue-200 border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="border border-slate-300 ... text-center">{{$loop->iteration}}</td>
                        <td class="border border-slate-300 ... pr-2">{{$s->ID}}</td>
                        <td class="border border-slate-300 ... truncate">{{$s->PRENAME}}{{$s->NAME}} {{$s->SURNAME}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM1}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM2}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM3}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM4}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM5}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM6}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM7}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM8}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM9}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->MIDTERM}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->FINAL}}</td>
                        <td class="border border-slate-300 ... text-center">{{$s->TOTAL}}</td>
                        <td class="border border-slate-300 ... text-center">
                          <span
                          @if($s->GRADE == 0)class="text-red-300"@endif
                          @if($s->GRADE > 0 && !is_numeric($s->GRADE))class="text-red-600"@endif
                          @if($s->GRADE == '' || $s->GRADE == null)class="text-red-600"@endif  
                          >
                          {{$s->GRADE}}
                          </span>
                        </td>
                        <td class="border border-slate-300 ... text-center">
                          @if($s->TYP_CODE == 1) ทอ* @endif
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
              @if($data==null) <section class="p-10 text-center text-xl text-red-500">-ไม่พบข้อมูล กรุณาเลือกรายการใหม่-</section> @endif
            {{-- </div>
          </div>
        </div>
      </div> --}}
    </div>
</x-teachers-layout>