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
                
                <hr class="mb-1">

                <div class="trackerAppointmentContact bg-notes p-1 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-2">Note:</h5>
                        <p class="ml-3" id="appointment-notes">{{ $appointment->notes }}</p>
                    </div>

                    <div id="buttons-container">
                        <!-- Conditionally render Edit icon if notes exist -->
                        @if ($appointment->notes)
                            <button onclick="showEditNoteInput()" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        @else
                            <!-- Render Add Note icon if notes are null -->
                            <button onclick="showAddNoteInput()" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i></button>
                        @endif
                    </div>
                </div>

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
                    @if ($appointment->appointment_status == 'Finished')
                        <p><strong>Date Finished:</strong> {{ $appointment->date_finished }}</p>
                    @endif
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

<script>
     function showAddNoteInput() {
        var notesContainer = document.getElementById('appointment-notes');
        var buttonsContainer = document.getElementById('buttons-container');

        // Create a text input element
        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'note-input');

        // Create a save button
        var saveButton = document.createElement('button');
        saveButton.innerHTML = '<i class="fas fa-save"></i>';
        saveButton.classList.add('btn', 'btn-sm', 'btn-primary', 'mx-1');
        saveButton.onclick = saveNote;

        // Create a cancel button
        var cancelButton = document.createElement('button');
        cancelButton.innerHTML = '<i class="fas fa-times"></i>';
        cancelButton.classList.add('btn', 'btn-sm', 'btn-danger');
        cancelButton.onclick = cancelAddNote;

        // Append the input and buttons to the appropriate containers
        notesContainer.appendChild(input);
        buttonsContainer.innerHTML = ''; // Clear existing buttons
        buttonsContainer.appendChild(saveButton);
        buttonsContainer.appendChild(cancelButton);
    }

    function showEditNoteInput() {
        var notesContainer = document.getElementById('appointment-notes');
        var buttonsContainer = document.getElementById('buttons-container');

        // Save the existing note text
        var currentNote = notesContainer.textContent.trim();

        // Create a text input element
        var input = document.createElement('input');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'note-input');
        input.setAttribute('value', currentNote);

        // Create a save button
        var saveButton = document.createElement('button');
        saveButton.innerHTML = '<i class="fas fa-save"></i>';
        saveButton.classList.add('btn', 'btn-sm', 'btn-success', 'mx-1');
        saveButton.onclick = saveNote;

        // Create a cancel button
        var cancelButton = document.createElement('button');
        cancelButton.innerHTML = '<i class="fas fa-times"></i>';
        cancelButton.classList.add('btn', 'btn-sm', 'btn-danger');
        cancelButton.onclick = cancelEditNote;

        // Append the input and buttons to the appropriate containers
        notesContainer.innerHTML = ''; // Clear existing content
        notesContainer.appendChild(input);
        buttonsContainer.innerHTML = ''; // Clear existing buttons
        buttonsContainer.appendChild(saveButton);
        buttonsContainer.appendChild(cancelButton);
    }

    function saveNote() {
        var notesContainer = document.getElementById('appointment-notes');
        var input = document.getElementById('note-input').value;
        
        // Send AJAX request to save the note
        var id = '{{ $appointment->appointment_id }}'; // Assuming you have access to the appointment ID in your Blade template
        var formData = new FormData();
        formData.append('notes', input);
        
        fetch('{{ route('appointment.addNote', ['id' => $appointment->appointment_id]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.reload();
            }
            throw new Error('Network response was not ok.');
        })
        .catch(error => {
            console.error('There was an error!', error);
        });
    }

    function cancelAddNote() {
        var notesContainer = document.getElementById('appointment-notes');
        var input = document.getElementById('note-input');
        notesContainer.removeChild(input);

        var buttonsContainer = document.getElementById('buttons-container');
        buttonsContainer.innerHTML = '';
        var addButton = document.createElement('button');
        addButton.innerHTML = '<i class="fas fa-plus"></i>';
        addButton.classList.add('btn', 'btn-sm', 'btn-warning');
        addButton.onclick = showAddNoteInput;
        buttonsContainer.appendChild(addButton);
    }

    function cancelEditNote() {
        var notesContainer = document.getElementById('appointment-notes');
        var buttonsContainer = document.getElementById('buttons-container');

        // Restore the original note text
        var originalNote = '{{ $appointment->notes }}';
        notesContainer.textContent = originalNote;

        // Restore the original buttons
        buttonsContainer.innerHTML = '';
        var editButton = document.createElement('button');
        editButton.innerHTML = '<i class="fas fa-edit"></i>';
        editButton.classList.add('btn', 'btn-sm', 'btn-warning');
        editButton.onclick = showEditNoteInput;
        buttonsContainer.appendChild(editButton);
    }
</script>
@endsection