<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .document-request-details {
            padding: 20px;
        }
        .document-request-details h2 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
        }
        .document-request-details h3 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
        }
        .contact-info {
            margin-bottom: 20px;
        }
        .message-history {
            border-top: 2px solid #ccc;
            padding-top: 20px;
            margin-bottom: 20px;
        }
        .message-history h3 {
            color: #35784F;
            margin-bottom: 20px;
        }
        .message-card {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .message-card .card-title {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .message-card .card-text {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .message-card .card-time {
            color: gray;
        }
        .message-card .text-muted {
            font-size: 14px;
        }
        .your-message-card {
            background-color: #d6e9c6;
        }
        .text-end {
            text-align: end;
        }
        .text-start {
            text-align: start;
        }
        .feedback-section {
            border-top: 2px solid #ccc;
            padding-top: 20px;
        }
        .feedback-section h3 {
            text-align: center;
            color: #35784F;
            margin-bottom: 20px;
        }
        header {
            padding: 10px;
            text-align: center;
        }
        header p {
            margin: 10px 0;
            font-size: 35px;
            color: #35784F;
        }
        .date-generation {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="date-generation">Generated on: {{ date('Y-m-d H:i:s') }}</div>
        <p><strong>PedroAID</strong></p>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card document-request-details">
                    <h2 class="text-uppercase">Document Request Details</h2>
                    <div class="document-request-info">
                        <p><strong>Request ID:</strong> {{ $documentRequest->documentRequest_id }}</p>
                        <p><strong>Status:</strong> {{ $documentRequest->documentRequest_status }}</p>
                        <p><strong>Document Type:</strong> {{ $documentRequest->document_type }}</p>
                        <p><strong>Date Claimed:</strong> {{ $documentRequest->date_claimed }}</p>
                        <p><strong>Created At:</strong> {{ $documentRequest->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $documentRequest->updated_at }}</p>
                    </div>
                    <hr>
                    <div class="contact-info">
                        <h3 class="text-uppercase">Client Information</h3>
                        <p><strong>Client Name:</strong> {{ $documentRequest->name }}</p>
                        <p><strong>Address:</strong> {{ $documentRequest->address }}</p>
                        <p><strong>Contact Number:</strong> {{ $documentRequest->cellphone_number }}</p>
                        <p><strong>Email Address:</strong> {{ $documentRequest->email }}</p>
                    </div>
                    <hr>
                    <div class="contact-info">
                        <h3 class="text-uppercase">{{ $documentRequest->document_type }} Information</h3>

                        @if($documentRequest->document_type == 'Affidavit of Loss')

                            <p><strong>Name:</strong> {{ $additional_info->aol_name }}</p>
                            <p><strong>Age:</strong> {{ $additional_info->aol_age }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->aol_address }}</p>
                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>
                            <p><strong>Cedula:</strong> <a href="{{ asset($additional_info->cedula) }}" target="_blank">Cedula</a></p>

                        @elseif($documentRequest->document_type == 'Affidavit of Guardianship')

                            <p><strong>Guardian's Name:</strong> {{ $additional_info->guardian_name }}</p>
                            <p><strong>Guardian's Age:</strong> {{ $additional_info->guardian_age }}</p>
                            <p><strong>Guardian's Address:</strong> {{ $additional_info->guardian_address }}</p>
                            <p><strong>Guardian's Occupation:</strong> {{ $additional_info->guardian_occupation }}</p>
                            <p><strong>Guardian's Relationship to the Minor:</strong> {{ $additional_info->guardian_relationship }}</p>
                            <p><strong>Guardian's Barangay Clearance:</strong> <a href="{{ asset($additional_info->guardian_brgy_clearance) }}" target="_blank" >Barangay Clearance</a></p>
                            <br>
                            <p><strong>Minor's Name:</strong> {{ $additional_info->minor_name }}</p>
                            <p><strong>Minor's Age:</strong> {{ $additional_info->minor_age }}</p>
                            <p><strong>Minor's Address:</strong> {{ $additional_info->minor_address }}</p>
                            <p><strong>Minor's Relationship to the Guardian:</strong> {{ $additional_info->minor_relationship }}</p>
                        
                        @elseif($documentRequest->document_type == 'Affidavit of No income')

                            <p><strong>Name:</strong> {{ $additional_info->aoni_name }}</p>
                            <p><strong>Age:</strong> {{ $additional_info->aoni_age }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->aoni_address }}</p>
                            <p><strong>Certificate of Indigency:</strong> <a href="{{ asset($additional_info->certificate_of_indigency) }}" target="_blank">Certificate of Indigency</a></p>
                            <br>
                            <p><strong>Previous Employer's Name:</strong> {{ $additional_info->previous_employer_name }}</p>
                            <p><strong>Previous Employer's Contact:</strong> {{ $additional_info->previous_employer_contact }}</p>
                            <br>
                            <p><strong>Business Name:</strong> {{ $additional_info->business_name }}</p>
                            <p><strong>Registration Number:</strong> {{ $additional_info->registration_number }}</p>
                            <p><strong>Business Address:</strong> {{ $additional_info->business_address }}</p>
                            <p><strong>Business Period:</strong> {{ $additional_info->business_period }}</p>
                            <p><strong>No Income Period:</strong> {{ $additional_info->no_income_period }}</p>

                        @elseif($documentRequest->document_type == 'Affidavit of No fix income')

                            <p><strong>Name:</strong> {{ $additional_info->aonfi_name }}</p>
                            <p><strong>Age:</strong> {{ $additional_info->aonfi_age }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->aonfi_address }}</p>
                            <p><strong>Source of Income:</strong> {{ $additional_info->source_income }}</p>
                            <p><strong>Certificate of Indigency:</strong> <a href="{{ asset($additional_info->indigency) }}" target="_blank">Certificate of Indigency</a></p>

                        @elseif($documentRequest->document_type == 'Extra Judicial')

                            <p><strong>Death Certificate:</strong> <a href="{{ asset($additional_info->death_cert) }}" target="_blank">Death Certificate</a></p>
                            <p><strong>Heirship Documents:</strong> <a href="{{ asset($additional_info->heirship) }}" target="_blank">Heirship</a></p>
                            <p><strong>Inventory of Estate:</strong> <a href="{{ asset($additional_info->inv_estate) }}" target="_blank">Inventory of Estate</a></p>
                            <p><strong>Tax Clearance from BIR:</strong> <a href="{{ asset($additional_info->tax_clearance) }}" target="_blank">Tax Clearance</a></p>
                            <p><strong>Deed of Extra Judicial Settlement:</strong> <a href="{{ asset($additional_info->deed_extrajudicial) }}" target="_blank">Deed of Extra Judicial</a></p>

                        @elseif($documentRequest->document_type == 'Deed of Sale')

                            <p><strong>Party 1's Name:</strong> {{ $additional_info->name_identity_1 }}</p>
                            <p><strong>Party 2's Name:</strong> {{ $additional_info->name_identity_2 }}</p>
                            <p><strong>Details of Property/Vehicle:</strong> {{ $additional_info->details }}</p>

                        @elseif($documentRequest->document_type == 'Deed of Donation')

                            <p><strong>Donor's Name:</strong> {{ $additional_info->donor_name }}</p>
                            <p><strong>Donor's Age:</strong> {{ $additional_info->donor_age }}</p>
                            <p><strong>Donor's Address:</strong> {{ $additional_info->donor_address }}</p>
                            <br>
                            <p><strong>Donee's Name:</strong> {{ $additional_info->donee_name }}</p>
                            <p><strong>Donee's Age:</strong> {{ $additional_info->donee_age }}</p>
                            <p><strong>Donee's Address:</strong> {{ $additional_info->donee_address }}</p>

                        @else
                        
                        @endif
                    </div>
                </div>
                <div class="feedback-section">
                    <h3 class="text-uppercase">Feedback</h3>
                    <p><strong>Rating:</strong> {{ $rating }}</p>
                    <p><strong>Comment:</strong> {{ $comment }}</p>
                </div>
                <div class="message-history">
                    <h3 class="text-uppercase">Message History</h3>
                    @foreach($messages as $documentRequestMessage)
                        <div class="card message-card {{ !$documentRequestMessage->staff_name ? 'text-start' : ($documentRequestMessage->staff_name == $staffName ? 'your-message-card text-end' : '') }}">
                            <div class="card-body">
                                <h5 class="card-title {{ !$documentRequestMessage->staff_name ? 'text-start' : ($documentRequestMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ !$documentRequestMessage->staff_name ? $documentRequest->name : $documentRequestMessage->staff_name }}
                                </h5>
                                <p class="card-text {{ !$documentRequestMessage->staff_name ? 'text-start' : ($documentRequestMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $documentRequestMessage->message }}
                                </p>
                                <p class="card-text text-muted card-time {{ !$documentRequestMessage->staff_name ? 'text-start' : ($documentRequestMessage->staff_name == $staffName ? 'text-end' : 'text-start') }}">
                                    {{ $documentRequestMessage->created_at }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
