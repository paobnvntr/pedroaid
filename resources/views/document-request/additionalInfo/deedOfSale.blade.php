<div class="form-group text-center">
    <label>Deed of Sale Information:</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <input name="party1_name" id="party1_name" type="text"
            class="form-control @error('party1_name')is-invalid @enderror"
            placeholder="Name and Identity of Party 1" value="{{ old('party1_name') }}">
        @error('party1_name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-6">
        <input name="party2_name" id="party2_name" type="text"
            class="form-control @error('party2_name')is-invalid @enderror"
            placeholder="Name and Identity of Party 2" value="{{ old('party2_name') }}">
        @error('party2_name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <textarea name="property_details" id="property_details"
        class="form-control @error('property_details')is-invalid @enderror"
        placeholder="Details of the Property/Vehicle">{{ old('property_details') }}</textarea>
    @error('property_details')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<!-- <div class="form-check">
    <input name="notarization" id="notarization" type="checkbox" class="form-check-input">
    <label class="form-check-label" for="notarization">Notarization</label>
</div> -->