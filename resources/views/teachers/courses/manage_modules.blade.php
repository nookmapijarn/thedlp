<x-teachers-layout>
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="p-4 mt-20">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">จัดการโมดูลและบทเรียน: <br class="md:hidden">{{ $course->title }}</h1>
                <a href="{{ route('courses.manage') }}" class="text-blue-600 hover:underline">← กลับไปที่แดชบอร์ด</a>
            </div>
            
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
                        <input type="text" name="title" placeholder="ชื่อโมดูล (เช่น บทที่ 1: พื้นฐาน...)" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4 md:mb-0">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                            บันทึกโมดูล
                        </button>
                    </div>
                </form>
            </div>

            @if ($modules->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow-md text-center">
                    <p class="text-gray-500 text-lg">คอร์สนี้ยังไม่มีโมดูล</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($modules as $module)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                            <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                                <div class="flex-1 mr-4">
                                    <div id="module-title-container-{{ $module->id }}" class="flex items-center space-x-2">
                                        <h3 class="text-lg font-bold text-gray-800">
                                            {{ $module->order_number }}. {{ $module->title }}
                                        </h3>
                                        <button onclick="toggleEditModule({{ $module->id }})" class="text-gray-400 hover:text-blue-500">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                    </div>

                                    <form id="module-edit-form-{{ $module->id }}" action="{{ route('modules.update', $module->id) }}" method="POST" class="hidden flex items-center space-x-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="title" value="{{ $module->title }}" class="px-2 py-1 border rounded text-sm focus:ring-2 focus:ring-blue-500">
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-xs">บันทึก</button>
                                        <button type="button" onclick="toggleEditModule({{ $module->id }})" class="text-gray-500 text-xs">ยกเลิก</button>
                                    </form>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('modules.create_lesson', $module->id) }}" class="text-sm px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 flex items-center">
                                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i> เพิ่มบทเรียน
                                    </a>
                                    
                                    <form action="{{ route('modules.destroy', $module->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบโมดูลนี้? บทเรียนทั้งหมดในโมดูลจะถูกลบออกด้วย')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-400 hover:text-red-600">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="p-4">
                                @if ($module->lessons->isEmpty())
                                    <p class="text-gray-500 italic text-sm">ยังไม่มีบทเรียนในโมดูลนี้</p>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg group">
                                                <div class="flex items-center">
                                                    <i data-lucide="play-circle" class="w-4 h-4 text-blue-500 mr-3"></i>
                                                    <span class="text-gray-700">
                                                        {{ $lesson->order_number }}. {{ $lesson->title }}
                                                        @if ($lesson->quiz)
                                                            <span class="ml-2 text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">มีควิซ</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('lessons.edit', $lesson->id) }}" class="text-blue-500 hover:text-blue-700">
                                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบบทเรียนนี้?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-400 hover:text-red-600">
                                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        // ฟังก์ชันสลับแสดงฟอร์มแก้ไขชื่อ Module
        function toggleEditModule(id) {
            const titleContainer = document.getElementById(`module-title-container-${id}`);
            const editForm = document.getElementById(`module-edit-form-${id}`);
            
            if (editForm.classList.contains('hidden')) {
                editForm.classList.remove('hidden');
                titleContainer.classList.add('hidden');
            } else {
                editForm.classList.add('hidden');
                titleContainer.classList.remove('hidden');
            }
        }

        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</x-teachers-layout>