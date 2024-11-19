@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Tracker')

@section('contents')
    <section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up" data-aos-delay="200">AID Tracker - Inquiry</h1>
            </div>
		</div>
	</section>

    <section class="ordinance pt-4">
		<div class="container" data-aos="fade-up">

			<header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">Inquiry ID: {{ $inquiry->inquiry_id }}</p>
                    <a href="{{ route('inquiryTracker') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

            <div class="d-flex justify-content-center row">
                <div class="card shadow p-5 col-sm-6 mb-sm-0 trackerDetailsForm">
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="trackerAppointmentContact">
                        <h3 class="text-center">Inquiry of {{ $inquiry->name }}</h3>
                        <p><strong>Name:</strong> {{ $inquiry->name }}</p>
                        <p><strong>Email Address:</strong> {{ $inquiry->email }}</p>
                        <p><strong>Inquiry:</strong> {{ $inquiry->inquiry }}</p>
                    </div>
                    
                    <hr>
                    <div>
                        <div class="message-wrapper">
                            <div class="message-container" >
                                @foreach($messages as $inquiryMessage)
                                                             
                                        @if($inquiryMessage->staff_name == null)
                                            <div class="card mb-3 your-message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title text-end">You</h5>
                                                    <p class="card-text text-end">{{ $inquiryMessage->message }}</p>
                                                    <p class="card-text text-end"><small class="text-muted">{{ $inquiryMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card mb-3 message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $inquiryMessage->staff_name }}</h5>
                                                    <p class="card-text">{{ $inquiryMessage->message }}</p>
                                                    <p class="card-text"><small class="text-muted">{{ $inquiryMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @endif
                                            
                                @endforeach
                            </div>
                        </div>

                        <hr>
                        
                        <form action="{{ route('inquirySendMessage', $inquiry->inquiry_id) }}" method="POST" class="user">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <textarea class="form-control form-control-textbox" id="message" name="message" rows="2" placeholder="Type Message"></textarea>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary btn-block" id="send-btn">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow col-sm-3 trackerAppointmentDetails">
                    <div class="d-flex flex-column justify-content-center">
                        <a href="{{ route('refreshInquiry', $inquiry->inquiry_id) }}" class="btn btn-primary"><i class="ri-refresh-line icon"></i> Refresh Page</a>
                        <hr>
                        <h3 class="text-center">Inquiry Details</h3>
                        <p><strong>Inquiry ID:</strong> {{ $inquiry->inquiry_id }}</p>
                        <p><strong>Status:</strong> {{ $inquiry->status }}</p>
                        <p><strong>Created At:</strong> {{ $inquiry->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $inquiry->updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection