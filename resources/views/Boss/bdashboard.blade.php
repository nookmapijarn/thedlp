<x-boss-layout>
    {{-- <x-slot name="header">
        <h6 class="font-semibold text-md text-gray-800 dark:text-gray-200 leading-tight pl-4">
            {{ __('ผู้บริหาร') }}
        </h6>
    </x-slot> --}}
    <div class="h-full p-8 bg-gray-100">
        <h3 class="text-lg font-medium leading-6 text-gray-900">
          ข้อมูล ภาคเรียนปัจจุบัน {{$semestry}}
        </h3>
        <div class="grid grid-cols-1 gap-5 mt-5 md:grid-cols-3">
          <div class="overflow-hidden bg-indigo-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
              <dl>
                <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                    <div>
                        นักศึกษาทั้งหมด
                    </div>  
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>                                
                </dt>
                <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900 ">
                  {{$allstudent}} คน
                </dd>
              </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-green-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            นักศึกษาใหม่
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>                                                       
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{$new_student}} คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-violet-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            คาดว่าจะจบ
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                        </svg>                                                       
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{$expectfin_student}} คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-yellow-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
                <dl>
                    <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                        <div>
                            ไม่จบตกค้าง
                        </div>  
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>                                                      
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{$nofinish_student}} คน
                    </dd>
                </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-blue-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
              <dl>
                <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                    <div>
                        เข้าสอบ (ล่าสุด)
                    </div>  
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                    </svg>                                                                    
                </dt>
                <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                  {{$exam_avg}}%
                </dd>
              </dl>
            </div>
          </div>
          <div class="overflow-hidden bg-red-100 rounded-lg shadow">
            <div class="px-4 py-5 lg:p-6">
              <dl>
                <dt class="text-sm font-medium leading-5 text-gray-500 truncate flex flex-row content-center place-content-between">
                    <div>
                        ขาดสอบ (ล่าสุด)
                    </div>  
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384" />
                    </svg>                                                     
                </dt>
                <dd class="mt-1 text-3xl font-semibold leading-9 text-gray-900">
                    {{100-$exam_avg}}%
                </dd>
              </dl>
            </div>
          </div>
        </div>
      </div>  
</x-boss-layout>
