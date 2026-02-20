<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <nav class="flex text-gray-500 text-sm mb-2" aria-label="Breadcrumb">
                        <ol class="list-none p-0 inline-flex">
                            <li class="flex items-center">
                                <a href="{{ route('courses.manage') }}" class="hover:text-blue-600">จัดการคอร์ส</a>
                                <svg class="fill-current w-3 h-3 mx-3" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                            </li>
                            <li class="flex items-center">
                                <a href="{{ route('courses.manage_modules', $lesson->module->course_id) }}" class="hover:text-blue-600">จัดการโมดูล</a>
                                <svg class="fill-current w-3 h-3 mx-3" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                            </li>
                            <li class="text-gray-800 font-bold">แก้ไขบทเรียน</li>
                        </ol>
                    </nav>
                    <h1 class="text-2xl font-bold text-gray-800">แก้ไขบทเรียน: {{ $lesson->title }}</h1>
                    <p class="text-gray-500 text-sm">อยู่ในโมดูล: {{ $lesson->module->title }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">หัวข้อบทเรียน <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $lesson->title) }}" 
                                class="w-full px-4 py-2 border @error('title') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required placeholder="เช่น พื้นฐานการใช้งานเบื้องต้น">
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL วิดีโอ (YouTube/Vimeo)</label>
                            <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="https://www.youtube.com/watch?v=...">
                            <p class="text-gray-400 text-xs mt-1 italic">หากไม่มีให้เว้นว่างไว้</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">เชื่อมต่อแบบทดสอบ (Quiz)</label>
                            <select name="quiz_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="">--- ไม่ระบุ ---</option>
                                @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}" {{ (old('quiz_id', $lesson->quiz_id) == $quiz->id) ? 'selected' : '' }}>
                                        {{ $quiz->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">เนื้อหาบทเรียน <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="12" 
                            class="w-full px-4 py-3 border @error('content') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="พิมพ์เนื้อหาบทเรียนที่นี่... (รองรับ HTML เบื้องต้น)">{{ old('content', $lesson->content) }}</textarea>
                        @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-end space-y-3 md:space-y-0 md:space-x-4 border-t pt-6">
                        <a href="{{ route('courses.manage_modules', $lesson->module->course_id) }}" 
                            class="w-full md:w-auto px-6 py-2.5 text-center text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                            ยกเลิก
                        </a>
                        <button type="submit" 
                            class="w-full md:w-auto px-10 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition duration-200 font-bold shadow-md">
                            อัปเดตบทเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-teachers-layout>