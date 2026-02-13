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
                
                // ฟังก์ชันสำหรับแปลงคำตอบจากตัวอักษรเป็นตัวเลข
                const mapAnswerToNumber = (ans) => {
                    if (!ans) return 0;
                    const a = ans.toString().trim().toLowerCase();
                    if (a === 'ก' || a === 'a' || a === '1') return 1;
                    if (a === 'ข' || a === 'b' || a === '2') return 2;
                    if (a === 'ค' || a === 'c' || a === '3') return 3;
                    if (a === 'ง' || a === 'd' || a === '4') return 4;
                    return 0;
                };

                jsonData.forEach(row => {
                    // แปลงค่าจาก column 'answer' ใน Excel ให้เป็นตัวเลขก่อน
                    const correctAnswer = mapAnswerToNumber(row.answer);

                    const newQuestion = {
                        question_text: row.question_text || '',
                        score: row.score || 1,
                        question_type: 'multiple_choice',
                        standard: row.standard || '',
                        indicator: row.indicator || '',
                        topic: row.topic || '',
                        taxonomy_level: row.taxonomy_level || '',
                        choices: [
                            { choice_text: row.choice_1, is_correct: correctAnswer === 1 },
                            { choice_text: row.choice_2, is_correct: correctAnswer === 2 },
                            { choice_text: row.choice_3, is_correct: correctAnswer === 3 },
                            { choice_text: row.choice_4, is_correct: correctAnswer === 4 }
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
function addQuestion(q = null) {
    const container = document.getElementById('questions-container');
    const idx = questionCount++;
    const qDiv = document.createElement('div');
    qDiv.className = "p-6 border rounded-xl bg-gray-50 relative question-block mb-4 animate-fade-in";
    qDiv.id = `q-block-${idx}`;

    qDiv.innerHTML = `
        <button type="button" onclick="removeQuestion('${idx}')" class="absolute top-4 right-4 text-red-400 hover:text-red-600">ลบข้อนี้</button>
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
                <label class="block text-xs font-bold text-gray-500 uppercase">มาตรฐาน (Standard)</label>
                <input type="text" name="questions[${idx}][standard]" value="${q ? q.standard : ''}" placeholder="เช่น มาตรฐาน 5.2 มีความรู้ความเข้าใจ เห็นคุณค่า และสืบทอดศาสนา วัฒนธรรม ประเพณี เพื่อการอยู่ร่วมกันอย่างสันติสุข" class="w-full rounded-lg border-gray-300 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase">ตัวชี้วัด (Indicator)</label>
                <input type="text" name="questions[${idx}][indicator]" value="${q ? q.indicator : ''}" placeholder="เช่น 1.1.1 บอกความเป็นมาของศาสนาต่าง ๆ ได้" class="w-full rounded-lg border-gray-300 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase">เรื่อง/เนื้อหา (Topic)</label>
                <input type="text" name="questions[${idx}][topic]" value="${q ? q.topic : ''}" placeholder="เช่น ศาสนาต่าง ๆ (กำเนิดศาสนา)" class="w-full rounded-lg border-gray-300 mt-1">
            </div>
            <div class="md:col-span-3 mt-1">
                <label class="block text-xs font-bold text-gray-500 uppercase">Bloom's Taxonomy</label>
                <select name="questions[${idx}][taxonomy_level]" onchange="updateBadge(this, ${idx})" class="w-full rounded-lg border-gray-300 mt-1">
                    <option value="">-- เลือกด้านที่วัด --</option>
                    ${(() => {
                        // สร้างแผนผังข้อมูล (Mapping)
                        const levels = {
                            1: '1.ความรู้-ความจำ',
                            2: '2.ความเข้าใจ',
                            3: '3.การนำไปใช้',
                            4: '4.การวิเคราะห์',
                            5: '5.การสังเคราะห์',
                            6: '6.การประเมินค่า'
                        };

                        return Object.entries(levels).map(([v, label]) => `
                            <option value="${v}" ${q && q.taxonomy_level == v ? 'selected' : ''}>
                                ${label}
                            </option>
                        `).join('');
                    })()}
                </select>
                <div id="badge-${idx}" class="mt-2"></div>
            </div>
        </div>
        
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3" id="choices-${idx}"></div>
    `;

    container.appendChild(qDiv);
    const choiceContainer = qDiv.querySelector(`#choices-${idx}`);

    if (q && q.choices) {
        q.choices.forEach((c) => addChoice(idx, choiceContainer, c));
    } else {
        for(let i=0; i<4; i++) addChoice(idx, choiceContainer);
    }

    if(q && q.taxonomy_level) updateBadge(qDiv.querySelector('select[name*="taxonomy_level"]'), idx);
    reIndexQuestions();
}

function addChoice(qIdx, container, c = null) {
    const currentChoices = container.querySelectorAll('.choice-item').length;
    const choiceDiv = document.createElement('div');
    choiceDiv.className = "flex items-center gap-2 choice-item bg-white p-2 rounded-lg border border-gray-100 shadow-sm";
    choiceDiv.innerHTML = `
        <input type="checkbox" name="questions[${qIdx}][choices][${currentChoices}][is_correct]" value="1" ${c && c.is_correct ? 'checked' : ''} class="rounded text-blue-600">
        <input type="text" name="questions[${qIdx}][choices][${currentChoices}][choice_text]" value="${c ? c.choice_text : ''}" placeholder="ตัวเลือก" class="w-full text-sm border-none focus:ring-0 p-1">
    `;
    container.appendChild(choiceDiv);
}

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
        '1 ความจำ': { text: 'ความจำ', color: 'bg-gray-100 text-gray-600' },
        '2 เข้าใจ': { text: 'เข้าใจ', color: 'bg-green-100 text-green-700' },
        '3 นำไปใช้': { text: 'นำไปใช้', color: 'bg-blue-100 text-blue-700' },
        '4 วิเคราะห์': { text: 'วิเคราะห์', color: 'bg-yellow-100 text-yellow-700' },
        '5 สังเคราะห์': { text: 'สังเคราะห์', color: 'bg-purple-100 text-purple-700' },
        '6 ประเมินค่า': { text: 'ประเมินค่า', color: 'bg-red-100 text-red-700' }
    };
    const val = select.value;
    badge.innerHTML = labels[val] ? `<span class="px-2 py-1 rounded text-xs font-bold ${labels[val].color}">${labels[val].text}</span>` : '';
}
</script>