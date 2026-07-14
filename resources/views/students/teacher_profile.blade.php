<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased pb-20">
        
        {{-- Top App Bar Overlay --}}
        <div class="sticky top-0 bg-slate-900/80 backdrop-blur-md border-b border-white/5 z-40 px-4 py-3.5 flex items-center justify-between">
            <a href="{{ route('shorts.index') }}" class="p-2 bg-white/5 hover:bg-white/10 rounded-full text-white/80 transition-all focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <span class="text-sm font-black tracking-tight">โปรไฟล์คุณครู</span>
            <div class="w-9 h-9"></div> {{-- Spacer to balance --}}
        </div>

        <div class="max-w-md mx-auto px-4 pt-6">
            
            {{-- Teacher Profile Header Info --}}
            <div class="flex flex-col items-center text-center space-y-4">
                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-rose-500 via-purple-600 to-indigo-500 shadow-xl">
                        <div class="w-full h-full rounded-full overflow-hidden border-2 border-slate-950 bg-slate-900">
                            <img src="{{ $teacher->avatar ? asset('storage/' . $teacher->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    <span class="absolute bottom-0 right-1 bg-blue-500 text-white rounded-full p-1 border-2 border-slate-950">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </span>
                </div>

                {{-- Name & Handle --}}
                <div class="space-y-1">
                    <h2 class="text-lg font-black text-white flex items-center justify-center gap-1.5">
                        {{ $teacher->name }}
                    </h2>
                    <p class="text-xs text-slate-400 font-bold">@teacher_{{ $teacher->id }}</p>
                </div>

                {{-- Bio Description --}}
                <p class="text-xs text-slate-350 leading-relaxed max-w-sm font-semibold">
                    {{ $teacher->bio ?? 'ร่วมส่งมอบประสบการณ์การเรียนรู้ออนไลน์ที่ดีที่สุดให้กับทุกคน 🚀' }}
                </p>

                {{-- TikTok style Stats count --}}
                <div class="flex items-center justify-center gap-8 py-3.5 border-y border-white/5 w-full max-w-xs text-center">
                    <div>
                        <div class="text-base font-black text-white">{{ $shorts->count() }}</div>
                        <div class="text-[10px] text-slate-450 font-bold uppercase tracking-wider mt-0.5">คลิปสั้น</div>
                    </div>
                    <div>
                        <div class="text-base font-black text-white">{{ $courses->count() }}</div>
                        <div class="text-[10px] text-slate-450 font-bold uppercase tracking-wider mt-0.5">หลักสูตร</div>
                    </div>
                    <div>
                        <div class="text-base font-black text-white">{{ number_format($totalLikes) }}</div>
                        <div class="text-[10px] text-slate-450 font-bold uppercase tracking-wider mt-0.5">ยอดถูกใจ</div>
                    </div>
                </div>
            </div>

            {{-- TikTok Profile Tabs Navigation --}}
            <div x-data="{ activeTab: 'shorts' }" class="mt-8">
                
                {{-- Segmented Tab bar --}}
                <div class="flex border-b border-white/5 relative">
                    <button @click="activeTab = 'shorts'" 
                            :class="activeTab === 'shorts' ? 'text-white border-b-2 border-white font-black' : 'text-slate-450 font-bold'"
                            class="flex-1 py-3 text-center text-xs transition-all focus:outline-none flex items-center justify-center gap-1">
                        🎬 คลิป/โพสต์
                    </button>
                    <button @click="activeTab = 'courses'" 
                            :class="activeTab === 'courses' ? 'text-white border-b-2 border-white font-black' : 'text-slate-450 font-bold'"
                            class="flex-1 py-3 text-center text-xs transition-all focus:outline-none flex items-center justify-center gap-1">
                        📚 หลักสูตร
                    </button>
                    <button @click="activeTab = 'quizzes'" 
                            :class="activeTab === 'quizzes' ? 'text-white border-b-2 border-white font-black' : 'text-slate-450 font-bold'"
                            class="flex-1 py-3 text-center text-xs transition-all focus:outline-none flex items-center justify-center gap-1">
                        📝 ข้อสอบ
                    </button>
                </div>

                {{-- Tabs Contents --}}
                <div class="mt-4">
                    
                    {{-- 1. SHORTS GRID TAB --}}
                    <div x-show="activeTab === 'shorts'" x-transition.opacity class="animate-in fade-in duration-300">
                        @if($shorts->isNotEmpty())
                            <div class="grid grid-cols-3 gap-1">
                                @foreach($shorts as $short)
                                    <a href="{{ route('shorts.index', ['id' => $short->id]) }}" 
                                       class="relative aspect-[3/4] bg-slate-900 overflow-hidden group border border-white/5 rounded-lg active:scale-95 transition-all">
                                        
                                        {{-- Thumbnail rendering --}}
                                        @if($short->type === 'video')
                                            <div class="w-full h-full bg-slate-900 relative">
                                                {{-- Background placeholder icon --}}
                                                <div class="absolute inset-0 flex items-center justify-center text-white/10 z-0">
                                                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </div>
                                                <video src="{{ asset('storage/' . $short->video_path) }}" preload="metadata" class="w-full h-full object-cover relative z-10" muted playsinline></video>
                                            </div>
                                        @else
                                            @if(is_array($short->images) && count($short->images) > 0)
                                                <img src="{{ asset('storage/' . $short->images[0]) }}" class="w-full h-full object-cover">
                                                <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md px-1.5 py-0.5 rounded text-[8px] font-black text-white z-20">
                                                    🖼️ {{ count($short->images) }}
                                                </div>
                                            @else
                                                <div class="w-full h-full bg-slate-900 flex items-center justify-center text-slate-500">
                                                    📚
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Title & Like view counters overlay --}}
                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/35 to-transparent p-2 flex flex-col justify-end z-20">
                                            <p class="text-[9px] text-white/90 font-bold truncate">{{ $short->title }}</p>
                                            <div class="flex items-center gap-1 mt-0.5 text-white/70 text-[8px] font-bold">
                                                <svg class="w-2.5 h-2.5 text-rose-500 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                </svg>
                                                <span>{{ $short->likes_count }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-16 text-slate-500 text-center">
                                <span class="text-3xl mb-2">🎬</span>
                                <p class="text-xs font-bold">ยังไม่ได้เผยแพร่คลิปวิดีโอ</p>
                            </div>
                        @endif
                    </div>

                    {{-- 2. COURSES TAB --}}
                    <div x-show="activeTab === 'courses'" x-transition.opacity class="space-y-3.5 animate-in fade-in duration-300">
                        @if($courses->isNotEmpty())
                            @foreach($courses as $course)
                                <a href="{{ route('classroom.show', $course->id) }}" 
                                   class="flex gap-3 bg-white/5 border border-white/5 hover:bg-white/10 rounded-2xl p-3 transition-all active:scale-[0.98] items-center">
                                    
                                    {{-- Course Cover --}}
                                    <div class="w-16 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-slate-900 border border-white/5">
                                        @if($course->cover_image)
                                            <img src="{{ $course->cover_image }}" alt="Cover" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xl">
                                                📚
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Course text details --}}
                                    <div class="flex-1 min-w-0 space-y-1">
                                        <span class="inline-flex px-1.5 py-0.5 bg-indigo-500/10 text-indigo-400 text-[8px] font-black rounded-md uppercase tracking-wider">
                                            {{ $course->subject_code ?? 'ONLINE' }}
                                        </span>
                                        <h4 class="text-xs font-black text-white truncate">{{ $course->title }}</h4>
                                        <p class="text-[9px] text-slate-400 font-medium line-clamp-1 leading-relaxed">
                                            {{ $course->description ?? 'ไม่มีคำอธิบายเพิ่มเติมสำหรับหลักสูตรนี้' }}
                                        </p>
                                        <div class="flex items-center gap-3 pt-1 text-[9px] text-slate-550 font-bold">
                                            <span>📝 {{ $course->lessons_count ?? $course->modules->flatMap->lessons->count() }} บทเรียน</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center py-16 text-slate-500 text-center">
                                <span class="text-3xl mb-2">📚</span>
                                <p class="text-xs font-bold">ยังไม่มีหลักสูตรออนไลน์ที่เปิดรับสมัคร</p>
                            </div>
                        @endif
                    </div>

                    {{-- 3. QUIZZES TAB --}}
                    <div x-show="activeTab === 'quizzes'" x-transition.opacity class="space-y-3.5 animate-in fade-in duration-300">
                        @if($quizzes->isNotEmpty())
                            @foreach($quizzes as $quiz)
                                <div class="bg-white/5 border border-white/5 rounded-2xl p-4.5 flex items-center justify-between gap-4">
                                    <div class="space-y-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 bg-rose-500/10 text-rose-400 text-[8px] font-black rounded uppercase tracking-wider">
                                                {{ $quiz->subject_table_type ?? 'TEST' }}
                                            </span>
                                            <span class="text-[8px] text-slate-400 font-bold">{{ $quiz->time_limit }} นาที</span>
                                        </div>
                                        <h4 class="text-xs font-black text-white truncate">{{ $quiz->title }}</h4>
                                        <p class="text-[9px] text-slate-400 font-medium truncate leading-relaxed">
                                            {{ $quiz->description ?? 'แบบทดสอบออนไลน์เพื่อทบทวนความรู้' }}
                                        </p>
                                    </div>

                                    <a href="{{ route('quizzes.start', $quiz->id) }}" 
                                       class="flex-shrink-0 px-4 py-2 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white rounded-xl text-[10px] font-black transition-all shadow-md active:scale-95">
                                        ทำข้อสอบ
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center py-16 text-slate-500 text-center">
                                <span class="text-3xl mb-2">📝</span>
                                <p class="text-xs font-bold">ยังไม่มีแบบทดสอบแบบอิสระในขณะนี้</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
