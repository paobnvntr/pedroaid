@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('super-admin') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Add Super Admin</h1>
	</div>

	<div>
		<div class="p-5">
			<form action="{{ route('super-admin.saveSuperAdmin') }}" method="POST" enctype="multipart/form-data" class="user" id="createSuperAdminForm">
				@csrf
				<div class="form-group row">
					<div class="col-sm-6 mb-3 mb-sm-0">
						<input name="name" id="name" type="text"
							class="form-control form-control-user @error('name')is-invalid @enderror"
							id="exampleInputName" placeholder="Super Admin Name" value="{{ old('name') }}">
						@error('name')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>

					<div class="col-sm-6">
						<input name="username" id="username" type="text"
							class="form-control form-control-user @error('username')is-invalid @enderror"
							id="exampleInputUsername" placeholder="Super Admin Username" value="{{ old('username') }}">
						@error('username')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<input name="email" id="email" type="email"
							class="form-control form-control-user @error('email')is-invalid @enderror"
							id="exampleInputEmail" placeholder="Email Address" value="{{ old('email') }}">
						@error('email')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>

					<div class="col-sm-6 d-flex align-items-center justify-content-center">
						<small class="text-muted">Note: Password is auto-generated. The combination is "<strong>24AID_</strong> + <strong>username</strong>".</small>
					</div>
				</div>

				

				<!-- <div class="form-group row">
					<div class="col-sm-6 mb-3 mb-sm-0">
						<div class="password-toggle-container">
							<input name="password" id="password" type="password"
								class="form-control form-control-user @error('password')is-invalid @enderror"
								placeholder="Password" required>
							<span class="password-toggle-btn" onclick="togglePasswordVisibility('password')">Show</span>
							@error('password')
							<span class="invalid-feedback">{{ $message }}</span>
							@enderror
						</div>
					</div>
					<div class="col-sm-6">
						<div class="password-toggle-container">
							<input name="password_confirmation" id="password_confirmation" type="password"
								class="form-control form-control-user @error('password_confirmation')is-invalid @enderror"
								placeholder="Confirm Password">
							<span class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation')">Show</span>
							@error('password_confirmation')
							<span class="invalid-feedback">{{ $message }}</span>
							@enderror
						</div>
					</div>
				</div> -->

				<div class="form-group">
					<label for="profile_picture">Profile Picture:</label>
					<input type="file" name="profile_picture" id="profile_picture"
						class="form-control @error('profile_picture')is-invalid @enderror"
						accept="image/jpeg, image/png">

					@error('profile_picture')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
				</div>

				<button type="button" class="btn btn-primary btn-user btn-block" id="createSuperAdminBtn">Create Super Admin Account</button>
			</form>
			<hr>
		</div>
	</div>

<script>
	// function togglePasswordVisibility(fieldId) {
    //     var passwordInput = document.getElementById(fieldId);
    //     var passwordToggleBtn = passwordInput.parentNode.querySelector(".password-toggle-btn");

    //     if (passwordInput.type === "password") {
    //         passwordInput.type = "text";
    //         passwordToggleBtn.textContent = "Hide";
    //     } else {
    //         passwordInput.type = "password";
    //         passwordToggleBtn.textContent = "Show";
    //     }
    // }

	const createSuperAdminBtn = document.getElementById("createSuperAdminBtn");

	createSuperAdminBtn.addEventListener("click", async () => {
		const createSuperAdminForm = document.getElementById("createSuperAdminForm");
		const formData = new FormData(createSuperAdminForm);

		const errorElements = document.querySelectorAll('.invalid-feedback');
		errorElements.forEach(errorElement => {
			errorElement.remove();
		});

		const inputElements = document.querySelectorAll('.is-invalid');
		inputElements.forEach(inputElement => {
			inputElement.classList.remove('is-invalid');
		});

		try {
			const response = await fetch('{{ route('super-admin.validateAddSuperAdminForm') }}', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
				},
				body: formData,
			});

			const data = await response.json();

			if (data.message === 'Validation failed') {
				const errorElements = document.querySelectorAll('.invalid-feedback');
				errorElements.forEach(errorElement => {
					errorElement.remove();
				});

				const inputElements = document.querySelectorAll('.is-invalid');
				inputElements.forEach(inputElement => {
					inputElement.classList.remove('is-invalid');
				});

				for (const [key, value] of Object.entries(data.errors)) {
					const input = document.querySelector(`[name="${key}"]`);
					const error = document.createElement('div');
					error.classList.add('invalid-feedback');
					error.textContent = value;
					input.classList.add('is-invalid');
					input.parentNode.insertBefore(error, input.nextSibling);
				}
			} else if (data.message === 'Validation passed') {
				createSuperAdminForm.submit();
				console.log('Validation passed');
			} else {
				console.log('Other errors');
			}

		} catch (error) {
			console.error('An error occurred:', error);
		}
	});
</script>
@endsection