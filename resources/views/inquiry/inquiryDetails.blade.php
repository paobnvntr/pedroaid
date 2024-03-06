@extends('layouts.app')

@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('inquiry') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Inquiry Details</h1>
	</div>

    <div class="pt-4 pb-4">
        <div class="d-flex justify-content-center row">
            <div class="card shadow col-sm-6 mb-sm-0 trackerDetailsForm">
                
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

                <div class="trackerAppointmentContact">
                    <h3 class="text-center">Inquiry of {{ $inquiry->name }}</h3>
                    <p><strong>Client Name:</strong> {{ $inquiry->name }}</p>
                    <p><strong>Email Address:</strong> {{ $inquiry->email }}</p>
                    <p><strong>Inquiry:</strong> {{ $inquiry->inquiry }}</p>
                </div>
                
                <hr>
                <div>
                    <div class="message-wrapper">
                        <!-- Message History Here -->
                        <div class="message-container" >
                            @foreach($messages as $inquiryMessage)
                                                            
                                    @if($inquiryMessage->staff_name == $staffName)
                                        <div class="card mb-3 your-message-card">
                                            <div class="card-body">
                                                <h5 class="card-title text-end">You</h5>
                                                <p class="card-text text-end">{{ $inquiryMessage->message }}</p>
                                                <p class="card-text text-end"><small class="text-muted">{{ $inquiryMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @elseif ($inquiryMessage->staff_name != $staffName && $inquiryMessage->staff_name != null)
                                        <div class="card mb-3 your-message-card">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $inquiryMessage->staff_name }}</h5>
                                                <p class="card-text">{{ $inquiryMessage->message }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $inquiryMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card mb-3 message-card">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $inquiry->name }}</h5>
                                                <p class="card-text">{{ $inquiryMessage->message }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $inquiryMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @endif
                                        
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('inquiry.inquirySendMessage', $inquiry->inquiry_id) }}" method="POST" class="user">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <textarea class="form-control @error('message')is-invalid @enderror" id="message" name="message" rows="2" 
                                    placeholder="Type Message" value="{{ old('message') }}"></textarea>
                                @error('message')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow col-sm-3 trackerAppointmentDetails">
                <div class="d-flex flex-column justify-content-center">
                    <a href="{{ route('generate.inquiry', $inquiry->inquiry_id) }}" class="btn btn-primary">Generate Report</a>
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
@endsection