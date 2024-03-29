<x-teachers-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight m-2">
            {{ __('ข้อมูลผู้เรียน') }}
        </h6>
    </x-slot>
    <div class="mx-auto mt-4 max-w-4xl sm:mt-6">
        <div class="flex items-center w-auto h-36 overflow-hiddenrounded-full">
            <svg class=" w-36 h-36 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            <h6 class="text-2xl">ค้นหาประวัติผู้เรียน</h6>
        </div>
    </div>
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

    {{-- **************** ข้อมูลทั่วไป ********************* --}}
    <div class="isolate px-4 py-4 sm:py-4 lg:px-4">   
        {{-- <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]" aria-hidden="true">
            <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div> --}}
        <div class="mx-auto max-w-8xl text-center">
            {{-- <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">ข้อมูลทั่วไป</h2> --}}
            {{-- <p class="mt-2 text-lg leading-8 text-gray-600">Aute magna irure deserunt veniam aliqua magna enim voluptate.</p> --}}
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
                        <label>
                            @if($s->FIN_CAUSE==1) <span class="text-green-400">จบหลักสูตร</span> @endif 
                            @if($s->FIN_CAUSE==2) ลาออก @endif 
                            @if($s->FIN_CAUSE==3) <span class="text-red-400">หมดสภาพ</span> @endif 
                            @if($s->FIN_CAUSE==4) พ้นสภาพ @endif 
                            @if($s->FIN_CAUSE==5) ศึกษาต่อที่อื่น @endif 
                            @if($s->FIN_CAUSE==6) ศึกษาเพิ่งหลังจบ @endif 
                            @if($s->FIN_CAUSE==7) จบตกหล่น @endif 
                            @if($s->FIN_CAUSE==8) อื่นๆ @endif 
                            @if($s->FIN_CAUSE==9) จบอยู่ระหว่างตรวจสอบวุฒิ @endif 
                            @if($s->FIN_CAUSE=='') ยังไม่ระบุ @endif
                        </label>
                        {{-- <input value="{{$s->FIN_CAUSE}}" type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"> --}}
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
    </div>  
    {{-- **************** ประวัติผลการเรียน ********************* --}}
    <div class="isolate px-4 py-4 sm:py-2 lg:px-4">
        {{-- <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]" aria-hidden="true">
            <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div> --}}
        <div class="mx-auto max-w-4xl text-center">
            {{-- <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">ประวัติผลการเรียน</h2> --}}
            {{-- <p class="mt-2 text-lg leading-8 text-gray-600">Aute magna irure deserunt veniam aliqua magna enim voluptate.</p> --}}

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">รายวิชาผ่านแล้ว</h5>
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
                            <li class="text-lg text-gray-50 text-center bg-green-500">
                                รวม {{$sum_grade}} นก.
                            </li>
                        </ul>
                </div>
                </div>   

                <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">กิจกรรมพัฒนาคุณภาพชีวิต</h5>
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
                            <li class="text-lg text-gray-50 text-center bg-yellow-500">
                                รวม {{$sum_act}} ชม.
                            </li>
                        </ul>
                </div>
                </div> 
            </div>
        </div>
    </div>  
</x-teachers-layout>