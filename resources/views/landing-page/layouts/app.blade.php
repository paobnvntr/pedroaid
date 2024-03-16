<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link rel="icon" href="{{ asset('images/PedroAID-Logo.png') }}" type="image/png">

	<title>@yield('title')</title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

	<!-- Vendor CSS Files -->
	<link href="../../../plugins/landing-page/aos/aos.css" rel="stylesheet">
	<link href="../../../plugins/landing-page/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../../plugins/landing-page/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="../../../plugins/landing-page/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="../../../plugins/landing-page/remixicon/remixicon.css" rel="stylesheet">
	<link href="../../../plugins/landing-page/swiper/swiper-bundle.min.css" rel="stylesheet">
	<link href="../../../plugins/jquery-ui/jquery-ui.css" rel="stylesheet">

	<!-- Template Main CSS File -->
	<link rel="stylesheet" href="../../../css/landing-page.css">

	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="../../../plugins/jquery-ui/jquery-ui.js"></script>
	@yield('scripts')
</head>

<body>
	@include('landing-page.layouts.navbar')

	@yield('contents')

	$<link rel="stylesheet" href="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/themes/df-messenger-default.css">
	<script src="https://www.gstatic.com/dialogflow-console/fast/df-messenger/prod/v1/df-messenger.js"></script>
	<df-messenger
		location="asia-southeast1"
		project-id="pedroaid"
		agent-id="20bb3eaf-7d13-44ce-a599-010c62285a82"
		language-code="en"
		max-query-length="-1">
		<df-messenger-chat-bubble
			chat-title="PeDroid"
			chat-width="400"
			chat-icon="{{ asset('images/PeDroid.svg') }}"
			placeholder-text="Type 'Hi' to start conversation."
			bot-actor-image="{{ asset('images/PeDroid-Logo.png') }}">
		</df-messenger-chat-bubble>
		
	</df-messenger>
	<style>
		df-messenger {
			z-index: 999;
			position: fixed;
			bottom: 15px;
			right: 15px;
			--df-messenger-primary-color : #35784F !important;
			--df-messenger-chat-window-height : 480px !important;
			--df-messenger-chat-border-radius: 20px !important;
			--df-messenger-chat-bubble-close-icon-size: 30px !important;
			--df-messenger-titlebar-title-font-size: 25px !important;
			--df-messenger-titlebar-title-font-weight: 700 !important;
			--df-messenger-titlebar-title-font-family: 'Nunito', sans-serif !important;
			--df-messenger-titlebar-font-color: var(--df-messenger-primary-color) !important;
		}
	</style>

	@include('landing-page.layouts.footer')

	<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
		<i class="bi bi-arrow-up-short"></i>
	</a>

	<script src="../../../plugins/landing-page/purecounter/purecounter_vanilla.js"></script>
	<script src="../../../plugins/landing-page/aos/aos.js"></script>
	<script src="../../../plugins/landing-page/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="../../../plugins/landing-page/glightbox/js/glightbox.min.js"></script>
	<script src="../../../plugins/landing-page/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="../../../plugins/landing-page/swiper/swiper-bundle.min.js"></script>

	<script src="../../../js/landing-page.js"></script>


	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
	<script src="../../../plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="../../../plugins/datatables/dataTables.bootstrap4.min.js"></script>
</body>