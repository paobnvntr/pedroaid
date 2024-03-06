@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
		<a href="{{ route('committee') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Edit Committee Details</h1>
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
			<form action="{{ route('committee.updateCommittee', $committee->id) }}" method="POST" class="user" enctype="multipart/form-data" id="updateCommitteeForm">
                @csrf
				<div class="form-group">
					<input name="name" type="text"
						class="form-control form-control-user @error('name')is-invalid @enderror"
						id="name" placeholder="Current Committee Name: {{ $committee->name }}" value="{{ old('name', $committee->name) }}" disabled>
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
                                placeholder="Current First Name: {{ implode(' ', array_slice(explode(' ', $committee->chairman), 0, -1)) }}" 
                                value="{{ old('chairman_firstName', implode(' ', array_slice(explode(' ', $committee->chairman), 0, -1))) }}" disabled>
                            @error('chairman_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="chairman_lastName" id="chairman_lastName" type="text"
                                class="form-control form-control-user @error('chairman_lastName')is-invalid @enderror"
                                placeholder="Current Last Name: {{ $currentChairmanLastName }}" 
                                value="{{ old('chairman_lastName', $currentChairmanLastName) }}" disabled>
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
                                placeholder="Current First Name: {{ implode(' ', array_slice(explode(' ', $committee->vice_chairman), 0, -1)) }}" 
                                value="{{ old('viceChairman_firstName', implode(' ', array_slice(explode(' ', $committee->vice_chairman), 0, -1))) }}" disabled>
                            @error('viceChairman_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="viceChairman_lastName" id="viceChairman_lastName" type="text"
                                class="form-control form-control-user @error('viceChairman_lastName')is-invalid @enderror"
                                placeholder="Current Last Name: {{ $currentViceChairmanLastName }}" 
                                value="{{ old('viceChairman_lastName', $currentViceChairmanLastName) }}" disabled>
                            @error('viceChairman_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
				</div>

                <div class="form-group">
                    <label for="chairmain_name">Member(s):</label>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0" id="firstMember1">
                            <input name="member1_firstName" id="member1_firstName" type="text"
                                class="form-control form-control-user @error('member1_firstName')is-invalid @enderror"
                                placeholder="Current First Name: {{ implode(' ', array_slice(explode(' ', $committee->member_1), 0, -1)) }}" 
                                value="{{ old('member1_firstName', implode(' ', array_slice(explode(' ', $committee->member_1), 0, -1))) }}" disabled>
                            @error('member1_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5" id="lastMember1">
                            <input name="member1_lastName" id="member1_lastName" type="text"
                                class="form-control form-control-user @error('member1_lastName')is-invalid @enderror"
                                id="exampleInputLastName" placeholder="Current Last Name: {{ $currentMember1LastName }}" 
                                value="{{ old('member1_lastName', $currentMember1LastName) }}" disabled>
                            @error('member1_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-2 d-flex align-items-center" id="trashMember1">
                            <a href="{{ route('committee.deleteMember1', $committee->id) }}" class="form-control btn btn-danger" id="deleteMember1Btn" style="display: none;"><i class="fas fa-fw fa-trash-alt"></i></a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0" id="firstMember2">
                            <input name="member2_firstName" id="member2_firstName" type="text"
                                class="form-control form-control-user @error('member2_firstName')is-invalid @enderror"
                                placeholder="Current First Name: {{ implode(' ', array_slice(explode(' ', $committee->member_2), 0, -1)) }}" 
                                value="{{ old('member2_firstName', implode(' ', array_slice(explode(' ', $committee->member_2), 0, -1))) }}" disabled>
                            @error('member2_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5" id="lastMember2">
                            <input name="member2_lastName" id="member2_lastName" type="text"
                                class="form-control form-control-user @error('member2_lastName')is-invalid @enderror"
                                placeholder="Current Last Name: {{ $currentMember2LastName }}" 
                                value="{{ old('member2_lastName', $currentMember2LastName) }}" disabled>
                            @error('member2_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-2 d-flex align-items-center" id="trashMember2">
                            <a href="{{ route('committee.deleteMember2', $committee->id) }}" class="form-control btn btn-danger" id="deleteMember2Btn" style="display: none;"><i class="fas fa-fw fa-trash-alt"></i></a>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-5 mb-3 mb-sm-0" id="firstMember3">
                            <input name="member3_firstName" id="member3_firstName" type="text"
                                class="form-control form-control-user @error('member3_firstName')is-invalid @enderror"
                                placeholder="Current First Name: {{ implode(' ', array_slice(explode(' ', $committee->member_3), 0, -1)) }}" 
                                value="{{ old('member3_firstName', implode(' ', array_slice(explode(' ', $committee->member_3), 0, -1))) }}" disabled>
                            @error('member3_firstName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5" id="lastMember3">
                            <input name="member3_lastName" id="member3_lastName" type="text"
                                class="form-control form-control-user @error('member3_lastName')is-invalid @enderror"
                                placeholder="Current Last Name: {{ $currentMember3LastName }}" 
                                value="{{ old('member3_lastName', $currentMember3LastName) }}" disabled>
                            @error('member3_lastName')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-2 d-flex align-items-center" id="trashMember3">
                            <a href="{{ route('committee.deleteMember3', $committee->id) }}" class="form-control btn btn-danger" id="deleteMember3Btn" style="display: none;"><i class="fas fa-fw fa-trash-alt"></i></a>
                        </div>
                    </div>
				</div>

				<button type="button" class="btn btn-primary btn-user btn-block" id="updateCommitteeBtn" style="display: none;">Update Details</button>
				<button type="button" class="btn btn-primary btn-user btn-block" id="editCommitteeBtn">Edit Committee</button>
			</form>
			<hr>
		</div>
	</div>

<script>
	const originalName = "{{ $committee->name }}";
    const originalChairmanFirstName = "{{ implode(' ', array_slice(explode(' ', $committee->chairman), 0, -1)) }}";
    const originalChairmanLastName = "{{ $currentChairmanLastName }}";
    const originalViceChairmanFirstName = "{{ implode(' ', array_slice(explode(' ', $committee->vice_chairman), 0, -1)) }}";
    const originalViceChairmanLastName = "{{ $currentViceChairmanLastName }}";
    const originalMember1FirstName = "{{ implode(' ', array_slice(explode(' ', $committee->member_1), 0, -1)) }}";
    const originalMember1LastName = "{{ $currentMember1LastName }}";
    const originalMember2FirstName = "{{ implode(' ', array_slice(explode(' ', $committee->member_2), 0, -1)) }}";  
    const originalMember2LastName = "{{ $currentMember2LastName }}";
    const originalMember3FirstName = "{{ implode(' ', array_slice(explode(' ', $committee->member_3), 0, -1)) }}";
    const originalMember3LastName = "{{ $currentMember3LastName }}";
	
	const editCommitteeBtn = document.getElementById("editCommitteeBtn");

	editCommitteeBtn.addEventListener("click", async () => {
		const submitBtn = document.getElementById("updateCommitteeBtn");
		const nameInput = document.getElementById("name");
        const chairmanFirstNameInput = document.getElementById("chairman_firstName");
        const chairmanLastNameInput = document.getElementById("chairman_lastName");
        const viceChairmanFirstNameInput = document.getElementById("viceChairman_firstName");
        const viceChairmanLastNameInput = document.getElementById("viceChairman_lastName");
        const member1FirstNameInput = document.getElementById("member1_firstName");
        const member1LastNameInput = document.getElementById("member1_lastName");
        const member2FirstNameInput = document.getElementById("member2_firstName");
        const member2LastNameInput = document.getElementById("member2_lastName");
        const member3FirstNameInput = document.getElementById("member3_firstName");
        const member3LastNameInput = document.getElementById("member3_lastName");

        const deleteMember1Btn = document.getElementById("deleteMember1Btn");
        const deleteMember2Btn = document.getElementById("deleteMember2Btn");
        const deleteMember3Btn = document.getElementById("deleteMember3Btn");

        const firstMember1 = document.getElementById("firstMember1");
        const firstMember2 = document.getElementById("firstMember2");
        const firstMember3 = document.getElementById("firstMember3");

        const lastMember1 = document.getElementById("lastMember1");
        const lastMember2 = document.getElementById("lastMember2");
        const lastMember3 = document.getElementById("lastMember3");

		if (editCommitteeBtn.textContent === "Edit Committee") {
			editCommitteeBtn.textContent = "Cancel";
			editCommitteeBtn.classList.remove("btn-primary");
			editCommitteeBtn.classList.add("btn-danger");
            nameInput.disabled = false;
            chairmanFirstNameInput.disabled = false;
            chairmanLastNameInput.disabled = false;
            viceChairmanFirstNameInput.disabled = false;
            viceChairmanLastNameInput.disabled = false;
            member1FirstNameInput.disabled = false;
            member1LastNameInput.disabled = false;
            member2FirstNameInput.disabled = false;
            member2LastNameInput.disabled = false;
            member3FirstNameInput.disabled = false;
            member3LastNameInput.disabled = false;

            deleteMember1Btn.style.display = "block";
            firstMember1.classList.remove("col-sm-6");
            firstMember1.classList.add("col-sm-5");

            deleteMember2Btn.style.display = "block";
            firstMember2.classList.remove("col-sm-6");
            firstMember2.classList.add("col-sm-5");

            deleteMember3Btn.style.display = "block";
            firstMember3.classList.remove("col-sm-6");
            firstMember3.classList.add("col-sm-5");

			submitBtn.style.display = "block"; // show the submit button
		} else {
			editCommitteeBtn.textContent = "Edit Committee";
			editCommitteeBtn.classList.remove("btn-danger");
			editCommitteeBtn.classList.add("btn-primary");
			nameInput.disabled = true;
            chairmanFirstNameInput.disabled = true;
            chairmanLastNameInput.disabled = true;
            viceChairmanFirstNameInput.disabled = true;
            viceChairmanLastNameInput.disabled = true;
            member1FirstNameInput.disabled = true;
            member1LastNameInput.disabled = true;
            member2FirstNameInput.disabled = true;
            member2LastNameInput.disabled = true;
            member3FirstNameInput.disabled = true;
            member3LastNameInput.disabled = true;

            deleteMember1Btn.style.display = "none";
            firstMember1.classList.remove("col-sm-5");
            firstMember1.classList.add("col-sm-6");

            deleteMember2Btn.style.display = "none";
            firstMember2.classList.remove("col-sm-5");
            firstMember2.classList.add("col-sm-6");

            deleteMember3Btn.style.display = "none";
            firstMember3.classList.remove("col-sm-5");
            firstMember3.classList.add("col-sm-6");

			submitBtn.style.display = "none"; // hide the submit button

			// reset the form
			nameInput.value = originalName;
            chairmanFirstNameInput.value = originalChairmanFirstName;
            chairmanLastNameInput.value = originalChairmanLastName;
            viceChairmanFirstNameInput.value = originalViceChairmanFirstName;
            viceChairmanLastNameInput.value = originalViceChairmanLastName;
            member1FirstNameInput.value = originalMember1FirstName;
            member1LastNameInput.value = originalMember1LastName;
            member2FirstNameInput.value = originalMember2FirstName;
            member2LastNameInput.value = originalMember2LastName;
            member3FirstNameInput.value = originalMember3FirstName;
            member3LastNameInput.value = originalMember3LastName;

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

	const updateCommitteeBtn = document.getElementById("updateCommitteeBtn");

	updateCommitteeBtn.addEventListener("click", async () => {
		const updateCommitteeForm = document.getElementById("updateCommitteeForm");
		const formData = new FormData(updateCommitteeForm);

		try {
			const response = await fetch('{{ route('committee.validateEditCommitteeForm', $committee->id) }}', {
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
				updateCommitteeForm.submit();
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