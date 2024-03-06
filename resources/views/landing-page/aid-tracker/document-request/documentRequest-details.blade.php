@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Tracker')

@section('contents')
    <section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up" data-aos-delay="200">AID Tracker - Document Request</h1>
            </div>
		</div>
	</section>

    <section class="ordinance pt-4">
		<div class="container" data-aos="fade-up">

			<header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">Document Request ID: {{ $documentRequest->documentRequest_id }}</p>
                    <a href="{{ route('documentRequestTracker') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

            <div class="d-flex justify-content-center row">
                <div class="card shadow p-5 col-sm-6 mb-sm-0 trackerDetailsForm">
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="trackerAppointmentContact">
                        <h3 class="text-center">Request of {{ $documentRequest->name }}</h3>
                        <p><strong>Name:</strong> {{ $documentRequest->name }}</p>
                        <p><strong>Address:</strong> {{ $documentRequest->address }}</p>
                        <p><strong>Contact Number:</strong> {{ $documentRequest->cellphone_number }}</p>
                        <p><strong>Email Address:</strong> {{ $documentRequest->email }}</p>
                    </div>

                    <hr>

                    <div class="trackerAppointmentContact">
                        <h5 class="text-center">{{ $documentRequest->document_type }} Information</h5>

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
                    
                    <hr>
                    <div>
                        <div class="message-wrapper">
                            <!-- Message History Here -->
                            <div class="message-container" >
                                @foreach($messages as $documentRequestMessage)
                                                             
                                        @if($documentRequestMessage->staff_name == null)
                                            <div class="card mb-3 your-message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title text-end">You</h5>
                                                    <p class="card-text text-end">{{ $documentRequestMessage->message }}</p>
                                                    <p class="card-text text-end"><small class="text-muted">{{ $documentRequestMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="card mb-3 message-card">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $documentRequestMessage->staff_name }}</h5>
                                                    <p class="card-text">{{ $documentRequestMessage->message }}</p>
                                                    <p class="card-text"><small class="text-muted">{{ $documentRequestMessage->created_at }}</small></p>
                                                </div>
                                            </div>
                                        @endif
                                            
                                @endforeach
                            </div>
                        </div>

                        <hr>
                        
                        @if($documentRequest->documentRequest_status == 'Approved' || $documentRequest->documentRequest_status == 'Processing' || $documentRequest->documentRequest_status == 'On Hold')
                            <form action="{{ route('documentRequestSendMessage', $documentRequest->documentRequest_id) }}" method="POST" class="user">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-sm-9">
                                        <textarea class="form-control form-control-textbox" id="message" name="message" rows="2" placeholder="Type Message"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary btn-block" id="send-btn">Send</button>
                                    </div>
                                </div>
                            </form>
                        @elseif ($documentRequest->documentRequest_status == 'Pending')
                            <div class="alert alert-warning" role="alert">
                                Document Request is pending.
                            </div>
                        @elseif ($documentRequest->documentRequest_status == 'Claimed')
                            <div class="alert alert-success" role="alert">
                                Document Request is finished.
                            </div>
                        @elseif ($documentRequest->documentRequest_status == 'Cancelled')
                            <div class="alert alert-danger" role="alert">
                                Document Request is cancelled.
                            </div>
                        @elseif ($documentRequest->documentRequest_status == 'Declined')
                            <div class="alert alert-danger" role="alert">
                                Document Request is declined.
                            </div>
                        @elseif ($documentRequest->documentRequest_status == 'To Claim')
                            <div class="alert alert-success" role="alert">
                                Document Request is ready to claim.
                            </div>
                        @elseif ($documentRequest->documentRequest_status == 'Unclaimed')
                            <div class="alert alert-danger" role="alert">
                                Document Request is unclaimed.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card shadow col-sm-3 trackerAppointmentDetails">
                    <div class="d-flex flex-column justify-content-center">
                        <a href="{{ route('refreshDocumentRequest', $documentRequest->documentRequest_id) }}" class="btn btn-primary"><i class="ri-refresh-line icon"></i> Refresh Page</a>
                        <hr>
                        <h3 class="text-center">Request Details</h3>
                        <p><strong>Request ID:</strong> {{ $documentRequest->documentRequest_id }}</p>
                        <p><strong>Status:</strong> {{ $documentRequest->documentRequest_status }}</p>
                        <p><strong>Document Type:</strong> {{ $documentRequest->document_type }}</p>
                        <p><strong>Created At:</strong> {{ $documentRequest->created_at }}</p>
                        <p><strong>Updated At:</strong> {{ $documentRequest->updated_at }}</p>
                        @if($documentRequest->documentRequest_status == 'Claimed')
                            <hr>
                            @if($feedback->count() > 0)
                                <p><strong>Rating:</strong> {{ $rating }}</p>
                                <p><strong>Comment:</strong> {{ $comment }}</p>
                                <a href="{{ route('redirectEditDocumentRequestFeedback', $documentRequest->documentRequest_id) }}" class="btn btn-warning">Edit Feedback <i class="ri-file-list-fill icon"></i></a>
                            @else
                                <a href="{{ route('redirectDocumentRequestFeedback', $documentRequest->documentRequest_id) }}" class="btn btn-primary">Give Feedback <i class="ri-file-list-fill icon"></i></a>
                            @endif
                        @elseif($documentRequest->documentRequest_status == 'Pending' || $documentRequest->documentRequest_status == 'Approved')
                            <hr>
                            <a href="{{ route('cancelDocumentRequest', $documentRequest->documentRequest_id) }}" class="btn btn-danger">Cancel <i class="ri-close-circle-line icon"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection