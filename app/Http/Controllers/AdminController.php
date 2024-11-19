<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Auth;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admin = User::where('level', 'admin')->where('is_active', true)->orderBy('created_at', 'ASC')->get();
        return view('admin.index', compact('admin'));
    }

    public function addAdmin() 
    {
        return view('admin.addAdmin');
    }

    public function validateAddAdminForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->where('is_active', true)->first();
                    if ($user) {
                        $fail('The ' . $attribute . ' has already been taken by an active user.');
                    }
                },
            ],
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveAdmin(Request $request)
    {
        $filePath = $this->uploadProfilePicture($request);
        $password = "24AID_" . trim($request->username);
        $adminData = [
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => trim(Hash::make($password)),
            'level' => 'Admin',
            'profile_picture' => $filePath,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
        $createAdmin = User::create($adminData);
        $user = Auth::user()->username;

        if ($createAdmin) {
            $this->logAddAdminSuccess($user, $request->username);
            session()->flash('password', $password);
            return redirect()->route('admin')->with('success', 'Admin Added Successfully!<br><br>Password: <strong>' . $password . '</strong>');
        } else {
            $this->logAddAdminFailed($user, $request->username);
            return redirect()->route('admin')->with('failed', 'Failed to Add Admin!');
        }
    }

    private function uploadProfilePicture(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $originalFileName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/profile/admin/' . $fileName;
            $file->move('uploads/profile/admin/', $fileName);
        } else {
            $filePath = 'uploads/profile/admin/default_admin.jpg';
        }
        return $filePath;
    }

    private function logAddAdminSuccess($user, $username)
    {
        Logs::create([
            'type' => 'Add Admin',
            'user' => $user,
            'subject' => 'Add Admin Success',
            'message' => "$user has successfully added $username as an admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logAddAdminFailed($user, $username)
    {
        Logs::create([
            'type' => 'Add Admin',
            'user' => $user,
            'subject' => 'Add Admin Failed',
            'message' => "$user has unsuccessfully added $username as an admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function editAdmin(string $id)
    {
        $admin = User::where('level', 'admin')->findorFail($id);
        return view('admin.editAdmin', compact('admin'));
    }

    public function validateEditAdminForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'username' => [
                Rule::unique('users')->ignore($id),
            ],
            'email' => 'email|regex:/^.+@.+\..+$/i',
            'password' => 'nullable|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        } else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function updateAdmin(Request $request, string $id)
    {
        $admin = User::findOrFail($id);

        if ($this->shouldUpdateUserDetails($admin, $request)) {
            $this->updateUserDetails($admin, $request);
            $this->logUpdate($admin);
            return redirect()
                ->route('admin.editAdmin', $admin->id)
                ->with('success', 'Admin Details Updated Successfully!');
        } else {
            return redirect()
                ->route('admin.editAdmin', $admin->id)
                ->with('failed', 'Fill Up a Field!');
        }
    }

    private function shouldUpdateUserDetails(User $user, Request $request)
    {
        return (
            $request->input('name') !== $user->name ||
            $request->input('username') !== $user->username ||
            $request->input('email') !== $user->email ||
            $request->filled('password') ||
            $request->hasFile('profile_picture')
        );
    }

    private function updateUserDetails(User $user, Request $request)
    {
        $user->name = $request->filled('name') ? $request->name : $user->name;
        $user->username = $request->filled('username') ? $request->username : $user->username;
        $user->email = $request->filled('email') ? $request->email : $user->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $this->updateProfilePicture($user, $request->file('profile_picture'));
        }

        $user->updated_at = now('Asia/Manila');
        $user->update();
    }

    private function updateProfilePicture(User $user, UploadedFile $file)
    {
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/profile/admin/' . $fileName;
        $file->move('uploads/profile/admin', $fileName);

        if ($user->profile_picture != 'uploads/profile/admin/default_admin.jpg') {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = $filePath;
    }

    private function logUpdate(User $user)
    {
        $logType = 'Edit Admin';
        $logUser = Auth::user()->username;

        if ($user->wasChanged()) {
            $logSubject = 'Edit Admin Success';
            $logMessage = "$logUser has successfully edited admin details of $user->username.";
        } else {
            $logSubject = 'Edit Admin Failed';
            $logMessage = "$logUser has unsuccessfully edited admin details of $user->username.";
        }

        Logs::create([
            'type' => $logType,
            'user' => $logUser,
            'subject' => $logSubject,
            'message' => $logMessage,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function destroyAdmin(string $id)
    {
        try {
            DB::beginTransaction();
            $admin = User::findOrFail($id);
            $user = Auth::user()->username;
            $this->createAdminDeleteLog($user, $admin);
            $admin->is_active = false;
            $admin->updated_at = now('Asia/Manila');
            $admin->update();
            DB::commit();
            return redirect()->route('admin')->with('success', 'Admin Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->createAdminDeleteLog($user, $admin, 'Failed to delete Admin', $e->getMessage());
            return redirect()->route('admin')->with('failed', 'Failed to delete Admin!');
        }
    }

    private function createAdminDeleteLog($user, $admin, $subject = 'Delete Admin Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Admin',
            'user' => $user,
            'subject' => $subject,
            'message' => "$admin->username has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }

        Logs::create($logData);
    }
}