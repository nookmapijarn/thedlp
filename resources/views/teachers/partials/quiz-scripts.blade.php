<script>
let questionCount = 0;

// --- ฟังก์ชันนำเข้าข้อมูลจาก Excel ---
document.getElementById('excel_import').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const firstSheetName = workbook.SheetNames[0];
        const jsonData = XLSX.utils.sheet_to_json(workbook.Sheets[firstSheetName]);

        if (jsonData.length > 0) {
            if (confirm(`พบข้อมูลข้อสอบ ${jsonData.length} ข้อ คุณต้องการเพิ่มเข้าไปในรายการใช่หรือไม่?`)) {
                
                // ปรับ map ให้คืนค่าเป็น Index (0, 1, 2, 3) เพื่อให้ตรงกับ Radio
                const mapAnswerToIndex = (ans) => {
                    if (!ans) return -1;
                    const a = ans.toString().trim().toLowerCase();
                    if (a === 'ก' || a === 'a' || a === '1') return 0;
                    if (a === 'ข' || a === 'b' || a === '2') return 1;
                    if (a === 'ค' || a === 'c' || a === '3') return 2;
                    if (a === 'ง' || a === 'd' || a === '4') return 3;
                    return -1;
                };

                jsonData.forEach(row => {
                    const correctIdx = mapAnswerToIndex(row.answer);

                    const newQuestion = {
                        question_text: row.question_text || '',
                        question_image: row.question_image || row.image || row.image_url || '',
                        score: row.score || 1,
                        question_type: 'multiple_choice',
                        standard: row.standard || '',
                        indicator: row.indicator || '',
                        topic: row.topic || '',
                        taxonomy_level: row.taxonomy_level || '',
                        // ส่งลำดับข้อที่ถูกไปให้ addQuestion
                        correct_answer_index: correctIdx, 
                        choices: [
                            { choice_text: row.choice_1 },
                            { choice_text: row.choice_2 },
                            { choice_text: row.choice_3 },
                            { choice_text: row.choice_4 }
                        ]
                    };
                    addQuestion(newQuestion);
                });
                alert('นำเข้าข้อมูลสำเร็จ!');
            }
        }
    };
    reader.readAsArrayBuffer(file);
});

// --- จัดการรูปภาพในโจทย์ ---
function handleQuestionImageChange(input, idx) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({
            icon: 'warning',
            title: 'ไฟล์มีขนาดใหญ่เกินไป',
            text: 'กรุณาเลือกไฟล์รูปภาพขนาดไม่เกิน 5MB',
            confirmButtonColor: '#4f46e5'
        });
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const base64 = e.target.result;
        document.getElementById(`q_img_base64_${idx}`).value = base64;
        document.getElementById(`q_img_remove_${idx}`).value = "0";

        const previewImg = document.getElementById(`q_img_preview_${idx}`);
        previewImg.src = base64;
        
        document.getElementById(`q_img_preview_container_${idx}`).classList.remove('hidden');
        document.getElementById(`q_img_btn_upload_${idx}`).classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function removeQuestionImage(idx) {
    const fileInput = document.getElementById(`q_img_input_${idx}`);
    if (fileInput) fileInput.value = '';

    document.getElementById(`q_img_base64_${idx}`).value = '';
    document.getElementById(`q_img_url_${idx}`).value = '';
    document.getElementById(`q_img_remove_${idx}`).value = '1';

    const previewImg = document.getElementById(`q_img_preview_${idx}`);
    previewImg.src = '';

    document.getElementById(`q_img_preview_container_${idx}`).classList.add('hidden');
    document.getElementById(`q_img_btn_upload_${idx}`).classList.remove('hidden');
}

function openImagePreviewModal(src) {
    if (!src) return;
    Swal.fire({
        imageUrl: src,
        imageAlt: 'Question Image',
        showConfirmButton: false,
        showCloseButton: true,
        background: '#ffffff',
        customClass: {
            image: 'max-h-[80vh] object-contain rounded-xl'
        }
    });
}

