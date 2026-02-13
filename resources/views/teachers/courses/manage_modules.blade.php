<x-teachers-layout>
    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">จัดการโมดูลและบทเรียน: <br class="md:hidden">{{ $course->title }}</h1>
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">เพิ่มโมดูลใหม่</h2>
                <form action="{{ route('courses.store_module', $course->id) }}" method="POST">
                    @csrf
                    <div class="flex flex-col md:flex-row md:space-x-4">
                        <input type="text" name="title" placeholder="ชื่อโมดูล" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4 md:mb-0">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                            บันทึกโมดูล
                        </button>
                    </div>
                </form>
            </div>

            @if ($modules->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-500 text-lg">คอร์สนี้ยังไม่มีโมดูล</p>
                    <p class="mt-2 text-gray-500">ใช้ฟอร์มด้านบนเพื่อเพิ่มโมดูลแรก</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($modules as $module)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $module->order_number }}. {{ $module->title }}
                                </h3>
                                <a href="{{ route('modules.create_lesson', $module->id) }}" class="px-3 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-300">
                                    + เพิ่มบทเรียน
                                </a>
                            </div>

                            @if ($module->lessons->isEmpty())
                                <p class="text-gray-500 italic ml-4">ยังไม่มีบทเรียนในโมดูลนี้</p>
                            @else
                                <ul class="list-disc list-inside space-y-2 text-gray-700">
                                    @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                        <li class="flex justify-between items-center">
                                            <span>
                                                {{ $lesson->order_number }}. {{ $lesson->title }}
                                                @if ($lesson->quiz)
                                                    <span class="ml-2 text-sm text-blue-500 font-semibold">(มีแบบทดสอบ)</span>
                                                @endif
                                            </span>
                                            </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-teachers-layout>