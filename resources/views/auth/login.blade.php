<x-guest-layout>

    {{-- Label --}}
    <div class="text-center m-2 rounded-lg text-lg text-shadow">ศูนย์ส่งเสริมการเรียนรู้อำเภอโพธิ์ทอง</div>
    <div class="text-center p-1 m-1 text-2xl text-gray-900 ">สำหรับนักศึกษา</div>
 


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="student_id" :value="__('รหัสนักศึกษา')" />
            <x-text-input id="student_id" class="block mt-1 w-full" type="Number" name="student_id" :value="old('student_id')" required autofocus autocomplete="student_id" />
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        {{-- <button class="rounded-full ... bg-emerald-500"> 
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
                {{ __('สมัครสมาชิก') }}
            </a>
        </button> --}}

        <div class="flex items-center justify-center mt-4">
            {{-- @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}

            <x-primary-button class="ml-0 w-autu p-5">
                {{ __('เข้าสู่ระบบ') }}
            </x-primary-button>
        </div>

        <!-- Register -->
        <div class="flex items-center justify-end ">
            <a class="mt-4 m-2 hover:text-sky-700" href="{{ route('register') }}">
                <div class="rounded-full underline">
                    {{ __('สมัครสมาชิก') }}
                </div>   
            </a>   
            <a class="flex items-center justify-end mt-4 m-2 hover:text-sky-700" href="https://dlpd.in.th/welcome">
                <div class="rounded-full underline">
                    {{ __('หน้าหลัก') }}
                </div>   
            </a>   
        </div>
        

    </form>
</x-guest-layout>
