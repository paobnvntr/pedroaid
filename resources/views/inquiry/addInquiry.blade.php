@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('inquiry') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Add Inquiry</h1>
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
        <form action="{{ route('inquiry.saveInquiry') }}" method="POST" id="inquiryForm" class="user" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input name="name" id="name" type="text"
                                class="form-control form-control-user @error('name')is-invalid @enderror"
                                placeholder="Client Name (e.g. Juan Dela Cruz)" value="{{ old('name') }}">
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <input name="email" id="email" type="email" maxlength="320"
                                class="form-control form-control-user @error('email')is-invalid @enderror"
                                id="email" placeholder="Email Address (e.g. juandelacruz@gmail.com)" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <textarea name="inquiry" id="inquiry" rows="5" 
                        class="form-control form-control-textbox @error('inquiry')is-invalid @enderror" 
                        placeholder="Write your inquiry here...">{{ old('inquiry') }}</textarea>
                        @error('inquiry')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="button" class="btn btn-primary btn-user btn-block" id="createInquiryBtn">Send Inquiry</button>
                </form>
        </div>
    </div>
    
    <script>
    const createInquiryBtn = document.getElementById("createInquiryBtn");

    createInquiryBtn.addEventListener("click", async () => {
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
            const response = await fetch('{{ route('inquiry.validateInquiryForm') }}', {
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