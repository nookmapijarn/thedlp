<!-- โหลด CSS และ JS สำหรับ Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('รูปภาพ') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("อัพโหลดภาพของคุณ") }}
        </p>
    </header>

    <form method="post" action="{{ route('avatar.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        
        {{-- Avatar Upload form --}}
        <div class="flex items-center justify-center w-44 h-48 relative overflow-hidden border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50">
            <div id="preview-container" class="relative w-full h-full">
                <img id="preview"
                    src="{{ auth()->user()->avatar ? auth()->user()->avatar . '?v=' . time() : 'https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png' }}" 
                    alt="Preview Image" 
                    onerror="this.src='https://phothongdlec.ac.th/storage/images/avatar/unkhonw.png'">
            </div>
            <label for="IMG_1" class="flex flex-col absolute items-center justify-center w-full h-full">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 text-center"><span class="font-semibold">กดเพื่ออัพรูป</span></p>
                </div>
                <input id="IMG_1" name="IMG_1" type="file" class="hidden " onchange="loadAndPreviewImage(event)" required>
            </label>
        </div> 

        {{-- messages --}}
        <div>
            @if (session('status'))
                @php
                    $status = session('status');
                    $urlStartPos = strpos($status, 'URL : ');
                    $url = $urlStartPos !== false ? substr($status, $urlStartPos + 6) : '';
                    $message = $url ? substr($status, 0, $urlStartPos) : $status;
                @endphp

                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                    @if ($url)
                        <p>View Avatar: <a href="{{ $url }}" target="_blank">{{ $url }}</a></p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Crop Modal --}}
        <div id="crop-modal" class="hidden fixed inset-0 z-50 bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-4 rounded-lg max-w-4xl w-full">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Crop your avatar</h3>
                <div class="relative">
                    <img id="crop-image" src="#" alt="Image to crop" class="w-full h-auto">
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal()">Cancel</button>
                    <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" onclick="cropImage()">Crop</button>
                </div>
            </div>
        </div>

        <input type="hidden" name="cropped_image" id="cropped_image">
        <input type="hidden" name="original_image" id="original_image">

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Upload') }}</x-primary-button>
        </div>
    </form>
</section>

<script>
    let cropper;
    let originalImage;

    // ฟังก์ชันสำหรับโหลดและแสดง preview
    function loadAndPreviewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.style.display = 'block'; // แสดงรูป preview

            // เก็บไฟล์ต้นฉบับไว้
            originalImage = e.target.result;

            // แสดง modal เพื่อ crop รูปภาพ
            document.getElementById('crop-modal').classList.remove('hidden');
            const cropImage = document.getElementById('crop-image');
            cropImage.src = e.target.result;

            // ตั้งค่าให้ cropper ใช้รูปที่เพิ่งโหลด
            if (cropper) {
                cropper.destroy(); // หากมี instance ก่อนหน้าให้ลบออก
            }

            cropper = new Cropper(cropImage, {
                aspectRatio: 7 / 8, // อัตราส่วน 3.5:4.2
                viewMode: 1,
                autoCropArea: 0.8,
                responsive: true,
                zoomable: false,
                scalable: false,
                ready: function () {
                    // ตั้งค่าให้ cropper เป็นขนาดที่พอดีเมื่อพร้อม
                    const canvasData = cropper.getCanvasData();
                    const imageData = cropper.getImageData();
                    const cropBoxData = cropper.getCropBoxData();
                    cropper.setCropBoxData({
                        left: (canvasData.width - cropBoxData.width) / 2,
                        top: (canvasData.height - cropBoxData.height) / 2
                    });
                }
            });
        };

        reader.readAsDataURL(file); // อ่านไฟล์เป็น base64
    }

    // ฟังก์ชัน crop ภาพ
    function cropImage() {
        const croppedCanvas = cropper.getCroppedCanvas();
        const croppedImage = croppedCanvas.toDataURL(); // แปลงเป็น base64
        document.getElementById('cropped_image').value = croppedImage; // ส่งข้อมูล cropped image ไปยังฟอร์ม

        // แสดง preview หลังจากการ crop
        const preview = document.getElementById('preview');
        preview.src = croppedImage;
        preview.style.display = 'block';

        // ซ่อน modal
        closeModal();
    }

    // ฟังก์ชันปิด modal
    function closeModal() {
        document.getElementById('crop-modal').classList.add('hidden');
    }

    // เมื่อฟอร์มถูกส่ง จะส่งข้อมูลที่ crop หรือรูปต้นฉบับ
    document.querySelector('form').addEventListener('submit', function(event) {
        if (!document.getElementById('cropped_image').value) {
            // หากไม่มีการ crop ส่งรูปต้นฉบับ
            document.getElementById('original_image').value = originalImage;
        }
    });
</script>
