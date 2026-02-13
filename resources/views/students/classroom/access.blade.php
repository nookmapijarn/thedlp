<x-app-layout>
    <div class="">
        <div class="container mx-auto px-4 py-8">
            <div class="course-title mt-24">รายวิชา : {{ $course->title }}</div>

            {{-- Progress Bar --}}
            <div class="relative p-4 mt-8 max-w-sm mx-auto">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                            In Progress
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-teal-600 progress-text">
                            {{ $progress ?? 0 }}%
                        </span>
                    </div>
                </div>
                <div class="flex rounded-full h-2 bg-gray-200">
                    <div style="width:{{ $progress ?? 0 }}%" class="rounded-full bg-teal-500 progress-bar"></div>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="tabs flex border-b border-gray-200 dark:border-gray-700 mt-8">
                @foreach ($course->modules->sortBy('order_number') as $module)
                    <button class="tab-btn py-4 px-6 font-medium text-sm border-b-2 border-transparent hover:text-blue-600 hover:border-blue-500 transition-all duration-300 {{ $loop->first ? 'active' : '' }}" data-tab="tab{{ $module->id }}">
                        เรื่องที่ {{ $module->order_number }} {{ $module->title }}
                    </button>
                @endforeach
            </div>

            {{-- Tab Contents --}}
            <div class="tab-contents">
                @foreach ($course->modules->sortBy('order_number') as $module)
                    <div class="tab-content {{ $loop->first ? 'active' : 'hidden' }}" id="tab{{ $module->id }}">
                        <div class="section">
                            <div class="section-title">เรื่องที่ {{ $module->order_number }} : {{ $module->title }}</div>

                            @if ($module->lessons->isEmpty())
                                <p class="text-gray-500 italic mt-4">โมดูลนี้ยังไม่มีบทเรียน</p>
                            @else
                                @foreach ($module->lessons->sortBy('order_number') as $lesson)
                                    <div class="chapter">
                                        <div class="lesson" onclick="toggleVideo(this)">
                                            <div class="lesson-title">
                                                ตอนที่ {{ $lesson->order_number }} : {{ $lesson->title }}
                                            </div>
                                            {{-- แสดงผลตามสถานะการเรียนจบ --}}
                                            <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                                                @if($completedLessons->contains('lesson_id', $lesson->id)) 
                                                    <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" d="M12 2c-.791 0-1.55.314-2.11.874l-.893.893a.985.985 0 0 1-.696.288H7.04A2.984 2.984 0 0 0 4.055 7.04v1.262a.986.986 0 0 1-.288.696l-.893.893a2.984 2.984 0 0 0 0 4.22l.893.893a.985.985 0 0 1 .288.696v1.262a2.984 2.984 0 0 0 2.984 2.984h1.262c.261 0 .512.104.696.288l.893.893a2.984 2.984 0 0 0 4.22 0l.893-.893a.985.985 0 0 1 .696-.288h1.262a2.984 2.984 0 0 0 2.984-2.984V15.7c0-.261.104-.512.288-.696l.893-.893a2.984 2.984 0 0 0 0-4.22l-.893-.893a.985.985 0 0 1-.288-.696V7.04a2.984 2.984 0 0 0-2.984-2.984h-1.262a.985.985 0 0 1-.696-.288l-.893-.893A2.984 2.984 0 0 0 12 2Zm3.683 7.73a1 1 0 1 0-1.414-1.413l-4.253 4.253-1.277-1.277a1 1 0 0 0-1.415 1.414l1.985 1.984a1 1 0 0 0 1.414 0l4.96-4.96Z" clip-rule="evenodd"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.032 12 1.984 1.984 4.96-4.96m4.55 5.272.893-.893a1.984 1.984 0 0 0 0-2.806l-.893-.893a1.984 1.984 0 0 1-.581-1.403V7.04a1.984 1.984 0 0 0-1.984-1.984h-1.262a1.983 1.983 0 0 1-1.403-.581l-.893-.893a1.984 1.984 0 0 0-2.806 0l-.893.893a1.984 1.984 0 0 1-1.403.581H7.04A1.984 1.984 0 0 0 5.055 7.04v1.262c0 .527-.209 1.031-.581 1.403l-.893.893a1.984 1.984 0 0 0 0 2.806l.893.893c.372.372.581.876.581 1.403v1.262a1.984 1.984 0 0 0 1.984 1.984h1.262c.527 0 1.031.209 1.403.581l.893.893a1.984 1.984 0 0 0 2.806 0l.893-.893a1.985 1.985 0 0 1 1.403-.581h1.262a1.984 1.984 0 0 0 1.984-1.984V15.7c0-.527.209-1.031.581-1.403Z"/>
                                                    </svg>
                                                @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                                            <div class="bg-white p-4 rounded-lg shadow-md">
                                                @php
                                                    $videoId = '';
                                                    if ($lesson->video_url) {
                                                        parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $url_params);
                                                        if (isset($url_params['v'])) {
                                                            $videoId = $url_params['v'];
                                                        }
                                                    }
                                                @endphp

                                                @if ($videoId)
                                                    <div class="video-container">
                                                        <iframe 
                                                            src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                            frameborder="0" 
                                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                            allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                @else
                                                    <div class="w-full h-auto bg-gray-200 flex items-center justify-center text-gray-500 text-center py-10 rounded-md mb-4">
                                                        ไม่มีวิดีโอสำหรับบทเรียนนี้
                                                    </div>
                                                @endif
                                                <p class="text-gray-700">คำอธิบาย : {{ $lesson->content }}</p>
                                                
                                                {{-- ปุ่มที่เปลี่ยนสถานะตามที่ผู้ใช้กด --}}
                                                <div class="lesson-progress-container mt-4">
                                                    @if ($completedLessons->contains('lesson_id', $lesson->id))
                                                        <button 
                                                            class="w-full bg-emerald-500 text-white font-bold py-3 rounded-lg cursor-not-allowed opacity-75"
                                                            disabled>
                                                            เรียนแล้ว
                                                        </button>
                                                    @else
                                                        <button
                                                            onclick="markAsCompleted(this, {{ $lesson->id }})"
                                                            class="complete-btn w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                                                            ทำเครื่องหมายว่าเรียนจบแล้ว
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

