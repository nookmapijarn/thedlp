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

// --- CSS Animation สำหรับการนำทางและการกระพริบข้อสอบ ---
const navStyles = document.createElement('style');
navStyles.innerHTML = `
    @keyframes pulseHighlight {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
        50% { transform: scale(1.008); box-shadow: 0 0 0 12px rgba(79, 70, 229, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }
    .highlight-target-question {
        animation: pulseHighlight 1.2s cubic-bezier(0, 0, 0.2, 1);
        border-color: #6366f1 !important;
        background-color: #f5f7ff !important;
    }
    @keyframes badgePop {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .badge-pop {
        animation: badgePop 0.2s ease-out;
    }
`;
document.head.appendChild(navStyles);

// --- ฟังก์ชันเพิ่มคำถาม (ปรับปรุงใหม่พร้อม Checkbox และแถบหัวข้อ) ---
function addQuestion(q = null) {
    const container = document.getElementById('questions-container');
    const idx = questionCount++;
    const qDiv = document.createElement('div');
    qDiv.className = "p-6 border border-slate-200/90 rounded-2xl bg-white relative question-block mb-5 shadow-sm hover:shadow-md transition-all duration-200 animate-fade-in";
    qDiv.id = `q-block-${idx}`;
    qDiv.dataset.idx = idx;

    const hasImage = q && q.question_image && q.question_image.trim() !== '';

    qDiv.innerHTML = `
        <!-- Question Card Header: Checkbox, Badge Number, Quick Actions -->
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none group/chk bg-slate-50 hover:bg-indigo-50/60 px-2.5 py-1.5 rounded-xl border border-slate-200/70 transition-colors">
                    <input type="checkbox" 
                           class="q-select-chk w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer" 
                           data-idx="${idx}" 
                           onchange="updateSelectedCount()">
                    <span class="text-xs font-bold text-slate-600 group-hover/chk:text-indigo-600 transition-colors">เลือก</span>
                </label>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-slate-900 text-white rounded-xl text-xs font-black tracking-wide q-number shadow-xs">#</span>
                    <span class="text-[11px] font-bold text-slate-400 hidden sm:inline">ข้อคำถาม</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="scrollToStickyNavigator()" 
                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 px-2.5 py-1.5 rounded-xl transition-all" 
                        title="ดูแผนผังนำทาง">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>ผังข้อ</span>
                </button>
                <button type="button" onclick="removeQuestion('${idx}')" 
                        class="inline-flex items-center gap-1 text-xs text-rose-500 hover:text-white hover:bg-rose-500 px-3 py-1.5 rounded-xl font-bold transition-all shadow-xs" 
                        title="ลบข้อนี้">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>ลบข้อนี้</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-8">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1">โจทย์คำถาม <span class="text-rose-500">*</span></label>
                <textarea name="questions[${idx}][question_text]" required 
                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 px-3 transition-all" 
                    rows="3" 
                    placeholder="ระบุเนื้อหาโจทย์คำถาม...">${q ? (q.question_text || '') : ''}</textarea>
                
                <!-- ส่วนแนบรูปภาพโจทย์ -->
                <div class="mt-2.5">
                    <input type="file" id="q_img_input_${idx}" accept="image/*" class="hidden" onchange="handleQuestionImageChange(this, '${idx}')">
                    <input type="hidden" name="questions[${idx}][image_base64]" id="q_img_base64_${idx}">
                    <input type="hidden" name="questions[${idx}][question_image]" id="q_img_url_${idx}" value="${hasImage ? q.question_image : ''}">
                    <input type="hidden" name="questions[${idx}][remove_image]" id="q_img_remove_${idx}" value="0">

                    <!-- ปุ่มเปิดเลือกรูปภาพ -->
                    <div id="q_img_btn_upload_${idx}" class="${hasImage ? 'hidden' : ''}">
                        <button type="button" onclick="document.getElementById('q_img_input_${idx}').click()" 
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 border border-dashed border-slate-300 hover:border-indigo-400 rounded-xl text-xs font-bold text-slate-600 transition-all shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>📷 แนบรูปภาพโจทย์</span>
                        </button>
                    </div>

                    <!-- กล่องพรีวิวรูปภาพ -->
                    <div id="q_img_preview_container_${idx}" class="${hasImage ? '' : 'hidden'} flex items-start gap-3 bg-slate-50 p-2.5 rounded-xl border border-slate-200 shadow-xs w-fit max-w-full">
                        <div class="relative group">
                            <img id="q_img_preview_${idx}" src="${hasImage ? q.question_image : ''}" 
                                class="h-24 max-w-[220px] object-contain rounded-lg border border-slate-200 bg-white cursor-pointer hover:opacity-90 transition-opacity"
                                onclick="openImagePreviewModal(this.src)"
                                title="คลิกเพื่อดูรูปขนาดเต็ม">
                        </div>
                        <div class="flex flex-col gap-1.5 self-center">
                            <button type="button" onclick="document.getElementById('q_img_input_${idx}').click()" 
                                class="px-2.5 py-1 bg-white hover:bg-slate-100 text-slate-700 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1 border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                เปลี่ยนรูป
                            </button>
                            <button type="button" onclick="removeQuestionImage('${idx}')" 
                                class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-[11px] font-bold transition-all flex items-center gap-1 border border-rose-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                ลบรูป
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1">คะแนน</label>
                <input type="number" name="questions[${idx}][score]" value="${q ? q.score : 1}" step="0.5" min="0" class="w-full rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm py-2.5 px-3 font-bold">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wide mb-1">ประเภท</label>
                <select name="questions[${idx}][question_type]" class="w-full rounded-xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 text-sm py-2.5 px-3 font-bold">
                    <option value="multiple_choice" ${q && q.question_type == 'multiple_choice' ? 'selected' : ''}>ปรนัย 4 ตัวเลือก</option>
                </select>
            </div>

            <div class="md:col-span-3 mt-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase">มาตรฐาน (Standard)</label>
                <input type="text" name="questions[${idx}][standard]" value="${q ? (q.standard || '') : ''}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-xs py-2 px-3 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase">ตัวชี้วัด (Indicator)</label>
                <input type="text" name="questions[${idx}][indicator]" value="${q ? (q.indicator || '') : ''}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-xs py-2 px-3 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase">เรื่อง/เนื้อหา (Topic)</label>
                <input type="text" name="questions[${idx}][topic]" value="${q ? (q.topic || '') : ''}" class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-xs py-2 px-3 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-[11px] font-bold text-slate-500 uppercase">Bloom's Taxonomy</label>
                <select name="questions[${idx}][taxonomy_level]" onchange="updateBadge(this, ${idx})" class="w-full rounded-xl border-slate-200 bg-slate-50/50 text-xs py-2 px-3 mt-1 font-bold">
                    <option value="">-- ด้านที่วัด --</option>
                    ${[1,2,3,4,5,6].map(v => `<option value="${v}" ${q && q.taxonomy_level == v ? 'selected' : ''}>ระดับ ${v}</option>`).join('')}
                </select>
                <div id="badge-${idx}" class="mt-1.5"></div>
            </div>
        </div>
        
        <div class="mt-4 pt-3 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-black text-slate-700">ตัวเลือกคำตอบ (คลิกปุ่มวงกลมเพื่อระบุข้อที่ถูกต้อง) <span class="text-rose-500">*</span></span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="choices-${idx}"></div>
        </div>
    `;

    container.appendChild(qDiv);
    const choiceContainer = qDiv.querySelector(`#choices-${idx}`);

    // สร้าง Choice 4 ข้อ
    if (q && q.choices) {
        q.choices.forEach((c, cIdx) => {
            const isCorrect = c.is_correct || (q.correct_answer_index === cIdx);
            addChoice(idx, choiceContainer, c, isCorrect);
        });
    } else {
        for(let i=0; i<4; i++) addChoice(idx, choiceContainer);
    }

    if(q && q.taxonomy_level) updateBadge(qDiv.querySelector('select[name*="taxonomy_level"]'), idx);
    reIndexQuestions();
    updateSelectedCount();
}

