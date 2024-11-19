@extends('layouts.app')

@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('ordinance') }}" class="fas fa-angle-left fs-4"></a>
        <h1 class="mb-0 ml-4">Add City Ordinance</h1>
    </div>

    <div>
        <div class="p-5">
            <form action="{{ route('ordinance.saveOrdinance') }}" method="POST" class="user" enctype="multipart/form-data" id="createOrdinanceForm">
                @csrf

                <div class="form-group">
                    <label for="committee">Committee:</label>
                    <select name="committee" id="committee" class="form-control">
                        @foreach($committee as $ct)
                            <option value="{{ $ct->name }}" {{ old('committee') == '$ct->name' ? 'selected' : '' }}>{{ $ct->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input name="ordinance_number" type="text"
                            class="form-control form-control-user @error('ordinance_number')is-invalid @enderror"
                            id="exampleInputOrdinanceNumber" placeholder="Ordinance Number" value="{{ old('ordinance_number') }}">
                        @error('ordinance_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-4 d-flex align-items-center justify-content-center">
                                <label for="exampleInputDateApproved" class="text-center">Date Approved: </label>
                            </div>
                            <div class="col-sm-8">
                                <input name="date_approved" type="date"
                                    class="form-control form-control-user @error('date_approved')is-invalid @enderror"
                                    id="exampleInputDateApproved" placeholder="Date Approved" value="{{ old('date_approved') }}">
                                @error('date_approved')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <textarea name="description" rows="5"
                        class="form-control form-control-user @error('description')is-invalid @enderror"
                        id="exampleInputDescription" placeholder="Description">{{ old('description') }}</textarea>
                    @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="exampleInputFile">City Ordinance File (PDF only)</label>
                    <input type="file" name="ordinance_file" class="form-control @error('ordinance_file')is-invalid @enderror" 
                    id="exampleInputFile" accept="application/pdf">
                    @error('ordinance_file')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary btn-user btn-block" id="createOrdinanceBtn">Add City Ordinance</button>
            </form>
            <hr>
        </div>
    </div>

<script>
	const createOrdinanceBtn = document.getElementById("createOrdinanceBtn");

	createOrdinanceBtn.addEventListener("click", async () => {
		const createOrdinanceForm = document.getElementById("createOrdinanceForm");
		const formData = new FormData(createOrdinanceForm);

		const errorElements = document.querySelectorAll('.invalid-feedback');
		errorElements.forEach(errorElement => {
			errorElement.remove();
		});

		const inputElements = document.querySelectorAll('.is-invalid');
		inputElements.forEach(inputElement => {
			inputElement.classList.remove('is-invalid');
		});

		try {
			const response = await fetch('{{ route('ordinance.validateAddOrdinanceForm') }}', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
				createOrdinanceForm.submit();
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