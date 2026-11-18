<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function updateAvatar(Request $request)
    {
        // ตรวจสอบว่ามีข้อมูลรูปภาพหรือไม่
        if ($request->has('cropped_image')) {
            // รับข้อมูล base64 ของรูปที่ถูก crop
            $imageData = $request->input('cropped_image');
        } elseif ($request->has('original_image')) {
            // รับข้อมูล base64 ของรูปต้นฉบับ
            $imageData = $request->input('original_image');
        } else {
            return redirect()->back()->withErrors(['message' => 'No image provided.']);
        }
    
        // ลบข้อมูล prefix base64 ออก
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);
    
        // สร้างชื่อไฟล์
        $imageName = auth()->user()->student_id . '.png';
    
        // บันทึกไฟล์ลง storage/app/public/images/avatar พร้อมตั้งสิทธิ์ 0755 / 0644 ป้องกัน 403 บน Host
        $storageDir = storage_path('app/public/images/avatar');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        @chmod(storage_path('app/public'), 0755);
        @chmod(storage_path('app/public/images'), 0755);
        @chmod($storageDir, 0755);

        $storagePath = $storageDir . '/' . $imageName;
        file_put_contents($storagePath, $imageData);
        @chmod($storagePath, 0644);

        $publicStorageRoot = public_path('storage');
        if (file_exists($publicStorageRoot) && !is_link($publicStorageRoot)) {
            $publicDir = public_path('storage/images/avatar');
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            @chmod($publicStorageRoot, 0755);
            @chmod(public_path('storage/images'), 0755);
            @chmod($publicDir, 0755);
            $publicPath = $publicDir . '/' . $imageName;
            file_put_contents($publicPath, $imageData);
            @chmod($publicPath, 0644);
        }

        $publicUrl = asset('storage/images/avatar/' . $imageName); // URL สำหรับการเข้าถึงไฟล์

        // อัปเดต URL ของ avatar ในฐานข้อมูล
        auth()->user()->update(['avatar' => $publicUrl]); 
    
        return redirect()->back()->with('status', 'อัพโหลดรูปภาพสำเร็จ.');
    }


      
    
}
