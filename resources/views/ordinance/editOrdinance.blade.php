@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('ordinance') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Edit Ordinance No. {{ $ordinance->ordinance_number }}'s Details</h1>
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

    <div>
        <div class="p-5">
            <form action="{{ route('ordinance.editOrdinance', $ordinance->id) }}" method="POST" class="user" enctype="multipart/form-data" id="updateOrdinanceForm">
                @csrf
                <div class="form-group">
                    <label for="committee">Committee:</label>
                    <select name="committee" id="committee" class="form-control" disabled>
                        @foreach($committee as $ct)
                            <option value="{{ $ct->name }}" {{ (old('committee') ?? $ordinance->committee) == $ct->name ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="ordinance_number">Ordinance Number: </label>
                        <input name="ordinance_number" type="text"
                            class="form-control form-control-user @error('ordinance_number')is-invalid @enderror"
                            id="ordinance_number" placeholder="Current Ordinance Number: {{ $ordinance->ordinance_number }}" value="{{ old('ordinance_number', $ordinance->ordinance_number) }}" disabled>
                        @error('ordinance_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="date_approved">Date Approved: </label>
                        <input name="date_approved" type="date"
                            class="form-control form-control-user @error('date_approved')is-invalid @enderror"
                            id="date_approved" placeholder="Date Approved" value="{{ old('date_approved', $ordinance->date_approved) }}" disabled>
                        @error('date_approved')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Ordinance Description:</label>
                    <textarea name="description" rows="5"
                        class="form-control form-control-user @error('description')is-invalid @enderror"
                        id="description" placeholder="Description" disabled>{{ old('description', $ordinance->description) }}</textarea>
                    @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="ordinance_file">New City Ordinance File:</label>
                        <input type="file" name="ordinance_file" class="form-control @error('ordinance_file')is-invalid @enderror" 
                        id="ordinance_file" accept="application/pdf" disabled>
                        @error('ordinance_file')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="ordinance_file">Current City Ordinance File:</label>
                        <a href="{{ asset($ordinance->ordinance_file) }}" target="_blank" class="form-control">{{ basename($ordinance->ordinance_file) }}</a>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-user btn-block" id="updateOrdinanceBtn" style="display: none;">Update Details</button>
                <button type="button" class="btn btn-primary btn-user btn-block" id="editOrdinanceBtn">Edit Ordinance</button>
            </form>
            <hr>
        </div>
    </div>

<script>
	const originalCommittee = "{{ $ordinance->committee }}";
    const originalOrdinanceNumber = "{{ $ordinance->ordinance_number }}";
    const originalDateApproved = "{{ $ordinance->date_approved }}";
    const originalDescription = "{{ $ordinance->description }}";
	const ordinanceFileInput = document.getElementById("ordinance_file");
	
	const editOrdinanceBtn = document.getElementById("editOrdinanceBtn");

	editOrdinanceBtn.addEventListener("click", async () => {
		const submitBtn = document.getElementById("updateOrdinanceBtn");
        const committeeInput = document.getElementById("committee");
        const ordinanceNumberInput = document.getElementById("ordinance_number");
        const dateApprovedInput = document.getElementById("date_approved");
        const descriptionInput = document.getElementById("description");

		if (editOrdinanceBtn.textContent === "Edit Ordinance") {
			editOrdinanceBtn.textContent = "Cancel";
			editOrdinanceBtn.classList.remove("btn-primary");
			editOrdinanceBtn.classList.add("btn-danger");
            committeeInput.disabled = false;
            ordinanceNumberInput.disabled = false;
            dateApprovedInput.disabled = false;
            descriptionInput.disabled = false;
            ordinanceFileInput.disabled = false;
			submitBtn.style.display = "block"; // show the submit button
		} else {
			editOrdinanceBtn.textContent = "Edit Ordinance";
			editOrdinanceBtn.classList.remove("btn-danger");
			editOrdinanceBtn.classList.add("btn-primary");
            committeeInput.disabled = true;
            ordinanceNumberInput.disabled = true;
            dateApprovedInput.disabled = true;
            descriptionInput.disabled = true;
            ordinanceFileInput.disabled = true;
			submitBtn.style.display = "none"; // hide the submit button

			// reset the form
            committeeInput.value = originalCommittee;
            ordinanceNumberInput.value = originalOrdinanceNumber;
            dateApprovedInput.value = originalDateApproved;
            descriptionInput.value = originalDescription;
            ordinanceFileInput.value = "";

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

	const updateOrdinanceBtn = document.getElementById("updateOrdinanceBtn");

	updateOrdinanceBtn.addEventListener("click", async () => {
		const updateOrdinanceForm = document.getElementById("updateOrdinanceForm");
		const formData = new FormData(updateOrdinanceForm);

		try {
			const response = await fetch('{{ route('ordinance.validateEditOrdinanceForm', $ordinance->id) }}', {
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
				updateOrdinanceForm.submit();
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