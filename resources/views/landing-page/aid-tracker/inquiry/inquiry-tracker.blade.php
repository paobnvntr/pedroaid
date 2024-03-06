@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Tracker')

@section('contents')
    <section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up" data-aos-delay="200">AID Tracker</h1>
            </div>
		</div>
	</section>

    <section class="ordinance pt-4">
		<div class="container" data-aos="fade-up">

			<header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">Inquiry Tracker</p>
                    <a href="{{ route('tracker') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

            <div class="d-flex align-items-center justify-content-center">
                <div class="card shadow p-5 trackingForm">
                    @if(Session::has('failed'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('failed') }}
                        </div>
                    @endif
                    <form action="{{ route('inquiryDetails') }}" method="post" class="user">
                        @method('GET')
                        @csrf
                        <div class="form-group">
                            <input name="inquiry_id" id="inquiry_id" type="text"
                                class="form-control form-control-user @error('inquiry_id')is-invalid @enderror"
                                id="inquiry_id" placeholder="Tracking ID" value="{{ old('inquiry_id') }}">
                            @error('inquiry_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input name="email" id="email" type="email"
                                class="form-control form-control-user @error('email')is-invalid @enderror"
                                id="email" placeholder="Email" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Track Inquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection