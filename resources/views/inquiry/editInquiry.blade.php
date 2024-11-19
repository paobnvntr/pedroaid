@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('inquiry') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Edit Inquiry ID: {{ $inquiry->inquiry_id }}'s Details</h1>
	</div>

    @if(Session::has('success'))
        <div class="alert alert-success" id="alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="p-5">
        <div class="p-5 card shadow">
            <form action="{{ route('inquiry.updateInquiry', $inquiry->inquiry_id) }}" method="POST" id="inquiryForm" class="user" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input name="name" id="name" type="text"
                            class="form-control form-control-user @error('name')is-invalid @enderror"
                            placeholder="Current Name: {{ $inquiry->name }}" value="{{ old('name', $inquiry->name) }}" disabled>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <input name="email" id="email" type="email" maxlength="320"
                            class="form-control form-control-user @error('email')is-invalid @enderror"
                            id="email" placeholder="Current Email Address: {{ $inquiry->email }}" value="{{ old('email', $inquiry->email) }}" disabled>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <textarea name="inquiry" id="inquiry" rows="5" 
                    class="form-control form-control-textbox @error('inquiry')is-invalid @enderror" 
                    placeholder="Current Inquiry: {{ $inquiry->inquiry }}" disabled>{{ old('inquiry', $inquiry->inquiry) }}</textarea>
                    @error('inquiry')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <select name="status" id="status" class="form-control @error('status')is-invalid @enderror" disabled>
                        <option value="Unanswered" {{ old('status', $inquiry->status) === 'Unanswered' ? 'selected' : '' }}>Unanswered</option>
                        <option value="Answered" {{ old('status', $inquiry->status) === 'Answered' ? 'selected' : '' }}>Answered</option>
                    </select>
                    @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary btn-user btn-block hideBtn" id="updateInquiryBtn">Update Inquiry</button>
                <button type="button" class="btn btn-primary btn-user btn-block" id="editInquiryBtn">Edit Inquiry</button>
            </form>
        </div>
    </div>
    
<script>
    const originalName = "{{ $inquiry->name }}";
    const originalEmail = "{{ $inquiry->email }}";
    const originalInquiry = "{{ $inquiry->inquiry }}";
    const originalStatus = "{{ $inquiry->status }}";

    const editInquiryBtn = document.getElementById("editInquiryBtn");
    const updateInquiryBtn = document.getElementById("updateInquiryBtn");

    editInquiryBtn.addEventListener("click", async () => {
        const name = document.getElementById("name");
        const email = document.getElementById("email");
        const inquiry = document.getElementById("inquiry");
        const status = document.getElementById("status");


        if (editInquiryBtn.textContent === "Edit Inquiry") {
            editInquiryBtn.textContent = "Cancel";
            editInquiryBtn.classList.remove("btn-primary");
            editInquiryBtn.classList.add("btn-danger");
            name.disabled = false;
            email.disabled = false;
            inquiry.disabled = false;
            status.disabled = false;
            updateInquiryBtn.classList.remove("hideBtn");
            updateInquiryBtn.classList.add("showBtn");
        } else {
            editInquiryBtn.textContent = "Edit Inquiry";
            editInquiryBtn.classList.remove("btn-danger");
            editInquiryBtn.classList.add("btn-primary");
            name.disabled = true;
            email.disabled = true;
            inquiry.disabled = true;
            status.disabled = true;
            updateInquiryBtn.classList.remove("showBtn");
            updateInquiryBtn.classList.add("hideBtn");

            name.value = originalName;
            email.value = originalEmail;
            inquiry.value = originalInquiry;
            status.value = originalStatus;
        }
    });

    updateInquiryBtn.addEventListener("click", async () => {
        const inquiryForm = document.getElementById("inquiryForm");
        const formData = new FormData(inquiryForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('inquiry.validateEditInquiryForm', $inquiry->inquiry_id) }}', {
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
                inquiryForm.submit();
                console.log('Validation passed');
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 2000);
        }

        let failedAlert = document.getElementById('alert-failed');
        if (failedAlert) {
            setTimeout(() => {
                failedAlert.style.transition = "opacity 0.5s ease";
                failedAlert.style.opacity = 0;
                setTimeout(() => { failedAlert.remove(); }, 500);
            }, 2000);
        }
    });
</script>
@endsection