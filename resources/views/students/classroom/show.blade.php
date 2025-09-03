<x-app-layout>
    <div class="sm:ml-64">
        {{-- Flash Messages --}}
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

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Course Content Section --}}
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    @if ($course->cover_image)
                        <img src="{{$course->cover_image}}" alt="Course Cover: {{ $course->title }}" class="w-full h-80 object-cover">
                    @else
                        <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-500 text-center text-xl">
                            ไม่มีรูปภาพ
                        </div>
                    @endif
                    <div class="p-6">
                        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">{{ $course->title }}</h1>
                        <p class="text-lg text-gray-600 mb-4">{{ $course->description }}</p>
                        <div class="flex items-center text-gray-500 mb-4">
                            <span class="font-semibold text-gray-700">สอนโดย: {{ $course->teacher->name }}</span>
                        </div>
                    </div>
                </div>

                {{-- Modules and Lessons --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">สารบัญหลักสูตร</h2>
                    @if ($course->modules->isEmpty())
                        <p class="text-gray-500 italic">หลักสูตรนี้ยังไม่มีโมดูลหรือบทเรียน</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($course->modules->sortBy('order_number') as $module)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ $module->order_number }}. {{ $module->title }}</h3>
                                    @if ($module->lessons->isEmpty())
                                        <p class="text-gray-500 italic ml-4">โมดูลนี้ยังไม่มีบทเรียน</p>
                                    @else
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                                <li>{{ $lesson->order_number }}. {{ $lesson->title }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar and Enrollment Section --}}
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-3xl font-extrabold text-blue-600">
                            ฟรี
                        </span>
                        {{-- <span class="text-lg text-gray-500 line-through">999฿</span> --}}
                    </div>

                    {{-- Enrollment Button --}}
                    @guest
                        <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                            ล็อกอินเพื่อลงทะเบียนเรียน
                        </a>
                    @else
                        @if (!$isEnrolled)
                            <form action="{{ route('classroom.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                    ลงทะเบียนเรียนเลย
                                </button>
                            </form>
                        @else
                            <button class="w-full bg-green-500 text-white font-bold py-3 rounded-lg cursor-not-allowed opacity-75">
                                ลงทะเบียนแล้ว
                            </button>
                            {{-- เปลี่ยน href="#" ให้เป็น route ใหม่ --}}
                            <a href="{{ route('classroom.access', $course->id) }}" class="block w-full text-center mt-2 bg-gray-200 text-gray-800 font-bold py-3 rounded-lg hover:bg-gray-300 transition duration-300">
                                เข้าสู่ห้องเรียน
                            </a>
                        @endif
                    @endguest

                    <hr class="my-6">

                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            เข้าถึงได้ตลอดชีพ
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            วิดีโอคุณภาพสูง
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            แบบทดสอบท้ายบท
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>