// --- ฟังก์ชันเพิ่มตัวเลือก (Radio) ---
function addChoice(qIdx, container, c = null, isCorrect = false) {
    const currentChoices = container.querySelectorAll('.choice-item').length;
    const choiceDiv = document.createElement('div');
    choiceDiv.className = "flex items-center gap-2.5 choice-item bg-slate-50/80 hover:bg-slate-100/80 p-2.5 rounded-xl border border-slate-200/70 transition-colors";
    
    choiceDiv.innerHTML = `
        <label class="flex items-center justify-center cursor-pointer" title="ตั้งเป็นข้อที่ถูกต้อง">
            <input type="radio" 
                   name="questions[${qIdx}][correct_index]" 
                   value="${currentChoices}" 
                   ${isCorrect ? 'checked' : ''} 
                   class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 cursor-pointer" required>
        </label>
        <span class="text-xs font-black text-slate-400 w-4">${['ก','ข','ค','ง','จ','ฉ'][currentChoices] || (currentChoices + 1)}.</span>
        <input type="text" 
               required 
               name="questions[${qIdx}][choices][${currentChoices}][choice_text]" 
               value="${c ? (c.choice_text || c.text || '') : ''}" 
               placeholder="ตัวเลือกที่ ${currentChoices + 1}" 
               class="w-full text-xs font-bold text-slate-800 bg-transparent border-none focus:ring-0 p-0">
    `;
    container.appendChild(choiceDiv);
}

