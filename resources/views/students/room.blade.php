<x-app-layout>
  <div class="sm:ml-64">
    <div class="container">
      <div class="course-title">รายวิชา : เทคโนโลยีสารสนเทศ</div>

        <div class="relative p-4 mt-24 max-w-sm mx-auto">
        <div class="flex mb-2 items-center justify-between">
            <div>
            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                In Progress
            </span>
            </div>
            <div class="text-right">
            <span class="text-xs font-semibold inline-block text-teal-600">
                70%
            </span>
            </div>
        </div>
        <div class="flex rounded-full h-2 bg-gray-200">
            <div style="width:70%" class="rounded-full bg-teal-500"></div>
        </div>
        </div>

      <!-- Tab Navigation -->
      <div class="tabs flex border-b border-gray-200 dark:border-gray-700 mt-8">
        <button class="tab-btn py-4 px-6 font-medium text-sm border-b-2 border-transparent hover:text-blue-600 hover:border-blue-500 transition-all duration-300 active" data-tab="tab1">เรื่องที่ 1</button>
        <button class="tab-btn py-4 px-6 font-medium text-sm border-b-2 border-transparent hover:text-blue-600 hover:border-blue-500 transition-all duration-300" data-tab="tab2">เรื่องที่ 2</button>
        <button class="tab-btn py-4 px-6 font-medium text-sm border-b-2 border-transparent hover:text-blue-600 hover:border-blue-500 transition-all duration-300" data-tab="tab3">เรื่องที่ 3</button>
      </div>

      <!-- Tab Contents -->
      <div class="tab-contents">
        <!-- Section 1 -->
        <div class="tab-content active" id="tab1">
          <div class="section">
            <div class="section-title">เรื่องที่ 1 : ปัญญาประดิษฐ์</div>

            {{-- ตอนที่ 1 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 1 : ทำความรู้จักกับ AI</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">5:34</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : คำอธิบายเพิ่มเติม</p>
                </div>
              </div>
            </div>

            {{-- ตอนที่ 2 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 2 : เบื้องหลัง AI ทำงานอย่างไร</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">5:34</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : คำอธิบายเพิ่มเติม</p>
                </div>
              </div>
            </div>

            {{-- ตอนที่ 3 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 3 : การประยุกต์ใช้ AI ในชีวิตประจำวัน</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">5:34</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : คำอธิบายเพิ่มเติม</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section 2 -->
        <div class="tab-content hidden" id="tab2">
          <div class="section">
            <div class="section-title">เรื่องที่ 2 : Machine Learning พื้นฐาน</div>

            {{-- ตอนที่ 1 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 1 : แนะนำ Machine Learning</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">7:12</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : เรียนรู้พื้นฐานของ Machine Learning</p>
                </div>
              </div>
            </div>

            {{-- ตอนที่ 2 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 2 : Supervised vs Unsupervised Learning</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">8:45</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : เปรียบเทียบการเรียนรู้แบบมีผู้สอนและไม่มีผู้สอน</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section 3 -->
        <div class="tab-content hidden" id="tab3">
          <div class="section">
            <div class="section-title">เรื่องที่ 3 : การประยุกต์ใช้ AI ในชีวิตจริง</div>

            {{-- ตอนที่ 1 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 1 : AI ในอุตสาหกรรมต่างๆ</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">6:30</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : ตัวอย่างการใช้งาน AI ในอุตสาหกรรมต่างๆ</p>
                </div>
              </div>
            </div>

            {{-- ตอนที่ 2 --}}
            <div class="chapter">
              <div class="lesson" onclick="toggleVideo(this)">
                <div class="lesson-title">ตอนที่ 2 : สร้างโปรเจค AI ง่ายๆ ด้วยตัวเอง</div>
                <button class="toggle-btn flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg transition-all duration-300">
                  <div class="text-white">10:15</div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="toggle-icon h-5 w-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </div>
              <div class="video-player video-hidden transition-all duration-500 overflow-hidden">
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <video class="w-full rounded-md mb-4" controls>
                    <source src="https://phothongdlec.ac.th/storage/video/เข้าสู่ระบบ.mp4" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                  </video>
                  <p class="text-gray-700">คำอธิบาย : เริ่มต้นสร้างโปรเจค AI ง่ายๆ ด้วยเครื่องมือฟรี</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

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

  .lesson:hover {
    background-color: #f0f0f0;
  }

  .lesson-title {
    font-size: 16px;
    color: #353b48;
  }

  /* แสดงว่าตอนนี้ถูกเปิดอยู่ */
  .lesson.active {
    background-color: #dff0ff;
  }

  /* หมุนไอคอนขึ้นเมื่อเปิด */
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

  /* Tab Styles */
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

    .step-line {
        background-color: #e5e7eb;
        height: 2px;
    }
    .step-line.active {
        background: linear-gradient(90deg, #10B981, #10B981);
    }
</style>

<script>
  function toggleVideo(el) {
    const allPlayers = document.querySelectorAll('.video-player');
    const allLessons = document.querySelectorAll('.lesson');

    const chapter = el.closest('.chapter');
    const currentPlayer = chapter.querySelector('.video-player');

    const isOpen = currentPlayer.classList.contains('video-show');

    // ปิดทุก video และลบคลาส active ก่อน
    allPlayers.forEach(player => {
      player.classList.remove('video-show');
      player.classList.add('video-hidden');
    });
    allLessons.forEach(lesson => {
      lesson.classList.remove('active');
    });

    // ถ้ายังไม่เปิด ให้เปิดอันนี้
    if (!isOpen) {
      currentPlayer.classList.remove('video-hidden');
      currentPlayer.classList.add('video-show');
      el.classList.add('active');
    }
    // ถ้าเปิดอยู่แล้ว ไม่ต้องทำอะไรเพิ่มเติม (เพราะปิดไปแล้วข้างบน)
  }

  // Tab functionality
  document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    tabBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        // Remove active class from all buttons and contents
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked button and corresponding content
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
      });
    });
  });
</script>

<script>
    // สามารถเพิ่ม JavaScript เพื่อเปลี่ยนสถานะขั้นตอนได้
    document.querySelectorAll('.relative.z-10').forEach((step, index) => {
        step.addEventListener('click', () => {
            // เปลี่ยนสีขั้นตอนที่คลิกและขั้นตอนก่อนหน้าเป็นสีเขียว
            document.querySelectorAll('.relative.z-10').forEach((s, i) => {
                if(i <= index) {
                    s.classList.remove('bg-gray-300', 'text-gray-500');
                    s.classList.add('bg-emerald-500', 'text-white');
                } else {
                    s.classList.remove('bg-emerald-500', 'text-white');
                    s.classList.add('bg-gray-300', 'text-gray-500');
                }
            });
            
            // อัพเดตเส้นความคืบหน้า
            const progress = (index / 4) * 100;
            document.querySelector('.step-line-progress').style.width = `${progress}%`;
            
            // อัพเดตสถานะในแบบแนวตั้ง
            const verticalSteps = document.querySelectorAll('.md:hidden .flex.items-start');
            verticalSteps.forEach((vStep, vIndex) => {
                const textDiv = vStep.querySelector('.pt-3.pl-4');
                if(vIndex <= index) {
                    vStep.querySelector('div.absolute').classList.remove('bg-gray-300', 'text-gray-500');
                    vStep.querySelector('div.absolute').classList.add('bg-emerald-500', 'text-white');
                    textDiv.classList.add('text-emerald-600', 'font-medium');
                    textDiv.textContent = `ขั้นตอนที่ ${vIndex+1} (เสร็จแล้ว)`;
                } else {
                    vStep.querySelector('div.absolute').classList.remove('bg-emerald-500', 'text-white');
                    vStep.querySelector('div.absolute').classList.add('bg-gray-300', 'text-gray-500');
                    textDiv.classList.remove('text-emerald-600', 'font-medium');
                    textDiv.textContent = `ขั้นตอนที่ ${vIndex+1}`;
                }
            });
        });
    });
</script>