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

                                        <div class="group">
                                            <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">ภาพเกียรติบัตร</label>
                                            <div class="relative">
                                                <div id="cert_placeholder" 
                                                    onclick="document.getElementById('cert_input').click()" 
                                                    class="{{ !blank($quiz->certificate_image) ? 'hidden' : '' }} cursor-pointer w-full h-[200px] bg-slate-50 border-4 border-dashed border-slate-200 rounded-[1.5rem] flex flex-col items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-300 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-xs font-bold">เพิ่มเกียรติบัตร</span>
                                                </div>

                                                <div id="cert_preview_container" 
                                                    class="{{ blank($quiz->certificate_image) ? 'hidden' : '' }} relative">
                                                    <img id="cert_preview" 
                                                        src="{{ !blank($quiz->certificate_image) ? $quiz->certificate_image : '' }}" 
                                                        class="preview-img-box border-4 border-slate-100 object-cover">
                                                    
                                                    <button type="button" 
                                                            onclick="removeCertImage()" 
                                                            class="absolute -top-2 -right-2 p-2 bg-rose-500 text-white rounded-full hover:bg-rose-600 shadow-lg transition-transform active:scale-90">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <input type="file" id="cert_input" accept="image/*" class="hidden" onchange="previewCertImage(this)">
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
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- Remove Certificate ---
        function removeCertImage() {
            document.getElementById('cert_input').value = ""; // ล้างไฟล์ใน input
            document.getElementById('quiz_certificate_base64').value = "REMOVE"; // ส่งค่าไปบอก Controller ว่าให้ลบรูปเดิม
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
</x-teachers-layout>