<div class="form-group text-center">
    <label>Affidavit Of No Income Information:</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="document_name" id="document_name" type="text"
            class="form-control form-control-user @error('document_name')is-invalid @enderror"
            placeholder="Current Name: {{ $additional_info->aoni_name }}" value="{{ old('document_name', $additional_info->aoni_name) }}" disabled>
    </div>

    <div class="col-sm-6">
        <input name="document_age" id="document_age" type="number"
            class="form-control form-control-user @error('document_age')is-invalid @enderror"
            id="document_age" placeholder="Current Age: {{ $additional_info->aoni_age }}" value="{{ old('document_age', $additional_info->aoni_age) }}" disabled>
    </div>
</div>

<div class="form-group">
    <label for="document_address">Address:</label>
    <div class="form-check @error('document_city')is-invalid @enderror">
        <input class="form-check-input" type="radio" name="document_city" id="document-san-pedro-city" value="San Pedro City" {{ old('document_city', $document_city) == 'San Pedro City' ? 'checked' : '' }} disabled>
        <label class="form-check-label" for="document-san-pedro-city">
            San Pedro City
        </label>
    </div>

    <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="document_city" id="document-other-city" value="Other City" {{ old('document_city', $document_city) != 'San Pedro City' ? 'checked' : '' }} disabled>
        <label class="form-check-label" for="document-other-city">
            Other City
        </label>
    </div>
    @error('document_city')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    <div class="form-group {{ old('document_city') == 'document-san-pedro-city' ? '' : 'd-none' }}" id="document-barangay-group">
        <select name="document_barangay" id="document-barangay" class="form-control @error('document_barangay')is-invalid @enderror">
            <option value="">-- Select Barangay --</option>
            <option value="Bagong Silang" {{ old('document_barangay', $document_final_barangay) == 'Bagong Silang' ? 'selected' : '' }}>Bagong Silang</option>
            <option value="Calendola" {{ old('document_barangay', $document_final_barangay) == 'Calendola' ? 'selected' : '' }}>Calendola</option>
            <option value="Chrysanthemum" {{ old('document_barangay', $document_final_barangay) == 'Chrysanthemum' ? 'selected' : '' }}>Chrysanthemum</option>
            <option value="Cuyab" {{ old('document_barangay', $document_final_barangay) == 'Cuyab' ? 'selected' : '' }}>Cuyab</option>
            <option value="Estrella" {{ old('document_barangay', $document_final_barangay) == 'Estrella' ? 'selected' : '' }}>Estrella</option>
            <option value="Fatima" {{ old('document_barangay', $document_final_barangay) == 'Fatima' ? 'selected' : '' }}>Fatima</option>
            <option value="G.S.I.S" {{ old('document_barangay', $document_final_barangay) == 'G.S.I.S' ? 'selected' : '' }}>G.S.I.S</option>
            <option value="Landayan" {{ old('document_barangay', $document_final_barangay) == 'Landayan' ? 'selected' : '' }}>Landayan</option>
            <option value="Langgam" {{ old('document_barangay', $document_final_barangay) == 'Langgam' ? 'selected' : '' }}>Langgam</option>
            <option value="Laram" {{ old('document_barangay', $document_final_barangay) == 'Laram' ? 'selected' : '' }}>Laram</option>
            <option value="Magsaysay" {{ old('document_barangay', $document_final_barangay) == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
            <option value="Maharlika" {{ old('document_barangay', $document_final_barangay) == 'Maharlika' ? 'selected' : '' }}>Maharlika</option>
            <option value="Narra" {{ old('document_barangay', $document_final_barangay) == 'Narra' ? 'selected' : '' }}>Narra</option>
            <option value="Nueva" {{ old('document_barangay', $document_final_barangay) == 'Nueva' ? 'selected' : '' }}>Nueva</option>
            <option value="Pacita 1" {{ old('document_barangay', $document_final_barangay) == 'Pacita 1' ? 'selected' : '' }}>Pacita 1</option>
            <option value="Pacita 2" {{ old('document_barangay', $document_final_barangay) == 'Pacita 2' ? 'selected' : '' }}>Pacita 2</option>
            <option value="Poblacion" {{ old('document_barangay', $document_final_barangay) == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
            <option value="Riverside" {{ old('document_barangay', $document_final_barangay) == 'Riverside' ? 'selected' : '' }}>Riverside</option>
            <option value="Rosario" {{ old('document_barangay', $document_final_barangay) == 'Rosario' ? 'selected' : '' }}>Rosario</option>
            <option value="Sampaguita Village" {{ old('document_barangay', $document_final_barangay) == 'Sampaguita Village' ? 'selected' : '' }}>Sampaguita Village</option>
            <option value="San Antonio" {{ old('document_barangay', $document_final_barangay) == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
            <option value="San Lorenzo Ruiz" {{ old('document_barangay', $document_final_barangay) == 'San Lorenzo Ruiz' ? 'selected' : '' }}>San Lorenzo Ruiz</option>
            <option value="San Roque" {{ old('document_barangay', $document_final_barangay) == 'San Roque' ? 'selected' : '' }}>San Roque</option>
            <option value="San Vicente" {{ old('document_barangay', $document_final_barangay) == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
            <option value="Santo Niño" {{ old('document_barangay', $document_final_barangay) == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
            <option value="United Bayanihan" {{ old('document_barangay', $document_final_barangay) == 'United Bayanihan' ? 'selected' : '' }}>United Bayanihan</option>
            <option value="United Better Living" {{ old('document_barangay', $document_final_barangay) == 'United Better Living' ? 'selected' : '' }}>United Better Living</option>
        </select>
        @error('document_barangay')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group {{ old('document_city') == 'document-san-pedro-city' ? '' : 'd-none' }}" id="document-street-group">
        <input name="document_street" id="document-street" type="text"
            class="form-control form-control-user @error('document_street')is-invalid @enderror"
            placeholder="Current Street Address: {{ $document_street }}" value="{{ old('document_street', $document_street) }}">
        @error('document_street')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group {{ old('document_city') == 'document-other-city' ? '' : 'd-none' }}" id="document-other-address-group">
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input name="document_other_city" id="document-other-city-input" type="text"
                    class="form-control form-control-user @error('document_other_city')is-invalid @enderror"
                    placeholder="Current City: {{ $document_other_city }}" value="{{ old('document_other_city', $document_other_city) }}">
                @error('document_other_city')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6">
                <input name="document_other_barangay" id="document-other-barangay" type="text"
                    class="form-control form-control-user @error('document_other_barangay')is-invalid @enderror"
                    placeholder="Current Barangay: {{ $document_other_barangay }}" value="{{ old('document_other_barangay', $document_other_barangay) }}">
                @error('document_other_barangay')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <input name="document_other_street" id="document-other-street" type="text"
                class="form-control form-control-user @error('document_other_street')is-invalid @enderror"
                placeholder="Current Street Address: {{ $document_other_street }}" value="{{ old('document_other_street', $document_other_street) }}">
            @error('document_other_street')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @error('document_city')
        <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="certificate_of_indigency">Certificate of Indigency: <a href="{{ asset($additional_info->certificate_of_indigency) }}" target="_blank">Current</a></label>
    <input name="certificate_of_indigency" id="certificate_of_indigency" type="file" accept=".pdf"
        class="form-control @error('certificate_of_indigency')is-invalid @enderror" disabled>
    @error('certificate_of_indigency')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<hr>
<div class="form-group text-center">
    <label>Previous Employer Information (If Applicable):</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="previous_employer_name" id="previous_employer_name" type="text"
            class="form-control form-control-user @error('previous_employer_name')is-invalid @enderror"
            placeholder="Current Employer's Name: {{ $additional_info->previous_employer_name }}" value="{{ old('previous_employer_name', $additional_info->previous_employer_name) }}" disabled>
        @error('previous_employer_name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <input name="previous_employer_contact" id="previous_employer_contact" type="text"
            class="form-control form-control-user @error('previous_employer_contact')is-invalid @enderror"
            placeholder="Current Employer's Contact Number: {{ $additional_info->previous_employer_contact }}" value="{{ old('previous_employer_contact', $additional_info->previous_employer_contact) }}" disabled>
        @error('previous_employer_contact')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<hr>
<div class="form-group text-center">
    <label>Business Information:</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="business_name" id="business_name" type="text"
            class="form-control form-control-user @error('business_name')is-invalid @enderror"
            placeholder="Current Business Name: {{ $additional_info->business_name }}" value="{{ old('business_name', $additional_info->business_name) }}" disabled>
        @error('business_name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <input name="registration_number" id="registration_number" type="text"
            class="form-control form-control-user @error('registration_number')is-invalid @enderror"
            placeholder="Current Registration Number: {{ $additional_info->registration_number }}" value="{{ old('registration_number', $additional_info->registration_number) }}" disabled>
        @error('registration_number')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <input name="business_address" id="business_address" type="text"
        class="form-control form-control-user @error('business_address')is-invalid @enderror"
        placeholder="Current Business Address: {{ $additional_info->business_address }}" value="{{ old('business_address', $additional_info->business_address) }}" disabled>
    @error('business_address')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="business_period" id="business_period" type="text"
            class="form-control form-control-user @error('business_period')is-invalid @enderror"
            placeholder="Current Business Period (Previous): {{ $additional_info->business_period }}" value="{{ old('business_period', $additional_info->business_period) }}" disabled>
        @error('business_period')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <input name="no_income_period" id="no_income_period" type="text"
            class="form-control form-control-user @error('no_income_period')is-invalid @enderror"
            placeholder="Current Period which the business does not have any income: {{ $additional_info->no_income_period }}" value="{{ old('no_income_period', $additional_info->no_income_period) }}" disabled>
        @error('no_income_period')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>