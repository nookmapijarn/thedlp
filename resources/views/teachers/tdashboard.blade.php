<x-teachers-layout>
    {{-- Main Container --}}
    <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6">
        
        <!-- Welcome Greeting Banner Block -->
        @php
            $teacherPendingCount = \App\Models\HelpRequest::where('status', 'pending')->count();
        @endphp
        <div class="bg-gradient-to-r from-purple-500 via-violet-800 to-purple-500 rounded-[2.5rem] p-8 sm:p-10 text-white shadow-md relative overflow-hidden flex flex-col md:flex-row md:items-center md:justify-between min-h-[140px]">
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/20 text-white text-[10px] font-black tracking-widest uppercase border border-white/10 shadow-sm leading-none">
                    OLIS Teacher Portal
                </span>
                <h2 class="text-3xl font-black mt-1">สวัสดีครับครู {{ Auth::user()->name }}</h2>
                <p class="text-sm text-purple-100 font-bold max-w-2xl leading-relaxed">
                    ยินดีต้อนรับสู่ระบบจัดการบทเรียน ตรวจสอบผลการเรียน และวิเคราะห์ข้อมูลผู้เรียนอย่างเป็นระบบ
                </p>
            </div>
            <img class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-3xl border-4 border-white/20 shadow-inner flex-shrink-0 mt-4 md:mt-0" src="https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg" alt="Teacher photo">
        </div>

        <!-- Quick Tools Menu Grid -->
        <div class="space-y-3">
            <span class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest ml-1 block">เมนูควบคุมการทำงาน</span>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                <!-- จัดการหลักสูตร -->
                <a href="{{ route('courses.manage') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 6c0-1.65685 1.3431-3 3-3s3 1.34315 3 3-1.3431 3-3 3-3-1.34315-3-3Zm2 3.62992c-.1263-.04413-.25-.08799-.3721-.13131-1.33928-.47482-2.49256-.88372-4.77995-.8482C4.84875 8.66593 4 9.46413 4 10.5v7.2884c0 1.0878.91948 1.8747 1.92888 1.8616 1.283-.0168 2.04625.1322 2.79671.3587.29285.0883.57733.1863.90372.2987l.00249.0008c.11983.0413.24534.0845.379.1299.2989.1015.6242.2088.9892.3185V9.62992Zm2-.00374V20.7551c.5531-.1678 1.0379-.3374 1.4545-.4832.2956-.1034.5575-.1951.7846-.2653.7257-.2245 1.4655-.3734 2.7479-.3566.5019.0065.9806-.1791 1.3407-.4788.3618-.3011.6723-.781.6723-1.3828V10.5c0-.58114-.2923-1.05022-.6377-1.3503-.3441-.29904-.8047-.49168-1.2944-.49929-2.2667-.0352-3.386.36906-4.6847.83812-.1256.04539-.253.09138-.3832.13765Z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">จัดการหลักสูตร</span>
                </a>
                <!-- แบบทดสอบ -->
                <a href="{{ url('teachers/ttest') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">แบบทดสอบ</span>
                </a>
                <!-- คลิปเรียนรู้สั้น (OLIS Shorts) -->
                <a href="{{ route('shorts.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">คลิปเรียนรู้สั้น<br>OLIS Shorts</span>
                </a>
                <!-- จัดการคลิปสั้น -->
                <a href="{{ route('teachers.shorts.index') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">จัดการคลิปสั้น</span>
                </a>
                <!-- รายงานผู้เรียนในกลุ่ม -->
                <a href="{{ url('teachers/treport') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7h1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h11.5M7 14h6m-6 3h6m0-10h.5m-.5 3h.5M7 7h3v3H7V7Z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">รายงานผู้เรียนในกลุ่ม</span>
                </a>
                <!-- ผลการเรียน -->
                <a href="{{ url('teachers/tgrade') }}" class="flex flex-col items-center justify-center p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] text-center hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-sm">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-650 flex items-center justify-center mb-2.5">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M7 2a2 2 0 0 0-2 2v1a1 1 0 0 0 0 2v1a1 1 0 0 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a1 1 0 1 0 0 2v1a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H7Zm3 8a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm-1 7a3 3 0 0 1 3-3h2a3 3 0 0 1 3 3 1 1 0 0 1-1 1h-6a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xs font-black text-slate-700 dark:text-slate-300 leading-tight">ผลการเรียน</span>
                </a>
            </div>
        </div>

        <!-- Ranking Section -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 ml-1">
                <span class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest block">กระดานจัดอันดับ (Ranking Boards)</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-[9px] font-black uppercase tracking-wider">OLIS Analytics</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. ผู้เรียนเข้าเรียนสูงสุด -->
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] p-5 shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-850">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-500 flex items-center justify-center font-bold text-lg">🏆</div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-white leading-none">ผู้เรียนเข้าเรียนสูงสุด</h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Active Students</span>
                            </div>
                        </div>
                        <ul class="divide-y divide-slate-50 dark:divide-slate-850/50 mt-3 space-y-2">
                            @forelse($top_students as $index => $std)
                                <li class="flex items-center justify-between py-1 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-5 h-5 flex items-center justify-center rounded-full font-mono font-black text-[10px] 
                                            @if($index == 0) bg-amber-100 text-amber-800 @elseif($index == 1) bg-slate-100 text-slate-700 @elseif($index == 2) bg-orange-100 text-orange-850 @else bg-slate-50 text-slate-400 @endif">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300 truncate max-w-[120px]">{{ $std->name }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-450 bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-md shrink-0">{{ $std->logs_count }} ครั้ง</span>
                                </li>
                            @empty
                                <li class="text-center text-slate-400 py-6 text-xs font-bold">ไม่มีข้อมูลการเข้าเรียน</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- 2. ครูผู้สร้างผลงานมากที่สุด -->
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] p-5 shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-850">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/30 text-purple-500 flex items-center justify-center font-bold text-lg">⭐</div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-white leading-none">ครูผู้สร้างผลงานสูงสุด</h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Top Creators</span>
                            </div>
                        </div>
                        <ul class="divide-y divide-slate-50 dark:divide-slate-850/50 mt-3 space-y-2">
                            @forelse($top_teachers as $index => $tc)
                                <li class="flex items-center justify-between py-1 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-5 h-5 flex items-center justify-center rounded-full font-mono font-black text-[10px] 
                                            @if($index == 0) bg-amber-100 text-amber-800 @elseif($index == 1) bg-slate-100 text-slate-700 @elseif($index == 2) bg-orange-100 text-orange-850 @else bg-slate-50 text-slate-400 @endif">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300 truncate max-w-[120px]">{{ $tc['name'] }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-purple-700 bg-purple-50 dark:bg-purple-950/40 dark:text-purple-300 px-2 py-0.5 rounded-md shrink-0">{{ $tc['total'] }} รายการ</span>
                                </li>
                            @empty
                                <li class="text-center text-slate-400 py-6 text-xs font-bold">ไม่มีข้อมูลผู้สร้าง</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- 3. คลิปที่มีผู้กดไลก์สูงสุด -->
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] p-5 shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-850">
                            <div class="w-8 h-8 rounded-xl bg-pink-50 dark:bg-pink-950/30 text-pink-500 flex items-center justify-center font-bold text-lg">❤️</div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-white leading-none">คลิปผู้กดไลก์สูงสุด</h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Most Liked Clips</span>
                            </div>
                        </div>
                        <ul class="divide-y divide-slate-50 dark:divide-slate-850/50 mt-3 space-y-2">
                            @forelse($top_liked_shorts as $index => $sv)
                                <li class="flex items-center justify-between py-1 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-5 h-5 flex items-center justify-center rounded-full font-mono font-black text-[10px] 
                                            @if($index == 0) bg-amber-100 text-amber-800 @elseif($index == 1) bg-slate-100 text-slate-700 @elseif($index == 2) bg-orange-100 text-orange-850 @else bg-slate-50 text-slate-400 @endif">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300 truncate max-w-[120px]">{{ $sv->title }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-pink-700 bg-pink-50 dark:bg-pink-950/40 dark:text-pink-300 px-2 py-0.5 rounded-md shrink-0">{{ $sv->likes_count ?? 0 }} ไลก์</span>
                                </li>
                            @empty
                                <li class="text-center text-slate-400 py-6 text-xs font-bold">ไม่มีข้อมูลคลิปสั้น</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- 4. หลักสูตรที่มีผู้เรียนมากที่สุด -->
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] p-5 shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 dark:border-slate-850">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-500 flex items-center justify-center font-bold text-lg">📚</div>
                            <div>
                                <h4 class="text-sm font-black text-slate-800 dark:text-white leading-none">หลักสูตรที่มีผู้เรียนสูงสุด</h4>
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Top Enrolled Courses</span>
                            </div>
                        </div>
                        <ul class="divide-y divide-slate-50 dark:divide-slate-850/50 mt-3 space-y-2">
                            @forelse($top_courses as $index => $cs)
                                <li class="flex items-center justify-between py-1 text-xs">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-5 h-5 flex items-center justify-center rounded-full font-mono font-black text-[10px] 
                                            @if($index == 0) bg-amber-100 text-amber-800 @elseif($index == 1) bg-slate-100 text-slate-700 @elseif($index == 2) bg-orange-100 text-orange-850 @else bg-slate-50 text-slate-400 @endif">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-bold text-slate-700 dark:text-slate-300 truncate max-w-[120px]">{{ $cs->title }}</span>
                                    </div>
                                    <span class="text-[10px] font-black text-blue-700 bg-blue-50 dark:bg-blue-950/40 dark:text-blue-300 px-2 py-0.5 rounded-md shrink-0">{{ $cs->enrollments_count }} คน</span>
                                </li>
                            @empty
                                <li class="text-center text-slate-400 py-6 text-xs font-bold">ไม่มีข้อมูลหลักสูตร</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!-- My Shorts Grid -->
        <div class="space-y-3">
            <div class="flex items-center justify-between ml-1">
                <span class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest">วิดีโอคลิปสั้นของคุณ ({{ $shorts->count() }})</span>
                <a href="{{ route('teachers.shorts.index') }}" class="text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider hover:underline">จัดการทั้งหมด →</a>
            </div>
            
            @if($shorts->isEmpty())
                <div class="p-8 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] text-center text-slate-400 font-bold text-sm">
                    ยังไม่มีวิดีโอคลิปสั้น หรือสไลด์โชว์ที่อัปโหลด
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                    @foreach($shorts->take(6) as $s)
                        <a href="{{ route('shorts.index', ['id' => $s->id]) }}" class="block bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] overflow-hidden shadow-sm hover:shadow-md hover:border-purple-300 dark:hover:border-purple-900/60 transition-all duration-300">
                            <div class="relative h-44 sm:h-56 bg-slate-950 flex items-center justify-center">
                                @if($s->type === 'images' && is_array($s->images) && count($s->images) > 0)
                                    <img class="w-full h-full object-cover" src="{{ asset('storage/' . $s->images[0]) }}" onerror="this.src='https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg'">
                                    <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full bg-slate-950/70 text-white text-[9px] font-black">📷 {{ count($s->images) }} ภาพ</span>
                                @elseif($s->video_path)
                                    <video class="w-full h-full object-cover" src="{{ asset('storage/' . $s->video_path) }}"></video>
                                    <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full bg-slate-950/70 text-white text-[9px] font-black">🎬 วิดีโอ</span>
                                @else
                                    <span class="text-xs text-slate-500">ไม่มีรูปภาพ</span>
                                @endif
                            </div>
                            <div class="p-3.5 space-y-1.5">
                                <h5 class="text-xs font-black text-slate-800 dark:text-white truncate leading-tight">{{ $s->title }}</h5>
                                <div class="flex items-center justify-between text-[10px] text-slate-450 dark:text-slate-500 font-bold">
                                    <span>👁️ {{ $s->views_count ?? 0 }} วิว</span>
                                    <span>❤️ {{ $s->likes_count ?? 0 }} ไลค์</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Double Column (Courses & Quizzes) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-10">
            
            <!-- Left: My Courses -->
            <div class="space-y-3">
                <div class="flex items-center justify-between ml-1">
                    <span class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest">หลักสูตรการสอน ({{ $courses->count() }})</span>
                    <a href="{{ route('courses.manage') }}" class="text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider hover:underline">จัดการทั้งหมด →</a>
                </div>
                
                @if($courses->isEmpty())
                    <div class="p-8 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] text-center text-slate-400 font-bold text-sm h-full min-h-[200px] flex items-center justify-center">
                        ยังไม่ได้สร้างหลักสูตรหรือวิชาเรียนออนไลน์
                    </div>
                @else
                    <div class="space-y-3.5">
                        @foreach($courses->take(4) as $c)
                            <div class="flex gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] shadow-sm relative">
                                <img class="w-16 h-16 object-cover rounded-2xl flex-shrink-0 shadow-sm border border-slate-100 dark:border-slate-800" src="{{ $c->cover_image ? $c->cover_image : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" onerror="this.src='https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg'">
                                <div class="flex-grow space-y-1.5 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full {{ $c->is_published ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-850 dark:text-slate-400' }} text-[9px] font-black uppercase tracking-wider leading-none">
                                            {{ $c->is_published ? 'เปิดสอน' : 'ร่าง' }}
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white truncate leading-tight">{{ $c->title }}</h4>
                                    <span class="text-xs text-slate-450 dark:text-slate-500 font-bold block leading-none">{{ $c->modules->count() }} บทเรียน | ผู้สมัครเรียน {{ $c->enrollments->count() }} คน</span>
                                    <div class="flex gap-2 pt-1.5">
                                        <a href="{{ route('courses.edit', $c->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-350 text-[10px] font-black rounded-lg transition-all">แก้ไขคอร์ส</a>
                                        <a href="{{ route('courses.report', $c->id) }}" class="px-3 py-1 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-[10px] font-black rounded-lg transition-all">รายงานผู้เรียน</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: My Quizzes -->
            <div class="space-y-3">
                <div class="flex items-center justify-between ml-1">
                    <span class="text-xs font-black text-slate-450 dark:text-slate-500 uppercase tracking-widest">คลังข้อสอบ/แบบทดสอบ ({{ $quizzes->count() }})</span>
                    <a href="{{ url('teachers/ttest') }}" class="text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider hover:underline">จัดการทั้งหมด →</a>
                </div>
                
                @if($quizzes->isEmpty())
                    <div class="p-8 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[2rem] text-center text-slate-400 font-bold text-sm h-full min-h-[200px] flex items-center justify-center">
                        ยังไม่มีแบบทดสอบหรือชุดคำถามที่สร้าง
                    </div>
                @else
                    <div class="space-y-3.5">
                        @foreach($quizzes->take(4) as $q)
                            <div class="flex gap-4 p-4 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-[1.75rem] shadow-sm relative">
                                <img class="w-16 h-16 object-cover rounded-2xl flex-shrink-0 shadow-sm border border-slate-100 dark:border-slate-800" src="{{ $q->cover_image ? $q->cover_image : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" onerror="this.src='https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg'">
                                <div class="flex-grow space-y-1.5 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full {{ $q->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-850 dark:text-slate-400' }} text-[9px] font-black uppercase tracking-wider leading-none">
                                            {{ $q->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-800 dark:text-white truncate leading-tight">{{ $q->title }}</h4>
                                    <span class="text-xs text-slate-450 dark:text-slate-500 font-bold block leading-none">วิชา: {{ $q->subject_code }} | คะแนน {{ $q->total_score }}</span>
                                    <div class="flex gap-2 pt-1.5">
                                        <a href="{{ route('ttest.edit', $q->id) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-slate-700 dark:text-slate-350 text-[10px] font-black rounded-lg transition-all">แก้ไขแบบทดสอบ</a>
                                        <a href="{{ route('ttest.report.summary', $q->id) }}" class="px-3 py-1 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-[10px] font-black rounded-lg transition-all">รายงานผล</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</x-teachers-layout>

@include('layouts.footer')