<div class="form-group text-center">
    <label>Extra Judicial Information:</label>
</div>

<div class="form-group">
    <label for="death_certificate">Death Certificate: <a href="{{ asset($additional_info->death_cert) }}" target="_blank">Current</a></label>
    <input name="death_certificate" id="death_certificate" type="file" accept=".pdf"
        class="form-control @error('death_certificate')is-invalid @enderror" disabled>
    @error('death_certificate')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="heirship_documents">Heirship Documents: <a href="{{ asset($additional_info->heirship) }}" target="_blank">Current</a></label>
    <input name="heirship_documents" id="heirship_documents" type="file" accept=".pdf"
        class="form-control @error('heirship_documents')is-invalid @enderror" disabled>
    @error('heirship_documents')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="inventory_of_estate">Inventory of Estate: <a href="{{ asset($additional_info->inv_estate) }}" target="_blank">Current</a></label>
    <input name="inventory_of_estate" id="inventory_of_estate" type="file" accept=".pdf"
        class="form-control @error('inventory_of_estate')is-invalid @enderror" disabled>
    @error('inventory_of_estate')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="tax_clearance">Tax Clearance from BIR: <a href="{{ asset($additional_info->tax_clearance) }}" target="_blank">Current</a></label>
    <input name="tax_clearance" id="tax_clearance" type="file" accept=".pdf"
        class="form-control @error('tax_clearance')is-invalid @enderror" disabled>
    @error('tax_clearance')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="deed_of_extrajudicial_settlement">Deed of Extrajudicial Settlement: <a href="{{ asset($additional_info->deed_extrajudicial) }}" target="_blank">Current</a></label>
    <input name="deed_of_extrajudicial_settlement" id="deed_of_extrajudicial_settlement" type="file" accept=".pdf"
        class="form-control @error('deed_of_extrajudicial_settlement')is-invalid @enderror" disabled>
    @error('deed_of_extrajudicial_settlement')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>