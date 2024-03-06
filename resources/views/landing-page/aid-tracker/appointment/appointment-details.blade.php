@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Tracker')

@section('contents')
    <section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up" data-aos-delay="200">AID Tracker - Appointment</h1>
            </div>
		</div>
	</section>

    <section class="ordinance pt-4">
		<div class="container" data-aos="fade-up">

			<header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">Appointment ID: {{ $appointment->appointment_id }}</p>
                    <a href="{{ route('appointmentTracker') }}"
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
                        <h3 class="text-center">Appointment of {{ $appointment->name }}</h3>
                        <p><strong>Name:</strong> {{ $appointment->name }}</p>
                        <p><strong>Address:</strong> {{ $appointment->address }}</p>
                        <p><strong>Contact Number:</strong> {{ $appointment->cellphone_number }}</p>
                        <p><strong>Email Address:</strong> {{ $appointment->email }}</p>
                    </div>
                    
                    <hr>
                    <div>
                        <div class="message-wrapper">
                            <!-- Message History Here -->
                            <div class="message-container" >
                                @foreach($messages as $appointmentMessage)
                                                             
                                        @if($appointmentMessage->staff_name == null)
                                            <div class="card mb-3 your-message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title text-end">You</h5>
                                                    <p class="card-text text-end">{{ $appointmentMessage->message }}</p>
                                                    <p class="card-text text-end"><small class="text-muted">{{ $appointmentMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card mb-3 message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $appointmentMessage->staff_name }}</h5>
                                                    <p class="card-text">{{ $appointmentMessage->message }}</p>
                                                    <p class="card-text"><small class="text-muted">{{ $appointmentMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @endif
                                            
                                @endforeach
                            </div>
                        </div>

                        <hr>
                        
                        @if($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled')
                            <form action="{{ route('appointmentSendMessage', $appointment->appointment_id) }}" method="POST" class="user">
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
                        @elseif ($appointment->appointment_status == 'Finished')
                            <div class="alert alert-success" role="alert">
                                Appointment is finished.
                            </div>
                        @elseif ($appointment->appointment_status == 'Cancelled')
                            <div class="alert alert-danger" role="alert">
                                Appointment is cancelled.
                            </div>
                        @elseif ($appointment->appointment_status == 'Declined')
                            <div class="alert alert-danger" role="alert">
                                Appointment is declined.
                            </div>
                        @elseif ($appointment->appointment_status == 'Pending')
                            <div class="alert alert-warning" role="alert">
                                Appointment is pending.
                            </div>
                        @elseif ($appointment->appointment_status == 'No-Show')
                            <div class="alert alert-danger" role="alert">
                                Appointment is no-show.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card shadow col-sm-3 trackerAppointmentDetails">
                    <div class="d-flex flex-column justify-content-center">
                        <a href="{{ route('refreshAppointment', $appointment->appointment_id) }}" class="btn btn-primary"><i class="ri-refresh-line icon"></i> Refresh Page</a>
                        <hr>
                        <h3 class="text-center">Appointment Details</h3>
                        <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id }}</p>
                        <p><strong>Status:</strong> {{ $appointment->appointment_status }}</p>
                        <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date }}</p>
                        <p><strong>Appointment Time:</strong> {{ $appointment->appointment_time }}</p>
                        <p><strong>Created At:</strong> {{ $appointment->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $appointment->updated_at }}</p>
                        @if($appointment->appointment_status == 'Finished')
                            <hr>
                            @if($feedback->count() > 0)
                                <p><strong>Rating:</strong> {{ $rating }}</p>
                                <p><strong>Comment:</strong> {{ $comment }}</p>
                                <a href="{{ route('redirectEditFeedback', $appointment->appointment_id) }}" class="btn btn-warning">Edit Feedback <i class="ri-file-list-fill icon"></i></a>
                            @else
                                <a href="{{ route('redirectFeedback', $appointment->appointment_id) }}" class="btn btn-primary">Give Feedback <i class="ri-file-list-fill icon"></i></a>
                            @endif
                        @elseif($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled')
                            <hr>
                            <a href="{{ route('rescheduleAppointment', $appointment->appointment_id) }}" class="btn btn-warning mb-3">Reschedule <i class="ri-time-line icon"></i></a>
                            <a href="{{ route('cancelAppointment', $appointment->appointment_id) }}" class="btn btn-danger">Cancel <i class="ri-close-circle-line icon"></i></a>
                        @elseif($appointment->appointment_status == 'Pending')
                            <hr>
                            <a href="{{ route('cancelAppointment', $appointment->appointment_id) }}" class="btn btn-danger">Cancel <i class="ri-close-circle-line icon"></i></a>
                        @elseif($appointment->appointment_status == 'No-Show')
                            <hr>
                            <a href="{{ route('rescheduleAppointment', $appointment->appointment_id) }}" class="btn btn-warning mb-3">Reschedule <i class="ri-time-line icon"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection