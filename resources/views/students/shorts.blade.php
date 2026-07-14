<x-app-layout>
    <!-- Style Override to completely hide navigation and layout padding only for this route -->
    <style>
        /* Hide top navigation bar */
        nav {
            display: none !important;
        }
        
        /* Hide footer if any */
        footer {
            display: none !important;
        }

        /* Adjust main layout margins to take full screen height */
        .ml-0.sm\:ml-72 {
            margin-left: 0 !important;
        }
        @media (min-width: 640px) {
            .ml-0.sm\:ml-72 {
                margin-left: 18rem !important; /* 72 = 18rem */
            }
        }
        
        /* Remove default main layout paddings for full height experience */
        main > div.max-w-7xl {
            padding: 0 !important;
            max-width: 100% !important;
        }
    </style>

    <div class="fixed inset-x-0 top-0 bottom-16 sm:bottom-0 sm:left-72 bg-slate-950 flex items-center justify-center z-30">
        <div class="w-full h-full max-w-[480px] bg-black relative flex flex-col sm:border-x sm:border-slate-800">
            <!-- Scroll container -->
            <div id="shorts-container" class="flex-1 overflow-y-scroll snap-y snap-mandatory relative" style="-ms-overflow-style: none; scrollbar-width: none;">
                @forelse($shorts as $index => $short)
                    <div class="w-full h-full snap-start snap-always relative flex-shrink-0 short-video-slide" data-id="{{ $short->id }}" data-index="{{ $index }}" data-course-id="{{ $short->course_id }}">
                        
                        @if($short->type === 'images')
                            <!-- Image slideshow element -->
                            <div class="w-full h-full relative overflow-hidden bg-slate-950 flex items-center justify-center">
                                <div class="w-full h-full flex overflow-x-scroll snap-x snap-mandatory scrollbar-none scroll-smooth items-center carousel-images-container">
                                    @if(is_array($short->images))
                                        @foreach($short->images as $imgPath)
                                            <div class="w-full h-full flex-shrink-0 snap-start snap-always">
                                                <img src="{{ asset('storage/' . $imgPath) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <!-- Background Audio Player -->
                                @if($short->audio_path)
                                    <audio class="short-audio-player" loop src="{{ asset('storage/' . $short->audio_path) }}" preload="auto"></audio>
                                @endif

                                <!-- Floating Image Index Counter (e.g. 1/3) -->
                                <div class="absolute top-24 right-4 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-black text-white tracking-wider z-20">
                                    <span class="carousel-index-indicator">1</span> / {{ is_array($short->images) ? count($short->images) : 0 }}
                                </div>

                                <!-- Left/Right Indicator Arrows -->
                                @if(is_array($short->images) && count($short->images) > 1)
                                    <div class="absolute inset-x-2 top-1/2 -translate-y-1/2 flex justify-between pointer-events-none z-20">
                                        <button type="button" onclick="scrollCarousel(this, -1)" class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white pointer-events-auto hover:bg-black/75 transition-all shadow-md active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                                        </button>
                                        <button type="button" onclick="scrollCarousel(this, 1)" class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center text-white pointer-events-auto hover:bg-black/75 transition-all shadow-md active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Play/Pause Tap Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center cursor-pointer video-tap-area z-10">
                                <!-- Play indicator -->
                                <div class="play-icon-overlay opacity-0 scale-75 pointer-events-none transition-all duration-300 bg-black/40 backdrop-blur-sm p-4 rounded-full hidden">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @else
                            <!-- Video element -->
                            <video class="w-full h-full object-contain bg-black short-video-player" loop playsinline preload="metadata">
                                <source src="{{ asset('storage/' . $short->video_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>

                            <!-- Play/Pause Tap Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center cursor-pointer video-tap-area z-10">
                                <!-- Play indicator -->
                                <div class="play-icon-overlay opacity-0 scale-75 pointer-events-none transition-all duration-300 bg-black/40 backdrop-blur-sm p-4 rounded-full hidden">
                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif

                        <!-- Double tap heart animation container -->
                        <div class="absolute inset-0 pointer-events-none z-20 flex items-center justify-center overflow-hidden heart-burst-container"></div>

                        <!-- Right Actions Sidebar -->
                        <div class="absolute right-3.5 bottom-24 flex flex-col items-center gap-4.5 z-30">
                            <!-- Teacher Profile -->
                            <div class="flex flex-col items-center">
                                <div class="relative w-11 h-11 rounded-full border-2 border-purple-500 overflow-hidden shadow-md bg-slate-900">
                                    <img src="{{ ($short->teacher && $short->teacher->avatar) ? asset('storage/' . $short->teacher->avatar) : 'https://as1.ftcdn.net/v2/jpg/03/46/83/96/1000_F_346839683_6nAPzbhpSkIpb8pmAwufkC7c5eD7wYws.jpg' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-[9px] text-white font-bold bg-purple-600 px-1.5 py-0.5 rounded-full -mt-2.5 z-10 shadow-sm border border-white/20">ครู</span>
                            </div>

                            <!-- Like button -->
                            <div class="flex flex-col items-center">
                                <button class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-all border border-white/10 active:scale-90 btn-like" data-id="{{ $short->id }}" data-liked="{{ $short->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                    <svg class="w-5.5 h-5.5 svg-heart {{ $short->isLikedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-white' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <span class="text-[10px] text-white font-black drop-shadow-md mt-1 like-count">{{ $short->likes_count }}</span>
                            </div>

                            <!-- Course redirect button -->
                            @if($short->course)
                                <div class="flex flex-col items-center">
                                    <a href="{{ route('classroom.show', $short->course_id) }}" class="w-11 h-11 rounded-full bg-purple-600/90 backdrop-blur-md flex items-center justify-center text-white hover:bg-purple-700 transition-all border border-white/20 shadow-lg active:scale-90" title="เรียนรู้เพิ่มเติม">
                                        <svg class="w-5.5 h-5.5 text-white animate-bounce" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </a>
                                    <span class="text-[9px] text-white font-bold drop-shadow-md mt-1">บทเรียน</span>
                                </div>
                            @endif

                            <!-- Quiz prompt button -->
                            @php
                                $lessonWithQuiz = $short->lessons->whereNotNull('quiz_id')->first();
                            @endphp
                            @if($lessonWithQuiz)
                                <div class="flex flex-col items-center">
                                    <a href="{{ route('quizzes.start', $lessonWithQuiz->quiz_id) }}" class="w-11 h-11 rounded-full bg-rose-600/90 backdrop-blur-md flex items-center justify-center text-white hover:bg-rose-700 transition-all border border-white/20 shadow-lg active:scale-95 animate-pulse animate-duration-1000" title="ทำแบบทดสอบ">
                                        <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <span class="text-[9px] text-white font-bold drop-shadow-md mt-1">ทำข้อสอบ</span>
                                </div>
                            @endif

                            <!-- Share/Copy Link button -->
                            <div class="flex flex-col items-center">
                                <button class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white hover:bg-black/60 transition-all border border-white/10 active:scale-90 btn-share" data-id="{{ $short->id }}">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 10.742l4.636-2.318m0 4.152l-4.636-2.318M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <span class="text-[9px] text-white font-bold drop-shadow-md mt-1">แชร์</span>
                            </div>
                        </div>

                        <!-- Bottom Info Overlay -->
                        <div class="absolute left-4 right-20 bottom-6 z-30 flex flex-col text-white">
                            <h4 class="font-black text-sm drop-shadow-md flex items-center gap-1.5">
                                {{ $short->teacher?->name ?? 'คุณครู' }}
                                <svg class="w-3.5 h-3.5 text-blue-400 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </h4>
                            <p class="text-xs font-bold mt-1.5 drop-shadow-md leading-relaxed">
                                {{ $short->title }}
                            </p>
                            @if($short->description)
                                <p class="text-[10px] text-slate-200 mt-1 font-semibold drop-shadow-md leading-relaxed">
                                    {{ $short->description }}
                                </p>
                            @endif

                            <!-- Course connection badge -->
                            @if($short->course)
                                <a href="{{ route('classroom.show', $short->course_id) }}" class="mt-3 inline-flex items-center gap-1.5 bg-purple-600/30 hover:bg-purple-600/50 backdrop-blur-md border border-purple-500/30 px-3 py-1.5 rounded-xl text-[10px] font-bold text-purple-200 transition-all w-fit shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                    หลักสูตร: {{ $short->course->title }}
                                </a>
                            @endif
                        </div>

                        <!-- Shadow Overlays -->
                        <div class="absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-black/90 via-black/40 to-transparent pointer-events-none z-20"></div>
                        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-black/60 to-transparent pointer-events-none z-20"></div>

                        <!-- Custom Seekbar Slider -->
                        <div class="absolute bottom-0 inset-x-0 h-5 flex items-end pb-1.5 px-4 z-40 group cursor-pointer progress-bar-container">
                            <div class="w-full h-1 bg-white/25 rounded-full relative progress-bar-bg group-hover:h-1.5 transition-all">
                                <div class="absolute top-0 left-0 bottom-0 bg-purple-600 rounded-full progress-bar-fill w-0"></div>
                                <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-3 h-3 rounded-full bg-white opacity-0 group-hover:opacity-100 transition-opacity progress-bar-handle" style="left: 0%;"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-6 text-white bg-slate-950">
                        <svg class="w-14 h-14 text-slate-700 mb-3 animate-pulse" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                        <h4 class="font-black text-sm text-slate-300">ยังไม่มีคลิปบทเรียนสั้นในขณะนี้</h4>
                        <p class="text-xs text-slate-500 mt-1">คอยติดตามความรู้ใหม่ๆ จากคณะอาจารย์เร็วๆ นี้</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Toast Notification Widget -->
    <div id="toast-notify" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900/90 backdrop-blur-md text-white text-xs font-bold px-4 py-2.5 rounded-full shadow-2xl border border-slate-800 z-50 transition-all opacity-0 translate-y-3 pointer-events-none duration-300 flex items-center gap-1.5">
        <svg class="w-4 h-4 text-green-400 fill-current" viewBox="0 0 20 20">
            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 11.414l-3.707-3.707 1.414-1.414L9 10.586l4.293-4.293 1.414 1.414L9 13.414z"/>
        </svg>
        <span id="toast-message">คัดลอกลิงก์เรียบร้อยแล้ว</span>
    </div>

    <style>
        /* Hide scrollbars */
        #shorts-container::-webkit-scrollbar,
        .carousel-images-container::-webkit-scrollbar {
            display: none;
        }
        #shorts-container,
        .carousel-images-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Line clamp description */
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('shorts-container');
            const slides = document.querySelectorAll('.short-video-slide');
            let activeIndex = 0;
            const viewedShorts = new Set(); // Track view counts incremented in this session

            if (slides.length === 0) return;

            // CSRF Token header config
            const getHeaders = () => {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                };
            };

            // Heartbeat tracking for short video watch duration
            let pingInterval = null;
            let currentSessionId = null;
            let currentShortVideoId = null;

            function startShortVideoPing(shortId, courseId) {
                if (pingInterval) {
                    clearInterval(pingInterval);
                }
                currentSessionId = null;
                currentShortVideoId = shortId;

                function sendPing() {
                    const activeSlide = document.querySelector(`.short-video-slide[data-id="${currentShortVideoId}"]`);
                    if (!activeSlide) return;

                    const video = activeSlide.querySelector('.short-video-player');
                    const audio = activeSlide.querySelector('.short-audio-player');
                    const media = video || audio;

                    if (media && !media.paused) {
                        fetch('/study-session/ping', {
                            method: 'POST',
                            headers: getHeaders(),
                            body: JSON.stringify({
                                course_id: courseId,
                                short_video_id: currentShortVideoId,
                                type: 'short_video',
                                session_id: currentSessionId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.session_id) {
                                currentSessionId = data.session_id;
                            }
                        })
                        .catch(err => console.log('Short video ping failed:', err));
                    }
                }

                // Initial ping after 3 seconds, then every 15 seconds
                setTimeout(() => {
                    if (currentShortVideoId === shortId) {
                        sendPing();
                    }
                }, 3000);

                pingInterval = setInterval(() => {
                    if (currentShortVideoId === shortId) {
                        sendPing();
                    }
                }, 15000);
            }

            function stopShortVideoPing() {
                if (pingInterval) {
                    clearInterval(pingInterval);
                    pingInterval = null;
                }
                currentSessionId = null;
                currentShortVideoId = null;
            }

            // Play/Pause control for specific index
            const managePlayback = (index) => {
                slides.forEach((slide, i) => {
                    const video = slide.querySelector('.short-video-player');
                    const audio = slide.querySelector('.short-audio-player');

                    if (i === index) {
                        // Play targeted active video if present
                        if (video) {
                            video.play().catch(err => {
                                console.log("Autoplay blocked, waiting for user click.", err);
                            });
                        }

                        // Play background audio if present
                        if (audio) {
                            audio.play().catch(err => {
                                console.log("Audio autoplay blocked.", err);
                            });
                        }
                        
                        // Increment view count if not done in session
                        const shortId = slide.getAttribute('data-id');
                        if (!viewedShorts.has(shortId)) {
                            viewedShorts.add(shortId);
                            fetch(`/shorts/${shortId}/view`, {
                                method: 'POST',
                                headers: getHeaders()
                            }).catch(err => console.log(err));
                        }

                        // Start heartbeat tracking if course is linked
                        const courseId = slide.getAttribute('data-course-id');
                        if (courseId && courseId !== '') {
                            startShortVideoPing(shortId, courseId);
                        } else {
                            stopShortVideoPing();
                        }
                    } else {
                        // Pause adjacent videos and reset timeframe
                        if (video) {
                            video.pause();
                            video.currentTime = 0;
                        }
                        // Pause adjacent background audios
                        if (audio) {
                            audio.pause();
                            audio.currentTime = 0;
                        }
                    }
                });
            };

            // Auto-play the first video on user interaction
            const triggerInitialPlay = () => {
                managePlayback(0);
                document.removeEventListener('click', triggerInitialPlay);
                document.removeEventListener('touchstart', triggerInitialPlay);
            };
            document.addEventListener('click', triggerInitialPlay);
            document.addEventListener('touchstart', triggerInitialPlay);

            // Intersection Observer to track scroll snaps
            const observerOptions = {
                root: container,
                threshold: 0.6 // Trigger when 60% of the slide is snapped in
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const index = parseInt(entry.target.getAttribute('data-index'));
                        if (index !== activeIndex) {
                            activeIndex = index;
                            managePlayback(activeIndex);
                        }
                    }
                });
            }, observerOptions);

            slides.forEach(slide => observer.observe(slide));

            // Tap area play/pause and double-tap like triggers
            slides.forEach(slide => {
                const tapArea = slide.querySelector('.video-tap-area');
                const video = slide.querySelector('.short-video-player');
                const audio = slide.querySelector('.short-audio-player');
                const playOverlay = slide.querySelector('.play-icon-overlay');
                let lastTap = 0;

                if (!tapArea) return;

                tapArea.addEventListener('click', (e) => {
                    const now = Date.now();
                    const doubleTapDelay = 300;

                    // Double-tap handler (Like gesture)
                    if (now - lastTap < doubleTapDelay) {
                        triggerDoubleTapLike(slide, e);
                    } else {
                        // Single-tap handler (Play/Pause toggler)
                        const media = video || audio;
                        if (media) {
                            setTimeout(() => {
                                // Check that user didn't fire double-tap within window
                                if (Date.now() - lastTap >= doubleTapDelay) {
                                    togglePlayPause(media, playOverlay);
                                }
                            }, doubleTapDelay);
                        }
                    }
                    lastTap = now;
                });
            });

            // Play/Pause toggling function
            const togglePlayPause = (media, overlay) => {
                if (!media) return;
                
                if (media.paused) {
                    media.play().catch(e => console.log(e));
                    if (overlay) {
                        overlay.classList.add('opacity-0', 'scale-75');
                        setTimeout(() => overlay.classList.add('hidden'), 300);
                    }
                } else {
                    media.pause();
                    if (overlay) {
                        overlay.classList.remove('hidden');
                        setTimeout(() => {
                            overlay.classList.remove('opacity-0', 'scale-75');
                        }, 10);
                    }
                }
            };

            // Double tap Like burst animation and AJAX toggle
            const triggerDoubleTapLike = (slide, event) => {
                const heartContainer = slide.querySelector('.heart-burst-container');
                const likeBtn = slide.querySelector('.btn-like');
                
                // Show floating burst heart animation at client coordinate
                const rect = heartContainer.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                const heart = document.createElement('div');
                heart.className = 'absolute text-red-500 drop-shadow-[0_4px_12px_rgba(239,68,68,0.5)] transform -translate-x-1/2 -translate-y-1/2 pointer-events-none scale-0 animate-[heart-pop_0.6s_ease-out_forwards] z-40';
                heart.style.left = `${x}px`;
                heart.style.top = `${y}px`;
                heart.innerHTML = `
                    <svg class="w-16 h-16 fill-current" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                `;
                heartContainer.appendChild(heart);

                setTimeout(() => heart.remove(), 600);

                // Call Like endpoint if the short is not already liked
                const isLiked = likeBtn.getAttribute('data-liked') === 'true';
                if (!isLiked) {
                    toggleLike(slide.getAttribute('data-id'), likeBtn, slide.querySelector('.like-count'), slide.querySelector('.svg-heart'));
                }
            };

            // AJAX Like Toggle Function
            const toggleLike = (id, btn, countSpan, heartSvg) => {
                fetch(`/shorts/${id}/like`, {
                    method: 'POST',
                    headers: getHeaders()
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.setAttribute('data-liked', data.status === 'liked' ? 'true' : 'false');
                        countSpan.textContent = data.likes_count;
                        
                        if (data.status === 'liked') {
                            heartSvg.classList.add('text-red-500', 'fill-current');
                            heartSvg.classList.remove('text-white');
                            btn.classList.add('scale-110');
                            setTimeout(() => btn.classList.remove('scale-110'), 200);
                        } else {
                            heartSvg.classList.remove('text-red-500', 'fill-current');
                            heartSvg.classList.add('text-white');
                        }
                    }
                })
                .catch(err => console.log(err));
            };

            // Hook heart click triggers
            document.querySelectorAll('.btn-like').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Avoid triggering video play/pause
                    const slide = btn.closest('.short-video-slide');
                    const id = slide.getAttribute('data-id');
                    const countSpan = slide.querySelector('.like-count');
                    const heartSvg = slide.querySelector('.svg-heart');
                    toggleLike(id, btn, countSpan, heartSvg);
                });
            });

            // Toast feedback mechanism
            const showToast = (message) => {
                const toast = document.getElementById('toast-notify');
                const text = document.getElementById('toast-message');
                text.textContent = message;
                
                toast.classList.remove('opacity-0', 'translate-y-3', 'pointer-events-none');
                
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-3', 'pointer-events-none');
                }, 2500);
            };

            // Direct URL sharing handler
            document.querySelectorAll('.btn-share').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const shortId = btn.getAttribute('data-id');
                    // Build absolute share link linking directly to the index with the video query ID
                    const shareUrl = `${window.location.origin}/shorts?id=${shortId}`;
                    
                    navigator.clipboard.writeText(shareUrl)
                        .then(() => {
                            showToast('คัดลอกลิงก์ไปยังคลิปความรู้นี้เรียบร้อยแล้ว');
                        })
                        .catch(err => {
                            console.error(err);
                            showToast('เกิดข้อผิดพลาดในการคัดลอกลิงก์');
                        });
                });
            });

            // Horizontal scrolling carousel indicators listener
            document.querySelectorAll('.carousel-images-container').forEach(carousel => {
                const indicator = carousel.parentElement.querySelector('.carousel-index-indicator');
                if (!indicator) return;

                carousel.addEventListener('scroll', () => {
                    const scrollLeft = carousel.scrollLeft;
                    const width = carousel.clientWidth;
                    const index = Math.round(scrollLeft / width) + 1;
                    indicator.textContent = index;
                });
            });

            // Handle swipe gestures on image slides
            document.querySelectorAll('.short-video-slide').forEach(slide => {
                const carousel = slide.querySelector('.carousel-images-container');
                const tapArea = slide.querySelector('.video-tap-area');
                if (!carousel || !tapArea) return;

                let touchStartX = 0;
                let touchEndX = 0;

                tapArea.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                tapArea.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, { passive: true });

                function handleSwipe() {
                    const threshold = 45; // minimum swipe distance in px
                    const width = carousel.clientWidth;
                    if (touchStartX - touchEndX > threshold) {
                        // Swipe left (next image)
                        carousel.scrollBy({ left: width, behavior: 'smooth' });
                    } else if (touchEndX - touchStartX > threshold) {
                        // Swipe right (prev image)
                        carousel.scrollBy({ left: -width, behavior: 'smooth' });
                    }
                }
            });

            // Bind progress seekbars for each slide
            document.querySelectorAll('.short-video-slide').forEach(slide => {
                const video = slide.querySelector('.short-video-player');
                const audio = slide.querySelector('.short-audio-player');
                const media = video || audio;
                
                const progressContainer = slide.querySelector('.progress-bar-container');
                if (!media || !progressContainer) return;
                
                const fill = progressContainer.querySelector('.progress-bar-fill');
                const handle = progressContainer.querySelector('.progress-bar-handle');
                
                // Update progress bar on timeupdate
                media.addEventListener('timeupdate', () => {
                    if (media.duration) {
                        const pct = (media.currentTime / media.duration) * 100;
                        fill.style.width = `${pct}%`;
                        if (handle) {
                            handle.style.left = `${pct}%`;
                        }
                    }
                });
                
                // Handle seek click / drag
                let isDragging = false;
                
                const seek = (e) => {
                    const rect = progressContainer.getBoundingClientRect();
                    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                    let offsetX = clientX - rect.left;
                    if (offsetX < 0) offsetX = 0;
                    if (offsetX > rect.width) offsetX = rect.width;
                    
                    const pct = offsetX / rect.width;
                    if (media.duration) {
                        media.currentTime = pct * media.duration;
                    }
                };
                
                progressContainer.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    seek(e);
                });
                
                window.addEventListener('mousemove', (e) => {
                    if (isDragging) {
                        seek(e);
                    }
                });
                
                window.addEventListener('mouseup', () => {
                    isDragging = false;
                });
                
                // Touch events for mobile
                progressContainer.addEventListener('touchstart', (e) => {
                    isDragging = true;
                    seek(e);
                }, { passive: true });
                
                window.addEventListener('touchmove', (e) => {
                    if (isDragging) {
                        seek(e);
                    }
                }, { passive: false });
                
                window.addEventListener('touchend', () => {
                    isDragging = false;
                });
            });

            // Check if specific ID is passed in query string to snap directly to it
            const urlParams = new URLSearchParams(window.location.search);
            const queryId = urlParams.get('id');
            if (queryId) {
                const targetSlide = document.querySelector(`.short-video-slide[data-id="${queryId}"]`);
                if (targetSlide) {
                    setTimeout(() => {
                        targetSlide.scrollIntoView({ behavior: 'auto' });
                        activeIndex = parseInt(targetSlide.getAttribute('data-index'));
                        managePlayback(activeIndex);
                    }, 500);
                }
            }
        });

        // Global carousel scrolling function for click arrows
        window.scrollCarousel = function(button, direction) {
            const slide = button.closest('.short-video-slide');
            const carousel = slide.querySelector('.carousel-images-container');
            if (carousel) {
                const width = carousel.clientWidth;
                carousel.scrollBy({ left: direction * width, behavior: 'smooth' });
            }
        };
    </script>

    <!-- Custom inline animation for burst heart -->
    <style>
        @keyframes heart-pop {
            0% {
                transform: translate(-50%, -50%) scale(0) rotate(0deg);
                opacity: 0;
            }
            15% {
                transform: translate(-50%, -50%) scale(1.2) rotate(-15deg);
                opacity: 0.9;
            }
            30% {
                transform: translate(-50%, -50%) scale(1) rotate(10deg);
                opacity: 1;
            }
            80% {
                transform: translate(-50%, -50%) scale(0.95) translateY(-30px);
                opacity: 0.6;
            }
            100% {
                transform: translate(-50%, -50%) scale(0.8) translateY(-60px);
                opacity: 0;
            }
        }
    </style>
</x-app-layout>
