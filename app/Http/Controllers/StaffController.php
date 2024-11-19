<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('level', 'staff')->where('is_active', true)->orderBy('created_at', 'ASC')->get();
        return view('staff.index', compact('staff'));
    }

    public function addStaff() 
    {
        return view('staff.addStaff');
    }

    public function validateAddStaffForm(Request $request) {
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
            'transaction_level' => 'required',
            'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()]);
        }else {
            return response()->json(['message' => 'Validation passed']);
        }
    }

    public function saveStaff(Request $request)
    {
        $filePath = $this->uploadProfilePicture($request);

        $password = "24AID_" . trim($request->username);

        $staffData = [
            'name' => trim($request->name),
            'username' => trim($request->username),
            'email' => trim($request->email),
            'password' => Hash::make($password),
            'transaction_level' => $request->transaction_level,
            'level' => 'Staff',
            'profile_picture' => $filePath,
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        $createStaff = User::create($staffData);

        $user = Auth::user()->username;

        if ($createStaff) {
            $this->logAddStaffSuccess($user, $request->username);
            session()->flash('password', $password);
            return redirect()->route('staff')->with('success', 'Staff Added Successfully!<br><br>Password: <strong>' . $password . '</strong>');
        } else {
            $this->logAddStaffFailed($user, $request->username);
            return redirect()->route('staff')->with('failed', 'Failed to Add Staff!');
        }
    }

    private function uploadProfilePicture(Request $request)
    {
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $originalFileName = $file->getClientOriginalName();
            $fileName = time() . '_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/profile/staff/' . $fileName;
            $file->move('uploads/profile/staff', $fileName);
        } else {
            $filePath = 'uploads/profile/staff/default_staff.jpg';
        }

        return $filePath;
    }

    private function logAddStaffSuccess($user, $username)
    {
        Logs::create([
            'type' => 'Add Staff',
            'user' => $user,
            'subject' => 'Add Staff Success',
            'message' => "$user has successfully added $username as a staff.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    private function logAddStaffFailed($user, $username)
    {
        Logs::create([
            'type' => 'Add Staff',
            'user' => $user,
            'subject' => 'Add Staff Failed',
            'message' => "$user has unsuccessfully added $username as a staff.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ]);
    }

    public function editStaff(string $id)
    {
        $staff = User::where('level', 'staff')->findorFail($id);
        return view('staff.editStaff', compact('staff'));
    }

    public function validateEditStaffForm(Request $request, string $id) {
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

    public function updateStaff(Request $request, string $id)
    {
        $staff = User::findOrFail($id);

        if ($this->shouldUpdateUserDetails($staff, $request)) {
            $this->updateUserDetails($staff, $request);

            $this->logUpdate($staff);

            return redirect()
                ->route('staff.editStaff', $staff->id)
                ->with('success', 'Staff Details Updated Successfully!');
        } else {
            return redirect()
                ->route('staff.editStaff', $staff->id)
                ->with('failed', 'Fill Up a Field!');
        }
    }

    private function shouldUpdateUserDetails(User $user, Request $request)
    {
        return (
            $request->input('name') !== $user->name ||
            $request->input('username') !== $user->username ||
            $request->input('email') !== $user->email ||
            $request->input('transaction_level') !== $user->transaction_level ||
            $request->filled('password') ||
            $request->hasFile('profile_picture')
        );
    }

    private function updateUserDetails(User $user, Request $request)
    {
        $user->name = $request->filled('name') ? $request->name : $user->name;
        $user->username = $request->filled('username') ? $request->username : $user->username;
        $user->email = $request->filled('email') ? $request->email : $user->email;
        $user->transaction_level = $request->filled('transaction_level') ? $request->transaction_level : $user->transaction_level;

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
        $filePath = "uploads/profile/staff/$fileName";
        $file->move("uploads/profile/staff", $fileName);

        if ($user->profile_picture != "uploads/profile/staff/default_staff.jpg") {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = $filePath;
    }

    private function logUpdate(User $user)
    {
        $logType = 'Edit Staff';
        $logUser = Auth::user()->username;

        if ($user->wasChanged()) {
            $logSubject = 'Edit Staff Success';
            $logMessage = "$logUser has successfully edited staff details of $user->username.";
        } else {
            $logSubject = 'Edit Staff Failed';
            $logMessage = "$logUser has unsuccessfully edited staff details of $user->username.";
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

    public function destroyStaff(string $id)
    {
        try {
            DB::beginTransaction();

            $staff = User::findOrFail($id);
            $user = Auth::user()->username;

            $this->createStaffDeleteLog($user, $staff);

            $staff->is_active = false;
            $staff->updated_at = now('Asia/Manila');

            $staff->update();

            DB::commit();

            return redirect()->route('staff')->with('success', 'Staff Deleted Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->createStaffDeleteLog($user, $staff, 'Failed to delete Staff', $e->getMessage());

            return redirect()->route('staff')->with('failed', 'Failed to delete Staff!');
        }
    }

    private function createStaffDeleteLog($user, $staff, $subject = 'Delete Staff Success', $errorMessage = null)
    {
        $logData = [
            'type' => 'Delete Staff',
            'user' => $user,
            'subject' => $subject,
            'message' => "$staff->username has been " . strtolower($subject) . " by $user.",
            'created_at' => now('Asia/Manila'),
            'updated_at' => now('Asia/Manila'),
        ];

        if ($errorMessage) {
            $logData['message'] .= ' Error: ' . $errorMessage;
        }

        Logs::create($logData);
    }
}