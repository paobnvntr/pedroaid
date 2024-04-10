@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('admin') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Edit {{ $admin->name }}'s Details</h1>
	</div>
	@if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="p-5">
        <div class="text-center mb-4">
            <img src="{{ asset($admin->profile_picture) }}" class="rounded-circle" width="150" height="150">
        </div>
        <form action="{{ route('admin.updateAdmin', $admin->id) }}" method="POST" enctype="multipart/form-data" class="user" id="updateAdminForm">
            @csrf
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="name">Admin Name:</label>
                    <input name="name" type="text"
                        class="form-control form-control-user @error('name')is-invalid @enderror"
                        id="name" placeholder="Current Name: {{ $admin->name }}" value="{{ old('name', $admin->name) }}" disabled>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="username">Admin Username:</label>
                    <input name="username" type="text"
                        class="form-control form-control-user @error('username')is-invalid @enderror"
                        id="username" placeholder="Current Username: {{ $admin->username }}" value="{{ old('username', $admin->username) }}" disabled>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Admin Email:</label>
                <input name="email" type="email"
                    class="form-control form-control-user @error('email')is-invalid @enderror"
                    id="email" placeholder="Current Email: {{ $admin->email }}" value="{{ old('email', $admin->email) }}" disabled>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="password">New Password:</label>
					<div class="password-toggle-container">
						<input name="password" type="password"
							class="form-control form-control-user @error('password')is-invalid @enderror"
							id="password" disabled>
						<span class="password-toggle-btn" style="display: none;" onclick="togglePasswordVisibility('password')">Show</span>
						@error('password')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
                </div>
                <div class="col-sm-6">
                    <label for="password_confirmation">Confirm Password:</label>
					<div class="password-toggle-container">
						<input name="password_confirmation" type="password"
							class="form-control form-control-user @error('password_confirmation')is-invalid @enderror"
							id="password_confirmation" disabled>
						<span class="password-toggle-btn" style="display: none;" onclick="togglePasswordVisibility('password_confirmation')">Show</span>
						@error('password_confirmation')
						<span class="invalid-feedback">{{ $message }}</span>
						@enderror
					</div>
                </div>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture" 
                    class="form-control @error('profile_picture')is-invalid @enderror"
                    accept="image/jpeg, image/png" disabled>
                @error('profile_picture')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <button type="button" class="btn btn-primary btn-user btn-block" id="updateAdminBtn" style="display: none;">Update Details</button>
            <button type="button" class="btn btn-primary btn-user btn-block" id="editAdminBtn">Edit Admin</button>
        </form>
        <hr>
    </div>

<script>
	const originalName = "{{ $admin->name }}";
	const originalUsername = "{{ $admin->username }}";
	const originalEmail = "{{ $admin->email }}";
	const passwordInput = document.getElementById("password");
	const passwordConfirmationInput = document.getElementById("password_confirmation");
	const profilePictureInput = document.getElementById("profile_picture");

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
	
	const editAdminBtn = document.getElementById("editAdminBtn");

	editAdminBtn.addEventListener("click", async () => {
		const submitBtn = document.getElementById("updateAdminBtn");
		const nameInput = document.getElementById("name");
		const usernameInput = document.getElementById("username");
		const emailInput = document.getElementById("email");
		const passwordInput = document.getElementById("password");
		const passwordConfirmationInput = document.getElementById("password_confirmation");
		const profilePictureInput = document.getElementById("profile_picture");
		const showPasswordToggleBtns = document.querySelectorAll('.password-toggle-btn');


		if (editAdminBtn.textContent === "Edit Admin") {
			editAdminBtn.textContent = "Cancel";
			editAdminBtn.classList.remove("btn-primary");
			editAdminBtn.classList.add("btn-danger");
			nameInput.disabled = false;
			usernameInput.disabled = false;
			emailInput.disabled = false;
			passwordInput.disabled = false;
			passwordConfirmationInput.disabled = false;
			profilePictureInput.disabled = false;
			submitBtn.style.display = "block"; // show the submit button
			showPasswordToggleBtns.forEach(btn => {
				btn.style.display = "block";
			});
		} else {
			editAdminBtn.textContent = "Edit Admin";
			editAdminBtn.classList.remove("btn-danger");
			editAdminBtn.classList.add("btn-primary");
			nameInput.disabled = true;
			usernameInput.disabled = true;
			emailInput.disabled = true;
			passwordInput.disabled = true;
			passwordConfirmationInput.disabled = true;
			profilePictureInput.disabled = true;
			submitBtn.style.display = "none"; // hide the submit button
			showPasswordToggleBtns.forEach(btn => {
				btn.style.display = "none";
			});

			// reset the form
			nameInput.value = originalName;
			usernameInput.value = originalUsername;
			emailInput.value = originalEmail;
			passwordInput.value = "";
			passwordConfirmationInput.value = "";
			profilePictureInput.value = "";

			const errorElements = document.querySelectorAll('.invalid-feedback');
			errorElements.forEach(errorElement => {
				errorElement.remove();
			});

			const inputElements = document.querySelectorAll('.is-invalid');
			inputElements.forEach(inputElement => {
				inputElement.classList.remove('is-invalid');
			});
		}
	});

	const updateAdminBtn = document.getElementById("updateAdminBtn");

	updateAdminBtn.addEventListener("click", async () => {
		const updateAdminForm = document.getElementById("updateAdminForm");
		const formData = new FormData(updateAdminForm);

		try {
			const response = await fetch('{{ route('admin.validateEditAdminForm', $admin->id) }}', {
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
				updateAdminForm.submit();
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