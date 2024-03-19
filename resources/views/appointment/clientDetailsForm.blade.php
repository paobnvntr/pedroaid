<div class="p-5 card shadow clientDetailsForm" id="clientDetailsForm">
    <form action="{{ route('appointment.saveAppointment') }}" method="POST" id="appointmentForm" class="user" enctype="multipart/form-data">
    @csrf
    <div class="form-group row appointmentDisabled">
        <div class="col-sm-6 mb-3 mb-sm-0">
            <input name="appointment_date" id="appointment_date" type="text"
                class="form-control form-control-user @error('appointment_date')is-invalid @enderror">
            @error('appointment_date')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <input name="appointment_time" id="appointment_time" type="hidden">
            <input name="selected_timeslot_display" id="selected_timeslot_display" type="text"
                class="form-control form-control-user @error('appointment_time')is-invalid @enderror">
            @error('appointment_time')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <input name="name" id="name" type="text"
            class="form-control form-control-user @error('name')is-invalid @enderror"
            placeholder="Full Name (e.g. Juan Dela Cruz)" value="{{ old('name') }}">
        @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="address">Client Address:</label>
        <div class="form-check @error('city')is-invalid @enderror">
            <input class="form-check-input" type="radio" name="city" id="san-pedro-city" value="San Pedro City" {{ old('city') == 'san-pedro-city' ? 'checked' : '' }}>
            <label class="form-check-label" for="san-pedro-city">
                San Pedro City
            </label>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="city" id="other-city" value="Other City" {{ old('city') == 'other-city' ? 'checked' : '' }}>
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
                <option value="Bagong Silang" {{ old('barangay') == 'Bagong Silang' ? 'selected' : '' }}>Bagong Silang</option>
                <option value="Calendola" {{ old('barangay') == 'Calendola' ? 'selected' : '' }}>Calendola</option>
                <option value="Chrysanthemum" {{ old('barangay') == 'Chrysanthemum' ? 'selected' : '' }}>Chrysanthemum</option>
                <option value="Cuyab" {{ old('barangay') == 'Cuyab' ? 'selected' : '' }}>Cuyab</option>
                <option value="Estrella" {{ old('barangay') == 'Estrella' ? 'selected' : '' }}>Estrella</option>
                <option value="Fatima" {{ old('barangay') == 'Fatima' ? 'selected' : '' }}>Fatima</option>
                <option value="G.S.I.S" {{ old('barangay') == 'G.S.I.S' ? 'selected' : '' }}>G.S.I.S</option>
                <option value="Landayan" {{ old('barangay') == 'Landayan' ? 'selected' : '' }}>Landayan</option>
                <option value="Langgam" {{ old('barangay') == 'Langgam' ? 'selected' : '' }}>Langgam</option>
                <option value="Laram" {{ old('barangay') == 'Laram' ? 'selected' : '' }}>Laram</option>
                <option value="Magsaysay" {{ old('barangay') == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
                <option value="Maharlika" {{ old('barangay') == 'Maharlika' ? 'selected' : '' }}>Maharlika</option>
                <option value="Narra" {{ old('barangay') == 'Narra' ? 'selected' : '' }}>Narra</option>
                <option value="Nueva" {{ old('barangay') == 'Nueva' ? 'selected' : '' }}>Nueva</option>
                <option value="Pacita 1" {{ old('barangay') == 'Pacita 1' ? 'selected' : '' }}>Pacita 1</option>
                <option value="Pacita 2" {{ old('barangay') == 'Pacita 2' ? 'selected' : '' }}>Pacita 2</option>
                <option value="Poblacion" {{ old('barangay') == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
                <option value="Riverside" {{ old('barangay') == 'Riverside' ? 'selected' : '' }}>Riverside</option>
                <option value="Rosario" {{ old('barangay') == 'Rosario' ? 'selected' : '' }}>Rosario</option>
                <option value="Sampaguita Village" {{ old('barangay') == 'Sampaguita Village' ? 'selected' : '' }}>Sampaguita Village</option>
                <option value="San Antonio" {{ old('barangay') == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
                <option value="San Lorenzo Ruiz" {{ old('barangay') == 'San Lorenzo Ruiz' ? 'selected' : '' }}>San Lorenzo Ruiz</option>
                <option value="San Roque" {{ old('barangay') == 'San Roque' ? 'selected' : '' }}>San Roque</option>
                <option value="San Vicente" {{ old('barangay') == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
                <option value="Santo Niño" {{ old('barangay') == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
                <option value="United Bayanihan" {{ old('barangay') == 'United Bayanihan' ? 'selected' : '' }}>United Bayanihan</option>
                <option value="United Better Living" {{ old('barangay') == 'United Better Living' ? 'selected' : '' }}>United Better Living</option>
            </select>
            @error('barangay')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group {{ old('city') == 'san-pedro-city' ? '' : 'd-none' }}" id="street-group">
            <input name="street" id="street" type="text"
                class="form-control form-control-user @error('street')is-invalid @enderror"
                id="street" placeholder="Street Address" value="{{ old('street') }}">
            @error('street')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group {{ old('city') == 'other-city' ? '' : 'd-none' }}" id="other-address-group">
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input name="other_city" id="other-city" type="text"
                        class="form-control form-control-user @error('other_city')is-invalid @enderror"
                        id="other-city" placeholder="City (e.g. Biñan City)" value="{{ old('other_city') }}">
                    @error('other_city')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-sm-6">
                    <input name="other_barangay" id="other-barangay" type="text"
                        class="form-control form-control-user @error('other_barangay')is-invalid @enderror"
                        id="other-barangay" placeholder="Barangay" value="{{ old('other_barangay') }}">
                    @error('other_barangay')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <input name="other_street" id="other-street" type="text"
                    class="form-control form-control-user @error('other_street')is-invalid @enderror"
                    id="other-street" placeholder="Street Address" value="{{ old('other_street') }}">
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
                class="form-control form-control-user @error('cellphone_number')is-invalid @enderror" maxlength="11"
                id="cellphone" placeholder="Cellphone Number (e.g. 09XXXXXXXXX)" value="{{ old('cellphone_number') }}"
                >
            @error('cellphone_number')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-sm-6">
            <input name="email" id="email" type="email"
                class="form-control form-control-user @error('email')is-invalid @enderror" maxlength="320"
                id="email" placeholder="Email Address (e.g. juandelacruz@gmail.com)" value="{{ old('email') }}">
            @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    
    <button type="button" class="btn btn-primary btn-user btn-block" id="createAppointmentBtn">Create Appointment</button>
    </form>
</div>

<script>
    const sanPedroCityRadio = document.getElementById('san-pedro-city');
    const otherCityRadio = document.getElementById('other-city');
    const barangayGroup = document.getElementById('barangay-group');
    const streetGroup = document.getElementById('street-group');
    const otherAddressGroup = document.getElementById('other-address-group');

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
</script>