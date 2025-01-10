<x-app-layout>
    <x-slot name="header">
        <h6 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight pl-4">
            {{ __('ประวัติการเรียน') }}
        </h6>
    </x-slot>

    {{-- Avatar --}}
    <section class="bg-white dark:bg-gray-900"> 
        <div class="flex items-center space-x-4 gap- items-center py-1 px-4 mx-auto max-w-screen-md">
            <!-- Horizontal Student Card -->
            <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg flex overflow-hidden relative">
                <!-- Background Logo -->
                <div class="absolute inset-0">
                    <img src="https://phothongdlec.ac.th/storage/logo.png" alt="School Logo" 
                    class="opacity-10 w-full h-full object-contain">
                </div>
            
                <div class="w-full flex flex-col relative z-10">
                    <!-- Header Section -->
                    <div class="bg-blue-600 text-white text-center py-2">
                        <h1 class="text-lg font-bold">บัตรประจำตัวนักศึกษา</h1>
                    </div>
                
                    <!-- Main Content Section -->
                    <div class="flex flex-col md:flex-row">
                        <!-- Profile Image Section -->
                        <div class="w-full md:w-1/3 p-4 flex justify-center items-center relative">
                            <div class="w-48 h-64 bg-gray-200 rounded-md overflow-hidden transform shadow-md">
                                <img class="w-full h-full object-cover md:aspect-auto aspect-[7/8]"
                                     src="{{ auth()->user()->avatar ? auth()->user()->avatar . '?v=' . time() : 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png' }}" 
                                     alt="Preview Image" 
                                     onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                                <!-- Edit Icon -->
                                <a href="/profile" class="absolute top-2 right-2 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-700">
                                    <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="2" d="M7 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h1m4-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm7.441 1.559a1.907 1.907 0 0 1 0 2.698l-6.069 6.069L10 19l.674-3.372 6.07-6.07a1.907 1.907 0 0 1 2.697 0Z"/>
                                    </svg>                                  
                                </a>
                            </div>
                        </div>
                
                        <!-- Information Section -->
                        <div class="w-full md:w-2/3 p-6 flex flex-col justify-between">
                            <div>
                                @foreach($student as $std)
                                <h2 class="text-2xl font-bold text-gray-800">{{$std->PRENAME}}{{$std->NAME}} {{$std->SURNAME}}</h2>
                                <p class="text-gray-600 text-sm mt-1">นักศึกษาศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง</p>
                                <p class="text-gray-500 text-sm">สังกัดสำนักงานส่งเสริมการเรียนรู้ประจำจังหวัดอ่างทอง</p>
                                <div class="mt-4">
                                    <p class="text-gray-700 text-sm"><span class="font-bold">รหัสนักศึกษา:</span> {{$std->ID}}</p>
                                    <p class="text-gray-700 text-sm"><span class="font-bold">ระดับชั้น:</span> 
                                        @if($lavel==1) ประถมศึกษา @endif 
                                        @if($lavel==2) มัธยมต้น @endif 
                                        @if($lavel==3) มัธยมปลาย @endif 
                                    </p>
                                    <p class="text-gray-700 text-sm"><span class="font-bold">กลุ่มตำบล:</span> {{$std->GRP_CODE}}</p>
                                    <p class="text-gray-700 text-sm"><span class="font-bold">สถานะ:</span> 
                                        @if($std->FIN_CAUSE==1) "จบหลักสูตร" @endif 
                                        @if($std->FIN_CAUSE==2) "ลาออก" @endif 
                                        @if($std->FIN_CAUSE==3) "หมดสภาพ" @endif 
                                        @if($std->FIN_CAUSE==4) "พ้นสภาพ"  @endif 
                                        @if($std->FIN_CAUSE==5) "ศึกษาต่อที่อื่น"  @endif 
                                        @if($std->FIN_CAUSE==6) "ศึกษาเพิ่งหลังจบ"  @endif 
                                        @if($std->FIN_CAUSE==7) "จบตกหล่น"  @endif 
                                        @if($std->FIN_CAUSE==8) "อื่นๆ"  @endif 
                                        @if($std->FIN_CAUSE==9) "จบอยู่ระหว่างตรวจสอบวุฒิ"  @endif 
                                        @if($std->FIN_CAUSE=='' || $std->FIN_CAUSE==null || $std->FIN_CAUSE==0) เป็นนักศึกษา @endif   
                                    </p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                
                    <!-- Print Button -->
                    <div class="px-6 pb-4 text-right">
                        <a href="/profile" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-2">
                            แก้ไขรูปภาพ
                        </a>
                        <button onclick="printCard('{{ auth()->user()->avatar }}')" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            พิมพ์บัตร
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    {{-- Progess --}}
    <section class="bg-white grid justify-items-center">
        <div class="max-w-screen-md p-2 w-full pt-10 pb-10">
            <div class="grid grid-cols-1 gap-20 md:grid-cols-2 md:gap-10">
              <div class="flex items-center flex-wrap px-10 bg-white shadow-xl rounded-2xl h-20"
                 x-data="{ circumference: 50 * 2 * Math.PI, percent: {{$credit_percent}} }"
                 >
                    <div class="flex items-center justify-center -m-6 overflow-hidden bg-white rounded-full">
                      <svg class="w-32 h-32 transform translate-x-1 translate-y-1" x-cloak aria-hidden="true">
                        <circle
                          class="text-gray-300"
                          stroke-width="10"
                          stroke="currentColor"
                          fill="transparent"
                          r="50"
                          cx="60"
                          cy="60"
                          />
                        <circle
                          class="text-blue-600"
                          stroke-width="10"
                          :stroke-dasharray="circumference"
                          :stroke-dashoffset="circumference - percent / 100 * circumference"
                          stroke-linecap="round"
                          stroke="currentColor"
                          fill="transparent"
                          r="50"
                          cx="60"
                          cy="60"
                         />
                      </svg>
                      <span class="absolute text-2xl text-blue-700" x-text="`${percent}%`"></span>
                    </div>
                    <p class="ml-10 font-medium text-gray-600 sm:text-sm">หน่วยกิต</p>
          
                    <span class="ml-auto text-sm font-medium text-blue-600"> {{$credit}}/{{$allcredit}}</span>
                </div>
              
              
              <div class="flex items-center flex-wrap px-10 bg-white shadow-xl rounded-2xl h-20"
                 x-data="{ circumference: 50 * 2 * Math.PI, percent: {{($act_sum*100)/200}} }"
                 >
                    <div class="flex items-center justify-center -m-6 overflow-hidden bg-white rounded-full">
                      <svg class="w-32 h-32 transform translate-x-1 translate-y-1" x-cloak aria-hidden="true">
                        <circle
                          class="text-gray-300"
                          stroke-width="10"
                          stroke="currentColor"
                          fill="transparent"
                          r="50"
                          cx="60"
                          cy="60"
                          />
                        <circle
                          class="text-yellow-400"
                          stroke-width="10"
                          :stroke-dasharray="circumference"
                          :stroke-dashoffset="circumference - percent / 100 * circumference"
                          stroke-linecap="round"
                          stroke="currentColor"
                          fill="transparent"
                          r="50"
                          cx="60"
                          cy="60"
                         />
                      </svg>
                      <span class="absolute text-2xl text-yellow-400" x-text="`${percent}%`"></span>
                    </div>
                    <p class="ml-10 font-medium text-gray-600 sm:text-sm">ชั่วโมง กพช.</p>
          
                    <span class="ml-auto text-sm font-medium text-yellow-400 ">{{$act_sum}}/200</span>
                </div>
            </div>
          </div>
    </section>

    {{-- คุณธรรม --}}
    <section class="bg-white grid justify-items-center">
        <h6 class="text-sm text-center font-medium leading-6 text-gray-900 font-kanit">
            ประเมินคุณธรรม
            @if($moral!=null && $moral == 0)
                ปรับปรุง
            @endif
            @if($moral!=null && $moral == 1)
                พอใช้
            @endif
            @if($moral!=null && $moral == 2)
                ดี
            @endif
            @if($moral!=null && $moral == 3)
                ดีมาก
            @endif
        </h6>
        <div class="flex items-center max-w-screen-md">
            <div class="flex items-center">
                @if($moral!=null && $moral >= 0)
                <svg class="w-8 h-8 ms-3 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                @endif
                @if($moral!=null && $moral > 0)
                <svg class="w-8 h-8 ms-3 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                @endif
                @if($moral!=null && $moral > 1)
                <svg class="w-8 h-8 ms-3 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                @endif
                @if($moral!=null && $moral > 2)
                <svg class="w-8 h-8 ms-3 text-yellow-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                    <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                </svg>
                @endif
            </div>
        </div>
    </section>

    {{-- Process Semestry --}}
    <section class="bg-white grid justify-items-center">
        <div class="w-full p-10 max-w-screen-md">
            <h6 class="text-sm text-center font-medium leading-6 text-gray-900 font-kanit">
                ระยะเวลาเรียน (ไม่เกิน 10 ภาคเรียน)
            </h6>
            <div class="pt-5">
                <div class="bg-gray-200 relative w-[100%] ">
                    <div class="border-r-4 border-red-500 absolute top-0 left-0 h-full w-[100.5%] " >
                        <span class="bg-red-400 absolute -right-9 mt-6 rounded-sm py-1 px-2 text-xs font-semibold text-white">
                            <span class="bg-red-400 absolute top-[-2px] left-1/2 -z-15 h-2 w-2 -translate-x-1/2 rotate-45 rounded-sm">
                            </span>
                             หมดสภาพ
                        </span>
                    </div>
                    <div class="border-r-4 border-yellow-500 absolute top-0 left-0 h-full w-[100%] " >
                        <div class="absolute text-xs font-semibold right-4 mr-2 text-gray-200 text-ellipsis overflow-hidden ...">ล่าช้า 5-10 ภาคเรียน</div>
                        {{-- <span class="bg-yellow-400 absolute right-36 mt-6 rounded-sm py-1 px-2 text-xs font-semibold text-white">
                            <span class="bg-yellow-400 absolute top-[-2px] left-1/2 -z-15 h-2 w-2 -translate-x-1/2 rotate-45 rounded-sm">
                            </span>
                            จบล่าช้า
                        </span> --}}
                    </div>
                    <div class="border-r-4 border-green-500 absolute top-0 left-0 h-full w-[40%] g" >
                        <div class="absolute text-xs font-semibold right-4 mr-2 text-gray-200 text-ellipsis overflow-hidden ..."> 1-4 ภาคเรียน</div>
                        <span class="bg-green-400 absolute -right-7 mt-6 rounded-sm py-1 px-2 text-xs font-semibold text-white">
                            <span class="bg-green-400 absolute top-[-2px] left-1/2 -z-15 h-2 w-2 -translate-x-1/2 rotate-45 rounded-sm">
                            </span>
                            จบปกติ
                        </span>
                    </div>
                    <div x-data="{width: 0}" x-init="$nextTick(() => { width = {{$timelerning}} })" class="bg-gray-300 rounded-lg h-4 my-2" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" role="progressbar">
                        @if($timelerning!=0)
                        <div :style="`width: ${width}%; transition: width 1s;`" class="bg-indigo-500 h-4 relative ">
                            <span class="bg-indigo-500 absolute bottom-full mb-2 rounded-sm py-1 px-2 text-xs font-semibold text-white -right-8 md:-right-8">
                                <span class="bg-indigo-500 absolute bottom-[-2px] left-1/2 -z-15 h-2 w-2 -translate-x-1/2 rotate-45 rounded-sm">
                                </span>
                                ปัจจุบัน {{$timelerning/10}}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>         
    </section>

    {{-- Bar Redar --}}
    <section class="bg-white grid justify-items-center">
        <div class="h-full w-full p-10 bg-white max-w-screen-md  grid justify-items-center">
            <h6 class="text-sm font-medium leading-6 text-gray-900 font-kanit">
                ภาพรวมคะแนนเฉลี่ยทุกรายวิชา 5 กลุ่มสาระ
            </h6>
            <canvas id="myChart" height="100px"></canvas>
        </div> 
    </section>
    {{-- Progess bar --}}
    {{-- <section class="bg-white dark:bg-gray-900">
          <div class="h-full p-4 bg-white-100 mx-auto max-w-screen-md">
              <h6 class="text-sm font-medium leading-6 text-gray-900 font-kanit">
                สถิติการเรียน
              </h6>
              <div class="grid grid-cols-3 gap-4">
                <div class="px-4 py-5 lg:p-6 shadow-md rounded-md bg-opacity-50 bg-green-400">
                    <dl>
                        <dt class="text-sm font-medium leading-5 text-gray-500 truncate">
                        GPA
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                            {{$grade_avg}}
                        </dd>
                    </dl>
                </div>
                <div class="px-4 py-5 lg:p-6 shadow-md rounded-md bg-opacity-50 bg-blue-400">
                    <dl>
                        <dt class="text-sm font-medium leading-5 text-gray-500 truncate">
                        N-NET
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                            {{$nnet}}
                        </dd>
                    </dl>
                </div>
                <div class="px-4 py-5 lg:p-6 shadow-md rounded-md bg-opacity-50 bg-yellow-400">
                    <dl>
                        <dt class="text-sm font-medium leading-5 text-gray-500 truncate">
                        คุณธรรม
                        </dt>
                        <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                            {{$moral}}
                        </dd>
                    </dl>
                </div> --}}
                {{-- <div class="text-lg bg-gray-200 font-semibold text-gray-800 bg-opacity-50 rounded-lg border">GPA : {{$grade_avg}}</div> --}}
                {{-- <div class="text-lg bg-gray-200 font-semibold text-gray-800 bg-opacity-50 rounded-lg border">N-NET : {{$nnet}}</div>
                <div class="text-lg bg-gray-200 font-semibold text-gray-800 bg-opacity-50 rounded-lg border">คุณธรรม : {{$moral}}</div> --}}
              {{-- </div>
          </div>

          <div class="gap- items-center py-1 px-4 mx-auto max-w-screen-md md:grid md:grid-cols-2 md:py-12 md:px-6">
            <div class="mr-5">
                <div class="float-left mr-1 bg-green-400 rounded-full w-5 h-5"></div>
                <span class="text-sm align-top text-gray-600">หน่วยกิต {{$credit}}/{{$allcredit}}</span>
           </div>
            <div x-data="{width: 0}" x-init="$nextTick(() => { width = {{$credit_percent}} })" class="bg-gray-300 rounded-lg h-4 my-2" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" role="progressbar">
                @if($credit_percent!=0)
                <div :style="`width: ${width}%; transition: width 5s;`" class="bg-green-400 h-4 rounded-full relative">
                    <div class="absolute text-xs font-semibold right-0 mr-2"><span x-text="width"></span>%</div>
                </div>
                @endif
            </div>
            <div class="mr-5">
                <div class="float-left mr-1 bg-orange-400 rounded-full w-5 h-5"></div>
                <span class="text-sm align-top text-gray-600">กพช {{$act_sum}}/200</span>
            </div>
            <div x-data="{width: 0}" x-init="$nextTick(() => { width = {{$act_percentage}} })" class="bg-gray-300 rounded-lg h-4 my-2" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" role="progressbar">
                @if($act_percentage!=0)
                <div :style="`width: ${width}%; transition: width 5s;`" class="bg-orange-400 h-4 rounded-full relative">
                    <div class="absolute text-xs  font-semibold right-0 mr-2"><span x-text="width"></span>%</div>
                </div>
                @endif
            </div>
            <div class="mr-5">
              <div class="float-left mr-1 bg-yellow-400 rounded-full w-5 h-5"></div>
              <span class="text-sm align-top text-gray-600">ระยะเวลาเรียน {{$timelerning}} %</span>
          </div>
          <div x-data="{width: 0}" x-init="$nextTick(() => { width = {{$timelerning}} })" class="bg-gray-300 rounded-lg h-4 my-2" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" role="progressbar">
            @if($timelerning!=0)  
            <div :style="`width: ${width}%; transition: width 5s;`" class="bg-yellow-400 h-4 relative rounded-full">
                  <div class="absolute text-xs  font-semibold right-0 mr-2"><span x-text="width"></span>%</div>
              </div>
              @endif
          </div>
        </div>
    </section> --}}

    {{-- GRADE --}}
    <section class="bg-white dark:bg-gray-900">
        
        <div 
            class="gap- items-center py-1 px-4 mx-auto max-w-screen-md"
            x-data="{
            activeTab : 'tab1',
            isActive(tab){
                if(this.activeTab == tab){
                    return true;
                }else{
                    return false;
                }
            }
            }" >
            <!-- Tab Navigation-->
            <div class="flex my-2 text-sm font-semibold items-center text-gray-800">
                <div class="flex-grow border-t h-px mr-3"></div>
                ผลการเรียนรายภาค
                <div class="flex-grow border-t h-px ml-3"></div>
            </div>
            <div class="text-sm md:text-md font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                    <li class="mr-2">
                        <span class="inline-block p-4 rounded-t-lg border-b-2 border-transparent">
                            ภาคเรียนที่ :
                        </span>
                    </li>
                    @foreach($semestrylist1 as $semestry)
                    <li class="mr-2 cursor-pointer"
                        @click="activeTab = {{$semestry->SEMESTRY}}">
                        <a 
                            :class="isActive({{$semestry->SEMESTRY}}) ? 
                            'inline-block p-4 text-white bg-violet-500 active underline rounded-t-lg shadow-md' 
                            : 'inline-block p-4 text-gray-600 border-blue-600  rounded-t-lg'">
                            {{$semestry->SEMESTRY}}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <!-- Tab Content-->
            <div class="p-2 text-gray-500 text-xs md:text-sm">
                {{-- <div class="flex my-2 text-sm font-semibold items-center text-gray-800">
                    <div class="flex-grow border-t h-px mr-3"></div>
                    ตารางผลการเรียน
                    <div class="flex-grow border-t h-px ml-3"></div>
                </div> --}}
                @foreach($grade as $g)
                <div x-cloak="" x-show="isActive({{$g['semestry']}})" class="hover:bg-violet-200 active:bg-violet-200">
                    <div class="flex ... text-xs md:text-sm">
                        <div class="flex-none ... p-2">
                            {{$g['semestry']}}
                        </div>
                        <div class="flex-none ... p-2">
                            {{$g['sub_code']}}
                        </div>
                        <div class="flex-auto w-64 ... p-2">
                            {{$g['sub_name']}}
                        </div>
                        <div class="flex-auto w-7 ... p-2">
                            {{$g['grade']}}
                        </div>
                      </div>
                </div>
            @endforeach
            </div>
        </div>
    </section>
