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
        <form action="{{ route('ttest.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event)" class="max-w-7xl mx-auto pb-20 px-4 space-y-8">
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
                                        <option value="0" selected>ทุกระดับ</option>
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
                                        <div class="group space-y-2">
                                            <label class="flex items-center gap-2 text-sm font-black text-slate-700 dark:text-white mb-2 ml-1">การจัดการเกียรติบัตร</label>
                                            
                                            <!-- Hidden inputs and file selectors must remain in form -->
                                            <input type="file" id="cert_input" accept="image/*" class="hidden" onchange="previewCertImage(this)">
                                            <input type="hidden" name="quiz_certificate_base64" id="quiz_certificate_base64">
                                            <input type="hidden" name="certificate_config" id="certificate_config">
                                            <input type="file" name="quiz_signature" id="quiz_signature_hidden_file" class="hidden" accept="image/png">
                                            <input type="file" name="quiz_logo" id="quiz_logo_hidden_file" class="hidden" accept="image/*">
                                            <input type="hidden" name="quiz_signature_remove" id="quiz_signature_remove" value="false">
                                            <input type="hidden" name="quiz_logo_remove" id="quiz_logo_remove" value="false">

                                            <!-- Placeholder Upload Trigger Button -->
                                            <button type="button" id="cert_placeholder_btn" onclick="openCertConfigModal()" 
                                                class="w-full py-4 px-6 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-2xl font-black text-sm transition-all active:scale-[0.98] shadow-lg shadow-amber-100 dark:shadow-none flex items-center justify-center gap-2.5">
                                                <span>🎨 ออกแบบและตั้งค่าเกียรติบัตร</span>
                                            </button>

                                            <!-- High fidelity Canvas Preview Container -->
                                            <div id="cert_main_preview_container" class="hidden relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-955 flex flex-col gap-3">
                                                <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-955 border border-slate-200 dark:border-slate-800 flex items-center justify-center aspect-[1123/794]">
                                                    <canvas id="cert_main_preview_canvas" class="w-full h-auto max-h-[150px] object-contain"></canvas>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="button" onclick="openCertConfigModal()" class="flex-1 py-2 px-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[11.5px] font-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-1.5 focus:outline-none">
                                                        🎨 ออกแบบและตั้งค่าเกียรติบัตร
                                                    </button>
                                                    <button type="button" onclick="removeCertImage()" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-lg focus:outline-none flex items-center justify-center">
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
                            class="cursor-pointer w-full h-[220px] bg-white dark:bg-slate-900 border-4 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center text-slate-400 hover:text-amber-500 hover:border-amber-300 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-xs font-black">อัปโหลดพื้นหลังเกียรติบัตร</span>
                            <span class="text-[10px] text-slate-400 mt-1 font-bold">แนะนำไฟล์รูปภาพแนวนอน ขนาด A4</span>
                        </div>

                        <div id="cert_modal_preview_container" class="hidden relative w-full rounded-2xl border border-slate-200 dark:border-slate-800 p-4 bg-white dark:bg-slate-900 shadow-sm">
                            <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex items-center justify-center aspect-[1123/794]">
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
                                <input type="text" id="cert_prefix_text" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า" value="ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า">
                            </div>
                            <div class="p-3 bg-amber-50/50 dark:bg-amber-950/20 rounded-xl border border-amber-200/50 dark:border-amber-900/30 text-[10px] text-amber-700 dark:text-amber-400 font-bold">
                                🔒 ชื่อผู้เรียน — ดึงจากระบบอัตโนมัติ
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">คำอธิบาย <span class="text-slate-350 font-medium">(หลังชื่อผู้เรียน)</span></label>
                                <textarea id="cert_description" rows="2" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ได้ผ่านการทำแบบทดสอบ"></textarea>
                            </div>
                            <div class="p-3 bg-amber-50/50 dark:bg-amber-950/20 rounded-xl border border-amber-200/50 dark:border-amber-900/30 text-[10px] text-amber-700 dark:text-amber-400 font-bold">
                                🔒 ชื่อแบบทดสอบ / วันที่ — ดึงจากระบบอัตโนมัติ
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 mb-1.5 block">ข้อความอวยพร <span class="text-slate-350 font-medium">(ปิดท้าย)</span></label>
                                <input type="text" id="cert_blessing" class="w-full rounded-xl border-slate-200 bg-white dark:bg-slate-900 dark:border-slate-800 text-xs font-bold py-2.5 px-3 focus:ring-1 focus:ring-amber-500 focus:border-amber-500" placeholder="ขอให้ประสบแต่ความสุข..." value="ขอให้ประสบแต่ความสุข ความเจริญ และมีความก้าวหน้าต่อไป">
                            </div>
                        </div>

                        <!-- Logo & Signature Uploads -->
                        <div class="space-y-4 border-t border-slate-200 dark:border-slate-800 pt-5">
                            <h4 class="text-xs font-black uppercase tracking-[0.15em] text-slate-400">โลโก้ และ ลายเซ็น</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Logo -->
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                    <div class="w-12 h-12 rounded-lg border border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-center overflow-hidden bg-slate-50 dark:bg-slate-950 shrink-0">
                                        <img id="cert_logo_img" src="#" class="w-full h-full object-contain hidden">
                                        <span id="cert_logo_placeholder" class="text-[8px] text-slate-400 font-bold text-center">โลโก้</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-black text-slate-700 dark:text-white truncate">โลโก้หน่วยงาน</p>
                                        <p class="text-[8px] text-slate-400">ภาพสี่เหลี่ยมจัตุรัส</p>
                                    </div>
                                    <div class="flex gap-1 shrink-0">
                                        <button type="button" onclick="document.getElementById('quiz_logo_hidden_file').click()" class="text-[9px] px-2 py-1.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-black hover:bg-indigo-100/55 transition-colors">เลือก</button>
                                        <button type="button" onclick="removeLogo()" class="text-[9px] px-2 py-1.5 bg-rose-50 dark:bg-rose-950/30 text-rose-500 dark:text-rose-450 rounded-lg font-black hover:bg-rose-100/55 transition-colors">ลบ</button>
                                    </div>
                                </div>
                                <!-- Signature -->
                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                                    <div class="w-12 h-12 rounded-lg border border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-center overflow-hidden bg-slate-50 dark:bg-slate-950 shrink-0">
                                        <img id="cert_sig_img" src="#" class="w-full h-full object-contain hidden">
                                        <span id="cert_sig_placeholder" class="text-[8px] text-slate-400 font-bold text-center">ลายเซ็น</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-black text-slate-700 dark:text-white truncate">ลายเซ็น</p>
                                        <p class="text-[8px] text-slate-400">ภาพ PNG โปร่งแสง</p>
                                    </div>
                                    <div class="flex gap-1 shrink-0">
                                        <button type="button" onclick="document.getElementById('quiz_signature_hidden_file').click()" class="text-[9px] px-2 py-1.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg font-black hover:bg-indigo-100/55 transition-colors">เลือก</button>
                                        <button type="button" onclick="removeSignature()" class="text-[9px] px-2 py-1.5 bg-rose-50 dark:bg-rose-950/30 text-rose-500 dark:text-rose-450 rounded-lg font-black hover:bg-rose-100/55 transition-colors">ลบ</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Signer Info -->
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

    // Trigger initial load on page load
    if (gradeSelect) {
        gradeSelect.dispatchEvent(new Event('change'));
    }
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


<!-- Certificate Config Script (form-based, dynamic canvas preview with color pickers) -->
<script>
    const certInput = document.getElementById('cert_input');
    const certModalPreviewContainer = document.getElementById('cert_modal_preview_container');
    const certMainPreviewContainer = document.getElementById('cert_main_preview_container');
    const certPlaceholderBtn = document.getElementById('cert_placeholder_btn');
    const certPlaceholder = document.getElementById('cert_placeholder');
    const certBase64Input = document.getElementById('quiz_certificate_base64');
    const certConfigInput = document.getElementById('certificate_config');

    // Default values for new certificates
    document.getElementById('cert_prefix_text').value = document.getElementById('cert_prefix_text').value || 'ขอมอบวุฒิบัตรฉบับนี้ให้ไว้เพื่อแสดงว่า';
    document.getElementById('cert_blessing').value = document.getElementById('cert_blessing').value || 'ขอให้ประสบแต่ความสุข ความเจริญ และมีความก้าวหน้าต่อไป';

    // Cached images for live preview rendering
    let cachedBgImage = null;
    let cachedLogoImage = null;
    let cachedSigImage = null;

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
