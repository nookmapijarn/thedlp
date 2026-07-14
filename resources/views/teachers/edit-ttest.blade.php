<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<x-teachers-layout>
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .ts-control { 
            border-radius: 1rem !important; 
            padding: 0.75rem 1.25rem !important; 
            border-color: #e2e8f0 !important; 
            transition: all 0.2s;
            font-weight: 700 !important;
            color: #475569 !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
        }

        /* พรีวิวรูปภาพ */
        .preview-img-box {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 1.5rem;
        }
    </style>

    <div class="p-4 mt-20 animate-fade-in">
        <form action="{{ route('ttest.update', $quiz->id) }}" 
            onsubmit="return validateForm(event)"
            method="POST" class="max-w-7xl mx-auto pb-20 px-4 space-y-8">
            @csrf
            @method('PUT')
            
            <input type="file" id="excel_import" accept=".xlsx, .xls, .csv" class="hidden">
            <input type="hidden" name="quiz_cover_base64" id="quiz_cover_base64">
            <input type="hidden" name="quiz_certificate_base64" id="quiz_certificate_base64">

            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-500 via-amber-600 to-amber-600 px-10 py-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="p-4 bg-white/15 backdrop-blur-xl rounded-2xl text-white shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-white tracking-tight">แก้ไขแบบทดสอบ</h2>
                                <p class="text-amber-50/80 text-sm font-medium">รหัสอ้างอิง: #{{ $quiz->id }} • ปรับปรุงข้อมูลล่าสุด</p>
                            </div>
                        </div>
                        <div class="bg-black/10 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/10 text-left md:text-right">
                            <p class="text-[10px] font-black text-amber-200 uppercase tracking-[0.2em] mb-1">แก้ไขล่าสุดเมื่อ</p>
                            <p class="text-sm font-black text-white">
                                {{ $quiz->updated_at ? \Carbon\Carbon::parse($quiz->updated_at)->format('d/m/Y H:i') : 'ไม่มีข้อมูล' }} น.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        <div class="lg:col-span-7 space-y-8">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-1 gap-6">
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">ภาพปกแบบทดสอบ</label>
                                    <div class="relative cursor-pointer" onclick="document.getElementById('cover_input').click()">
                                        <div id="cover_placeholder" class="{{ $quiz->cover_image ? 'hidden' : '' }} w-full h-[200px] bg-slate-50 border-4 border-dashed border-slate-200 rounded-[1.5rem] flex flex-col items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-300 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            <span class="text-xs font-bold">เปลี่ยนภาพปก</span>
                                        </div>
                                        <img id="cover_preview" src="{{ $quiz->cover_image ?? '' }}" class="{{ $quiz->cover_image ? '' : 'hidden' }} preview-img-box border-4 border-slate-100 object-cover">
                                        <input type="file" id="cover_input" accept="image/*" class="hidden" onchange="previewCoverImage(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">ชื่อแบบทดสอบ <span class="text-rose-500">*</span></label>
                                <input type="text" name="quiz_title" value="{{ old('quiz_title', $quiz->title) }}" required 
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all py-4 px-5 text-lg text-slate-600 font-bold">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">ระดับชั้น <span class="text-rose-500">*</span></label>
                                    <select id="grade_level" name="grade_level" required class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all py-4 px-5 text-slate-600 font-bold">
                                        <option value="">เลือกระดับชั้น</option>
                                        <option value="0" @selected($quiz->grade_level == 0)>ทุกระดับ</option>
                                        <option value="1" @selected($quiz->grade_level == 1)>ประถมศึกษา</option>
                                        <option value="2" @selected($quiz->grade_level == 2)>มัธยมศึกษาตอนต้น</option>
                                        <option value="3" @selected($quiz->grade_level == 3)>มัธยมศึกษาตอนปลาย</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">รายวิชา <span class="text-rose-500">*</span></label>
                                    <select id="subject_select" name="subject_code" data-selected="{{ $quiz->subject_code }}" required></select>
                                </div>
                            </div>
                            <div class="group">
                                <label class="block text-sm font-black text-slate-700 mb-3 ml-1 uppercase tracking-wider">คำอธิบายเพิ่มเติม</label>
                                <textarea name="quiz_description" rows="4" placeholder="ระบุรายละเอียดหรือคำชี้แจง..." class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm  resize-none">{{ $quiz->description }}</textarea>
                            </div>
                            <input type="hidden" name="subject_group" id="subject_group" value="{{ $quiz->subject_group }}">
                        </div>

                        <div class="lg:col-span-5">
                            <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100 space-y-8 h-full">
                                <div>
                                    <h4 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400 flex items-center gap-2 mb-6">
                                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span> ตั้งค่าแบบทดสอบ
                                    </h4>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm">
                                            <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">เวลา (นาที)</label>
                                            <input type="number" name="time_limit" value="{{ $quiz->time_limit }}" class="w-full border-none p-0 text-2xl font-black text-indigo-600 focus:ring-0 bg-transparent">
                                        </div>
                                        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm">
                                            <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">เกณฑ์ผ่าน (%)</label>
                                            <input type="number" name="pass_percentage" value="{{ $quiz->pass_percentage }}" class="w-full border-none p-0 text-2xl font-black text-emerald-600 focus:ring-0 bg-transparent">
                                        </div>
                                    </div>

                                    <hr class="border-slate-200/60 mb-8">

                                    <h4 class="text-[11px] uppercase tracking-[0.2em] font-black text-slate-500 mb-5">ตั้งค่าความปลอดภัย</h4>
                                    <div class="space-y-4">
                                        <label class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200/60 cursor-pointer hover:border-indigo-300 transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-black text-slate-700">บังคับ GPS</span>
                                                    <span class="text-[10px] text-slate-400">ระบุตำแหน่งผู้สอบ</span>
                                                </div>
                                            </div>
                                            <div class="relative inline-block w-10 h-6">
                                                <input type="checkbox" name="require_location" value="1" @checked($quiz->require_location) class="peer opacity-0 w-0 h-0">
                                                <span class="absolute inset-0 cursor-pointer bg-slate-200 rounded-full transition-colors peer-checked:bg-indigo-600"></span>
                                                <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></span>
                                            </div>
                                        </label>

                                        <label class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200/60 cursor-pointer hover:border-emerald-300 transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /></svg>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-black text-slate-700">สุ่มถ่ายรูป</span>
                                                    <span class="text-[10px] text-slate-400">บันทึกใบหน้าอัตโนมัติ</span>
                                                </div>
                                            </div>
                                            <div class="relative inline-block w-10 h-6">
                                                <input type="checkbox" name="require_snapshot" value="1" @checked($quiz->require_snapshot) class="peer opacity-0 w-0 h-0">
                                                <span class="absolute inset-0 cursor-pointer bg-slate-200 rounded-full transition-colors peer-checked:bg-emerald-600"></span>
                                                <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-4"></span>
                                            </div>
                                        </label>

                                        <div class="group space-y-2">
                                            <label class="flex items-center gap-2 text-sm font-black text-slate-700 dark:text-white mb-2 ml-1">ภาพเกียรติบัตร</label>
                                            <div class="relative group/cert space-y-3">
                                                <input type="file" id="cert_input" accept="image/*" class="hidden" onchange="previewCertImage(this)">
                                                <input type="hidden" name="quiz_certificate_base64" id="quiz_certificate_base64">
                                                <input type="hidden" name="certificate_config" id="certificate_config" value="{{ $quiz->certificate_config }}">
                                                <!-- Standard file input for signature inside form submission -->
                                                <input type="file" name="quiz_signature" id="quiz_signature_hidden_file" class="hidden" accept="image/png">
                                                <!-- Hidden input to tell controller if signature should be removed -->
                                                <input type="hidden" name="quiz_signature_remove" id="quiz_signature_remove" value="false">
                                                
                                                <div id="cert_placeholder" 
                                                    onclick="document.getElementById('cert_input').click()" 
                                                    class="{{ !blank($quiz->certificate_image) ? 'hidden' : '' }} cursor-pointer w-full h-[200px] bg-slate-50 border-4 border-dashed border-slate-200 rounded-[1.5rem] flex flex-col items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-300 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-xs font-bold">เพิ่มเกียรติบัตร</span>
                                                </div>

                                                <div id="cert_preview_container" class="{{ blank($quiz->certificate_image) ? 'hidden' : '' }} relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-950 flex flex-col gap-3">
                                                    <div class="relative w-full rounded-xl overflow-hidden bg-slate-900 border border-white/5 flex items-center justify-center">
                                                        <img id="cert_preview" src="{{ !blank($quiz->certificate_image) ? $quiz->certificate_image : '#' }}" alt="Certificate Preview" class="w-full h-auto object-contain max-h-[150px]">
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button type="button" onclick="openCertDesigner()" class="flex-1 py-2 px-3.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-[11.5px] font-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-1.5 focus:outline-none">
                                                            🎨 จัดการตำแหน่ง / ลายเซ็น
                                                        </button>
                                                        <button type="button" onclick="removeCertImage()" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-lg focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky top-10 z-30">
                <div class="bg-white/90 backdrop-blur-2xl border border-indigo-100/50 shadow-2xl shadow-indigo-100/40 rounded-[2rem] px-8 py-5 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-5">
                        <div class="h-14 w-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-black shadow-xl">
                            <span class="text-2xl">#</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">รายการข้อสอบ</h3>
                            <p class="text-xs text-indigo-500 font-bold uppercase tracking-widest">Question Bank</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <button type="button" onclick="document.getElementById('excel_import').click()" class="flex-1 md:flex-none px-6 py-3.5 bg-white border-2 border-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:border-emerald-500 flex items-center gap-2 transition-all">
                            Excel
                        </button>
                        <button type="button" onclick="addQuestion()" class="flex-1 md:flex-none px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-black flex items-center gap-2 transition-all">
                            เพิ่มข้อใหม่
                        </button>
                    </div>
                </div>
            </div>

            <div id="questions-container" class="space-y-8 px-2"></div>

            <div class="mt-16 flex flex-col sm:flex-row gap-6">
                <a href="{{ route('ttest.index') }}" class="sm:w-1/3 flex justify-center items-center py-6 px-4 bg-white border-2 border-slate-200 text-xl font-black rounded-[2rem] text-slate-400 hover:bg-slate-50 transition-all">
                    ยกเลิก
                </a>
                <button type="submit" class="sm:w-2/3 bg-indigo-600 hover:bg-indigo-700 py-6 px-10 rounded-[2rem] flex items-center justify-center gap-4 shadow-xl shadow-indigo-100 transition-all active:scale-[0.98]">
                    <span class="text-2xl font-black text-white tracking-wide">อัปเดตแบบทดสอบ</span>
                </button>
            </div>
        </form>
    </div>

    @include('teachers.partials.quiz-scripts')

    <script>
        // --- Preview Cover ---
        function previewCoverImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64String = e.target.result;
                    document.getElementById('cover_preview').src = base64String;
                    document.getElementById('cover_preview').classList.remove('hidden');
                    document.getElementById('cover_placeholder').classList.add('hidden');
                    document.getElementById('quiz_cover_base64').value = base64String;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- Preview Certificate ---
        function previewCertImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64String = e.target.result;
                    document.getElementById('cert_preview').src = base64String;
                    document.getElementById('cert_preview_container').classList.remove('hidden');
                    document.getElementById('cert_placeholder').classList.add('hidden');
                    document.getElementById('quiz_certificate_base64').value = base64String;
                    
                    // Open designer immediately
                    setTimeout(openCertDesigner, 300);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- Remove Certificate ---
        function removeCertImage() {
            document.getElementById('cert_input').value = ""; // ล้างไฟล์ใน input
            document.getElementById('quiz_certificate_base64').value = "REMOVE"; // ส่งค่าไปบอก Controller ว่าให้ลบรูปเดิม
            document.getElementById('certificate_config').value = "";
            document.getElementById('cert_preview_container').classList.add('hidden');
            document.getElementById('cert_placeholder').classList.remove('hidden');
        }

        // --- TomSelect & Other Logics ---
        function toggleSubjectLock(isLocked) {
            const control = tsSubject.control;
            if (isLocked) {
                control.style.pointerEvents = 'none'; 
                control.style.backgroundColor = '#e9ecef';
                control.style.opacity = '0.8';
                tsSubject.settings.readonly = true;
            } else {
                control.style.pointerEvents = 'auto';
                control.style.backgroundColor = '';
                control.style.opacity = '1';
                tsSubject.settings.readonly = false;
            }
        }

        const initialQuestions = @json($questions);
        const gradeSelect = document.getElementById('grade_level');
        const subjectSelectEl = document.getElementById('subject_select');
        const groupHidden = document.getElementById('subject_group');

        const tsSubject = new TomSelect("#subject_select", {
            create: false,
            maxItems: 1,
            valueField: 'value',
            labelField: 'text',
            searchField: ['text'],
            dropdownParent: 'body',
            onChange: function(value) {
                if (value) {
                    const prefix = value.substring(0, 2);
                    const groupMap = { 'ทร': 'ทักษะการเรียนรู้', 'พว': 'ความรู้พื้นฐาน', 'อช': 'การประกอบอาชีพ', 'ทช': 'ทักษะการดำเนินชีวิต', 'สค': 'การพัฒนาสังคม', 'คค': 'ความรู้พื้นฐาน' };
                    groupHidden.value = groupMap[prefix] || 'ไม่ระบุกลุ่มสาระ';
                }
            }
        });

        async function loadSubjects(grade, defaultValue = null) {
            if (grade === "" || grade === "0") {
                const specialValue = 'กก00000';
                tsSubject.clearOptions();
                tsSubject.addOptions([{ value: specialValue, text: 'กก00000 วิชาแกน/พื้นฐาน (อัตโนมัติ)' }]);
                tsSubject.setValue(specialValue);
                toggleSubjectLock(true); 
                return;
            }
            toggleSubjectLock(false);
            if (!grade) return;
            tsSubject.clearOptions();
            try {
                const response = await fetch("{{ route('api.subjects') }}?grade=" + grade);
                const data = await response.json();
                const options = data.map(item => ({ value: item.SUB_CODE, text: `${item.SUB_CODE} ${item.SUB_NAME}` }));
                tsSubject.addOptions(options);
                if (defaultValue) tsSubject.setValue(defaultValue);
            } catch (err) { console.error(err); }
        }

        gradeSelect.addEventListener('change', function() {
            tsSubject.clear();
            loadSubjects(this.value);
        });

        window.onload = () => {
            const initialGrade = gradeSelect.value;
            const initialSubject = subjectSelectEl.getAttribute('data-selected');
            if (initialGrade !== undefined) loadSubjects(initialGrade, initialSubject);

            if (initialQuestions && initialQuestions.length > 0) {
                initialQuestions.forEach((q, index) => {
                    setTimeout(() => addQuestion({
                        ...q,
                        choices: q.choices.map(c => ({ 
                            ...c, 
                            is_correct: (c.is_correct == 1 || c.is_correct === true) 
                        }))
                    }), index * 30);
                });
            } else {
                addQuestion();
            }
        };
    </script>

