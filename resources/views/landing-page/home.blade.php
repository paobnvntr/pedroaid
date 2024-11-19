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


<section id="about-us" class="features">
	<div class="container" data-aos="fade-up">
		<header class="section-header">
			<h2>About Us</h2>
			<p>Learn more about PedroAID</p>
		</header>
		<div class="row feture-tabs">
			<div class="col-lg-6">
				<div class="row align-items-center">
					<div class="col-6 col-lg-6 feture-tabs" data-aos="zoom-in">
						<img src="{{ asset('images/PedroAID-Logo.png') }}" class="img-fluid" alt="">
					</div>
					<div class="col-6 col-lg-6 feture-tabs" data-aos="zoom-in">
						<img src="{{ asset('images/sangguniang-panlungsod-logo.png') }}" class="img-fluid" alt="">
					</div>
				</div>
			</div>

			<div class="col-lg-6" data-aos="fade-up" id="description-about">
				<h3>Free Legal Access to San Pedro City</h3>

				<ul class="nav nav-pills mb-3">
					<li>
						<a class="nav-link active" data-bs-toggle="pill" href="#tab1">Mission</a>
					</li>
					<li>
						<a class="nav-link" data-bs-toggle="pill" href="#tab2">What We Offer</a>
					</li>
					<li>
						<a class="nav-link" data-bs-toggle="pill" href="#tab3">Why Choose PedroAid</a>
					</li>
				</ul>

				<div class="tab-content">

					<div class="tab-pane fade show active" id="tab1">
						<p>Our mission is to empower people and communities in San Pedro City, Laguna by giving them
							easy access to legal representation through a user-friendly platform. In order to improve
							accessibility for everyone, we work hard to expedite the appointment-scheduling,
							inquiry-handling, and legal document request processes.</p>
					</div>

					<div class="tab-pane fade show" id="tab2">
						<p>PedroAID offers a range of services designed to simplify legal processes for residents of San
							Pedro City, Laguna:</p>
						<ol>
							<li><strong>Appointment Management:</strong> Easily schedule appointments with a legal
								expert.</li>
							<li><strong>Inquiry Handling:</strong> Centralized platform for addressing legal inquiries
								promptly.</li>
							<li><strong>Legal Document Requests:</strong> Submit requests for various legal documents
								hassle-free.</li>
						</ol>
					</div>

					<div class="tab-pane fade show" id="tab3">
						<p>Here's why PedroAID stands out as the preferred legal assistance platform in San Pedro City,
							Laguna:</p>
						<ul>
							<li><strong>Tailored for San Pedro City:</strong> Our platform is customized to address the
								unique legal needs of our community.</li>
							<li><strong>Efficiency and Convenience:</strong> Experience hassle-free access to legal
								assistance with just a few clicks.</li>
							<li><strong>Transparency and Accountability:</strong> We handle inquiries and document
								requests with professionalism and confidentiality.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- <div class="sangguniang-panlungsod" id="sangguniang-panlungsod" data-aos="fade-up">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-12 text-center mb-3">
				<h2>Sangguniang Panlungsod</h2>
				<p>Get to know our San Pedro City Councilors</p>
			</div>

			<div class="col-lg-3">
				<div class="text-center text-lg-start">
					<a href="{{ route('sangguniangPanlungsod')  }}" class="btn-track-now d-flex align-items-center justify-content-center align-self-center">
						<span>Explore</span>
						<i class="bi bi-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</div> -->

<!-- <section id="about-ordinances" class="about">
	<div class="container" data-aos="fade-up">
		<div class="row gx-0">
			<div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up">
				<div class="content">
					<h3>San Pedro City Ordinances</h3>
					<h2>Dive into the rules and regulations that shape our vibrant community, ensuring a safe, sustainable, and prosperous environment for all residents and visitors</h2>
					<p>
						Stay informed, stay empowered! <br>Click below to access the San Pedro City Ordinances.
					</p>
					<div class="text-center text-lg-start">
						<a href="{{ route('displayCommittee') }}" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
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
</section> -->

<section id="services" class="services pt-4">
	<div class="container" data-aos="fade-up">
		<header class="section-header">
			<p>San Pedro City - AID</p>
			<p class="pb-4">Appointment, Inquiry, and Document Request</p>
			<h2 class="services-h2">Appointment, Inquiry and Document System or AID, is an online help center
				portal designed for the legal needs of citizens of San Pedro City, Laguna</h2>
		</header>

		<div class="row gy-4">

			<div class="col-lg-4 col-md-6" data-aos="fade-up">
				<a href="{{ route('appointmentForm') }}">
					<div class="service-box green">
						<i class="ri-calendar-todo-fill icon"></i>
						<h3>Appointment</h3>
						<p>Click here to book an appointment for legal consultation</p>
						<div class="read-more"><span>Book Now</span> <i class="bi bi-arrow-right"></i></div>
					</div>
				</a>
			</div>

			<div class="col-lg-4 col-md-6" data-aos="fade-up">
				<a href="{{ route('inquiryForm') }}">
					<div class="service-box green">
						<i class="ri-questionnaire-fill icon"></i>
						<h3>Inquiry</h3>
						<p>Click here to inquire for any legal concerns</p>
						<div class="read-more"><span>Inquire Now</span> <i class="bi bi-arrow-right"></i></div>
					</div>
				</a>
			</div>

			<div class="col-lg-4 col-md-6" data-aos="fade-up">
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
</section>

