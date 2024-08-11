@extends('layouts.app')

@section('contents')
	<div class="d-flex align-items-center justify-content-start addStaff mb-4">
        <a href="{{ route('document-request') }}" class="fas fa-angle-left fs-4"></a>
		<h1 class="mb-0 ml-4">Add Document Request</h1>
	</div>

	@if(Session::has('success'))
        <div class="alert alert-success" id="alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

	@if(Session::has('failed'))
        <div class="alert alert-danger" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="p-5">
        <div class="p-5 card shadow">
            <form action="{{ route('document-request.saveDocumentRequest') }}" method="POST" id="documentRequestForm" class="user" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="document_type">Document Type:</label>
                    <select name="document_type" id="document_type" class="form-control">
                        <option value="">-- Select Document --</option>
                        <option value="Affidavit of Loss" {{ old('document_type') == 'Affidavit of Loss' ? 'selected' : '' }}>Affidavit of Loss</option>
                        <option value="Affidavit of Guardianship" {{ old('document_type') == 'Affidavit of Guardianship' ? 'selected' : '' }}>Affidavit of Guardianship</option>
                        <option value="Affidavit of No income" {{ old('document_type') == 'Affidavit of No income' ? 'selected' : '' }}>Affidavit of No income</option>
                        <option value="Affidavit of No fix income" {{ old('document_type') == 'Affidavit of No fix income' ? 'selected' : '' }}>Affidavit of No fix income</option>
                        <option value="Extra Judicial" {{ old('document_type') == 'Extra Judicial' ? 'selected' : '' }}>Extra Judicial</option>
                        <option value="Deed of Sale" {{ old('document_type') == 'Deed of Sale' ? 'selected' : '' }}>Deed of Sale</option>
                        <option value="Deed of Donation" {{ old('document_type') == 'Deed of Donation' ? 'selected' : '' }}>Deed of Donation</option>
                        <option value="Other Document" {{ old('document_type') == 'Other Document' ? 'selected' : '' }}>Other Document</option>
                    </select>
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
                        <select name="barangay" id="barangay" class="form-control">
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
                                <input name="other_city" id="other-city-input" type="text"
                                    class="form-control form-control-user @error('other_city')is-invalid @enderror"
                                    placeholder="City (e.g. Biñan City)" value="{{ old('other_city') }}">
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

                <hr>

                <div class="form-group" id="additional-info"></div>
                
                <button type="button" class="btn btn-primary btn-user btn-block" id="createDocumentRequestBtn">Send Document Request</button>
            </form>
        </div>
    </div>
    
