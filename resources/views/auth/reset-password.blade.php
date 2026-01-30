<x-guest-layout>
    <style> 
        [x-cloak] { display: none !important; } 
        .noise-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            pointer-events: none;
            z-index: 1;
            filter: url(#noiseFilter);
        }
    </style>

    <svg class="hidden">
        <filter id='noiseFilter'>
            <feTurbulence type='fractalNoise' baseFrequency='0.6' numOctaves='3' stitchTiles='stitch'/>
        </filter>
    </svg>

    <div class="grid grid-cols-1 gap-2 justify-items-center min-h-screen bg-white/100 backdrop-blur-md rounded-[0.3rem] bg-gradient-to-br from-slate-50 via-slate-100 to-slate-200 py-12 px-4 relative overflow-hidden">
        
        <div class="noise-bg"></div>

        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100/30 blur-[100px] rounded-full z-0"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-100/30 blur-[100px] rounded-full z-0"></div>

        <div class="sm:max-w-md w-full space-y-6 relative z-10 animate-in fade-in slide-in-from-top-4 duration-1000">
            
            <div class="flex flex-col items-center justify-center py-2">
                <a href="/" class="relative group">
                    <div class="absolute inset-0 bg-blue-500/15 blur-[40px] rounded-full animate-pulse"></div>
                    <div class="relative animate-[mega-float_6s_ease-in-out_infinite]">
                        <img class="w-32 h-auto opacity-90" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
                    </div>
                </a>
            </div>

            <div class="text-center space-y-2">
                <h3 class="text-2xl font-black text-gray-800 tracking-[0.25em] uppercase drop-shadow-sm">Reset Password</h3>
                <div class="h-[1px] w-12 bg-purple-500 mx-auto opacity-80"></div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">ตั้งรหัสผ่านใหม่ของคุณ</p>
            </div>

            <div class="bg-white/70 backdrop-blur-md rounded-[2rem] p-8 border border-white shadow-2xl shadow-slate-200/50">
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                               class="w-full px-4 py-3 bg-slate-50/50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 flex flex-col items-center" />
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center mb-1">New Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 bg-slate-50/50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 flex flex-col items-center" />
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 bg-slate-50/50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 flex flex-col items-center" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95 tracking-[0.2em] uppercase">
                            {{ __('เปลี่ยนรหัสผ่าน') }}
                        </button>
                    </div>
                </form>
            </div>
            
            <p class="text-[9px] text-center text-gray-400 font-medium uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} — นนทชัย มาพิจารณ์
            </p>
        </div>
    </div>
</x-guest-layout>