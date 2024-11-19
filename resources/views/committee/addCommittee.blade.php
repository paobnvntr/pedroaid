@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('committee') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Add Committee</h1>
	</div>

	<div>
		<div class="p-5">
			<form action="{{ route('committee.saveCommittee') }}" method="POST" class="user" enctype="multipart/form-data" id="createCommitteeForm">
				@csrf
				<div class="form-group">
					<input name="name" id="name" type="text"
						class="form-control form-control-user @error('name')is-invalid @enderror"
						id="exampleInputName" placeholder="Committee Name" value="{{ old('name') }}">
					@error('name')
					<span class="invalid-feedback">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
                    <label for="chairmain_name">Chairman:</label>
                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="chairman_firstName" id="chairman_firstName" type="text"
                                class="form-control form-control-user @error('chairman_firstName')is-invalid @enderror"
                                id="exampleInputFirstName" placeholder="First Name" value="{{ old('chairman_firstName') }}">
                            @error('chairman_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="chairman_lastName" id="chairman_lastName" type="text"
                                class="form-control form-control-user @error('chairman_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Last Name" value="{{ old('chairman_lastName') }}">
                            @error('chairman_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
				</div>

                <div class="form-group">
                    <label for="chairmain_name">Vice-Chairman:</label>
                    <div class="row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="viceChairman_firstName" id="viceChairman_firstName" type="text"
                                class="form-control form-control-user @error('viceChairman_firstName')is-invalid @enderror"
                                id="exampleInputFirstName" placeholder="First Name" value="{{ old('viceChairman_firstName') }}">
                            @error('viceChairman_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="viceChairman_lastName" id="viceChairman_lastName" type="text"
                                class="form-control form-control-user @error('viceChairman_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Last Name" value="{{ old('viceChairman_lastName') }}">
                            @error('viceChairman_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
				</div>

                <div class="form-group">
                    <label for="chairmain_name">Member(s):</label>
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="member1_firstName" id="member1_firstName" type="text"
                                class="form-control form-control-user @error('member1_firstName')is-invalid @enderror"
                                id="exampleInputFirstName" placeholder="First Name" value="{{ old('member1_firstName') }}">
                            @error('member1_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="member1_lastName" id="member1_lastName" type="text"
                                class="form-control form-control-user @error('member1_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Last Name" value="{{ old('member1_lastName') }}">
                            @error('member1_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="member2_firstName" id="member2_firstName" type="text"
                                class="form-control form-control-user @error('member2_firstName')is-invalid @enderror"
                                id="exampleInputFirstName" placeholder="First Name" value="{{ old('member2_firstName') }}">
                            @error('member2_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="member2_lastName" id="member2_lastName" type="text"
                                class="form-control form-control-user @error('member2_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Last Name" value="{{ old('member2_lastName') }}">
                            @error('member2_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="member3_firstName" id="member3_firstName" type="text"
                                class="form-control form-control-user @error('member3_firstName')is-invalid @enderror"
                                id="exampleInputFirstName" placeholder="First Name" value="{{ old('member3_firstName') }}">
                            @error('member3_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="member3_lastName" id="member3_lastName" type="text"
                                class="form-control form-control-user @error('member3_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Last Name" value="{{ old('member3_lastName') }}">
                            @error('member3_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
				</div>

				<button type="button" class="btn btn-primary btn-user btn-block" id="createCommitteeBtn">Create Committee</button>
			</form>
			<hr>
		</div>
	</div>

<script>
	const createCommitteeBtn = document.getElementById("createCommitteeBtn");

	createCommitteeBtn.addEventListener("click", async () => {
		const createCommitteeForm = document.getElementById("createCommitteeForm");
		const formData = new FormData(createCommitteeForm);

		const errorElements = document.querySelectorAll('.invalid-feedback');
		errorElements.forEach(errorElement => {
			errorElement.remove();
		});

		const inputElements = document.querySelectorAll('.is-invalid');
		inputElements.forEach(inputElement => {
			inputElement.classList.remove('is-invalid');
		});

		try {
			const response = await fetch('{{ route('committee.validateAddCommitteeForm') }}', {
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
				createCommitteeForm.submit();
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