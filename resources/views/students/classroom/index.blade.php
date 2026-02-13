<x-app-layout>
    <section class="blog-section">
        <div class="blog-header">
            <h2 class="blog-title">หลักสูตรทั้งหมด</h2>
            <p class="blog-subtitle">ค้นหาหลักสูตรที่คุณสนใจและเริ่มต้นการเรียนรู้ได้เลย</p>
        </div>
        
        @if ($courses->isEmpty())
            <div class="bg-white p-10 rounded-lg shadow-md text-center">
                <p class="text-gray-500 text-xl">ยังไม่มีหลักสูตรที่เปิดสอนในขณะนี้</p>
                <p class="mt-2 text-gray-500">กรุณากลับมาตรวจสอบอีกครั้งในภายหลัง</p>
            </div>
        @else
            <div class="blog-posts">
                @foreach ($courses as $course)
                    <article class="blog-card" onclick="window.location.href='{{ route('classroom.show', $course->id) }}'">
                        {{-- แสดงรูปภาพปก --}}
                        @if ($course->cover_image)
                            <img src="{{$course->cover_image}}" alt="Course Cover: {{ $course->title }}" class="blog-card-image">
                        @else
                            <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-500 text-center text-lg">
                                ไม่มีรูปภาพ
                            </div>
                        @endif
                        
                        <div class="blog-card-content">
                            {{-- <span class="blog-card-category" style="background: #4299e1;">
                                {{ number_format($course->price, 0) }}฿
                            </span> --}}
                            <h3 class="blog-card-title">{{ $course->title }}</h3>
                            
                            {{-- ใช้คลาส Tailwind เพื่อจำกัดข้อความ --}}
                            <p class="blog-card-excerpt line-clamp-3">{{ $course->description }}</p>
                            
                            <div class="blog-card-meta">
                                <span class="blog-card-date">
                                    {{ $course->created_at->format('M d, Y') }}
                                </span>
                                <span class="blog-card-author">
                                    {{-- แสดงรูปภาพโปรไฟล์ครู --}}
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($course->teacher->name) }}&color=7F9CF5&background=EBF4FF" 
                                         alt="{{ $course->teacher->name }}" 
                                         class="author-avatar">
                                    {{ $course->teacher->name }}
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-app-layout>

{{-- ส่วนของ CSS ยังเหมือนเดิม --}}
<style>
    .blog-section {
        padding: 4rem 2rem;
    }
    .blog-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .blog-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }
    .blog-subtitle {
        font-size: 1.2rem;
        color: #4a5568;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }
    .blog-posts {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }
    .blog-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
    .blog-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .blog-card-content {
        padding: 1.5rem;
    }
    .blog-card-category {
        display: inline-block;
        background: #4299e1;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    .blog-card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    .blog-card-excerpt {
        color: #4a5568;
        margin-bottom: 1.25rem;
        line-height: 1.6;
    }
    .blog-card-meta {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #718096;
    }
    .blog-card-date {
        margin-right: 1rem;
    }
    .blog-card-author {
        display: flex;
        align-items: center;
    }
    .author-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-right: 0.5rem;
        object-fit: cover;
    }
    @media (max-width: 768px) {
        .blog-section {
            padding: 3rem 1.5rem;
        }
        .blog-title {
            font-size: 2rem;
        }
        .blog-subtitle {
            font-size: 1.1rem;
        }
        .blog-posts {
            grid-template-columns: 1fr;
        }
    }
</style>