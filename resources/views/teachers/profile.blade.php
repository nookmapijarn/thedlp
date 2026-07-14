<x-teachers-layout>
    <div class="p-6 mt-16 max-w-4xl mx-auto space-y-6">
        
        {{-- Header Banner --}}
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[140px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[11px] font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    OLIS Teacher Profile
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">จัดการข้อมูลส่วนตัว</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-bold max-w-3xl mt-1.5">
                    แก้ไขข้อมูลประวัติ อัปโหลดรูปภาพโปรไฟล์ และเปลี่ยนรหัสผ่านเพื่อรักษาความปลอดภัยในระบบ
                </p>
            </div>
        </div>

        {{-- Success / Error Alerts --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-bold flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-650 dark:text-rose-450 rounded-2xl text-xs font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Profile form --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
            <form action="{{ route('teachers.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Avatar Edit Section --}}
                <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-purple-500 bg-slate-100 dark:bg-slate-950 flex items-center justify-center">
                            <img id="avatar-preview" 
                                 src="{{ $teacher->avatar ? asset('storage/' . $teacher->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-center sm:text-left">
                        <h4 class="text-sm font-black text-slate-800 dark:text-white">ภาพประจำตัว (Avatar)</h4>
                        <p class="text-[10px] text-slate-400 font-bold leading-relaxed max-w-sm">
                            อัปโหลดภาพสกุล JPG, PNG หรือ GIF เท่านั้น ความละเอียดสูงและอัตราส่วน 1:1 จะดูดีที่สุด และขนาดห้ามเกิน 2MB
                        </p>
                        
                        <div class="pt-1">
                            <label class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-[11px] font-black cursor-pointer shadow-md transition-all active:scale-95">
                                <span>📁 เลือกรูปภาพใหม่</span>
                                <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(event)">
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Personal details --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">อีเมลเข้าใช้งาน</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" placeholder="เช่น 081XXXXXXX"
                               class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">
                    </div>

                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">แนะนำตัวสั้นๆ (Bio / คำนำหน้าในวิดีโอ)</label>
                        <textarea name="bio" rows="3" placeholder="ร่วมส่งมอบประสบการณ์การเรียนรู้ออนไลน์ที่ดีที่สุดให้กับทุกคน 🚀"
                                  class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">{{ old('bio', $teacher->bio) }}</textarea>
                    </div>
                </div>

                {{-- Password change area --}}
                <div class="border-t border-slate-100 dark:border-slate-800 pt-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-black text-slate-800 dark:text-white">เปลี่ยนรหัสผ่านใหม่ (หากไม่ต้องการเปลี่ยนให้เว้นว่างไว้)</h4>
                        <p class="text-[9px] text-slate-450 font-semibold leading-relaxed">เพื่อป้องกันความปลอดภัยทางบัญชี รหัสผ่านควรประกอบด้วยตัวอักษรอย่างน้อย 8 ตัวอักษร</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">รหัสผ่านใหม่</label>
                            <input type="password" name="password" 
                                   class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-3 text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 font-bold">
                        </div>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center justify-end gap-3.5 pt-4">
                    <a href="{{ route('tdashboard') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-650 dark:text-slate-300 rounded-xl text-xs font-black transition-all">
                        ยกเลิก
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md transition-all active:scale-95">
                        บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script for profile image live preview --}}
    <script>
        function previewAvatar(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-teachers-layout>
