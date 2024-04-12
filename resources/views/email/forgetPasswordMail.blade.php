<h1>Forgot Password</h1> <br>

Hi {{ $username }}, <br><br>

You can reset password from below link: <br>
<a href="{{ route('resetPasswordForm', ['token' => $token, 'username' => $username]) }}">Reset Password</a>