---

### สไตล์และสคริปต์ (CSS & JavaScript)

<style>
    .container {
        margin: auto;
        padding: 20px;
    }
    .course-title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .section {
        background: #fff;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #2f3640;
        cursor: pointer;
    }

    .lesson {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #eee;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .lesson-success {
        background: red;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-top: 1px solid #eee;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .lesson:hover {
        background-color: #f0f0f0;
    }

    .lesson-title {
        font-size: 16px;
        color: #353b48;
    }

    .lesson.active {
        background-color: #dff0ff;
    }

    .lesson.active .toggle-icon {
        transform: rotate(180deg);
    }

    .video-hidden {
        max-height: 0;
        opacity: 0;
        transition: all 0.5s ease-in-out;
    }

    .video-show {
        max-height: 1000px;
        opacity: 1;
    }

    .tabs {
        margin-top: 30px;
        margin-bottom: 20px;
    }

    .tab-btn {
        position: relative;
        color: #6b7280;
    }

    .tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        border-bottom-width: 2px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* CSS สำหรับกำหนดสัดส่วนวิดีโอ (16:9) โดยไม่ใช้ปลั๊กอิน */
    .video-container {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* (9 / 16) * 100% */
        margin-bottom: 1rem;
        overflow: hidden;
        border-radius: 0.5rem; /* rounded-md */
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>

<script>
    function toggleVideo(el) {
        const allPlayers = document.querySelectorAll('.video-player');
        const allLessons = document.querySelectorAll('.lesson');

        const chapter = el.closest('.chapter');
        const currentPlayer = chapter.querySelector('.video-player');

        const isOpen = currentPlayer.classList.contains('video-show');

        allPlayers.forEach(player => {
            player.classList.remove('video-show');
            player.classList.add('video-hidden');
        });
        allLessons.forEach(lesson => {
            lesson.classList.remove('active');
        });

        if (!isOpen) {
            currentPlayer.classList.remove('video-hidden');
            currentPlayer.classList.add('video-show');
            el.classList.add('active');
        }
    }

    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    });

    // AJAX function to mark a lesson as completed
    function markAsCompleted(button, lessonId) {
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        button.innerHTML = 'กำลังบันทึก...';
        
        fetch(`/lessons/${lessonId}/complete`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // เปลี่ยนสถานะปุ่ม
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-emerald-500', 'opacity-75', 'cursor-not-allowed');
                button.innerHTML = 'เรียนแล้ว';

                // อัปเดต Progress Bar และข้อความ
                const progressBar = document.querySelector('.progress-bar');
                const progressText = document.querySelector('.progress-text');

                if (progressBar && progressText) {
                    progressBar.style.width = data.progress + '%';
                    progressText.innerText = data.progress + '%';
                }

                // อัปเดตไอคอนในปุ่ม toggle-btn ของบทเรียนที่เพิ่งทำเสร็จ
                const lessonDiv = button.closest('.chapter').querySelector('.lesson');
                const toggleBtn = lessonDiv.querySelector('.toggle-btn');
                toggleBtn.innerHTML = `
                    <div class="text-white text-lg">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                `;

            } else {
                button.disabled = false;
                button.classList.remove('opacity-75', 'cursor-not-allowed');
                button.innerHTML = 'ทำเครื่องหมายว่าเรียนจบแล้ว';
                alert('ไม่สามารถทำเครื่องหมายว่าเรียนจบได้');
            }
        })
        .catch(error => {
            console.error('An error occurred:', error);
            button.disabled = false;
            button.classList.remove('opacity-75', 'cursor-not-allowed');
            button.innerHTML = 'ทำเครื่องหมายว่าเรียนจบแล้ว';
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        });
    }
</script>