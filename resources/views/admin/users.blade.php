<x-admin-layout>
    <div class="p-4 sm:ml-64">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <form method="POST" class="p-4" action="{{ route('adminregister') }}">
                <h1 class="pt-4">เพิ่มผู้ใช้งาน สำหรับครู/ผู้ดูแลระบบ</h1>
                @csrf
                <!-- role -->
                <div>
                    <x-input-label for="role" :value="__('กำหนดสิทธิ ครู : 2 | ผู้ดูแล : 3')" />
                    <x-text-input id="role" class="block mt-1 w-full" type="Number" min="2" max="3" name="role" :value="2" required autofocus autocomplete="role" />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Name -->
                <div class="mt-4">
                    <x-input-label for="name" :value="__('ชื่อผู้ใช้งาน')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
        
                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
        
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
        
                <!-- PDPA -->
                <div class="mt-4">
                    <p class="mb-3 text-xs font-bold text-gray-900 dark:text-gray-400 " style="width: 100%; max-height:400px;">
                    <span>แบบขอความยินยอมให้ เก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ.2562 วัตถุประสงค์ของการเก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ดังต่อไปนี้</span>
                        <ul class="text-xs list-decimal p-2">
                            <li>รายละเอียดเกี่ยวกับตัวสมาชิก เช่น ชื่อ นามสกุล วัน เดือน ปีเกิด สถานภาพ เป็นต้น</li>
                            <li>รายละเอียดเกี่ยวกับการระบุและยืนยันตัวตน เช่น หมายเลขประจําตัวประชาชน หมายเลขโทรศัพท์ ภาพถ่าย เป็นต้น</li>
                            <li>รายละเอียดสําหรับการติดต่อ เช่น ที่อยู่ หมายเลขโทรศัพท์ E-mail เป็นต้น</li>
                            <li>รายละเอียดเกี่ยวกับการเรียน เช่น เกรดเฉลี่ย ประวัติการเรียน เวลาเรียน อื่นๆ เป็นต้น</li>
                            <li>ข้อมูลอื่น ๆ ที่สมาชิกได้ให้ไว้กับสถานศึกษาที่สังกัด</li>
                            <li>ผู้ใช้งานสามารถยกเลิกการเปิดเผยข้อมูลได้หากต้องการข้าพเจ้าได้อ่านข้อความข้านต้นทั้งหมดแล้ว และยินยอมให้เก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ตามวัถุประสงค์ที่ข้างต้นทั้งหมด</li>
                        </ul>
                    </p>
                    <br>
                    <input type="checkbox" class="custom-control-input" name="pdpa_check">
                    <label class="mt-4" for="pdpa_check"> ยอมรับแบบขอความยินยอมฯ  </label>
                    <x-input-error :messages="$errors->get('pdpa_check')" class="mt-2" />
                </div>
        
                <div class="flex items-center justify-end mt-4">
                    {{-- <a class="underline text-sm text-gray-900 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                        {{ __('ไปหน้า login') }}
                    </a> --}}
        
                    <x-primary-button class="ml-4">
                        {{ __('สมัครสมาชิก') }}
                    </x-primary-button>
                </div>
            </form>      
            <div class="relative overflow-x-auto w-full max-h-[750px]">
                <h1 class="p-4">ตาราง user ครู/ผู้ดูแลระบบ</h1>
                <table class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                No.
                            </th>
                            {{-- <th scope="col" class="px-6 py-3">
                                ID
                            </th> --}}
                            <th scope="col" class="px-6 py-3">
                                NAME
                            </th>
                            <th scope="col" class="px-6 py-3">
                                E-MAIL
                            </th>
                            {{-- <th scope="col" class="px-6 py-3">
                                PASS
                            </th> --}}
                            <th scope="col" class="px-6 py-3">
                                ROLE
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users_admin as $user_ad)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class=" text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loop->iteration}}
                            </th>
                            {{-- <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user_ad->id }}
                            </th> --}}
                            <td class="px-6 py-4">
                                {{ $user_ad->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user_ad->email }}
                            </td>
                            {{-- <td class="px-6 py-4">
                                {{ $user->password }}
                            </td> --}}
                            <td class="px-6 py-4">
                                {{ $user_ad->role }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>    
        </div>
        <div class="p-4 rounded-lg dark:border-gray-700 mt-2">
            <!-- แสดงข้อความ error -->
            @if(session('error'))
                <div class="bg-red-500 text-white p-2 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- แสดงข้อความ success -->
            @if(session('success'))
                <div class="bg-green-500 text-white p-2 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        {{-- Table --}}
        <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-2">
            <th>ตาราง user ผู้เรียน </th> <br>
            <div class="relative overflow-x-auto max-h-screen">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                No.
                            </th>
                            <th scope="col" class="px-6 py-3">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3">
                                NAME
                            </th>
                            <th scope="col" class="px-6 py-3">
                                E-MAIL
                            </th>
                            {{-- <th scope="col" class="px-6 py-3">
                                PASS
                            </th> --}}
                            <th scope="col" class="px-6 py-3">
                                CREATEAT
                            </th>
                            {{-- <th scope="col" class="px-6 py-3">
                                ROLE
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class=" text-center font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{$loop->iteration}}
                            </th>
                            <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $user->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->email }}
                            </td>
                            {{-- <td class="px-6 py-4">
                                {{ $user->password }}
                            </td> --}}
                            <td class="px-6 py-4">
                                {{ $user->created_at }}
                            </td>
                            {{-- <td class="px-6 py-4">
                                {{ $user->role }}
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>   
        </div>
    </div>
</x-admin-layout>