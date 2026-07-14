<x-app-layout>
    <style>
        /* Hide default layout nav on this page for cleaner look */
        .timeline-line {
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #e2e8f0, #cbd5e1);
        }
        .timeline-line-filled {
            background: linear-gradient(to bottom, #8b5cf6, #a78bfa);
        }
        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 10;
            position: relative;
            transition: all 0.3s;
        }
        .step-dot.completed {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }
        .step-dot.current {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.3);
            animation: pulse-dot 2s infinite;
        }
        .step-dot.locked {
            background: #e2e8f0;
            border: 2px solid #cbd5e1;
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.3); }
            50% { box-shadow: 0 0 0 8px rgba(139, 92, 246, 0.1); }
        }
    </style>

    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-slate-900">
        <div class="max-w-4xl mx-auto px-4 pt-24 pb-12">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 flex items-center gap-3 mb-6" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 flex items-center gap-3 mb-6" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path></svg>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ===== COURSE HERO HEADER ===== --}}
            <div class="relative rounded-3xl overflow-hidden mb-8 shadow-lg">
                @if ($course->cover_image)
                    <img src="{{ $course->cover_image }}" alt="{{ $course->title }}" class="w-full h-52 sm:h-64 object-cover">
                @else
                    <div class="w-full h-52 sm:h-64 bg-gradient-to-br from-purple-600 via-violet-700 to-indigo-800"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <p class="text-xs font-black uppercase tracking-widest text-purple-300 mb-1">
                        {{ $course->teacher?->name ?? 'ไม่ระบุผู้สอน' }}
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-black leading-tight">{{ $course->title }}</h1>
                    @if($course->description)
                        <p class="text-sm text-white/75 mt-2 line-clamp-2">{{ $course->description }}</p>
                    @endif
                </div>
            </div>

            {{-- ===== ENROLLMENT / ACCESS SECTION ===== --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                @guest
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3.5 px-6 bg-purple-700 hover:bg-purple-800 text-white font-black text-sm rounded-2xl shadow-md transition-all active:scale-95">
                        ล็อกอินเพื่อลงทะเบียนเรียน
                    </a>
                @else
                    @if (!$isEnrolled)
                        <form action="{{ route('classroom.enroll', $course->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-3.5 px-6 bg-purple-700 hover:bg-purple-800 text-white font-black text-sm rounded-2xl shadow-md transition-all active:scale-95">
                                ลงทะเบียนเรียนเลย (ฟรี)
                            </button>
                        </form>
                    @else
                        <a href="{{ route('classroom.access', $course->id) }}" class="flex-1 text-center py-3.5 px-6 bg-gradient-to-r from-purple-600 to-violet-700 hover:from-purple-700 hover:to-violet-800 text-white font-black text-sm rounded-2xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"></path></svg>
                            เข้าเรียนเลย
                        </a>
                    @endif
                @endguest
            </div>

            {{-- ===== PROGRESS OVERVIEW (Enrolled Only) ===== --}}
            @if($isEnrolled && $totalLessons > 0)
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 mb-8 shadow-sm">
                    <div class="flex flex-wrap gap-4 mb-4">
                        {{-- Progress Ring --}}
                        <div class="flex items-center gap-4 flex-1 min-w-[200px]">
                            <div class="relative w-16 h-16 flex-shrink-0">
                                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 36 36">
                                    <path d="M18 2.0845a15.9155 15.9155 0 010 31.831 15.9155 15.9155 0 010-31.831" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                                    <path d="M18 2.0845a15.9155 15.9155 0 010 31.831 15.9155 15.9155 0 010-31.831" fill="none" stroke="url(#progressGrad)" stroke-width="3" stroke-dasharray="{{ $progress }}, 100" stroke-linecap="round"/>
                                    <defs><linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="0%"><stop offset="0%" stop-color="#8b5cf6"/><stop offset="100%" stop-color="#06b6d4"/></linearGradient></defs>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-sm font-black text-slate-800 dark:text-white">{{ $progress }}%</span>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800 dark:text-white">ความคืบหน้าการเรียน</p>
                                <p class="text-xs font-bold text-slate-400">เรียนแล้ว {{ $completedCount }}/{{ $totalLessons }} บทเรียน</p>
                            </div>
                        </div>

                        {{-- Quiz Stats --}}
                        @php
                            $totalQuizzes = $allLessons->where('quiz_id', '!=', null)->count();
                            $passedQuizzes = 0;
                            $failedQuizzes = 0;
                            $notTakenQuizzes = 0;
                            foreach ($allLessons as $l) {
                                if ($l->quiz_id) {
                                    if (isset($quizAttempts[$l->quiz_id])) {
                                        $bestAttempt = $quizAttempts[$l->quiz_id]->first();
                                        if ($bestAttempt->is_passed) $passedQuizzes++;
                                        else $failedQuizzes++;
                                    } else {
                                        $notTakenQuizzes++;
                                    }
                                }
                            }
                        @endphp
                        @if($totalQuizzes > 0)
                        <div class="flex gap-3 flex-1 min-w-[200px]">
                            <div class="flex-1 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl p-3 text-center border border-emerald-100 dark:border-emerald-900/40">
                                <p class="text-lg font-black text-emerald-700 dark:text-emerald-400">{{ $passedQuizzes }}</p>
                                <p class="text-[10px] font-bold text-emerald-600/70">สอบผ่าน</p>
                            </div>
                            <div class="flex-1 bg-rose-50 dark:bg-rose-950/30 rounded-2xl p-3 text-center border border-rose-100 dark:border-rose-900/40">
                                <p class="text-lg font-black text-rose-700 dark:text-rose-400">{{ $failedQuizzes }}</p>
                                <p class="text-[10px] font-bold text-rose-600/70">ไม่ผ่าน</p>
                            </div>
                            <div class="flex-1 bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-3 text-center border border-slate-100 dark:border-slate-800">
                                <p class="text-lg font-black text-slate-600 dark:text-slate-300">{{ $notTakenQuizzes }}</p>
                                <p class="text-[10px] font-bold text-slate-400">ยังไม่ทำ</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-cyan-500 transition-all duration-700" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @endif

            {{-- ===== SHORTS CAROUSEL ===== --}}
            @if(!$shortVideos->isEmpty())
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 mb-8 shadow-sm">
                    <h2 class="text-sm font-black text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center">🎬</span>
                        คลิปแนะนำการเรียนรู้สั้น
                    </h2>
                    <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-purple-200">
                        @foreach($shortVideos as $video)
                            <a href="{{ route('shorts.index', ['id' => $video->id]) }}" class="flex-shrink-0 group relative w-28 h-44 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-750 shadow-sm hover:shadow-md hover:scale-105 transition-all">
                                @if($video->type === 'images' && is_array($video->images) && count($video->images) > 0)
                                    <img src="{{ asset('storage/' . $video->images[0]) }}" class="w-full h-full object-cover">
                                @elseif($video->video_path)
                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center relative">
                                        <video src="{{ asset('storage/' . $video->video_path) }}#t=0.5" class="w-full h-full object-cover" muted></video>
                                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-white/90 drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-purple-100 flex items-center justify-center"><span class="text-xs font-bold text-purple-650">คลิปสั้น</span></div>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent pt-6 pb-2 px-2.5 text-white">
                                    <span class="text-[9px] font-bold truncate block">{{ $video->title }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== LEARNING PATH TIMELINE ===== --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <h2 class="text-sm font-black text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/50 flex items-center justify-center">📋</span>
                    เส้นทางการเรียนรู้
                </h2>

                @if ($allLessons->isEmpty())
                    <div class="text-center py-10">
                        <span class="text-4xl block mb-3">📚</span>
                        <p class="text-sm font-bold text-slate-400">หลักสูตรนี้ยังไม่มีบทเรียน</p>
                    </div>
                @else
                    @php $currentModuleId = null; $stepIndex = 0; @endphp

                    <div class="relative pl-10">
                        {{-- Timeline Line --}}
                        <div class="timeline-line {{ $isEnrolled ? '' : '' }}"></div>

                        @foreach ($allLessons as $lesson)
                            @php
                                $isCompleted = $isEnrolled && $completedLessonIds->contains($lesson->id);
                                $isFirst = $stepIndex === 0;
                                // Current = first uncompleted lesson
                                $isCurrent = false;
                                if ($isEnrolled && !$isCompleted) {
                                    // Check if all previous are completed
                                    $allPrevCompleted = true;
                                    for ($i = 0; $i < $stepIndex; $i++) {
                                        if (!$completedLessonIds->contains($allLessons[$i]->id)) {
                                            $allPrevCompleted = false;
                                            break;
                                        }
                                    }
                                    if ($allPrevCompleted) $isCurrent = true;
                                }

                                $showModuleHeader = ($lesson->module_id !== $currentModuleId);
                                $currentModuleId = $lesson->module_id;
                                $stepIndex++;
                            @endphp

                            {{-- Module divider --}}
                            @if ($showModuleHeader)
                                <div class="mb-4 {{ !$isFirst ? 'mt-6' : '' }}">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-violet-50 dark:bg-violet-950/40 text-violet-700 dark:text-violet-300 text-[10px] font-black uppercase tracking-wider border border-violet-100 dark:border-violet-900/40">
                                        📁 {{ $lesson->module->title }}
                                    </span>
                                </div>
                            @endif

                            {{-- Step Item --}}
                            <div class="relative flex items-start gap-4 mb-6 group">
                                {{-- Dot --}}
                                <div class="step-dot {{ $isCompleted ? 'completed' : ($isCurrent ? 'current' : 'locked') }}" style="margin-left: -25px;">
                                    @if ($isCompleted)
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif ($isCurrent)
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    @else
                                        <span class="text-[10px] font-black text-slate-400">{{ $stepIndex }}</span>
                                    @endif
                                </div>

                                {{-- Content Card --}}
                                <div class="flex-1 bg-slate-50 dark:bg-slate-950/50 rounded-2xl p-4 border border-slate-100 dark:border-slate-800 group-hover:bg-white dark:group-hover:bg-slate-900 group-hover:shadow-sm transition-all {{ $isCurrent ? 'ring-2 ring-purple-200 dark:ring-purple-800' : '' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-black text-slate-800 dark:text-white leading-snug">
                                                {{ $lesson->title }}
                                            </p>
                                            <div class="flex flex-wrap gap-1.5 mt-2">
                                                @if ($lesson->video_url)
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-red-50 text-red-600 dark:bg-red-950/30 dark:text-red-400 border border-red-100 dark:border-red-900/30">▶ วิดีโอ</span>
                                                @endif
                                                @if ($lesson->short_video_id)
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/30">🎥 คลิปสั้น</span>
                                                @endif
                                                @if ($lesson->quiz_id)
                                                    @php
                                                        $quizStatus = 'not_taken';
                                                        if ($isEnrolled && isset($quizAttempts[$lesson->quiz_id])) {
                                                            $best = $quizAttempts[$lesson->quiz_id]->first();
                                                            $quizStatus = $best->is_passed ? 'passed' : 'failed';
                                                        }
                                                    @endphp
                                                    @if ($quizStatus === 'passed')
                                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">✅ สอบผ่าน</span>
                                                    @elseif ($quizStatus === 'failed')
                                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">❌ ไม่ผ่าน</span>
                                                    @else
                                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">📝 แบบทดสอบ</span>
                                                    @endif
                                                @endif
                                                @if ($isCompleted)
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">✓ เรียนจบแล้ว</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($isEnrolled)
                                            <a href="{{ route('classroom.access', $course->id) }}#lesson-{{ $lesson->id }}" class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center transition-all {{ $isCurrent ? 'bg-purple-600 text-white shadow-md hover:bg-purple-700' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-purple-600 hover:bg-purple-50' }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Course Info Footer --}}
            <div class="mt-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                <div class="flex flex-wrap gap-4 text-xs font-bold text-slate-500">
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> เข้าถึงได้ตลอดชีพ</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> วิดีโอคุณภาพสูง</span>
                    <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> แบบทดสอบท้ายบท</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>