// --- ฟังก์ชันเพิ่มคำถาม (ปรับปรุงใหม่) ---
function addQuestion(q = null) {
    const container = document.getElementById('questions-container');
    const idx = questionCount++;
    const qDiv = document.createElement('div');
    qDiv.className = "p-6 border rounded-xl bg-gray-50 relative question-block mb-4 animate-fade-in";
    qDiv.id = `q-block-${idx}`;

    const hasImage = q && q.question_image && q.question_image.trim() !== '';

    qDiv.innerHTML = `
        <button type="button" onclick="removeQuestion('${idx}')" class="absolute top-4 right-4 text-red-400 hover:text-red-600 font-bold text-sm">ลบข้อนี้</button>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-1 text-lg font-bold text-gray-400 q-number">#</div>
            
            <div class="md:col-span-7">
                <label class="block text-xs font-bold text-gray-500 uppercase">โจทย์คำถาม</label>
                <textarea name="questions[${idx}][question_text]" required class="w-full rounded-lg border-gray-300 mt-1" rows="3" placeholder="ระบุเนื้อหาคำถาม...">${q ? (q.question_text || '') : ''}</textarea>
                
                <!-- ส่วนแนบรูปภาพโจทย์ -->
                <div class="mt-2.5">
                    <input type="file" id="q_img_input_${idx}" accept="image/*" class="hidden" onchange="handleQuestionImageChange(this, '${idx}')">
                    <input type="hidden" name="questions[${idx}][image_base64]" id="q_img_base64_${idx}">
                    <input type="hidden" name="questions[${idx}][question_image]" id="q_img_url_${idx}" value="${hasImage ? q.question_image : ''}">
                    <input type="hidden" name="questions[${idx}][remove_image]" id="q_img_remove_${idx}" value="0">

                    <!-- ปุ่มเปิดเลือกรูปภาพ -->
                    <div id="q_img_btn_upload_${idx}" class="${hasImage ? 'hidden' : ''}">
                        <button type="button" onclick="document.getElementById('q_img_input_${idx}').click()" 
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-white hover:bg-indigo-50 hover:text-indigo-600 border border-dashed border-slate-300 hover:border-indigo-400 rounded-lg text-xs font-bold text-slate-600 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>📷 แนบรูปภาพโจทย์</span>
                        </button>
                    </div>

                    <!-- กล่องพรีวิวรูปภาพ -->
                    <div id="q_img_preview_container_${idx}" class="${hasImage ? '' : 'hidden'} flex items-start gap-3 bg-white p-2.5 rounded-xl border border-slate-200 shadow-sm w-fit max-w-full">
                        <div class="relative group">
                            <img id="q_img_preview_${idx}" src="${hasImage ? q.question_image : ''}" 
                                class="h-24 max-w-[220px] object-contain rounded-lg border border-slate-100 bg-slate-50 cursor-pointer hover:opacity-90 transition-opacity"
                                onclick="openImagePreviewModal(this.src)"
                                title="คลิกเพื่อดูรูปขนาดเต็ม">
                        </div>
                        <div class="flex flex-col gap-1.5 self-center">
                            <button type="button" onclick="document.getElementById('q_img_input_${idx}').click()" 
                                class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-md text-[11px] font-bold transition-all flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                เปลี่ยนรูป
                            </button>
                            <button type="button" onclick="removeQuestionImage('${idx}')" 
                                class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-md text-[11px] font-bold transition-all flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                ลบรูป
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase">คะแนน</label>
                <input type="number" name="questions[${idx}][score]" value="${q ? q.score : 1}" step="0.5" class="w-full rounded-lg border-gray-300 mt-1">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase">ประเภท</label>
                <select name="questions[${idx}][question_type]" class="w-full rounded-lg border-gray-300 mt-1 text-sm">
                    <option value="multiple_choice" ${q && q.question_type == 'multiple_choice' ? 'selected' : ''}>ปรนัย</option>
                </select>
            </div>

            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase text-[10px]">มาตรฐาน (Standard)</label>
                <input type="text" name="questions[${idx}][standard]" value="${q ? q.standard : ''}" class="w-full rounded-lg border-gray-300 mt-1 text-sm">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase text-[10px]">ตัวชี้วัด (Indicator)</label>
                <input type="text" name="questions[${idx}][indicator]" value="${q ? q.indicator : ''}" class="w-full rounded-lg border-gray-300 mt-1 text-sm">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase text-[10px]">เรื่อง/เนื้อหา (Topic)</label>
                <input type="text" name="questions[${idx}][topic]" value="${q ? q.topic : ''}" class="w-full rounded-lg border-gray-300 mt-1 text-sm">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase text-[10px]">Bloom's Taxonomy</label>
                <select name="questions[${idx}][taxonomy_level]" onchange="updateBadge(this, ${idx})" class="w-full rounded-lg border-gray-300 mt-1 text-sm">
                    <option value="">-- เลือกด้านที่วัด --</option>
                    ${[1,2,3,4,5,6].map(v => `<option value="${v}" ${q && q.taxonomy_level == v ? 'selected' : ''}>${v}</option>`).join('')}
                </select>
                <div id="badge-${idx}" class="mt-2"></div>
            </div>
        </div>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="choices-${idx}"></div>
    `;

    container.appendChild(qDiv);
    const choiceContainer = qDiv.querySelector(`#choices-${idx}`);

    // สร้าง Choice 4 ข้อ
    if (q && q.choices) {
        q.choices.forEach((c, cIdx) => {
            // เช็คว่าข้อนี้ถูกไหม (รองรับทั้ง object จาก Excel และ array จาก DB)
            const isCorrect = c.is_correct || (q.correct_answer_index === cIdx);
            addChoice(idx, choiceContainer, c, isCorrect);
        });
    } else {
        for(let i=0; i<4; i++) addChoice(idx, choiceContainer);
    }

    if(q && q.taxonomy_level) updateBadge(qDiv.querySelector('select[name*="taxonomy_level"]'), idx);
    reIndexQuestions();
}

