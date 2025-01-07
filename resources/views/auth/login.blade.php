<x-guest-layout>

    {{-- Label --}}
    <div class="text-center m-2 rounded-lg text-lg text-shadow">{{ config('app.name_system') }}</div>
    <div class="text-center m-2 rounded-lg text-lg text-shadow">{{ config('app.name_th') }}</div>
    <div class="text-center p-1 m-1 text-2xl text-gray-900 ">{{ config('app.name') }}</div>
 


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
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
        <div class="mb-2 text-sm md:text-md font-medium text-center text-gray-500 dark:text-gray-400 dark:border-gray-700">
            <ul class="grid grid-cols-1 gap-2 sm:grid-cols-2 justify-center">
                {{-- <li class="mr-2">
                    <span class="inline-block p-4 rounded-t-lg border-b-2 border-transparent">
                        เลือก :
                    </span>
                </li> --}}

                <li class="cursor-pointer"
                    @click="activeTab = {{1}}">
                    <a :class="isActive({{1}}) ? 'w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5 ' : 'w-full sm:w-auto bg-gray-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5'">
                        <svg class=" w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg> 
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class=" font-sans text-sm font-semibold">ผู้เรียน</div>
                        </div>
                    </a>
                </li>
                <li class="cursor-pointer"
                    @click="activeTab = {{2}}">
                    <a :class="isActive({{2}}) ? 'w-full sm:w-auto bg-yellow-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5 ' : 'w-full sm:w-auto bg-gray-300 text-gray-800 hover:bg-yellow-400 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-lg inline-flex items-center justify-center px-4 py-2.5'">
                        <svg  class="mr-3 w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg> 
                        <div class="text-left">
                            <div class="mb-1 text-xs">เข้าสู่ระบบ</div>
                            <div class=" font-sans text-sm font-semibold">ผู้สอน / เจ้าหน้าที่</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ผู้เรียน -->
        <div class="text-gray-500 text-xs md:text-sm">
            <div x-cloak="" x-show="isActive({{1}})" class="">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Student ID -->
                    <div>
                        <x-input-label for="student_id" :value="__('รหัสนักศึกษา')" />
                        <x-text-input id="student_id" class="block mt-1 w-full text-center" 
                            type="Number" name="student_id" :value="old('student_id')" 
                            required 
                            autofocus 
                            autocomplete="student_id" />
                        <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                    </div>
            
            
                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
            
                        <x-text-input id="password" class="block mt-1 w-full text-center"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        />
            
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
                                {{ __('ลืมรหัสผ่าน?') }}
                            </a>
                        @endif --}}
                        {{-- <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">
                            {{ __('เข้าสู่ระบบ') }}
                            <svg aria-hidden="true" class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button> --}}
                        <button class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center inline-flex items-center">
                            {{ __('เข้าสู่ระบบ') }}
                            <svg aria-hidden="true" class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- เจ้าหน้าที่ --}}
        <div class="text-gray-500 text-xs md:text-sm">
            <div x-cloak="" x-show="isActive({{2}})" class="">
                <form method="POST" action="{{ route('loginWithEmail') }}">
                    @csrf
            
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full text-center" 
                            type="email" name="email" :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
            
                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
            
                        <x-text-input id="password" class="block mt-1 w-full text-center"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password"
                                        />
            
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
            
                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>
            
                    <div class="flex items-center justify-center mt-4">
                        <button class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center inline-flex items-center">
                            {{ __('เข้าสู่ระบบ') }}
                            <svg aria-hidden="true" class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Manu -->
        <div class="flex items-center justify-center ">
            <a class="mt-4 m-2 hover:text-sky-900" href="{{ route('/welcome') }}">
                <div class="rounded-full">
                    {{ __('หน้าหลัก') }}
                </div>   
            </a> 
            <a class="mt-4 m-2 hover:text-sky-900" href="{{ route('register') }}">
                <div class="rounded-full">
                    {{ __('สมัครสมาชิก') }}
                </div>   
            </a>   
            @if (Route::has('password.request'))
                <a class="mt-4 m-2 dark:text-gray-400 hover:text-sky-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('ลืมรหัสผ่าน?') }}
                </a>
            @endif
        </div>
</x-guest-layout>
