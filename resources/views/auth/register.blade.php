<x-guest-layout>
    <style> 
        [x-cloak] { display: none !important; } 
        /* สร้าง Grainy Noise Texture ด้วย SVG Filter */
        .noise-bg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.08; /* ปรับความชัดของเม็ดทรายที่นี่ */
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

        <div class="sm:max-w-xl w-full space-y-6 relative z-10 animate-in fade-in slide-in-from-top-4 duration-1000">
            
            <div class="flex flex-col items-center justify-center">
                <a href="/" class="relative group">
                    <div class="absolute inset-0 bg-blue-500/15 blur-[40px] rounded-full animate-pulse"></div>
                    <div class="relative animate-[mega-float_6s_ease-in-out_infinite]">
                        <img class="w-32 sm:w-40 h-auto opacity-90" src="https://phothongdlec.ac.th/storage/olislogo.png" alt="Logo">
                    </div>
                </a>
            </div>

            <div class="text-center space-y-2">
                <h3 class="text-2xl font-black text-gray-800 tracking-[0.25em] uppercase drop-shadow-sm">Register</h3>
                <div class="h-[1px] w-12 bg-purple-500 mx-auto opacity-80"></div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">สร้างบัญชีผู้ใช้งานใหม่</p>
            </div>

            <div class="bg-white/70 backdrop-blur-md rounded-[2rem] p-8 sm:p-10 border border-white shadow-2xl shadow-slate-200/50">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">รหัสนักศึกษา 10 หลัก</label>
                            <input name="student_id" type="number" value="{{ old('student_id') }}" required autofocus
                                   class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all"
                                   placeholder="0000000000">
                            <x-input-error :messages="$errors->get('student_id')" class="mt-1 flex flex-col items-center" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">ชื่อผู้ใช้งาน</label>
                            <input name="name" type="text" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all"
                                   placeholder="Your Name">
                            <x-input-error :messages="$errors->get('name')" class="mt-1 flex flex-col items-center" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 font-medium transition-all"
                               placeholder="example@mail.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 flex flex-col items-center" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Password</label>
                            <input name="password" type="password" required
                                   class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                                   placeholder="รหัสผ่าน">
                            <x-input-error :messages="$errors->get('password')" class="mt-1 flex flex-col items-center" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Confirm Password</label>
                            <input name="password_confirmation" type="password" required
                                   class="w-full px-4 py-3 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-blue-500/20 text-center text-gray-700 transition-all"
                                   placeholder="ยืนยันรหัสผ่าน">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 flex flex-col items-center" />
                        </div>
                    </div>

                    <div class="mt-6 p-5 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <h4 class="text-[10px] font-black text-gray-700 uppercase tracking-widest mb-3 flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> นโยบายคุ้มครองข้อมูลส่วนบุคคล (PDPA)
                        </h4>
                        <div class="max-h-32 overflow-y-auto text-[10px] text-gray-500 leading-relaxed space-y-2 pr-2 custom-scrollbar">
                            <p>แบบขอความยินยอมให้ เก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ.2562 วัตถุประสงค์ของการเก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ดังต่อไปนี้</p>
                            <ul class="list-decimal pl-4 space-y-1">
                                <li>รายละเอียดเกี่ยวกับตัวสมาชิก เช่น ชื่อ นามสกุล วัน เดือน ปีเกิด สถานภาพ เป็นต้น</li>
                                <li>รายละเอียดเกี่ยวกับการระบุและยืนยันตัวตน เช่น หมายเลขประจําตัวประชาชน หมายเลขโทรศัพท์ ภาพถ่าย เป็นต้น</li>
                                <li>รายละเอียดสําหรับการติดต่อ เช่น ที่อยู่ หมายเลขโทรศัพท์ E-mail เป็นต้น</li>
                                <li>รายละเอียดเกี่ยวกับการเรียน เช่น เกรดเฉลี่ย ประวัติการเรียน เวลาเรียน อื่นๆ เป็นต้น</li>
                                <li>ข้อมูลอื่น ๆ ที่สมาชิกได้ให้ไว้กับสถานศึกษาที่สังกัด</li>
                                <li>ผู้ใช้งานสามารถยกเลิกการเปิดเผยข้อมูลได้หากต้องการ ข้าพเจ้าได้อ่านข้อความข้างต้นทั้งหมดแล้ว และยินยอมให้เก็บ รวบรวม ใช้ และ/หรือ เปิดเผยข้อมูลส่วนบุคคล ตามวัตถุประสงค์ที่ข้างต้นทั้งหมด</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-center space-x-2">
                            <input type="checkbox" name="pdpa_check" id="pdpa_check" class="rounded-md border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500/20 w-4 h-4 cursor-pointer">
                            <label for="pdpa_check" class="text-[11px] font-bold text-gray-600 cursor-pointer">ยอมรับแบบขอความยินยอมฯ</label>
                        </div>
                        <x-input-error :messages="$errors->get('pdpa_check')" class="mt-2 flex flex-col items-center" />
                    </div>

                    <div class="flex flex-col space-y-4 mt-8">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-4 rounded-xl shadow-lg shadow-blue-200 transition-all active:scale-95 tracking-[0.2em] uppercase">
                            ลงทะเบียนสมัครสมาชิก
                        </button>
                        
                        <div class="flex items-center justify-center space-x-4">
                            <div class="h-[1px] w-12 bg-gray-100"></div>
                            <a class="text-[10px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest" href="{{ route('login') }}">
                                มีบัญชีอยู่แล้ว? เข้าสู่ระบบ
                            </a>
                            <div class="h-[1px] w-12 bg-gray-100"></div>
                        </div>
                    </div>
                </form>
            </div>
            
            <p class="text-[9px] text-center text-gray-400 font-medium uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} — นนทชัย มาพิจารณ์
            </p>
        </div>
    </div>
</x-guest-layout>