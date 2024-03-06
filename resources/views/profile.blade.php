@extends('layouts.app')
  
@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('dashboard') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Update Profile</h1>
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

	<div class=" p-5 row">
		<div class="col-md-8 offset-md-2">
			<div class="text-center mb-4">
				<img src="{{ asset($user->profile_picture) }}" class="rounded-circle" width="150" height="150">
			</div>

			<div id="profile-form">
				<form method="POST" action="{{ route('profile.updateProfile') }}" enctype="multipart/form-data" id="updateProfileForm">
					@csrf
					<div class="row">
						<div class="form-group col-md-6">
							<label for="name">Name:</label>
							<input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" disabled>
						</div>
						<div class="form-group col-md-6">
							<label for="username">Username</label>
							<input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" disabled>
						</div>
					</div>

					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
					</div>

					<div class="row">
						<div class="form-group col-md-6">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password" disabled>
						</div>
						<div class="form-group col-md-6">
							<label for="password_confirmation">Confirm Password</label>
							<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" disabled>
						</div>
					</div>

					<div class="form-group">
						<label for="profile_picture">Profile Picture</label>
						<input type="file" class="form-control" id="profile_picture" name="profile_picture" disabled>
					</div>

					<button type="button" class="btn btn-primary btn-block" id="updateProfileBtn" style="display: none;">Update Profile</button>

					<button type="button" class="btn btn-primary btn-block" id="editProfileBtn">Edit Profile</button>
				</form>
			</div>
			
			
		</div>
	</div>

<script>
	const originalName = "{{ $user->name }}";
	const originalUsername = "{{ $user->username }}";
	const originalEmail = "{{ $user->email }}";
	const passwordInput = document.getElementById("password");
	const passwordConfirmationInput = document.getElementById("password_confirmation");
	const profilePictureInput = document.getElementById("profile_picture");
	
	const editProfileBtn = document.getElementById("editProfileBtn");

	editProfileBtn.addEventListener("click", async () => {
		const submitBtn = document.getElementById("updateProfileBtn");
		const nameInput = document.getElementById("name");
		const usernameInput = document.getElementById("username");
		const emailInput = document.getElementById("email");
		const passwordInput = document.getElementById("password");
		const passwordConfirmationInput = document.getElementById("password_confirmation");
		const profilePictureInput = document.getElementById("profile_picture");

		if (editProfileBtn.textContent === "Edit Profile") {
			editProfileBtn.textContent = "Cancel";
			editProfileBtn.classList.remove("btn-primary");
			editProfileBtn.classList.add("btn-danger");
			nameInput.disabled = false;
			usernameInput.disabled = false;
			emailInput.disabled = false;
			passwordInput.disabled = false;
			passwordConfirmationInput.disabled = false;
			profilePictureInput.disabled = false;
			submitBtn.style.display = "block"; // show the submit button
		} else {
			editProfileBtn.textContent = "Edit Profile";
			editProfileBtn.classList.remove("btn-danger");
			editProfileBtn.classList.add("btn-primary");
			nameInput.disabled = true;
			usernameInput.disabled = true;
			emailInput.disabled = true;
			passwordInput.disabled = true;
			passwordConfirmationInput.disabled = true;
			profilePictureInput.disabled = true;
			submitBtn.style.display = "none"; // hide the submit button

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

	const updateProfileBtn = document.getElementById("updateProfileBtn");

	updateProfileBtn.addEventListener("click", async () => {
		const updateProfileForm = document.getElementById("updateProfileForm");
		const formData = new FormData(updateProfileForm);

		try {
			const response = await fetch('{{ route('profile.validateProfileForm', $user->id) }}', {
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
				updateProfileForm.submit();
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