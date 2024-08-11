@extends('layouts.app')

@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('appointment.appointmentDetails', $appointment->appointment_id) }}" class="fas fa-angle-left fs-4"></a>
        <h1 class="mb-0 ml-4">Appointment Details</h1>
    </div>

    <div class="pt-4 pb-4">
        <div class="d-flex justify-content-center row">
            <div class="card shadow p-5 col-sm-6 mb-sm-0 trackerDetailsForm">
                <div class="trackerAppointmentContact">
                    <h3 class="text-center">Edit Feedback Form</h3>
                </div>
                
                <hr>
                <div>
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
                    <form action="{{ route('appointment.saveEditFeedback', ['id' => $appointment->appointment_id, 'type' => 'Appointment']) }}" method="POST" class="user feedback-form" id="sendFeedbackForm">
                        @csrf
                        <div class="mb-3">
                            <label class="rating-label" for="rating">Service Rating:</label>
                            
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" name="rating" value="Poor" {{ old('rating', $rating) == 'Poor' ? 'checked' : '' }} id="rating-poor">
                                <label class="form-check-label" for="rating-poor">
                                    Poor
                                </label>
                            </div>
                            
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" name="rating" value="Fair" {{ old('rating', $rating) == 'Fair' ? 'checked' : '' }} id="rating-fair">
                                <label class="form-check-label" for="rating-fair">
                                    Fair
                                </label>
                            </div>
                            
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" name="rating" value="Good" {{ old('rating', $rating) == 'Good' ? 'checked' : '' }} id="rating-good">
                                <label class="form-check-label" for="rating-good">
                                    Good
                                </label>
                            </div>

                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" name="rating" value="Very Good" {{ old('rating', $rating) == 'Very Good' ? 'checked' : '' }} id="rating-verygood">
                                <label class="form-check-label" for="rating-verygood">
                                    Very Good
                                </label>
                            </div>
                            
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="radio" name="rating" value="Excellent" {{ old('rating', $rating) == 'Excellent' ? 'checked' : '' }} id="rating-excellent">
                                <label class="form-check-label" for="rating-excellent">
                                    Excellent
                                </label>
                            </div>
                        </div>

                        <label class="rating-label" for="comment">Comment:</label>
                        <textarea class="form-control form-control-textbox" id="comment" name="comment" rows="3" placeholder="Type Comment">{{ old('comment', $comment) }}</textarea>

                        <button type="button" class="btn btn-primary btn-block mt-3" id="sendFeedbackBtn">Edit Feedback</button>
                    </form>
                </div>
            </div>
            <div class="card shadow col-sm-3 trackerAppointmentDetails">
                <div class="d-flex flex-column justify-content-center">
                    <h3 class="text-center">Appointment Details</h3>
                    <p><strong>Appointment ID:</strong> {{ $appointment->appointment_id }}</p>
                    <p><strong>Status:</strong> {{ $appointment->appointment_status }}</p>
                    <p><strong>Appointment Date:</strong> {{ $appointment->appointment_date }}</p>
                    <p><strong>Appointment Time:</strong> {{ $appointment->appointment_time }}</p>
                    <p><strong>Created At:</strong> {{ $appointment->created_at }}</p>
                    <p><strong>Updated At:</strong> {{ $appointment->updated_at }}</p>
                </div>
            </div>
        </div>
    </div>

<script>
    const sendFeedbackBtn = document.getElementById("sendFeedbackBtn");

    sendFeedbackBtn.addEventListener("click", async () => {
        const sendFeedbackForm = document.getElementById("sendFeedbackForm");
        const formData = new FormData(sendFeedbackForm);

        try {
            const response = await fetch('{{ route('appointment.validateEditFeedbackForm', ['id' => $appointment->appointment_id, 'type' => 'Appointment']) }}', {
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
                sendFeedbackForm.submit();
                console.log('Validation passed');
            } else {
                console.log('Other errors');
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