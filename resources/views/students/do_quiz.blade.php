<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100;400;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- เพิ่ม: ป้องกันการคลุมดำ (Select Text) --- */
        body {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
        }

        /* --- 1. ระบบ Navigation (ตัวเลขข้อ) --- */
        .question-step {
            width: 32px; height: 32px; border-radius: 7px;
            border: 3px solid #0f172a; background-color: #ffffff;
            color: #0f172a; font-weight: 900; display: flex;
            align-items: center; justify-content: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; flex-shrink: 0;
        }
        .question-step.completed {
            background-color: #10b981 !important; border-color: #064e3b !important; color: #ffffff !important;
            box-shadow: 4px 4px 0px #064e3b;
        }
        .question-step.active {
            border-color: #4f46e5 !important; background-color: #e0e7ff !important;
            color: #4f46e5 !important; transform: translateY(-4px);
            box-shadow: 0px 8px 15px rgba(79, 70, 229, 0.3);
        }

        /* คุม Element หลัก */
        #main-quiz-container { font-family: 'Sarabun', sans-serif !important; }
        #main-quiz-container button, #main-quiz-container input, #main-quiz-container select, #main-quiz-container textarea {
            font-family: 'Sarabun', sans-serif !important;
        }

        /* --- 2. การจัดการ Slide ระบบใหม่ --- */
        #questions-viewport { width: 100%; overflow: hidden; position: relative; }
        #questions-container { display: flex; transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
        .question-block { flex: 0 0 100%; width: 100%; opacity: 0.2; transition: opacity 0.4s ease; padding: 0 10px; }
        .question-block.active-slide { opacity: 1; }

        /* Modal & Helpers */
        #init-auth-modal { 
            display: none; position: fixed; inset: 0; z-index: 1000;
            background-color: #020617; align-items: center; justify-content: center; padding: 1rem;
        }
        .swal2-popup { border: 5px solid #0f172a !important; border-radius: 2.5rem !important; }
        .swal2-confirm {background-color: #4f46e5;}
        .swal2-cancel {background-color: red;}
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- 1. Initial Auth Modal --}}
    <div id="init-auth-modal">
        <div class="bg-white max-w-md w-full rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.7)] p-5 text-center border-[6px] border-slate-900">
            <div class="mb-4">
                <div class="w-12 h-12 bg-indigo-600 text-white rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-4xl shadow-[8px_8px_0px_#312e81]">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="text-xl font-black text-slate-950 uppercase tracking-tighter">เข้าสู่ระบบการสอบ</h2>
                <p class="text-slate-600 mt-2 font-bold">กรุณาตรวจสอบกล้องและพิกัดก่อนเริ่ม</p>
            </div>

            <div id="camera-section" class="hidden relative w-full aspect-square max-w-[240px] bg-black rounded-[2.5rem] mx-auto mb-8 overflow-hidden border-[6px] border-slate-900 shadow-inner">
                <video id="webcam" autoplay playsinline class="w-full h-full object-cover scale-x-[-1]"></video>
                <canvas id="photo-canvas" class="hidden"></canvas>
            </div>

            <div class="bg-slate-100 p-6 rounded-[2rem] mb-8 border-4 border-slate-200 text-left">
                <label class="flex items-start gap-4 cursor-pointer group">
                    <input type="checkbox" id="consent-check" class="mt-1 w-9 h-9 rounded-xl border-4 border-slate-400 text-indigo-600 focus:ring-indigo-900 transition-all cursor-pointer">
                    <span class="text-base text-slate-950 font-black leading-tight group-hover:text-indigo-700">
                        ฉันยืนยันตัวตน และยินยอมให้ระบบบันทึกภาพ/พิกัดตลอดการสอบ
                    </span>
                </label>
            </div>
            <input type="hidden" id="lat_input" value="0">
            <input type="hidden" id="lng_input" value="0">
            <button type="button" id="start-init-btn" disabled onclick="processInitialize()"
                class="w-full py-6 bg-slate-300 text-slate-500 font-black rounded-[1.5rem] transition-all text-xl uppercase border-b-[10px] border-slate-400">
                เริ่มทำข้อสอบ
            </button>
            
            <p id="gps-status" class="mt-6 text-sm font-black text-slate-500 flex items-center justify-center gap-2">
                <i class="fa-solid fa-circle-notch fa-spin"></i> {{ $quiz->require_location == 1 ? 'WAITING FOR GPS...' : 'GPS BYPASSED' }}
            </p>
        </div>
    </div>

    {{-- 2. Quiz Content --}}
    <div id="main-quiz-container" class="min-h-screen bg-slate-100 hidden overflow-x-hidden mb-20">
        <div class="sticky top-0 z-[0] bg-blue-50 border-b-[4px] border-slate-950 shadow-md">
            <div class="max-w-full mx-auto px-3 py-2 md:px-4 md:py-3">
                <div class="flex justify-between items-center gap-2 mb-2">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <img src="https://phothongdlec.ac.th/storage/logo.png" class="h-8 w-auto md:h-10 object-contain shrink-0">
                        <div class="flex flex-col truncate">
                            <span class="text-sm md:text-base font-black text-slate-950 truncate leading-tight">ศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอ{{ config('app.name_district') }}</span>
                            <span class="text-[10px] md:text-xs font-bold text-slate-600 truncate">สำนักงานส่งเสริมการเรียนรู้ประจำจังหวัด{{ config('app.name_province') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-950 px-2 py-1 md:px-3 md:py-1.5 rounded-lg shadow-[2px_2px_0px_#4f46e5] shrink-0">
                        <i class="fa-solid fa-clock text-indigo-400 text-xs md:text-sm"></i>
                        <div class="text-white font-mono font-black text-sm md:text-lg min-w-[45px] md:w-[60px] text-center" id="countdown-timer">00:00</div>
                    </div>
                </div>

                <div class="border-t border-slate-300/50 pt-2 mb-2">
                    <h1 class="text-sm md:text-lg font-black text-slate-950 leading-tight mb-2 line-clamp-1">
                        <span class="text-slate-500 font-medium text-xs md:text-sm">แบบทดสอบ:</span> {{ $quiz->title }}
                    </h1>
                    <div class="flex flex-wrap gap-1.5">
                        <div class="inline-flex items-center px-2 py-0.5 rounded bg-white border border-slate-300 text-[10px] md:text-xs font-bold text-slate-700">
                            <i class="fa-solid fa-book text-indigo-500 mr-1"></i> {{ $quiz->subject_code }}
                        </div>
                        <div class="inline-flex items-center px-2 py-0.5 rounded bg-white border border-slate-300 text-[10px] md:text-xs font-bold text-slate-700">
                            <i class="fa-solid fa-star text-yellow-500 mr-1"></i> {{ $quiz->total_score }} คะแนน
                        </div>
                        <div class="inline-flex items-center px-2 py-0.5 rounded bg-white border border-slate-300 text-[10px] md:text-xs font-bold text-slate-700">
                            <i class="fa-regular fa-clock text-red-500 mr-1"></i> {{ $quiz->time_limit }}น.
                        </div>
                    </div>
                </div>

                <div class="flex gap-1.5 overflow-x-auto pb-1 no-scrollbar border-t border-slate-200 pt-2" id="nav-container">
                    @foreach($questions as $index => $q)
                        <button type="button" onclick="showQuestion({{ $index }})" id="step-dot-{{ $index }}" 
                                class="question-step shrink-0 w-5 h-5 md:w-5 md:h-5 flex items-center justify-center rounded-md border-[1.5px] border-slate-950 bg-white text-xs md:text-sm font-bold text-slate-950 hover:bg-slate-100 transition-colors shadow-[2px_2px_0px_rgba(0,0,0,1)] active:translate-y-0.5 active:shadow-none">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="max-w-full mx-auto pt-6 pb-0"> 
            <form id="quizForm" action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
                @csrf
                <input type="hidden" id="lat_input" name="latitude" value="0">
                <input type="hidden" id="lng_input" name="longitude" value="0">
                <input type="hidden" id="violation_count" name="violation_count" value="0"> <div id="questions-viewport">
                    <div id="questions-container">
                        @foreach ($questions as $index => $question)
                            <div id="q-block-{{ $index }}" class="question-block">
                                <input type="hidden" name="questions[{{ $index }}][question_id]" value="{{ $question->id }}">
                                <div class="mb-2 mt-2">
                                    <span class="bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-black uppercase tracking-widest shadow-[4px_4px_0px_#1e1b4b]">
                                        ข้อที่ {{ $index + 1 }} จาก {{ count($questions) }}
                                    </span>
                                </div>
                                <h3 class="p-4 text-sm md:text-2xl font-black text-slate-950 leading-[1.2]">
                                    {{ $question->question_text }}
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-2">
                                    @if ($question->question_type === 'multiple_choice')
                                        @foreach ($question->choices as $choice)
                                            <label class="group relative flex items-center p-4 rounded-[1rem] border-[3px] border-slate-300 cursor-pointer transition-all hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-[8px_8px_0px_#4f46e5]">
                                                <input type="radio" name="questions[{{ $index }}][answer]" value="{{ $choice->id }}" 
                                                    onchange="markAsAnswered({{ $index }})" 
                                                    class="w-6 h-6 text-indigo-600 border-slate-400 focus:ring-indigo-900 mr-4">
                                                <span class="font-black text-slate-950 text-sm">{{ $choice->choice_text }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Floating Buttons --}}
                <div class="fixed sm:block bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t-[5px] border-slate-950 p-6 z-[55]">
                    <div class="max-w-xl mx-auto flex gap-6">
                        <button type="button" id="prev-btn" class="w-24 h-12 bg-white border-[4px] border-slate-950 rounded-[1.5rem] flex items-center justify-center text-slate-950 text-xl shadow-[6px_6px_0px_#000] active:translate-y-1 active:shadow-none transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        
                        <button type="button" id="next-btn" class="flex-1 h-12 bg-slate-950 text-white rounded-[1.5rem] font-black text-xl flex items-center justify-center shadow-[8px_8px_0px_#4f46e5] active:translate-y-1 active:shadow-none transition-all">
                            ถัดไป <i class="fa-solid fa-chevron-right ml-4"></i>
                        </button>

                        <button type="button" id="submit-btn" class="hidden flex-1 h-12 bg-emerald-500 text-white rounded-[1.5rem] font-black text-xl flex items-center justify-center shadow-[8px_8px_0px_#064e3b] active:translate-y-1 active:shadow-none transition-all">
                            ส่งข้อสอบ <i class="fa-solid fa-check-double ml-4"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const reqSnap = {{ $quiz->require_snapshot ?? 0 }};
        const reqLoc = {{ $quiz->require_location ?? 0 }};
        const timeLimit = {{ $quiz->time_limit }} * 60;
        const totalQuestions = {{ count($questions) }};

        let currentQuestionIndex = 0;
        let timeRemainingSeconds = timeLimit;
        let isSubmitting = false;
        let violationCount = 0; // นับจำนวนการสลับหน้าจอ
        const answeredQuestions = new Array(totalQuestions).fill(false);

        // --- เพิ่ม: ป้องกันการคลิกขวา (Right Click) ---
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('keydown', event => {
            // ป้องกัน Ctrl+U, Ctrl+S, Ctrl+C (Optional)
            if(event.ctrlKey && (event.key === 'u' || event.key === 's' || event.key === 'c')) {
                event.preventDefault();
            }
        });

        // --- เพิ่ม: ตรวจจับการสลับหน้าจอ (Tab Switch) ---
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                violationCount++;
                document.getElementById('violation_count').value = violationCount;
                document.title = "⚠️ กลับมาทำข้อสอบเดี๋ยวนี้!";
                
                Swal.fire({
                    icon: 'warning',
                    title: 'ตรวจพบการสลับหน้าจอ!',
                    text: `คุณได้สลับหน้าจอเป็นครั้งที่ ${violationCount} ระบบได้บันทึกพฤติกรรมนี้ไว้แล้ว`,
                    confirmButtonText: 'รับทราบ',
                    confirmButtonColor: '#f59e0b',
                    allowOutsideClick: false
                });
            } else {
                document.title = "{{ $quiz->title }}";
            }
        });

        window.onload = function() {
            if (reqSnap === 0 && reqLoc === 0) {
                processInitialize();
            } else {
                document.getElementById('init-auth-modal').style.display = 'flex';
                initSecurityDevices();
            }
        };

