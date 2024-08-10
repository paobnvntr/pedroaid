<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
	<div class="footer-top">
		<div class="container">
			<div class="row gy-4">
				<div class="col-lg-5 col-md-12 footer-info">
					<a href="{{ route('home') }}#home" class="logo d-flex align-items-center">
						<span>PedroAID</span>
					</a>
					<p>PedroAID (Appointment, Inquiry, and Document Request) is an online help center
					portal designed for the legal needs of citizens of San Pedro City, Laguna</p>
					<div class="social-links mt-3">
						<a href="https://www.facebook.com/AMFLAC" class="facebook" target="_blank"><i class="bi bi-facebook"></i> Atty. Marky Oliveros</a>
						<a href="https://www.facebook.com/groups/405644160278321/" class="facebook" target="_blank"><i class="bi bi-facebook"></i> AMFLAC Facebook Group</a>
					</div>
				</div>

				<div class="col-lg-2 col-6 footer-links">
					<h4>Useful Links</h4>
					<ul>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#home">Home</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#about-us">About Us</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#sangguniang-panlungsod">Sangguniang Panlungsod</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('displayCommittee') }}">City Ordinances</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#faq">FAQs</a></li>
						<li><i class="bi bi-chevron-right"></i> <span data-toggle="modal" data-target="#privacyModal" id="privacyPolicySpan">Privacy Policy</span></li>
					</ul>
				</div>

				@include('landing-page/privacyPolicy')

				<div class="col-lg-2 col-6 footer-links">
					<h4>Our Services</h4>
					<ul>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('appointmentForm') }}">Appointment</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('inquiryForm') }}">Inquiry</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('documentRequestForm') }}">Document Request</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('tracker') }}">AID Tracker</a></li>
					</ul>
				</div>

				<div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
					<h4>Contact Us</h4>
					<p>
						3F New City Hall Bldg.,<br>
						Brgy. Poblacion, <br>
						City of San Pedro, 4023<br>
						Laguna, Philippines <br>
						<strong>Google Maps: </strong> <a href="https://maps.app.goo.gl/pyGqSpdLeSFNkAQi9" target="_blank">Click here</a>
						<br><br>
						<strong>Phone:</strong> 8082020305<br>
						<strong>Mobile:</strong> 09205001415<br>
						<strong>Email:</strong> <a href="https://mail.google.com/mail/?view=cm&fs=1&to=contact@pedroaid.com" target="_blank">contact@pedroaid.com</a><br>
					</p>

				</div>

			</div>
		</div>
	</div>
</footer><!-- End Footer -->