<!-- Certificate Designer Modal (Pro-level drag & drop positioning editor) -->
<div id="cert-designer-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2rem] w-full max-w-5xl h-[85vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50 dark:bg-slate-900/50">
            <div>
                <h3 class="text-sm font-black text-slate-855 dark:text-white">เครื่องมือจัดตำแหน่งใบประกาศเกียรติบัตร</h3>
                <p class="text-[10px] text-slate-400 font-bold mt-0.5">ลากเพื่อจัดวางตำแหน่ง และปรับขนาด/สี ของแต่ละข้อความได้อิสระ</p>
            </div>
            <button type="button" onclick="closeCertDesigner()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-lg font-black focus:outline-none">×</button>
        </div>

        <!-- Body -->
        <div class="flex-1 flex flex-col lg:flex-row overflow-hidden">
            <!-- Left Workspace Area (Canvas mockup) -->
            <div class="flex-1 bg-slate-100 dark:bg-slate-950 p-6 overflow-auto flex items-center justify-center relative">
                <div id="cert-workspace" class="relative shadow-2xl bg-white select-none border border-slate-200 dark:border-slate-800 aspect-[1123/794] w-full max-w-[700px] overflow-hidden">
                    <!-- Background image inside Workspace -->
                    <img id="cert-designer-bg" src="" class="w-full h-full object-cover pointer-events-none">
                    
                    <!-- Draggable Elements -->
                    <div id="drag-student-name" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-purple-500 bg-purple-500/10 text-slate-800 select-none">
                        [ชื่อ-นามสกุล ผู้เรียน]
                    </div>
                    <div id="drag-quiz-title" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-blue-500 bg-blue-500/10 text-slate-800 select-none">
                        [ชื่อแบบทดสอบ]
                    </div>
                    <div id="drag-quiz-score" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-amber-500 bg-amber-500/10 text-slate-800 select-none">
                        คะแนน: [คะแนนที่ได้] / [คะแนนเต็ม]
                    </div>
                    <div id="drag-date" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-emerald-500 bg-emerald-500/10 text-slate-800 select-none">
                        วันที่สอบผ่าน: [วันที่ออกใบประกาศ]
                    </div>
                    <div id="drag-signature" class="absolute cursor-move px-2 py-1 rounded border border-dashed border-rose-500 bg-rose-500/10 flex flex-col items-center justify-center w-28 h-12 select-none">
                        <img id="designer-signature-img" src="" class="max-h-full max-w-full object-contain hidden pointer-events-none">
                        <span id="designer-signature-placeholder" class="text-[9px] text-rose-500 font-black">ลายเซ็นจำลอง</span>
                    </div>
                    <div id="drag-signer-name" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-indigo-500 bg-indigo-500/10 text-slate-800 select-none">
                        [ชื่อผู้ลงนาม]
                    </div>
                    <div id="drag-signer-title" class="absolute cursor-move font-black whitespace-nowrap px-2 py-1 rounded border border-dashed border-pink-500 bg-pink-500/10 text-slate-800 select-none">
                        [ตำแหน่งผู้ลงนาม]
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Controls -->
            <div class="w-full lg:w-80 border-t lg:border-t-0 lg:border-l border-slate-150 dark:border-slate-800 p-5 overflow-y-auto space-y-5 bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300">
                <div class="space-y-4">
                    <h4 class="font-black text-slate-800 dark:text-white uppercase tracking-wider text-xs border-b pb-2 border-slate-100 dark:border-slate-800">เครื่องมือปรับแต่ง</h4>
                    
                    <!-- Element Selector / Visibility & Styling -->
                    <div class="space-y-3.5">
                        <!-- Active Element selector dropdown -->
                        <div class="space-y-1">
                            <label class="font-bold text-[10px] text-slate-450 uppercase tracking-wider">เลือกส่วนที่ต้องการปรับแต่ง</label>
                            <select id="designer-element-select" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl py-2.5 px-3 focus:outline-none focus:ring-1 focus:ring-purple-500 font-black text-xs text-slate-800 dark:text-white">
                                <option value="student_name">ชื่อผู้สอบผ่าน</option>
                                <option value="quiz_title">ชื่อแบบทดสอบ</option>
                                <option value="quiz_score">คะแนนแบบทดสอบ</option>
                                <option value="date">วันที่ออกประกาศ</option>
                                <option value="signature">รูปลักษณ์ลายเซ็น</option>
                                <option value="signer_name">ชื่อผู้ลงนาม</option>
                                <option value="signer_title">ตำแหน่งผู้ลงนาม</option>
                            </select>
                        </div>

                        <!-- Visibility Toggle -->
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-150 dark:border-slate-800">
                            <span class="font-bold text-xs">แสดงผลส่วนนี้</span>
                            <input type="checkbox" id="designer-visible-toggle" class="rounded border-slate-300 text-purple-600 focus:ring-purple-500 w-4 h-4 cursor-pointer">
                        </div>

                        <!-- Text Customization (only shown for signer name/title) -->
                        <div id="designer-text-custom-group" class="space-y-1">
                            <label class="font-bold text-[10px] text-slate-450 uppercase tracking-wider">แก้ไขข้อความตัวอย่าง</label>
                            <input type="text" id="designer-text-input" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl py-2 px-3 focus:outline-none focus:ring-1 focus:ring-purple-500 font-bold text-xs text-slate-800 dark:text-white">
                        </div>

                        <!-- Font Size Adjustment -->
                        <div id="designer-font-size-group" class="space-y-1">
                            <label class="font-bold text-[10px] text-slate-450 uppercase tracking-wider flex justify-between">
                                <span>ขนาดตัวอักษร</span>
                                <span id="designer-font-size-val" class="text-purple-600 dark:text-purple-400 font-black">14px</span>
                            </label>
                            <input type="range" id="designer-font-size-slider" min="8" max="72" class="w-full accent-purple-600">
                        </div>

                        <!-- Color Picker -->
                        <div id="designer-color-group" class="space-y-1">
                            <label class="font-bold text-[10px] text-slate-450 uppercase tracking-wider">สีของตัวอักษร</label>
                            <div class="flex gap-2">
                                <input type="color" id="designer-color-picker" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-850 cursor-pointer p-0 bg-transparent overflow-hidden">
                                <input type="text" id="designer-color-hex" class="flex-1 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 focus:outline-none text-xs font-bold uppercase tracking-wider text-slate-800 dark:text-white">
                            </div>
                        </div>

                        <!-- Alignment Selector -->
                        <div id="designer-align-group" class="space-y-1">
                            <label class="font-bold text-[10px] text-slate-450 uppercase tracking-wider">จัดแนวอักษร</label>
                            <select id="designer-align-select" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl py-2 px-3 focus:outline-none focus:ring-1 focus:ring-purple-500 font-black text-xs text-slate-800 dark:text-white">
                                <option value="center">กึ่งกลาง (Center)</option>
                                <option value="left">ชิดซ้าย (Left)</option>
                                <option value="right">ชิดขวา (Right)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload signature directly from Designer -->
                    <div class="space-y-2 border-t border-slate-100 dark:border-slate-800 pt-4">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">รูปลายเซ็นโปร่งแสง (.PNG)</label>
                        <div class="flex gap-2 items-center">
                            <input type="file" id="designer-signature-input" accept="image/png" class="flex-1 text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-rose-50 file:text-rose-700 dark:file:bg-rose-950/20 dark:file:text-rose-400 file:cursor-pointer">
                            <button type="button" onclick="removeSignatureImage()" class="p-2 bg-rose-100 text-rose-700 hover:bg-rose-200 rounded-xl text-[10px] font-black focus:outline-none transition-all">
                                ลบออก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3 bg-slate-50 dark:bg-slate-900/50">
            <button type="button" onclick="closeCertDesigner()" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-650 dark:text-slate-350 rounded-xl text-xs font-black transition-all">
                ยกเลิก
            </button>
            <button type="button" onclick="saveCertDesignerConfig()" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-black shadow-md transition-all active:scale-95">
                เสร็จสิ้นการตั้งค่าตำแหน่ง
            </button>
        </div>
    </div>
