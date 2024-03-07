<div class="form-group text-center">
    <label>Other Document Information:</label>
</div>

<div class="form-group row">
    <div class="col-sm-6">
        <label for="valid_id_front">Valid ID (Front):</label>
        <input name="valid_id_front" id="valid_id_front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('valid_id_front')is-invalid @enderror">
        @error('valid_id_front')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <label for="valid_id_back">Valid ID (Back):</label>
        <input name="valid_id_back" id="valid_id_back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('valid_id_back')is-invalid @enderror">
        @error('valid_id_back')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>