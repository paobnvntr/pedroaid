@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Terms of Service')

@section('contents')
	<section class="city-ordinance d-flex align-items-center">
		<div class="container">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h1 data-aos="fade-up">Terms of Service</h1>
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
                <p>Welcome to PedroAID!</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque facere tempora esse, nostrum et fugit eius debitis obcaecati deserunt tenetur dignissimos facilis maiores iure hic mollitia sequi quas, in laboriosam.</p>
                <p class="indent">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Culpa modi id quaerat nisi ab illo eius asperiores, dolorum quis exercitationem illum, nemo, molestiae quae! Est, rem molestiae. Doloremque, nihil repudiandae!</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam cupiditate, nobis laudantium itaque magnam tempora eaque sed nulla repellendus inventore, laboriosam dolorem, perspiciatis dignissimos facilis numquam dolor reprehenderit nam laborum.</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime, exercitationem. Nulla, nemo vero? Excepturi earum saepe reprehenderit minima magni, cupiditate ducimus eum odio. Doloribus qui aliquam provident quos perspiciatis facere.</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur, adipisicing elit. At facere minus maiores. Ut soluta amet aspernatur nobis accusamus veniam perspiciatis earum at eveniet consequuntur? Veritatis eveniet quasi molestias soluta quia.</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio commodi ab exercitationem fugit nam officia velit rem quisquam, asperiores laborum magnam nisi! Sit velit dignissimos in quae nemo similique quam!</p>
                <p class="indent">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Praesentium sint, distinctio fugit eveniet necessitatibus itaque eligendi assumenda, aperiam eum dignissimos odit dolore, quibusdam consequatur adipisci quasi nisi hic repellendus cumque.</p>
                <p class="indent">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ducimus deleniti impedit velit perspiciatis, ipsa molestias tempore cupiditate rerum distinctio voluptate commodi iure labore. Quia, repudiandae temporibus. Rerum deleniti itaque reprehenderit!</p>
            </div>
        </div>
    </section>
@endsection