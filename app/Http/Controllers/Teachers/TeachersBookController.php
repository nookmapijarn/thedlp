<?php
namespace App\Http\Controllers\Teachers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeachersBookController extends Controller
{
    /**
     * แสดงรายการหนังสือทั้งหมด
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::with('category', 'files')->get();
        $categories = [
            ['id' => 1, 'name' => 'คณิตศาสตร์'],
            ['id' => 2, 'name' => 'วิทยาศาสตร์และเทคโนโลยี'],
            ['id' => 3, 'name' => 'ภาษาไทย'],
            ['id' => 4, 'name' => 'สังคมศึกษา'],
        ];
        
        return view('teachers.tbook', compact('books', 'categories'));
    }

    /**
     * แสดงฟอร์มสำหรับสร้างหนังสือใหม่
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // ตรวจสอบให้แน่ใจว่ามีการดึงข้อมูลหมวดหมู่จากฐานข้อมูล
        // และส่งไปยัง View ด้วย compact() หรือ with()
        $categories = Category::all();

        // วิธีที่ 1: ใช้ compact()
        return view('books.create', compact('categories'));

        // หรือวิธีที่ 2: ใช้ with()
        // return view('books.create')->with('categories', $categories);
    }

    /**
     * บันทึกหนังสือใหม่และไฟล์ที่เกี่ยวข้อง
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. ตรวจสอบข้อมูล (Validation)
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB
            'book_file' => 'required|mimes:pdf|max:51200', // 50MB
        ]);

        // 2. จัดการการอัปโหลดไฟล์
        // สร้างชื่อไฟล์ที่ไม่ซ้ำกัน
        $coverImagePath = $request->file('cover_image')->store('public/covers');
        $bookFilePath = $request->file('book_file')->store('public/books');

        // 3. บันทึกข้อมูลลงในฐานข้อมูล
        try {
            DB::beginTransaction();

            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'cover_image_path' => Storage::url($coverImagePath),
            ]);

            $file = new File([
                'file_name' => $request->file('book_file')->getClientOriginalName(),
                'file_path' => Storage::url($bookFilePath),
                'file_type' => 'pdf',
                'file_size' => $request->file('book_file')->getSize(),
            ]);

            $book->files()->save($file);

            DB::commit();

            return redirect()->route('books.index')->with('success', 'เพิ่มหนังสือเรียนสำเร็จ!');

        } catch (\Exception $e) {
            DB::rollback();
            // ลบไฟล์ที่อัปโหลดไปแล้วหากเกิดข้อผิดพลาดในการบันทึกข้อมูล
            Storage::delete($coverImagePath);
            Storage::delete($bookFilePath);

            return back()->with('error', 'เกิดข้อผิดพลาดในการเพิ่มหนังสือเรียน: ' . $e->getMessage());
        }
    }
}