<x-teachers-layout>
    <div class="p-6 mt-16 max-w-7xl mx-auto space-y-6" x-data="{ editOpen: false, editShort: { id: null, title: '', description: '', course_id: '' }, previewOpen: false, previewShort: { type: '', title: '', video_path: '', images: [], audio_path: '' } }">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="p-4 text-sm text-green-800 rounded-2xl bg-green-50 dark:bg-green-950/30 dark:text-green-300 border border-green-200 dark:border-green-800/40 flex items-center gap-3 animate-in fade-in" role="alert">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 text-sm text-red-800 rounded-2xl bg-red-50 dark:bg-red-950/30 dark:text-red-300 border border-red-200 dark:border-red-800/40 flex flex-col gap-1.5 animate-in fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                    </svg>
                    <span class="font-bold">เกิดข้อผิดพลาดในการตรวจสอบข้อมูล:</span>
                </div>
                <ul class="list-disc list-inside pl-5 mt-1 font-semibold text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Header banner -->
        <div class="bg-gradient-to-r from-purple-50/70 via-slate-50 to-indigo-50/70 dark:from-slate-900/90 dark:via-purple-950/80 dark:to-indigo-950/90 text-slate-800 dark:text-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm border border-slate-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between min-h-[160px]">
            <div class="relative z-10 space-y-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-950/50 dark:text-purple-300 text-xs font-black tracking-widest uppercase border border-purple-200/60 dark:border-purple-800/40 shadow-sm">
                    OLIS Shorts Studio
                </span>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">จัดการคลังคลิปเรียนรู้สั้น</h1>
                <p class="text-sm text-slate-600 dark:text-slate-350 leading-relaxed font-semibold max-w-3xl">
                    สร้างเนื้อหาการเรียนรู้ตามหลักสูตรด้วยวิดีโอคลิปสั้น หรือลงโพสต์ประเภทรูปภาพสไลด์ที่เลื่อนดูได้แบบ TikTok เพื่อให้ผู้เรียนเข้าถึงข้อมูลความรู้ได้ง่ายและน่าสนใจ
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Upload Card -->
            <div class="lg:col-span-1 bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm h-fit" x-data="{ postType: 'video' }">
                <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v12m0-12a3 3 0 013 3v1m-3-4a3 3 0 00-3 3v1m3-4V4m0 0L8 8m4-4l4 4"></path>
                    </svg>
                    เผยแพร่ผลงานใหม่
                </h2>

                <!-- Type Selector Tabs -->
                <div class="flex border border-slate-200 dark:border-slate-800 rounded-xl p-1 mb-5 bg-slate-50 dark:bg-slate-950">
                    <button type="button" @click="postType = 'video'" :class="postType === 'video' ? 'bg-white dark:bg-slate-900 text-purple-700 dark:text-purple-400 shadow-sm' : 'text-slate-500'" class="flex-1 py-2 text-xs font-black rounded-lg transition-all text-center">
                        🎥 คลิปวิดีโอสั้น
                    </button>
                    <button type="button" @click="postType = 'images'" :class="postType === 'images' ? 'bg-white dark:bg-slate-900 text-purple-700 dark:text-purple-400 shadow-sm' : 'text-slate-500'" class="flex-1 py-2 text-xs font-black rounded-lg transition-all text-center">
                        📸 สไลด์รูปภาพ
                    </button>
                </div>

                <form action="{{ route('teachers.shorts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" :value="postType">
                    
                    <!-- File input (Video) -->
                    <div class="space-y-2" x-show="postType === 'video'">
                        <label class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">ไฟล์วิดีโอแนวตั้ง (MP4/WebM)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-900/60 transition-all group overflow-hidden relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4" id="upload-prompt">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-purple-500 transition-colors mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-xs font-bold text-slate-650 dark:text-slate-400">คลิกเพื่อเลือกไฟล์วิดีโอ</p>
                                    <p class="text-[9px] text-slate-400 mt-1 font-bold">อัตราส่วน 9:16 (แนวตั้ง) แนะนำไม่เกิน 50MB</p>
                                </div>
                                <div class="hidden flex-col items-center justify-center text-center p-4 absolute inset-0 bg-purple-50/50 dark:bg-purple-950/20" id="file-info">
                                    <svg class="w-8 h-8 text-purple-600 mb-2 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-750 dark:text-slate-300 block truncate w-full" id="file-name">filename.mp4</span>
                                    <span class="text-[9px] text-slate-400 font-bold mt-1" id="file-size">0.0 MB</span>
                                </div>
                                <input type="file" name="video" id="video-input" class="hidden" accept="video/mp4,video/webm,video/quicktime">
                            </label>
                        </div>
                    </div>

                    <!-- File input (Images) -->
                    <div class="space-y-2" x-show="postType === 'images'" x-cloak>
                        <label class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">เลือกรูปภาพหลายรูป (แนวตั้ง)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl cursor-pointer bg-slate-50 dark:bg-slate-950 hover:bg-slate-100 dark:hover:bg-slate-900/60 transition-all group overflow-hidden relative">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4" id="images-upload-prompt">
                                    <svg class="w-8 h-8 text-slate-400 group-hover:text-purple-500 transition-colors mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-xs font-bold text-slate-650 dark:text-slate-400">คลิกเพื่อเลือกภาพประกอบ</p>
                                    <p class="text-[9px] text-slate-400 mt-1 font-bold">เลือกได้พร้อมกันสูงสุด 10 รูป</p>
                                </div>
                                <div class="hidden flex-col items-center justify-center text-center p-4 absolute inset-0 bg-purple-50/50 dark:bg-purple-950/20" id="images-file-info">
                                    <svg class="w-8 h-8 text-purple-600 mb-2 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-750 dark:text-slate-300 block truncate w-full" id="images-count-text">0 รูปภาพ</span>
                                    <span class="text-[9px] text-slate-400 font-bold mt-1">พร้อมสำหรับอัปโหลด</span>
                                </div>
                                <input type="file" name="images[]" id="images-input" class="hidden" accept="image/*" multiple>
                            </label>
                        </div>
                    </div>

                    <!-- Audio Options (Only visible for Images type) -->
                    <div class="space-y-4 pt-3 border-t border-slate-100 dark:border-slate-800" x-show="postType === 'images'" x-cloak>
                        <h3 class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-wider">ใส่เสียงประกอบ (เพลง / เสียงพากย์)</h3>
                        
                        <div class="space-y-3" x-data="{ selectMode: 'upload' }">
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" @click="selectMode = 'upload'" :class="selectMode === 'upload' ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 border-purple-200 dark:text-purple-400 dark:border-purple-800' : 'bg-slate-50 dark:bg-slate-950 text-slate-500 border-transparent'" class="py-2.5 px-3 text-[11px] font-black rounded-xl border transition-all flex items-center justify-center gap-1.5">
                                    📂 อัปโหลดไฟล์เสียง
                                </button>
                                <button type="button" @click="selectMode = 'record'" :class="selectMode === 'record' ? 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 border-purple-200 dark:text-purple-400 dark:border-purple-800' : 'bg-slate-50 dark:bg-slate-950 text-slate-500 border-transparent'" class="py-2.5 px-3 text-[11px] font-black rounded-xl border transition-all flex items-center justify-center gap-1.5">
                                    🎙️ บันทึกเสียงพากย์สด
                                </button>
                            </div>

                            <!-- File Upload Form Field -->
                            <div class="mt-2" x-show="selectMode === 'upload'">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1">รองรับไฟล์ MP3, WAV, M4A (ไม่เกิน 10MB)</label>
                                <input type="file" name="audio" id="audio-input" accept="audio/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-purple-50 file:text-purple-700 dark:file:bg-purple-950/30 dark:file:text-purple-400 file:cursor-pointer">
                            </div>

                            <!-- Live Audio Recorder Widget -->
                            <div class="mt-2 bg-slate-50 dark:bg-slate-950 p-4 rounded-2xl border border-slate-200 dark:border-slate-800/80 flex flex-col items-center gap-3" x-show="selectMode === 'record'" x-cloak x-data="audioRecorder()">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500" :class="isRecording ? 'animate-pulse' : 'opacity-40'"></span>
                                    <span class="text-xs font-black text-slate-700 dark:text-slate-300" x-text="formatTime(recordingTime)">00:00</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <!-- Record / Stop toggle button -->
                                    <button type="button" @click="toggleRecord()" class="w-10 h-10 rounded-full flex items-center justify-center text-white transition-all active:scale-95 shadow-md" :class="isRecording ? 'bg-red-600 hover:bg-red-700' : 'bg-purple-600 hover:bg-purple-700'">
                                        <svg x-show="!isRecording" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/><path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/></svg>
                                        <svg x-show="isRecording" class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                    </button>
                                </div>

                                <p class="text-[9px] font-bold text-slate-400 text-center" x-text="statusText">คลิกไมโครโฟนเพื่อเริ่มบันทึกเสียง</p>

                                <!-- Recorded audio preview -->
                                <div class="w-full flex flex-col items-center gap-2 mt-2 pt-2 border-t border-slate-200 dark:border-slate-800" x-show="audioUrl" x-cloak>
                                    <audio :src="audioUrl" controls class="w-full h-8"></audio>
                                    <span class="text-[9px] text-green-600 dark:text-green-450 font-black">✓ บันทึกและแนบเสียงลงในโพสต์แล้ว</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="space-y-2">
                        <label for="title" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">หัวข้อ / คำอธิบายแบบย่อ (Title)</label>
                        <input type="text" name="title" id="title" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm" placeholder="เช่น การหาแรงลัพธ์ในวิชาฟิสิกส์ ม.4" required>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">แฮชแท็ก / คำอธิบายเพิ่มเติม</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm" placeholder="เช่น #ฟิสิกส์ #วิทยาศาสตร์ #ความรู้ทั่วไป"></textarea>
                    </div>

                    <!-- Course select -->
                    <div class="space-y-2">
                        <label for="course_id" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">หลักสูตร / รายวิชาที่เชื่อมโยง (ระบุหรือไม่ก็ได้)</label>
                        <select name="course_id" id="course_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                            <option value="">-- ไม่เชื่อมโยง (เฉพาะความรู้ทั่วไป) --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="w-full py-3 bg-purple-700 hover:bg-purple-800 text-white rounded-xl text-xs font-black tracking-widest uppercase transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        เริ่มเผยแพร่ผลงาน
                    </button>
                </form>
            </div>

            <!-- List Grid -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        ผลงานเรียนรู้สั้นที่เผยแพร่แล้ว ({{ $shorts->count() }} รายการ)
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($shorts as $short)
                            <div class="border border-slate-150 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50 dark:bg-slate-950 flex flex-col justify-between hover:shadow-md transition-shadow">
                                <div class="p-4 flex gap-4">
                                    <!-- Simple Thumbnail Preview -->
                                    <div class="w-16 h-28 rounded-lg bg-black overflow-hidden relative flex-shrink-0 flex items-center justify-center border border-slate-200 dark:border-slate-800">
                                        @if($short->type === 'images')
                                            @php
                                                $firstImg = is_array($short->images) && count($short->images) > 0 ? $short->images[0] : null;
                                            @endphp
                                            @if($firstImg)
                                                <img src="{{ asset('storage/' . $firstImg) }}" class="w-full h-full object-cover opacity-80">
                                            @else
                                                <span class="text-white text-[9px] font-black">ไม่มีรูปภาพ</span>
                                            @endif
                                            <span class="absolute top-1 right-1 bg-black/60 text-[8px] text-white px-1.5 py-0.5 rounded font-black z-10">
                                                🖼️ {{ count($short->images) }}
                                            </span>
                                            @if($short->audio_path)
                                                <span class="absolute bottom-1 left-1 bg-purple-600/80 text-[7px] text-white px-1 py-0.5 rounded font-bold z-10 flex items-center gap-0.5">
                                                    🎵 มีเสียง
                                                </span>
                                            @endif
                                        @else
                                            <video class="w-full h-full object-cover opacity-75" preload="metadata" muted>
                                                <source src="{{ asset('storage/' . $short->video_path) }}#t=0.5" type="video/mp4">
                                            </video>
                                            <span class="absolute inset-0 flex items-center justify-center z-10 bg-black/30 pointer-events-none">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Content Info -->
                                    <div class="flex-grow min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[9px] px-1.5 py-0.5 rounded font-black {{ $short->type === 'images' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ $short->type === 'images' ? 'SLIDE' : 'VIDEO' }}
                                            </span>
                                            <h3 class="font-black text-xs text-slate-800 dark:text-white truncate leading-relaxed flex-grow" title="{{ $short->title }}">{{ $short->title }}</h3>
                                        </div>
                                        <span class="text-[9px] text-slate-400 font-bold block mt-1.5">{{ $short->created_at->diffForHumans() }}</span>
                                        
                                        @if($short->course)
                                            <span class="inline-flex items-center mt-2.5 px-2 py-0.5 rounded-md bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-300 text-[9px] font-black border border-purple-100 dark:border-purple-800/40">
                                                หลักสูตร: {{ Str::limit($short->course->title, 20) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center mt-2.5 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 text-slate-500 text-[9px] font-black">
                                                ความรู้ทั่วไป
                                            </span>
                                        @endif

                                        <!-- Statistics -->
                                        <div class="flex items-center gap-3 mt-3.5 text-[10px] text-slate-500 font-bold">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ $short->views_count }} วิว
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-red-400 fill-current" viewBox="0 0 24 24">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                </svg>
                                                {{ $short->likes_count }} ถูกใจ
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions row -->
                                <div class="bg-slate-100/50 dark:bg-slate-900/40 px-4 py-2 flex justify-between items-center border-t border-slate-100 dark:border-slate-800 gap-2">
                                    <button type="button" @click="previewShort = { type: '{{ $short->type }}', title: '{{ addslashes($short->title) }}', video_path: '{{ $short->video_path ? asset('storage/' . $short->video_path) : '' }}', images: {{ json_encode($short->images) }}, audio_path: '{{ $short->audio_path ? asset('storage/' . $short->audio_path) : '' }}' }; previewOpen = true;" class="text-[10px] text-purple-650 hover:underline font-bold flex items-center gap-1 focus:outline-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        ดูตัวอย่าง
                                    </button>
                                    
                                    <button type="button" @click="editShort = { id: {{ $short->id }}, title: '{{ addslashes($short->title) }}', description: '{{ addslashes($short->description) }}', course_id: '{{ $short->course_id }}' }; editOpen = true;" class="text-[10px] text-blue-650 hover:underline font-bold flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        แก้ไข
                                    </button>
                                    
                                    <form action="{{ route('teachers.shorts.destroy', $short->id) }}" method="POST" onsubmit="return confirm('ยืนยันที่จะลบข้อมูลนี้ถาวร?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] text-red-650 hover:underline font-bold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            ลบ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 py-16 text-center text-slate-400 font-bold text-xs bg-slate-50 dark:bg-slate-955 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-slate-300 dark:text-slate-700 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                                </svg>
                                คุณยังไม่ได้เผยแพร่ผลงานคลิปหรือสไลด์รูปภาพบทเรียนในระบบ
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal Widget -->
        <div x-show="editOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editOpen = false"></div>

            <!-- Modal Dialog -->
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-[2rem] bg-white dark:bg-slate-900 text-left shadow-2xl transition-all w-full max-w-md p-6 border border-slate-150 dark:border-slate-800" @click.stop>
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            แก้ไขข้อมูลคลิปเรียนรู้สั้น
                        </h3>
                        <button type="button" @click="editOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form :action="`/teachers/shorts/${editShort.id}`" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="edit_title" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">หัวข้อ / คำอธิบายแบบย่อ</label>
                            <input type="text" name="title" id="edit_title" x-model="editShort.title" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm" required>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="edit_description" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">แฮชแท็ก / คำอธิบายเพิ่มเติม</label>
                            <textarea name="description" id="edit_description" x-model="editShort.description" rows="3" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm"></textarea>
                        </div>

                        <!-- Course select -->
                        <div class="space-y-2">
                            <label for="edit_course_id" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider">หลักสูตรที่เชื่อมโยง</label>
                            <select name="course_id" id="edit_course_id" x-model="editShort.course_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-slate-100 text-xs font-semibold focus:ring-purple-500 focus:border-purple-500 transition-all shadow-sm">
                                <option value="">-- ไม่เชื่อมโยง (เฉพาะความรู้ทั่วไป) --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" @click="editOpen = false" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all">
                                ยกเลิก
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black tracking-wider uppercase transition-all shadow-md active:scale-95">
                                บันทึกการแก้ไข
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Video & Images file display JavaScript helper -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Video input handlers
            const videoInput = document.getElementById('video-input');
            const videoPrompt = document.getElementById('upload-prompt');
            const videoInfo = document.getElementById('file-info');
            const videoName = document.getElementById('file-name');
            const videoSize = document.getElementById('file-size');

            if (videoInput) {
                videoInput.addEventListener('change', () => {
                    if (videoInput.files && videoInput.files[0]) {
                        const file = videoInput.files[0];
                        videoName.textContent = file.name;
                        const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                        videoSize.textContent = `${sizeMb} MB`;

                        videoPrompt.classList.add('hidden');
                        videoInfo.classList.remove('hidden');
                    } else {
                        videoPrompt.classList.remove('hidden');
                        videoInfo.classList.add('hidden');
                    }
                });
            }

            // Images input handlers
            const imagesInput = document.getElementById('images-input');
            const imagesPrompt = document.getElementById('images-upload-prompt');
            const imagesInfo = document.getElementById('images-file-info');
            const imagesCountText = document.getElementById('images-count-text');

            if (imagesInput) {
                imagesInput.addEventListener('change', () => {
                    if (imagesInput.files && imagesInput.files.length > 0) {
                        imagesCountText.textContent = `${imagesInput.files.length} รูปภาพ`;
                        imagesPrompt.classList.add('hidden');
                        imagesInfo.classList.remove('hidden');
                    } else {
                        imagesPrompt.classList.remove('hidden');
                        imagesInfo.classList.add('hidden');
                    }
                });
            }
        });

        // Alpine voiceover recorder helper
        function audioRecorder() {
            return {
                isRecording: false,
                mediaRecorder: null,
                audioChunks: [],
                audioUrl: null,
                recordingTime: 0,
                timerInterval: null,
                statusText: 'คลิกเพื่อเริ่มบันทึกเสียงพากย์สด',

                formatTime(seconds) {
                    const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const secs = (seconds % 60).toString().padStart(2, '0');
                    return `${mins}:${secs}`;
                },

                async toggleRecord() {
                    if (this.isRecording) {
                        this.stopRecording();
                    } else {
                        await this.startRecording();
                    }
                },

                async startRecording() {
                    this.audioChunks = [];
                    this.audioUrl = null;
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.mediaRecorder = new MediaRecorder(stream);
                        this.mediaRecorder.ondataavailable = (event) => {
                            if (event.data.size > 0) {
                                this.audioChunks.push(event.data);
                            }
                        };
                        this.mediaRecorder.onstop = () => {
                            const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                            this.audioUrl = URL.createObjectURL(audioBlob);
                            
                            // Inject into the standard file input
                            const fileInput = document.getElementById('audio-input');
                            if (fileInput) {
                                const container = new DataTransfer();
                                const file = new File([audioBlob], "voiceover.wav", { 
                                    type: "audio/wav", 
                                    lastModified: new Date().getTime() 
                                });
                                container.items.add(file);
                                fileInput.files = container.files;
                            }
                        };

                        this.mediaRecorder.start();
                        this.isRecording = true;
                        this.statusText = 'กำลังบันทึกเสียงพากย์... คลิกปุ่มสีแดงเพื่อหยุด';
                        
                        this.recordingTime = 0;
                        this.timerInterval = setInterval(() => {
                            this.recordingTime++;
                        }, 1000);
                    } catch (err) {
                        console.error('Microphone access denied:', err);
                        this.statusText = 'เกิดข้อผิดพลาด: ไม่สามารถเข้าถึงไมโครโฟนได้';
                    }
                },

                stopRecording() {
                    if (this.mediaRecorder && this.isRecording) {
                        this.mediaRecorder.stop();
                        
                        // Release mic tracks
                        this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
                        
                        clearInterval(this.timerInterval);
                        this.isRecording = false;
                        this.statusText = 'บันทึกเสียงพากย์เสร็จเรียบร้อย!';
                    }
                }
            };
        }
    </script>

    <!-- Live Preview Modal (TikTok Smartphone Mockup Style) -->
    <div x-show="previewOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-4" 
         x-cloak>
        
        <div @click.outside="previewOpen = false; if($refs.previewVideo) $refs.previewVideo.pause(); if($refs.previewAudio) $refs.previewAudio.pause();" 
             class="bg-slate-900 border border-slate-800 rounded-[2.5rem] w-full max-w-[340px] aspect-[9/16] relative overflow-hidden flex flex-col shadow-2xl">
            
            <!-- Top bar with close button -->
            <div class="absolute top-4 left-4 right-4 z-30 flex items-center justify-between pointer-events-none">
                <span class="text-[9px] text-white/50 bg-black/40 backdrop-blur-md px-2.5 py-1 rounded-full font-black border border-white/5 uppercase tracking-wider" x-text="previewShort.type === 'images' ? '📸 Slide' : '🎥 Video'">
                    ดูตัวอย่าง
                </span>
                <button type="button" @click="previewOpen = false; if($refs.previewVideo) $refs.previewVideo.pause(); if($refs.previewAudio) $refs.previewAudio.pause();" 
                        class="pointer-events-auto p-1.5 bg-black/40 hover:bg-black/60 text-white rounded-full transition-all focus:outline-none border border-white/10 active:scale-95">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content Preview Area -->
            <div class="flex-1 bg-black relative flex items-center justify-center">
                
                <!-- Video Preview -->
                <template x-if="previewShort.type === 'video'">
                    <video x-ref="previewVideo" :src="previewShort.video_path" class="w-full h-full object-contain bg-black" loop controls autoplay></video>
                </template>

                <!-- Images Carousel Preview -->
                <template x-if="previewShort.type === 'images'">
                    <div class="w-full h-full relative flex items-center justify-center bg-slate-950" x-data="{ currentImg: 0 }">
                        <!-- Carousel Images -->
                        <div class="w-full h-full flex overflow-x-hidden relative items-center justify-center">
                            <template x-for="(img, idx) in previewShort.images" :key="idx">
                                <img x-show="currentImg === idx" :src="'/storage/' + img" class="w-full h-full object-cover">
                            </template>
                        </div>

                        <!-- Arrows -->
                        <button type="button" @click="currentImg = (currentImg - 1 + previewShort.images.length) % previewShort.images.length" 
                                class="absolute left-2.5 w-7 h-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center border border-white/10 active:scale-90 focus:outline-none z-20 text-[10px]">
                            ◀
                        </button>
                        <button type="button" @click="currentImg = (currentImg + 1) % previewShort.images.length" 
                                class="absolute right-2.5 w-7 h-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center border border-white/10 active:scale-90 focus:outline-none z-20 text-[10px]">
                            ▶
                        </button>

                        <!-- Carousel Dot Indicators -->
                        <div class="absolute bottom-16 inset-x-0 flex items-center justify-center gap-1.5 z-20">
                           <template x-for="(img, idx) in previewShort.images" :key="idx">
                               <span :class="currentImg === idx ? 'bg-rose-500 w-3' : 'bg-white/40 w-1.5'" class="h-1.5 rounded-full transition-all duration-300"></span>
                           </template>
                        </div>

                        <!-- Audio Player for Images -->
                        <template x-if="previewShort.audio_path">
                            <audio x-ref="previewAudio" :src="previewShort.audio_path" loop autoplay class="absolute bottom-4 inset-x-4 h-8 bg-black/40 rounded-xl"></audio>
                        </template>
                    </div>
                </template>
            </div>

            <!-- Bottom Info -->
            <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent p-4 pt-10 flex flex-col justify-end pointer-events-none">
                <p class="text-xs text-white font-black truncate drop-shadow-md" x-text="previewShort.title"></p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="text-[9px] text-white/50 font-bold uppercase tracking-wider">กำลังเล่นไฟล์ตัวอย่าง</span>
                </div>
            </div>
        </div>
    </div>
</x-teachers-layout>
