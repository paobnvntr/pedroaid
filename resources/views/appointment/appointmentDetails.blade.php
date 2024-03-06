@extends('layouts.app')

@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
        @if($appointment->appointment_status == 'Pending' || $appointment->appointment_status == 'Declined')
            <a href="{{ route('appointment.pendingAppointment') }}" class="fas fa-angle-left fs-4"></a>
        @elseif($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled'|| $appointment->appointment_status == 'Cancelled')
            <a href="{{ route('appointment') }}" class="fas fa-angle-left fs-4"></a>
        @elseif($appointment->appointment_status == 'Finished' || $appointment->appointment_status == 'No-Show')
	        <a href="{{ route('appointment.finishedAppointment') }}" class="fas fa-angle-left fs-4"></a>
        @endif
		<h1 class="mb-0 ml-4">Appointment Details</h1>
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
                    <h3 class="text-center">Appointment of {{ $appointment->name }}</h3>
                    <p><strong>Client Name:</strong> {{ $appointment->name }}</p>
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
                                                            
                                    @if($appointmentMessage->staff_name == $staffName)
                                        <div class="card mb-3 your-message-card">
                                            <div class="card-body">
                                                <h5 class="card-title text-end">You</h5>
                                                <p class="card-text text-end">{{ $appointmentMessage->message }}</p>
                                                <p class="card-text text-end"><small class="text-muted">{{ $appointmentMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @elseif ($appointmentMessage->staff_name != $staffName && $appointmentMessage->staff_name != null)
                                        <div class="card mb-3 your-message-card">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $appointmentMessage->staff_name }}</h5>
                                                <p class="card-text">{{ $appointmentMessage->message }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $appointmentMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card mb-3 message-card">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $appointment->name }}</h5>
                                                <p class="card-text">{{ $appointmentMessage->message }}</p>
                                                <p class="card-text"><small class="text-muted">{{ $appointmentMessage->created_at }}</small></p>
                                            </div>
                                        </div>
                                    @endif
                                        
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('appointment.appointmentSendMessage', $appointment->appointment_id) }}" method="POST" class="user">
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
                    <a href="{{ route('generate.appointment', $appointment->appointment_id) }}" class="btn btn-primary">Generate Report</a>
                    <hr>

                    <h3 class="text-center">Appointment Details</h3>
                    <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id }}</p>
                    <p><strong>Status:</strong> {{ $appointment->appointment_status }}</p>
                    <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date }}</p>
                    <p><strong>Appointment Time:</strong> {{ $appointment->appointment_time }}</p>
                    <p><strong>Date Finished:</strong> {{ $appointment->date_finished }}</p>
                    <p><strong>Created At:</strong> {{ $appointment->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $appointment->updated_at }}</p>

                    @if($appointment->appointment_status == 'Pending')
                        <hr>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('appointment.approveAppointment', $appointment->appointment_id) }}" class="btn btn-primary btn-block">Approve</a>
                            <a href="{{ route('appointment.declineAppointment', $appointment->appointment_id) }}" class="btn btn-danger ml-2 mt-0 btn-block">Decline</a>
                        </div>
                    @elseif($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled')
                        <hr>

                        <div class="d-flex justify-content-center align-items-center">
                            <a href="{{ route('appointment.finishAppointment', $appointment->appointment_id) }}" class="btn btn-primary btn-block">Finish</a>
                            <a href="{{ route('appointment.noShowAppointment', $appointment->appointment_id) }}" class="btn btn-danger ml-2 mt-0 btn-block">No-Show</a>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-center">
                            <a href="{{ route('appointment.rescheduleAppointmentForm', $appointment->appointment_id) }}" class="btn btn-warning btn-block">Reschedule</a>
                            <a href="{{ route('appointment.cancelAppointment', $appointment->appointment_id) }}" class="btn btn-danger ml-2 mt-0 btn-block">Cancel</a>
                        </div>
                    @elseif($appointment->appointment_status == 'Finished')
                        <hr>
                        @if($feedback->count() > 0)
                            <p><strong>Rating:</strong> {{ $rating }}</p>
                            <p><strong>Comment:</strong> {{ $comment }}</p>
                            <a href="{{ route('feedbackEditForm', $appointment->appointment_id) }}" class="btn btn-warning">Edit Feedback <i class="ri-file-list-fill icon"></i></a>
                        @else
                            <a href="{{ route('feedbackForm', $appointment->appointment_id) }}" class="btn btn-primary">Give Feedback <i class="ri-file-list-fill icon"></i></a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection