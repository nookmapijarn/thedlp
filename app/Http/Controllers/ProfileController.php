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
    
        // บันทึกไฟล์ลง storage (public disk)
        $path = 'images/avatar/' . $imageName; // เส้นทางสัมพันธ์ใน storage
        Storage::disk('public')->put($path, $imageData);
        $publicUrl = asset('storage/' . $path);

        // อัปเดต URL ของ avatar ในฐานข้อมูล
        auth()->user()->update(['avatar' => $path]); 
    
        return redirect()->back()->with('status', 'Avatar updated successfully. URL : '. $publicUrl);
    }


      
    
}
