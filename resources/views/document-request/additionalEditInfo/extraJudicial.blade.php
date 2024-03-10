<div class="form-group text-center">
    <label>Extra Judicial Information:</label>
</div>

<input type="hidden" name="documentRequest_id" value="{{ $additional_info->documentRequest_id }}">

<div class="form-group row">
    <div class="col-sm-6">
        <label for="title_of_property">Title of Property: <a href="{{ asset($additional_info->title_of_property) }}" target="_blank">Current</a></label>
        <input name="title_of_property" id="title_of_property" type="file" accept=".pdf"
            class="form-control @error('title_of_property') is-invalid @enderror" disabled>
        @error('title_of_property')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-sm-6">
        <label for="title_holder">Title Holder:</label>
        <input type="text" name="title_holder" id="title_holder" 
        class="form-control @error('title_holder') is-invalid @enderror" value="{{ old('title_holder', $additional_info->title_holder) }}" placeholder="Current Title Holder/Owner: {{ $additional_info->title_holder }}" disabled>
        @error('title_holder')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="surviving_spouse">Name of Surviving Spouse:
        <label for="deceased_spouse">
            <input type="checkbox" id="deceased_spouse" name="deceased_spouse" class="same-address" disabled>
            Deceased
        </label>
    </label>
    <input type="text" name="surviving_spouse" id="surviving_spouse" class="form-control @error('surviving_spouse') is-invalid @enderror" value="{{ old('surviving_spouse', $additional_info->surviving_spouse) }}" disabled>
    @error('surviving_spouse')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div id="spouseValidIDFields">
    <div class="form-group row">
        <div class="col-sm-6">
            <label for="spouse_valid_id_front">Spouse Valid ID (Front): 
                @if($additional_info->spouse_valid_id_front != null)
                    <a href="{{ asset($additional_info->spouse_valid_id_front) }}" target="_blank">Current</a>
                @endif
            </label>
            <input name="spouse_valid_id_front" id="spouse_valid_id_front" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('spouse_valid_id_front')is-invalid @enderror" disabled>
            @error('spouse_valid_id_front')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="spouse_valid_id_back">Spouse Valid ID (Back):
                @if($additional_info->spouse_valid_id_front != null)
                    <a href="{{ asset($additional_info->spouse_valid_id_back) }}" target="_blank">Current</a>
                @endif
            </label>
            <input name="spouse_valid_id_back" id="spouse_valid_id_back" type="file" accept="image/jpeg, image/jpg, image/png" class="form-control @error('spouse_valid_id_back')is-invalid @enderror" disabled>
            @error('spouse_valid_id_back')
            <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

<div id="heirFields" style="display: none;">
    @if($additional_info->surviving_spouse != null && $additional_info->spouse_valid_id_front != null && $additional_info->spouse_valid_id_back != null)
        <div id="deceasedFields">    
            <div class="form-group row">
                <div class="col-sm-6">
                    <label for="surviving_heir[0]">Name of Surviving Heir:</label>
                    <input type="text" name="surviving_heir[0]" id="surviving_heir[0]" class="form-control @error('surviving_heir[0]') is-invalid @enderror" value="{{ old('surviving_heir[0]') }}" disabled>
                    @error('surviving_heir[0]')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <label for="spouse_of_heir[0]">Name of Spouse: (If Married)</label>
                    <input type="text" name="spouse_of_heir[0]" id="spouse_of_heir[0]" class="form-control @error('spouse_of_heir[0]') is-invalid @enderror" value="{{ old('spouse_of_heir[0]') }}" disabled>
                    @error('spouse_of_heir[0]')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <hr>
        </div>
    @else   
        @foreach ($heirs_info as $key => $heir)
            <div id="deceasedFields{{ $key > 0 ? '_' . $key : '' }}">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="surviving_heir[{{$key}}]">Name of Surviving Heir:</label>
                        <input type="text" name="surviving_heir[{{$key}}]" id="surviving_heir[{{$key}}]" class="form-control @error('surviving_heir['.$key.']') is-invalid @enderror" value="{{ old('surviving_heir['.$key.']', $heir->surviving_heir) }}" disabled>
                        @error('surviving_heir['.$key.']')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <label for="spouse_of_heir[{{$key}}]">Name of Spouse: (If Married)</label>
                        <input type="text" name="spouse_of_heir[{{$key}}]" id="spouse_of_heir[{{$key}}]" class="form-control @error('spouse_of_heir['.$key.']') is-invalid @enderror" value="{{ old('spouse_of_heir['.$key.']', $heir->spouse_of_heir) }}" disabled>
                        @error('spouse_of_heir['.$key.']')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <hr>
            </div>
        @endforeach
    @endif

    <div class="d-flex align-items-center justify-content-between" id="buttons">
        <button type="button" id="addHeirBtn" class="btn btn-warning" disabled>Add More Heir</button>
        <button type="button" id="deleteHeirBtn" class="btn btn-danger" disabled>Delete Heir</button>
    </div>
</div>