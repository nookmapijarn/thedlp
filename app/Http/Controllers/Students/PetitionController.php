<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use App\Models\User;
use App\Models\HelpNotification;
use App\Models\HelpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetitionController extends Controller
{
    protected $lavel;
    protected $std_code;

    /**
     * Show student petition dashboard page.
     */
    public function index()
    {
        $id = auth()->user()->student_id;
        $this->lavel = str_split($id, 1)[3];
        $this->std_code = DB::table("student{$this->lavel}")
            ->where('ID', $id)
            ->select('STD_CODE')
            ->groupBy('STD_CODE')
            ->value('STD_CODE');

        // Fetch all petitions submitted by this student
        $petitions = Petition::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('students.petition', compact('petitions'));
    }

    /**
     * Store new student petition request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('uploads/petitions', 'public');
        }

        $petition = Petition::create([
            'user_id' => auth()->id(),
            'student_id' => auth()->user()->student_id ?? 'N/A',
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description,
            'file_path' => $filePath,
            'status' => 'pending',
        ]);

        // Send a notification/alert message to admins (role 4) or help notification
        try {
            // Notify via help center system if possible, or create logs
            // Since we want to notify admins, let's create a notification log if they exist
            $admins = User::where('role', 4)->get();
            foreach ($admins as $admin) {
                // To avoid integrity constraint on help_request_id, we can create a dummy or skip
                // We'll write a log or use DB notifications if there's any general notification.
                // Let's check if we can insert directly into help_notifications with a dummy or nullable.
                // Since help_request_id is not nullable, we won't insert to help_notifications unless we have to.
                // Instead, the Admin Sidebar badge acts as the direct notification.
            }
        } catch (\Exception $e) {
            logger()->error("Petition notification dispatch failed: " . $e->getMessage());
        }

        return redirect()->back()->with('success', 'ยื่นคำร้องเสร็จเรียบร้อยแล้ว! ระบบกำลังนำเสนอเพื่ออนุมัติ');
    }

    /**
     * Show admin petition management list.
     */
    public function adminIndex(Request $request)
    {
        $petitions = Petition::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.petitions.index', compact('petitions'));
    }

    /**
     * Update admin comment and petition status.
     */
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,in_progress,approved,rejected',
            'admin_comment' => 'nullable|string',
        ]);

        $petition = Petition::findOrFail($id);
        $petition->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        return redirect()->back()->with('success', 'บันทึกการพิจารณาคำร้องและอัปเดตสถานะเรียบร้อยแล้ว!');
    }
}
