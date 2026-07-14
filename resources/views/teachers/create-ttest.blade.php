<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<x-teachers-layout>
    <style>
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* ปรับแต่ง Tom Select ให้เข้ากับ UI เดิม */
        .ts-control { 
            border-radius: 0.75rem !important; 
            padding: 0.625rem !important; 
            border-color: #d1d5db !important; 
            transition: all 0.2s;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        }
        .ts-wrapper.disabled .ts-control { background-color: #f9fafb !important; opacity: 0.6; }
    </style>

    <div class="p-4 mt-20 animate-fade-in">
        <form action="{{ route('ttest.store') }}" method="POST" enctype="multipart/form-data" class="max-w-7xl mx-auto pb-20 px-4 space-y-8">
            @csrf
            <input type="file" id="excel_import" accept=".xlsx, .xls, .csv" class="hidden">
            
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden animate-fade-in">
                <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-indigo-800 px-10 py-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="p-4 bg-white/15 backdrop-blur-xl rounded-2xl text-white shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-white tracking-tight">สร้างแบบทดสอบใหม่</h2>
                                <p class="text-indigo-100/80 text-sm font-medium">ระบุรายละเอียดและตั้งค่าระบบความปลอดภัย</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        <div class="lg:col-span-7 space-y-8">
                            
                            <div class="group">
                                <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1 transition-colors group-focus-within:text-indigo-600">
                                    ภาพหน้าปกแบบทดสอบ (สัดส่วน 16:9)
                                </label>
                                <div class="relative group/cover">
                                    <input type="file" id="quiz_cover_input" accept="image/*" class="hidden">
                                    <input type="hidden" name="quiz_cover_base64" id="quiz_cover_base64">

                                    <label for="quiz_cover_input" class="relative flex flex-col items-center justify-center w-full min-h-[300px] rounded-[2rem] border-2 border-dashed border-slate-200 bg-slate-50/50 hover:bg-white hover:border-indigo-400 transition-all cursor-pointer overflow-hidden">
                                        
                                        <div id="preview-wrapper" class="hidden w-full h-full">
                                            <img id="image-to-crop" class="max-w-full block">
                                        </div>

                                        <div id="upload-placeholder" class="flex flex-col items-center justify-center text-slate-400 group-hover/cover:text-indigo-500 transition-colors px-6 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-sm font-black uppercase tracking-wider">คลิกเพื่อเลือกภาพหน้าปก</p>
                                            <p class="text-xs mt-1 font-medium opacity-60 text-slate-400">ขนาดแนะนำ 1280x720 px (16:9)</p>
                                        </div>
                                    </label>

                                    <div id="crop-controls" class="hidden absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10 bg-white/90 backdrop-blur-md p-2 rounded-2xl shadow-xl border border-slate-200">
                                        <button type="button" id="btn-crop" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-black hover:bg-indigo-700 transition-all flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            ใช้รูปนี้
                                        </button>
                                        <button type="button" id="btn-reset" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-black hover:bg-rose-50 hover:text-rose-600 transition-all">
                                            ยกเลิก
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="group">
                                <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1 transition-colors group-focus-within:text-indigo-600">
                                    ชื่อแบบทดสอบ <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="quiz_title" required placeholder="เช่น แบบทดสอบกลางภาค วิชาภาษาไทย" 
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all py-4 px-5 text-lg text-slate-600 font-bold placeholder:text-slate-400">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">ระดับชั้น <span class="text-rose-500">*</span></label>
                                    <select id="grade_level" name="grade_level" required
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all py-4 px-5 text-slate-600 font-bold cursor-pointer">
                                        <option value="">เลือกระดับชั้น</option>
                                        <option value="0">ทุกระดับ</option>
                                        <option value="1">ประถมศึกษา</option>
                                        <option value="2">มัธยมศึกษาตอนต้น</option>
                                        <option value="3">มัธยมศึกษาตอนปลาย</option>
                                    </select>
                                </div>
                                <div class="group">
                                    <label class="flex items-center gap-2 text-sm font-black text-slate-700 mb-3 ml-1">รายวิชา <span class="text-rose-500">*</span></label>
                                    <select id="subject_select" name="subject_code" required 
                                        class="w-full rounded-2xl border-slate-200 bg-slate-50/50 py-4 px-5 font-bold"></select>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-black text-slate-700 mb-3 ml-1 uppercase tracking-wider">คำอธิบายเพิ่มเติม</label>
                                <textarea name="quiz_description" rows="4" placeholder="ระบุรายละเอียดหรือคำชี้แจง..." 
                                    class="w-full rounded-2xl border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm py-4 px-5 resize-none"></textarea>
                            </div>

                            <input type="hidden" id="subject_group_display">
                            <input type="hidden" name="subject_group" id="subject_group">
                        </div>

                        <div class="lg:col-span-5 space-y-6">
                            <div class="bg-slate-50 rounded-[2.5rem] p-8 border border-slate-100 space-y-8">
                                <div>
                                    <h4 class="text-xs uppercase tracking-[0.2em] font-black text-slate-400 flex items-center gap-2 mb-6">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                        ตั้งค่าแบบทดสอบ
                                    </h4>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm">
                                            <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">เวลา (นาที)</label>
                                            <input type="number" name="time_limit" value="60" class="w-full border-none p-0 text-2xl font-black text-indigo-600 focus:ring-0 bg-transparent">
                                        </div>
                                        <div class="bg-white p-5 rounded-3xl border border-slate-200/60 shadow-sm">
                                            <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">เกณฑ์ผ่าน (%)</label>
                                            <input type="number" name="pass_percentage" value="50" min="0" max="100" class="w-full border-none p-0 text-2xl font-black text-emerald-600 focus:ring-0 bg-transparent">
                                        </div>
                                    </div>

                                    <hr class="border-slate-200/60 mb-8">

                                    <h4 class="text-[11px] uppercase tracking-[0.2em] font-black text-slate-500 mb-5">ตั้งค่าการระบุตัวตน</h4>
                                    <div class="space-y-4">
                                        <label class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200/60 cursor-pointer hover:border-indigo-300 transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-black text-slate-700">บังคับ GPS</span>
                                                    <span class="text-[10px] text-slate-400">ระบุตำแหน่งผู้สอบ</span>
                                                </div>
                                            </div>
                                            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                                                <input type="checkbox" name="require_location" value="1" class="peer opacity-0 w-0 h-0">
                                                <span class="absolute inset-0 cursor-pointer bg-slate-200 rounded-full transition-colors duration-200 peer-checked:bg-indigo-600"></span>
                                                <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 peer-checked:translate-x-4 shadow-sm"></span>
                                            </div>
                                        </label>

                                        <label class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200/60 cursor-pointer hover:border-emerald-300 transition-all group">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-black text-slate-700">สุ่มถ่ายรูป</span>
                                                    <span class="text-[10px] text-slate-400">บันทึกใบหน้าอัตโนมัติ</span>
                                                </div>
                                            </div>
                                            <div class="relative inline-block w-10 h-6 transition duration-200 ease-in-out">
                                                <input type="checkbox" name="require_snapshot" value="1" class="peer opacity-0 w-0 h-0">
                                                <span class="absolute inset-0 cursor-pointer bg-slate-200 rounded-full transition-colors duration-200 peer-checked:bg-emerald-600"></span>
                                                <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform duration-200 peer-checked:translate-x-4 shadow-sm"></span>
                                            </div>
                                        </label>
                                        <div class="space-y-4">
                                            <h4 class="text-[11px] uppercase tracking-[0.2em] font-black text-slate-500 mb-5">ตั้งค่าเกียรติบัตร</h4>
                                            
                                            <div class="relative group/cert space-y-3">
                                                <input type="file" id="quiz_certificate_input" accept="image/*" class="hidden">
                                                <input type="hidden" name="quiz_certificate_base64" id="quiz_certificate_base64">
                                                <input type="hidden" name="certificate_config" id="certificate_config">
                                                <!-- Standard file input for signature inside form submission -->
                                                <input type="file" name="quiz_signature" id="quiz_signature_hidden_file" class="hidden" accept="image/png">
                                                
                                                <div id="cert-preview-container" class="hidden relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-950 flex flex-col gap-3">
                                                    <div class="relative w-full rounded-xl overflow-hidden bg-slate-900 border border-white/5 flex items-center justify-center">
                                                        <img id="cert-preview-img" src="#" alt="Certificate Preview" class="w-full h-auto object-contain max-h-[150px]">
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <button type="button" onclick="openCertDesigner()" class="flex-1 py-2 px-3.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-[11.5px] font-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-1.5 focus:outline-none">
                                                            🎨 จัดการตำแหน่ง / ลายเซ็น
                                                        </button>
                                                        <button type="button" id="btn-remove-cert" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-lg focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <label id="cert-upload-label" for="quiz_certificate_input" class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 cursor-pointer hover:border-emerald-300 transition-all group">
                                                    <div class="flex items-center gap-3">
                                                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="block text-sm font-black text-slate-700 dark:text-white">อัปโหลดภาพพื้นหลังเกียรติบัตร</span>
                                                            <span class="text-[10px] text-slate-400">อัปโหลดภาพพื้นหลังเปล่าๆ แล้วจัดตำแหน่งองค์ประกอบ</span>
                                                        </div>
                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </label>
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
                        <div class="h-14 w-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black shadow-xl">
                            <span class="text-2xl">#</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">คลังข้อสอบของคุณ</h3>
                            <p class="text-xs text-indigo-500 font-bold uppercase tracking-widest">Question Bank</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <button type="button" onclick="document.getElementById('excel_import').click()" 
                            class="flex-1 md:flex-none px-6 py-3.5 bg-white border-2 border-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:border-emerald-500 transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Import Excel
                        </button>
                        <button type="button" onclick="addQuestion()" 
                            class="flex-1 md:flex-none px-8 py-3.5 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-black transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            เพิ่มคำถาม
                        </button>
                    </div>
                </div>
            </div>

            <div id="questions-container" class="space-y-8 min-h-[100px]"></div>

            <div class="pt-10">
                <button type="submit" class="group relative w-full overflow-hidden bg-indigo-600 p-[2px] rounded-[2.5rem] transition-all hover:scale-[1.01] active:scale-[0.98] shadow-xl shadow-indigo-100">
                    <div class="bg-indigo-600 group-hover:bg-indigo-700 px-10 py-7 rounded-[2.4rem] flex items-center justify-center gap-5 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-2xl font-black text-white tracking-wider">ยืนยันและบันทึกแบบทดสอบ</span>
                    </div>
                </button>
            </div>
        </form>
    </div>

@include('teachers.partials.quiz-scripts')

<script>
    const gradeSelect = document.getElementById('grade_level');
    const groupDisplay = document.getElementById('subject_group_display');
    const groupHidden = document.getElementById('subject_group');
    const groupMap = { 'ทร': 'ทักษะการเรียนรู้', 'พว': 'ความรู้พื้นฐาน', 'อช': 'การประกอบอาชีพ', 'ทช': 'ทักษะการดำเนินชีวิต', 'สค': 'การพัฒนาสังคม', 'คค': 'ความรู้พื้นฐาน' };

    // 1. ตั้งค่า Tom Select แบบระมัดระวัง
    const tsSubject = new TomSelect("#subject_select", {
        create: false,
        maxItems: 1,
        allowEmptyOption: true,
        valueField: 'value',
        labelField: 'text',
        searchField: ['text'],
        onChange: function(value) {
            if (value) {
                const prefix = value.substring(0, 2);
                const groupName = groupMap[prefix] || 'ไม่ระบุกลุ่มสาระ';
                groupDisplay.value = groupName;
                groupHidden.value = groupName;
            } else {
                groupDisplay.value = '';
                groupHidden.value = '';
            }
        }
    });

    tsSubject.disable();

// 2. ปรับปรุง Logic การเปลี่ยนระดับชั้น
    gradeSelect.addEventListener('change', function() {
        const grade = this.value;
        
        tsSubject.clear();
        tsSubject.clearOptions();
        tsSubject.disable();
        
        // หากไม่เลือกอะไรเลย
        if (!grade && grade !== "0") {
            tsSubject.settings.placeholder = "-- กรุณาเลือกระดับชั้นก่อน --";
            tsSubject.inputState();
            return;
        }

        // --- เพิ่มเงื่อนไขสำหรับ grade 0 (ทุกระดับ) ---
        if (grade === "0") {
            const defaultOption = {
                value: 'กก00000',
                text: 'กก00000 รายวิชาทั่วไป/ไม่ระบุ'
            };
            tsSubject.addOptions([defaultOption]);
            tsSubject.setValue('กก00000');
            tsSubject.enable(); // เปิดให้เลือกได้ หรือจะทิ้งไว้แบบนี้ก็ได้
            tsSubject.settings.placeholder = "รหัสวิชาถูกกำหนดอัตโนมัติ";
            tsSubject.inputState();
            return; // จบการทำงาน ไม่ต้องไป fetch API
        }
        // -------------------------------------------

        // กรณีเลือกเกรดอื่นๆ ให้ไป Fetch ข้อมูลตามปกติ
        tsSubject.settings.placeholder = "กำลังโหลดข้อมูล...";
        tsSubject.inputState();

        fetch("{{ route('api.subjects') }}?grade=" + grade)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const options = data.map(item => ({
                        value: item.SUB_CODE,
                        text: item.SUB_CODE + " " + item.SUB_NAME
                    }));
                    
                    tsSubject.addOptions(options);
                    tsSubject.enable();
                    tsSubject.settings.placeholder = "ค้นหาชื่อวิชาหรือรหัสวิชา...";
                } else {
                    tsSubject.settings.placeholder = "ไม่พบรายวิชาในระดับนี้";
                }
                tsSubject.inputState(); 
            })
            .catch(err => {
                console.error("Fetch Error:", err);
                tsSubject.settings.placeholder = "โหลดข้อมูลล้มเหลว";
                tsSubject.inputState();
            });
    });