<script>
    const documentType = document.getElementById("document_type");
    const additionalInfo = document.getElementById("additional-info");

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
            const documentBarangay = document.getElementById('document_barangay');
            const documentStreet = document.getElementById('document_street');

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
                documentSanPedroCityRadio.checked = false;
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentBarangay.value = '';
                documentStreet.value = '';
                documentOtherCityRadio.checked = false;
                documentOtherAddressGroup.classList.add('d-none');
                documentOtherCity.value = '';
                documentOtherBarangay.value = '';
                documentOtherStreet.value = '';
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
            const documentBarangay = document.getElementById('document_barangay_2');
            const documentStreet = document.getElementById('document_street_2');

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
                documentSanPedroCityRadio.checked = false;
                documentBarangayGroup.classList.add('d-none');
                documentStreetGroup.classList.add('d-none');
                documentBarangay.value = '';
                documentStreet.value = '';
                documentOtherCityRadio.checked = false;
                documentOtherAddressGroup.classList.add('d-none');
                documentOtherCity.value = '';
                documentOtherBarangay.value = '';
                documentOtherStreet.value = '';
            }
        });
    }

    function extraJudicialDeceased() {
        document.getElementById('deceased_spouse').addEventListener('change', function() {
            var heirFields = document.getElementById('heirFields');
            var survivingSpouseField = document.getElementById('surviving_spouse');
            var spouseValidIDFields = document.getElementById('spouseValidIDFields');
            
            if (this.checked) {
                heirFields.style.display = 'block';
                survivingSpouseField.value = '';
                survivingSpouseField.disabled = true;
                spouseValidIDFields.style.display = 'none';
            } else {
                heirFields.style.display = 'none';
                survivingSpouseField.disabled = false;
                spouseValidIDFields.style.display = 'block';
            }
        });

        // Declare cloneCount outside of the event handler function
        var cloneCount = 0;

        document.getElementById('addHeirBtn').addEventListener('click', function() {
            var heirFields = document.getElementById('heirFields');
            var deceasedFields = document.getElementById('deceasedFields');
            var clone = deceasedFields.cloneNode(true); // Clone the entire deceasedFields section

            // Increment cloneCount each time the button is clicked
            cloneCount++;

            // Modify the id attribute of the cloned fields and adjust the names to use array-like structure
            var clonedFields = clone.querySelectorAll('input, select, textarea');
            clonedFields.forEach(function(field) {
                var originalId = field.id;
                var originalName = field.name;
                
                field.id = originalName.replace(/\[\d+\]/, '[' + cloneCount + ']'); // Adjust id for array-like structure
                field.name = originalName.replace(/\[\d+\]/, '[' + cloneCount + ']'); // Adjust name for array-like structure

                var errorElement = document.querySelector('[data-error="' + originalName + '"]');
                if (errorElement) {
                    errorElement.setAttribute('data-error', field.name);
                }

                // Update value using old() function for Laravel
                field.value = '{{ old(' + JSON.stringify(originalName) + ') }}';
            });

            // Modify the id attribute of the cloned deceasedField
            clone.id = 'deceasedFields_' + cloneCount;

            // Insert the cloned fields before the addHeirBtn
            heirFields.insertBefore(clone, document.getElementById('buttons'));

            // Enable the delete button
            document.getElementById('deleteHeirBtn').disabled = false;
        });

        document.getElementById('deleteHeirBtn').addEventListener('click', function() {
            // Find the most recent cloned field
            var mostRecentClone = document.getElementById('deceasedFields_' + cloneCount);
            if (mostRecentClone) {
                // Remove the most recent cloned field when the delete button is clicked
                mostRecentClone.remove();
                // Decrement the cloneCount variable
                cloneCount--;

                // If there are no remaining cloned fields, disable the delete button
                var deleteButton = document.getElementById('deleteHeirBtn');
                deleteButton.disabled = cloneCount === 0;

                // Adjust the index of the remaining fields
                var heirFields = document.getElementById('heirFields');
                var clonedFields = heirFields.querySelectorAll('[id^="deceasedFields_"]');
                clonedFields.forEach(function(field, index) {
                    var newIndex = index + 1;
                    field.id = 'deceasedFields_' + newIndex;
                    var inputs = field.querySelectorAll('input, select, textarea');
                    inputs.forEach(function(input) {
                        var name = input.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                        input.name = name;
                    });
                });
            }
        });

    }

    documentType.addEventListener('change', () => {
        if(documentType.value === 'Affidavit of Loss') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.affidavitOfLoss')
            `;

            additionalInfoAddress();
            useSameAddress();

        } else if(documentType.value === 'Affidavit of Guardianship') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.affidavitOfGuardianship')
            `;

            additionalInfoAddress();
            useSameAddress();

        } else if(documentType.value === 'Affidavit of No income') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.affidavitOfNoIncome')
            `;

            additionalInfoAddress();
            useSameAddress();

        } else if(documentType.value === 'Affidavit of No fix income') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.affidavitOfNoFixIncome')
            `;

            additionalInfoAddress();
            useSameAddress();

        } else if(documentType.value === 'Extra Judicial') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.extraJudicial')
            `;

            extraJudicialDeceased();

        } else if(documentType.value === 'Deed of Sale') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.deedOfSale')
            `;

            additionalInfoAddress();
            useSameAddress();
            
        } else if(documentType.value === 'Deed of Donation') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.deedOfDonation')
            `;

            additionalInfoAddress();
            additionalInfoAddress2();

            useSameAddress();
            useSameAddress2();

        } else if(documentType.value === 'Other Document') {
            additionalInfo.innerHTML = `
                @include('document-request.additionalInfo.otherDocument')
            `;
    
        } else {
            additionalInfo.innerHTML = '';
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

    const createDocumentRequestBtn = document.getElementById("createDocumentRequestBtn");

    createDocumentRequestBtn.addEventListener("click", async () => {
        const documentRequestForm = document.getElementById("documentRequestForm");
        const formData = new FormData(documentRequestForm);

        const errorElements = document.querySelectorAll('.invalid-feedback');
        errorElements.forEach(errorElement => {
            errorElement.remove();
        });

        const inputElements = document.querySelectorAll('.is-invalid');
        inputElements.forEach(inputElement => {
            inputElement.classList.remove('is-invalid');
        });

        try {
            const response = await fetch('{{ route('document-request.validateDocumentRequestForm') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Add CSRF token
                },
                body: formData,
            });

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

                    // console.log(`${modifiedKey}: ${value}`);
                    const input = document.querySelector(`[name="${modifiedKey}"]`);
                    const error = document.createElement('div');
                    error.classList.add('invalid-feedback');
                    error.textContent = value;
                    input.classList.add('is-invalid');
                    input.parentNode.insertBefore(error, input.nextSibling);
                }
            } else if (data.message === 'Validation passed') { 
                documentRequestForm.submit();
                console.log('Validation passed');
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 2000);
        }

        let failedAlert = document.getElementById('alert-failed');
        if (failedAlert) {
            setTimeout(() => {
                failedAlert.style.transition = "opacity 0.5s ease";
                failedAlert.style.opacity = 0;
                setTimeout(() => { failedAlert.remove(); }, 500);
            }, 2000);
        }
    });
</script>
@endsection