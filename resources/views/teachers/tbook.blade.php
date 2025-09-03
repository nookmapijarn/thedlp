<x-teachers-layout>
    <div class="p-4 sm:ml-64 mt-20">
        <h1>เพิ่มหนังสือเรียนใหม่</h1>

        @if (session('success'))
            <div style="color: green;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="color: red;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="title">ชื่อหนังสือ:</label>
                <input type="text" name="title" id="title" required>
                @error('title')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="category_id">หมวดหมู่:</label>
<select name="category_id" id="category_id" required>
    <option value="" disabled selected>-- เลือกหมวดหมู่ --</option>

    @foreach ($categories as $category)
        <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
    @endforeach

</select>
                @error('category_id')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="cover_image">รูปปกหนังสือ:</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" required>
                @error('cover_image')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="book_file">ไฟล์ PDF:</label>
                <input type="file" name="book_file" id="book_file" accept=".pdf" required>
                @error('book_file')
                    <div style="color: red;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">บันทึก</button>
        </form>
    </div>
</x-teachers-layout>