// เพิ่มตัวแปร global เพื่อเช็คสถานะ GPS
let isGpsReady = (reqLoc === 0); // ถ้าไม่เอา GPS ให้ค่าเป็น true เลย

async function initSecurityDevices() {
    // 1. จัดการเรื่องกล้อง
    if (reqSnap === 1) {
        document.getElementById('camera-section').classList.remove('hidden');
        try {
            const video = document.getElementById('webcam');
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'CAMERA ERROR', text: 'กรุณาเปิดกล้องเพื่อทำข้อสอบ' });
        }
    }

    // 2. จัดการเรื่อง GPS
    if (reqLoc === 1) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                document.getElementById('lat_input').value = pos.coords.latitude;
                document.getElementById('lng_input').value = pos.coords.longitude;
                
                const status = document.getElementById('gps-status');
                status.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i> GPS SIGNAL READY`;
                status.classList.replace('text-slate-500', 'text-emerald-600');
                
                isGpsReady = true; // ยืนยันว่าได้พิกัดแล้ว
                checkButtonStatus(); // เช็คปุ่มทันทีที่ได้พิกัด
            }, err => {
                console.error("GPS Error:", err);
                let msg = "กรุณาอนุญาตการเข้าถึงพิกัด (Location Access)";
                if(err.code === 1) msg = "คุณปฏิเสธการเข้าถึงพิกัด กรุณาตั้งค่าเบราว์เซอร์ให้ยินยอม";
                
                Swal.fire({ icon: 'warning', title: 'GPS REQUIRED', text: msg });
            });
        }
    } else {
        checkButtonStatus(); // ถ้าไม่ใช้ GPS ให้เช็คปุ่มเลย (เผื่อติ๊ก checkbox ไว้ก่อน)
    }
}

// ฟังก์ชันใหม่สำหรับคุมการเปิด-ปิดปุ่ม "เริ่มทำข้อสอบ"
function checkButtonStatus() {
    const consentCheck = document.getElementById('consent-check');
    const startBtn = document.getElementById('start-init-btn');
    
    // เงื่อนไข: ต้องติ๊ก checkbox และ GPS ต้องพร้อม (ถ้าบังคับ)
    if (consentCheck.checked && isGpsReady) {
        startBtn.disabled = false;
        // ปรับ UI ให้ดูว่ากดได้แล้ว
        startBtn.classList.remove('bg-slate-300', 'text-slate-500', 'border-slate-400');
        startBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-800');
    } else {
        startBtn.disabled = true;
        // ปรับ UI กลับเป็นสีเทา
        startBtn.classList.add('bg-slate-300', 'text-slate-500', 'border-slate-400');
        startBtn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-800');
    }
}

// เพิ่ม Event ให้ Checkbox เมื่อมีการคลิก
document.getElementById('consent-check').addEventListener('change', checkButtonStatus);

// เรียกใช้งานตอนโหลดหน้าครั้งแรก
document.addEventListener('DOMContentLoaded', () => {
    initSecurityDevices();
});

        async function processInitialize() {
            const btn = document.getElementById('start-init-btn');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> LOADING...`;

            let photoData = "";
            if (reqSnap === 1) {
                const video = document.getElementById('webcam');
                const canvas = document.getElementById('photo-canvas');
                if (video.srcObject) {
                    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    photoData = canvas.toDataURL('image/png');
                }
            }

            try {
                const response = await fetch("{{ route('quizzes.initialize', $quiz->id) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ 
                        start_photo: photoData, 
                        latitude: document.getElementById('lat_input').value || 0,
                        longitude: document.getElementById('lng_input').value || 0
                    })
                });

                const data = await response.json();
                if (data.success) {
                    document.getElementById('init-auth-modal').style.display = 'none';
                    document.getElementById('main-quiz-container').classList.remove('hidden');
                    if (document.getElementById('webcam').srcObject) {
                        document.getElementById('webcam').srcObject.getTracks().forEach(t => t.stop());
                    }
                    showQuestion(0);
                    startTimer();
                } else { throw new Error(data.message); }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'ERROR', text: e.message });
                btn.disabled = false;
                btn.innerText = "TRY AGAIN";
            }
        }

        function showQuestion(index) {
            currentQuestionIndex = index;
            const container = document.getElementById('questions-container');
            container.style.transform = `translateX(-${index * 100}%)`;

            document.querySelectorAll('.question-block').forEach((b, i) => {
                b.classList.toggle('active-slide', i === index);
            });

            document.querySelectorAll('.question-step').forEach((b, i) => {
                b.classList.toggle('active', i === index);
            });
            
            document.getElementById('prev-btn').style.opacity = index === 0 ? '0.2' : '1';
            document.getElementById('prev-btn').disabled = index === 0;

            const isLast = index === totalQuestions - 1;
            document.getElementById('next-btn').classList.toggle('hidden', isLast);
            document.getElementById('submit-btn').classList.toggle('hidden', !isLast);

            document.getElementById(`step-dot-${index}`).scrollIntoView({ 
                behavior: 'smooth', inline: 'center', block: 'nearest' 
            });
        }

        function markAsAnswered(index) {
            answeredQuestions[index] = true;
            document.querySelectorAll('.question-step')[index].classList.add('completed');
        }

        function startTimer() {
            const timerEl = document.getElementById('countdown-timer');
            const timerInt = setInterval(() => {
                if (timeRemainingSeconds > 0) {
                    timeRemainingSeconds--;
                    const m = Math.floor(timeRemainingSeconds / 60);
                    const s = timeRemainingSeconds % 60;
                    timerEl.innerText = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                    if(timeRemainingSeconds < 60) {
                        timerEl.parentElement.classList.replace('bg-slate-950', 'bg-rose-600');
                    }
                } else {
                    clearInterval(timerInt);
                    submitFinal(true); // Force submit if time is up
                }
            }, 1000);
        }

        function submitFinal(force = false) {
            if (isSubmitting) return;
            
            // --- แก้ไข: ตรวจสอบว่าทำครบทุกข้อหรือยัง ---
            if (!force) {
                const unansweredIndex = answeredQuestions.findIndex(answered => answered === false);
                if (unansweredIndex !== -1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ยังทำข้อสอบไม่ครบ!',
                        text: `คุณยังไม่ได้ทำข้อที่ ${unansweredIndex + 1}`,
                        confirmButtonText: 'ไปทำข้อนั้นเดี๋ยวนี้',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => {
                        showQuestion(unansweredIndex);
                    });
                    return; // หยุดการทำงาน ไม่ส่งฟอร์ม
                }
            }

            isSubmitting = true;
            Swal.fire({ 
                title: 'SENDING...', 
                html: '<p class="font-bold">ระบบกำลังบันทึกคำตอบ ห้ามปิดหน้าต่างนี้!</p>',
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading() 
            });
            document.getElementById('quizForm').submit();
        }

        document.getElementById('consent-check').onchange = (e) => {
            const btn = document.getElementById('start-init-btn');
            btn.disabled = !e.target.checked;
            if(e.target.checked) {
                btn.className = "w-full py-6 bg-indigo-600 text-white font-black rounded-[1.5rem] transition-all text-2xl uppercase border-b-[10px] border-indigo-900 shadow-xl cursor-pointer active:translate-y-1 active:border-b-0";
            } else {
                btn.className = "w-full py-6 bg-slate-300 text-slate-500 font-black rounded-[1.5rem] transition-all text-2xl uppercase border-b-[10px] border-slate-400";
            }
        };

        document.getElementById('prev-btn').onclick = () => showQuestion(currentQuestionIndex - 1);
        document.getElementById('next-btn').onclick = () => showQuestion(currentQuestionIndex + 1);
        
        document.getElementById('submit-btn').onclick = () => {
            // เช็คก่อนว่าทำครบไหมในฟังก์ชัน submitFinal
            const unansweredCount = answeredQuestions.filter(a => !a).length;
            if (unansweredCount > 0) {
                 submitFinal(); // เรียกใช้ฟังก์ชันนี้เพื่อให้มันแจ้งเตือนและพาไปข้อที่ขาด
            } else {
                Swal.fire({
                    title: 'ยืนยันการส่งข้อสอบ?',
                    text: "คุณทำครบทุกข้อแล้ว ต้องการส่งเลยหรือไม่?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน ส่งเลย!',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#e11d48',
                }).then(r => r.isConfirmed && submitFinal());
            }
        };
    </script>
</x-app-layout>