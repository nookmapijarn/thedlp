<x-app-layout>
    <div class="sm:ml-64 p-4 min-h-screen bg-gray-100">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4 text-gray-800">{{ $quiz->title }}</h1>
            <p class="text-gray-600 mb-6">{{ $quiz->description ?? 'ไม่มีคำอธิบายสำหรับแบบทดสอบนี้' }}</p>

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">เวลาที่เหลือ:</h2>
                    <div id="countdown-timer" class="text-2xl font-bold text-blue-600">
                        {{ $quiz->time_limit > 0 ? sprintf('%02d:%02d:%02d', floor($quiz->time_limit), $quiz->time_limit * 60 % 60, 0) : 'ไม่จำกัด' }}
                    </div>
                </div>
                @if ($quiz->time_limit > 0)
                    <p class="text-sm text-gray-500">แบบทดสอบนี้มีเวลาจำกัด {{ $quiz->time_limit }} นาที</p>
                @endif
            </div>

            <form id="quizForm" action="{{ route('quizzes.submit', $quiz->id) }}" method="POST">
                @csrf

                <div id="questions-container">
                    @foreach ($questions as $index => $question)
                        <div id="question-{{ $question->id }}" class="question-block bg-white rounded-lg shadow-md p-6 mb-6 {{ $index === 0 ? '' : 'hidden' }}" data-question-index="{{ $index }}" data-question-type="{{ $question->question_type }}">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                คำถามที่ {{ $index + 1 }} จาก {{ count($questions) }}:
                            </h3>
                            <p class="text-gray-700 text-lg mb-4">{{ $question->question_text }}</p>

                            <input type="hidden" name="questions[{{ $index }}][question_id]" value="{{ $question->id }}">
                            <input type="hidden" name="questions[{{ $index }}][question_type]" value="{{ $question->question_type }}">

                            <div class="options-container mb-4">
                                @if ($question->question_type === 'multiple_choice')
                                    @foreach ($question->choices as $choice)
                                        <label class="flex items-center text-gray-700 cursor-pointer mb-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                            <input type="radio" name="questions[{{ $index }}][answer]" value="{{ $choice->id }}" class="form-radio h-5 w-5 text-blue-600 mr-3" {{ old("questions.$index.answer") == $choice->id ? 'checked' : '' }}>
                                            <span>{{ $choice->choice_text }}</span>
                                        </label>
                                    @endforeach
                                @elseif ($question->question_type === 'true_false')
                                    <label class="flex items-center text-gray-700 cursor-pointer mb-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                        <input type="radio" name="questions[{{ $index }}][answer]" value="1" class="form-radio h-5 w-5 text-blue-600 mr-3" {{ old("questions.$index.answer") == '1' ? 'checked' : '' }}>
                                        <span>ถูก</span>
                                    </label>
                                    <label class="flex items-center text-gray-700 cursor-pointer mb-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                        <input type="radio" name="questions[{{ $index }}][answer]" value="0" class="form-radio h-5 w-5 text-blue-600 mr-3" {{ old("questions.$index.answer") == '0' ? 'checked' : '' }}>
                                        <span>ผิด</span>
                                    </label>
                                @elseif ($question->question_type === 'short_answer')
                                    <textarea name="questions[{{ $index }}][answer]" rows="4" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y" placeholder="พิมพ์คำตอบของคุณที่นี่">{{ old("questions.$index.answer") }}</textarea>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" id="prev-btn" class="bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition-colors duration-200" style="display: none;">
                        &larr; ก่อนหน้า
                    </button>
                    <button type="button" id="next-btn" class="bg-blue-600 text-white font-bold py-2 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-200">
                        ถัดไป &rarr;
                    </button>
                    {{-- เปลี่ยน type="submit" เป็น type="button" --}}
                    <button type="button" id="submit-btn" class="bg-green-600 text-white font-bold py-2 px-6 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-200" style="display: none;">
                        ส่งคำตอบ
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal สำหรับแจ้งเตือนข้อผิดพลาด --}}
    <div id="error-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-sm w-full">
            <div class="flex justify-between items-center border-b pb-3 mb-3">
                <h3 class="text-xl font-semibold text-gray-800" id="modal-title">เกิดข้อผิดพลาด!</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="modal-content" class="text-gray-700 mb-4">
                {{-- ข้อความข้อผิดพลาดจะถูกแทรกที่นี่ --}}
            </div>
            <div class="flex justify-end">
                <button type="button" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50" onclick="hideModal()">
                    ตกลง
                </button>
            </div>
        </div>
    </div>

    <script>
        const quizDurationMinutes = {{ $quiz->time_limit }};
        let timeRemainingSeconds;
        let timerInterval;
        let currentQuestionIndex = 0;
        const questionBlocks = document.querySelectorAll('.question-block');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        const countdownTimerDisplay = document.getElementById('countdown-timer');
        const quizForm = document.getElementById('quizForm');
        let isSubmitting = false;

        const errorModal = document.getElementById('error-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');

        // Initialize timer
        if (quizDurationMinutes > 0) {
            timeRemainingSeconds = quizDurationMinutes * 60;
            startTimer();
        } else {
            countdownTimerDisplay.textContent = 'ไม่จำกัด';
        }

        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return `${h > 0 ? `${String(h).padStart(2, '0')}:` : ''}${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                timeRemainingSeconds--;
                countdownTimerDisplay.textContent = formatTime(timeRemainingSeconds);

                if (timeRemainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    countdownTimerDisplay.textContent = 'หมดเวลา!';
                    alert('หมดเวลาทำแบบทดสอบแล้ว ระบบจะส่งคำตอบของคุณโดยอัตโนมัติ');
                    isSubmitting = true;
                    quizForm.submit();
                }
            }, 1000);
        }

        // Modal Functions
        function showModal(title, message) {
            modalTitle.textContent = title;
            modalContent.innerHTML = message;
            errorModal.classList.remove('hidden');
        }

        function hideModal() {
            errorModal.classList.add('hidden');
        }

        // Client-side Validation Function
        function validateQuiz() {
            const unansweredQuestions = [];

            questionBlocks.forEach(questionBlock => {
                const questionIndex = parseInt(questionBlock.dataset.questionIndex) + 1; // +1 เพื่อให้เป็นเลขข้อ
                const questionType = questionBlock.dataset.questionType;
                let isAnswered = false;

                if (questionType === 'multiple_choice' || questionType === 'true_false') {
                    // ตรวจสอบ radio button
                    const radios = questionBlock.querySelectorAll('input[type="radio"]');
                    for (const radio of radios) {
                        if (radio.checked) {
                            isAnswered = true;
                            break;
                        }
                    }
                } else if (questionType === 'short_answer') {
                    // ตรวจสอบ textarea
                    const textarea = questionBlock.querySelector('textarea');
                    if (textarea && textarea.value.trim() !== '') {
                        isAnswered = true;
                    }
                }

                if (!isAnswered) {
                    unansweredQuestions.push(questionIndex);
                }
            });

            if (unansweredQuestions.length > 0) {
                let message = 'คุณยังไม่ได้ตอบคำถามต่อไปนี้:';
                message += '<ul class="mt-2 list-disc list-inside">';
                unansweredQuestions.forEach(qNum => {
                    message += `<li>ข้อที่ ${qNum}</li>`;
                });
                message += '</ul>';
                message += '<p class="mt-3">โปรดตอบให้ครบถ้วนก่อนส่งแบบทดสอบ</p>';

                showModal('กรุณาตอบคำถามให้ครบถ้วน', message);
                return false; // Validation failed
            }
            return true; // All questions answered
        }

        // Check for server-side errors on page load
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('error'))
                showModal('เกิดข้อผิดพลาด!', '{{ session('error') }}');
            @endif

            @if ($errors->any())
                let errorMessage = '<strong class="font-bold">ข้อมูลไม่ถูกต้อง:</strong><ul class="mt-3 list-disc list-inside">';
                @foreach ($errors->all() as $error)
                    errorMessage += '<li>{{ $error }}</li>';
                @endforeach
                errorMessage += '</ul>';
                showModal('ข้อผิดพลาดในการตรวจสอบ!', errorMessage);
            @endif

            // Initial question display
            showQuestion(currentQuestionIndex);
        });

        // Question Navigation
        function showQuestion(index) {
            questionBlocks.forEach((block, i) => {
                block.classList.add('hidden');
                if (i === index) {
                    block.classList.remove('hidden');
                }
            });
            updateNavigationButtons();
        }

        function updateNavigationButtons() {
            prevBtn.style.display = (currentQuestionIndex > 0) ? 'block' : 'none';
            nextBtn.style.display = (currentQuestionIndex < questionBlocks.length - 1) ? 'block' : 'none';
            submitBtn.style.display = (currentQuestionIndex === questionBlocks.length - 1) ? 'block' : 'none';

            if (questionBlocks.length === 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            }
        }

        prevBtn.addEventListener('click', () => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentQuestionIndex < questionBlocks.length - 1) {
                currentQuestionIndex++;
                showQuestion(currentQuestionIndex);
            }
        });

        // Event Listener for the Submit Button
        submitBtn.addEventListener('click', () => {
            if (validateQuiz()) { // ตรวจสอบความถูกต้องก่อนส่ง
                isSubmitting = true; // ตั้งค่าสถานะว่ากำลังส่ง
                quizForm.submit();    // ส่งฟอร์มจริง
            }
        });

        // Optional: Warn user before leaving page
        window.addEventListener('beforeunload', (event) => {
            if (quizDurationMinutes > 0 && timeRemainingSeconds > 0 && !isSubmitting) {
                event.returnValue = 'คุณแน่ใจหรือไม่ที่ต้องการออกจากหน้านี้? คำตอบของคุณอาจไม่ถูกบันทึก';
            }
        });

        // Prevent accidental form submission on enter key
        quizForm.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });
    </script>
</x-app-layout>