// ปรับฟังก์ชัน addChoice ให้เป็น Radio
// --- ฟังก์ชันเพิ่มตัวเลือก (ปรับเป็น Radio) ---
function addChoice(qIdx, container, c = null, isCorrect = false) {
    const currentChoices = container.querySelectorAll('.choice-item').length;
    const choiceDiv = document.createElement('div');
    choiceDiv.className = "flex items-center gap-2 choice-item bg-white p-2 rounded-lg border border-gray-100 shadow-sm";
    
    choiceDiv.innerHTML = `
        <input type="radio" 
               name="questions[${qIdx}][correct_index]" 
               value="${currentChoices}" 
               ${isCorrect ? 'checked' : ''} 
               class="w-4 h-4 text-indigo-600 focus:ring-indigo-500" required>
        <input type="text" 
               required 
               name="questions[${qIdx}][choices][${currentChoices}][choice_text]" 
               value="${c ? (c.choice_text || c.text || '') : ''}" 
               placeholder="ตัวเลือกที่ ${currentChoices + 1}" 
               class="w-full text-sm border-none focus:ring-0 p-1">
    `;
    container.appendChild(choiceDiv);
}

// --- ฟังก์ชันเสริมอื่นๆ ---
function removeQuestion(idx) {
    document.getElementById(`q-block-${idx}`).remove();
    reIndexQuestions();
}

function reIndexQuestions() {
    document.querySelectorAll('.question-block').forEach((block, i) => {
        block.querySelector('.q-number').innerText = `#${i + 1}`;
    });
}

