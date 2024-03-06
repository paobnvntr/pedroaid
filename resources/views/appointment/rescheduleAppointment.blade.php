@extends('layouts.app')

@section('scripts')
<script>
    let fullyBookedDates = [];
    let availableDates = [];

    fetch('{{ route('appointment.checkDateAvailability') }}')
        .then(response => response.json())
        .then(data => {
            fullyBookedDates = data.fullyBookedDates;

            // Initialize the datepicker after the data has been fetched
            $("#datepicker").datepicker({
                minDate: 1,
                changeMonth: true,
                changeYear: true,
                beforeShowDay: dateFilter,
                onSelect: function (dateText, inst) {
                    // Set the selected date to the appointment_date input
                    $("#appointment_date").val($.datepicker.formatDate('yy-mm-dd', new Date(dateText)));

                    // Call your timeslotChecker function if needed
                    timeslotChecker(dateText);
                },
            });
        });

    function dateFilter(date) {
        var day = date.getDay();
        var isTuesdayOrThursday = day == 2 || day == 4;
        var isNotHoliday = !isHoliday(date);
        var formattedDate = date.toLocaleDateString("en-US", {
            month: "2-digit",
            day: "2-digit",
            year: "numeric"
        }).replace(/(\d+)\/(\d+)\/(\d+)/, "$3-$1-$2");

        if (isTuesdayOrThursday && isNotHoliday) {
            // console.log(formattedDate);
            if (fullyBookedDates.includes(formattedDate)) {
                return [true, "highlight-fullybooked"];
            } else {
                return [true, "highlight-available"];
            }
        }

        return [isTuesdayOrThursday && isNotHoliday, ""];
    }  

    function isHoliday(date) {
        const holidays = [
            `01-01`,
            `02-25`,
            `04-09`,
            `04-15`,
            `04-21`,
            `05-01`,
            `06-12`,
            `08-30`,
            `11-01`,
            `11-30`,
            `12-25`,
            `12-30`
        ];
        const formattedDate = $.datepicker.formatDate('mm-dd', date);
        return holidays.includes(formattedDate);
    }

    function timeslotChecker(dateText, inst) {
        var selectedDate = new Date(dateText);
        var timeslots = $('.timeslot-option'); // Assuming you have an element with the ID 'timeslot'
        var formattedDate = selectedDate.toLocaleDateString("en-US", {
            month: "2-digit",
            day: "2-digit",
            year: "numeric"
        }).replace(/(\d+)\/(\d+)\/(\d+)/, "$3-$1-$2");

        // Make an AJAX request to check availability
        timeslots.each(async function (index, timeslotButton) {
            const timeslot = $(timeslotButton).val();

            try {
                const response = await fetch('{{ route('appointment.checkTimeAvailability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                    },
                    body: JSON.stringify({ selectedDate: formattedDate, timeslot }),
                });

                const data = await response.json();

                if (data.message === 'Timeslot is available') {
                    // Timeslot is available
                    timeslotButton.disabled = false;
                    timeslotButton.classList.remove("timeslot-booked");
                    $(".timeslot").css("display", "block");
                    $(".descriptionTimeslot").css("display", "none");
                } else if (data.message === 'Timeslot is not available') {
                    // Timeslot is not available
                    timeslotButton.disabled = true;
                    timeslotButton.classList.add("timeslot-booked");
                    $(".timeslot").css("display", "block");
                    $(".descriptionTimeslot").css("display", "none");
                } else {
                    // Something went wrong
                    console.error(data.message);
                }
            } catch (error) {
                console.error("Error checking availability:", error);
            }
        });
    }
</script>
@endsection

