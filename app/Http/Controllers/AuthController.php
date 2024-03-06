<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
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
        Validator::make($request->all(), [
            '_token' => 'required',
            'username' => 'required',
            'password' => 'required'
        ])->validate();
        

        if (!Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
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

        Logs::create([
            'type' => 'Login',
            'user' => $request->username,
            'subject' => 'Login Success',
            'message' => $request->username . ' has successfully logged in.'
        ]);

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
}
