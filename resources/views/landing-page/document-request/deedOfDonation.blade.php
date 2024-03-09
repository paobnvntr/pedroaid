<div class="form-group text-center">
    <label>Deed of Donation Information (Donor):</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="donor_name" id="donor_name" type="text"
            class="form-control form-control-user @error('donor_name')is-invalid @enderror"
            placeholder="Name of Donor" value="{{ old('donor_name') }}">
    </div>

    <div class="col-sm-6">
        <select name="donor_civil_status" id="donor_civil_status" class="form-control @error('donor_civil_status')is-invalid @enderror">
            <option value="">-- Select Civil Status --</option>
            <option value="Single" {{ old('donor_civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
            <option value="Married" {{ old('donor_civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
            <option value="Divorced" {{ old('donor_civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
            <option value="Widowed" {{ old('donor_civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
            <option value="Separated" {{ old('donor_civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
        </select>
        @error('donor_civil_status')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="donor_address">Donor's Address:
        <label for="use_same_address">
            <input type="checkbox" id="use_same_address" name="use_same_address" class="same-address">
            Use same address on client details
        </label>
    </label>
    <div class="form-check @error('document_city')is-invalid @enderror">
        <input class="form-check-input" type="radio" name="document_city" id="document-san-pedro-city" value="San Pedro City" {{ old('document_city') == 'document-san-pedro-city' ? 'checked' : '' }}>
        <label class="form-check-label" for="document-san-pedro-city">
            San Pedro City
        </label>
    </div>

    <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="document_city" id="document-other-city" value="Other City" {{ old('document_city') == 'document-other-city' ? 'checked' : '' }}>
        <label class="form-check-label" for="document-other-city">
            Other City
        </label>
    </div>
    @error('document_city')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    <div class="form-group {{ old('document_city') == 'document-san-pedro-city' ? '' : 'd-none' }}" id="document-barangay-group">
        <select name="document_barangay" id="document_barangay" class="form-control @error('document_barangay')is-invalid @enderror">
            <option value="">-- Select Barangay --</option>
            <option value="Bagong Silang" {{ old('document_barangay') == 'Bagong Silang' ? 'selected' : '' }}>Bagong Silang</option>
            <option value="Calendola" {{ old('document_barangay') == 'Calendola' ? 'selected' : '' }}>Calendola</option>
            <option value="Chrysanthemum" {{ old('document_barangay') == 'Chrysanthemum' ? 'selected' : '' }}>Chrysanthemum</option>
            <option value="Cuyab" {{ old('document_barangay') == 'Cuyab' ? 'selected' : '' }}>Cuyab</option>
            <option value="Estrella" {{ old('document_barangay') == 'Estrella' ? 'selected' : '' }}>Estrella</option>
            <option value="Fatima" {{ old('document_barangay') == 'Fatima' ? 'selected' : '' }}>Fatima</option>
            <option value="G.S.I.S" {{ old('document_barangay') == 'G.S.I.S' ? 'selected' : '' }}>G.S.I.S</option>
            <option value="Landayan" {{ old('document_barangay') == 'Landayan' ? 'selected' : '' }}>Landayan</option>
            <option value="Langgam" {{ old('document_barangay') == 'Langgam' ? 'selected' : '' }}>Langgam</option>
            <option value="Laram" {{ old('document_barangay') == 'Laram' ? 'selected' : '' }}>Laram</option>
            <option value="Magsaysay" {{ old('document_barangay') == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
            <option value="Maharlika" {{ old('document_barangay') == 'Maharlika' ? 'selected' : '' }}>Maharlika</option>
            <option value="Narra" {{ old('document_barangay') == 'Narra' ? 'selected' : '' }}>Narra</option>
            <option value="Nueva" {{ old('document_barangay') == 'Nueva' ? 'selected' : '' }}>Nueva</option>
            <option value="Pacita 1" {{ old('document_barangay') == 'Pacita 1' ? 'selected' : '' }}>Pacita 1</option>
            <option value="Pacita 2" {{ old('document_barangay') == 'Pacita 2' ? 'selected' : '' }}>Pacita 2</option>
            <option value="Poblacion" {{ old('document_barangay') == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
            <option value="Riverside" {{ old('document_barangay') == 'Riverside' ? 'selected' : '' }}>Riverside</option>
            <option value="Rosario" {{ old('document_barangay') == 'Rosario' ? 'selected' : '' }}>Rosario</option>
            <option value="Sampaguita Village" {{ old('document_barangay') == 'Sampaguita Village' ? 'selected' : '' }}>Sampaguita Village</option>
            <option value="San Antonio" {{ old('document_barangay') == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
            <option value="San Lorenzo Ruiz" {{ old('document_barangay') == 'San Lorenzo Ruiz' ? 'selected' : '' }}>San Lorenzo Ruiz</option>
            <option value="San Roque" {{ old('document_barangay') == 'San Roque' ? 'selected' : '' }}>San Roque</option>
            <option value="San Vicente" {{ old('document_barangay') == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
            <option value="Santo Niño" {{ old('document_barangay') == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
            <option value="United Bayanihan" {{ old('document_barangay') == 'United Bayanihan' ? 'selected' : '' }}>United Bayanihan</option>
            <option value="United Better Living" {{ old('document_barangay') == 'United Better Living' ? 'selected' : '' }}>United Better Living</option>
        </select>
        @error('document_barangay')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group {{ old('document_city') == 'document-san-pedro-city' ? '' : 'd-none' }}" id="document-street-group">
        <input name="document_street" id="document_street" type="text"
            class="form-control form-control-user @error('document_street')is-invalid @enderror"
            placeholder="Street Address" value="{{ old('document_street') }}">
        @error('document_street')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group {{ old('document_city') == 'document-other-city' ? '' : 'd-none' }}" id="document-other-address-group">
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input name="document_other_city" id="document-other-city-input" type="text"
                    class="form-control form-control-user @error('document_other_city')is-invalid @enderror"
                    placeholder="City (e.g. Biñan City)" value="{{ old('document_other_city') }}">
                @error('document_other_city')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6">
                <input name="document_other_barangay" id="document-other-barangay" type="text"
                    class="form-control form-control-user @error('document_other_barangay')is-invalid @enderror"
                    placeholder="Barangay" value="{{ old('document_other_barangay') }}">
                @error('document_other_barangay')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <input name="document_other_street" id="document-other-street" type="text"
                class="form-control form-control-user @error('document_other_street')is-invalid @enderror"
                placeholder="Street Address" value="{{ old('document_other_street') }}">
            @error('document_other_street')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @error('document_city')
    <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <label for="donor_valid_id_front">Valid ID (Front):</label>
        <input name="donor_valid_id_front" id="donor_valid_id_front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('donor_valid_id_front')is-invalid @enderror">
        @error('donor_valid_id_front')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <label for="donor_valid_id_back">Valid ID (Back):</label>
        <input name="donor_valid_id_back" id="donor_valid_id_back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('donor_valid_id_back')is-invalid @enderror">
        @error('donor_valid_id_back')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<hr>
<div class="form-group text-center">
    <label>Deed of Donation Information (Donee):</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="donee_name" id="donee_name" type="text"
            class="form-control form-control-user @error('donee_name')is-invalid @enderror"
            placeholder="Name of Donee" value="{{ old('donee_name') }}">
    </div>

    <div class="col-sm-6">
        <select name="donee_civil_status" id="donee_civil_status" class="form-control @error('donee_civil_status')is-invalid @enderror">
            <option value="">-- Select Civil Status --</option>
            <option value="Single" {{ old('donee_civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
            <option value="Married" {{ old('donee_civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
            <option value="Divorced" {{ old('donee_civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
            <option value="Widowed" {{ old('donee_civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
            <option value="Separated" {{ old('donee_civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
        </select>
        @error('donee_civil_status')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="donee_address">Donee's Address:
        <label for="use_same_address_2">
            <input type="checkbox" id="use_same_address_2" name="use_same_address_2" class="same-address">
            Use same address on client details
        </label>
    </label>
    <div class="form-check @error('document_city_2')is-invalid @enderror">
        <input class="form-check-input" type="radio" name="document_city_2" id="document-san-pedro-city-2" value="San Pedro City" {{ old('document_city_2') == 'document-san-pedro-city-2' ? 'checked' : '' }}>
        <label class="form-check-label" for="document-san-pedro-city-2">
            San Pedro City
        </label>
    </div>

    <div class="form-check mb-2">
        <input class="form-check-input" type="radio" name="document_city_2" id="document-other-city-2" value="Other City" {{ old('document_city_2') == 'document-other-city-2' ? 'checked' : '' }}>
        <label class="form-check-label" for="document-other-city-2">
            Other City
        </label>
    </div>
    @error('document_city_2')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror

    <div class="form-group {{ old('document_city_2') == 'document-san-pedro-city-2' ? '' : 'd-none' }}" id="document-barangay-group-2">
        <select name="document_barangay_2" id="document_barangay_2" class="form-control @error('document_barangay_2')is-invalid @enderror">
            <option value="">-- Select Barangay --</option>
            <option value="Bagong Silang" {{ old('document_barangay_2') == 'Bagong Silang' ? 'selected' : '' }}>Bagong Silang</option>
            <option value="Calendola" {{ old('document_barangay_2') == 'Calendola' ? 'selected' : '' }}>Calendola</option>
            <option value="Chrysanthemum" {{ old('document_barangay_2') == 'Chrysanthemum' ? 'selected' : '' }}>Chrysanthemum</option>
            <option value="Cuyab" {{ old('document_barangay_2') == 'Cuyab' ? 'selected' : '' }}>Cuyab</option>
            <option value="Estrella" {{ old('document_barangay_2') == 'Estrella' ? 'selected' : '' }}>Estrella</option>
            <option value="Fatima" {{ old('document_barangay_2') == 'Fatima' ? 'selected' : '' }}>Fatima</option>
            <option value="G.S.I.S" {{ old('document_barangay_2') == 'G.S.I.S' ? 'selected' : '' }}>G.S.I.S</option>
            <option value="Landayan" {{ old('document_barangay_2') == 'Landayan' ? 'selected' : '' }}>Landayan</option>
            <option value="Langgam" {{ old('document_barangay_2') == 'Langgam' ? 'selected' : '' }}>Langgam</option>
            <option value="Laram" {{ old('document_barangay_2') == 'Laram' ? 'selected' : '' }}>Laram</option>
            <option value="Magsaysay" {{ old('document_barangay_2') == 'Magsaysay' ? 'selected' : '' }}>Magsaysay</option>
            <option value="Maharlika" {{ old('document_barangay_2') == 'Maharlika' ? 'selected' : '' }}>Maharlika</option>
            <option value="Narra" {{ old('document_barangay_2') == 'Narra' ? 'selected' : '' }}>Narra</option>
            <option value="Nueva" {{ old('document_barangay_2') == 'Nueva' ? 'selected' : '' }}>Nueva</option>
            <option value="Pacita 1" {{ old('document_barangay_2') == 'Pacita 1' ? 'selected' : '' }}>Pacita 1</option>
            <option value="Pacita 2" {{ old('document_barangay_2') == 'Pacita 2' ? 'selected' : '' }}>Pacita 2</option>
            <option value="Poblacion" {{ old('document_barangay_2') == 'Poblacion' ? 'selected' : '' }}>Poblacion</option>
            <option value="Riverside" {{ old('document_barangay_2') == 'Riverside' ? 'selected' : '' }}>Riverside</option>
            <option value="Rosario" {{ old('document_barangay_2') == 'Rosario' ? 'selected' : '' }}>Rosario</option>
            <option value="Sampaguita Village" {{ old('document_barangay_2') == 'Sampaguita Village' ? 'selected' : '' }}>Sampaguita Village</option>
            <option value="San Antonio" {{ old('document_barangay_2') == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
            <option value="San Lorenzo Ruiz" {{ old('document_barangay_2') == 'San Lorenzo Ruiz' ? 'selected' : '' }}>San Lorenzo Ruiz</option>
            <option value="San Roque" {{ old('document_barangay_2') == 'San Roque' ? 'selected' : '' }}>San Roque</option>
            <option value="San Vicente" {{ old('document_barangay_2') == 'San Vicente' ? 'selected' : '' }}>San Vicente</option>
            <option value="Santo Niño" {{ old('document_barangay_2') == 'Santo Niño' ? 'selected' : '' }}>Santo Niño</option>
            <option value="United Bayanihan" {{ old('document_barangay_2') == 'United Bayanihan' ? 'selected' : '' }}>United Bayanihan</option>
            <option value="United Better Living" {{ old('document_barangay_2') == 'United Better Living' ? 'selected' : '' }}>United Better Living</option>
        </select>
        @error('document_barangay_2')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group {{ old('document_city_2') == 'document-san-pedro-city-2' ? '' : 'd-none' }}" id="document-street-group-2">
        <input name="document_street_2" id="document_street_2" type="text"
            class="form-control form-control-user @error('document_street_2')is-invalid @enderror"
            placeholder="Street Address" value="{{ old('document_street_2') }}">
        @error('document_street_2')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group {{ old('document_city_2') == 'document-other-city-2' ? '' : 'd-none' }}" id="document-other-address-group-2">
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input name="document_other_city_2" id="document-other-city-input-2" type="text"
                    class="form-control form-control-user @error('document_other_city_2')is-invalid @enderror"
                    placeholder="City (e.g. Biñan City)" value="{{ old('document_other_city_2') }}">
                @error('document_other_city_2')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-sm-6">
                <input name="document_other_barangay_2" id="document-other-barangay-2" type="text"
                    class="form-control form-control-user @error('document_other_barangay_2')is-invalid @enderror"
                    placeholder="Barangay" value="{{ old('document_other_barangay_2') }}">
                @error('document_other_barangay_2')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <input name="document_other_street_2" id="document-other-street-2" type="text"
                class="form-control form-control-user @error('document_other_street_2')is-invalid @enderror"
                placeholder="Street Address" value="{{ old('document_other_street_2') }}">
            @error('document_other_street_2')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @error('document_city_2')
    <span class="invalid-feedback d-block">{{ $message }}</span>
    @enderror
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <label for="donee_valid_id_front">Donee Valid ID (Front):</label>
        <input name="donee_valid_id_front" id="donee_valid_id_front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('donee_valid_id_front')is-invalid @enderror">
        @error('donee_valid_id_front')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <label for="donee_valid_id_back">Donee Valid ID (Back):</label>
        <input name="donee_valid_id_back" id="donee_valid_id_back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('donee_valid_id_back')is-invalid @enderror">
        @error('donee_valid_id_back')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="property_description">Property Description:</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="property_description" id="property_house" value="House" {{ old('property_description') == 'House' ? 'checked' : '' }}>
        <label class="form-check-label" for="property_house">
            House
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="property_description" id="property_land" value="Land" {{ old('property_description') == 'Land' ? 'checked' : '' }}>
        <label class="form-check-label" for="property_land">
            Land
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="property_description" id="property_vehicle" value="Vehicle" {{ old('property_description') == 'Vehicle' ? 'checked' : '' }}>
        <label class="form-check-label" for="property_vehicle">
            Vehicle
        </label>
    </div>
    @error('property_description')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>