</script>

<script>
    let cropper;
    const coverInput = document.getElementById('quiz_cover_input');
    const imageToCrop = document.getElementById('image-to-crop');
    const previewWrapper = document.getElementById('preview-wrapper');
    const placeholder = document.getElementById('upload-placeholder');
    const cropControls = document.getElementById('crop-controls');
    const base64Input = document.getElementById('quiz_cover_base64');

    // เมื่อเลือกไฟล์ภาพ
    coverInput.addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imageToCrop.src = event.target.result;
                previewWrapper.classList.remove('hidden');
                placeholder.classList.add('hidden');
                cropControls.classList.remove('hidden');

                if (cropper) cropper.destroy();

                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 16 / 9, // บังคับสัดส่วนสำหรับแสดงบน Card
                    viewMode: 2,
                    background: false,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // ปุ่มกดยืนยันการตัดรูป
    document.getElementById('btn-crop').addEventListener('click', function() {
        if (!cropper) return;
        
        // รับข้อมูลภาพที่ตัดแล้ว (กำหนดขนาดเพื่อความเร็วในการอัปโหลด)
        const canvas = cropper.getCroppedCanvas({
            width: 1280,
            height: 720
        });

        // เก็บข้อมูลในรูปแบบ Base64 ลง Hidden Input
        base64Input.value = canvas.toDataURL('image/jpeg', 0.8);
        
        // ล็อก Cropper (Optional: ทำให้ดูเหมือนว่าบันทึกแล้ว)
        alert('ภาพปกได้รับการปรับแต่งแล้ว พร้อมบันทึก!');
    });

    // ปุ่มรีเซ็ต (ยกเลิกการเลือกภาพ)
    document.getElementById('btn-reset').addEventListener('click', function(e) {
        e.preventDefault();
        coverInput.value = '';
        base64Input.value = '';
        previewWrapper.classList.add('hidden');
        placeholder.classList.remove('hidden');
        cropControls.classList.add('hidden');
        if (cropper) cropper.destroy();
    });

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
                        <input type="file" id="designer-signature-input" accept="image/png" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-rose-50 file:text-rose-700 dark:file:bg-rose-950/20 dark:file:text-rose-400 file:cursor-pointer">
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
    const certInput = document.getElementById('quiz_certificate_input');
    const certPreviewContainer = document.getElementById('cert-preview-container');
    const certPreviewImg = document.getElementById('cert-preview-img');
    const certUploadLabel = document.getElementById('cert-upload-label');
    const btnRemoveCert = document.getElementById('btn-remove-cert');
    const certBase64Input = document.getElementById('quiz_certificate_base64');
    const certConfigInput = document.getElementById('certificate_config');

    // Default configuration matching the classic certificate design coordinates
    let certificateConfig = {
        student_name: { x: 25, y: 46.6, size: 44, color: '#1e293b', align: 'center', visible: true },
        quiz_title: { x: 25, y: 65.5, size: 34, color: '#1e3a8a', align: 'center', visible: true },
        quiz_score: { x: 12.4, y: 94.4, size: 48, color: '#64748b', align: 'left', visible: true },
        date: { x: 25, y: 73.0, size: 24, color: '#64748b', align: 'center', visible: true },
        signature: { x: 75.0, y: 84.3, width: 200, height: 80, visible: true },
        signer_name: { x: 75.0, y: 96.3, size: 22, color: '#334155', align: 'center', visible: true, text: 'ผู้บริหารสถานศึกษา' },
        signer_title: { x: 75.0, y: 98.0, size: 18, color: '#64748b', align: 'center', visible: true, text: 'ผู้ช่วยผู้ตรวจการศึกษา' }
    };

    // เมื่อเลือกไฟล์เกียรติบัตร
    certInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('ไฟล์เกียรติบัตรใหญ่เกินไป (ไม่ควรเกิน 2MB)');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const base64String = event.target.result;
                certPreviewImg.src = base64String;
                certPreviewContainer.classList.remove('hidden');
                certUploadLabel.classList.add('hidden');
                certBase64Input.value = base64String;
                
                // Open Designer immediately to let them position elements
                setTimeout(openCertDesigner, 300);
            }
            reader.readAsDataURL(file);
        }
    });

    // เมื่อกดปุ่มลบรูปเกียรติบัตร
    btnRemoveCert.addEventListener('click', function(e) {
        e.preventDefault();
        certInput.value = ''; 
        certBase64Input.value = '';
        certConfigInput.value = '';
        certPreviewImg.src = '#';
        certPreviewContainer.classList.add('hidden');
        certUploadLabel.classList.remove('hidden');
    });

    // --- Drag and Drop Designer Functionalities ---
    const designerModal = document.getElementById('cert-designer-modal');
    const designerBg = document.getElementById('cert-designer-bg');
    const workspace = document.getElementById('cert-workspace');

    window.openCertDesigner = () => {
        if (!certBase64Input.value) {
            alert('กรุณาอัปโหลดภาพพื้นหลังเกียรติบัตรก่อน!');
            return;
        }
        designerBg.src = certBase64Input.value;
        designerModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Sync and apply elements style properties
        setTimeout(applyConfigToWorkspace, 100);
    };

    window.closeCertDesigner = () => {
        designerModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    // Apply values to HTML layout elements
    function applyConfigToWorkspace() {
        const parentRect = workspace.getBoundingClientRect();
        
        Object.keys(certificateConfig).forEach(key => {
            const cfg = certificateConfig[key];
            const dragId = `drag-${key.replace('_', '-')}`;
            const el = document.getElementById(dragId);
            if (!el) return;

            // Coordinates translation to pixels relative to parent
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

        // Initialize active control tab
        selectDesignerElement();
    }

    // Designer Controls Sync
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

        // Custom text controls
        if (cfg.text !== undefined) {
            textCustomGroup.classList.remove('hidden');
            textInput.value = cfg.text;
        } else {
            textCustomGroup.classList.add('hidden');
        }

        // Font style controls
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

    // Signature upload file selector sync inside designer modal
    const sigDesignerInput = document.getElementById('designer-signature-input');
    const sigHiddenFileInput = document.getElementById('quiz_signature_hidden_file');
    const sigPreviewImg = document.getElementById('designer-signature-img');
    const sigPlaceholder = document.getElementById('designer-signature-placeholder');

    sigDesignerInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.type !== 'image/png') {
                alert('รูปลอยเซ็นต้องเป็นภาพแบบโปร่งแสง (.PNG) เท่านั้น!');
                this.value = '';
                return;
            }

            // Sync with actual form file input
            sigHiddenFileInput.files = this.files;

            const reader = new FileReader();
            reader.onload = function(event) {
                sigPreviewImg.src = event.target.result;
                sigPreviewImg.classList.remove('hidden');
                sigPlaceholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // --- Interactive Mouse & Touch Drag Handler ---
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
        
        // Select matching dropdown option on drag
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

    // Save configurations
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

            // Save X, Y as percentage coordinates (e.g. 50%)
            cfg.x = Math.round((left / parentRect.width) * 1000) / 10;
            cfg.y = Math.round((top / parentRect.height) * 1000) / 10;
        });

        // Set hidden input value
        certConfigInput.value = JSON.stringify(certificateConfig);
        closeCertDesigner();
    };
</script>
</x-teachers-layout>