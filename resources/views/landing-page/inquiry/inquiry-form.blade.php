@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Inquiry')

@section('contents')
	<section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up">Services</h1>
            </div>
		</div>
	</section>

    <section class="ordinance p-5 appointmentForm">
		<div class="container" data-aos="fade-up">

            @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }} <span id="reloadMessage">Reloading in <span id="countdown">5</span> seconds...</span>
                </div>
                <script>
                    var timer = 5;
                    var countdown = setInterval(function() {
                        timer--;
                        document.getElementById('countdown').innerText = timer;
                        if (timer <= 0) {
                            clearInterval(countdown);
                            window.location.reload();
                        }
                    }, 1000);
                </script>
            @endif

            @if(Session::has('failed'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('failed') }} <span id="reloadMessage">Reloading in <span id="countdown">5</span> seconds...</span>
                </div>
                <script>
                    var timer = 5;
                    var countdown = setInterval(function() {
                        timer--;
                        document.getElementById('countdown').innerText = timer;
                        if (timer <= 0) {
                            clearInterval(countdown);
                            window.location.reload();
                        }
                    }, 1000);
                </script>
            @endif

            <header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">Inquiry Form</p>
                    <a href="{{ route('home') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
            </header>

            <div class="p-5 card shadow" id="clientDetailsForm">
                <form action="{{ route('saveInquiry') }}" method="POST" id="inquiryForm" class="user" enctype="multipart/form-data">
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
                            <input name="email" id="email" type="email"
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
    </section>

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
            const response = await fetch('{{ route('validateInquiryForm') }}', {
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
</script>
@endsection