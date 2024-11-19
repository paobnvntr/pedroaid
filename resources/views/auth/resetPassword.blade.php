<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="icon" href="{{ asset('images/PedroAID-Logo.png') }}" type="image/png">

	<title>Reset Password</title>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"
		type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/pedro-aid.css">
</head>

<body class="bg-gradient-primary login-content">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xl-10 col-lg-12 col-md-9">
				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<div class="row">
							<div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
							<div class="col-lg-6">
								<div class="p-5">
									<div class="text-center">
										<h3 class="text-gray-900 font-weight-bold mb-4">Reset Password</h3>
										<hr>
									</div>

									<form action="{{ route('resetPassword') }}" method="POST" class="user"
										id="loginForm">
										@csrf
										<input type="hidden" name="token" value="{{ $token }}">

										<div class="form-group">
											<label for="username">Username: <span
													class="text-gray-900">{{ $username }}</span></label>
											<input type="hidden" name="username" value="{{ $username }}">
										</div>

										<div class="form-group">
											<label for="password">New Password:</label>
											<div class="password-toggle-container">
												<input name="password" type="password"
													class="form-control form-control-user @error('password')is-invalid @enderror"
													id="password" value="{{ old('password') }}">
												<span class="password-toggle-btn"
													onclick="togglePasswordVisibility('password')">Show</span>
												@error('password')
													<span class="invalid-feedback">{{ $message }}</span>
												@enderror
											</div>
										</div>

										<div class="form-group">
											<label for="password_confirmation">Confirm Password:</label>
											<div class="password-toggle-container">
												<input name="password_confirmation" type="password"
													class="form-control form-control-user @error('password_confirmation')is-invalid @enderror"
													id="password_confirmation"
													value="{{ old('password_confirmation') }}">
												<span class="password-toggle-btn"
													onclick="togglePasswordVisibility('password_confirmation')">Show</span>
												@error('password_confirmation')
													<span class="invalid-feedback">{{ $message }}</span>
												@enderror
											</div>
										</div>

										<button type="submit" class="btn btn-primary btn-block btn-user btn-login">Reset
											Password</button>
									</form>
									<hr>

									@if(Session::has('success'))
										<div class="alert alert-success" role="alert">
											{{ Session::get('success') }}
										</div>
									@endif

									@if(Session::has('error'))
										<div class="alert alert-danger" role="alert">
											{{ Session::get('error') }}
										</div>
									@endif

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
		.password-toggle-container {
			position: relative;
		}

		.password-toggle-btn {
			font-size: 13px;
			position: absolute;
			top: 50%;
			right: 10px;
			transform: translateY(-50%);
			cursor: pointer;
		}
	</style>

	<script>
		function togglePasswordVisibility(fieldId) {
			var passwordInput = document.getElementById(fieldId);
			var passwordToggleBtn = passwordInput.parentNode.querySelector(".password-toggle-btn");

			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				passwordToggleBtn.textContent = "Hide";
			} else {
				passwordInput.type = "password";
				passwordToggleBtn.textContent = "Show";
			}
		}

		function submitForgotPasswordForm() {
			document.getElementById("loginForm").action = "{{ route('forgotPassword') }}";
			document.getElementById("loginForm").submit();
		}
	</script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
	<script src="../../js/pedro-aid.js"></script>
</body>

</html>