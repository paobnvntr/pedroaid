@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Privacy Policy')

@section('contents')
	<section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up">Privacy Policy</h1>
            </div>
		</div>
	</section>

    <section class="ordinance p-5 appointmentForm">
		<div class="container" data-aos="fade-up">

            <header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left"></p>
                    <a href="{{ route('home') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
            </header>

            <div class="p-5 card shadow" id="clientDetailsForm">
                <p class="indent">At PedroAID, we are committed to safeguarding your privacy and ensuring the secure handling of your personal information. This Privacy Policy outlines our practices regarding the collection, use, and disclosure of personal information through our legal office portal.</p>
                <p><strong> Collection and Use of Personal Information</strong></p>
                <p class="indent">We collect personal information from users of our portal to provide legal services and enhance user experience. This may include your name, civil status, address, contact number, email address, as well as images and files relevant to your legal matters. We use this information solely for the purposes stated in our Privacy Policy and with your consent.</p>
                <p><strong>Confidentiality and Security</strong></p>
                <p class="indent">PedroAID adheres to strict confidentiality standards and employs industry-standard security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. We regularly review and update our security protocols to ensure compliance with the Data Privacy Act of 2012 (RA 10173) and other relevant regulations.</p>
                <p><strong>Limited Disclosure</strong></p>
                <p class="indent">We do not disclose your personal information to third parties without your explicit consent, except as required by law or as outlined in this Privacy Policy. Trusted service providers may access your information solely for the purpose of assisting us in providing our services, and they are bound by confidentiality obligations.</p>
                <p><strong>Complaints and Other Concerns</strong></p>
                <p class="indent">If you have any questions, concerns, or requests regarding this Privacy Policy or our privacy practices, you may call us at <strong>8082020305</strong> via phone, <strong>09205001415</strong> via mobile, or <strong>santosritch@gmail.com</strong> via email.</p>
                <p><strong>Updates on the Privacy Policy</strong></p>
                <p class="indent">We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. Any updates will be posted on our portal, and we encourage you to review this Policy periodically.</p>

            </div>
        </div>
    </section>
@endsection