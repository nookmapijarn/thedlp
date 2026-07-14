<x-app-layout>
    <style>
        /* Hide nav, full immersive */
        nav { display: none !important; }
        footer { display: none !important; }
        .ml-0.sm\:ml-72 { margin-left: 0 !important; }
        @media (min-width: 640px) { .ml-0.sm\:ml-72 { margin-left: 0 !important; } }
        main > div.max-w-7xl { padding: 0 !important; max-width: 100% !important; }
        body { overflow: hidden; }

        /* Snap scroll */
        .lesson-scroll { scroll-snap-type: y mandatory; -ms-overflow-style: none; scrollbar-width: none; }
        .lesson-scroll::-webkit-scrollbar { display: none; }
        .lesson-slide { scroll-snap-align: start; scroll-snap-stop: always; }

        /* 16:9 video */
        .yt-container { position: relative; width: 100%; height: 100%; }
        .yt-container iframe { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; height: 100%; }

        /* Quiz pulse */
        @keyframes qp { 0%,100% { box-shadow: 0 0 0 0 rgba(168,85,247,0.5); } 70% { box-shadow: 0 0 0 12px rgba(168,85,247,0); } }
        .quiz-pulse { animation: qp 2s infinite; }

        /* Auto-complete toast */
        @keyframes toast-in { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .toast-anim { animation: toast-in 0.4s ease-out; }

        /* Text content scroll inside overlay */
        .content-scroll { -ms-overflow-style: none; scrollbar-width: none; max-height: 40vh; }
        .content-scroll::-webkit-scrollbar { display: none; }

        /* Image carousel */
        .img-carousel { -ms-overflow-style: none; scrollbar-width: none; }
        .img-carousel::-webkit-scrollbar { display: none; }
    </style>

    <div class="fixed inset-0 bg-black flex flex-col z-40">

        {{-- ===== TOP BAR (overlay) ===== --}}
        <div class="absolute top-0 inset-x-0 z-50 bg-gradient-to-b from-black/70 via-black/30 to-transparent px-4 pt-3 pb-8 flex items-center gap-3 pointer-events-none">
            <a href="{{ route('classroom.show', $course->id) }}" class="pointer-events-auto w-9 h-9 rounded-full bg-white/15 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/25 transition-all flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path></svg>
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-white truncate drop-shadow-lg">{{ $course->title }}</p>
                <p class="text-[10px] font-bold text-white/60" id="lesson-label">บทที่ 1/{{ $allLessons->count() }}</p>
            </div>
            <div class="pointer-events-auto flex items-center gap-2 flex-shrink-0">
                <span class="text-[11px] font-black text-white drop-shadow-lg" id="progress-text">{{ $progress }}%</span>
                <div class="w-16 h-1.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-purple-400 to-cyan-400 transition-all duration-500" id="progress-bar" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        {{-- ===== MAIN SCROLL ===== --}}
        <div id="lesson-container" class="flex-1 overflow-y-scroll lesson-scroll">
            @foreach ($allLessons as $index => $lesson)
                @php
                    $isCompleted = $completedLessons->contains('lesson_id', $lesson->id);
                    $videoId = '';
                    if ($lesson->video_url) {
                        parse_str(parse_url($lesson->video_url, PHP_URL_QUERY) ?? '', $p);
                        $videoId = $p['v'] ?? '';
                    }
                    $short = $lesson->shortVideo;
                    $hasMedia = $videoId || $short;
                @endphp

                <div class="lesson-slide w-full h-full flex-shrink-0 relative bg-black" id="lesson-{{ $lesson->id }}" data-index="{{ $index }}" data-lesson-id="{{ $lesson->id }}" data-completed="{{ $isCompleted ? '1' : '0' }}">

                    {{-- ===== BACKGROUND: Full-screen media ===== --}}
                    <div class="absolute inset-0">
                        @if ($short && $short->video_path)
                            {{-- Short Video full screen --}}
                            <video class="w-full h-full object-cover short-vid" loop playsinline preload="metadata" muted>
                                <source src="{{ asset('storage/' . $short->video_path) }}" type="video/mp4">
                            </video>
                        @elseif ($short && $short->type === 'images' && is_array($short->images) && count($short->images) > 0)
                            {{-- Image carousel full screen --}}
                            <div class="w-full h-full flex overflow-x-scroll snap-x snap-mandatory img-carousel scroll-smooth" id="img-carousel-{{ $lesson->id }}">
                                @foreach ($short->images as $imgIdx => $imgPath)
                                    <div class="w-full h-full flex-shrink-0 snap-start snap-always">
                                        <img src="{{ asset('storage/' . $imgPath) }}" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                            @if (count($short->images) > 1)
                                <div class="absolute top-16 right-4 bg-black/50 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-black text-white z-20">
                                    <span class="img-counter">1</span>/{{ count($short->images) }}
                                </div>
                            @endif
                            @if ($short->audio_path)
                                <audio class="slide-audio" loop src="{{ asset('storage/' . $short->audio_path) }}" preload="auto"></audio>
                            @endif
                        @elseif ($videoId)
                            {{-- YouTube full screen --}}
                            <div class="yt-container">
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&rel=0&modestbranding=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @else
                            {{-- No media: gradient bg --}}
                            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-purple-950 to-indigo-950"></div>
                        @endif
                    </div>

                    {{-- Tap to play/pause overlay --}}
                    @if ($short && $short->video_path)
                        <div class="absolute inset-0 z-10 tap-overlay cursor-pointer" data-slide="{{ $index }}"></div>
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
                            <div class="play-indicator opacity-0 scale-75 transition-all duration-200 bg-black/40 backdrop-blur-sm p-5 rounded-full">
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @endif

                    {{-- ===== OVERLAY: Bottom gradient + content ===== --}}
                    <div class="absolute inset-x-0 bottom-0 z-20 pointer-events-none">
                        <div class="bg-gradient-to-t from-black/90 via-black/50 to-transparent pt-24 pb-5 px-4">

                            {{-- Lesson title + module --}}
                            <div class="pointer-events-auto mb-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[9px] font-black flex-shrink-0 {{ $isCompleted ? 'bg-emerald-500/30 text-emerald-300' : 'bg-white/15 text-white/80' }}" id="badge-{{ $lesson->id }}">
                                        @if ($isCompleted) ✓ @else {{ $index + 1 }} @endif
                                    </span>
                                    <p class="text-sm font-black text-white drop-shadow-lg leading-snug">{{ $lesson->title }}</p>
                                </div>
                                <p class="text-[10px] font-bold text-white/50 ml-8">📁 {{ $lesson->module->title }}</p>
                            </div>

                            {{-- Text content (collapsible) --}}
                            @if ($lesson->content)
                                <div class="pointer-events-auto mb-3 ml-8" id="content-wrap-{{ $lesson->id }}">
                                    @php
                                        $rawContent = e($lesson->content);
                                        $contentHtml = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" class="text-purple-300 underline" target="_blank">$1</a>', $rawContent);
                                        $contentHtml = nl2br($contentHtml);
                                        $isLong = strlen($lesson->content) > 100;
                                    @endphp

                                    <div class="content-scroll overflow-y-auto {{ $isLong ? 'max-h-[60px]' : '' }} transition-all duration-300" id="content-{{ $lesson->id }}">
                                        <p class="text-xs text-white/80 font-medium leading-relaxed">{!! $contentHtml !!}</p>
                                    </div>

                                    @if ($isLong)
                                        <button onclick="toggleContent({{ $lesson->id }})" class="text-[10px] font-black text-white/60 hover:text-white mt-1 transition-colors" id="toggle-btn-{{ $lesson->id }}">
                                            ดูเพิ่มเติม ▼
                                        </button>
                                    @endif
                                </div>
                            @endif

                            {{-- Action buttons --}}
                            <div class="pointer-events-auto flex items-center gap-2 ml-8">
                                @if ($lesson->quiz_id)
                                    <a href="{{ route('quizzes.start', $lesson->quiz_id) }}" class="flex items-center gap-1.5 py-2.5 px-4 bg-purple-600/80 hover:bg-purple-600 backdrop-blur-sm text-white font-black text-[11px] rounded-full shadow-lg transition-all active:scale-95 quiz-pulse">
                                        📝 ทำแบบทดสอบ
                                    </a>
                                @endif

                                <button id="complete-btn-{{ $lesson->id }}" onclick="markComplete(this, {{ $lesson->id }})" class="flex items-center gap-1.5 py-2.5 px-4 backdrop-blur-sm font-black text-[11px] rounded-full shadow-lg transition-all active:scale-95 {{ $isCompleted ? 'bg-emerald-500/30 text-emerald-300 cursor-default' : 'bg-white/15 hover:bg-white/25 text-white' }}" {{ $isCompleted ? 'disabled' : '' }}>
                                    @if ($isCompleted)
                                        ✓ เรียนจบแล้ว
                                    @else
                                        ☑ จบบทเรียนนี้
                                    @endif
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- ===== RIGHT SIDEBAR (TikTok-style) ===== --}}
                    <div class="absolute right-3 bottom-44 z-30 flex flex-col items-center gap-5">
                        {{-- Like/Bookmark style indicators --}}
                        @if ($lesson->quiz_id)
                            <a href="{{ route('quizzes.start', $lesson->quiz_id) }}" class="flex flex-col items-center gap-0.5 group">
                                <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-md flex items-center justify-center group-hover:bg-purple-500/50 transition-all">
                                    <span class="text-lg">📝</span>
                                </div>
                                <span class="text-[9px] font-bold text-white/70">ข้อสอบ</span>
                            </a>
                        @endif
                        @if ($lesson->short_video_id && $short)
                            <div class="flex flex-col items-center gap-0.5">
                                <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-md flex items-center justify-center">
                                    <span class="text-lg">🎬</span>
                                </div>
                                <span class="text-[9px] font-bold text-white/70">คลิป</span>
                            </div>
                        @endif
                        @if ($lesson->video_url)
                            <div class="flex flex-col items-center gap-0.5">
                                <div class="w-10 h-10 rounded-full bg-white/15 backdrop-blur-md flex items-center justify-center">
                                    <span class="text-lg">▶️</span>
                                </div>
                                <span class="text-[9px] font-bold text-white/70">วิดีโอ</span>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

        {{-- ===== SIDE DOTS (Desktop nav) ===== --}}
        <div class="hidden sm:flex fixed right-3 top-1/2 -translate-y-1/2 flex-col gap-1.5 z-50">
            @foreach ($allLessons as $idx => $l)
                @php $done = $completedLessons->contains('lesson_id', $l->id); @endphp
                <button onclick="goSlide({{ $idx }})" class="side-dot w-2.5 h-2.5 rounded-full transition-all hover:scale-150 {{ $done ? 'bg-emerald-400' : 'bg-white/30' }}" data-index="{{ $idx }}" title="{{ $l->title }}"></button>
            @endforeach
        </div>

        {{-- Auto-complete toast --}}
        <div id="auto-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[60] hidden">
            <div class="bg-emerald-500/90 backdrop-blur-md text-white text-xs font-black px-5 py-2.5 rounded-full shadow-xl toast-anim flex items-center gap-2">
                ✓ บทเรียนนี้เรียนจบแล้ว
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('lesson-container');
        const slides = document.querySelectorAll('.lesson-slide');
        const sideDots = document.querySelectorAll('.side-dot');
        const total = slides.length;
        let currentIdx = 0;
        let viewTimers = {};  // Track time spent on each slide

        // ============ NAVIGATION ============
        function goSlide(i) {
            if (slides[i]) slides[i].scrollIntoView({ behavior: 'smooth' });
        }

        container.addEventListener('scroll', () => {
            const newIdx = Math.round(container.scrollTop / container.clientHeight);
            if (newIdx !== currentIdx && newIdx >= 0 && newIdx < total) {
                onLeaveSlide(currentIdx);
                currentIdx = newIdx;
                onEnterSlide(currentIdx);
                updateUI();
            }
        });

        function updateUI() {
            // Side dots
            sideDots.forEach((d, i) => {
                d.classList.toggle('scale-150', i === currentIdx);
                d.classList.toggle('ring-2', i === currentIdx);
                d.classList.toggle('ring-purple-400', i === currentIdx);
                if (i === currentIdx && !d.classList.contains('bg-emerald-400')) {
                    d.classList.add('bg-white/80');
                    d.classList.remove('bg-white/30');
                } else if (!d.classList.contains('bg-emerald-400')) {
                    d.classList.remove('bg-white/80');
                    d.classList.add('bg-white/30');
                }
            });
            // Top label
            document.getElementById('lesson-label').textContent = `บทที่ ${currentIdx + 1}/${total}`;
        }

        // ============ SLIDE ENTER/LEAVE ============
        function onEnterSlide(idx) {
            const slide = slides[idx];
            if (!slide) return;

            // Play video
            const vid = slide.querySelector('.short-vid');
            if (vid) { vid.muted = false; vid.play().catch(() => {}); }

            // Play audio
            const aud = slide.querySelector('.slide-audio');
            if (aud) aud.play().catch(() => {});

            // Start view timer for auto-complete (3 seconds)
            const lessonId = slide.dataset.lessonId;
            const isCompleted = slide.dataset.completed === '1';
            if (!isCompleted) {
                viewTimers[idx] = setTimeout(() => {
                    autoComplete(lessonId, slide);
                }, 3000);
            }
        }

        function onLeaveSlide(idx) {
            const slide = slides[idx];
            if (!slide) return;

            // Pause video
            const vid = slide.querySelector('.short-vid');
            if (vid) { vid.pause(); }

            // Pause audio
            const aud = slide.querySelector('.slide-audio');
            if (aud) aud.pause();

            // Clear timer
            if (viewTimers[idx]) {
                clearTimeout(viewTimers[idx]);
                delete viewTimers[idx];
            }
        }

        // Also auto-complete when video ends
        document.querySelectorAll('.short-vid').forEach(vid => {
            vid.addEventListener('ended', () => {
                const slide = vid.closest('.lesson-slide');
                if (slide && slide.dataset.completed !== '1') {
                    autoComplete(slide.dataset.lessonId, slide);
                }
                // Loop back
                vid.play().catch(() => {});
            });
        });

        // ============ TAP TO PLAY/PAUSE ============
        document.querySelectorAll('.tap-overlay').forEach(overlay => {
            overlay.addEventListener('click', () => {
                const slide = overlay.closest('.lesson-slide');
                const vid = slide.querySelector('.short-vid');
                const indicator = slide.querySelector('.play-indicator');
                if (!vid) return;

                if (vid.paused) {
                    vid.play();
                    if (indicator) { indicator.classList.add('opacity-0', 'scale-75'); indicator.classList.remove('opacity-100', 'scale-100'); }
                } else {
                    vid.pause();
                    if (indicator) {
                        indicator.classList.remove('opacity-0', 'scale-75');
                        indicator.classList.add('opacity-100', 'scale-100');
                        setTimeout(() => { indicator.classList.add('opacity-0', 'scale-75'); indicator.classList.remove('opacity-100', 'scale-100'); }, 1500);
                    }
                }
            });
        });

        // ============ AUTO COMPLETE ============
        function autoComplete(lessonId, slide) {
            if (slide.dataset.completed === '1') return;
            slide.dataset.completed = '1';

            fetch(`/lessons/${lessonId}/complete`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Update progress
                    document.getElementById('progress-bar').style.width = data.progress + '%';
                    document.getElementById('progress-text').textContent = data.progress + '%';

                    // Update badge
                    const badge = document.getElementById('badge-' + lessonId);
                    if (badge) { badge.textContent = '✓'; badge.className = 'w-6 h-6 rounded-lg flex items-center justify-center text-[9px] font-black flex-shrink-0 bg-emerald-500/30 text-emerald-300'; }

                    // Update button
                    const btn = document.getElementById('complete-btn-' + lessonId);
                    if (btn) { btn.innerHTML = '✓ เรียนจบแล้ว'; btn.disabled = true; btn.className = 'flex items-center gap-1.5 py-2.5 px-4 backdrop-blur-sm font-black text-[11px] rounded-full shadow-lg transition-all bg-emerald-500/30 text-emerald-300 cursor-default'; }

                    // Update side dot
                    const idx = parseInt(slide.dataset.index);
                    if (sideDots[idx]) { sideDots[idx].classList.remove('bg-white/30', 'bg-white/80'); sideDots[idx].classList.add('bg-emerald-400'); }

                    // Show toast
                    showToast();
                }
            })
            .catch(e => console.error('Auto-complete error:', e));
        }

        function markComplete(btn, lessonId) {
            if (btn.disabled) return;
            const slide = document.getElementById('lesson-' + lessonId);
            autoComplete(lessonId, slide);
        }

        function showToast() {
            const toast = document.getElementById('auto-toast');
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        }

        // ============ TOGGLE CONTENT ============
        function toggleContent(id) {
            const el = document.getElementById('content-' + id);
            const btn = document.getElementById('toggle-btn-' + id);
            if (el.classList.contains('max-h-[60px]')) {
                el.classList.remove('max-h-[60px]');
                el.classList.add('max-h-[40vh]');
                btn.textContent = 'ย่อ ▲';
            } else {
                el.classList.add('max-h-[60px]');
                el.classList.remove('max-h-[40vh]');
                btn.textContent = 'ดูเพิ่มเติม ▼';
            }
        }

        // ============ IMAGE CAROUSEL INDEX ============
        document.querySelectorAll('.img-carousel').forEach(carousel => {
            const slide = carousel.closest('.lesson-slide');
            const counter = slide.querySelector('.img-counter');
            if (!counter) return;
            carousel.addEventListener('scroll', () => {
                const idx = Math.round(carousel.scrollLeft / carousel.clientWidth) + 1;
                counter.textContent = idx;
            });
        });

        // ============ HASH NAV + INIT ============
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            if (hash) {
                const target = document.querySelector(hash);
                if (target) setTimeout(() => target.scrollIntoView({ behavior: 'smooth' }), 300);
            }
            onEnterSlide(0);
            updateUI();
        });

        // ============ HEARTBEAT ============
        (function() {
            let sid = null;
            const cid = {{ $course->id }};
            function ping() {
                fetch('/study-session/ping', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body: JSON.stringify({ course_id: cid, type: 'classroom', session_id: sid })
                }).then(r => r.json()).then(d => { if (d.success && d.session_id) sid = d.session_id; }).catch(() => {});
            }
            ping(); setInterval(ping, 15000);
        })();
    </script>
</x-app-layout>