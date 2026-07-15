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
            method="POST" enctype="multipart/form-data" class="max-w-7xl mx-auto pb-20 px-4 space-y-8">
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
                                        <option value="0" @selected($quiz->grade_level == 0 || is_null($quiz->grade_level))>ทุกระดับ</option>
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
                                                <input type="hidden" name="quiz_signature_remove" id="quiz_signature_remove" value="false">
                                                <input type="file" name="quiz_logo" id="quiz_logo_hidden_file" class="hidden" accept="image/*">
                                                <input type="hidden" name="quiz_logo_remove" id="quiz_logo_remove" value="false">
                                                
                                                <div id="cert_placeholder_btn" 
                                                    onclick="document.getElementById('cert_input').click()" 
                                                    class="{{ !blank($quiz->certificate_image) ? 'hidden' : '' }} cursor-pointer w-full h-[200px] bg-slate-50 border-4 border-dashed border-slate-200 rounded-[1.5rem] flex flex-col items-center justify-center text-slate-400 hover:text-emerald-500 hover:border-emerald-300 transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-xs font-bold">เพิ่มเกียรติบัตร</span>
                                                </div>

                                                <div id="cert_main_preview_container" class="{{ blank($quiz->certificate_image) ? 'hidden' : '' }} relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-955 flex flex-col gap-3">
                                                    <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center aspect-[1123/794]">
                                                        <canvas id="cert_main_preview_canvas" class="w-full h-auto max-h-[150px] object-contain"></canvas>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button type="button" onclick="openCertConfigModal()" class="flex-1 py-2 px-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[11.5px] font-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-1.5 focus:outline-none">
                                                            🎨 ออกแบบและตั้งค่าเกียรติบัตร
                                                        </button>
                                                        <button type="button" onclick="removeCertImage()" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-lg focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