// --- ฟังก์ชันลบข้อสอบเดี่ยว ---
function removeQuestion(idx) {
    const block = document.getElementById(`q-block-${idx}`);
    if (!block) return;

    block.remove();
    reIndexQuestions();
    updateSelectedCount();
}

// --- ฟังก์ชันจัดลำดับข้อและอัปเดตตัวเลข ---
function reIndexQuestions() {
    const blocks = document.querySelectorAll('.question-block');
    blocks.forEach((block, i) => {
        const qNum = i + 1;
        const qNumberEl = block.querySelector('.q-number');
        if (qNumberEl) qNumberEl.innerText = `#${qNum}`;
        block.dataset.questionNumber = qNum;
    });

    const totalBadge = document.getElementById('total-q-badge');
    if (totalBadge) totalBadge.innerText = blocks.length;

    const floatingBadge = document.getElementById('floating-q-count');
    if (floatingBadge) floatingBadge.innerText = blocks.length;

    renderQuestionNavigator();
}

// --- ฟังก์ชันจัดการเลือกหลายข้อ (Bulk Selection) ---
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.q-select-chk');
    const checked = document.querySelectorAll('.q-select-chk:checked');
    const deleteBtn = document.getElementById('btn-delete-selected');
    const countBadge = document.getElementById('selected-count-badge');
    const selectAllCheckbox = document.getElementById('select-all-questions');

    const count = checked.length;
    if (countBadge) countBadge.textContent = count;
    
    if (deleteBtn) {
        if (count > 0) {
            deleteBtn.classList.remove('hidden');
            deleteBtn.classList.add('inline-flex');
        } else {
            deleteBtn.classList.add('hidden');
            deleteBtn.classList.remove('inline-flex');
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = checkboxes.length > 0 && count === checkboxes.length;
        selectAllCheckbox.indeterminate = count > 0 && count < checkboxes.length;
    }

    // ไฮไลต์กล่องข้อสอบที่ถูกเลือก
    checkboxes.forEach(chk => {
        const block = chk.closest('.question-block');
        if (block) {
            if (chk.checked) {
                block.classList.add('ring-2', 'ring-indigo-400', 'bg-indigo-50/20');
            } else {
                block.classList.remove('ring-2', 'ring-indigo-400', 'bg-indigo-50/20');
            }
        }
    });

    renderQuestionNavigator();
}

function toggleSelectAllQuestions(masterCheckbox) {
    const isChecked = masterCheckbox.checked;
    const checkboxes = document.querySelectorAll('.q-select-chk');
    checkboxes.forEach(chk => {
        chk.checked = isChecked;
    });
    updateSelectedCount();
}

