<x-admin-layout>
    <div class="p-4 sm:ml-64">
        <!-- Main Panel Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-xl dark:bg-gray-800 dark:border-gray-700 mt-14">
            
            <!-- Header Block -->
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl dark:bg-blue-900/30 dark:text-blue-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">นำเข้าข้อมูลทะเบียนและผลการเรียน</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">อัปโหลดไฟล์สำรองข้อมูล (ZIP) จากระบบ ITW51 เพื่อนำเข้าข้อมูลรายวิชา ผลการเรียน และตารางสอน</p>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('zip.upload') }}" method="POST" class="mx-auto mt-4" enctype="multipart/form-data">   
                @csrf
                <x-input-error :messages="$errors->get('zip_file')" class="mb-4" />
                
                <div class="mb-6">
                    <label for="zip_file" class="flex flex-col items-center justify-center w-full h-44 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-55 hover:bg-gray-100/70 dark:hover:bg-gray-700/30 dark:bg-gray-800 dark:border-gray-600 dark:hover:border-gray-500 transition-all group relative">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                            <!-- Default Cloud Upload Icon -->
                            <svg class="upload-default-icon w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 dark:text-gray-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <!-- Success Checkmark Icon -->
                            <svg class="upload-success-icon w-10 h-10 mb-3 text-green-500 dark:text-green-400 transition-colors hidden animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="upload-instruction-text mb-1 text-sm text-gray-700 dark:text-gray-300 font-semibold">คลิกเพื่อเลือกไฟล์สำรองข้อมูล หรือลากไฟล์มาวางที่นี่</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">รองรับไฟล์ ZIP (ขนาดสูงสุด 60MB)</p>
                            <div id="file-name-display" class="mt-2.5 text-xs text-blue-600 dark:text-blue-400 font-bold hidden bg-blue-50 dark:bg-blue-900/20 px-3 py-1.5 rounded-lg border border-blue-100/50"></div>
                        </div>
                        <input type="file" name="zip_file" id="zip_file" required class="hidden" onchange="displayFileName(this)">
                    </label>
                    
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-xl text-sm px-6 py-3 transition-all">
                            เริ่มกระบวนการนำเข้าข้อมูล (Start Import)
                        </button>
                    </div>
                </div>
            </form>

            <!-- Steps Instructions Grid -->
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 dark:bg-gray-800/40 dark:border-gray-700/50 mt-6">
                <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    คำอธิบายขั้นตอนการนำเข้าข้อมูล (Import Instructions)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs flex-shrink-0 dark:bg-blue-900/30 dark:text-blue-400">1</div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">เตรียมไฟล์สำรองข้อมูล</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">เตรียมไฟล์ ZIP เช่น ITW51DATA.zip (หากมีขนาดเกิน 50MB แนะนำให้นำเข้าแยกทีละระดับชั้น)</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs flex-shrink-0 dark:bg-blue-900/30 dark:text-blue-400">2</div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">เลือกไฟล์และอัปโหลด</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">เลือกไฟล์หรือลากไฟล์สำรองข้อมูลมาวางในช่องรับไฟล์ด้านบน จากนั้นกดปุ่มเริ่มการนำเข้าข้อมูล</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs flex-shrink-0 dark:bg-blue-900/30 dark:text-blue-400">3</div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">วิเคราะห์ผลลัพธ์แบบละเอียด</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">ระบบจะเขียนทับเฉพาะข้อมูลที่มีการเปลี่ยนแปลง และแสดงตารางสรุปผลลัพธ์พร้อมแจ้งเตือนแถวที่มีปัญหาทันที</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Database Tools -->
            <div class="mt-6 p-5 bg-red-50/20 border border-red-200/30 rounded-2xl dark:bg-red-950/10 dark:border-red-900/20">
                <h3 class="text-xs font-bold text-red-800 dark:text-red-300 flex items-center mb-3 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    เครื่องมือจัดการฐานข้อมูล (Database Administrator Panel)
                </h3>
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('clearTable') }}" method="POST" onsubmit="return confirm('คำเตือน! คุณกำลังจะล้างข้อมูลทุกตารางทั้งหมดในฐานข้อมูล ยืนยันการดำเนินการ?')">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:text-white border border-red-200 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-xl px-4 py-2.5 dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-900 dark:hover:text-white transition-all">
                            ล้างตารางข้อมูลทั้งหมด (Truncate Tables)
                        </button>
                    </form>

                    <form action="{{ route('clearDateModifiled') }}" method="POST" onsubmit="return confirm('ยืนยันการล้างประวัติเวลาการนำเข้าล่าสุด? การอัปโหลดครั้งถัดไปจะนำเข้าข้อมูลใหม่ทั้งหมด')">
                        @csrf
                        <button type="submit" class="text-xs text-red-600 hover:text-white border border-red-200 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold rounded-xl px-4 py-2.5 dark:border-red-900/50 dark:text-red-400 dark:hover:bg-red-900 dark:hover:text-white transition-all">
                            ล้างค่าเวลาตรวจสอบไฟล์ล่าสุด (Reset Upload Stamps)
                        </button>
                    </form>

                    <form action="{{ route('fixStorageLink') }}" method="POST" onsubmit="return confirm('ยืนยันการเชื่อมโยงระบบจัดเก็บไฟล์ใหม่? เครื่องมือนี้จะลบสัญลักษณ์ลิงก์เดิมที่เสียหายบนโฮสต์และสร้างใหม่เพื่อให้แสดงผลไฟล์รูปภาพและคลิปวิดีโอได้ถูกต้อง')">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-white border border-blue-200 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-xl px-4 py-2.5 dark:border-blue-900/50 dark:text-blue-400 dark:hover:bg-blue-900 dark:hover:text-white transition-all">
                            เชื่อมโยงระบบไฟล์ใหม่ (Fix Storage Symlink)
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Alert & Report Output Container -->
        <div class="mt-6">
            @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded-xl mb-4 shadow-md font-medium text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-xl mb-4 shadow-md font-medium text-sm">
                    {{ session('success') }}
                </div>
            @endif
            
            <div id="alert-container" class="hidden rounded-2xl text-left"></div>
        </div>

        <!-- Upload Status History Table Card -->
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-xl dark:bg-gray-800 dark:border-gray-700 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ประวัติเวลาการนำเข้าข้อมูลตาราง (Database Upload Status)
                </h2>
                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-gray-700 dark:text-gray-300">
                    ทั้งหมด {{ count($lastmodified) }} ตาราง
                </span>
            </div>
            
            <div class="relative overflow-x-auto border border-gray-100 rounded-xl dark:border-gray-700 shadow-sm">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-center">ลำดับ</th>
                            <th scope="col" class="px-6 py-4">ตารางข้อมูล</th>
                            <th scope="col" class="px-6 py-4">เวลาแก้ไขไฟล์ล่าสุด (Timestamp)</th>
                            <th scope="col" class="px-6 py-4">เวลาอัปโหลดข้อมูล (Uploaded)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($lastmodified as $lastmodi)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 text-xs font-bold px-2.5 py-1 rounded-lg">
                                    {{ $lastmodi->FILE_NAME }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-600 dark:text-gray-400">{{ $lastmodi->LAST_MODIFIED }}</td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-600 dark:text-gray-400">{{ $lastmodi->UPLOADED }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500 italic bg-gray-50/30 dark:bg-gray-900/10">
                                ยังไม่มีประวัติการนำเข้าไฟล์สำรองข้อมูลในตารางระบบ
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<!-- Modal -->
<div id="modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl flex flex-col items-center justify-center max-w-sm w-full mx-4 border border-gray-100 dark:border-gray-700">
        <div role="status" class="mb-4">
            <svg aria-hidden="true" class="w-10 h-10 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
            </svg>
            <span class="sr-only">กำลังโหลด...</span>
        </div>
        <span class="text-sm font-bold text-gray-800 dark:text-gray-200 text-center animate-pulse">กำลังดำเนินการนำเข้าข้อมูล...<br><span class="text-xs font-normal text-gray-500 dark:text-gray-400 mt-1 block">กรุณาอย่าปิดหรือรีเฟรชหน้าจอนี้ในขณะทำงาน</span></span>
    </div>
</div>

<script>
    function displayFileName(input) {
        const display = document.getElementById('file-name-display');
        const dropzone = input.closest('label');
        const defaultIcon = dropzone.querySelector('.upload-default-icon');
        const successIcon = dropzone.querySelector('.upload-success-icon');
        const instructionText = dropzone.querySelector('.upload-instruction-text');
        
        if (input.files && input.files[0]) {
            display.innerText = "ไฟล์ที่เลือก: " + input.files[0].name + " (" + (input.files[0].size / 1024 / 1024).toFixed(2) + " MB)";
            display.classList.remove('hidden');
            
            // Apply green success states
            dropzone.classList.remove('border-gray-300', 'bg-gray-55', 'hover:bg-gray-100/70');
            dropzone.classList.add('border-green-400', 'bg-green-50/20', 'dark:bg-green-950/10', 'dark:border-green-900');
            
            if (defaultIcon) defaultIcon.classList.add('hidden');
            if (successIcon) successIcon.classList.remove('hidden');
            if (instructionText) {
                instructionText.innerHTML = '<span class="text-green-700 dark:text-green-400 font-bold flex items-center justify-center"><svg class="w-4 h-4 mr-1.5 flex-shrink-0 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>ดึงข้อมูลไฟล์สำเร็จ: พร้อมนำเข้าข้อมูลแล้ว</span>';
            }
        } else {
            display.classList.add('hidden');
            
            // Restore default gray states
            dropzone.classList.add('border-gray-300', 'bg-gray-55', 'hover:bg-gray-100/70');
            dropzone.classList.remove('border-green-400', 'bg-green-50/20', 'dark:bg-green-950/10', 'dark:border-green-900');
            
            if (defaultIcon) defaultIcon.classList.remove('hidden');
            if (successIcon) successIcon.classList.add('hidden');
            if (instructionText) {
                instructionText.innerHTML = 'คลิกเพื่อเลือกไฟล์สำรองข้อมูล หรือลากไฟล์มาวางที่นี่';
            }
        }
    }

    function initForms() {
        console.log('DLP Debug: initForms called.');
        const modal = document.getElementById('modal');
        const alertContainer = document.getElementById('alert-container');
        const forms = document.querySelectorAll('form');
        console.log('DLP Debug: Forms found on page:', forms.length);

        forms.forEach((form, i) => {
            console.log(`DLP Debug: Binding submit listener to form #${i} (action: ${form.getAttribute('action')})`);
            form.addEventListener('submit', function (e) {
                console.log(`DLP Debug: Form submit intercepted for form (action: ${this.getAttribute('action')})`);
                e.preventDefault();
                modal.classList.remove('hidden');

                const formData = new FormData(form);
                const action = form.getAttribute('action');
                const method = form.getAttribute('method').toUpperCase();

                fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (window.hidePageLoader) window.hidePageLoader();
                    modal.classList.add('hidden');
                    alertContainer.classList.remove('hidden');

                    if (data.success && data.report && data.report.length > 0) {
                        let reportHtml = `
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg dark:bg-gray-800 dark:border-gray-700 p-6 mt-4">
                                <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100 dark:border-gray-700">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        สรุปรายงานผลการนำเข้าข้อมูล (Import Summary)
                                    </h3>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                        สำเร็จ
                                    </span>
                                </div>
                                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        `;

                        data.report.forEach((item, index) => {
                            let statusBadge = '';
                            let statusClass = '';
                            if (item.status === 'imported') {
                                statusBadge = 'นำเข้าข้อมูลแล้ว';
                                statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                            } else if (item.status === 'unchanged') {
                                statusBadge = 'ไม่มีการเปลี่ยนแปลง';
                                statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                            } else if (item.status === 'missing') {
                                statusBadge = 'ไม่พบไฟล์';
                                statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                            } else {
                                statusBadge = 'เกิดข้อผิดพลาด';
                                statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                            }

                            let rowErrorsHtml = '';
                            if (item.errors && item.errors.length > 0) {
                                let grouped = {};
                                item.errors.forEach(err => {
                                    let rowNum = 'ไม่ทราบแถว';
                                    let reason = err;
                                    let match = err.match(/^แถวที่\s*(\d+)\s*:\s*(.*)$/i);
                                    if (match) {
                                        rowNum = parseInt(match[1]);
                                        reason = match[2];
                                        reason = reason.replace(/^ไม่สามารถนำเข้าได้\s*-\s*/, '');
                                    }
                                    if (!grouped[reason]) grouped[reason] = [];
                                    if (rowNum !== 'ไม่ทราบแถว') grouped[reason].push(rowNum);
                                });

                                let groupedHtml = '';
                                Object.keys(grouped).forEach(reason => {
                                    let rows = grouped[reason].sort((a, b) => a - b);
                                    let compactRows = [];
                                    let start = null;
                                    let prev = null;
                                    for (let idx = 0; idx < rows.length; idx++) {
                                        let r = rows[idx];
                                        if (start === null) { start = r; prev = r; }
                                        else if (r === prev + 1) { prev = r; }
                                        else {
                                            compactRows.push(start === prev ? start : `${start}-${prev}`);
                                            start = r; prev = r;
                                        }
                                    }
                                    if (start !== null) compactRows.push(start === prev ? start : `${start}-${prev}`);
                                    
                                    groupedHtml += `
                                        <div class="mb-2 last:mb-0 border-b border-red-100/30 last:border-b-0 pb-1.5 last:pb-0">
                                            <div class="font-semibold text-red-800 dark:text-red-300 flex items-center text-xs">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-2"></span>
                                                ${reason} (${rows.length} แถว)
                                            </div>
                                            <div class="pl-3.5 text-gray-600 dark:text-gray-400 font-mono text-[10px] break-all leading-normal mt-0.5">
                                                แถวที่: ${compactRows.join(', ')}
                                            </div>
                                        </div>
                                    `;
                                });

                                rowErrorsHtml = `
                                    <div class="mt-2.5">
                                        <button type="button" onclick="toggleErrorDetails(${index})" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-bold flex items-center focus:outline-none bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:hover:bg-red-900/30 px-3 py-1.5 rounded-xl border border-red-200/50">
                                            <svg id="arrow-${index}" class="w-3 h-3 mr-1.5 transform transition-transform flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            แสดงรายละเอียดปัญหา (${item.errors.length} รายการ)
                                        </button>
                                        <div id="details-${index}" class="hidden mt-2 p-3.5 bg-red-50 border border-red-100 rounded-lg text-xs font-mono text-red-700 space-y-1 max-h-40 overflow-y-auto dark:bg-red-950 dark:border-red-900 dark:text-red-300">
                                            ${groupedHtml}
                                        </div>
                                    </div>
                                `;
                            } else if (item.status === 'imported' || item.status === 'unchanged') {
                                rowErrorsHtml = `
                                    <div class="mt-1.5 text-[11px] text-green-600 dark:text-green-400 flex items-center font-medium">
                                        <svg class="w-3 h-3 text-green-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        ผ่านการตรวจสอบ: ทุกแถวนำเข้าได้สมบูรณ์ (100% Success)
                                    </div>
                                `;
                            }

                            reportHtml += `
                                <div class="p-3 bg-gray-100 rounded-xl dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-between flex-wrap gap-2">
                                        <div class="font-bold text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            ตาราง: ${item.file}
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full ${statusClass}">
                                            ${statusBadge}
                                        </span>
                                    </div>
                                    <div class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                        นำเข้าสำเร็จ: <span class="font-semibold text-gray-900 dark:text-white">${item.inserted.toLocaleString()} / ${item.total.toLocaleString()}</span> แถว
                                    </div>
                                    ${rowErrorsHtml}
                                </div>
                            `;
                        });

                        reportHtml += `
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="button" onclick="location.reload()" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-full text-sm px-6 py-2.5 text-center focus:ring-4 focus:ring-blue-300">
                                        ตกลง และ รีเฟรชหน้าเว็บ
                                    </button>
                                </div>
                            </div>
                        `;

                        alertContainer.innerHTML = reportHtml;
                    } else {
                        alertContainer.innerHTML = `<div class="bg-${data.success ? 'green' : 'red'}-200 text-gray-900 p-4 rounded-lg">${data.message}</div>`;
                        if (data.success) {
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    }
                })
                .catch(err => {
                    if (window.hidePageLoader) window.hidePageLoader();
                    modal.classList.add('hidden');
                    alertContainer.classList.remove('hidden');
                    alertContainer.innerHTML = `<div class="bg-red-200 text-gray-900 p-4 rounded-lg">เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์: ${err.message}</div>`;
                });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initForms);
    } else {
        initForms();
    }

    window.toggleErrorDetails = function(index) {
        const details = document.getElementById(`details-${index}`);
        const arrow = document.getElementById(`arrow-${index}`);
        if (details.classList.contains('hidden')) {
            details.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            details.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    };
</script>
</x-admin-layout>

