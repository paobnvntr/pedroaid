<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
	<div class="footer-top">
		<div class="container">
			<div class="row gy-4">
				<div class="col-lg-5 col-md-12 footer-info">
					<a href="{{ route('home') }}#home" class="logo d-flex align-items-center">
						<span>PedroAID</span>
					</a>
					<p>Pedro Appointment, Inquiry and Document System or AID, is an online help center
					portal designed for the legal needs of citizens of San Pedro City, Laguna</p>
					<div class="social-links mt-3">
						<a href="https://www.facebook.com/AMFLAC" class="facebook"><i class="bi bi-facebook"></i></a>
						<!-- <a href="#" class="instagram"><i class="bi bi-instagram"></i></a> -->
						<!-- <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a> -->
					</div>
				</div>

				<div class="col-lg-2 col-6 footer-links">
					<h4>Useful Links</h4>
					<ul>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#home">Home</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('displayCommittee') }}">City Ordinances</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('home') }}#faq">FAQs</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('termsOfService') }}">Terms of Service</a></li>
						<li><i class="bi bi-chevron-right"></i> <a href="{{ route('privacyPolicy') }}">Privacy Policy</a></li>
					</ul>
				</div>

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
						Laguna, Philippines <br><br>
						<strong>Phone:</strong> +69 912 345 6789<br>
						<strong>Email:</strong> pedroaid@gov.ph<br>
					</p>

				</div>

			</div>
		</div>
	</div>
</footer><!-- End Footer -->