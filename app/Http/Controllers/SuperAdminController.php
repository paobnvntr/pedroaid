<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    // display super admin list
    public function index()
    {
        $user = Auth::user()->username;
        $super_admin = User::where('level', 'super admin')->where('username', '!=', $user)->orderBy('created_at', 'ASC')->get();
        return view('super-admin.index', compact('super_admin'));
    }

    // redirect to add super admin form
    public function addSuperAdmin() 
    {
        return view('super-admin.addSuperAdmin');
    }

    // validate super admin form
    public function validateAddSuperAdminForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    // store super admin form
    public function saveSuperAdmin(Request $request)
    {
        $filePath = $this->uploadProfilePicture($request);

        $superAdminData = [
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => trim(Hash::make($request->password)),
            'level' => 'Super Admin',
            'profile_picture' => $filePath,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createSuperAdmin = User::create($superAdminData);

        $user = Auth::user()->username;

        if ($createSuperAdmin) {
            $this->logAddSuperAdminSuccess($user, $request->username);
            return redirect()->route('super-admin')->with('success', 'Super Admin Added Successfully!');
        } else {
            $this->logAddSuperAdminFailed($user, $request->username);
            return redirect()->route('super-admin')->with('failed', 'Failed to Add Super Admin!');
        }
    }

    // upload profile picture
    private function uploadProfilePicture(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $originalFileName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/profile/superadmin/' . $fileName;
            $file->move('uploads/profile/superadmin/', $fileName);
        } else {
            $filePath = 'uploads/profile/superadmin/default_superadmin.jpg';
        }

        return $filePath;
    }

    // log the success add super admin
    private function logAddSuperAdminSuccess($user, $username)
    {
        Logs::create([
            'type' => 'Add Super Admin',
            'user' => $user,
            'subject' => 'Add Super Admin Success',
            'message' => "$user has successfully added $username as a super admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    // log the failed add super admin
    private function logAddSuperAdminFailed($user, $username)
    {
        Logs::create([
            'type' => 'Add Super Admin',
            'user' => $user,
            'subject' => 'Add Super Admin Failed',
            'message' => "$user has unsuccessfully added $username as a super admin.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    // redirect to edit super admin page
    public function editSuperAdmin(string $id)
    {
        $super_admin = User::where('level', 'super admin')->findorFail($id);
        return view('super-admin.editSuperAdmin', compact('super_admin'));
    }

    // validate super admin form
    public function validateEditSuperAdminForm(Request $request, string $id) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'username' => [
                Rule::unique('users')->ignore($id),
            ],
            'email' => 'email',
            'password' => 'nullable|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    // update super admin details
    public function updateSuperAdmin(Request $request, string $id)
    {
        $superAdmin = User::findOrFail($id);

        // Update user details
        if ($this->shouldUpdateUserDetails($superAdmin, $request)) {
            $this->updateUserDetails($superAdmin, $request);

            // Log the update
            $this->logUpdate($superAdmin);

            return redirect()
                ->route('super-admin.editSuperAdmin', $superAdmin->id)
                ->with('success', 'Super Admin Details Updated Successfully!');
        } else {
            return redirect()
                ->route('super-admin.editSuperAdmin', $superAdmin->id)
                ->with('failed', 'Fill Up a Field!');
        }
    }

    // check if user details should be updated
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

    // update user details
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

    // update profile picture
    private function updateProfilePicture(User $user, UploadedFile $file)
    {
        $originalFileName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = 'uploads/profile/superadmin/' . $fileName;
        $file->move('uploads/profile/superadmin', $fileName);

        if ($user->profile_picture != 'uploads/profile/superadmin/default_superadmin.jpg') {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = $filePath;
    }

    // log the update
    private function logUpdate(User $user)
    {
        $logType = 'Edit Super Admin';
        $logUser = Auth::user()->username;

        if ($user->wasChanged()) {
            $logSubject = 'Edit Super Admin Success';
            $logMessage = "$logUser has successfully edited super admin details of $user->username.";
        } else {
            $logSubject = 'Edit Super Admin Failed';
            $logMessage = "$logUser has unsuccessfully edited super admin details of $user->username.";
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

    // redirect to delete super admin page
    public function deleteSuperAdmin(string $id)
    {
        $super_admin = User::findOrFail($id);
        return view('super-admin.deleteSuperAdmin', compact('super_admin'));
    }

    // delete super admin account
    public function destroySuperAdmin(string $id)
    {
        try {
            DB::beginTransaction();
    
            $superAdmin = User::findOrFail($id);
            $user = Auth::user()->username;
    
            $this->createSuperAdminDeleteLog($user, $superAdmin);
    
            if ($superAdmin->profile_picture !== 'uploads/profile/superadmin/default_superadmin.jpg') {
                $filePath = public_path($superAdmin->profile_picture);
    
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
    
            $superAdmin->delete();
    
            DB::commit();
    
            return redirect()->route('super-admin')->with('success', 'Super Admin Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            $this->createSuperAdminDeleteLog($user, $superAdmin, 'Failed to delete Super Admin', $e->getMessage());
    
            return redirect()->route('super-admin')->with('failed', 'Failed to delete Super Admin!');
        }
    }
    
    // Function to create super admin delete log
    private function createSuperAdminDeleteLog($user, $superAdmin, $subject = 'Delete Super Admin Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Super Admin',
            'user' => $user,
            'subject' => $subject,
            'message' => "$superAdmin->username has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }
    
        Logs::create($logData);
    }    
}