<!-- Certificate Config Modal -->
<div id="cert-config-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" x-cloak>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeCertConfigModal()"></div>
    
    <!-- Modal Body -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-5xl rounded-[2.5rem] bg-white dark:bg-slate-900 shadow-2xl transition-all border border-slate-100 dark:border-slate-800 flex flex-col overflow-hidden animate-in zoom-in duration-200">
            
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 px-8 py-5">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-amber-50 dark:bg-amber-950/30 text-amber-500 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 dark:text-white">จัดการรูปแบบเกียรติบัตร</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Branding & Certificate Settings</p>
                    </div>
                </div>
                <button type="button" onclick="closeCertConfigModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-600 transition-colors focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Content (Side by Side on Large Screens) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 p-8 max-h-[70vh] overflow-y-auto bg-slate-50 dark:bg-slate-955">
                <!-- Left Side: Live Preview -->
                <div class="lg:col-span-5 space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">ตัวอย่างเกียรติบัตร (Live Preview)</h4>
                    
                    <div id="cert_placeholder" onclick="document.getElementById('cert_input').click()" 
                        class="{{ !blank($quiz->certificate_image) ? 'hidden' : '' }} cursor-pointer w-full h-[220px] bg-white dark:bg-slate-900 border-4 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-300 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-xs font-black">อัปโหลดพื้นหลังเกียรติบัตร</span>
                        <span class="text-[10px] text-slate-400 mt-1 font-bold">แนะนำไฟล์รูปภาพแนวนอน ขนาด A4</span>
                    </div>

                    <div id="cert_modal_preview_container" class="{{ blank($quiz->certificate_image) ? 'hidden' : '' }} relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900 shadow-sm">
                        <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 flex items-center justify-center aspect-[1123/794]">
                            <canvas id="cert_preview_canvas" class="w-full h-auto max-h-[260px] object-contain"></canvas>
                        </div>
                        <div class="flex gap-2 mt-4">
                            <button type="button" onclick="document.getElementById('cert_input').click()" class="flex-1 py-2 px-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[11px] font-black shadow-md transition-all active:scale-95 focus:outline-none flex items-center justify-center gap-1.5">📷 เปลี่ยนพื้นหลัง</button>
                            <button type="button" onclick="removeCertImage()" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-md focus:outline-none flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Config Form Inputs -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Certificate Content Settings -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">ข้อความบนเกียรติบัตร</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ชื่อหน่วยงาน</label>
                                <input type="text" id="cert_org_name" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="กรมส่งเสริมการเรียนรู้">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">สังกัด / กระทรวง</label>
                                <input type="text" id="cert_sub_org" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="กระทรวงศึกษาธิการ">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ข้อความนำ <span class="text-slate-350 font-medium">(ก่อนชื่อผู้เรียน)</span></label>
                            <input type="text" id="cert_prefix_text" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า">
                        </div>
                        <div class="p-3 bg-amber-50/50 dark:bg-amber-955/20 rounded-xl border border-amber-200/50 dark:border-amber-900/30 text-[10px] text-amber-700 dark:text-amber-400 font-bold">
                            🔒 ชื่อผู้เรียน — ดึงจากระบบอัตโนมัติ
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">คำอธิบาย <span class="text-slate-350 font-medium">(หลังชื่อผู้เรียน)</span></label>
                            <textarea id="cert_description" rows="2" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ได้ผ่านการทำแบบทดสอบ"></textarea>
                        </div>
                        <div class="p-3 bg-amber-50/50 dark:bg-amber-955/20 rounded-xl border border-amber-200/50 dark:border-amber-900/30 text-[10px] text-amber-700 dark:text-amber-400 font-bold">
                            🔒 ชื่อแบบทดสอบ / วันที่ — ดึงจากระบบอัตโนมัติ
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ข้อความอวยพร <span class="text-slate-350 font-medium">(ปิดท้าย)</span></label>
                            <input type="text" id="cert_blessing" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ขอให้ประสบแต่ความสุข...">
                        </div>
                    </div>

                    <!-- Logo & Signature Uploads -->
                    <div class="space-y-4 border-t border-slate-200 dark:border-slate-800 pt-5">
                        <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">โลโก้ และ ลายเซ็น</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Logo File Input -->
                            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col gap-3">
                                <span class="text-[10px] font-bold text-slate-500">โลโก้ประทับ (สี่เหลี่ยมจัตุรัส)</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-xl bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                                        <img id="cert_logo_img" src="#" class="w-full h-full object-contain hidden">
                                        <span id="cert_logo_placeholder" class="text-[8px] text-slate-400 font-bold text-center">ไม่มีรูป</span>
                                    </div>
                                    <div class="flex-1 flex flex-col gap-1.5">
                                        <button type="button" onclick="document.getElementById('quiz_logo_hidden_file').click()" class="py-1 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-white rounded-lg text-[10px] font-black transition-all">อัปโหลดภาพ</button>
                                        <button type="button" onclick="removeLogo()" class="text-left text-rose-500 hover:text-rose-600 text-[9px] font-bold">ลบภาพนี้</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Signature File Input -->
                            <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col gap-3">
                                <span class="text-[10px] font-bold text-slate-500">รูปลายเซ็น (โปร่งแสง PNG)</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-20 h-14 rounded-xl bg-slate-50 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 flex items-center justify-center overflow-hidden">
                                        <img id="cert_sig_img" src="#" class="w-full h-full object-contain hidden">
                                        <span id="cert_sig_placeholder" class="text-[8px] text-slate-400 font-bold text-center">ไม่มีรูป</span>
                                    </div>
                                    <div class="flex-1 flex flex-col gap-1.5">
                                        <button type="button" onclick="document.getElementById('quiz_signature_hidden_file').click()" class="py-1 px-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-white rounded-lg text-[10px] font-black transition-all">อัปโหลดภาพ</button>
                                        <button type="button" onclick="removeSignature()" class="text-left text-rose-500 hover:text-rose-600 text-[9px] font-bold">ลบภาพนี้</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signer Information -->
                    <div class="space-y-4 border-t border-slate-200 dark:border-slate-800 pt-5">
                        <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">ผู้ลงนาม</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ชื่อผู้ลงนาม</label>
                                <input type="text" id="cert_signer_name" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="(นางเกศทิพย์ ศุภวานิช)">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ตำแหน่ง</label>
                                <input type="text" id="cert_signer_title" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ผู้อำนวยการ กศน.อำเภอ...">
                            </div>
                        </div>
                    </div>

                    <!-- Text Color Settings -->
                    <div class="space-y-4 border-t border-slate-200 dark:border-slate-800 pt-5">
                        <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">โทนสีข้อความ</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">สีข้อความหลัก</label>
                                <div class="flex gap-2">
                                    <input type="color" id="cert_text_color" value="#475569" class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer overflow-hidden p-0 bg-transparent">
                                    <input type="text" id="cert_text_color_hex" value="#475569" class="flex-1 rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-[10px] font-bold uppercase py-1 px-2 focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">สีชื่อหน่วยงาน</label>
                                <div class="flex gap-2">
                                    <input type="color" id="cert_org_color" value="#1e293b" class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer overflow-hidden p-0 bg-transparent">
                                    <input type="text" id="cert_org_color_hex" value="#1e293b" class="flex-1 rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-[10px] font-bold uppercase py-1 px-2 focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">สีชื่อผู้เรียน / จุดเน้น</label>
                                <div class="flex gap-2">
                                    <input type="color" id="cert_student_color" value="#0f172a" class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer overflow-hidden p-0 bg-transparent">
                                    <input type="text" id="cert_student_color_hex" value="#0f172a" class="flex-1 rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-[10px] font-bold uppercase py-1 px-2 focus:ring-1 focus:ring-amber-500 focus:border-amber-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer / Actions -->
            <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 px-8 py-5">
                <button type="button" onclick="closeCertConfigModal()" class="w-full sm:w-auto px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black tracking-wider uppercase transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                    ✓ ยืนยันการตั้งค่า
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Load config from DB or fallback
    let savedConfig = {};
    try {
        savedConfig = JSON.parse(document.getElementById('certificate_config').value || '{}');
    } catch(e) {
        console.error("Failed to parse existing certificate config", e);
    }

    const certInput = document.getElementById('cert_input');
    const certModalPreviewContainer = document.getElementById('cert_modal_preview_container');
    const certMainPreviewContainer = document.getElementById('cert_main_preview_container');
    const certPlaceholderBtn = document.getElementById('cert_placeholder_btn');
    const certPlaceholder = document.getElementById('cert_placeholder');
    const certBase64Input = document.getElementById('quiz_certificate_base64');
    const certConfigInput = document.getElementById('certificate_config');

    // Populate initial inputs from savedConfig
    document.getElementById('cert_org_name').value = savedConfig.org_name || '';
    document.getElementById('cert_sub_org').value = savedConfig.sub_org_name || '';
    document.getElementById('cert_prefix_text').value = savedConfig.prefix_text || 'ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า';
    document.getElementById('cert_description').value = savedConfig.description_text || '';
    document.getElementById('cert_blessing').value = savedConfig.blessing_text || 'ขอให้ประสบแต่ความสุข ความเจริญ และมีความก้าวหน้าต่อไป';
    document.getElementById('cert_signer_name').value = savedConfig.signer_name || '';
    document.getElementById('cert_signer_title').value = savedConfig.signer_title || '';

    // Colors
    const initialTextColor = savedConfig.text_color || '#475569';
    const initialOrgColor = savedConfig.org_color || '#1e293b';
    const initialStudentColor = savedConfig.student_color || '#0f172a';

    document.getElementById('cert_text_color').value = initialTextColor;
    document.getElementById('cert_text_color_hex').value = initialTextColor;
    document.getElementById('cert_org_color').value = initialOrgColor;
    document.getElementById('cert_org_color_hex').value = initialOrgColor;
    document.getElementById('cert_student_color').value = initialStudentColor;
    document.getElementById('cert_student_color_hex').value = initialStudentColor;

    // Cached images for live preview rendering
    let cachedBgImage = null;
    let cachedLogoImage = null;
    let cachedSigImage = null;

    // Load initial images if exist
    @if(!blank($quiz->certificate_image))
        updateCachedBg('{{ $quiz->certificate_image }}');
    @endif

    if (savedConfig.logo_url) {
        document.getElementById('cert_logo_img').src = savedConfig.logo_url;
        document.getElementById('cert_logo_img').classList.remove('hidden');
        document.getElementById('cert_logo_placeholder').classList.add('hidden');
        updateCachedLogo(savedConfig.logo_url);
    }

    @if(!blank($quiz->certificate_signature))
        document.getElementById('cert_sig_img').src = '{{ $quiz->certificate_signature }}';
        document.getElementById('cert_sig_img').classList.remove('hidden');
        document.getElementById('cert_sig_placeholder').classList.add('hidden');
        updateCachedSig('{{ $quiz->certificate_signature }}');
    @endif

    function drawCertPreview() {
        // Redraw both modal canvas and main preview canvas if they exist
        const canvases = [
            document.getElementById('cert_preview_canvas'),
            document.getElementById('cert_main_preview_canvas')
        ];

        canvases.forEach(canvas => {
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            canvas.width = 1123;
            canvas.height = 794;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Get inputs
            const orgName = document.getElementById('cert_org_name').value;
            const subOrgName = document.getElementById('cert_sub_org').value;
            const prefixText = document.getElementById('cert_prefix_text').value;
            const descriptionText = document.getElementById('cert_description').value;
            const blessingText = document.getElementById('cert_blessing').value;
            const signerName = document.getElementById('cert_signer_name').value;
            const signerTitle = document.getElementById('cert_signer_title').value;

            // Get colors
            const textColor = document.getElementById('cert_text_color').value || '#475569';
            const orgColor = document.getElementById('cert_org_color').value || '#1e293b';
            const studentColor = document.getElementById('cert_student_color').value || '#0f172a';

            const fontFamily = "'Prompt', sans-serif";
            const centerX = canvas.width / 2;
            ctx.textBaseline = "middle";

            // Draw Background
            if (cachedBgImage && cachedBgImage.complete && cachedBgImage.naturalWidth > 0) {
                ctx.drawImage(cachedBgImage, 0, 0, canvas.width, canvas.height);
            } else {
                ctx.fillStyle = '#f8fafc';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.strokeStyle = '#e2e8f0';
                ctx.lineWidth = 10;
                ctx.strokeRect(0, 0, canvas.width, canvas.height);
            }

            // Draw Logo
            if (cachedLogoImage && cachedLogoImage.complete && cachedLogoImage.naturalWidth > 0) {
                const logoSize = 90;
                ctx.drawImage(cachedLogoImage, centerX - (logoSize / 2), 60, logoSize, logoSize);
            } else {
                ctx.beginPath();
                ctx.arc(centerX, 105, 45, 0, 2 * Math.PI);
                ctx.fillStyle = '#f1f5f9';
                ctx.fill();
                ctx.lineWidth = 2;
                ctx.strokeStyle = '#cbd5e1';
                ctx.stroke();
                ctx.font = `bold 12px ${fontFamily}`;
                ctx.fillStyle = '#94a3b8';
                ctx.textAlign = 'center';
                ctx.fillText('ตราหน่วยงาน', centerX, 105);
            }

            // 2. Organization Name
            ctx.textAlign = "center";
            ctx.font = `bold 28px ${fontFamily}`;
            ctx.fillStyle = orgColor;
            ctx.fillText(orgName || 'ชื่อหน่วยงานหลัก', centerX, 185);

            // 3. Sub-organization Name
            ctx.font = `bold 20px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(subOrgName || 'ชื่อสังกัด/กระทรวง', centerX, 225);

            // 4. Prefix Text
            ctx.font = `bold 20px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(prefixText || 'ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า', centerX, 275);

            // 5. Student Name (Placeholder)
            ctx.font = `900 44px ${fontFamily}`;
            ctx.fillStyle = studentColor;
            ctx.fillText('นายสมชาย รักการเรียน (ชื่อผู้เรียน)', centerX, 355);

            // 6. Description Text
            ctx.font = `bold 20px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(descriptionText || 'ได้ผ่านเกณฑ์การประเมินการทดสอบออนไลน์...', centerX, 440);

            // 7. Date
            ctx.font = `bold 18px ${fontFamily}`;
            ctx.fillStyle = textColor;
            const dateStr = new Date().toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric' });
            ctx.fillText(`ให้ไว้ ณ วันที่ ${dateStr}`, centerX, 520);

            // 8. Blessing Text
            ctx.font = `italic 18px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(blessingText || 'ขอให้ประสบแต่ความสุข ความเจริญ และมีความก้าวหน้าต่อไป', centerX, 565);

            // 9. Signature
            if (cachedSigImage && cachedSigImage.complete && cachedSigImage.naturalWidth > 0) {
                const sigWidth = 160;
                const sigHeight = 64;
                ctx.drawImage(cachedSigImage, centerX - (sigWidth / 2), 600, sigWidth, sigHeight);
            } else {
                ctx.font = `12px ${fontFamily}`;
                ctx.fillStyle = '#94a3b8';
                ctx.fillText('[พื้นที่ลายเซ็น]', centerX, 632);
            }

            // 10. Signer Name
            ctx.font = `bold 18px ${fontFamily}`;
            ctx.fillStyle = orgColor;
            ctx.fillText(signerName || '(ชื่อผู้ลงนาม)', centerX, 685);

            // 11. Signer Title
            ctx.font = `bold 16px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(signerTitle || 'ตำแหน่งผู้ลงนาม', centerX, 715);

            // 12. Certificate Number
            ctx.font = `bold 14px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.fillText(`เลขที่ใบรับรอง COXXXX`, centerX, 755);
        });
    }

    function updateCachedBg(src) {
        if (!src || src === '#') {
            cachedBgImage = null;
            drawCertPreview();
            return;
        }
        cachedBgImage = new Image();
        cachedBgImage.onload = drawCertPreview;
        cachedBgImage.src = src;
    }

    function updateCachedLogo(src) {
        if (!src || src === '#') {
            cachedLogoImage = null;
            drawCertPreview();
            return;
        }
        cachedLogoImage = new Image();
        cachedLogoImage.onload = drawCertPreview;
        cachedLogoImage.src = src;
    }

    function updateCachedSig(src) {
        if (!src || src === '#') {
            cachedSigImage = null;
            drawCertPreview();
            return;
        }
        cachedSigImage = new Image();
        cachedSigImage.onload = drawCertPreview;
        cachedSigImage.src = src;
    }

    function syncConfigToHidden() {
        const config = {
            org_name: document.getElementById('cert_org_name').value,
            sub_org_name: document.getElementById('cert_sub_org').value,
            prefix_text: document.getElementById('cert_prefix_text').value,
            description_text: document.getElementById('cert_description').value,
            blessing_text: document.getElementById('cert_blessing').value,
            signer_name: document.getElementById('cert_signer_name').value,
            signer_title: document.getElementById('cert_signer_title').value,
            text_color: document.getElementById('cert_text_color').value,
            org_color: document.getElementById('cert_org_color').value,
            student_color: document.getElementById('cert_student_color').value,
            logo_url: (cachedLogoImage ? cachedLogoImage.src : '')
        };
        certConfigInput.value = JSON.stringify(config);
        drawCertPreview();
    }

    // Bind inputs to sync and redraw
    ['cert_org_name','cert_sub_org','cert_prefix_text','cert_description','cert_blessing','cert_signer_name','cert_signer_title'].forEach(id => {
        document.getElementById(id).addEventListener('input', syncConfigToHidden);
    });

    // Color Pickers sync
    ['cert_text_color', 'cert_org_color', 'cert_student_color'].forEach(id => {
        const picker = document.getElementById(id);
        const hex = document.getElementById(id + '_hex');
        
        picker.addEventListener('input', () => {
            hex.value = picker.value;
            syncConfigToHidden();
        });
        
        hex.addEventListener('input', () => {
            let val = hex.value;
            if (val.length === 6 && !val.startsWith('#')) {
                val = '#' + val;
            }
            if (val.startsWith('#') && val.length === 7) {
                picker.value = val;
                syncConfigToHidden();
            }
        });
    });

    // Populate initial base64 from current preview image
    @if(!blank($quiz->certificate_image))
        certBase64Input.value = '{{ $quiz->certificate_image }}';
    @endif

    syncConfigToHidden();

    function previewCertImage(input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('ไฟล์เกียรติบัตรใหญ่เกินไป (ไม่ควรเกิน 2MB)');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(event) {
            if (certModalPreviewContainer) certModalPreviewContainer.classList.remove('hidden');
            if (certMainPreviewContainer) certMainPreviewContainer.classList.remove('hidden');
            if (certPlaceholderBtn) certPlaceholderBtn.classList.add('hidden');
            if (certPlaceholder) certPlaceholder.classList.add('hidden');
            certBase64Input.value = event.target.result;
            updateCachedBg(event.target.result);
        };
        reader.readAsDataURL(file);
    }

    function removeCertImage() {
        certInput.value = '';
        certBase64Input.value = 'REMOVE';
        certConfigInput.value = '';
        if (certModalPreviewContainer) certModalPreviewContainer.classList.add('hidden');
        if (certMainPreviewContainer) certMainPreviewContainer.classList.add('hidden');
        if (certPlaceholderBtn) certPlaceholderBtn.classList.remove('hidden');
        if (certPlaceholder) certPlaceholder.classList.remove('hidden');
        updateCachedBg(null);
    }

    document.getElementById('quiz_logo_hidden_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const logoImg = document.getElementById('cert_logo_img');
            logoImg.src = event.target.result;
            logoImg.classList.remove('hidden');
            document.getElementById('cert_logo_placeholder').classList.add('hidden');
            document.getElementById('quiz_logo_remove').value = 'false';
            updateCachedLogo(event.target.result);
        };
        reader.readAsDataURL(file);
    });

    function removeLogo() {
        document.getElementById('quiz_logo_hidden_file').value = '';
        document.getElementById('cert_logo_img').src = '#';
        document.getElementById('cert_logo_img').classList.add('hidden');
        document.getElementById('cert_logo_placeholder').classList.remove('hidden');
        document.getElementById('quiz_logo_remove').value = 'true';
        updateCachedLogo(null);
    }

    document.getElementById('quiz_signature_hidden_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const sigImg = document.getElementById('cert_sig_img');
            sigImg.src = event.target.result;
            sigImg.classList.remove('hidden');
            document.getElementById('cert_sig_placeholder').classList.add('hidden');
            document.getElementById('quiz_signature_remove').value = 'false';
            updateCachedSig(event.target.result);
        };
        reader.readAsDataURL(file);
    });

    function removeSignature() {
        document.getElementById('quiz_signature_hidden_file').value = '';
        document.getElementById('cert_sig_img').src = '#';
        document.getElementById('cert_sig_img').classList.add('hidden');
        document.getElementById('cert_sig_placeholder').classList.remove('hidden');
        document.getElementById('quiz_signature_remove').value = 'true';
        updateCachedSig(null);
    }

    function openCertConfigModal() {
        const modal = document.getElementById('cert-config-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(drawCertPreview, 100);
    }

    function closeCertConfigModal() {
        const modal = document.getElementById('cert-config-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Expose functions globally to window object
    window.openCertConfigModal = openCertConfigModal;
    window.closeCertConfigModal = closeCertConfigModal;
    window.previewCertImage = previewCertImage;
    window.removeCertImage = removeCertImage;
    window.removeLogo = removeLogo;
    window.removeSignature = removeSignature;
    window.drawCertPreview = drawCertPreview;
</script>
</x-teachers-layout>