<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // 1. ตารางตรวจสอบ Audit Logs (PDPA)
        $query = DB::table('audit_logs');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('target_code', 'like', "%{$search}%")
                  ->orWhere('target_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        // 2. ไฟล์ประวัติการจราจรคอมพิวเตอร์ (Traffic Logs)
        $logDir = storage_path('logs');
        $trafficFiles = [];
        
        if (File::exists($logDir)) {
            $files = glob($logDir . '/traffic-*.log');
            if ($files) {
                // ดึงเฉพาะชื่อไฟล์ย่อย
                foreach ($files as $file) {
                    $trafficFiles[] = basename($file);
                }
                // เรียงลำดับจากล่าสุดขึ้นก่อน
                rsort($trafficFiles);
            }
        }

        // ดึงข้อมูลในไฟล์ที่เลือก
        $selectedFile = $request->input('file');
        
        // ถ้าไม่เลือกไฟล์ ให้ดึงไฟล์ของวันนี้เป็นค่าเริ่มต้น (หากมี) หรือไฟล์ล่าสุด
        if (empty($selectedFile) && !empty($trafficFiles)) {
            $selectedFile = $trafficFiles[0];
        }

        $logLines = [];
        $selectedFilePath = null;

        if (!empty($selectedFile)) {
            // ป้องกัน Directory Traversal ด้วยการเอาเฉพาะชื่อไฟล์ลบจุดออก
            $safeFileName = basename($selectedFile);
            $selectedFilePath = storage_path('logs/' . $safeFileName);

            if (File::exists($selectedFilePath)) {
                // อ่านข้อมูลจากหลังขึ้นมาหน้า (เฉพาะ 500 บรรทัดล่าสุด)
                $allLines = file($selectedFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if ($allLines) {
                    if (count($allLines) > 500) {
                        $logLines = array_slice($allLines, -500);
                    } else {
                        $logLines = $allLines;
                    }
                    $logLines = array_reverse($logLines); // ให้บรรทัดล่าสุดแสดงอยู่แถวบนสุด
                }
            } else {
                $selectedFile = null;
            }
        }

        return view('admin.audit_logs', compact('auditLogs', 'trafficFiles', 'selectedFile', 'logLines'));
    }
}
