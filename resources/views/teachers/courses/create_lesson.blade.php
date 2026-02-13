<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">เพิ่มบทเรียนในโมดูล: <br class="md:hidden">{{ $module->title }}</h1>

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
                <form action="{{ route('modules.store_lesson', $module->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-semibold mb-2">ชื่อบทเรียน:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 font-semibold mb-2">เนื้อหาบทเรียน:</label>
                        <textarea id="content" name="content" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('content') }}</textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="video_url" class="block text-gray-700 font-semibold mb-2">URL วิดีโอ (ไม่บังคับ):</label>
                        <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-6">
                        <label for="quiz_id" class="block text-gray-700 font-semibold mb-2">ผูกกับแบบทดสอบ:</label>
                        <select id="quiz_id" name="quiz_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- ไม่ผูกแบบทดสอบ --</option>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}" {{ old('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                    {{ $quiz->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                            บันทึกบทเรียน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-teachers-layout>