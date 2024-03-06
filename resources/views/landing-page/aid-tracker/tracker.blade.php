@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Tracker')

@section('contents')
    <section class="city-ordinance d-flex align-items-center">
		<div class="container">
			<div class="d-flex flex-column justify-content-center align-items-center">
				<h1 data-aos="fade-up" data-aos-delay="200">AID Tracker</h1>
			</div>
		</div>
	</section>

	<section id="services" class="services pt-4">
		<div class="container" data-aos="fade-up">
			<div class="row gy-4">
				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
					<a href="{{ route('appointmentTracker') }}">
						<div class="service-box green">
							<i class="ri-calendar-todo-fill icon"></i>
							<h3>Appointment Tracker</h3>
							<p>Track here your appointment for legal consultation</p>
							<div class="read-more"><span>Track Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>

				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
					<a href="{{ route('inquiryTracker') }}">
						<div class="service-box green">
							<i class="ri-questionnaire-fill icon"></i>
							<h3>Inquiry Tracker</h3>
							<p>Track here your inquiry of legal concerns</p>
							<div class="read-more"><span>Track Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>

				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
					<a href="{{ route('documentRequestTracker') }}">
						<div class="service-box green">
							<i class="ri-file-text-fill icon"></i>
							<h3>Document Request Tracker</h3>
							<p>Track here your requested file of legal document or certification</p>
							<div class="read-more"><span>Track Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</section>
@endsection