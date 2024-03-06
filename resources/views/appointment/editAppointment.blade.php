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
            if (fullyBookedDates.includes(formattedDate)) {
                return [true, "highlight-fullybooked"];
                
            } else {
                return [true, "highlight-available"];
            }
        }

        return [isTuesdayOrThursday && isNotHoliday, ""];
    }  
    {{ old('barangay') == 'Bagong Silang' ? 'selected' : '' }}

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
	<div class="pb-5">
        <div class="d-flex align-items-center justify-content-start addStaff mb-4">
            @if($appointment->appointment_status == 'Pending' || $appointment->appointment_status == 'Declined')
                <a href="{{ route('appointment.pendingAppointment') }}" class="fas fa-angle-left fs-4"></a>
            @elseif($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled')
                <a href="{{ route('appointment') }}" class="fas fa-angle-left fs-4"></a>
            @else
                <a href="{{ route('appointment.finishedAppointment') }}" class="fas fa-angle-left fs-4"></a>
            @endif
            <h1 class="mb-0 ml-4">Edit Appointment ID: {{ $appointment->appointment_id }}'s Details</h1>
        </div>

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

        <div class="p-5 card shadow editclientDetailsForm">
            <form action="{{ route('appointment.editAppointment', $appointment->appointment_id) }}" method="POST" id="appointmentForm" class="user" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="appointment_status">Appointment Status:</label>
                    <select class="form-control @error('appointment_status') is-invalid @enderror" name="appointment_status" id="appointment_status" disabled>
                        <option value="Booked" {{ old('appointment_status', $appointment->appointment_status) == 'Booked' ? 'selected' : '' }}>Booked</option>
                        <option value="Rescheduled" {{ old('appointment_status', $appointment->appointment_status) == 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                        <option value="Finished" {{ old('appointment_status', $appointment->appointment_status) == 'Finished' ? 'selected' : '' }}>Finished</option>
                        <option value="Cancelled" {{ old('appointment_status', $appointment->appointment_status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Pending" {{ old('appointment_status', $appointment->appointment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Declined" {{ old('appointment_status', $appointment->appointment_status) == 'Declined' ? 'selected' : '' }}>Declined</option>
                        <option value="No-Show" {{ old('appointment_status', $appointment->appointment_status) == 'No Show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    @error('appointment_status')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="hideForm" id="chooseDateTime">
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
                                <select class="form-control timeslot @error('timeslot') is-invalid @enderror" name="timeslot" id="timeslot">
                                    <option value="">Select Timeslot</option>
                                    <option class="timeslot-option" value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>14:00 - 14:10</option>
                                    <option class="timeslot-option" value="14:10" {{ old('appointment_time') == '14:10' ? 'selected' : '' }}>14:10 - 14:20</option>
                                    <option class="timeslot-option" value="14:20" {{ old('appointment_time') == '14:20' ? 'selected' : '' }}>14:20 - 14:30</option>
                                    <option class="timeslot-option" value="14:30" {{ old('appointment_time') == '14:30' ? 'selected' : '' }}>14:30 - 14:40</option>
                                    <option class="timeslot-option" value="14:40" {{ old('appointment_time') == '14:40' ? 'selected' : '' }}>14:40 - 14:50</option>
                                    <option class="timeslot-option" value="14:50" {{ old('appointment_time') == '14:50' ? 'selected' : '' }}>14:50 - 15:00</option>
                                    <option class="timeslot-option" value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>15:00 - 15:10</option>
                                    <option class="timeslot-option" value="15:10" {{ old('appointment_time') == '15:10' ? 'selected' : '' }}>15:10 - 15:20</option>
                                    <option class="timeslot-option" value="15:20" {{ old('appointment_time') == '15:20' ? 'selected' : '' }}>15:20 - 15:30</option>
                                    <option class="timeslot-option" value="15:30" {{ old('appointment_time') == '15:30' ? 'selected' : '' }}>15:30 - 15:40</option>
                                    <option class="timeslot-option" value="15:40" {{ old('appointment_time') == '15:40' ? 'selected' : '' }}>15:40 - 15:50</option>
                                    <option class="timeslot-option" value="15:50" {{ old('appointment_time') == '15:50' ? 'selected' : '' }}>15:50 - 15:00</option>
                                </select>
                                @error('timeslot')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                <p class="descriptionTimeslot">Please Select a Date</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="appointment_date">Current Date:</label>
                        <input name="appointment_date" id="appointment_date" type="text"
                            class="form-control form-control-user @error('appointment_date')is-invalid @enderror"
                            value="{{ old('appointment_date', $appointment->appointment_date) }}">
                        @error('appointment_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-sm-6">
                        <label for="appointment_time">Current Time:</label>
                        <input name="appointment_time" id="appointment_time" type="text"
                            class="form-control form-control-user @error('appointment_time')is-invalid @enderror"
                            value="{{ old('appointment_time', $appointment->appointment_time) }}">
                        @error('appointment_time')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <input name="name" id="name" type="text"
                        class="form-control form-control-user @error('name')is-invalid @enderror"
                        placeholder="Current Full Name: {{ $appointment->name }}" value="{{ old('name', $appointment->name) }}" disabled>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Client Address:</label>
                    <div class="form-check @error('city')is-invalid @enderror">
                        <input class="form-check-input" type="radio" name="city" id="san-pedro-city" value="San Pedro City" {{ old('city', $city) == 'San Pedro City' ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="san-pedro-city">
                            San Pedro City
                        </label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="city" id="other-city" value="Other City" {{ old('city', $city) != 'San Pedro City' ? 'checked' : '' }} disabled>
                        <label class="form-check-label" for="other-city">
                            Other City
                        </label>
                    </div>
                    @error('city')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror

                    <div class="form-group {{ old('city') == 'san-pedro-city' ? '' : 'd-none' }}" id="barangay-group">
                        <select name="barangay" id="barangay" class="form-control @error('barangay')is-invalid @enderror">
                            <option value="">-- Select Barangay --</option>
                            <option value="Bagong Silang" {{ old('barangay', $final_barangay) == 'Bagong Silang' ? 'selected' : '' }}>Bagong Silang</option>
                            <option value="Calendola" {{ old('barangay', $final_barangay) == 'Calendola' ? 'selected' : '' }}>Calendola</option>
                            <option value="Chrysanthemum" {{ old('barangay', $final_barangay) == 'Chrysanthemum' ? 'selected' : '' }}>Chrysanthemum</option>
                            <option value="Cuyab" {{ old('barangay', $final_barangay) == 'Cuyab' ? 'selected' : '' }}>Cuyab</option>
                            <option value="Estrella" {{ old('barangay', $final_barangay) == 'Estrella' ? 'selected' : '' }}>Estrella</option>
                            <option value="Fatima" {{ old('barangay', $final_barangay) == 'Fatima' ? 'selected' : '' }}>Fatima</option>
                            <option value="G.S.I.S" {{ old('barangay', $final_barangay) == 'G.S.I.S' ? 'selected' : '' }}>G.S.I.S</option>
                            <option value="Landayan" {{ old('barangay', $final_barangay) == 'Landayan' ? 'selected' : '' }}>Landayan</option>
                            <option value="Langgam" {{ old('barangay', $final_barangay) == 'Langgam' ? 'selected' : '' }}>Langgam</option>
                            <option value="Laram" {{ old('barangay', $final_barangay) == 'Laram' ? 'selected' : '' }}>Laram</option>
                            <option value="Magsaysay" {{ old('barangay', $final_barangay) == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
                            <option value="Maharlika" {{ old('barangay', $final_barangay) == 'Maharlika' ? 'selected' : '' }}>Maharlika</option>
                            <option value="Narra" {{ old('barangay', $final_barangay) == 'Narra' ? 'selected' : '' }}>Narra</option>
                            <option value="Nueva" {{ old('barangay', $final_barangay) == 'Nueva' ? 'selected' : '' }}>Nueva</option>
                            <option value="Pacita 1" {{ old('barangay', $final_barangay) == 'Pacita 1' ? 'selected' : '' }}>Pacita 1</option>
                            <option value="Pacita 2" {{ old('barangay', $final_barangay) == 'Pacita 2' ? 'selected' : '' }}>Pacita 2</option>
                            <option value="Poblacion" {{ old('barangay', $final_barangay) == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
                            <option value="Riverside" {{ old('barangay', $final_barangay) == 'Riverside' ? 'selected' : '' }}>Riverside</option>
                            <option value="Rosario" {{ old('barangay', $final_barangay) == 'Rosario' ? 'selected' : '' }}>Rosario</option>
                            <option value="Sampaguita Village" {{ old('barangay', $final_barangay) == 'Sampaguita Village' ? 'selected' : '' }}>Sampaguita Village</option>
                            <option value="San Antonio" {{ old('barangay', $final_barangay) == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
                            <option value="San Lorenzo Ruiz" {{ old('barangay', $final_barangay) == 'San Lorenzo Ruiz' ? 'selected' : '' }}>San Lorenzo Ruiz</option>
                            <option value="San Roque" {{ old('barangay', $final_barangay) == 'San Roque' ? 'selected' : '' }}>San Roque</option>
                            <option value="San Vicente" {{ old('barangay', $final_barangay) == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
                            <option value="Santo Niño" {{ old('barangay', $final_barangay) == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
                            <option value="United Bayanihan" {{ old('barangay', $final_barangay) == 'United Bayanihan' ? 'selected' : '' }}>United Bayanihan</option>
                            <option value="United Better Living" {{ old('barangay', $final_barangay) == 'United Better Living' ? 'selected' : '' }}>United Better Living</option>
                        </select>
                        @error('barangay')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group {{ old('city') == 'san-pedro-city' ? '' : 'd-none' }}" id="street-group">
                        <input name="street" id="street" type="text"
                            class="form-control form-control-user @error('street')is-invalid @enderror"
                            id="street" placeholder="Street Address" value="{{ old('street', $street) }}">
                        @error('street')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group {{ old('city') == 'other-city' ? '' : 'd-none' }}" id="other-address-group">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <input name="other_city" id="other-city-input" type="text"
                                    class="form-control form-control-user @error('other_city')is-invalid @enderror"
                                    placeholder="City (e.g. Biñan City)" value="{{ old('other_city', $other_city) }}">
                                @error('other_city')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-sm-6">
                                <input name="other_barangay" id="other-barangay" type="text"
                                    class="form-control form-control-user @error('other_barangay')is-invalid @enderror"
                                    placeholder="Barangay" value="{{ old('other_barangay', $other_barangay) }}">
                                @error('other_barangay')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <input name="other_street" id="other-street" type="text"
                                class="form-control form-control-user @error('other_street')is-invalid @enderror"
                                placeholder="Street Address" value="{{ old('other_street', $other_street) }}">
                            @error('other_street')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @error('city')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="contact_details">Contact Details:</label>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input name="cellphone_number" type="tel"
                            class="form-control form-control-user @error('cellphone_number')is-invalid @enderror"
                            id="cellphone" placeholder="Current Cellphone Number: {{ $appointment->cellphone_number }}" value="{{ old('cellphone_number', $appointment->cellphone_number) }}"
                            disabled>
                        @error('cellphone_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <input name="email" id="email" type="email"
                            class="form-control form-control-user @error('email')is-invalid @enderror"
                            id="email" placeholder="Current Email Address: {{ $appointment->email }}" value="{{ old('email', $appointment->email) }}" disabled>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary btn-user btn-block hideBtn" id="updateAppointmentBtn">Update Appointment</button>
                <button type="button" class="btn btn-primary btn-user btn-block" id="editAppointmentBtn">Edit Appointment</button>
            </form>
        </div>
    </div>

<script>
    // Store original values when page loads
    const originalAppointmentStatus = "{{ $appointment->appointment_status }}";
    const originalAppointmentDate = '{{ $appointment->appointment_date }}';
    const originalAppointmentTime = "{{ $appointment->appointment_time }}";
    const originalName =  "{{ $appointment->name }}";
    const originalCity = "{{ $city }}";
    const originalBarangay = "{{ $final_barangay }}";
    const originalStreet = "{{ $street }}";
    const originalOtherCity = "{{ $other_city }}";
    const originalOtherBarangay = "{{ $other_barangay }}";
    const originalOtherStreet = "{{ $other_street }}";
    const originalCellphoneNumber = "{{ $appointment->cellphone_number }}";
    const originalEmail =  "{{ $appointment->email }}";

    const editAppointmentBtn = document.getElementById("editAppointmentBtn");

    const appointmentDate = document.getElementById("appointment_date");
    const appointmentTime = document.getElementById("appointment_time");
    appointmentDate.readOnly = true;
    appointmentTime.readOnly = true;

    editAppointmentBtn.addEventListener("click", async () => {
        const updateAppointmentBtn = document.getElementById("updateAppointmentBtn");
        const appointmentStatus = document.getElementById("appointment_status");
        const dateTimeForm = document.getElementById("chooseDateTime");
        const name = document.getElementById("name")
        const cellphoneNumber = document.getElementById("cellphone")
        const email = document.getElementById("email");
        const sanPedroCityRadio = document.getElementById("san-pedro-city");
        const otherCityRadio = document.getElementById("other-city");

        const barangayGroup = document.getElementById("barangay-group");
        const streetGroup = document.getElementById("street-group");
        const barangay = document.getElementById("barangay");
        const street = document.getElementById("street");

        const otherAddressGroup = document.getElementById("other-address-group");
        const otherCityInput = document.getElementById("other-city-input");
        const otherBarangay = document.getElementById("other-barangay");
        const otherStreet = document.getElementById("other-street");

        if (editAppointmentBtn.textContent === "Edit Appointment") {
            editAppointmentBtn.textContent = "Cancel";
            editAppointmentBtn.classList.remove("btn-primary");
            editAppointmentBtn.classList.add("btn-danger");
            appointmentStatus.disabled = false;
            dateTimeForm.classList.remove("hideForm");
            dateTimeForm.classList.add("showForm");
            name.disabled = false;
            cellphoneNumber.disabled = false;
            email.disabled = false;
            sanPedroCityRadio.disabled = false;
            otherCityRadio.disabled = false;
            updateAppointmentBtn.classList.remove("hideBtn");
            updateAppointmentBtn.classList.add("showBtn");

            // Show barangay and street fields
            if(sanPedroCityRadio.checked) {
                barangayGroup.classList.remove('d-none');
                streetGroup.classList.remove('d-none');
            } else if (otherCityRadio.checked) {
                otherAddressGroup.classList.remove('d-none');
            }
        } else {
            editAppointmentBtn.textContent = "Edit Appointment";
            editAppointmentBtn.classList.remove("btn-danger");
            editAppointmentBtn.classList.add("btn-primary");
            appointmentStatus.disabled = true;
            dateTimeForm.classList.remove("showForm");
            dateTimeForm.classList.add("hideForm");
            name.disabled = true;
            cellphoneNumber.disabled = true;
            email.disabled = true;
            sanPedroCityRadio.disabled = true;
            otherCityRadio.disabled = true;

            // Hide update button
            updateAppointmentBtn.classList.remove("showBtn");
            updateAppointmentBtn.classList.add("hideBtn");

            // Reset fields to original values
            appointmentStatus.value = originalAppointmentStatus;
            appointmentDate.value = originalAppointmentDate;
            appointmentTime.value = originalAppointmentTime;
            name.value = originalName;
            cellphoneNumber.value = originalCellphoneNumber;
            email.value = originalEmail;

            if (originalCity === 'San Pedro City') {
                sanPedroCityRadio.checked = true;
                otherCityRadio.checked = false;
                barangay.value = originalBarangay;
                street.value = originalStreet;
                otherCityInput.value = originalOtherCity;
                otherBarangay.value = originalOtherBarangay;
                otherStreet.value = originalOtherStreet;
            } else {
                sanPedroCityRadio.checked = false;
                otherCityRadio.checked = true;
                otherCityInput.value = originalCity;
                otherBarangay.value = originalOtherBarangay;
                otherStreet.value = originalOtherStreet;
                barangay.value = originalBarangay;
                street.value = originalStreet;
            }

            // Hide barangay and street fields
            barangayGroup.classList.add('d-none');
            streetGroup.classList.add('d-none');
            otherAddressGroup.classList.add('d-none');   
            
            const errorElements = document.querySelectorAll('.invalid-feedback');
			errorElements.forEach(errorElement => {
				errorElement.remove();
			});

			const inputElements = document.querySelectorAll('.is-invalid');
			inputElements.forEach(inputElement => {
				inputElement.classList.remove('is-invalid');
			});
        }

        sanPedroCityRadio.addEventListener('change', () => {
            if (sanPedroCityRadio.checked) {
                barangayGroup.classList.remove('d-none');
                streetGroup.classList.remove('d-none');
                otherAddressGroup.classList.add('d-none');
            }
        });

        otherCityRadio.addEventListener('change', () => {
            if (otherCityRadio.checked) {
                barangayGroup.classList.add('d-none');
                streetGroup.classList.add('d-none');
                otherAddressGroup.classList.remove('d-none');
            }
        });

        otherCityRadio.addEventListener('click', () => {
            if (!otherCityRadio.checked) {
                barangayGroup.classList.add('d-none');
                streetGroup.classList.add('d-none');
                otherAddressGroup.classList.add('d-none');
                document.getElementById('barangay').value = '';
                document.getElementById('street').value = '';
                document.getElementById('other-address').value = '';
            }
        });

        const appointmentDateValue = appointmentDate.value;
    
        appointmentDate.addEventListener('change', () => {
            if (appointmentDate.value !== appointmentDateValue) {
                // The date has changed, so clear the appointment time
                appointmentTime.value = "";
            }
        });
    });


    $("#timeslot").change(function() {
        // Check if a timeslot is selected
        if ($(this).val() !== '') {            
            $('html, body').animate({
                scrollTop: $("#dateLabel").offset().top
            }, 100);

            // Set the selected time to the hidden input
            $("#appointment_time").val($(this).val());
        }
    });

    const updateAppointmentBtn = document.getElementById("updateAppointmentBtn");

    updateAppointmentBtn.addEventListener("click", async () => {
        const appointmentForm = document.getElementById("appointmentForm");
        const formData = new FormData(appointmentForm);

        try {
            const response = await fetch('{{ route('appointment.validateEditForm', $appointment->appointment_id) }}', {
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