@section('contents')
    <div class="d-flex align-items-center justify-content-start addStaff mb-4">
	    <a href="{{ route('appointment.appointmentDetails', $appointment->appointment_id) }}" class="fas fa-angle-left fs-4"></a>
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

                <div class="trackerAppointmentContact">
                    <h3 class="text-center">Reschedule Appointment</h3>
                </div>
                
                <hr>
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="form-group row d-flex align-items-center justify-content-center">
                        <div class="col-sm-12">
                            <div class="datepickerContainer">
                                <label for="datepicker">New Appointment Date:</label>
                            </div>
                            <div class="datepickerContainer">
                                <div name="selectedDate" id="datepicker"
                                class="@error('selectedDate')is-invalid @enderror">{{ old('selectedDate') }}</div>
                                @error('selectedDate')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="d-flex align-items-center justify-content-center m-4" id="dateLabel">
                                <div class="appointment-availability available">Available</div>
                                <div class="appointment-availability fullybooked ml-4">Fully Booked</div>
                            </div>
                        </div>

                        <div class="col-sm-12 d-flex align-items-center justify-content-center">
                            <div class="form-group">
                                <label for="timeslot">New Appointment Time:</label>
                                <select class="form-control timeslot @error('timeslot') is-invalid @enderror" name="timeslot" id="timeslot">
                                    <option value="">Select Timeslot</option>
                                    <option class="timeslot-option" value="13:00" {{ old('appointment_time') == '13:00' ? 'selected' : '' }}>13:00 - 13:10</option>
                                    <option class="timeslot-option" value="13:10" {{ old('appointment_time') == '13:10' ? 'selected' : '' }}>13:10 - 13:20</option>
                                    <option class="timeslot-option" value="13:20" {{ old('appointment_time') == '13:20' ? 'selected' : '' }}>13:20 - 13:30</option>
                                    <option class="timeslot-option" value="13:30" {{ old('appointment_time') == '13:30' ? 'selected' : '' }}>13:30 - 13:40</option>
                                    <option class="timeslot-option" value="13:40" {{ old('appointment_time') == '13:40' ? 'selected' : '' }}>13:40 - 13:50</option>
                                    <option class="timeslot-option" value="13:50" {{ old('appointment_time') == '13:50' ? 'selected' : '' }}>13:50 - 14:00</option>
                                    <option class="timeslot-option" value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>14:00 - 14:10</option>
                                    <option class="timeslot-option" value="14:10" {{ old('appointment_time') == '14:10' ? 'selected' : '' }}>14:10 - 14:20</option>
                                    <option class="timeslot-option" value="14:20" {{ old('appointment_time') == '14:20' ? 'selected' : '' }}>14:20 - 14:30</option>
                                    <option class="timeslot-option" value="14:30" {{ old('appointment_time') == '14:30' ? 'selected' : '' }}>14:30 - 14:40</option>
                                    <option class="timeslot-option" value="14:40" {{ old('appointment_time') == '14:40' ? 'selected' : '' }}>14:40 - 14:50</option>
                                    <option class="timeslot-option" value="14:50" {{ old('appointment_time') == '14:50' ? 'selected' : '' }}>14:50 - 15:00</option>
                                </select>
                                @error('timeslot')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <p class="descriptionTimeslot">Please Select a Date</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('appointment.appointmentReschedule', $appointment->appointment_id) }}" method="POST" class="user" enctype="multipart/form-data" id="rescheduleForm">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <label for="appointment_date">Selected Date:</label>
                            <input name="appointment_date" id="appointment_date" type="text"
                                class="form-control form-control-user @error('appointment_date')is-invalid @enderror"
                                value="{{ old('appointment_date') }}">
                            @error('appointment_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="appointment_time">Selected Time:</label>
                            <input name="appointment_time" id="appointment_time" type="text"
                                class="form-control form-control-user @error('appointment_time')is-invalid @enderror"
                                value="{{ old('appointment_time') }}">
                            @error('appointment_time')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between row">
                        <button type="button" class="btn btn-primary col-sm-3" id="rescheduleBtn">Reschedule</button>
                        <a href="{{ route('appointment.appointmentDetails', $appointment->appointment_id) }}" class="btn btn-danger col-sm-3">Cancel</a>
                    </div>
                </form>
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
    const appointmentDate = document.getElementById("appointment_date");
    const appointmentTime = document.getElementById("appointment_time");
    appointmentDate.readOnly = true;
    appointmentTime.readOnly = true;

    // Check if a date is selected
    $("#timeslot").change(function() {
        // Check if a timeslot is selected
        if ($(this).val() !== '') {
            // Set the selected time to the hidden input
            $("#appointment_time").val($(this).val());
        }
    });

    const rescheduleBtn = document.getElementById("rescheduleBtn");

    rescheduleBtn.addEventListener("click", async () => {
        const rescheduleForm = document.getElementById("rescheduleForm");
        const formData = new FormData(rescheduleForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('appointment.validateReschedule', $appointment->appointment_id) }}', {
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
                rescheduleForm.submit();
                console.log('Validation passed');
            } else {
                console.log('Other errors');
            }

        } catch (error) {
            console.error('An error occurred:', error);
        }
    });
</script>
@endsection