function updateBadge(select, idx) {
    const badge = document.getElementById(`badge-${idx}`);
    const labels = {
        '1': { text: 'ความจำ', color: 'bg-gray-100 text-gray-600' },
        '2': { text: 'เข้าใจ', color: 'bg-green-100 text-green-700' },
        '3': { text: 'นำไปใช้', color: 'bg-blue-100 text-blue-700' },
        '4': { text: 'วิเคราะห์', color: 'bg-yellow-100 text-yellow-700' },
        '5': { text: 'สังเคราะห์', color: 'bg-purple-100 text-purple-700' },
        '6': { text: 'ประเมินค่า', color: 'bg-red-100 text-red-700' }
    };
    const val = select.value;
    badge.innerHTML = labels[val] ? `<span class="px-2 py-1 rounded text-xs font-bold ${labels[val].color}">${labels[val].text}</span>` : '';
}

// --- ฟังก์ชันตรวจสอบก่อนส่ง (Validation) ---
function validateForm(event) {
    // 1. ตรวจสอบชื่อแบบทดสอบ
    const quizTitle = document.querySelector('input[name="quiz_title"]');
    if (!quizTitle || !quizTitle.value.trim()) {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'กรุณากรอก "ชื่อแบบทดสอบ"',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
        quizTitle.focus();
        return false;
    }

    // 2. ตรวจสอบระดับชั้น
    const gradeLevel = document.getElementById('grade_level');
    if (!gradeLevel || gradeLevel.value === "") {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'กรุณาเลือก "ระดับชั้น"',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
        if(gradeLevel) gradeLevel.focus();
        return false;
    }

    // 3. ตรวจสอบวิชา
    const subjectSelect = document.getElementById('subject_select');
    if (!subjectSelect || !subjectSelect.value || subjectSelect.value === "") {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'กรุณาเลือก "รายวิชา"',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
        return false;
    }

    // 4. ตรวจสอบข้อสอบ (ต้องมีอย่างน้อย 1 ข้อ)
    const questions = document.querySelectorAll('.question-block');
    if (questions.length === 0) {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: 'กรุณาเพิ่มข้อสอบอย่างน้อย 1 ข้อ',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
        return false;
    }

    // 5. ตรวจสอบคำถาม ตัวเลือก และคำตอบที่ถูกต้อง
    let isValid = true;
    let errorMessage = '';
    let firstMissing = null;

    questions.forEach((block, idx) => {
        const questionNum = idx + 1;
        
        // ตรวจสอบหัวข้อคำถาม
        const questionInput = block.querySelector('textarea[name^="questions["][name$="[question_text]"]');
        if (!questionInput || !questionInput.value.trim()) {
            isValid = false;
            if (!errorMessage) errorMessage = `กรุณากรอกหัวข้อคำถามในข้อที่ ${questionNum}`;
            block.classList.add('ring-2', 'ring-red-500', 'bg-red-50');
            if (!firstMissing) firstMissing = questionInput;
            return;
        }

        // ตรวจสอบคำตอบเฉลย
        const radioChecked = block.querySelector('input[type="radio"]:checked');
        if (!radioChecked) {
            isValid = false;
            if (!errorMessage) errorMessage = `กรุณาเลือกคำตอบที่ถูกต้องในข้อที่ ${questionNum}`;
            block.classList.add('ring-2', 'ring-red-500', 'bg-red-50');
            if (!firstMissing) firstMissing = block;
        } else {
            block.classList.remove('ring-2', 'ring-red-500', 'bg-red-50');
        }
    });

    if (!isValid) {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
            text: errorMessage,
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
        if (firstMissing) {
            firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => { firstMissing.focus(); }, 500);
        }
        return false;
    }

    return true;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    window.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: "{{ session('success') }}",
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'ตกลง'
        });
    });
</script>
@endif

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', () => {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: "{{ session('error') }}",
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ตกลง'
        });
    });
</script>
@endif