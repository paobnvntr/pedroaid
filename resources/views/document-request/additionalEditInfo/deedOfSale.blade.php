<div class="form-group text-center">
    <label>Deed of Sale Information (Vendor):</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="name_of_vendor" id="name_of_vendor" type="text"
            class="form-control @error('name_of_vendor')is-invalid @enderror"
            placeholder="Current Name and Identity of Vendor: {{ $additional_info->name_of_vendor }}" value="{{ old('name_of_vendor', $additional_info->name_of_vendor) }}" disabled>
        @error('name_of_vendor')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <select name="document_civil_status" id="document_civil_status" class="form-control @error('document_civil_status')is-invalid @enderror" disabled>
            <option value="">-- Select Civil Status --</option>
            <option value="Single" {{ old('document_civil_status', $additional_info->vendor_civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
            <option value="Married" {{ old('document_civil_status', $additional_info->vendor_civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
            <option value="Divorced" {{ old('document_civil_status', $additional_info->vendor_civil_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
            <option value="Widowed" {{ old('document_civil_status', $additional_info->vendor_civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
            <option value="Separated" {{ old('document_civil_status', $additional_info->vendor_civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
        </select>
        @error('document_civil_status')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="document_address">Address:
        <label for="use_same_address">
            <input type="checkbox" id="use_same_address" name="use_same_address" class="same-address" disabled>
            Use same address on client details
        </label>
    </label>
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

<div class="form-group row">
    <div class="col-sm-6">
        <label for="property_document">Property Document (or Vehicle OR/CR): <a href="{{ asset($additional_info->property_document) }}" target="_blank">Current</a></label>
        <input name="property_document" id="property_document" type="file" accept=".pdf" class="form-control @error('property_document')is-invalid @enderror" disabled>
        @error('property_document')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <label for="property_price">Property Price:</label>
        <input name="property_price" id="property_price" class="form-control @error('property_price') is-invalid @enderror" type="text" placeholder="Current Price of the Property/Vehicle in Deed of Sale: {{ $additional_info->property_price }}" value="{{ old('property_price', $additional_info->property_price) }}" disabled>
        @error('property_price')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <div class="form-group row">
        <div class="col-sm-6">
            <label for="vendor_valid_id_front">Vendor Valid ID (Front): <a href="{{ asset($additional_info->vendor_valid_id_front) }}" target="_blank">Current</a></label>
            <input name="vendor_valid_id_front" id="vendor-valid-id-front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('vendor_valid_id_front')is-invalid @enderror" disabled>
            @error('vendor_valid_id_front')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="vendor_valid_id_back">Vendor Valid ID (Back): <a href="{{ asset($additional_info->vendor_valid_id_back) }}" target="_blank">Current</a></label>
            <input name="vendor_valid_id_back" id="vendor-valid-id-back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('vendor_valid_id_back')is-invalid @enderror" disabled>
            @error('vendor_valid_id_back')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<hr>
<div class="form-group text-center">
    <label>Deed of Sale Information (Vendee):</label>
</div>

<div class="form-group">
    <input name="name_of_vendee" id="name_of_vendee" type="text"
        class="form-control @error('name_of_vendee')is-invalid @enderror"
        placeholder="Current Name and Identity of Vendee: {{ $additional_info->name_of_vendee }}" value="{{ old('name_of_vendee', $additional_info->name_of_vendee) }}" disabled>
    @error('name_of_vendee')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group row">
    <div class="form-group row">
        <div class="col-sm-6">
            <label for="vendee_valid_id_front">Vendee Valid ID (Front): <a href="{{ asset($additional_info->vendee_valid_id_front) }}" target="_blank">Current</a></label>
            <input name="vendee_valid_id_front" id="vendee-valid-id-front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('vendee_valid_id_front')is-invalid @enderror" disabled>
            @error('vendee_valid_id_front')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="vendee_valid_id_back">Vendee Valid ID (Back): <a href="{{ asset($additional_info->vendee_valid_id_back) }}" target="_blank">Current</a></label>
            <input name="vendee_valid_id_back" id="vendee-valid-id-back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('vendee_valid_id_back')is-invalid @enderror" disabled>
            @error('vendee_valid_id_back')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<hr>
<div class="form-group text-center">
    <label>Deed of Sale Information (Witness):</label>
</div>

<div class="form-group">
    <input name="name_of_witness" id="name_of_witness" type="text"
        class="form-control @error('name_of_witness')is-invalid @enderror"
        placeholder="Current Name and Identity of Witness: {{ $additional_info->name_of_witness }}" value="{{ old('name_of_witness', $additional_info->name_of_witness) }}" disabled>
    @error('name_of_witness')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group row">
    <div class="form-group row">
        <div class="col-sm-6">
            <label for="witness_valid_id_front">Witness Valid ID (Front): <a href="{{ asset($additional_info->witness_valid_id_front) }}" target="_blank">Current</a></label>
            <input name="witness_valid_id_front" id="witness-valid-id-front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('witness_valid_id_front')is-invalid @enderror" disabled>
            @error('witness_valid_id_front')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="witness_valid_id_back">Witness Valid ID (Back): <a href="{{ asset($additional_info->witness_valid_id_back) }}" target="_blank">Current</a></label>
            <input name="witness_valid_id_back" id="witness-valid-id-back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('witness_valid_id_back')is-invalid @enderror" disabled>
            @error('witness_valid_id_back')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>