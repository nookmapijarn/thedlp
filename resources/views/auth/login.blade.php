<x-guest-layout>
    <style> [x-cloak] { display: none !important; } </style>

    <div class="grid grid-cols-1 gap-2 justify-items-center max-h-screen bg-white/0 backdrop-blur-md rounded-[0.3rem] from-slate-50 via-slate-100 to-slate-200 py-6 px-4 ">

        <div class="text-center space-y-3 animate-in fade-in slide-in-from-top-4 duration-1000">
            <div class="flex flex-col items-center">

                <div class="flex flex-col items-center justify-center py-2">
                    <a href="/" class="relative group">
                        <div class="absolute inset-0 bg-blue-500/20 blur-[60px] rounded-full animate-[pulse_5s_ease-in-out_infinite]"></div>
                        
                        <div class="absolute -bottom-12 left-1/2 -translate-x-1/2 w-32 h-4 bg-blue-600/10 blur-xl rounded-[100%] animate-[shadow-scale_6s_ease-in-out_infinite]"></div>

                        <div class="relative animate-[mega-float_6s_ease-in-out_infinite]">
                            <img class="w-56" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
                            {{-- <x-application-logo class="w-[150px] sm:w-64 h-auto fill-current text-blue-600 filter drop-shadow-[0_20px_30px_rgba(37,99,235,0.25)] transition-all duration-1000 group-hover:scale-110 group-hover:brightness-110" /> --}}
                        </div>
                    </a>
                </div>

                {{-- <h3 class="text-2xl font-black text-gray-800 tracking-[0.25em] uppercase mb-1 drop-shadow-sm">
                    {{ config('app.name') }}
                </h3> --}}
                
                <div class="h-[1px] w-12 bg-purple-500 mb-2 opacity-80"></div>

                <h1 class="text-[15px] sm:text-md font-bold text-gray-900 uppercase tracking-[0.2em] mb-1">
                    {{ config('app.name_system') }}
                </h1>
                <h2 class="text-md sm:text-md font-light text-gray-500 tracking-wide">
                    {{ config('app.name_th') }}
                </h2>
            </div>
        </div>

        {{-- Login Card --}}
        <div class="sm:max-w-md w-full space-y-4">

            <x-auth-session-status class="mb-2 text-md text-center font-medium text-green-600" :status="session('status')" />

            <div class="bg-white/70 backdrop-blur-md rounded-[2rem] p-8 border border-white shadow-2xl shadow-slate-200/50"
                 x-cloak
                 x-data="{ 
                    activeTab: '{{ $errors->has('email') ? 'staff' : 'student' }}',
                    isActive(tab) { return this.activeTab === tab; } 
                 }">
                <div class="mb-8">
                    <div class="flex p-1 bg-slate-100 rounded-2xl">
                        <button type="button" @click="activeTab = 'student'"
                                :class="isActive('student') ? 'bg-white text-gray-900 shadow-md' : 'text-gray-400 hover:text-gray-600'"
                                class="flex-1 py-2 text-md font-bold rounded-xl transition-all duration-300 uppercase tracking-widest">
                            ผู้เรียน
                        </button>
                        <button type="button" @click="activeTab = 'staff'"
                                :class="isActive('staff') ? 'bg-white text-gray-900 shadow-md' : 'text-gray-400 hover:text-gray-600'"
                                class="flex-1 py-2 text-md font-bold rounded-xl transition-all duration-300 uppercase tracking-widest">
                            เจ้าหน้าที่
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <div x-show="isActive('student')" x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-2">
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <div class="space-y-2">
                                <label class="block text-[15px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">รหัสนักศึกษา</label>
                                <input name="student_id" type="text" value="{{ old('student_id') }}" required autofocus
                                       class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all"
                                       placeholder="0000000000">
                                <x-input-error :messages="$errors->get('student_id')" class="mt-1 flex flex-col items-center text-center" />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[15px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Password</label>
                                <input name="password" type="password" required
                                       class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                                       placeholder="••••••••">
                                <x-input-error :messages="$errors->get('password')" class="mt-1 flex flex-col items-center text-center" />
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-md font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 tracking-[0.2em] uppercase">
                                เข้าสู่ระบบ
                            </button>
                        </form>
                    </div>

                    <div x-show="isActive('staff')" x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0 translate-y-2">
                        <form method="POST" action="{{ route('loginWithEmail') }}" class="space-y-5">
                            @csrf
                            <div class="space-y-2">
                                <label class="block text-[15px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Email (เจ้าหน้าที่)</label>
                                <input name="email" type="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all"
                                       placeholder="staff@example.com">
                                <x-input-error :messages="$errors->get('email')" class="mt-1 flex flex-col items-center text-center" />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[15px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Password</label>
                                <input name="password" type="password" required
                                       class="w-full px-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                                       placeholder="••••••••">
                            </div>

                            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-700 text-white text-md font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95 tracking-[0.2em] uppercase">
                                เข้าสู่ระบบเจ้าหน้าที่
                            </button>
                        </form>
                    </div>
                </div>

                <div class="flex flex-col items-center space-y-3 mt-8">
                    <div class="h-[1px] w-full bg-gray-100"></div>
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('register') }}" class="text-[15px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">สมัครสมาชิก</a>
                        <span class="text-gray-200">/</span>
                        <a href="{{ route('password.request') }}" class="text-[15px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">ลืมรหัสผ่าน?</a>
                    </div>
                </div>
            </div>
            
            <p class="text-[9px] text-center text-gray-400 font-medium uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} — นนทชัย มาพิจารณ์
            </p>
        </div>
    </div>
</x-guest-layout>

