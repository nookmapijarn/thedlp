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
                            เพิ่มข้อสอบใหม่
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
        
        // วิธีเปลี่ยน Placeholder ที่ถูกต้องสำหรับ Tom Select
        tsSubject.settings.placeholder = grade ? "กำลังโหลดข้อมูล..." : "-- กรุณาเลือกระดับชั้นก่อน --";
        tsSubject.inputState(); // สั่งให้ input อัปเดตสถานะ

        if (grade) {
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
        }
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
</x-teachers-layout>