<div class="aid-tracker" id="aid-tracker" data-aos="fade-up">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-12 text-center">
				<h4>AID Tracker</h4>
				<p>A convenient way to track the progress and status of your appointment, inquiry or document request.
				</p>
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
</div>

<section id="reviews" class="testimonials">
	<div class="container" data-aos="fade-up">
		<header class="section-header">
			<h2>Client Feedback</h2>
			<p>What they are saying about us</p>
		</header>

		<div class="testimonials-slider swiper" data-aos="fade-up">
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
								</div>
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
</section>

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
							Welcome to PedroAID, your trusted companion for all legal matters! PedroAID, which stands
							for Pedro Appointment, Inquiry, and Document Request System, is your go-to online help
							center portal, tailored to cater to the diverse legal needs of individuals far and wide.
							<br><br>
							Picture PedroAID as your virtual legal advisor, ready to assist you at every step of your
							journey. Whether you're inquiring about legal procedures, scheduling appointments for
							consultations, or requesting essential documents, PedroAID is here to streamline the process
							for you.
							Based in the vibrant city of San Pedro, Laguna, PedroAID extends its services to anyone
							seeking reliable legal assistance, irrespective of geographical boundaries. Our mission is
							to empower individuals with easy access to legal support, ensuring that everyone can
							navigate the complexities of the legal landscape with confidence.
							<br><br>
							Join us at PedroAID and experience the convenience of a comprehensive legal assistance
							platform designed with your needs in mind. Let's embark on this legal journey together,
							where solutions are just a click away!
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
							PedroAID stands firm in its dedication to offering complimentary legal office services
							exclusively to the esteemed residents of San Pedro City, Laguna. Here at PedroAID, every
							service provided through our portal comes at absolutely no cost to you.
							<br><br>
							Why? Because we firmly believe in breaking down financial barriers and ensuring that
							everyone, regardless of economic status, has access to essential legal assistance. Your
							rights and well-being matter to us, and we're committed to providing the support you need,
							completely free of charge.
							<br><br>
							So, rest assured, when you turn to PedroAID for your legal inquiries, appointments, and
							document requests, you're not just receiving top-notch assistance – you're accessing it with
							the peace of mind that it won't cost you a single cent.
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
							When it comes to certain legal documents, ensuring you have the necessary paperwork in order
							is crucial. Here's a breakdown of the documents required for various legal processes:
							<br><br>
							<strong>Affidavit of Loss</strong>: A valid ID is required. <br>
							<strong>Affidavit of Guardianship</strong>: A valid ID is required. <br>
							<strong>Affidavit of No Income</strong>: Prepare a Certificate of Indigency along with a
							valid ID. <br>
							<strong>Affidavit of No Fixed Income</strong>: You'll need a Certificate of Residency and a
							valid ID. <br>
							<strong>Extra Judicial</strong>: Ensure you have the title of the property and the valid ID
							of the spouse (if applicable). <br>
							<strong>Deed of Sale</strong>: Gather the property document, vendor's valid ID, vendee's
							valid ID, and witness's valid ID. <br>
							<strong>Deed of Donation</strong>: Make sure to have the donor's valid ID and the donee's
							valid ID. <br>
							<strong>Other Documents</strong>: For any other legal documents, a valid ID is typically
							required.
							<br><br>
							Having these documents ready beforehand can streamline the legal process and ensure a smooth
							transaction.
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
							data-bs-target="#faq2-content-1">
							Can I check the status of my appointment, inquiry, or request?
						</button>
					</h2>
					<div id="faq2-content-1" class="accordion-collapse collapse" data-bs-parent="#faqlist1">
						<div class="accordion-body">
							With our innovative AID Tracker, monitoring the status of your appointment, inquiry, or
							document request has never been easier.
							Simply provide the Tracking ID and the email address associated with your appointment or
							request, as provided in the confirmation email sent to you. Once entered, our AID Tracker
							springs into action, delivering real-time updates and notifications straight to your
							fingertips.
							<br><br>
							Stay in the loop every step of the way as the AID Tracker keeps you informed about the
							progress of your requests, ensuring transparency and peace of mind throughout your PedroAID
							journey.
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
							At PedroAID, convenience is key. That's why our portal is at your service 24/7. Whether
							you're seeking information, scheduling appointments, or submitting requests and inquiries,
							you have unrestricted access whenever it suits you.
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
							At PedroAID, we understand that your legal needs don't always align with traditional office
							hours. While our customer support is available during the working hours of the legal office,
							we recognize the importance of providing assistance even during non-office hours.
							<br><br>
							That's why we've introduced a friendly and helpful chatbot to bridge the gap. Our chatbot is
							here to assist you whenever you need support, even outside regular office hours. Whether
							it's late at night or early in the morning, you can rely on our chatbot to provide guidance
							and answer your queries promptly.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection