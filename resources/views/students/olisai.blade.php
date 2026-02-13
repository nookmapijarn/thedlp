<x-app-layout>
    
    {{-- 💡 CDN สำหรับ Marked.js (แปลง Markdown) และ DOMPurify (ความปลอดภัย) --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
    
    <div class="">
        {{-- ปรับ h-full เป็น h-screen เพื่อให้กล่องแชทเต็มความสูงของหน้าจอ --}}
        <div class="container mx-auto max-w-4xl p-4 flex flex-col h-full">

            <main class="flex-1 flex flex-col overflow-hidden">
                <header class="border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-center flex-shrink-0">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-semibold dark:text-white">Olis AI Assistant</h2>
                    </div>
                </header>

                {{-- กล่องแชท: ใช้ overflow-y-scroll เพื่อให้สามารถเลื่อนดูข้อความได้ --}}
                <div id="chat-box" class="flex-1 overflow-y-scroll p-4 space-y-6">
                    @if($conversations->isEmpty())
                        <div class="flex justify-center my-16">
                            <div class="text-center text-gray-500 italic">
                                ฉันคือ Olis AI ผู้ช่วยด้านการศึกษาของคุณ <br> มีอะไรให้ช่วยไหม?
                            </div>
                        </div>
                    @else
                        @foreach($conversations as $conversation)
                            {{-- ข้อความคำถามของผู้ใช้ (ฟังก์ชันเดิม) --}}
                            <div class="flex justify-end max-w-3xl ml-auto">
                                <div class="bg-blue-500 text-white rounded-lg p-4 shadow-sm break-words">
                                    {{ $conversation->user_question }}
                                </div>
                            </div>
                            
                            {{-- ข้อความคำตอบของ AI: ปรับปรุงการแสดงผล Markdown --}}
                            <div class="flex gap-4 max-w-3xl mx-auto">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium dark:text-white mb-1">Olis AI</div>
                                    {{-- 💡 เพิ่มคลาส 'prose' สำหรับการจัดรูปแบบ Markdown --}}
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm dark:text-gray-200 prose dark:prose-invert max-w-none">
                                        <div id="ai-response-{{ $conversation->id }}" class="ai-response-content break-words">
                                            {{-- เนื้อหาจะถูกเติมโดย JavaScript ด้านล่างนี้ --}}
                                        </div>
                                        {{-- 💡 สคริปต์เพื่อแปลงข้อความ Markdown ที่โหลดมาแล้ว (สำหรับข้อความเก่า) --}}
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const markdownText = {!! json_encode($conversation->ai_response) !!};
                                                if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                                                    const cleanHtml = DOMPurify.sanitize(marked.parse(markdownText));
                                                    document.getElementById('ai-response-{{ $conversation->id }}').innerHTML = cleanHtml;
                                                }
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4 flex-shrink-0">
                    <form id="chat-form" class="max-w-3xl mx-auto">
                        <div class="bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 flex items-center p-2 shadow-lg">
                            <textarea id="user-input" class="flex-1 w-full p-2 bg-transparent border-0 focus:ring-0 dark:text-white resize-none overflow-hidden" rows="1" placeholder="ถามคำถามเกี่ยวกับผลการเรียนของคุณ..."></textarea>
                            <button type="submit" class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition" aria-label="ส่งข้อความ">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>

<script>
    // Auto-resize the textarea (ฟังก์ชันเดิม)
    const textarea = document.getElementById('user-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');
        const userQuestion = userInput.value.trim();

        if (!userQuestion) {
            return;
        }

        // Add user's question to the chat box (ฟังก์ชันเดิม)
        chatBox.innerHTML += `
            <div class="flex justify-end max-w-3xl ml-auto">
                <div class="bg-blue-500 text-white rounded-lg p-4 shadow-sm break-words">
                    ${userQuestion}
                </div>
            </div>
        `;
        userInput.value = '';
        userInput.style.height = 'auto';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Display a "typing" message from the AI (ฟังก์ชันเดิม)
        const loadingMessage = document.createElement('div');
        loadingMessage.className = 'flex gap-4 max-w-3xl mx-auto loading-message';
        loadingMessage.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="font-medium dark:text-white mb-1">Olis AI</div>
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 shadow-sm animate-pulse dark:text-gray-200">
                    AI กำลังคิด...
                </div>
            </div>
        `;
        chatBox.appendChild(loadingMessage);
        chatBox.scrollTop = chatBox.scrollHeight;

        // Send the question to the server (ฟังก์ชันเดิม)
        fetch('{{ route('olisai.chat') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ question: userQuestion })
        })
        .then(response => response.json())
        .then(data => {
            // ลบข้อความ "กำลังคิด..."
            const currentLoadingMessage = document.querySelector('.loading-message');
            if (currentLoadingMessage) {
                chatBox.removeChild(currentLoadingMessage);
            }

            const aiResponseMarkdown = data.answer || 'ขออภัย เกิดข้อผิดพลาดในการตอบกลับ';
            let cleanHtml = aiResponseMarkdown; 

            // 💡 แปลง Markdown เป็น HTML และทำความสะอาด (สำหรับข้อความใหม่)
            if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                const htmlContent = marked.parse(aiResponseMarkdown);
                cleanHtml = DOMPurify.sanitize(htmlContent);
            }
            // -----------------------------------------------------------------

            // แสดงคำตอบของ AI ที่จัดรูปแบบแล้ว
            chatBox.innerHTML += `
                <div class="flex gap-4 max-w-3xl mx-auto">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium dark:text-white mb-1">Olis AI</div>
                        {{-- 💡 ใช้คลาส prose สำหรับจัดรูปแบบ HTML ที่เกิดจากการแปลง Markdown --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm dark:text-gray-200 prose dark:prose-invert max-w-none">
                            ${cleanHtml}
                        </div>
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            const currentLoadingMessage = document.querySelector('.loading-message');
            if (currentLoadingMessage) {
                chatBox.removeChild(currentLoadingMessage);
            }
            chatBox.innerHTML += `<div class="text-red-500">เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    });
</script>