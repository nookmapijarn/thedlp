<x-teachers-layout>
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <nav class="flex text-gray-500 text-sm mb-2" aria-label="Breadcrumb">
                    <ol class="list-none p-0 inline-flex">
                        <li class="flex items-center">
                            <a href="{{ route('courses.manage') }}" class="hover:text-blue-600">จัดการคอร์ส</a>
                            <i data-lucide="chevron-right" class="w-3 h-3 mx-2"></i>
                        </li>
                        <li class="flex items-center">
                            <a href="{{ route('courses.manage_modules', $module->course_id) }}" class="hover:text-blue-600">จัดการโมดูล</a>
                            <i data-lucide="chevron-right" class="w-3 h-3 mx-2"></i>
                        </li>
                        <li class="text-gray-800 font-bold text-blue-600">เพิ่มบทเรียนใหม่</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-gray-800">เพิ่มบทเรียนใหม่</h1>
                <p class="text-gray-500">ในโมดูล: <span class="font-semibold text-gray-700">{{ $module->title }}</span></p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded shadow-sm">
                    <div class="flex items-center mb-2">
                        <i data-lucide="alert-circle" class="text-red-500 w-5 h-5 mr-2"></i>
                        <span class="font-bold text-red-800">เกิดข้อผิดพลาดในการบันทึก!</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <form action="{{ route('modules.store_lesson', $module->id) }}" method="POST" class="p-6 md:p-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">ชื่อบทเรียน <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                class="w-full px-4 py-2.5 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                                required placeholder="เช่น พื้นฐานการติดตั้งซอฟต์แวร์">
                        </div>

                        <div>
                            <label for="video_url" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i data-lucide="youtube" class="w-4 h-4 mr-1 text-red-500"></i> URL วิดีโอ (YouTube/Vimeo)
                            </label>
                            <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                                placeholder="https://www.youtube.com/watch?v=...">
                        </div>

                        <div>
                            <label for="quiz_id" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                <i data-lucide="help-circle" class="w-4 h-4 mr-1 text-blue-500"></i> เชื่อมต่อแบบทดสอบ
                            </label>
                            <select id="quiz_id" name="quiz_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white transition">
                                <option value="">-- ไม่ผูกแบบทดสอบ --</option>
                                @foreach ($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}" {{ old('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">เนื้อหาบทเรียน <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" rows="10" 
                            class="w-full px-4 py-3 border @error('content') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                            required placeholder="ใส่เนื้อหา รายละเอียดบทเรียน หรือคำอธิบายเพิ่มเติมที่นี่...">{{ old('content') }}</textarea>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-4 border-t pt-6">
                        <a href="{{ route('courses.manage_modules', $module->course_id) }}" 
                            class="w-full md:w-auto px-6 py-2.5 text-center text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                            ยกเลิก
                        </a>
                        <button type="submit" 
                            class="w-full md:w-auto px-10 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-300 font-bold shadow-md flex items-center justify-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i> บันทึกบทเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</x-teachers-layout>