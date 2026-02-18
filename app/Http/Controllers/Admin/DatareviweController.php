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
    $tableName = $request->input('table', 'users');

    // ตรวจสอบความปลอดภัย
    if (!Schema::hasTable($tableName)) {
        return back()->with('error', 'ไม่พบตารางที่ระบุ');
    }

    // 1. ดึงรายชื่อคอลัมน์ของตารางที่เลือกมาเสมอ
    $columns = Schema::getColumnListing($tableName);

    // 2. ถ้าเป็นการเรียกแบบ AJAX (DataTables ขอข้อมูล)
    if ($request->ajax()) {
        $query = DB::table($tableName);

        // การค้นหา (Search)
        if ($search = $request->input('search.value')) {
            $query->where(function($q) use ($columns, $search) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        }

        $totalFiltered = $query->count();
        
        // การเรียงลำดับ
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');
        if (isset($columns[$orderColumnIndex])) {
            $query->orderBy($columns[$orderColumnIndex], $orderDir);
        }

        // การตัดหน้า (Pagination)
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->skip($start)->take($length)->get();

        $totalData = DB::table($tableName)->count();

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
            "columns" => $columns // ส่งคอลัมน์กลับไปด้วยเพื่อเช็ค
        ]);
    }

    // 3. ถ้าโหลดหน้าปกติ (ไม่ใช่ AJAX) ให้ส่ง $columns ไปสร้าง <thead> ใน HTML
    return view('admin.datareview', compact('columns', 'tableName'));
}
}
