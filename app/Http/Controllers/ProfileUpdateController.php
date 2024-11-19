<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateController extends Controller
{
    public function profile() {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function validateProfileForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'username' => [
                Rule::unique('users')->ignore($id),
            ],
            'email' => 'email|regex:/^.+@.+\..+$/i',
            'old_password' => [
                'nullable',
                'min:8',
                function ($attribute, $value, $fail) use ($request) {
                    $user = User::find($request->user()->id);
                    if (!Hash::check($value, $user->password)) {
                        $fail('The old password is incorrect.');
                    }
                },
                'required_with:password',
            ],
            'password' => 'nullable|confirmed|min:8|required_with:old_password',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function updateProfile(Request $request)
    {
        $current_user = Auth::user();
        $user = User::findOrFail($current_user->id);
    
        if ($this->shouldUpdateUserProfile($user, $request)) {
            $this->updateUserProfile($user, $request);
    
            return redirect()
                ->route('profile')
                ->with('success', 'Profile Updated Successfully!');
        } else {
            return redirect()
                ->route('profile')
                ->with('failed', 'Fill Up a Field!');
        }
    }

    private function shouldUpdateUserProfile(User $user, Request $request)
    {
        return (
            $request->input('name') !== $user->name ||
            $request->input('username') !== $user->username ||
            $request->input('email') !== $user->email ||
            $request->filled('password') ||
            $request->hasFile('profile_picture')
        );
    }

    private function updateUserProfile(User $user, Request $request)
    {
        $user->name = $request->filled('name') ? $request->name : $user->name;
        $user->username = $request->filled('username') ? $request->username : $user->username;
        $user->email = $request->filled('email') ? $request->email : $user->email;
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        if ($request->hasFile('profile_picture')) {
            $this->updateProfilePicture($user, $request->file('profile_picture'));
        }
    
        $user->updated_at = now('Asia/Manila');
        $user->save();
    
        $this->logUpdateProfile($user);
    }

    private function updateProfilePicture(User $user, UploadedFile $file)
    {
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $level = strtolower($user->level);
        $filePath = "uploads/profile/$level/$fileName";
        $file->move("uploads/profile/$level", $fileName);
    
        if ($user->profile_picture != "uploads/profile/$level/default_$level.jpg") {
            unlink(public_path($user->profile_picture));
        }
    
        $user->profile_picture = $filePath;
    }

    private function logUpdateProfile(User $user)
    {
        $logType = 'Edit Profile';
        $logUser = $user->username;
    
        Logs::create([
            'type' => $logType,
            'user' => $logUser,
            'subject' => 'Edit Profile Success',
            'message' => "$logUser has successfully edited profile.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }    
}
