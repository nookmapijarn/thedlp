<x-app-layout>
    <div class="sm:ml-64">
        <div class="container mx-auto max-w-4xl p-4 flex flex-col h-full">

            <main class="flex-1 flex flex-col">
                <header class="border-b border-gray-200 dark:border-gray-700 p-4 flex items-center justify-center">
                    <div class="flex items-center gap-4">
                        <h2 class="text-2xl font-semibold dark:text-white">Olis AI Assistant</h2>
                    </div>
                </header>

                <div id="chat-box" class="flex-1 overflow-auto p-4 space-y-6">
                    @if($conversations->isEmpty())
                        <div class="flex justify-center my-16">
                            <div class="text-center text-gray-500 italic">
                                ฉันคือ Olis AI ผู้ช่วยด้านการศึกษาของคุณ <br> มีอะไรให้ช่วยไหม?
                            </div>
                        </div>
                    @else
                        @foreach($conversations as $conversation)
                            <div class="flex justify-end max-w-3xl ml-auto">
                                <div class="bg-blue-500 text-white rounded-lg p-4 shadow-sm">
                                    {{ $conversation->user_question }}
                                </div>
                            </div>
                            <div class="flex gap-4 max-w-3xl mx-auto">
                                <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-medium dark:text-white mb-1">Olis AI</div>
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm dark:text-gray-200">
                                        {{ $conversation->ai_response }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4">
                    <form id="chat-form" class="max-w-3xl mx-auto">
                        <div class="bg-white dark:bg-gray-800 rounded-full border border-gray-200 dark:border-gray-700 flex items-center p-2 shadow-lg">
                            <textarea id="user-input" class="flex-1 w-full p-2 bg-transparent border-0 focus:ring-0 dark:text-white resize-none overflow-hidden" rows="1" placeholder="ถามคำถามเกี่ยวกับผลการเรียนของคุณ..."></textarea>
                            <button type="submit" class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
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
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');
        const userQuestion = userInput.value.trim();

        if (!userQuestion) {
            return;
        }

        // Add user's question to the chat box
        chatBox.innerHTML += `
            <div class="flex justify-end max-w-3xl ml-auto">
                <div class="bg-blue-500 text-white rounded-lg p-4 shadow-sm">
                    ${userQuestion}
                </div>
            </div>
        `;
        userInput.value = '';
        userInput.style.height = 'auto';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Display a "typing" message from the AI
        const loadingMessage = document.createElement('div');
        loadingMessage.className = 'flex gap-4 max-w-3xl mx-auto';
        loadingMessage.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 shadow-sm animate-pulse dark:text-gray-200">
                    AI กำลังคิด...
                </div>
            </div>
        `;
        chatBox.appendChild(loadingMessage);
        chatBox.scrollTop = chatBox.scrollHeight;

        // Send the question to the server
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
            chatBox.removeChild(loadingMessage);
            chatBox.innerHTML += `
                <div class="flex gap-4 max-w-3xl mx-auto">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-medium dark:text-white mb-1">Olis AI</div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm dark:text-gray-200">
                            ${data.answer}
                        </div>
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            console.error('Error:', error);
            chatBox.removeChild(loadingMessage);
            chatBox.innerHTML += `<div class="text-red-500">เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง</div>`;
        });
    });

    // Auto-resize the textarea
    const textarea = document.getElementById('user-input');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
</script>