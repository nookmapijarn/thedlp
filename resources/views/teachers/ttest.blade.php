<x-teachers-layout>
    <div class="p-4 sm:ml-64 mt-20">
        <div class="container bg-white p-5 rounded-lg shadow-md max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">จัดการแบบทดสอบ</h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">สำเร็จ!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">ข้อมูลไม่ถูกต้อง:</strong>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <button type="button" onclick="openCreateQuizModal()" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                    + เพิ่มแบบทดสอบใหม่
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">รหัสวิชา</th>
                            <th class="py-3 px-6 text-left">ชื่อแบบทดสอบ</th>
                            <th class="py-3 px-6 text-center">คะแนนรวม</th>
                            <th class="py-3 px-6 text-center">เวลาจำกัด (นาที)</th>
                            <th class="py-3 px-6 text-center">สร้างเมื่อ</th>
                            <th class="py-3 px-6 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse ($quizzes as $quiz)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $quiz->subject_code}}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $quiz->title }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span>{{ $quiz->total_score }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span>{{ $quiz->time_limit }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span>{{ \Carbon\Carbon::parse($quiz->created_at)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        {{-- ปุ่มแก้ไข --}}
                                        <button type="button" onclick="openEditQuizModal({{json_encode($quiz)}})" class="w-8 h-8 mr-2 transform hover:text-blue-500 hover:scale-110 flex justify-center items-center" title="แก้ไข">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        {{-- ปุ่มลบ --}}
                                        <form action="{{ route('ttest.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่ต้องการลบแบบทดสอบนี้? การลบจะรวมถึงคำถามและตัวเลือกทั้งหมดที่เกี่ยวข้อง');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 transform hover:text-red-500 hover:scale-110 flex justify-center items-center" title="ลบ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-3 px-6 text-center text-gray-500">
                                    ยังไม่มีแบบทดสอบที่คุณสร้างไว้
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal สำหรับสร้างแบบทดสอบใหม่ (Minimal) --}}
    <div id="createQuizModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm">

        <div class="w-full max-w-3xl mx-4 bg-white rounded-2xl shadow-xl
                    max-h-[90vh] flex flex-col">

            {{-- Header (ไม่เลื่อน) --}}
            <div class="flex items-center justify-between px-6 py-4 border-b shrink-0">
                <h3 class="text-lg font-semibold text-gray-800">
                    สร้างแบบทดสอบใหม่
                </h3>
                <button onclick="closeCreateQuizModal()"
                        class="text-gray-400 hover:text-gray-600 transition">
                    ✕
                </button>
            </div>

            {{-- Body (เลื่อนได้) --}}
            <form id="createQuizForm"
                action="{{ route('ttest.store') }}"
                method="POST"
                class="px-6 py-6 space-y-6 overflow-y-auto">
                @csrf

                {{-- ข้อมูลแบบทดสอบ --}}
                <section class="space-y-4">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                        ข้อมูลแบบทดสอบ
                    </h4>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">ชื่อแบบทดสอบ</label>
                        <input type="text" name="quiz_title" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">คำอธิบาย</label>
                        <textarea name="quiz_description"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm min-h-[80px]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">รหัสวิชา</label>
                            <input type="text" name="subject_code" required
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">เวลาจำกัด (นาที)</label>
                            <input type="number" name="time_limit" min="0"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </section>

                {{-- คำถาม --}}
                <section class="space-y-4 pt-4 border-t">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide">
                        คำถาม
                    </h4>

                    <div id="create_questions_container" class="space-y-4">
                        {{-- JS inject --}}
                    </div>

                    <button type="button"
                            onclick="addQuestion('create_')"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                                text-green-700 bg-green-100 rounded-lg hover:bg-green-200 transition">
                        ＋ เพิ่มคำถาม
                    </button>
                </section>
            </form>

            {{-- Footer (ไม่เลื่อน) --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t shrink-0 bg-white">
                <button type="button"
                        onclick="closeCreateQuizModal()"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">
                    ยกเลิก
                </button>

                <button type="submit"
                        form="createQuizForm"
                        class="px-6 py-2 text-sm font-semibold text-white
                            bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    บันทึกแบบทดสอบ
                </button>
            </div>

        </div>
    </div>



    {{-- Modal สำหรับแก้ไขแบบทดสอบ --}}
    <div id="editQuizModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl font-bold text-gray-800">แก้ไขแบบทดสอบ</h3>
                <div class="modal-close cursor-pointer z-50" onclick="closeEditQuizModal()">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <path d="M14.53 4.53L9 10.06 3.47 4.53 2 5.94l6.53 6.53L14.53 17l1.41-1.41L9.94 9l6.53-6.53z"/>
                    </svg>
                </div>
            </div>

            <form id="editQuizForm" method="POST">
                @csrf
                @method('PUT') {{-- ใช้ method PUT สำหรับการอัปเดต --}}
                <input type="hidden" id="edit_quiz_id" name="quiz_id">

                <h2 class="text-2xl font-semibold mb-4 text-gray-700">ข้อมูลแบบทดสอบ</h2>
                <div class="mb-4">
                    <label for="edit_quiz_title" class="block text-gray-700 text-sm font-bold mb-2">ชื่อแบบทดสอบ:</label>
                    <input type="text" id="edit_quiz_title" name="quiz_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="edit_quiz_description" class="block text-gray-700 text-sm font-bold mb-2">คำอธิบาย:</label>
                    <textarea id="edit_quiz_description" name="quiz_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline resize-y min-h-[60px]"></textarea>
                </div>

                <div class="mb-4">
                    <label for="edit_subject_code" class="block text-gray-700 text-sm font-bold mb-2">รหัสวิชา (SUB_CODE):</label>
                    <input type="text" id="edit_subject_code" name="subject_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-6">
                    <label for="edit_time_limit" class="block text-gray-700 text-sm font-bold mb-2">เวลาจำกัด (นาที):</label>
                    <input type="number" id="edit_time_limit" name="time_limit" min="0" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <hr class="my-6 border-gray-300">

                <h2 class="text-2xl font-semibold mb-4 text-gray-700">คำถาม</h2>
                <div id="edit_questions_container">
                    {{-- Questions for editing will be added here by JavaScript --}}
                </div>

                <button type="button" class="mt-4 px-6 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-700 focus:outline-none focus:shadow-outline add-item" onclick="addQuestion('edit_')">เพิ่มคำถาม</button>

                <div class="mt-8 text-right">
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</x-teachers-layout>

<script>
    let createQuestionCount = 0; // สำหรับ Modal สร้างใหม่
    let editQuestionCount = 0;   // สำหรับ Modal แก้ไข

    // Helper เพื่อสร้าง ID ที่ไม่ซ้ำกันสำหรับองค์ประกอบที่สร้างแบบไดนามิก
    function generateUniqueId() {
        return Date.now() + Math.floor(Math.random() * 1000);
    }

    // Function เพื่อ populate คำถามและตัวเลือกสำหรับ Modal (ใช้สำหรับแก้ไขและเริ่มต้นการสร้าง)
    function populateQuestions(questionsData, prefix = '') {
        const questionsContainer = document.getElementById(`${prefix}questions_container`);
        questionsContainer.innerHTML = ''; // ล้างคำถามที่มีอยู่เดิม

        let currentQuestionInternalIndex = 0; // ใช้ Index ภายในสำหรับ ID ที่ไม่ซ้ำกันในฟอร์ม
        if (prefix === 'edit_') {
            editQuestionCount = 0; // รีเซ็ตตัวนับคำถามสำหรับ Modal แก้ไข
        } else {
            createQuestionCount = 0; // รีเซ็ตตัวนับคำถามสำหรับ Modal สร้างใหม่
        }

        questionsData.forEach((question, questionDisplayIndex) => {
            currentQuestionInternalIndex = (prefix === 'edit_') ? ++editQuestionCount : ++createQuestionCount;

            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block border border-gray-200 p-4 mb-5 rounded-md bg-gray-50';
            // ใช้ ID เฉพาะสำหรับ block เพื่อการลบที่ง่ายขึ้น
            questionBlock.id = `${prefix}question_block_${currentQuestionInternalIndex}`;

            let choicesHtml = '';
            let hasExistingChoices = false;

            if (question.question_type === 'multiple_choice' && question.choices && question.choices.length > 0) {
                hasExistingChoices = true;
                question.choices.forEach((choice) => {
                    const isCorrectChecked = choice.is_correct ? 'checked' : '';
                    // ใช้ ID ที่ไม่ซ้ำกันสำหรับแต่ละรายการตัวเลือกสำหรับ JS และเพื่อให้ชื่อในฟอร์มไม่ซ้ำกัน
                    const choiceUniqueId = generateUniqueId(); // ใช้ generateUniqueId() สำหรับ key ในฟอร์ม เพื่อให้ไม่ซ้ำกันเมื่อมีการลบและเพิ่มใหม่

                    choicesHtml += `
                        <div class="choice-item flex items-center mb-3" id="${prefix}choice_item_${currentQuestionInternalIndex}_${choiceUniqueId}">
                            ${(choice.id ? `<input type="hidden" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][id]" value="${choice.id}">` : '')}
                            <input type="hidden" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="0">
                            <input type="checkbox" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded" ${isCorrectChecked}>
                            <label class="ml-2 text-gray-700 mr-4">เป็นคำตอบที่ถูกต้อง</label>
                            <input type="text" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][choice_text]" placeholder="ข้อความตัวเลือก" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-grow" required value="${choice.choice_text || ''}">
                            <button type="button" class="ml-2 px-3 py-1 bg-red-400 text-white rounded hover:bg-red-600 focus:outline-none focus:shadow-outline remove-item" onclick="removeChoice(this)">ลบ</button>
                        </div>
                    `;
                });
            } else if (question.question_type === 'multiple_choice' && !hasExistingChoices) {
                // ถ้าเป็นปรนัยแต่ไม่มีตัวเลือก (เช่น คำถามใหม่) ให้เพิ่มตัวเลือกเริ่มต้น
                for (let i = 0; i < 2; i++) {
                    const choiceUniqueId = generateUniqueId();
                    choicesHtml += `
                        <div class="choice-item flex items-center mb-3" id="${prefix}choice_item_${currentQuestionInternalIndex}_${choiceUniqueId}">
                            <input type="hidden" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="0">
                            <input type="checkbox" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                            <label class="ml-2 text-gray-700 mr-4">เป็นคำตอบที่ถูกต้อง</label>
                            <input type="text" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][choice_text]" placeholder="ข้อความตัวเลือก" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-grow" required>
                            <button type="button" class="ml-2 px-3 py-1 bg-red-400 text-white rounded hover:bg-red-600 focus:outline-none focus:shadow-outline remove-item" onclick="removeChoice(this)">ลบ</button>
                        </div>
                    `;
                }
            }
            // เพิ่มปุ่ม "เพิ่มตัวเลือก" สำหรับคำถามปรนัย
            choicesHtml += `<button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-700 focus:outline-none focus:shadow-outline add-item" onclick="addChoice(${currentQuestionInternalIndex}, '${prefix}')">เพิ่มตัวเลือก</button>`;

            questionBlock.innerHTML = `
                <h3 class="text-xl font-semibold mb-4 text-gray-700">คำถามที่ ${questionDisplayIndex + 1}</h3>
                ${(question.id ? `<input type="hidden" name="questions[${currentQuestionInternalIndex}][id]" value="${question.id}">` : '')}
                <div class="mb-4">
                    <label for="${prefix}question_text_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">ข้อความคำถาม:</label>
                    <textarea id="${prefix}question_text_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][question_text]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline resize-y min-h-[60px]" required>${question.question_text || ''}</textarea>
                </div>

                <div class="mb-4">
                    <label for="${prefix}question_type_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">ประเภทคำถาม:</label>
                    <select id="${prefix}question_type_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][question_type]" onchange="toggleChoices(${currentQuestionInternalIndex}, '${prefix}')" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="multiple_choice" ${question.question_type === 'multiple_choice' ? 'selected' : ''}>ปรนัย (Multiple Choice)</option>
                        <option value="true_false" ${question.question_type === 'true_false' ? 'selected' : ''}>ถูก/ผิด (True/False)</option>
                        <option value="short_answer" ${question.question_type === 'short_answer' ? 'selected' : ''}>อัตนัย (Short Answer)</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="${prefix}question_score_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">คะแนน:</label>
                    <input type="number" id="${prefix}question_score_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][score]" min="1" value="${question.score || 1}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div id="${prefix}choices_container_${currentQuestionInternalIndex}" class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-lg font-semibold mb-3 text-gray-700">ตัวเลือก (สำหรับปรนัย)</h4>
                    ${choicesHtml}
                </div>
                <button type="button" class="mt-4 px-4 py-2 bg-red-500 text-white font-bold rounded hover:bg-red-700 focus:outline-none focus:shadow-outline remove-item" onclick="removeQuestion(${currentQuestionInternalIndex}, '${prefix}')">ลบคำถาม</button>
            `;
            questionsContainer.appendChild(questionBlock);
            toggleChoices(currentQuestionInternalIndex, prefix); // ตั้งค่าเริ่มต้นตามประเภทคำถาม
        });

        // กำหนดตัวนับ global ตาม index ภายในล่าสุดที่ใช้
        if (prefix === 'edit_') {
            editQuestionCount = currentQuestionInternalIndex;
        } else {
            createQuestionCount = currentQuestionInternalIndex;
        }
        updateQuestionDisplayNumbers(prefix); // อัปเดตหมายเลขที่แสดงหลังจาก populate
    }

    // Function เพื่อเพิ่มคำถามใหม่ใน Modal ที่ระบุ (สร้างหรือแก้ไข)
    function addQuestion(prefix = '') {
        let currentQuestionInternalIndex;
        let questionDisplayNumber; // สำหรับการแสดงผลเท่านั้น

        if (prefix === 'edit_') {
            editQuestionCount++;
            currentQuestionInternalIndex = editQuestionCount;
        } else {
            createQuestionCount++;
            currentQuestionInternalIndex = createQuestionCount;
        }
        questionDisplayNumber = document.querySelectorAll(`#${prefix}questions_container .question-block`).length + 1;


        const questionsContainer = document.getElementById(`${prefix}questions_container`);
        const questionBlock = document.createElement('div');
        questionBlock.className = 'question-block border border-gray-200 p-4 mb-5 rounded-md bg-gray-50';
        questionBlock.id = `${prefix}question_block_${currentQuestionInternalIndex}`;

        // ตัวเลือกเริ่มต้นสำหรับคำถามปรนัยใหม่
        let initialChoicesHtml = '';
        for (let i = 0; i < 2; i++) { // เพิ่มตัวเลือกเริ่มต้น 2 ตัว
            const choiceUniqueId = generateUniqueId();
            initialChoicesHtml += `
                <div class="choice-item flex items-center mb-3" id="${prefix}choice_item_${currentQuestionInternalIndex}_${choiceUniqueId}">
                    <input type="hidden" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="0">
                    <input type="checkbox" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][is_correct]" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                    <label class="ml-2 text-gray-700 mr-4">เป็นคำตอบที่ถูกต้อง</label>
                    <input type="text" name="questions[${currentQuestionInternalIndex}][choices][${choiceUniqueId}][choice_text]" placeholder="ข้อความตัวเลือก" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-grow" required>
                    <button type="button" class="ml-2 px-3 py-1 bg-red-400 text-white rounded hover:bg-red-600 focus:outline-none focus:shadow-outline remove-item" onclick="removeChoice(this)">ลบ</button>
                </div>
            `;
        }
        initialChoicesHtml += `<button type="button" class="mt-2 px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-700 focus:outline-none focus:shadow-outline add-item" onclick="addChoice(${currentQuestionInternalIndex}, '${prefix}')">เพิ่มตัวเลือก</button>`;


        questionBlock.innerHTML = `
            <h3 class="text-xl font-semibold mb-4 text-gray-700">คำถามที่ ${questionDisplayNumber}</h3>
            <div class="mb-4">
                <label for="${prefix}question_text_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">ข้อความคำถาม:</label>
                <textarea id="${prefix}question_text_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][question_text]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline resize-y min-h-[60px]" required></textarea>
            </div>

            <div class="mb-4">
                <label for="${prefix}question_type_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">ประเภทคำถาม:</label>
                <select id="${prefix}question_type_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][question_type]" onchange="toggleChoices(${currentQuestionInternalIndex}, '${prefix}')" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="multiple_choice" selected>ปรนัย (Multiple Choice)</option>
                    <option value="true_false">ถูก/ผิด (True/False)</option>
                    <option value="short_answer">อัตนัย (Short Answer)</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="${prefix}question_score_${currentQuestionInternalIndex}" class="block text-gray-700 text-sm font-bold mb-2">คะแนน:</label>
                <input type="number" id="${prefix}question_score_${currentQuestionInternalIndex}" name="questions[${currentQuestionInternalIndex}][score]" min="1" value="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div id="${prefix}choices_container_${currentQuestionInternalIndex}" class="border-t border-gray-200 pt-4 mt-4">
                <h4 class="text-lg font-semibold mb-3 text-gray-700">ตัวเลือก (สำหรับปรนัย)</h4>
                ${initialChoicesHtml}
            </div>
            <button type="button" class="mt-4 px-4 py-2 bg-red-500 text-white font-bold rounded hover:bg-red-700 focus:outline-none focus:shadow-outline remove-item" onclick="removeQuestion(${currentQuestionInternalIndex}, '${prefix}')">ลบคำถาม</button>
        `;
        questionsContainer.appendChild(questionBlock);
        toggleChoices(currentQuestionInternalIndex, prefix); // ตั้งค่าเริ่มต้นตามประเภทคำถาม
        updateQuestionDisplayNumbers(prefix); // อัปเดตหมายเลขที่แสดงหลังจากเพิ่ม
    }

    // Function เพื่อลบคำถามออกจาก Modal ที่ระบุ
    function removeQuestion(internalId, prefix = '') {
        const questionToRemove = document.getElementById(`${prefix}question_block_${internalId}`);
        if (questionToRemove) {
            questionToRemove.remove();
        }
        updateQuestionDisplayNumbers(prefix); // อัปเดตหมายเลขที่แสดงหลังจากลบ
    }

    // Helper function เพื่ออัปเดตหมายเลขที่แสดงของคำถาม
    function updateQuestionDisplayNumbers(prefix = '') {
        const questionsContainer = document.getElementById(`${prefix}questions_container`);
        const questionBlocks = questionsContainer.querySelectorAll('.question-block');
        questionBlocks.forEach((block, index) => {
            block.querySelector('h3').innerText = `คำถามที่ ${index + 1}`;
        });
    }

    // Function เพื่อเพิ่มตัวเลือกในคำถามที่ระบุใน Modal ที่ระบุ
    function addChoice(questionInternalId, prefix = '') {
        const choicesContainer = document.getElementById(`${prefix}choices_container_${questionInternalId}`);
        const choiceUniqueId = generateUniqueId(); // สร้าง ID ที่ไม่ซ้ำกันอย่างแท้จริงสำหรับตัวเลือกใหม่

        const choiceItem = document.createElement('div');
        choiceItem.className = 'choice-item flex items-center mb-3';
        choiceItem.id = `${prefix}choice_item_${questionInternalId}_${choiceUniqueId}`; // กำหนด ID เพื่อการลบที่ง่ายขึ้น

        choiceItem.innerHTML = `
            <input type="hidden" name="questions[${questionInternalId}][choices][${choiceUniqueId}][is_correct]" value="0">
            <input type="checkbox" name="questions[${questionInternalId}][choices][${choiceUniqueId}][is_correct]" value="1" class="form-checkbox h-4 w-4 text-blue-600 rounded">
            <label class="ml-2 text-gray-700 mr-4">เป็นคำตอบที่ถูกต้อง</label>
            <input type="text" name="questions[${questionInternalId}][choices][${choiceUniqueId}][choice_text]" placeholder="ข้อความตัวเลือก" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline flex-grow" required>
            <button type="button" class="ml-2 px-3 py-1 bg-red-400 text-white rounded hover:bg-red-600 focus:outline-none focus:shadow-outline remove-item" onclick="removeChoice(this)">ลบ</button>
        `;
        // หาปุ่ม "เพิ่มตัวเลือก" ที่อยู่ล่างสุดแล้วแทรกรายการใหม่ก่อนหน้านั้น
        const addButton = choicesContainer.querySelector('button.add-item');
        if (addButton) {
            choicesContainer.insertBefore(choiceItem, addButton);
        } else {
            choicesContainer.appendChild(choiceItem);
        }
    }

    // Function เพื่อลบตัวเลือก (เมื่อคลิกปุ่ม "ลบ" ภายใน choice-item)
    function removeChoice(buttonElement) {
        buttonElement.closest('.choice-item').remove();
    }

    // Function เพื่อสลับการแสดงผลของตัวเลือกตามประเภทคำถาม
    function toggleChoices(questionInternalId, prefix = '') {
        const questionTypeSelect = document.getElementById(`${prefix}question_type_${questionInternalId}`);
        const choicesContainer = document.getElementById(`${prefix}choices_container_${questionInternalId}`);

        if (questionTypeSelect.value === 'multiple_choice') {
            choicesContainer.style.display = 'block';
            // ทำให้ input choices ที่มีอยู่ required อีกครั้ง หากเปลี่ยนกลับมาเป็น multiple_choice
            choicesContainer.querySelectorAll('input[type="text"][name$="[choice_text]"]').forEach(input => {
                input.setAttribute('required', 'required');
            });
        } else {
            choicesContainer.style.display = 'none';
            // ลบ required ออกจาก input choices เมื่อเปลี่ยนไปใช้ประเภทอื่น
            choicesContainer.querySelectorAll('input[type="text"][name$="[choice_text]"]').forEach(input => {
                input.removeAttribute('required');
            });
        }
    }

    // Modal Functions
    function openCreateQuizModal() {
        document.getElementById('createQuizModal').classList.remove('hidden');
        // รีเซ็ตฟอร์มเมื่อเปิด modal ใหม่
        document.getElementById('createQuizForm').reset();
        document.getElementById('create_questions_container').innerHTML = ''; // ล้างคำถามเก่า
        createQuestionCount = 0; // รีเซ็ตตัวนับ
        addQuestion('create_'); // เพิ่มคำถามแรกเริ่มต้น
    }

    function closeCreateQuizModal() {
        document.getElementById('createQuizModal').classList.add('hidden');
        // Clear validation errors on close
        // (ถ้าคุณใช้ JavaScript ในการแสดง validation errors)
    }

    function openEditQuizModal(quiz) {
        const editForm = document.getElementById('editQuizForm');
        editForm.action = `/teachers/ttest/${quiz.id}`; // ตั้งค่า action ของฟอร์ม

        document.getElementById('edit_quiz_id').value = quiz.id;
        document.getElementById('edit_quiz_title').value = quiz.title;
        document.getElementById('edit_quiz_description').value = quiz.description || '';
        document.getElementById('edit_subject_code').value = quiz.subject_code || ''; // ใช้ subject_code ที่ดึงมาจาก Controller
        document.getElementById('edit_time_limit').value = quiz.time_limit;

        // ดึงข้อมูลคำถามและตัวเลือกสำหรับแบบทดสอบนี้
        // เนื่องจาก Controller ไม่ได้ส่ง questions มาพร้อมกับ quiz ใน method index
        // เราต้องเรียก AJAX หรือให้ Controller ส่งมาใน route edit (ซึ่งยังไม่มี route edit ใน code ที่ให้มา)
        // **สำหรับตอนนี้ เราจะสมมติว่า quiz object ที่ถูกส่งมาจากการคลิกปุ่มแก้ไข มี `questions` array อยู่แล้ว**
        // หากไม่มี คุณจะต้องปรับปรุง Controller's `index` method ให้ดึง questions และ choices มาด้วย
        // หรือสร้าง route 'ttest.edit' ใน Controller เพื่อโหลดข้อมูลเต็มรูปแบบของ Quiz
        // แต่ตามที่เห็นใน Controller, method `edit($id)` จะส่ง `quiz` และ `questions` มา
        // ดังนั้นโค้ดนี้จะใช้ได้ถ้าคุณเรียก Modal จากหน้า `edit-ttest` หรือใช้ AJAX
        // แต่ตามโครงสร้างปัจจุบันในหน้า `ttest.blade.php`, `quiz` มาจาก `$quizzes` ซึ่งไม่มี questions
        // ดังนั้น จะต้องปรับปรุง `index` method ใน Controller หรือเพิ่ม AJAX call เพื่อดึงข้อมูลเต็ม
        // **เพื่อความสอดคล้องกับ `edit($id)` method ของ Controller ที่คุณให้มา เราจะสมมติว่า `quiz` object ที่ส่งมามี `questions` array อยู่แล้ว**

        // ถ้า quiz object ที่ส่งมาไม่มี questions คุณอาจต้องปรับ Controller's index method
        // หรือใช้วิธีเรียก AJAX เพื่อดึงข้อมูล quiz, questions, choices เต็มรูปแบบ
        // (สำหรับตัวอย่างนี้ ผมจะใช้ข้อมูลที่สมมติว่ามีอยู่ใน quiz object โดยตรง)

        if (quiz.questions && quiz.questions.length > 0) {
            populateQuestions(quiz.questions, 'edit_');
        } else {
            // หากไม่มีคำถาม ให้เพิ่มคำถามแรกเริ่มต้น (กรณีสร้างใหม่ในโหมดแก้ไข หรือไม่มีคำถาม)
            document.getElementById('edit_questions_container').innerHTML = '';
            editQuestionCount = 0;
            addQuestion('edit_');
        }

        document.getElementById('editQuizModal').classList.remove('hidden');
    }

    function closeEditQuizModal() {
        document.getElementById('editQuizModal').classList.add('hidden');
        // Clear validation errors on close
    }

    // Initialize the first question for the create modal when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        // หากต้องการให้มีคำถามเริ่มต้น 1 ข้อ เมื่อโหลดหน้า
        // แต่เนื่องจาก Modal สร้างใหม่ซ่อนอยู่ และมี openCreateQuizModal ที่เรียก addQuestion() อยู่แล้ว
        // จึงไม่จำเป็นต้องเรียกตรงนี้อีก
    });

</script>