</x-app-layout>

{{-- Script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript"></script>
<script>
    var data_grade_analyze =  {{ Js::from($grade_analyze) }};
    const data = {
        labels: [
            'ทักษะการเรียนรู้',
            'ความรู้พื้นฐาน',
            'การประกอบอาชีพ',
            'ทักษะการดำเนินชีวิต',
            'การพัฒนาสังคม',
        ],
        datasets: [{
            label: 'คะแนนเฉลี่ยกลุ่มสาระ',
            data: data_grade_analyze,
            fill: true,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
            pointBackgroundColor: 'rgb(255, 99, 132)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgb(255, 99, 132)'
        }]
    };

    const config = {
    type: 'bar',
    data: data,
    options: {
        elements: {
            line: {
                borderWidth: 3
            }
        },
        // scales: {
        //     r: {
        //         angleLines: {
        //             display: false
        //         },
        //         suggestedMin: 50,
        //         suggestedMax: 100
        //     }
        // }
    },
    };

    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>
<!-- Include html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Function to format date as DD/MM/YYYY
    function formatDate(date) {
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Function to calculate expiry date (current date + 5 years)
    function calculateExpiryDate() {
        const currentDate = new Date();
        const expiryDate = new Date(currentDate);
        expiryDate.setFullYear(currentDate.getFullYear() + 5);
        return expiryDate;
    }

    // Print Card Function
    function printCard(avatarUrl) {
    const currentDate = new Date();
    const expiryDate = calculateExpiryDate();
    let level;

    if (@json($lavel) == 3) {
        level = 'มัธยมปลาย';
    } else if (@json($lavel) == 2) {
        level = 'มัธยมต้น';
    } else {
        level = 'ประถมศึกษา';
    }

    const studentData = {
        prename: @json($student[0]->PRENAME),
        name: @json($student[0]->NAME),
        surname: @json($student[0]->SURNAME),
        id: @json($student[0]->ID),
        level: level,
        department: 'สกร.ระดับอำเภอโพธิ์ทอง',
        issueDate: formatDate(currentDate), // Current date
        expiryDate: formatDate(expiryDate), // Current date + 5 years
    };

    const printContent = `
        <style>
            @media print {
                /* บังคับให้พิมพ์ background image และ background color */
                .print-background {
                    -webkit-print-color-adjust: exact; /* สำหรับ Chrome/Safari */
                    color-adjust: exact; /* สำหรับ Firefox */
                    print-color-adjust: exact; /* มาตรฐานใหม่ */
                }
            }
        </style>
        <div id="student-card" style="width: 8.6cm; height: 5.4cm; border: 1px solid #000; padding: 0px; font-family: 'Prompt', sans-serif; font-size: 12px; position: relative; box-sizing: border-box;">
            <!-- Background Logo with Reduced Opacity -->
            <div class="print-background" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('https://phothongdlec.ac.th/storage/logo.png'); background-size: contain; background-repeat: no-repeat; background-position: center; opacity: 0.1; z-index: 1;"></div>
            <!-- Card Content -->
            <div style="position: relative; z-index: 2;">
                <canvas id="qr-code" style="position: absolute; top: 10px; right: 10px;">
                QR CODE
                </canvas>
                <h1 style="text-align: center; font-size: 16px; margin-bottom: 5px;">บัตรประจำตัวนักศึกษา</h1>
                <div style="display: flex; height: calc(100% - 30px); justify-content: space-between; align-items: flex-start;">
                    <!-- Profile Image Section -->
                    <div style="width: 40%; display: flex; justify-content: center; align-items: center;">
                        <div style="width: 70%; aspect-ratio: 7 / 8; border: 1px solid #000; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img id="preview" src="${avatarUrl ? avatarUrl + '?v=' + Date.now() : 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'}" alt="Student Image" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
                        </div>
                    </div>
                    <!-- Information Section -->
                    <div style="width: calc(100% - 3cm); display: flex; flex-direction: column; justify-content: space-between; padding-left: 10px; box-sizing: border-box;">
                        <p style="margin: 0;"><strong>ชื่อ:</strong> ${studentData.prename}${studentData.name} ${studentData.surname}</p>
                        <p style="margin: 0;"><strong>รหัสนักศึกษา:</strong> ${studentData.id}</p>
                        <p style="margin: 0;"><strong>ระดับชั้น:</strong> ${studentData.level}</p>
                        <p style="margin: 0;"><strong>สถานศึกษา:</strong> ${studentData.department}</p>
                        <p style="margin: 0;"><strong>ออกบัตร:</strong> ${studentData.issueDate}</p>
                        <p style="margin: 0;"><strong>หมดอายุ:</strong> ${studentData.expiryDate}</p>
                    </div>
                </div>
                <!-- Signature Section -->
                <div style="width: 100%; display: flex; justify-content: space-between; margin-top: -45px;">
                    <div style="width: 48%; text-align: center; padding: 10px;">
                        <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                        <p style="margin-top: 5px; margin-bottom: 0;">ลงชื่อนักศึกษา</p>
                    </div>
                    <div style="width: 48%; text-align: center; padding: 10px;">
                        <div style="border-top: 1px solid #000; width: 100%; margin: 0 auto;"></div>
                        <p style="margin-top: 5px; margin-bottom: 0;">ลงชื่อผู้อำนวยการฯ</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Print and Download Buttons -->
        <div style="text-align: center; margin-top: 20px; width: 8.6cm;">
            <button onclick="window.print()" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer; margin-right: 10px;">
                กดพิมพ์บัตร
            </button>
        </div>
        <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    `;

    const newWindow = window.open('', '', 'width=800,height=600');
    newWindow.document.write(printContent);

    // Generate QR Code
    const qrCanvas = newWindow.document.getElementById('qr-code');
    QRCode.toCanvas(qrCanvas, JSON.stringify(studentData), {
        width: 80,
        margin: 2,
    })
    .then(() => {
        newWindow.document.close();
    })
    .catch((error) => {
        console.error('Error generating QR code:', error);
        newWindow.document.close();
    });
}
</script>

  