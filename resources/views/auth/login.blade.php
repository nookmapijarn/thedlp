<x-guest-layout>
    <!-- Main Container -->
    <div class=" flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg w-full space-y-8">
            <!-- Header Section -->
            <div class="text-center space-y-4">
                <div class="space-y-2">
                    <h1 class="text-2xl font-light text-gray-600">{{ config('app.name_system') }}</h1>
                    <h2 class="text-xl font-light text-gray-500">{{ config('app.name_th') }}</h2>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</h3>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Card -->
            <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-white/20"
                 x-data="{
                    activeTab: 'student',
                    isActive(tab) {
                        return this.activeTab === tab;
                    }
                 }">
                
                <!-- Tab Navigation -->
                <div class="mb-8">
                    <div class="flex space-x-1 rounded-xl bg-gray-100 p-1">
                        <!-- Student Tab -->
                        <button @click="activeTab = 'student'"
                                :class="isActive('student') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs opacity-75">เข้าสู่ระบบ</div>
                                <div class="font-semibold">ผู้เรียน</div>
                            </div>
                        </button>
                        
                        <!-- Staff Tab -->
                        <button @click="activeTab = 'staff'"
                                :class="isActive('staff') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs opacity-75">เข้าสู่ระบบ</div>
                                <div class="font-semibold">เจ้าหน้าที่</div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Student Login Form -->
                <div x-show="isActive('student')" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Student ID -->
                        <div class="space-y-1">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">รหัสนักศึกษา</label>
                            <input id="student_id" name="student_id" type="number" 
                                   value="{{ old('student_id') }}" required autofocus
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-center text-lg"
                                   placeholder="กรอกรหัสนักศึกษา">
                            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <label for="student_password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                            <input id="student_password" name="password" type="password" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-center"
                                   placeholder="กรอกรหัสผ่าน">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember_student" name="remember" type="checkbox"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_student" class="ml-2 text-sm text-gray-600">จดจำการเข้าสู่ระบบ</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center group">
                            เข้าสู่ระบบ
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Staff Login Form -->
                <div x-show="isActive('staff')" x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    <form method="POST" action="{{ route('loginWithEmail') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email -->
                        <div class="space-y-1">
                            <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                            <input id="email" name="email" type="email" 
                                   value="{{ old('email') }}" required autofocus
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-center"
                                   placeholder="กรอกอีเมล">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="space-y-1">
                            <label for="staff_password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                            <input id="staff_password" name="password" type="password" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-center"
                                   placeholder="กรอกรหัสผ่าน">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember_staff" name="remember" type="checkbox"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_staff" class="ml-2 text-sm text-gray-600">จดจำการเข้าสู่ระบบ</label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center group">
                            เข้าสู่ระบบ
                            <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center justify-center space-x-6 text-sm">
                <a href="{{ route('/welcome') }}" 
                   class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    หน้าหลัก
                </a>
                <span class="text-gray-300">•</span>
                <a href="{{ route('register') }}" 
                   class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    สมัครสมาชิก
                </a>
                @if (Route::has('password.request'))
                <span class="text-gray-300">•</span>
                <a href="{{ route('password.request') }}" 
                   class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">
                    ลืมรหัสผ่าน?
                </a>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>