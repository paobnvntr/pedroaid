<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Logs;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }
    public function loginAction(Request $request)
    {
        // Validate the request
        Validator::make($request->all(), [
            '_token' => 'required',
            'username' => 'required',
            'password' => 'required'
        ])->validate();

        // Retrieve the user based on the username and check if they are active
        $user = User::where('username', $request->username)
            ->where('is_active', true)
            ->first();

        // Check if user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            Logs::create([
                'type' => 'Login',
                'user' => $request->username,
                'subject' => 'Login Failed',
                'message' => $request->username . ' has unsuccessfully logged in.',
                'created_at' => now('Asia/Manila'),
                'updated_at'=> now('Asia/Manila'),
            ]);
        
            throw ValidationException::withMessages([
                'username' => trans('auth.failed')
            ]);
        }

        // Authenticate the user
        Auth::login($user, $request->boolean('remember'));

        // Log the successful login
        Logs::create([
            'type' => 'Login',
            'user' => $request->username,
            'subject' => 'Login Success',
            'message' => $request->username . ' has successfully logged in.',
            'created_at' => now('Asia/Manila'),
            'updated_at'=> now('Asia/Manila'),
        ]);

        // Check if the password is still the default password
        $defaultPassword = '24AID_' . $user->username;
        if (Hash::check($defaultPassword, $user->password)) {
            // If the password is the default one, redirect to the change password form
            return redirect()->route('changeDefaultPasswordForm', ['token' => csrf_token(), 'username' => $user->username]);
        }

        // Regenerate session and redirect to dashboard if password is not default
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $username = Auth::user()->username;
        Logs::create([
            'type' => 'Logout',
            'user' => $username,
            'subject' => 'Logout Success',
            'message' => $username . ' has successfully logged out.',
            'created_at' => now('Asia/Manila'),
            'updated_at'=> now('Asia/Manila'),
        ]);
       
        
        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
 
        return redirect('/login')->with('success', 'Logged Out Successfully!');
    }

    public function changeDefaultPasswordForm($token, $username) { 
        return view('auth.changeDefaultPassword', ['token' => $token, 'username' => $username]);
    }

    public function changeDefaultPassword(Request $request)
    {
        $request->validate([
           'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->where('is_active', true)->first();
                    if (!$user) {
                        $fail('No account found with the ' . $attribute . ' provided.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::where('username', $request->username)->update(['password' => Hash::make($request->password)]);

        return redirect('/login')->with('success', 'Your password has been changed!');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->where('is_active', true)->first();
                    if (!$user) {
                        $fail('No account found with the ' . $attribute . ' provided.');
                    }
                },
            ],
        ]);

        $token = Str::random(64);

        $email = User::where('username', $request->username)->value('email');

        DB::table('password_reset_tokens')->insert([
            'email' => $email, 
            'token' => $token, 
            'created_at' => now('Asia/Manila')
        ]);

        Mail::send('email.forgetPasswordMail', ['token' => $token, 'username' => $request->username], function($message) use($email){
            $message->to($email);
            $message->subject('PedroAID - Reset Password');
        });

        return back()->with('success', 'Mail Sent Successfully!');
    }

    public function resetPasswordForm($token, $username) { 
        return view('auth.resetPassword', ['token' => $token, 'username' => $username]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::where('username', $value)->where('is_active', true)->first();
                    if (!$user) {
                        $fail('No account found with the ' . $attribute . ' provided.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed'
        ]);

        $email = User::where('username', $request->username)->value('email');

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                              'email' => $email, 
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        DB::table('password_reset_tokens')->where('token', $request->token)->delete();

        User::where('username', $request->username)->update(['password' => Hash::make($request->password)]);

        return redirect('/login')->with('success', 'Your password has been changed!');
    }
}
