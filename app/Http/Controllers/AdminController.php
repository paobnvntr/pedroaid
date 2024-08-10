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
    // display admin list
    public function index()
    {
        $admin = User::where('level', 'admin')->where('is_active', true)->orderBy('created_at', 'ASC')->get();
        return view('admin.index', compact('admin'));
    }

    // redirect to add admin page
    public function addAdmin() 
    {
        return view('admin.addAdmin');
    }

    // validate add admin form
    public function validateAddAdminForm(Request $request) {
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|regex:/^.+@.+\..+$/i',
            'password' => 'required|confirmed|min:8',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    // save admin
    public function saveAdmin(Request $request)
    {
        $filePath = $this->uploadProfilePicture($request);
    
        $adminData = [
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => trim(Hash::make($request->password)),
            'level' => 'Admin',
            'profile_picture' => $filePath,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];
    
        $createAdmin = User::create($adminData);
    
        $user = Auth::user()->username;
    
        if ($createAdmin) {
            $this->logAddAdminSuccess($user, $request->username);
            return redirect()->route('admin')->with('success', 'Admin Added Successfully!');
        } else {
            $this->logAddAdminFailed($user, $request->username);
            return redirect()->route('admin')->with('failed', 'Failed to Add Admin!');
        }
    }

    // upload profile picture
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
    
    // log the success add admin
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
    
    // log the failed add admin
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

    // redirect to admin edit page
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
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }


    // update admin details
    public function updateAdmin(Request $request, string $id)
    {
        $admin = User::findOrFail($id);

        // Update user details
        if ($this->shouldUpdateUserDetails($admin, $request)) {
            $this->updateUserDetails($admin, $request);

            // Log the update
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
        $filePath = 'uploads/profile/admin/' . $fileName;
        $file->move('uploads/profile/admin', $fileName);

        if ($user->profile_picture != 'uploads/profile/admin/default_admin.jpg') {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = $filePath;
    }

    // log the update
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

    // delete admin account
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
    
    // Function to create admin delete log
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

