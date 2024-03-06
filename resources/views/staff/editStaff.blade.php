@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('staff') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Edit {{ $staff->name }}'s Details</h1>
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
            <img src="{{ asset($staff->profile_picture) }}" class="rounded-circle" width="150" height="150">
        </div>
        <form action="{{ route('staff.updateStaff', $staff->id) }}" method="POST" class="user" enctype="multipart/form-data" id="updateStaffForm">
            @csrf
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="name">Staff Name:</label>
                    <input name="name" type="text"
                        class="form-control form-control-user @error('name')is-invalid @enderror"
                        id="name" placeholder="Current Name: {{ $staff->name }}" value="{{ old('name', $staff->name) }}" disabled>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="username">Staff Username:</label>
                    <input name="username" type="text"
                        class="form-control form-control-user @error('username')is-invalid @enderror"
                        id="username" placeholder="Current Username: {{ $staff->username }}" value="{{ old('username', $staff->username) }}" disabled>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="transaction_level">Transaction Type</label>
                <select name="transaction_level" id="transaction_level" class="form-control" disabled>
                    <option value="Appointment" {{ old('transaction_level', $staff->transaction_level) == 'Appointment' ? 'selected' : '' }}>Appointment</option>
                    <option value="Inquiry" {{ old('transaction_level', $staff->transaction_level) == 'Inquiry' ? 'selected' : '' }}>Inquiry</option>
                    <option value="Document Request" {{ old('transaction_level', $staff->transaction_level) == 'Document Request' ? 'selected' : '' }}>Document Request</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Staff Email:</label>
                <input name="email" type="email"
                    class="form-control form-control-user @error('email')is-invalid @enderror"
                    id="email" placeholder="Current Email: {{ $staff->email }}" value="{{ old('email', $staff->email) }}" disabled>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="password">Password:</label>
                    <input name="password" type="password"
                        class="form-control form-control-user @error('password')is-invalid @enderror"
                        id="password" disabled>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="password_confirmation">Confirm Password:</label>
                    <input name="password_confirmation" type="password"
                        class="form-control form-control-user @error('password_confirmation')is-invalid @enderror"
                        id="password_confirmation" disabled>
                    @error('password_confirmation')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
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

            <button type="button" class="btn btn-primary btn-user btn-block" id="updateStaffBtn" style="display: none;">Update Details</button>
            <button type="button" class="btn btn-primary btn-user btn-block" id="editStaffBtn">Edit Staff</button>
        </form>
        <hr>
    </div>

<script>
	const originalName = "{{ $staff->name }}";
	const originalUsername = "{{ $staff->username }}";
	const originalEmail = "{{ $staff->email }}";
    const originalTransactionLevel = "{{ $staff->transaction_level }}";
	const passwordInput = document.getElementById("password");
	const passwordConfirmationInput = document.getElementById("password_confirmation");
	const profilePictureInput = document.getElementById("profile_picture");
	
	const editStaffBtn = document.getElementById("editStaffBtn");

	editStaffBtn.addEventListener("click", async () => {
		const submitBtn = document.getElementById("updateStaffBtn");
		const nameInput = document.getElementById("name");
		const usernameInput = document.getElementById("username");
		const emailInput = document.getElementById("email");
        const transactionLevelInput = document.getElementById("transaction_level");
		const passwordInput = document.getElementById("password");
		const passwordConfirmationInput = document.getElementById("password_confirmation");
		const profilePictureInput = document.getElementById("profile_picture");

		if (editStaffBtn.textContent === "Edit Staff") {
			editStaffBtn.textContent = "Cancel";
			editStaffBtn.classList.remove("btn-primary");
			editStaffBtn.classList.add("btn-danger");
			nameInput.disabled = false;
			usernameInput.disabled = false;
			emailInput.disabled = false;
            transactionLevelInput.disabled = false;
			passwordInput.disabled = false;
			passwordConfirmationInput.disabled = false;
			profilePictureInput.disabled = false;
			submitBtn.style.display = "block"; // show the submit button
		} else {
			editStaffBtn.textContent = "Edit Staff";
			editStaffBtn.classList.remove("btn-danger");
			editStaffBtn.classList.add("btn-primary");
			nameInput.disabled = true;
			usernameInput.disabled = true;
			emailInput.disabled = true;
            transactionLevelInput.disabled = true;
			passwordInput.disabled = true;
			passwordConfirmationInput.disabled = true;
			profilePictureInput.disabled = true;
			submitBtn.style.display = "none"; // hide the submit button

			// reset the form
			nameInput.value = originalName;
			usernameInput.value = originalUsername;
			emailInput.value = originalEmail;
            transactionLevelInput.value = originalTransactionLevel;
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

	const updateStaffBtn = document.getElementById("updateStaffBtn");

	updateStaffBtn.addEventListener("click", async () => {
		const updateStaffForm = document.getElementById("updateStaffForm");
		const formData = new FormData(updateStaffForm);

		try {
			const response = await fetch('{{ route('staff.validateEditStaffForm', $staff->id) }}', {
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
				updateStaffForm.submit();
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