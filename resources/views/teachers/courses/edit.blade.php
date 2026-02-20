<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">แก้ไขหลักสูตร: <br class="md:hidden">{{ $course->title }}</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">เกิดข้อผิดพลาด!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6">
                {{-- นำ enctype ออกได้เลยเพราะเราส่งผ่าน base64 --}}
                <form action="{{ route('courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-semibold mb-2">ชื่อหลักสูตร:</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- ส่วนจัดการรูปภาพปก --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">รูปภาพปก:</label>
                        
                        <div class="mb-3">
                            <div id="preview_container" class="{{ $course->cover_image ? '' : 'hidden' }}">
                                <p class="text-sm text-gray-500 mb-2">รูปภาพปัจจุบัน/ตัวอย่าง:</p>
                                <img id="image_preview" src="{{ $course->cover_image }}" alt="Course Cover" class="w-full md:w-1/2 rounded-lg shadow-sm border mb-2">
                            </div>
                            
                            @if (!$course->cover_image)
                                <p id="no_image_text" class="text-gray-500 italic mb-2">ยังไม่มีรูปภาพปก</p>
                            @endif
                        </div>

                        <input type="file" id="image_selector" accept="image/*" class="w-full text-gray-700 py-2">
                        
                        <input type="hidden" name="cover_image_base64" id="cover_image_base64">
                        
                        <p class="text-sm text-gray-500 mt-1">อัปโหลดไฟล์ใหม่เพื่อแทนที่ไฟล์เดิม (JPEG, PNG, JPG)</p>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-semibold mb-2">คำอธิบาย:</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-gray-700 font-semibold mb-2">ราคา (บาท):</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $course->price) }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('courses.manage') }}" class="px-6 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                            ยกเลิก
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript สำหรับจัดการ Preview และแปลง Base64 --}}
    <script>
        document.getElementById('image_selector').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const reader = new FileReader();

            if (file) {
                // ตรวจสอบขนาดไฟล์เบื้องต้น (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('ไฟล์มีขนาดใหญ่เกิน 2MB กรุณาเลือกไฟล์ใหม่');
                    e.target.value = '';
                    return;
                }

                reader.onloadend = function() {
                    const base64String = reader.result;
                    
                    // อัปเดตค่าใน Hidden Input
                    document.getElementById('cover_image_base64').value = base64String;
                    
                    // เปลี่ยนรูปภาพที่แสดงพรีวิว
                    const preview = document.getElementById('image_preview');
                    const container = document.getElementById('preview_container');
                    const noImageText = document.getElementById('no_image_text');
                    
                    preview.src = base64String;
                    container.classList.remove('hidden');
                    if(noImageText) noImageText.classList.add('hidden');
                }
                
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-teachers-layout>