</div>

<script>
    // Load config from DB or fallback
    let certificateConfig = {!! !blank($quiz->certificate_config) ? $quiz->certificate_config : json_encode([
        'student_name' => ['x' => 25, 'y' => 46.6, 'size' => 44, 'color' => '#1e293b', 'align' => 'center', 'visible' => true],
        'quiz_title' => ['x' => 25, 'y' => 65.5, 'size' => 34, 'color' => '#1e3a8a', 'align' => 'center', 'visible' => true],
        'quiz_score' => ['x' => 12.4, 'y' => 94.4, 'size' => 48, 'color' => '#64748b', 'align' => 'left', 'visible' => true],
        'date' => ['x' => 25, 'y' => 73.0, 'size' => 24, 'color' => '#64748b', 'align' => 'center', 'visible' => true],
        'signature' => ['x' => 75.0, 'y' => 84.3, 'width' => 200, 'height' => 80, 'visible' => true],
        'signer_name' => ['x' => 75.0, 'y' => 96.3, 'size' => 22, 'color' => '#334155', 'align' => 'center', 'visible' => true, 'text' => 'ผู้บริหารสถานศึกษา'],
        'signer_title' => ['x' => 75.0, 'y' => 98.0, 'size' => 18, 'color' => '#64748b', 'align' => 'center', 'visible' => true, 'text' => 'ผู้ช่วยผู้ตรวจการศึกษา']
    ]) !!};

    const designerModal = document.getElementById('cert-designer-modal');
    const designerBg = document.getElementById('cert-designer-bg');
    const workspace = document.getElementById('cert-workspace');
    
    const certBase64Input = document.getElementById('quiz_certificate_base64');
    const certConfigInput = document.getElementById('certificate_config');

    // Populate initial base64 from current preview image
    const initialPreviewImg = document.getElementById('cert_preview');
    if (initialPreviewImg && initialPreviewImg.src && initialPreviewImg.src !== '#' && !initialPreviewImg.src.endsWith('/#')) {
        certBase64Input.value = initialPreviewImg.src;
    }

    window.openCertDesigner = () => {
        if (!certBase64Input.value) {
            alert('กรุณาอัปโหลดภาพพื้นหลังเกียรติบัตรก่อน!');
            return;
        }
        designerBg.src = certBase64Input.value;
        designerModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(applyConfigToWorkspace, 100);
    };

    window.closeCertDesigner = () => {
        designerModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    function applyConfigToWorkspace() {
        const parentRect = workspace.getBoundingClientRect();
        
        Object.keys(certificateConfig).forEach(key => {
            const cfg = certificateConfig[key];
            const dragId = `drag-${key.replace('_', '-')}`;
            const el = document.getElementById(dragId);
            if (!el) return;

            el.style.left = `${(cfg.x / 100) * parentRect.width}px`;
            el.style.top = `${(cfg.y / 100) * parentRect.height}px`;

            if (key !== 'signature') {
                el.style.fontSize = `${(cfg.size / 794) * parentRect.height}px`;
                el.style.color = cfg.color;
                el.style.textAlign = cfg.align;
                if (cfg.text !== undefined) {
                    el.innerText = cfg.text;
                }
            } else {
                el.style.width = `${(cfg.width / 1123) * parentRect.width}px`;
                el.style.height = `${(cfg.height / 794) * parentRect.height}px`;
            }

            if (cfg.visible) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });

        selectDesignerElement();
    }

    // Controls sync
    const elemSelect = document.getElementById('designer-element-select');
    const visibleToggle = document.getElementById('designer-visible-toggle');
    const textCustomGroup = document.getElementById('designer-text-custom-group');
    const textInput = document.getElementById('designer-text-input');
    const fontSizeGroup = document.getElementById('designer-font-size-group');
    const fontSizeSlider = document.getElementById('designer-font-size-slider');
    const fontSizeVal = document.getElementById('designer-font-size-val');
    const colorGroup = document.getElementById('designer-color-group');
    const colorPicker = document.getElementById('designer-color-picker');
    const colorHex = document.getElementById('designer-color-hex');
    const alignGroup = document.getElementById('designer-align-group');
    const alignSelect = document.getElementById('designer-align-select');

    window.selectDesignerElement = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg) return;

        visibleToggle.checked = cfg.visible;

        if (cfg.text !== undefined) {
            textCustomGroup.classList.remove('hidden');
            textInput.value = cfg.text;
        } else {
            textCustomGroup.classList.add('hidden');
        }

        if (key !== 'signature') {
            fontSizeGroup.classList.remove('hidden');
            fontSizeSlider.value = cfg.size;
            fontSizeVal.innerText = `${cfg.size}px`;
            
            colorGroup.classList.remove('hidden');
            colorPicker.value = cfg.color;
            colorHex.value = cfg.color;

            alignGroup.classList.remove('hidden');
            alignSelect.value = cfg.align || 'center';
        } else {
            fontSizeGroup.classList.add('hidden');
            colorGroup.classList.add('hidden');
            alignGroup.classList.add('hidden');
        }
    };

    window.toggleDesignerElementVisibility = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg) return;

        cfg.visible = visibleToggle.checked;
        const dragId = `drag-${key.replace('_', '-')}`;
        const el = document.getElementById(dragId);
        
        if (cfg.visible) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    };

    window.updateDesignerElementText = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg || cfg.text === undefined) return;

        cfg.text = textInput.value;
        const dragId = `drag-${key.replace('_', '-')}`;
        const el = document.getElementById(dragId);
        el.innerText = cfg.text;
    };

    window.updateDesignerElementFontSize = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg || key === 'signature') return;

        cfg.size = parseInt(fontSizeSlider.value);
        fontSizeVal.innerText = `${cfg.size}px`;
        
        const dragId = `drag-${key.replace('_', '-')}`;
        const el = document.getElementById(dragId);
        const parentRect = workspace.getBoundingClientRect();
        el.style.fontSize = `${(cfg.size / 794) * parentRect.height}px`;
    };

    window.updateDesignerElementColor = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg || key === 'signature') return;

        cfg.color = colorPicker.value;
        colorHex.value = cfg.color;
        
        const dragId = `drag-${key.replace('_', '-')}`;
        const el = document.getElementById(dragId);
        el.style.color = cfg.color;
    };

    window.updateDesignerElementColorHex = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg || key === 'signature') return;

        let val = colorHex.value;
        if (val.startsWith('#') && val.length === 7) {
            cfg.color = val;
            colorPicker.value = val;
            const dragId = `drag-${key.replace('_', '-')}`;
            const el = document.getElementById(dragId);
            el.style.color = cfg.color;
        }
    };

    window.updateDesignerElementAlign = () => {
        const key = elemSelect.value;
        const cfg = certificateConfig[key];
        if (!cfg || key === 'signature') return;

        cfg.align = alignSelect.value;
        const dragId = `drag-${key.replace('_', '-')}`;
        const el = document.getElementById(dragId);
        el.style.textAlign = cfg.align;
    };

    // Signature upload file selector sync
    const sigDesignerInput = document.getElementById('designer-signature-input');
    const sigHiddenFileInput = document.getElementById('quiz_signature_hidden_file');
    const sigRemoveInput = document.getElementById('quiz_signature_remove');
    const sigPreviewImg = document.getElementById('designer-signature-img');
    const sigPlaceholder = document.getElementById('designer-signature-placeholder');

    // Populate initial signature image if exists in DB
    @if(!blank($quiz->certificate_signature))
        sigPreviewImg.src = '{{ $quiz->certificate_signature }}';
        sigPreviewImg.classList.remove('hidden');
        sigPlaceholder.classList.add('hidden');
    @endif

    sigDesignerInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type !== 'image/png') {
                alert('รูปลอยเซ็นต้องเป็นภาพแบบโปร่งแสง (.PNG) เท่านั้น!');
                this.value = '';
                return;
            }

            sigHiddenFileInput.files = this.files;
            sigRemoveInput.value = 'false';

            const reader = new FileReader();
            reader.onload = function(event) {
                sigPreviewImg.src = event.target.result;
                sigPreviewImg.classList.remove('hidden');
                sigPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    window.removeSignatureImage = () => {
        sigDesignerInput.value = '';
        sigHiddenFileInput.value = '';
        sigRemoveInput.value = 'true';
        sigPreviewImg.src = '';
        sigPreviewImg.classList.add('hidden');
        sigPlaceholder.classList.remove('hidden');
        alert('ลบลายเซ็นเก่าออกจากเกียรติบัตรแล้ว (กดตกลงเพื่อบันทึก)');
    };

    // Drag helper
    const dragElements = [
        'drag-student-name', 'drag-quiz-title', 'drag-quiz-score',
        'drag-date', 'drag-signature', 'drag-signer-name', 'drag-signer-title'
    ];

    let activeDragElement = null;
    let startX = 0, startY = 0;
    let startLeft = 0, startTop = 0;

    function handleDragStart(el, clientX, clientY) {
        activeDragElement = el;
        startX = clientX;
        startY = clientY;
        
        const rect = el.getBoundingClientRect();
        const parentRect = workspace.getBoundingClientRect();
        
        startLeft = rect.left - parentRect.left;
        startTop = rect.top - parentRect.top;
        
        const key = el.id.replace('drag-', '').replace('-', '_');
        elemSelect.value = key;
        selectDesignerElement();
    }

    function handleDragMove(clientX, clientY) {
        if (!activeDragElement) return;
        
        const dx = clientX - startX;
        const dy = clientY - startY;
        
        let newLeft = startLeft + dx;
        let newTop = startTop + dy;
        
        const parentRect = workspace.getBoundingClientRect();
        const rect = activeDragElement.getBoundingClientRect();
        
        if (newLeft < 0) newLeft = 0;
        if (newTop < 0) newTop = 0;
        if (newLeft + rect.width > parentRect.width) newLeft = parentRect.width - rect.width;
        if (newTop + rect.height > parentRect.height) newTop = parentRect.height - rect.height;
        
        activeDragElement.style.left = `${newLeft}px`;
        activeDragElement.style.top = `${newTop}px`;
    }

    dragElements.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;

        el.addEventListener('mousedown', (e) => {
            handleDragStart(el, e.clientX, e.clientY);
            e.preventDefault();
        });

        el.addEventListener('touchstart', (e) => {
            handleDragStart(el, e.touches[0].clientX, e.touches[0].clientY);
        }, { passive: true });
    });

    window.addEventListener('mousemove', (e) => {
        handleDragMove(e.clientX, e.clientY);
    });

    window.addEventListener('touchmove', (e) => {
        if (activeDragElement) {
            handleDragMove(e.touches[0].clientX, e.touches[0].clientY);
            e.preventDefault();
        }
    }, { passive: false });

    window.addEventListener('mouseup', () => { activeDragElement = null; });
    window.addEventListener('touchend', () => { activeDragElement = null; });

    // Save positions
    window.saveCertDesignerConfig = () => {
        const parentRect = workspace.getBoundingClientRect();
        
        Object.keys(certificateConfig).forEach(key => {
            const cfg = certificateConfig[key];
            const dragId = `drag-${key.replace('_', '-')}`;
            const el = document.getElementById(dragId);
            if (!el) return;

            const rect = el.getBoundingClientRect();
            const left = rect.left - parentRect.left;
            const top = rect.top - parentRect.top;

            cfg.x = Math.round((left / parentRect.width) * 1000) / 10;
            cfg.y = Math.round((top / parentRect.height) * 1000) / 10;
        });

        certConfigInput.value = JSON.stringify(certificateConfig);
        closeCertDesigner();
    };
</script>
</x-teachers-layout>