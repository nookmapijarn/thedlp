<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- student_id -->
        <div>
            <x-input-label for="student_id" :value="__('รหัสนักศึกษา 10 หลัก')" />
            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" required autofocus autocomplete="student_id" />
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
            <pre class="text-nowrap bd-highlight" style="width: 100%; font-size: 12px; overflow:auto; max-height:400px;">
<strong>แบบขอความยินยอมให้ เก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล 
ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ.2562</strong>
วัตถุประสงค์ของการเก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ดังต่อไปนี้
    1.รายละเอียดเกี่ยวกับตัวสมาชิก เช่น ชื่อ นามสกุล วัน เดือน ปีเกิด สถานภาพ เป็นต้น
    2.รายละเอียดเกี่ยวกับการระบุและยืนยันตัวตน เช่น หมายเลขประจําตัวประชาชน 
    หมายเลขโทรศัพท์ ภาพถ่าย เป็นต้น
    3.รายละเอียดสําหรับการติดต่อ เช่น ที่อยู่ หมายเลขโทรศัพท์ E-mail เป็นต้น
    4.รายละเอียดเกี่ยวกับการเรียน เช่น เกรดเฉลี่ย ประวัติการเรียน เวลาเรียน อื่นๆ เป็นต้น
    5.ข้อมูลอื่น ๆ ที่สมาชิกได้ให้ไว้กับ กศน.อำเภอโพธิ์ทอง
    6.ผู้ใช้งานสามารถยกเลิกการเปิดเผยข้อมูลได้หากต้องการ
ข้าพเจ้าได้อ่านข้อความข้านต้นทั้งหมดแล้ว และยินยอมให้เก็บ รวบรวม ใช้ และ/หรือ 
เปิดเผยข้อมูลส่วนบุคคล ตามวัถุประสงค์ที่ข้างต้นทั้งหมด
            </pre>
            <br>
            <input type="checkbox" class="custom-control-input" name="pdpa_check">
            <label class="mt-4" for="pdpa_check"> ยอมรับแบบขอความยินยอมฯ  </label>
            <x-input-error :messages="$errors->get('pdpa_check')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
