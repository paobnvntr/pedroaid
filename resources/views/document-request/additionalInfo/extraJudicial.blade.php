<div class="form-group text-center">
    <label>Extra Judicial Information:</label>
</div>

<div class="form-group">
    <label for="death_certificate">Death Certificate:</label>
    <input name="death_certificate" id="death_certificate" type="file" accept=".pdf"
        class="form-control @error('death_certificate')is-invalid @enderror">
    @error('death_certificate')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="heirship_documents">Heirship Documents:</label>
    <input name="heirship_documents" id="heirship_documents" type="file" accept=".pdf"
        class="form-control @error('heirship_documents')is-invalid @enderror">
    @error('heirship_documents')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="inventory_of_estate">Inventory of Estate:</label>
    <input name="inventory_of_estate" id="inventory_of_estate" type="file" accept=".pdf"
        class="form-control @error('inventory_of_estate')is-invalid @enderror">
    @error('inventory_of_estate')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="tax_clearance">Tax Clearance from BIR:</label>
    <input name="tax_clearance" id="tax_clearance" type="file" accept=".pdf"
        class="form-control @error('tax_clearance')is-invalid @enderror">
    @error('tax_clearance')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="deed_of_extrajudicial_settlement">Deed of Extrajudicial Settlement:</label>
    <input name="deed_of_extrajudicial_settlement" id="deed_of_extrajudicial_settlement" type="file" accept=".pdf"
        class="form-control @error('deed_of_extrajudicial_settlement')is-invalid @enderror">
    @error('deed_of_extrajudicial_settlement')
    <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>