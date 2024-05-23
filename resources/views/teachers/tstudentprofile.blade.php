<x-teachers-layout>
    <div class="p-4 sm:ml-64">
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            {{-- <div class="text-2xl font-bold w-full text-left">ค้นหาข้อมูลผู้เรียน</div> --}}
            <div class="isolate px-4 py-4 sm:py-4 lg:px-4">
                <form action="{{ route('tstudentprofile') }}" method="POST" class="mx-auto mt-4 max-w-4xl sm:mt-6">   
                    @csrf
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="search" name="student_id" autofocus maxlength="25" id="student_id" value="" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="รหัสนักศึกษา" required>
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2">ค้นหา</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($student_data)
    {{-- Start Table --}}
    {{-- **************** ข้อมูลทั่วไป ********************* --}}
    <div class="p-4 sm:ml-64 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <div class="flex justify-center">
            <img class="rounded w-36 h-36 p-1 rounded-full ring-2 ring-gray-300" src="{{asset('storage/avatar/unkhonw.png');}}" alt="Extra large avatar">
        </div>
        <form action="#" method="POST" class="mx-auto mt-4 max-w-4xl sm:mt-4">
            @foreach($student_data as $s)
                <div class="grid grid-cols-2 gap-x-8 gap-y-6 sm:grid-cols-4">
                    <div class="">
                        <label for="company" class="block text-sm font-semibold leading-6 text-gray-900">รหัสนักศึกษา</label>
                        <div class="mt-2.5">
                        <input value="{{$s->ID}}" type="text" name="company" id="company" autocomplete="organization" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold leading-6 text-gray-900">รหัสบัตรประชาชน</label>
                        <div class="mt-2.5">
                        <input value="{{$s->CARDID}}" type="email" name="email" id="email" autocomplete="email" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">รหัสกลุ่ม</label>
                        <div class="mt-2.5">
                        <input value="{{$s->GRP_CODE}}" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">สถานะ</label>
                        <div class="mt-2.5">
                        <input  
                            @if($s->FIN_CAUSE==1) value="จบหลักสูตร" @endif 
                            @if($s->FIN_CAUSE==2) value="ลาออก" @endif 
                            @if($s->FIN_CAUSE==3) value="หมดสภาพ" @endif 
                            @if($s->FIN_CAUSE==4) value="พ้นสภาพ"  @endif 
                            @if($s->FIN_CAUSE==5) value="ศึกษาต่อที่อื่น"  @endif 
                            @if($s->FIN_CAUSE==6) value="ศึกษาเพิ่งหลังจบ"  @endif 
                            @if($s->FIN_CAUSE==7) value="จบตกหล่น"  @endif 
                            @if($s->FIN_CAUSE==8) value="อื่นๆ"  @endif 
                            @if($s->FIN_CAUSE==9) value="จบอยู่ระหว่างตรวจสอบวุฒิ"  @endif 
                            @if($s->FIN_CAUSE=='' || $s->FIN_CAUSE==null || $s->FIN_CAUSE==0) value="กำลังศึกษา" @endif    
                        type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="first-name" class="block text-sm font-semibold leading-6 text-gray-900">คำนำหน้า</label>
                        <div class="mt-2.5">
                        <input value="{{$s->PRENAME}}" type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="first-name" class="block text-sm font-semibold leading-6 text-gray-900">ชื่อ</label>
                        <div class="mt-2.5">
                        <input value="{{$s->NAME}}" type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">นามสกุล</label>
                        <div class="mt-2.5">
                        <input value="{{$s->SURNAME}}" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-semibold leading-6 text-gray-900">อายุ</label>
                        <div class="mt-2.5">
                        <input value="{{$s->AGE}}" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mx-auto mt-4 max-w-4xl sm:mt-4">
            <div class=" bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-bold leading-none text-gray-900 dark:text-white">รายวิชาผ่านแล้ว</h5>
                    {{-- <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                        View all
                    </a> --}}
                </div>
                <div class="flow-root">
                    <ul role="list" class="space-y-4 text-left text-gray-500 dark:text-gray-400 max-h-100">
                        @foreach($grade_data as $g)
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                            <span class="text-xs">{{$g->SEMESTRY}} {{$g->SUB_CODE}} {{$g->SUB_NAME}} <strong>เกรด : {{$g->GRADE}}</strong></span>
                        </li>
                        @endforeach
                        <li class="text-lg text-gray-100 text-center bg-green-400 rounded-lg">
                            รวม {{$sum_grade}} นก.
                        </li>
                    </ul>
                </div>
            </div>   

            <div class=" bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-bold leading-none text-gray-900 dark:text-white">กิจกรรมพัฒนาคุณภาพชีวิต</h5>
                    {{-- <a href="#" class="text-sm font-medium text-blue-600 hover:underline dark:text-blue-500">
                        View all
                    </a> --}}
                </div>
                <div class="flow-root">
                    <ul role="list" class="space-y-4 text-left text-gray-500 dark:text-gray-400">
                        @foreach($activity_data as $a)
                        <li class="flex items-center space-x-3 rtl:space-x-reverse">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-green-500 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                            <span class="text-xs">{{$a->SEMESTRY}} {{$a->ACTIVITY}} <strong>{{$a->HOUR}} ชม.</strong></span>
                        </li>
                        @endforeach
                        <li class="text-lg text-gray-100 text-center bg-yellow-400 rounded-lg">
                            รวม {{$sum_act}} ชม.
                        </li>
                    </ul>
                </div>
            </div>
        </div>  
    @endif
    {{-- End Table --}}
</x-teachers-layout>