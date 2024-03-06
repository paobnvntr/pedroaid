@extends('landing-page.layouts.app')

@section('title', 'PedroAID - City Ordinances')

@section('contents')
	<section class="city-ordinance d-flex align-items-center">
		<div class="container">
			<!-- <div class="row"> -->
				<div class="d-flex flex-column justify-content-center align-items-center">
					<h1 data-aos="fade-up">San Pedro City Ordinances</h1>
				</div>
			<!-- </div> -->
		</div>
	</section>

	<section class="ordinance pt-4">
		<div class="container" data-aos="fade-up">

			<header class="section-header">
                <div class="text-center text-lg-start d-flex align-items-center justify-content-between">
                    <p class="align-items-left committeeName">{{ $committee->name }}</p>
                    <a href="{{ route('displayCommittee') }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

			<div class="row gy-4">
                @if($ordinanceYear->count() > 0)
                    @foreach($ordinanceYear as $ordY)
						<div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
							<div class="ordinance-box green">
								<h3>{{ $ordY->year }}</h3>
								<hr>

								<div class="text-center text-lg-start d-flex align-items-center justify-content-center">
									<a href="{{ route('displayOrdinance', [$committee->name, $ordY->year]) }}"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center">
										<span>View</span>
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
					@endforeach
                @else
					<header class="section-header">
						<p>No Ordinance Existing!</p>
					</header>
				@endif
			</div>
		</div>
	</section>

@endsection