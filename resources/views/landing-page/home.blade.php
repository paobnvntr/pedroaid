@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Home')

@section('contents')
	<section id="home" class="hero d-flex align-items-center">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 d-flex flex-column justify-content-center">
					<h1 data-aos="fade-up">Welcome to PedroAID</h1>
					<h2 data-aos="fade-up" data-aos-delay="400">Your legal assistance portal</h2>
					<div data-aos="fade-up" data-aos-delay="600">
						<div class="text-center text-lg-start">
							<a href="#services"
								class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
								<span>Get Started</span>
								<i class="bi bi-arrow-right"></i>
							</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="row align-items-center">
						<div class="col-lg-6 hero-img" data-aos="zoom-in" data-aos-delay="800">
							<img src="../../images/old-san-pedro-logo-home.png" class="img-fluid" alt="">
						</div>
						<div class="col-lg-6 hero-img" data-aos="zoom-in" data-aos-delay="1000">
							<img src="../../images/new-san-pedro-logo-home.png" class="img-fluid" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ======= About Section ======= -->
	<section id="about-ordinances" class="about">
		<div class="container" data-aos="fade-up">
			<div class="row gx-0">
				<div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
					<div class="content">
						<h3>San Pedro City Ordinances</h3>
						<h2>Dive into the rules and regulations that shape our vibrant community, ensuring a safe, sustainable, and prosperous environment for all residents and visitors</h2>
						<p>
							Stay informed, stay empowered! <br>Click below to access the San Pedro City Ordinances.
						</p>
						<div class="text-center text-lg-start">
							<a href="{{ route('displayCommittee') }}"
								class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
								<span>Read More</span>
								<i class="bi bi-arrow-right"></i>
							</a>
						</div>
					</div>
				</div>

				<div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
					<img src="../../images/san-pedro-arc.jpg" class="img-fluid rounded" alt="">
				</div>
			</div>
		</div>
	</section><!-- End About Section -->

	<!-- ======= Services Section ======= -->
	<section id="services" class="services pt-4">
		<div class="container" data-aos="fade-up">
			<header class="section-header">
				<p>San Pedro City - AID</p>
				<p class="pb-4">Appointment, Inquiry, and Document Request</p>
				<h2 class="services-h2">Appointment, Inquiry and Document System or AID, is an online help center
					portal designed for the legal needs of citizens of San Pedro City, Laguna</h2>

			</header>

			<div class="row gy-4">
				
				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
					<a href="{{ route('appointmentForm') }}">
						<div class="service-box green">
							<i class="ri-calendar-todo-fill icon"></i>
							<h3>Appointment</h3>
							<p>Click here to book an appointment for legal consultation</p>
							<div class="read-more"><span>Book Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>

				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
					<a href="{{ route('inquiryForm') }}">
						<div class="service-box green">
							<i class="ri-questionnaire-fill icon"></i>
							<h3>Inquiry</h3>
							<p>Click here to inquire for any legal concerns</p>
							<div class="read-more"><span>Inquire Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>

				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
					<a href="{{ route('documentRequestForm') }}">
						<div class="service-box green">
							<i class="ri-file-text-fill icon"></i>
							<h3>Document Request</h3>
							<p>Click here to file a request of legal document or certification</p>
							<div class="read-more"><span>Request Now</span> <i class="bi bi-arrow-right"></i></div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</section><!-- End Services Section -->

	<!-- ======= AID Tracker Section ======= -->
	<div class="aid-tracker" id="aid-tracker" data-aos="fade-up">
      	<div class="container">
        	<div class="row justify-content-center">
          		<div class="col-lg-12 text-center">           
					<h4>AID Tracker</h4>					
					<p>A convenient way to track the progress and status of your appointment, inquiry or document request.</p>
				</div>

				<div class="col-lg-3">
					<div class="text-center text-lg-start">
						<a href="{{ route('tracker')  }}"
							class="btn-track-now d-flex align-items-center justify-content-center align-self-center">
							<span>Track Now</span>
							<i class="bi bi-arrow-right"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div><!-- End AID Tracker Section -->

	<!-- ======= Feedback Section ======= -->
    <section id="reviews" class="testimonials">
      	<div class="container" data-aos="fade-up">
			<header class="section-header">
				<h2>Client Feedback</h2>
				<p>What they are saying about us</p>
			</header>

			<div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="200">
				<div class="swiper-wrapper">

					@if($feedback->count() > 0)
						@foreach($feedback as $fb)
							<div class="swiper-slide">
								<div class="testimonial-item">
									<h5 class="mb-4 mt-3">
										@if($fb->rating == 'Poor')
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
										@elseif($fb->rating == 'Fair')
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
										@elseif($fb->rating == 'Good')
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill"></i>
											<i class="bi bi-star-fill"></i>
										@elseif($fb->rating == 'Very Good')
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill"></i>
										@else
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
											<i class="bi bi-star-fill text-warning"></i>
										@endif
									</h5>

									<p>
										"{{ $fb->comment }}"
									</p>

									<div class="profile mt-auto">
										@if($fb->transaction_type === 'Appointment')
											@php
												$appointment = \App\Models\Appointment::where('appointment_id', $fb->transaction_id)->get()->first();
												$name = $appointment ? $appointment->name : 'Unknown';
											@endphp
										@elseif($fb->transaction_type === 'Document Request')
											@php
												$documentRequest = \App\Models\DocumentRequest::where('documentRequest_id', $fb->transaction_id)->get()->first();
												$name = $documentRequest ? $documentRequest->name : 'Unknown';
											@endphp
										@else
											@php
												$name = 'Unknown';
											@endphp
										@endif
									<h3>{{ $name }}</h3>
									<h4>{{ $fb->transaction_type }}</h4>
									</div>
								</div>
							</div><!-- End testimonial item -->
						@endforeach
					@else
						<div class="swiper-slide">
							<div class="testimonial-item">
								<h5 class="mb-3">
									Rating: 
								</h5>

								<p>
									No feedbacks yet.
								</p>

								<div class="profile mt-auto">
									<h3>Unknown</h3>
									<h4>Unknown</h4>
								</div>
							</div>
						</div>
					@endif

				</div>

				<div class="swiper-pagination"></div>
			</div>
      	</div>
    </section><!-- End Testimonials Section -->

	<!-- ======= F.A.Q Section ======= -->
	<section id="faq" class="faq">
		<div class="container" data-aos="fade-up">
			<header class="section-header">
				<h2>F.A.Q</h2>
				<p>Frequently Asked Questions</p>
			</header>

			<div class="accordion accordion-flush row" id="faqlist1">
				<div class="col-lg-6">
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq-content-1">
								What is PedroAID?
							</button>
						</h2>
						<div id="faq-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
								PedroAID stands for Pedro Appointment, Inquiry, and Document Request System. 
								It is an online help center portal specifically designed to address the legal needs of the citizens of San Pedro City, Laguna.
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq-content-2">
								Is there a fee for using PedroAID services?
							</button>
						</h2>
						<div id="faq-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
							PedroAID is committed to providing free legal office services to the residents of San Pedro City, Laguna. 
							All services offered through the portal are completely free of charge. 
							We believe in ensuring access to legal assistance without financial barriers.
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq-content-3">
								What documents are required for certain legal documents?
							</button>
						</h2>
						<div id="faq-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
								Di ko pa alam ilalagay
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq2-content-1">
								Can I check the status of my appointment or request?
							</button>
						</h2>
						<div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
								Yes, you can easily track the status of your appointment or inquiry through the AID Tracker.
								Provide the Tracking ID and the email address associated with your appointment or request, as found in the email confirmation sent to you. 
								The AID Tracker will provide real-time updates and notifications regarding the status of your requests, ensuring you stay informed throughout the process.
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq2-content-2">
								Is PedroAID available 24/7?
							</button>
						</h2>
						<div id="faq2-content-2" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
							While you can access the portal 24/7 to browse information, schedule appointments, and submit request & inquiries, please note that customer support hours may vary. 
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
								data-bs-target="#faq2-content-3">
								How can I get in touch with PedroAID for additional assistance?
							</button>
						</h2>
						<div id="faq2-content-3" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
							<div class="accordion-body">
							Customer support is typically available during the working hours of the legal office. 
							However, to provide assistance during non-office hours, we have a helpful chatbot that you can utilize.
							We aim to ensure that you have access to assistance even during times when the legal office is not actively staffed.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section><!-- End F.A.Q Section -->
@endsection