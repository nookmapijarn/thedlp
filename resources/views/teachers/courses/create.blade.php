<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">สร้างหลักสูตรใหม่</h1>

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
                {{-- เพิ่ม enctype="multipart/form-data" --}}
                <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-semibold mb-2">ชื่อหลักสูตร:</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    {{-- เพิ่ม input สำหรับรูปภาพ --}}
                    <div class="mb-4">
                        <label for="cover_image" class="block text-gray-700 font-semibold mb-2">รูปภาพปก:</label>
                        <input type="file" id="cover_image" name="cover_image" accept="image/*" class="w-full text-gray-700 py-2">
                        <p class="text-sm text-gray-500 mt-1">ไฟล์ JPEG, PNG, JPG หรือ GIF (สูงสุด 2MB)</p>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-semibold mb-2">คำอธิบาย:</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="price" class="block text-gray-700 font-semibold mb-2">ราคา (บาท):</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                            บันทึกหลักสูตร
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-teachers-layout>