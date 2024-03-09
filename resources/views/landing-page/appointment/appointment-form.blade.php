@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Appointment')

@section('scripts')
<script>
    let fullyBookedDates = [];
    let availableDates = [];

    fetch('{{ route('checkDateAvailability') }}')
        .then(response => response.json())
        .then(data => {
            fullyBookedDates = data.fullyBookedDates;

            // Initialize the datepicker after the data has been fetched
            $("#datepicker").datepicker({
                minDate: 1,
                changeMonth: true,
                changeYear: true,
                beforeShowDay: dateFilter,
                onSelect: timeslotChecker,
                altField: "#appointment_date",
                altFormat: "yy-mm-dd"
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
                const response = await fetch('{{ route('checkTimeAvailability') }}', {
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
                    <p class="align-items-left committeeName">Appointment Form</p>
                    <a href="{{ route('home') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
            </header>
    
            <div class="form-group row d-flex align-items-center justify-content-center">
                <div class="col-sm-6">
                    <div class="datepickerContainer">
                        <label for="datepicker">Appointment Date:</label>
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

                <div class="col-sm-6 d-flex align-items-center justify-content-center">
                    <div class="form-group">
                        <label for="timeslot">Appointment Time:</label>
                        <select class="form-control timeslot @error('timeslot') is-invalid @enderror" name="appointment_time" id="timeslot">
                            <option value="">Select Timeslot</option>
                            <option class="timeslot-option" value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>2:00 PM - 2:10 PM</option>
                            <option class="timeslot-option" value="14:10" {{ old('appointment_time') == '14:10' ? 'selected' : '' }}>2:10 PM - 2:20 PM</option>
                            <option class="timeslot-option" value="14:20" {{ old('appointment_time') == '14:20' ? 'selected' : '' }}>2:20 PM - 2:30 PM</option>
                            <option class="timeslot-option" value="14:30" {{ old('appointment_time') == '14:30' ? 'selected' : '' }}>2:30 PM - 2:40 PM</option>
                            <option class="timeslot-option" value="14:40" {{ old('appointment_time') == '14:40' ? 'selected' : '' }}>2:40 PM - 2:50 PM</option>
                            <option class="timeslot-option" value="14:50" {{ old('appointment_time') == '14:50' ? 'selected' : '' }}>2:50 PM - 3:00 PM</option>
                            <option class="timeslot-option" value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>3:00 PM - 3:10 PM</option>
                            <option class="timeslot-option" value="15:10" {{ old('appointment_time') == '15:10' ? 'selected' : '' }}>3:10 PM - 3:20 PM</option>
                            <option class="timeslot-option" value="15:20" {{ old('appointment_time') == '15:20' ? 'selected' : '' }}>3:20 PM - 3:30 PM</option>
                            <option class="timeslot-option" value="15:30" {{ old('appointment_time') == '15:30' ? 'selected' : '' }}>3:30 PM - 3:40 PM</option>
                            <option class="timeslot-option" value="15:40" {{ old('appointment_time') == '15:40' ? 'selected' : '' }}>3:40 PM - 3:50 PM</option>
                            <option class="timeslot-option" value="15:50" {{ old('appointment_time') == '15:50' ? 'selected' : '' }}>3:50 PM - 4:00 PM</option>
                        </select>
                        @error('timeslot')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        <p class="descriptionTimeslot">Please Select a Date</p>
                    </div>
                </div>
            </div>
    
            @include('landing-page.appointment.clientDetailsForm')
            
        </div>
    </section>

<script>
    $("#timeslot").change(function() {
        // Check if a timeslot is selected
        if ($(this).val() !== '') {
            // Show the clientDetailsForm
            $(".clientDetailsForm").css("display", "block");
            
            $('html, body').animate({
                scrollTop: $("#dateLabel").offset().top
            }, 100);

            // Set the selected time to the hidden input
            $("#appointment_time").val($(this).val()).prop("readonly", true);
            $("#appointment_date").prop("readonly", true);
            
        } else {
            // Hide the clientDetailsForm if no timeslot is selected
            $(".clientDetailsForm").css("display", "none");
        }
    });

    const createAppointmentBtn = document.getElementById("createAppointmentBtn");

    createAppointmentBtn.addEventListener("click", async () => {
        const appointmentForm = document.getElementById("appointmentForm");
        const formData = new FormData(appointmentForm);

        try {
            const response = await fetch('{{ route('appointmentValidateForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                },
                body: formData,
            });

            const data = await response.json();

            if (data.message === 'Validation failed') {
                document.getElementById('clientDetailsForm').style.display = 'block';

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
                appointmentForm.submit();
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