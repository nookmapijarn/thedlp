<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">แดชบอร์ดของฉัน</h1>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-700">หลักสูตรที่ฉันสอน</h2>
                <a href="{{ route('courses.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out">
                    สร้างหลักสูตรใหม่
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($courses->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-500 text-lg">คุณยังไม่มีหลักสูตรที่สร้างไว้</p>
                    <p class="mt-2 text-gray-500">คลิกปุ่ม **"สร้างหลักสูตรใหม่"** เพื่อเริ่มต้น</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($courses as $course)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105">
                            {{-- แสดงรูปภาพปก --}}
                            @if ($course->cover_image)
                                <img src="{{$course->cover_image}}" alt="Course Cover" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                    ไม่มีรูปภาพ
                                </div>
                            @endif
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                                <p class="text-gray-600 mb-4 h-16 overflow-hidden">{{$course->description}}</p>
                                <div class="flex flex-col space-y-3">
                                    <a href="{{ route('courses.manage_modules', $course->id) }}" class="inline-block text-center bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition duration-300">
                                        จัดการบทเรียน
                                    </a>
                                    <div class="flex justify-between space-x-2">
                                        <a href="{{ route('courses.edit', $course->id) }}" class="inline-block text-center flex-1 bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 transition duration-300">
                                            แก้ไข
                                        </a>
                                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 transition duration-300" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหลักสูตรนี้?');">
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-teachers-layout>