// --- ฟังก์ชันลบข้อสอบที่เลือกพร้อมกัน (Bulk Delete) ---
function deleteSelectedQuestions() {
    const checked = document.querySelectorAll('.q-select-chk:checked');
    const count = checked.length;
    if (count === 0) return;

    Swal.fire({
        title: `ยืนยันการลบข้อสอบ ${count} ข้อ?`,
        text: `คุณกำลังจะลบข้อสอบที่เลือกจำนวน ${count} ข้อออกจากแบบทดสอบนี้อย่างถาวร`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: `ลบ ${count} ข้อที่เลือก`,
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            checked.forEach(chk => {
                const block = chk.closest('.question-block');
                if (block) {
                    block.remove();
                }
            });
            reIndexQuestions();
            updateSelectedCount();
            
            Swal.fire({
                icon: 'success',
                title: 'ลบสำเร็จ',
                text: `ลบข้อสอบจำนวน ${count} ข้อเรียบร้อยแล้ว`,
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// --- ฟังก์ชันเรนเดอร์ปุ่มนำทางรายข้อ (Question Navigator Badges) ---
function renderQuestionNavigator() {
    const wrapper = document.getElementById('navigator-badges-wrapper');
    if (!wrapper) return;

    const blocks = document.querySelectorAll('.question-block');
    if (blocks.length === 0) {
        wrapper.innerHTML = `
            <div class="text-xs text-slate-400 py-2 italic font-bold">
                ยังไม่มีข้อสอบ — คลิกปุ่ม "+ เพิ่มข้อใหม่" เพื่อเริ่มต้น
            </div>
        `;
        const summaryEl = document.getElementById('q-status-summary');
        if (summaryEl) summaryEl.innerHTML = `0 ข้อ`;
        return;
    }

    let html = '';
    let completedCount = 0;

    blocks.forEach((block, i) => {
        const qNum = i + 1;
        const chk = block.querySelector('.q-select-chk');
        const isSelected = chk && chk.checked;
        
        const textarea = block.querySelector('textarea[name^="questions["][name$="[question_text]"]');
        const hasText = textarea && textarea.value.trim().length > 0;
        const radioChecked = block.querySelector('input[type="radio"]:checked');
        const isComplete = hasText && !!radioChecked;

        if (isComplete) completedCount++;

        let badgeClass = '';
        let title = `ข้อที่ ${qNum}`;

        if (isSelected) {
            badgeClass = 'bg-purple-600 text-white ring-2 ring-purple-300 font-black shadow-sm';
            title += ' (เลือกอยู่)';
        } else if (isComplete) {
            badgeClass = 'bg-emerald-50 text-emerald-700 border border-emerald-300 hover:bg-emerald-600 hover:text-white font-bold';
            title += ' (กรอกครบถ้วน)';
        } else {
            badgeClass = 'bg-amber-50 text-amber-700 border border-amber-300 hover:bg-amber-500 hover:text-white font-bold';
            title += ' (ยังไม่สมบูรณ์/ยังไม่เฉลย)';
        }

        html += `
            <button type="button" 
                    onclick="jumpToQuestion(${i})" 
                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center text-xs transition-all active:scale-90 hover:scale-105 badge-pop ${badgeClass}" 
                    title="${title}">
                ${qNum}
            </button>
        `;
    });

    // ปุ่มลัดเพิ่มข้อสอบต่อท้าย
    html += `
        <button type="button" 
                onclick="addQuestion()" 
                class="h-8 sm:h-9 px-3 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-600 text-xs font-black transition-all flex items-center gap-1 active:scale-95 shadow-2xs" 
                title="เพิ่มข้อใหม่">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>เพิ่ม</span>
        </button>
    `;

    wrapper.innerHTML = html;

    const summaryEl = document.getElementById('q-status-summary');
    if (summaryEl) {
        summaryEl.innerHTML = `${blocks.length} ข้อ <span class="text-[10px] text-emerald-600 font-black">(${completedCount} ครบถ้วน)</span>`;
    }
}

// --- ฟังก์ชันกระโดดไปยังข้อสอบรายข้อ (Smooth Scroll + Glow Highlight) ---
function jumpToQuestion(questionIndex) {
    const questionBlocks = document.querySelectorAll('.question-block');
    if (questionBlocks[questionIndex]) {
        const target = questionBlocks[questionIndex];
        
        // เลื่อนหน้าจออย่างนุ่มนวล
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // กระพริบเน้นสายตา (Pulsing Glow)
        target.classList.remove('highlight-target-question');
        void target.offsetWidth; // trigger reflow
        target.classList.add('highlight-target-question');
        setTimeout(() => {
            target.classList.remove('highlight-target-question');
        }, 1500);

        // โฟกัสกล่องพิมพ์โจทย์ทันที
        const textarea = target.querySelector('textarea');
        if (textarea) {
            setTimeout(() => { textarea.focus(); }, 300);
        }
    }
}

// --- ฟังก์ชันเลื่อนไปยังแถบนำทาง / ย่อขยายแถบนำทาง ---
function scrollToStickyNavigator() {
    const nav = document.getElementById('question-navigator-card') || document.getElementById('question-bank-header');
    if (nav) {
        nav.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const wrapper = document.getElementById('navigator-badges-wrapper');
        if (wrapper && wrapper.classList.contains('hidden')) {
            toggleNavigatorCollapse();
        }
    }
}

function toggleNavigatorCollapse() {
    const wrapper = document.getElementById('navigator-badges-wrapper');
    const toggleBtn = document.getElementById('nav-toggle-btn');
    if (!wrapper) return;
    
    if (wrapper.classList.contains('hidden')) {
        wrapper.classList.remove('hidden');
        if (toggleBtn) toggleBtn.innerHTML = '<span>ย่อ</span>';
    } else {
        wrapper.classList.add('hidden');
        if (toggleBtn) toggleBtn.innerHTML = '<span>ขยาย</span>';
    }
}

// ฟังก์ชัน Debounce สำหรับอัปเดตแบบเรียลไทม์เมื่อพิมพ์
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ผูก Event Listener อัปเดตผังสถานะแบบสดๆ
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('questions-container');
    if (container) {
        container.addEventListener('input', debounce(() => {
            renderQuestionNavigator();
        }, 300));
        container.addEventListener('change', () => {
            renderQuestionNavigator();
        });
    }
});

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