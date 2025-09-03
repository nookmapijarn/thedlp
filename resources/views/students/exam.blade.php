<x-app-layout>
    <div class="sm:ml-64 p-4 min-h-screen bg-gray-100">
        <div class="container mx-auto px-4 py-8">
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
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $quiz->title }}</h2>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $quiz->description ?? 'ไม่มีคำอธิบาย' }}</p>
                                <div class="flex items-center text-gray-500 text-sm mb-2">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>เวลาจำกัด: {{ $quiz->time_limit > 0 ? $quiz->time_limit . ' นาที' : 'ไม่จำกัด' }}</span>
                                </div>
                                <div class="flex items-center text-gray-500 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M17 16l-4-4-4 4"></path></svg>
                                    <span>คะแนนรวม: {{ $quiz->total_score }}</span>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                <a href="{{ route('quizzes.start', $quiz->id) }}" class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200 text-center w-full">
                                    เริ่มทำแบบทดสอบ
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-600 mb-12">
                    <p class="text-lg">ยังไม่มีแบบทดสอบให้ทำในขณะนี้</p>
                    <p class="text-sm mt-2">โปรดกลับมาตรวจสอบอีกครั้งในภายหลัง</p>
                </div>
            @endif

            ---

            <h1 class="text-3xl font-bold mb-8 text-gray-800">ประวัติการทำแบบทดสอบ</h1>

            @if (count($quizAttemptsHistory) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($quizAttemptsHistory as $attempt)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden p-6 flex flex-col">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $attempt->quiz_title }}</h2>
                            <p class="text-gray-600 text-sm mb-1">
                                <strong>วิชา:</strong> {{ $attempt->subject_name ?? 'ไม่ระบุ' }} ({{ $attempt->subject_code ?? 'N/A' }})
                            </p>
                            <p class="text-gray-600 text-sm mb-1">
                                <strong>คะแนนที่ได้:</strong> <span class="text-blue-600 font-bold">{{ $attempt->user_score }}</span> / {{ $attempt->quiz_total_score }}
                            </p>
                            <p class="text-gray-600 text-sm">
                                <strong>วันที่ทำ:</strong> {{ \Carbon\Carbon::parse($attempt->finished_at)->translatedFormat('j F Y, H:i') }}
                            </p>
                            
                            @php
                                $percentage = ($attempt->user_score / $attempt->quiz_total_score) * 100;
                            @endphp
                            
                            @if ($percentage >= 80)
                                <div class="mt-4">
                                    <button 
                                        type="button" 
                                        class="inline-block bg-green-500 text-white font-bold py-2 px-4 rounded-md hover:bg-green-600 transition-colors duration-200 text-center w-full"
                                        onclick="showCertificateModal('{{ $attempt->quiz_title }}', '{{ $attempt->user_score }}', '{{ $attempt->quiz_total_score }}')">
                                        <i class="fas fa-award mr-2"></i> แสดงเกียรติบัตร
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-600">
                    <p class="text-lg">ยังไม่มีประวัติการทำแบบทดสอบ</p>
                    <p class="text-sm mt-2">เมื่อคุณทำแบบทดสอบเสร็จสิ้น ประวัติจะปรากฏที่นี่</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal สำหรับแสดงเกียรติบัตร --}}
    <div id="certificate-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 m-4 w-full md:max-w-4xl max-h-[90vh] overflow-auto">
            <div class="flex justify-between items-center border-b pb-3 mb-3">
                <h3 class="text-xl font-semibold text-gray-800">เกียรติบัตร</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideCertificateModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="certificate-content" class="text-center overflow-hidden">
                {{-- เนื้อหาเกียรติบัตรจะถูกสร้างด้วย JavaScript --}}
            </div>
            <div class="flex justify-center mt-4">
                <a id="download-certificate-link" href="#" download="certificate.pdf" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    ดาวน์โหลดเกียรติบัตร
                </a>
            </div>
        </div>
    </div>

    {{-- Modal สำหรับแจ้งเตือนทั่วไป --}}
    <div id="general-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 m-4 w-full md:max-w-md max-h-[90vh] overflow-auto">
            <div class="flex justify-between items-center border-b pb-3 mb-3">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-800"></h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideGeneralModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="modal-content" class="text-gray-700">
                {{-- เนื้อหาจะถูกเพิ่มด้วย JavaScript --}}
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-md hover:bg-gray-300 transition-colors duration-200" onclick="hideGeneralModal()">ปิด</button>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        const certificateModal = document.getElementById('certificate-modal');
        const certificateContent = document.getElementById('certificate-content');
        const downloadLink = document.getElementById('download-certificate-link');
        
        // กำหนด URL ของรูปภาพ
        const backgroundUrl = 'https://phothongdlec.ac.th/storage/certificate/certi1.png';
        const signatureUrl = 'https://phothongdlec.ac.th/storage/certificate/signature.png';
        const logoUrl = 'https://phothongdlec.ac.th/storage/logo.png';

        async function showCertificateModal(quizTitle, userScore, totalScore) {
            const userName = '{{ auth()->user()->name }}';
            const date = new Date().toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' });
            
            const content = `
                <div id="certificate-template" class="relative w-full overflow-hidden" style="padding-top: 70.71%; background-image: url('${backgroundUrl}'); background-size: cover; background-position: center; font-family: 'Sarabun', sans-serif;">
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-4 text-gray-800 md:p-8">
                        <div class="w-full flex justify-center mt-4 md:mt-4">
                            <img src="${logoUrl}" alt="Logo" class="w-16 h-16 md:w-20 md:h-20">
                        </div>
                        
                        <h2 class="mt-4 text-sm font-bold text-center md:text-xl lg:text-3xl">เกียรติบัตรนี้ให้ไว้เพื่อแสดงว่า</h2>
                        <p class="mt-2 text-xl font-extrabold text-center text-blue-600 md:mt-4 md:text-2xl lg:text-4xl">${userName}</p>
                        <p class="mt-2 text-xs text-center md:mt-4 md:text-sm lg:text-base">ได้ผ่านการทดสอบแบบวัดความรู้</p>
                        <p class="mt-2 text-sm font-bold text-center text-green-700 md:text-xl lg:text-2xl">${quizTitle}</p>
                        <p class="mt-2 text-xs text-center md:text-sm lg:text-base">ได้รับคะแนน ${userScore} จาก ${totalScore} คะแนน</p>
                        
                        <div class="w-full max-w-sm mx-auto mt-4 text-center text-xs md:mt-8 md:text-sm lg:text-base">
                            <p class="text-gray-600">ให้ไว้ ณ วันที่ ${date}</p>
                            <p class="flex justify-center">
                                <img src="${signatureUrl}" alt="Signature" class="w-54 h-24">
                            </p>
                            <p class="font-bold">นางสาวกุหลาบ อ่อนระทวย</p>
                            <p class="text-xs md:text-sm">ผู้อำนวยการศูนย์ส่งเสริมการเรียนรู้ระดับอำเภอโพธิ์ทอง</p>
                        </div>
                    </div>
                </div>
            `;
            
            certificateContent.innerHTML = content;
            certificateModal.classList.remove('hidden');

            downloadLink.onclick = async (e) => {
                e.preventDefault();
                await downloadCertificate(userName, quizTitle);
            };
        }

        function hideCertificateModal() {
            certificateModal.classList.add('hidden');
        }

        async function downloadCertificate(userName, quizTitle) {
            const { jsPDF } = window.jspdf;
            const certificateTemplate = document.getElementById('certificate-template');

            const canvas = await html2canvas(certificateTemplate, {
                scale: 2, // เพิ่มความละเอียด
                useCORS: true, // อนุญาตให้โหลดรูปภาพจากต่างโดเมน
                allowTaint: true, // ช่วยให้ภาพพื้นหลังถูกเรนเดอร์ได้
            });

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('l', 'mm', 'a4');
            
            const imgWidth = 297; // A4 width in mm
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save(`เกียรติบัตร_${userName}_${quizTitle}.pdf`);
        }

        const generalModal = document.getElementById('general-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');

        function showGeneralModal(title, message, isSuccess = false) {
            modalTitle.textContent = title;
            modalContent.innerHTML = message;

            if (isSuccess) {
                modalTitle.classList.remove('text-red-700');
                modalTitle.classList.add('text-green-700');
            } else {
                modalTitle.classList.remove('text-green-700');
                modalTitle.classList.add('text-red-700');
            }

            generalModal.classList.remove('hidden');
        }

        function hideGeneralModal() {
            generalModal.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                @if (session('attempt_details'))
                    const details = @json(session('attempt_details'));

                    let message = `
                        <p class="mb-2 text-lg font-semibold">${details.quizTitle}</p>
                        <p><strong>ชื่อวิชา:</strong> ${details.subjectName}</p>
                        <p><strong>รหัสวิชา:</strong> ${details.subjectCode}</p>
                        <p><strong>วันที่ทำ:</strong> ${details.attemptDate}</p>
                        <p><strong>จำนวนข้อที่ทำ:</strong> ${details.answeredQuestions} / ${details.totalQuestions} ข้อ</p>
                        <p><strong>คะแนนที่ได้:</strong> ${details.score} / ${details.totalQuizScore} คะแนน</p>
                        <p><strong>ระยะเวลาที่ใช้:</strong> ${details.timeTaken}</p>
                        <p class="mt-4">${@json(session('success'))}</p>
                    `;
                    showGeneralModal('ผลการทำแบบทดสอบ', message, true);
                @else
                    showGeneralModal('สำเร็จ!', '{{ session('success') }}', true);
                @endif
            @endif

            @if (session('error'))
                showGeneralModal('เกิดข้อผิดพลาด!', '{{ session('error') }}', false);
            @endif

            const validationErrorsDiv = document.getElementById('validation-errors-message');
            if (validationErrorsDiv && validationErrorsDiv.innerHTML.trim() !== '') {
                showGeneralModal('ข้อมูลไม่ถูกต้อง', validationErrorsDiv.innerHTML, false);
                validationErrorsDiv.remove();
            }
        });
    </script>
</x-app-layout>