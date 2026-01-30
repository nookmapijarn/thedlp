<x-app-layout>
    <div class="sm:ml-64 p-4 min-h-screen bg-gray-100">
        <div class="container mx-auto px-4 py-8">

            {{-- Tabs --}}
            <div class="mb-6 border-b border-gray-200">
                <nav class="flex space-x-4">
                    <button
                        id="tab-quizzes"
                        class="tab-btn border-b-2 border-blue-600 text-blue-600 px-4 py-2 font-semibold"
                        onclick="switchTab('quizzes')">
                        แบบทดสอบ
                    </button>

                    <button
                        id="tab-history"
                        class="tab-btn border-b-2 border-transparent text-gray-500 hover:text-gray-700 px-4 py-2 font-semibold"
                        onclick="switchTab('history')">
                        ประวัติการทำแบบทดสอบ
                    </button>
                </nav>
            </div>

            {{-- ================= TAB : QUIZZES ================= --}}
            <div id="tab-content-quizzes">

                <h1 class="text-3xl font-bold mb-8 text-gray-800">แบบทดสอบทั้งหมด</h1>

                @if ($errors->any())
                    <div id="validation-errors-message" class="hidden">
                        <strong class="font-bold">ข้อมูลไม่ถูกต้อง:</strong>
                        <ul class="mt-3 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($quizzes) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        @foreach ($quizzes as $quiz)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                                <div class="p-6 flex-grow">
                                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                                        {{ $quiz->title }}
                                    </h2>

                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ $quiz->description ?? 'ไม่มีคำอธิบาย' }}
                                    </p>

                                    <div class="flex items-center text-gray-500 text-sm mb-2">
                                        <span>⏱ เวลาจำกัด:
                                            {{ $quiz->time_limit > 0 ? $quiz->time_limit . ' นาที' : 'ไม่จำกัด' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center text-gray-500 text-sm">
                                        <span>📊 คะแนนรวม: {{ $quiz->total_score }}</span>
                                    </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 border-t">
                                    <a href="{{ route('quizzes.start', $quiz->id) }}"
                                       class="block bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 text-center">
                                        เริ่มทำแบบทดสอบ
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-600">
                        <p class="text-lg">ยังไม่มีแบบทดสอบ</p>
                    </div>
                @endif
            </div>

            {{-- ================= TAB : HISTORY ================= --}}
            <div id="tab-content-history" class="hidden">

                <h1 class="text-3xl font-bold mb-8 text-gray-800">ประวัติการทำแบบทดสอบ</h1>

                @if (count($quizAttemptsHistory) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($quizAttemptsHistory as $attempt)
                            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col">
                                <h2 class="text-xl font-semibold mb-2">
                                    {{ $attempt->quiz_title }}
                                </h2>

                                <p class="text-sm text-gray-600">
                                    คะแนน: <span class="font-bold text-blue-600">
                                        {{ $attempt->user_score }}
                                    </span> / {{ $attempt->quiz_total_score }}
                                </p>

                                <p class="text-sm text-gray-600">
                                    วันที่ทำ:
                                    {{ \Carbon\Carbon::parse($attempt->finished_at)->translatedFormat('j F Y H:i') }}
                                </p>

                                @php
                                    $percent = ($attempt->user_score / $attempt->quiz_total_score) * 100;
                                @endphp

                                @if ($percent >= 80)
                                    <button
                                        class="mt-4 bg-green-500 text-white py-2 rounded hover:bg-green-600"
                                        onclick="showCertificateModal(
                                            '{{ $attempt->quiz_title }}',
                                            '{{ $attempt->user_score }}',
                                            '{{ $attempt->quiz_total_score }}'
                                        )">
                                        แสดงเกียรติบัตร
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-600">
                        ยังไม่มีประวัติการทำแบบทดสอบ
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= CERTIFICATE MODAL ================= --}}
    <div id="certificate-modal"
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-full md:max-w-4xl max-h-[90vh] overflow-auto">
            <div class="flex justify-between mb-3">
                <h3 class="text-xl font-bold">เกียรติบัตร</h3>
                <button onclick="hideCertificateModal()">✖</button>
            </div>

            <div id="certificate-content"></div>

            <div class="text-center mt-4">
                <a id="download-certificate-link"
                   class="bg-blue-600 text-white px-4 py-2 rounded">
                    ดาวน์โหลดเกียรติบัตร
                </a>
            </div>
        </div>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function switchTab(tab) {
            const q = document.getElementById('tab-content-quizzes');
            const h = document.getElementById('tab-content-history');
            const tq = document.getElementById('tab-quizzes');
            const th = document.getElementById('tab-history');

            if (tab === 'quizzes') {
                q.classList.remove('hidden');
                h.classList.add('hidden');
                tq.classList.add('border-blue-600','text-blue-600');
                th.classList.remove('border-blue-600','text-blue-600');
            } else {
                h.classList.remove('hidden');
                q.classList.add('hidden');
                th.classList.add('border-blue-600','text-blue-600');
                tq.classList.remove('border-blue-600','text-blue-600');
            }
        }

        const certificateModal = document.getElementById('certificate-modal');
        const certificateContent = document.getElementById('certificate-content');
        const downloadLink = document.getElementById('download-certificate-link');

        function showCertificateModal(title, score, total) {
            certificateContent.innerHTML = `
                <div class="text-center p-10 border">
                    <h2 class="text-2xl font-bold">${title}</h2>
                    <p class="mt-4">คะแนน ${score} / ${total}</p>
                    <p class="mt-2">{{ auth()->user()->name }}</p>
                </div>
            `;
            certificateModal.classList.remove('hidden');
        }

        function hideCertificateModal() {
            certificateModal.classList.add('hidden');
        }
    </script>
</x-app-layout>
