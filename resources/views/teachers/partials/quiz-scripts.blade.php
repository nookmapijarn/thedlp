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

// --- ฟังก์ชันเพิ่มคำถาม ---
// --- ฟังก์ชันเพิ่มคำถาม (ปรับปรุงใหม่) ---
function addQuestion(q = null) {
    const container = document.getElementById('questions-container');
    const idx = questionCount++;
    const qDiv = document.createElement('div');
    qDiv.className = "p-6 border rounded-xl bg-gray-50 relative question-block mb-4 animate-fade-in";
    qDiv.id = `q-block-${idx}`;

    qDiv.innerHTML = `
        <button type="button" onclick="removeQuestion('${idx}')" class="absolute top-4 right-4 text-red-400 hover:text-red-600 font-bold text-sm">ลบข้อนี้</button>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-1 text-lg font-bold text-gray-400 q-number">#</div>
            
            <div class="md:col-span-7">
                <label class="block text-xs font-bold text-gray-500 uppercase">โจทย์คำถาม</label>
                <textarea name="questions[${idx}][question_text]" required class="w-full rounded-lg border-gray-300 mt-1">${q ? q.question_text : ''}</textarea>
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
    const questions = document.querySelectorAll('.question-block');
    let isValid = true;
    let firstMissing = null;

    questions.forEach((block) => {
        const radioChecked = block.querySelector('input[type="radio"]:checked');
        if (!radioChecked) {
            isValid = false;
            block.classList.add('ring-2', 'ring-red-500', 'bg-red-50');
            if (!firstMissing) firstMissing = block;
        } else {
            block.classList.remove('ring-2', 'ring-red-500', 'bg-red-50');
        }
    });

    if (!isValid) {
        alert('กรุณาเลือกคำตอบที่ถูกต้องให้ครบทุกข้อก่อนบันทึก');
        firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    return true;
}
</script>