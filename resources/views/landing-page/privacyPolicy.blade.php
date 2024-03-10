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
                <p class="indent">In accordance with the Data Privacy Act of 2012 or RA 10173, PedroAID ensures the utmost confidentiality, careful usage, and limited disclosure of all personal information collected through our legal office portal.</p>
                <p class="indent">We collect and handle your personal information in accordance with PedroAID's Privacy Policy, unless explicitly stated otherwise. Personal information may encompass details such as your name, civil status, address, contact number, email address, images, files.</p>

                <p><strong>Complaints and Other Concerns</strong></p>
                <p class="indent">If you have any questions, concerns, or requests regarding this Privacy Policy or our privacy practices, you may call us at <strong>8082020305</strong> via phone, <strong>09205001415</strong> via mobile, or <strong>santosritch@gmail.com</strong> via email.</p>
            </div>
        </div>
    </section>
@endsection