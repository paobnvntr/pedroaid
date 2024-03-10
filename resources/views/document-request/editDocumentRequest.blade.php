@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
        @if($documentRequest->documentRequest_status == 'Pending' || $documentRequest->documentRequest_status == 'Declined')
            <a href="{{ route('document-request.pendingDocumentRequest') }}" class="fas fa-angle-left fs-4"></a>
        @elseif($documentRequest->documentRequest_status == 'To Claim' || $documentRequest->documentRequest_status == 'Claimed' || $documentRequest->documentRequest_status == 'Unclaimed')
            <a href="{{ route('document-request.finishedDocumentRequest') }}" class="fas fa-angle-left fs-4"></a>
        @else
            <a href="{{ route('document-request') }}" class="fas fa-angle-left fs-4"></a>
        @endif
		<h1 class="mb-0 ml-4">Edit Document Request ID: {{ $documentRequest->documentRequest_id }}</h1>
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

    <div class="p-5">
        <div class="p-5 card shadow">
            <form action="{{ route('document-request.updateDocumentRequest', $documentRequest->documentRequest_id) }}" method="POST" id="editDocumentRequestForm" class="user" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="document_type">Document Type:</label>
                    <select name="document_type" id="document_type" class="form-control" disabled>
                        <option value="">-- Select Document --</option>
                        <option value="Affidavit of Loss" {{ old('document_type', $documentRequest->document_type) == 'Affidavit of Loss' ? 'selected' : '' }}>Affidavit of Loss</option>
                        <option value="Affidavit of Guardianship" {{ old('document_type', $documentRequest->document_type) == 'Affidavit of Guardianship' ? 'selected' : '' }}>Affidavit of Guardianship</option>
                        <option value="Affidavit of No income" {{ old('document_type', $documentRequest->document_type) == 'Affidavit of No income' ? 'selected' : '' }}>Affidavit of No income</option>
                        <option value="Affidavit of No fix income" {{ old('document_type', $documentRequest->document_type) == 'Affidavit of No fix income' ? 'selected' : '' }}>Affidavit of No fix income</option>
                        <option value="Extra Judicial" {{ old('document_type', $documentRequest->document_type) == 'Extra Judicial' ? 'selected' : '' }}>Extra Judicial</option>
                        <option value="Deed of Sale" {{ old('document_type', $documentRequest->document_type) == 'Deed of Sale' ? 'selected' : '' }}>Deed of Sale</option>
                        <option value="Deed of Donation" {{ old('document_type', $documentRequest->document_type) == 'Deed of Donation' ? 'selected' : '' }}>Deed of Donation</option>
                        <option value="Other Document" {{ old('document_type', $documentRequest->document_type) == 'Other Document' ? 'selected' : '' }}>Other Document</option>
                    </select>
                </div>

                <div class="form-group">
                    <input name="name" id="name" type="text"
                        class="form-control form-control-user @error('name')is-invalid @enderror"
                        placeholder="Current Full Name: {{ $documentRequest->name }}" value="{{ old('name', $documentRequest->name) }}" disabled>
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Current Client Address:</label>
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
                        <select name="barangay" id="barangay" class="form-control">
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
                    </div>

                    <div class="form-group {{ old('city') == 'san-pedro-city' ? '' : 'd-none' }}" id="street-group">
                        <input name="street" id="street" type="text"
                            class="form-control form-control-user @error('street')is-invalid @enderror"
                            placeholder="Street Address" value="{{ old('street', $street) }}">
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
                            id="cellphone_number" placeholder="Current Cellphone Number: {{ $documentRequest->cellphone_number }}" value="{{ old('cellphone_number', $documentRequest->cellphone_number) }}" disabled>
                        @error('cellphone_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <input name="email" id="email" type="email"
                            class="form-control form-control-user @error('email')is-invalid @enderror"
                            placeholder="Current Email Address: {{ $documentRequest->email }}" value="{{ old('email', $documentRequest->email) }}" disabled>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr>
                <div class="form-group" id="additional-info"></div>
                
                <button type="button" class="btn btn-primary btn-user btn-block hideBtn" id="updateDocumentRequestBtn">Update Document Request</button>
                <button type="button" class="btn btn-primary btn-user btn-block" id="editDocumentRequestBtn">Edit Document Request</button>
            </form>
        </div>
    </div>
    
<script>
    // Store original values when page loads
    const originalDocumentType = "{{ $documentRequest->document_type }}";
    const originalName = "{{ $documentRequest->name }}";
    const originalCity = "{{ $city }}";
    const originalBarangay = "{{ $final_barangay }}";
    const originalStreet = "{{ $street }}";
    const originalOtherCity = "{{ $other_city }}";
    const originalOtherBarangay = "{{ $other_barangay }}";
    const originalOtherStreet = "{{ $other_street }}";
    const originalCellphoneNumber = "{{ $documentRequest->cellphone_number }}";
    const originalEmail = "{{ $documentRequest->email }}";

    const editDocumentRequestBtn = document.getElementById("editDocumentRequestBtn");

    function additionalInfoAddress() {
        const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
        const documentOtherCityRadio = document.getElementById('document-other-city');
        const documentBarangayGroup = document.getElementById('document-barangay-group');
        const documentStreetGroup = document.getElementById('document-street-group');
        const documentOtherAddressGroup = document.getElementById('document-other-address-group');

        documentSanPedroCityRadio.addEventListener('change', () => {
            if (documentSanPedroCityRadio.checked) {
                documentBarangayGroup.classList.remove('d-none');
                documentStreetGroup.classList.remove('d-none');
                documentOtherAddressGroup.classList.add('d-none');
            }
        });

        documentOtherCityRadio.addEventListener('change', () => {
            if (documentOtherCityRadio.checked) {
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.remove('d-none');
            }
        });

        documentOtherCityRadio.addEventListener('click', () => {
            if (!documentOtherCityRadio.checked) {
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.add('d-none');
                document.getElementById('document_barangay').value = '';
                document.getElementById('document_street').value = '';
                document.getElementById('document_other_address').value = '';
            }
        });
    }

    function additionalInfoAddress2() {
        const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
        const documentOtherCityRadio = document.getElementById('document-other-city-2');
        const documentBarangayGroup = document.getElementById('document-barangay-group-2');
        const documentStreetGroup = document.getElementById('document-street-group-2');
        const documentOtherAddressGroup = document.getElementById('document-other-address-group-2');

        documentSanPedroCityRadio.addEventListener('change', () => {
            if (documentSanPedroCityRadio.checked) {
                documentBarangayGroup.classList.remove('d-none');
                documentStreetGroup.classList.remove('d-none');
                documentOtherAddressGroup.classList.add('d-none');
            }
        });

        documentOtherCityRadio.addEventListener('change', () => {
            if (documentOtherCityRadio.checked) {
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.remove('d-none');
            }
        });

        documentOtherCityRadio.addEventListener('click', () => {
            if (!documentOtherCityRadio.checked) {
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.add('d-none');
                document.getElementById('document_barangay').value = '';
                document.getElementById('document_street').value = '';
                document.getElementById('document_other_address').value = '';
            }
        });
    }

    function useSameAddress() {
        const useSameAddressCheckbox = document.getElementById('use_same_address');
        const sanPedroCityRadio = document.getElementById('san-pedro-city');
        const otherCityRadio = document.getElementById('other-city');
        const barangay = document.getElementById('barangay');
        const street = document.getElementById('street');
        const otherCity = document.getElementById('other-city-input');
        const otherBarangay = document.getElementById('other-barangay');
        const otherStreet = document.getElementById('other-street');

        useSameAddressCheckbox.addEventListener('change', () => {
            const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
            const documentOtherCityRadio = document.getElementById('document-other-city');

            const documentBarangayGroup = document.getElementById('document-barangay-group');
            const documentStreetGroup = document.getElementById('document-street-group');
            const documentBarangay = document.getElementById('document-barangay');
            const documentStreet = document.getElementById('document-street');

            const documentOtherAddressGroup = document.getElementById('document-other-address-group');
            const documentOtherCity = document.getElementById('document-other-city-input');
            const documentOtherBarangay = document.getElementById('document-other-barangay');
            const documentOtherStreet = document.getElementById('document-other-street');

            if (useSameAddressCheckbox.checked) {
                if (sanPedroCityRadio.checked) {
                    documentSanPedroCityRadio.checked = true;
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                    documentBarangay.value = barangay.value;
                    documentStreet.value = street.value;
                    documentOtherCityRadio.checked = false;
                    documentOtherCity.value = '';
                    documentOtherBarangay.value = '';
                    documentOtherStreet.value = '';
                } else if (otherCityRadio.checked) {
                    documentOtherCityRadio.checked = true;
                    documentOtherAddressGroup.classList.remove('d-none');
                    documentOtherCity.value = otherCity.value;
                    documentOtherBarangay.value = otherBarangay.value;
                    documentOtherStreet.value = otherStreet.value;
                    documentSanPedroCityRadio.checked = false;
                    documentBarangay.value = '';
                    documentStreet.value = '';
                }
            } else {
                const originalDocumentCity = "{{ $document_city }}";
                const originalDocumentBarangay = "{{ $document_final_barangay }}";
                const originalDocumentStreet = "{{ $document_street }}";
                const originalDocumentOtherCity = "{{ $document_other_city }}";
                const originalDocumentOtherBarangay = "{{ $document_other_barangay }}";
                const originalDocumentOtherStreet = "{{ $document_other_street }}";

                if (originalDocumentCity === 'San Pedro City') {

                    documentSanPedroCityRadio.checked = true;
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;

                    documentOtherCityRadio.checked = false;
                    documentOtherAddressGroup.classList.add('d-none');
                    documentOtherCity.value = '';
                    documentOtherBarangay.value = '';
                    documentOtherStreet.value = '';
                    
                } else {

                    documentOtherCityRadio.checked = true;
                    documentOtherAddressGroup.classList.remove('d-none');
                    documentOtherCity.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;

                    documentSanPedroCityRadio.checked = false;
                    documentBarangayGroup.classList.add('d-none');
                    documentStreetGroup.classList.add('d-none');
                    documentBarangay.value = '';
                    documentStreet.value = '';
                }
            }
        });
    }

    function useSameAddress2() {
        const useSameAddressCheckbox = document.getElementById('use_same_address_2');
        const sanPedroCityRadio = document.getElementById('san-pedro-city');
        const otherCityRadio = document.getElementById('other-city');
        const barangay = document.getElementById('barangay');
        const street = document.getElementById('street');
        const otherCity = document.getElementById('other-city-input');
        const otherBarangay = document.getElementById('other-barangay');
        const otherStreet = document.getElementById('other-street');

        useSameAddressCheckbox.addEventListener('change', () => {
            const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
            const documentOtherCityRadio = document.getElementById('document-other-city-2');

            const documentBarangayGroup = document.getElementById('document-barangay-group-2');
            const documentStreetGroup = document.getElementById('document-street-group-2');
            const documentBarangay = document.getElementById('document-barangay-2');
            const documentStreet = document.getElementById('document-street-2');

            const documentOtherAddressGroup = document.getElementById('document-other-address-group-2');
            const documentOtherCity = document.getElementById('document-other-city-input-2');
            const documentOtherBarangay = document.getElementById('document-other-barangay-2');
            const documentOtherStreet = document.getElementById('document-other-street-2');

            if (useSameAddressCheckbox.checked) {
                if (sanPedroCityRadio.checked) {
                    documentSanPedroCityRadio.checked = true;
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                    documentBarangay.value = barangay.value;
                    documentStreet.value = street.value;
                    documentOtherCityRadio.checked = false;
                    documentOtherCity.value = '';
                    documentOtherBarangay.value = '';
                    documentOtherStreet.value = '';
                } else if (otherCityRadio.checked) {
                    documentOtherCityRadio.checked = true;
                    documentOtherAddressGroup.classList.remove('d-none');
                    documentOtherCity.value = otherCity.value;
                    documentOtherBarangay.value = otherBarangay.value;
                    documentOtherStreet.value = otherStreet.value;
                    documentSanPedroCityRadio.checked = false;
                    documentBarangay.value = '';
                    documentStreet.value = '';
                }
            } else {
                const originalDocumentCity = "{{ $document_city_2 }}";
                const originalDocumentBarangay = "{{ $document_final_barangay_2 }}";
                const originalDocumentStreet = "{{ $document_street_2 }}";
                const originalDocumentOtherCity = "{{ $document_other_city_2 }}";
                const originalDocumentOtherBarangay = "{{ $document_other_barangay_2 }}";
                const originalDocumentOtherStreet = "{{ $document_other_street_2 }}";

                if (originalDocumentCity === 'San Pedro City') {

                    documentSanPedroCityRadio.checked = true;
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;

                    documentOtherCityRadio.checked = false;
                    documentOtherAddressGroup.classList.add('d-none');
                    documentOtherCity.value = '';
                    documentOtherBarangay.value = '';
                    documentOtherStreet.value = '';
                    
                } else {

                    documentOtherCityRadio.checked = true;
                    documentOtherAddressGroup.classList.remove('d-none');
                    documentOtherCity.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;

                    documentSanPedroCityRadio.checked = false;
                    documentBarangayGroup.classList.add('d-none');
                    documentStreetGroup.classList.add('d-none');
                    documentBarangay.value = '';
                    documentStreet.value = '';
                }
            }
        });
    }

    const additionalInfo = document.getElementById("additional-info");

    function generateAdditionalInfo(originalDocumentType) {
        const additionalInfo = document.getElementById("additional-info");

        if (originalDocumentType === 'Affidavit of Loss') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.affidavitOfLoss')
            `;

            additionalInfoAddress();

            useSameAddress();

        } else if (originalDocumentType === 'Affidavit of Guardianship') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.affidavitOfGuardianship')
            `;

            additionalInfoAddress();
            additionalInfoAddress2();

            useSameAddress();
            useSameAddress2();

        } else if (originalDocumentType === 'Affidavit of No income') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.affidavitOfNoIncome')
            `;

            additionalInfoAddress();

            useSameAddress();

        } else if (originalDocumentType === 'Affidavit of No fix income') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.affidavitOfNoFixIncome')
            `;

            additionalInfoAddress();

            useSameAddress();

        } else if (originalDocumentType === 'Extra Judicial') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.extraJudicial')
            `;
        } else if (originalDocumentType === 'Deed of Sale') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.deedOfSale')
            `;
        } else if (originalDocumentType === 'Deed of Donation') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.deedOfDonation')
            `;

            additionalInfoAddress();
            additionalInfoAddress2();

            useSameAddress();
            useSameAddress2();

        } else if (originalDocumentType === 'Other Document') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalEditInfo.otherDocument')
            `;
        
        } else {
            additionalInfo.innerHTML = '';
        }
    }

    generateAdditionalInfo(originalDocumentType);


    editDocumentRequestBtn.addEventListener("click", async () => {
        const updateDocumentRequestBtn = document.getElementById("updateDocumentRequestBtn");
        const documentType = document.getElementById("document_type");
        const name = document.getElementById("name")
        const cellphoneNumber = document.getElementById("cellphone_number")
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

        if (editDocumentRequestBtn.textContent === "Edit Document Request") {
            editDocumentRequestBtn.textContent = "Cancel";
            editDocumentRequestBtn.classList.remove("btn-primary");
            editDocumentRequestBtn.classList.add("btn-danger");
            documentType.disabled = false;
            name.disabled = false;
            cellphoneNumber.disabled = false;
            email.disabled = false;
            sanPedroCityRadio.disabled = false;
            otherCityRadio.disabled = false;
            updateDocumentRequestBtn.classList.remove("hideBtn");
            updateDocumentRequestBtn.classList.add("showBtn");

            // Show barangay and street fields
            if(sanPedroCityRadio.checked) {
                barangayGroup.classList.remove('d-none');
                streetGroup.classList.remove('d-none');
            } else if (otherCityRadio.checked) {
                otherAddressGroup.classList.remove('d-none');
            }

            if(originalDocumentType === 'Affidavit of Loss') {
                const documentName = document.getElementById('document_name');
                const documentCivilStatus = document.getElementById('document_civil_status');
                const useSameAddress = document.getElementById('use_same_address');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');
                const documentOtherStreet = document.getElementById('document-other-street');

                const itemLost = document.getElementById('item_lost');
                const reasonOfLoss = document.getElementById('reason_of_loss');
                const validIDFront = document.getElementById('valid-id-front');
                const validIDBack = document.getElementById('valid-id-back');

                documentName.disabled = false;
                documentCivilStatus.disabled = false;
                useSameAddress.disabled = false;
                documentSanPedroCityRadio.disabled = false;
                documentOtherCityRadio.disabled = false;
                itemLost.disabled = false;
                reasonOfLoss.disabled = false;
                validIDFront.disabled = false;
                validIDBack.disabled = false;

                if(documentSanPedroCityRadio.checked) {
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                } else if (documentOtherCityRadio.checked) {
                    documentOtherAddressGroup.classList.remove('d-none');
                }

            } else if(originalDocumentType === 'Affidavit of Guardianship') {
                const guardianName = document.getElementById('guardian_name');
                const guardianAge = document.getElementById('guardian_age');
                const useSameAddress = document.getElementById('use_same_address');
                const guardianSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const guardianOtherCityRadio = document.getElementById('document-other-city');

                const guardianBarangayGroup = document.getElementById('document-barangay-group');
                const guardianStreetGroup = document.getElementById('document-street-group');
                const guardianBarangay = document.getElementById('document-barangay');
                const guardianStreet = document.getElementById('document-street');

                const guardianOtherAddressGroup = document.getElementById('document-other-address-group');
                const guardianOtherCityInput = document.getElementById('document-other-city-input');
                const guardianOtherBarangay = document.getElementById('document-other-barangay');
                const guardianOtherStreet = document.getElementById('document-other-street');

                const guardianOccupation = document.getElementById('guardian_occupation');
                const guardianBrgyClearance = document.getElementById('barangay_clearance');
                const guardianRelationship = document.getElementById('relationship');

                const minorName = document.getElementById('minor_name');
                const minorAge = document.getElementById('minor_age');
                const useSameAddress2 = document.getElementById('use_same_address_2');
                const minorSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
                const minorOtherCityRadio = document.getElementById('document-other-city-2');

                const minorBarangayGroup = document.getElementById('document-barangay-group-2');
                const minorStreetGroup = document.getElementById('document-street-group-2');
                const minorBarangay = document.getElementById('document-barangay-2');
                const minorStreet = document.getElementById('document-street-2');

                const minorOtherAddressGroup = document.getElementById('document-other-address-group-2');
                const minorOtherCityInput = document.getElementById('document-other-city-input-2');
                const minorOtherBarangay = document.getElementById('document-other-barangay-2');
                const minorOtherStreet = document.getElementById('document-other-street-2');

                const minorRelationship = document.getElementById('minor_relationship');

                guardianName.disabled = false;
                guardianAge.disabled = false;
                useSameAddress.disabled = false;
                guardianSanPedroCityRadio.disabled = false;
                guardianOtherCityRadio.disabled = false;
                guardianOccupation.disabled = false;
                guardianBrgyClearance.disabled = false;
                guardianRelationship.disabled = false;

                minorName.disabled = false;
                minorAge.disabled = false;
                useSameAddress2.disabled = false;
                minorSanPedroCityRadio.disabled = false;
                minorOtherCityRadio.disabled = false;
                minorRelationship.disabled = false;

                if(guardianSanPedroCityRadio.checked) {
                    guardianBarangayGroup.classList.remove('d-none');
                    guardianStreetGroup.classList.remove('d-none');
                } else if (guardianOtherCityRadio.checked) {
                    guardianOtherAddressGroup.classList.remove('d-none');
                }

                if(minorSanPedroCityRadio.checked) {
                    minorBarangayGroup.classList.remove('d-none');
                    minorStreetGroup.classList.remove('d-none');
                } else if (minorOtherCityRadio.checked) {
                    minorOtherAddressGroup.classList.remove('d-none');
                }

            } else if(originalDocumentType === 'Affidavit of No income') {
                const documentName = document.getElementById('document_name');
                const documentAge = document.getElementById('document_age');
                const useSameAddress = document.getElementById('use_same_address');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');

                const certificateOfIndigency = document.getElementById('certificate_of_indigency');

                const employerName = document.getElementById('previous_employer_name');
                const employerContact = document.getElementById('previous_employer_contact');

                const businessName = document.getElementById('business_name');
                const registrationNumber = document.getElementById('registration_number');
                const businessAddress = document.getElementById('business_address');
                const businessPeriod = document.getElementById('business_period');
                const noIncomePeriod = document.getElementById('no_income_period');

                documentName.disabled = false;
                documentAge.disabled = false;
                useSameAddress.disabled = false;
                documentSanPedroCityRadio.disabled = false;
                documentOtherCityRadio.disabled = false;
                certificateOfIndigency.disabled = false;
                employerName.disabled = false;
                employerContact.disabled = false;
                businessName.disabled = false;
                registrationNumber.disabled = false;
                businessAddress.disabled = false;
                businessPeriod.disabled = false;
                noIncomePeriod.disabled = false;

                if(documentSanPedroCityRadio.checked) {
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                } else if (documentOtherCityRadio.checked) {
                    documentOtherAddressGroup.classList.remove('d-none');
                }

            } else if(originalDocumentType === 'Affidavit of No fix income') {
                const documentName = document.getElementById('document_name');
                const documentAge = document.getElementById('document_age');
                const useSameAddress = document.getElementById('use_same_address');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');
                const documentOtherStreet = document.getElementById('document-other-street');

                const sourceOfIncome = document.getElementById('source_of_income');
                const certificateOfIndigency = document.getElementById('certificate_of_indigency');

                documentName.disabled = false;
                documentAge.disabled = false;
                useSameAddress.disabled = false;
                documentSanPedroCityRadio.disabled = false;
                documentOtherCityRadio.disabled = false;
                sourceOfIncome.disabled = false;
                certificateOfIndigency.disabled = false;

                if(documentSanPedroCityRadio.checked) {
                    documentBarangayGroup.classList.remove('d-none');
                    documentStreetGroup.classList.remove('d-none');
                } else if (documentOtherCityRadio.checked) {
                    documentOtherAddressGroup.classList.remove('d-none');
                }

            } else if(originalDocumentType === 'Extra Judicial') {
                const deathtCertificate = document.getElementById('death_certificate');
                const heirshipDocument = document.getElementById('heirship_documents');
                const inventoryOfEstate = document.getElementById('inventory_of_estate');
                const taxClearance = document.getElementById('tax_clearance');
                const deedOfExtrajudicialSettlement = document.getElementById('deed_of_extrajudicial_settlement');

                deathtCertificate.disabled = false;
                heirshipDocument.disabled = false;
                inventoryOfEstate.disabled = false;
                taxClearance.disabled = false;
                deedOfExtrajudicialSettlement.disabled = false;

            } else if(originalDocumentType === 'Deed of Sale') {
                const nameOfIdentity1 = document.getElementById('party1_name');
                const nameOfIdentity2 = document.getElementById('party2_name');
                const propertyDetails = document.getElementById('property_details');

                nameOfIdentity1.disabled = false;
                nameOfIdentity2.disabled = false;
                propertyDetails.disabled = false;

            } else if(originalDocumentType === 'Deed of Donation') {
                const donorName = document.getElementById('donor_name');
                const donorAge = document.getElementById('donor_age');
                const useSameAddress = document.getElementById('use_same_address');
                const donorSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const donorOtherCityRadio = document.getElementById('document-other-city');

                const donorBarangayGroup = document.getElementById('document-barangay-group');
                const donorStreetGroup = document.getElementById('document-street-group');
                const donorBarangay = document.getElementById('document-barangay');
                const donorStreet = document.getElementById('document-street');

                const donorOtherAddressGroup = document.getElementById('document-other-address-group');
                const donorOtherCityInput = document.getElementById('document-other-city-input');
                const donorOtherBarangay = document.getElementById('document-other-barangay');
                const donorOtherStreet = document.getElementById('document-other-street');

                const doneeName = document.getElementById('donee_name');
                const doneeAge = document.getElementById('donee_age');
                const useSameAddress2 = document.getElementById('use_same_address_2');
                const doneeSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
                const doneeOtherCityRadio = document.getElementById('document-other-city-2');

                const doneeBarangayGroup = document.getElementById('document-barangay-group-2');
                const doneeStreetGroup = document.getElementById('document-street-group-2');
                const doneeBarangay = document.getElementById('document-barangay-2');
                const doneeStreet = document.getElementById('document-street-2');

                const doneeOtherAddressGroup = document.getElementById('document-other-address-group-2');
                const doneeOtherCityInput = document.getElementById('document-other-city-input-2');
                const doneeOtherBarangay = document.getElementById('document-other-barangay-2');
                const doneeOtherStreet = document.getElementById('document-other-street-2');

                donorName.disabled = false;
                donorAge.disabled = false;
                useSameAddress.disabled = false;
                donorSanPedroCityRadio.disabled = false;
                donorOtherCityRadio.disabled = false;

                doneeName.disabled = false;
                doneeAge.disabled = false;
                useSameAddress2.disabled = false;
                doneeSanPedroCityRadio.disabled = false;
                doneeOtherCityRadio.disabled = false;

                if(donorSanPedroCityRadio.checked) {
                    donorBarangayGroup.classList.remove('d-none');
                    donorStreetGroup.classList.remove('d-none');
                } else if (donorOtherCityRadio.checked) {
                    donorOtherAddressGroup.classList.remove('d-none');
                }

                if(doneeSanPedroCityRadio.checked) {
                    doneeBarangayGroup.classList.remove('d-none');
                    doneeStreetGroup.classList.remove('d-none');
                } else if (doneeOtherCityRadio.checked) {
                    doneeOtherAddressGroup.classList.remove('d-none');
                }

            } else if(originalDocumentType === 'Other Document') {
                const validIDFront = document.getElementById('valid-id-front');
                const validIDBack = document.getElementById('valid-id-back');

                validIDFront.disabled = false;
                validIDBack.disabled = false;
            
            } else {
                additionalInfo.innerHTML = '';
            }


           documentType.addEventListener('change', () => {
                if(documentType.value === 'Affidavit of Loss') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.affidavitOfLoss')
                    `;

                    additionalInfoAddress();

                } else if(documentType.value === 'Affidavit of Guardianship') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.affidavitOfGuardianship')
                    `;

                    additionalInfoAddress();
                    additionalInfoAddress2();

                } else if(documentType.value === 'Affidavit of No income') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.affidavitOfNoIncome')
                    `;

                    additionalInfoAddress();

                } else if(documentType.value === 'Affidavit of No fix income') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.affidavitOfNoFixIncome')
                    `;

                    additionalInfoAddress();

                } else if(documentType.value === 'Extra Judicial') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.extraJudicial')
                    `;
                } else if(documentType.value === 'Deed of Sale') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.deedOfSale')
                    `;
                } else if(documentType.value === 'Deed of Donation') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.deedOfDonation')
                    `;

                    additionalInfoAddress();
                    additionalInfoAddress2();

                } else if(documentType.value === 'Other Document') {
                    additionalInfo.innerHTML = `
                        @include('document-request.additionalInfo.otherDocument')
                    `;
                
                } else {
                    additionalInfo.innerHTML = '';
                }
        
            });
        } else {
            editDocumentRequestBtn.textContent = "Edit Document Request";
            editDocumentRequestBtn.classList.remove("btn-danger");
            editDocumentRequestBtn.classList.add("btn-primary");
            documentType.disabled = true;
            name.disabled = true;
            cellphoneNumber.disabled = true;
            email.disabled = true;
            sanPedroCityRadio.disabled = true;
            otherCityRadio.disabled = true;

            // Hide update button
            updateDocumentRequestBtn.classList.remove("showBtn");
            updateDocumentRequestBtn.classList.add("hideBtn");

            // Reset fields to original values
            documentType.value = originalDocumentType;
            name.value = originalName;
            cellphoneNumber.value = originalCellphoneNumber;
            email.value = originalEmail;

            // Reset radio buttons
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
            
            if(originalDocumentType === 'Affidavit of Loss') {
                generateAdditionalInfo(originalDocumentType);

                const documentName = document.getElementById('document_name');
                const documentCivilStatus = document.getElementById('document_civil_status');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');
                const documentOtherStreet = document.getElementById('document-other-street');

                const itemLost = document.getElementById('item_lost');
                const reasonOfLoss = document.getElementById('reason_of_loss');
                const validIDFront = document.getElementById('valid-id-front');
                const validIDBack = document.getElementById('valid-id-back');

                const originalDocumentName = "{{ $additional_info->name }}";
                const originalDocumentCivilStatus = "{{ $additional_info->civil_status }}";
                const originalDocumentCity = "{{ $document_city }}";
                const originalDocumentBarangay = "{{ $document_final_barangay }}";
                const originalDocumentStreet = "{{ $document_street }}";
                const originalDocumentOtherCity = "{{ $document_other_city }}";
                const originalDocumentOtherBarangay = "{{ $document_other_barangay }}";
                const originalDocumentOtherStreet = "{{ $document_other_street }}";
                const originalItemLost = "{{ $additional_info->item_lost }}";
                const originalReasonOfLoss = "{{ $additional_info->reason_of_loss }}";

                documentName.disabled = true;
                documentCivilStatus.disabled = true;
                documentSanPedroCityRadio.disabled = true;
                documentOtherCityRadio.disabled = true;
                itemLost.disabled = true;
                reasonOfLoss.disabled = true;
                validIDFront.disabled = true;
                validIDBack.disabled = true;

                documentName.value = originalDocumentName;
                documentAge.value = originalDocumentAge;
                documentCivilStatus.value = originalDocumentCivilStatus;
                itemLost.value = originalItemLost;
                reasonOfLoss.value = originalReasonOfLoss;
                validIDFront.value = "";
                validIDBack.value = "";

                if (originalDocumentCity === 'San Pedro City') {
                    documentSanPedroCityRadio.checked = true;
                    documentOtherCityRadio.checked = false;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;
                } else {
                    documentSanPedroCityRadio.checked = false;
                    documentOtherCityRadio.checked = true;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                }

                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.add('d-none');

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Affidavit of Guardianship') {
                generateAdditionalInfo(originalDocumentType);

                const guardianName = document.getElementById('guardian_name');
                const guardianAge = document.getElementById('guardian_age');
                const guardianSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const guardianOtherCityRadio = document.getElementById('document-other-city');

                const guardianBarangayGroup = document.getElementById('document-barangay-group');
                const guardianStreetGroup = document.getElementById('document-street-group');
                const guardianBarangay = document.getElementById('document-barangay');
                const guardianStreet = document.getElementById('document-street');

                const guardianOtherAddressGroup = document.getElementById('document-other-address-group');
                const guardianOtherCityInput = document.getElementById('document-other-city-input');
                const guardianOtherBarangay = document.getElementById('document-other-barangay');
                const guardianOtherStreet = document.getElementById('document-other-street');

                const guardianOccupation = document.getElementById('guardian_occupation');
                const guardianBrgyClearance = document.getElementById('barangay_clearance');
                const guardianRelationship = document.getElementById('relationship');

                const minorName = document.getElementById('minor_name');
                const minorAge = document.getElementById('minor_age');
                const minorSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
                const minorOtherCityRadio = document.getElementById('document-other-city-2');

                const minorBarangayGroup = document.getElementById('document-barangay-group-2');
                const minorStreetGroup = document.getElementById('document-street-group-2');
                const minorBarangay = document.getElementById('document-barangay-2');
                const minorStreet = document.getElementById('document-street-2');

                const minorOtherAddressGroup = document.getElementById('document-other-address-group-2');
                const minorOtherCityInput = document.getElementById('document-other-city-input-2');
                const minorOtherBarangay = document.getElementById('document-other-barangay-2');
                const minorOtherStreet = document.getElementById('document-other-street-2');

                const minorRelationship = document.getElementById('minor_relationship');

                const originalGuardianName = "{{ $additional_info->guardian_name }}";
                const originalGuardianAge = "{{ $additional_info->guardian_age }}";
                const originalGuardianCity = "{{ $document_city }}";
                const originalGuardianBarangay = "{{ $document_final_barangay }}";
                const originalGuardianStreet = "{{ $document_street }}";
                const originalGuardianOtherCity = "{{ $document_other_city }}";
                const originalGuardianOtherBarangay = "{{ $document_other_barangay }}";
                const originalGuardianOtherStreet = "{{ $document_other_street }}";
                const originalGuardianOccupation = "{{ $additional_info->guardian_occupation }}";
                const originalGuardianRelationship = "{{ $additional_info->guardian_relationship }}";

                const originalMinorName = "{{ $additional_info->minor_name }}";
                const originalMinorAge = "{{ $additional_info->minor_age }}";
                const originalMinorCity = "{{ $document_city_2 }}";
                const originalMinorBarangay = "{{ $document_final_barangay_2 }}";
                const originalMinorStreet = "{{ $document_street_2 }}";
                const originalMinorOtherCity = "{{ $document_other_city_2 }}";
                const originalMinorOtherBarangay = "{{ $document_other_barangay_2 }}";
                const originalMinorOtherStreet = "{{ $document_other_street_2 }}";
                const originalMinorRelationship = "{{ $additional_info->minor_relationship }}";

                guardianName.disabled = true;
                guardianAge.disabled = true;
                guardianSanPedroCityRadio.disabled = true;
                guardianOtherCityRadio.disabled = true;
                guardianOccupation.disabled = true;
                guardianBrgyClearance.disabled = true;
                guardianRelationship.disabled = true;

                minorName.disabled = true;
                minorAge.disabled = true;
                minorSanPedroCityRadio.disabled = true;
                minorOtherCityRadio.disabled = true;
                minorRelationship.disabled = true;

                guardianName.value = originalGuardianName;
                guardianAge.value = originalGuardianAge;
                guardianOccupation.value = originalGuardianOccupation;
                guardianBrgyClearance.value = "";
                guardianRelationship.value = originalGuardianRelationship;

                minorName.value = originalMinorName;
                minorAge.value = originalMinorAge;
                minorRelationship.value = originalMinorRelationship;

                if(originalGuardianCity === 'San Pedro City') {
                    guardianSanPedroCityRadio.checked = true;
                    guardianOtherCityRadio.checked = false;
                    guardianBarangay.value = originalGuardianBarangay;
                    guardianStreet.value = originalGuardianStreet;
                    guardianOtherCityInput.value = originalGuardianOtherCity;
                    guardianOtherBarangay.value = originalGuardianOtherBarangay;
                    guardianOtherStreet.value = originalGuardianOtherStreet;
                } else {
                    guardianSanPedroCityRadio.checked = false;
                    guardianOtherCityRadio.checked = true;
                    guardianOtherCityInput.value = originalGuardianOtherCity;
                    guardianOtherBarangay.value = originalGuardianOtherBarangay;
                    guardianOtherStreet.value = originalGuardianOtherStreet;
                    guardianBarangay.value = originalGuardianBarangay;
                    guardianStreet.value = originalGuardianStreet;
                }

                if(originalMinorCity === 'San Pedro City') {
                    minorSanPedroCityRadio.checked = true;
                    minorOtherCityRadio.checked = false;
                    minorBarangay.value = originalMinorBarangay;
                    minorStreet.value = originalMinorStreet;
                    minorOtherCityInput.value = originalMinorOtherCity;
                    minorOtherBarangay.value = originalMinorOtherBarangay;
                    minorOtherStreet.value = originalMinorOtherStreet;
                } else {
                    minorSanPedroCityRadio.checked = false;
                    minorOtherCityRadio.checked = true;
                    minorOtherCityInput.value = originalMinorOtherCity;
                    minorOtherBarangay.value = originalMinorOtherBarangay;
                    minorOtherStreet.value = originalMinorOtherStreet;
                    minorBarangay.value = originalMinorBarangay;
                    minorStreet.value = originalMinorStreet;
                }

                guardianBarangayGroup.classList.add('d-none');
                guardianStreetGroup.classList.add('d-none');
                guardianOtherAddressGroup.classList.add('d-none');

                minorBarangayGroup.classList.add('d-none');
                minorStreetGroup.classList.add('d-none');
                minorOtherAddressGroup.classList.add('d-none');

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Affidavit of No income') {
                generateAdditionalInfo(originalDocumentType);

                const documentName = document.getElementById('document_name');
                const documentAge = document.getElementById('document_age');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');

                const certificateOfIndigency = document.getElementById('certificate_of_indigency');

                const employerName = document.getElementById('previous_employer_name');
                const employerContact = document.getElementById('previous_employer_contact');

                const businessName = document.getElementById('business_name');
                const registrationNumber = document.getElementById('registration_number');
                const businessAddress = document.getElementById('business_address');
                const businessPeriod = document.getElementById('business_period');
                const noIncomePeriod = document.getElementById('no_income_period');

                const originalDocumentName = "{{ $additional_info->aoni_name }}";
                const originalDocumentAge = "{{ $additional_info->aoni_age }}";
                const originalDocumentCity = "{{ $document_city }}";
                const originalDocumentBarangay = "{{ $document_final_barangay }}";
                const originalDocumentStreet = "{{ $document_street }}";
                const originalDocumentOtherCity = "{{ $document_other_city }}";
                const originalDocumentOtherBarangay = "{{ $document_other_barangay }}";
                const originalDocumentOtherStreet = "{{ $document_other_street }}";

                const originalEmployerName = "{{ $additional_info->previous_employer_name }}";
                const originalEmployerContact = "{{ $additional_info->previous_employer_contact }}";

                const originalBusinessName = "{{ $additional_info->business_name }}";
                const originalRegistrationNumber = "{{ $additional_info->registration_number }}";
                const originalBusinessAddress = "{{ $additional_info->business_address }}";
                const originalBusinessPeriod = "{{ $additional_info->business_period }}";
                const originalNoIncomePeriod = "{{ $additional_info->no_income_period }}";

                documentName.disabled = true;
                documentAge.disabled = true;
                documentSanPedroCityRadio.disabled = true;
                documentOtherCityRadio.disabled = true;
                certificateOfIndigency.disabled = true;
                employerName.disabled = true;
                employerContact.disabled = true;
                businessName.disabled = true;
                registrationNumber.disabled = true;
                businessAddress.disabled = true;
                businessPeriod.disabled = true;
                noIncomePeriod.disabled = true;

                documentName.value = originalDocumentName;
                documentAge.value = originalDocumentAge;
                certificateOfIndigency.value = "";

                employerName.value = originalEmployerName;
                employerContact.value = originalEmployerContact;

                businessName.value = originalBusinessName;
                registrationNumber.value = originalRegistrationNumber;
                businessAddress.value = originalBusinessAddress;
                businessPeriod.value = originalBusinessPeriod;
                noIncomePeriod.value = originalNoIncomePeriod;

                if (originalDocumentCity === 'San Pedro City') {
                    documentSanPedroCityRadio.checked = true;
                    documentOtherCityRadio.checked = false;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                } else {
                    documentSanPedroCityRadio.checked = false;
                    documentOtherCityRadio.checked = true;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                }

                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.add('d-none');

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Affidavit of No fix income') {
                generateAdditionalInfo(originalDocumentType);

                const documentName = document.getElementById('document_name');
                const documentAge = document.getElementById('document_age');
                const documentSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const documentOtherCityRadio = document.getElementById('document-other-city');

                const documentBarangayGroup = document.getElementById('document-barangay-group');
                const documentStreetGroup = document.getElementById('document-street-group');
                const documentBarangay = document.getElementById('document-barangay');
                const documentStreet = document.getElementById('document-street');

                const documentOtherAddressGroup = document.getElementById('document-other-address-group');
                const documentOtherCityInput = document.getElementById('document-other-city-input');
                const documentOtherBarangay = document.getElementById('document-other-barangay');
                const documentOtherStreet = document.getElementById('document-other-street');

                const sourceOfIncome = document.getElementById('source_of_income');
                const certificateOfIndigency = document.getElementById('certificate_of_indigency');

                const originalDocumentName = "{{ $additional_info->aonfi_name }}";
                const originalDocumentAge = "{{ $additional_info->aonfi_age }}";
                const originalDocumentCity = "{{ $document_city }}";
                const originalDocumentBarangay = "{{ $document_final_barangay }}";
                const originalDocumentStreet = "{{ $document_street }}";
                const originalDocumentOtherCity = "{{ $document_other_city }}";
                const originalDocumentOtherBarangay = "{{ $document_other_barangay }}";
                const originalDocumentOtherStreet = "{{ $document_other_street }}";
                const originalSourceOfIncome = "{{ $additional_info->source_of_income }}";

                documentName.disabled = true;
                documentAge.disabled = true;
                documentSanPedroCityRadio.disabled = true;
                documentOtherCityRadio.disabled = true;
                sourceOfIncome.disabled = true;
                certificateOfIndigency.disabled = true;

                documentName.value = originalDocumentName;
                documentAge.value = originalDocumentAge;
                sourceOfIncome.value = originalSourceOfIncome;
                certificateOfIndigency.value = "";

                if (originalDocumentCity === 'San Pedro City') {
                    documentSanPedroCityRadio.checked = true;
                    documentOtherCityRadio.checked = false;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;
                } else {
                    documentSanPedroCityRadio.checked = false;
                    documentOtherCityRadio.checked = true;
                    documentOtherCityInput.value = originalDocumentOtherCity;
                    documentOtherBarangay.value = originalDocumentOtherBarangay;
                    documentOtherStreet.value = originalDocumentOtherStreet;
                    documentBarangay.value = originalDocumentBarangay;
                    documentStreet.value = originalDocumentStreet;
                }

                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentOtherAddressGroup.classList.add('d-none');

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Extra Judicial') {
                generateAdditionalInfo(originalDocumentType);

                const deathtCertificate = document.getElementById('death_certificate');
                const heirshipDocument = document.getElementById('heirship_documents');
                const inventoryOfEstate = document.getElementById('inventory_of_estate');
                const taxClearance = document.getElementById('tax_clearance');
                const deedOfExtrajudicialSettlement = document.getElementById('deed_of_extrajudicial_settlement');

                deathtCertificate.disabled = true;
                heirshipDocument.disabled = true;
                inventoryOfEstate.disabled = true;
                taxClearance.disabled = true;
                deedOfExtrajudicialSettlement.disabled = true;

                deathtCertificate.value = "";
                heirshipDocument.value = "";
                inventoryOfEstate.value = "";
                taxClearance.value = "";
                deedOfExtrajudicialSettlement.value = "";

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Deed of Sale') {
                generateAdditionalInfo(originalDocumentType);

                const nameOfIdentity1 = document.getElementById('party1_name');
                const nameOfIdentity2 = document.getElementById('party2_name');
                const propertyDetails = document.getElementById('property_details');

                const originalNameOfIdentity1 = "{{ $additional_info->name_identity_1 }}";
                const originalNameOfIdentity2 = "{{ $additional_info->name_identity_2 }}";
                const originalPropertyDetails = "{{ $additional_info->details }}";

                nameOfIdentity1.disabled = true;
                nameOfIdentity2.disabled = true;
                propertyDetails.disabled = true;

                nameOfIdentity1.value = originalNameOfIdentity1;
                nameOfIdentity2.value = originalNameOfIdentity2;
                propertyDetails.value = originalPropertyDetails;

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Deed of Donation') {
                generateAdditionalInfo(originalDocumentType);

                const donorName = document.getElementById('donor_name');
                const donorAge = document.getElementById('donor_age');
                const donorSanPedroCityRadio = document.getElementById('document-san-pedro-city');
                const donorOtherCityRadio = document.getElementById('document-other-city');

                const donorBarangayGroup = document.getElementById('document-barangay-group');
                const donorStreetGroup = document.getElementById('document-street-group');
                const donorBarangay = document.getElementById('document-barangay');
                const donorStreet = document.getElementById('document-street');

                const donorOtherAddressGroup = document.getElementById('document-other-address-group');
                const donorOtherCityInput = document.getElementById('document-other-city-input');
                const donorOtherBarangay = document.getElementById('document-other-barangay');
                const donorOtherStreet = document.getElementById('document-other-street');

                const doneeName = document.getElementById('donee_name');
                const doneeAge = document.getElementById('donee_age');
                const doneeSanPedroCityRadio = document.getElementById('document-san-pedro-city-2');
                const doneeOtherCityRadio = document.getElementById('document-other-city-2');

                const doneeBarangayGroup = document.getElementById('document-barangay-group-2');
                const doneeStreetGroup = document.getElementById('document-street-group-2');
                const doneeBarangay = document.getElementById('document-barangay-2');
                const doneeStreet = document.getElementById('document-street-2');

                const doneeOtherAddressGroup = document.getElementById('document-other-address-group-2');
                const doneeOtherCityInput = document.getElementById('document-other-city-input-2');
                const doneeOtherBarangay = document.getElementById('document-other-barangay-2');
                const doneeOtherStreet = document.getElementById('document-other-street-2');

                const originalDonorName = "{{ $additional_info->donor_name }}";
                const originalDonorAge = "{{ $additional_info->donor_age }}";
                const originalDonorCity = "{{ $document_city }}";
                const originalDonorBarangay = "{{ $document_final_barangay }}";
                const originalDonorStreet = "{{ $document_street }}";
                const originalDonorOtherCity = "{{ $document_other_city }}";
                const originalDonorOtherBarangay = "{{ $document_other_barangay }}";
                const originalDonorOtherStreet = "{{ $document_other_street }}";

                const originalDoneeName = "{{ $additional_info->donee_name }}";
                const originalDoneeAge = "{{ $additional_info->donee_age }}";
                const originalDoneeCity = "{{ $document_city_2 }}";
                const originalDoneeBarangay = "{{ $document_final_barangay_2 }}";
                const originalDoneeStreet = "{{ $document_street_2 }}";
                const originalDoneeOtherCity = "{{ $document_other_city_2 }}";
                const originalDoneeOtherBarangay = "{{ $document_other_barangay_2 }}";
                const originalDoneeOtherStreet = "{{ $document_other_street_2 }}";

                donorName.disabled = true;
                donorAge.disabled = true;
                donorSanPedroCityRadio.disabled = true;
                donorOtherCityRadio.disabled = true;

                doneeName.disabled = true;
                doneeAge.disabled = true;
                doneeSanPedroCityRadio.disabled = true;
                doneeOtherCityRadio.disabled = true;

                donorName.value = originalDonorName;
                donorAge.value = originalDonorAge;
                
                doneeName.value = originalDoneeName;
                doneeAge.value = originalDoneeAge;

                if(originalDonorCity === 'San Pedro City') {
                    donorSanPedroCityRadio.checked = true;
                    donorOtherCityRadio.checked = false;
                    donorBarangay.value = originalDonorBarangay;
                    donorStreet.value = originalDonorStreet;
                    donorOtherCityInput.value = originalDonorOtherCity;
                    donorOtherBarangay.value = originalDonorOtherBarangay;
                    donorOtherStreet.value = originalDonorOtherStreet;
                } else {
                    donorSanPedroCityRadio.checked = false;
                    donorOtherCityRadio.checked = true;
                    donorOtherCityInput.value = originalDonorOtherCity;
                    donorOtherBarangay.value = originalDonorOtherBarangay;
                    donorOtherStreet.value = originalDonorOtherStreet;
                    donorBarangay.value = originalDonorBarangay;
                    donorStreet.value = originalDonorStreet;
                }

                if(originalDoneeCity === 'San Pedro City') {
                    doneeSanPedroCityRadio.checked = true;
                    doneeOtherCityRadio.checked = false;
                    doneeBarangay.value = originalDoneeBarangay;
                    doneeStreet.value = originalDoneeStreet;
                    doneeOtherCityInput.value = originalDoneeOtherCity;
                    doneeOtherBarangay.value = originalDoneeOtherBarangay;
                    doneeOtherStreet.value = originalDoneeOtherStreet;
                } else {
                    doneeSanPedroCityRadio.checked = false;
                    doneeOtherCityRadio.checked = true;
                    doneeOtherCityInput.value = originalDoneeOtherCity;
                    doneeOtherBarangay.value = originalDoneeOtherBarangay;
                    doneeOtherStreet.value = originalDoneeOtherStreet;
                    doneeBarangay.value = originalDoneeBarangay;
                    doneeStreet.value = originalDoneeStreet;
                }

                donorBarangayGroup.classList.add('d-none');
                donorStreetGroup.classList.add('d-none');
                donorOtherAddressGroup.classList.add('d-none');

                doneeBarangayGroup.classList.add('d-none');
                doneeStreetGroup.classList.add('d-none');
                doneeOtherAddressGroup.classList.add('d-none');

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });

            } else if(originalDocumentType === 'Other Document') {
                generateAdditionalInfo(originalDocumentType);

                const validIDFront = document.getElementById('valid-id-front');
                const validIDBack = document.getElementById('valid-id-back');

                validIDFront.disabled = true;
                validIDBack.disabled = true;

                validIDFront.value = "";
                validIDBack.value = "";

                const errorElements = document.querySelectorAll('.invalid-feedback');
                errorElements.forEach(errorElement => {
                    errorElement.remove();
                });

                const inputElements = document.querySelectorAll('.is-invalid');
                inputElements.forEach(inputElement => {
                    inputElement.classList.remove('is-invalid');
                });
            
            } else {
                additionalInfo.innerHTML = '';
            }
            
            const errorElements = document.querySelectorAll('.invalid-feedback');
			errorElements.forEach(errorElement => {
				errorElement.remove();
			});

			const inputElements = document.querySelectorAll('.is-invalid');
			inputElements.forEach(inputElement => {
				inputElement.classList.remove('is-invalid');
			});
        }
    });

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

    const updateDocumentRequestBtn = document.getElementById("updateDocumentRequestBtn");
    const documentType = document.getElementById("document_type");

    updateDocumentRequestBtn.addEventListener("click", async () => {
        const editDocumentRequestForm = document.getElementById("editDocumentRequestForm");
        const formData = new FormData(editDocumentRequestForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            let response;
            if (documentType.value !== originalDocumentType) {
                response = await fetch('{{ route('document-request.validateEditNewDocumentRequestForm', $documentRequest->documentRequest_id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                    },
                    body: formData,
                });
            } else {
                response = await fetch('{{ route('document-request.validateEditSameDocumentRequestForm', $documentRequest->documentRequest_id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                    },
                    body: formData,
                });
            }

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
                    let modifiedKey = key;
                    if (key.includes('.')) {
                        modifiedKey = key.replace(/\./, '[') + ']'; // Replace dot with '[' and add ']'
                    }

                    console.log(`${modifiedKey}: ${value}`);
                    const input = document.querySelector(`[name="${modifiedKey}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') {
                console.log(documentType.value);
                console.log(originalDocumentType);
                if (documentType.value !== originalDocumentType) {
                    console.log('Validation passed - New document type');
                } else {
                    console.log('Validation passed - Same document type');
                }

                editDocumentRequestForm.submit();
            } else {
                console.log('Other errors');
            }

        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    
</script>
@endsection