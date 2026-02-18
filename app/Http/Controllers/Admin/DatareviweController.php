<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatareviweController extends Controller
{
public function index(Request $request)
    {
        // 1. กำหนดชื่อตาราง (Default คือ users)
        $tableName = $request->input('table', 'users');

        // ตรวจสอบว่ามีตารางจริงไหม เพื่อความปลอดภัย
        if (!Schema::hasTable($tableName)) {
            return back()->with('error', 'Table not found');
        }

        $columns = Schema::getColumnListing($tableName);

        // 2. ถ้าเป็นการเรียกผ่าน AJAX (DataTables เรียกมา)
        if ($request->ajax()) {
            
            // เริ่มต้น Query
            $query = DB::table($tableName);
            
            // A. การค้นหา (Search)
            if ($search = $request->input('search.value')) {
                $query->where(function($q) use ($columns, $search) {
                    foreach ($columns as $column) {
                        // ค้นหาทุกคอลัมน์ (ใช้ orWhere)
                        $q->orWhere($column, 'LIKE', "%{$search}%");
                    }
                });
            }

            // นับจำนวนข้อมูลทั้งหมด (หลังกรอง)
            $totalFiltered = $query->count();

            // B. การเรียงลำดับ (Ordering)
            $orderColumnIndex = $request->input('order.0.column'); // Index ของคอลัมน์ที่กด sort
            $orderDir = $request->input('order.0.dir', 'asc'); // ทิศทาง asc/desc
            
            if ($orderColumnIndex !== null && isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDir);
            }

            // C. การตัดแบ่งหน้า (Pagination) -> หัวใจสำคัญของการลดโหลด
            $start = $request->input('start', 0); // เริ่มที่แถวไหน
            $length = $request->input('length', 10); // เอามาทีกี่แถว

            $data = $query->skip($start)->take($length)->get();

            // นับจำนวนข้อมูลทั้งหมดในตาราง (ก่อนกรอง)
            $totalData = DB::table($tableName)->count();

            // ส่ง JSON กลับไปให้ DataTables
            return response()->json([
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data // ส่งไปแค่ 10-20 แถว ไม่ใช่ทั้งหมด
            ]);
        }

        // 3. ถ้าเป็นการโหลดหน้าปกติ (ครั้งแรก) ให้ส่งแค่ชื่อคอลัมน์ไปสร้างหัวตาราง
        return view('admin.datareview', compact('columns', 'tableName'));
    }
}
