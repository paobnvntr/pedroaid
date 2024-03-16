@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('staff') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Add Staff</h1>
	</div>

	<div>
		<div class="p-5">
			<form action="{{ route('staff.saveStaff') }}" method="POST" class="user" enctype="multipart/form-data" id="createStaffForm">
				@csrf
				<div class="form-group row">
					<div class="col-sm-6 mb-3 mb-sm-0">
						<input name="name" id="name" type="text"
							class="form-control form-control-user @error('name')is-invalid @enderror"
							placeholder="Staff Name" value="{{ old('name') }}">
						@error('name')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
					
					<div class="col-sm-6">
						<input name="username" id="username" type="text"
							class="form-control form-control-user @error('username')is-invalid @enderror"
							placeholder="Staff Username" value="{{ old('username') }}">
						@error('username')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
				</div>

				<div class="form-group">
					<label for="transaction_level">Transaction Type:</label>
					<select name="transaction_level" id="transaction_level" class="form-control @error('transaction_level')is-invalid @enderror">
						<option value="Appointment" {{ old('transaction_level') == 'Appointment' ? 'selected' : '' }}>Appointment</option>
						<option value="Inquiry" {{ old('transaction_level') == 'Inquiry' ? 'selected' : '' }}>Inquiry</option>
						<option value="Document Request" {{ old('transaction_level') == 'Document Request' ? 'selected' : '' }}>Document Request</option>
					</select>
					@error('transaction_level')
					<span class="invalid-feedback">{{ $message }}</span>
					@enderror
				</div>


				<div class="form-group">
					<input name="email" id="email" type="email"
						class="form-control form-control-user @error('email')is-invalid @enderror"
						placeholder="Email Address" value="{{ old('email') }}">
					@error('email')
					<span class="invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group row">
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
				</div>

				<div class="form-group">
					<label for="profile_picture">Profile Picture:</label>
					<input type="file" name="profile_picture" id="profile_picture"
						class="form-control @error('name')is-invalid @enderror"
						accept="image/jpeg, image/png">
					@error('profile_picture')
					<span class="invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<button type="button" class="btn btn-primary btn-user btn-block" id="createStaffBtn">Create Staff Account</button>
			</form>
			<hr>
		</div>
	</div>

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

	const createStaffBtn = document.getElementById("createStaffBtn");

	createStaffBtn.addEventListener("click", async () => {
		const createStaffForm = document.getElementById("createStaffForm");
		const formData = new FormData(createStaffForm);

		const errorElements = document.querySelectorAll('.invalid-feedback');
		errorElements.forEach(errorElement => {
			errorElement.remove();
		});

		const inputElements = document.querySelectorAll('.is-invalid');
		inputElements.forEach(inputElement => {
			inputElement.classList.remove('is-invalid');
		});

		try {
			const response = await fetch('{{ route('staff.validateAddStaffForm') }}', {
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
				createStaffForm.submit();
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