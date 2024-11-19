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

                            <p><strong>Name:</strong> {{ $additional_info->name }}</p>
                            <p><strong>Civil Status:</strong> {{ $additional_info->civil_status }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->address }}</p>
                            <p><strong>Item Lost:</strong> {{ $additional_info->item_lost }}</p>
                            <p><strong>Reason of Loss:</strong> {{ $additional_info->reason_of_loss }}</p>
                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>

                        @elseif($documentRequest->document_type == 'Affidavit of Guardianship')

                            <p><strong>Guardian's Name:</strong> {{ $additional_info->guardian_name }}</p>
                            <p><strong>Guardian's Civil Status:</strong> {{ $additional_info->civil_status }}</p>
                            <p><strong>Guardian's Address:</strong> {{ $additional_info->address }}</p>
                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>
                            <br>
                            <p><strong>Minor's Name:</strong> {{ $additional_info->minor_name }}</p>
                            <p><strong>Years in Care:</strong> {{ $additional_info->years_in_care }}</p>
                        
                        @elseif($documentRequest->document_type == 'Affidavit of No income')

                            <p><strong>Name:</strong> {{ $additional_info->name }}</p>
                            <p><strong>Civil Status:</strong> {{ $additional_info->civil_status }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->address }}</p>
                            <p><strong>Year of No Income:</strong> {{ $additional_info->year_of_no_income }}</p>
                            <p><strong>Certificate of Indigency:</strong> <a href="{{ asset($additional_info->certificate_of_indigency) }}" target="_blank">Certificate of Indigency</a></p>
                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>

                        @elseif($documentRequest->document_type == 'Affidavit of No fix income')

                        <p><strong>Name:</strong> {{ $additional_info->name }}</p>
                            <p><strong>Civil Status:</strong> {{ $additional_info->civil_status }}</p>
                            <p><strong>Address:</strong> {{ $additional_info->address }}</p>
                            <p><strong>Year of No Income:</strong> {{ $additional_info->year_of_no_income }}</p>
                            <p><strong>Certificate of Residency:</strong> <a href="{{ asset($additional_info->certificate_of_residency) }}" target="_blank">Certificate of Residency</a></p>
                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>

                        @elseif($documentRequest->document_type == 'Extra Judicial')

                            <p><strong>Title of Property</strong> <a href="{{ asset($additional_info->title_of_property) }}" target="_blank">Title of Property</a></p>
                            <p><strong>Title Holder:</strong> {{ $additional_info->title_holder }}</p>
                            @if($additional_info->surviving_spouse == null && $additional_info->spouse_valid_id_front == null && $additional_info->spouse_valid_id_back == null)
                                @foreach($heirs_info as $heir)
                                    <br>
                                    <p><strong>Heir's Name:</strong> {{ $heir->surviving_heir }}</p>
                                    <p><strong>Heir's Spouse:</strong> {{ $heir->spouse_of_heir }}</p>
                               @endforeach
                            @else
                                <p><strong>Surviving Spouse:</strong> {{ $additional_info->surviving_spouse }}</p>
                                <p><strong>Spouse's Valid ID (Front):</strong> <a href="{{ asset($additional_info->spouse_valid_id_front) }}" target="_blank">Spouse's Valid ID (Front)</a></p>
                                <p><strong>Spouse's Valid ID (Back):</strong> <a href="{{ asset($additional_info->spouse_valid_id_back) }}" target="_blank">Spouse's Valid ID (Back)</a></p>
                            @endif

                        @elseif($documentRequest->document_type == 'Deed of Sale')

                            <p><strong>Vendor's Name:</strong> {{ $additional_info->name_of_vendor }}</p>
                            <p><strong>Vendor's Civil Status:</strong> {{ $additional_info->vendor_civil_status }}</p>
                            <p><strong>Property Document:</strong> <a href="{{ asset($additional_info->property_document) }}" target="_blank">Property Document</a></p>
                            <p><strong>Property Price:</strong> {{ $additional_info->property_price }}</p>
                            <p><strong>Vendor's Valid ID (Front):</strong> <a href="{{ asset($additional_info->vendor_valid_id_front) }}" target="_blank">Vendor's Valid ID (Front)</a></p>
                            <p><strong>Vendor's Valid ID (Back):</strong> <a href="{{ asset($additional_info->vendor_valid_id_back) }}" target="_blank">Vendor's Valid ID (Back)</a></p>
                            <br>
                            <p><strong>Vendee's Name:</strong> {{ $additional_info->name_of_vendee }}</p>
                            <p><strong>Vendee's Valid ID (Front):</strong> <a href="{{ asset($additional_info->vendee_valid_id_front) }}" target="_blank">Vendee's Valid ID (Front)</a></p>
                            <p><strong>Vendee's Valid ID (Back):</strong> <a href="{{ asset($additional_info->vendee_valid_id_back) }}" target="_blank">Vendee's Valid ID (Back)</a></p>
                            <br>
                            <p><strong>Witness's Name:</strong> {{ $additional_info->name_of_witness }}</p>
                            <p><strong>Witness's Valid ID (Front):</strong> <a href="{{ asset($additional_info->witness_valid_id_front) }}" target="_blank">Witness's Valid ID (Front)</a></p>
                            <p><strong>Witness's Valid ID (Back):</strong> <a href="{{ asset($additional_info->witness_valid_id_back) }}" target="_blank">Witness's Valid ID (Back)</a></p>

                        @elseif($documentRequest->document_type == 'Deed of Donation')

                            <p><strong>Donor's Name:</strong> {{ $additional_info->donor_name }}</p>
                            <p><strong>Donor's Civil Status:</strong> {{ $additional_info->donor_civil_status }}</p>
                            <p><strong>Donor's Address:</strong> {{ $additional_info->donor_address }}</p>
                            <p><strong>Donor's Valid ID (Front):</strong> <a href="{{ asset($additional_info->donor_valid_id_front) }}" target="_blank">Donor's Valid ID (Front)</a></p>
                            <p><strong>Donor's Valid ID (Back):</strong> <a href="{{ asset($additional_info->donor_valid_id_back) }}" target="_blank">Donor's Valid ID (Back)</a></p>
                            <br>
                            <p><strong>Donee's Name:</strong> {{ $additional_info->donee_name }}</p>
                            <p><strong>Donee's Civil Status:</strong> {{ $additional_info->donee_civil_status }}</p>
                            <p><strong>Donee's Address:</strong> {{ $additional_info->donee_address }}</p>
                            <p><strong>Donee's Valid ID (Front):</strong> <a href="{{ asset($additional_info->donee_valid_id_front) }}" target="_blank">Donee's Valid ID (Front)</a></p>
                            <p><strong>Donee's Valid ID (Back):</strong> <a href="{{ asset($additional_info->donee_valid_id_back) }}" target="_blank">Donee's Valid ID (Back)</a></p>
                            <br>
                            <p><strong>Property Description:</strong> {{ $additional_info->property_description }}</p>

                        @elseif($documentRequest->document_type == 'Other Document')

                            <p><strong>Valid ID (Front):</strong> <a href="{{ asset($additional_info->valid_id_front) }}" target="_blank">Valid ID (Front)</a></p>
                            <p><strong>Valid ID (Back):</strong> <a href="{{ asset($additional_info->valid_id_back) }}" target="_blank">Valid ID (Back)</a></p>

                        @else
                        
                        @endif
                    </div>
                    
                    <hr>
                    <div>
                        <div class="message-wrapper">
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