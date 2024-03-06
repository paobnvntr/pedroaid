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
                    <a href="{{ route('displayYear', $committee->name) }}"
                        class="btn-ordinance-back scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                        <i class="bi bi-arrow-left"></i>
                        <span>Go Back</span>
                    </a>
                </div>
			</header>

			<div class="row gy-4">
                @if($ordinance->count() > 0)
                    @foreach($ordinance as $ord)
						<div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
							<div class="ordinance-box green">
								<div class="d-flex align-items-center justify-content-between">
                                    <h3 class="ordinanceNumber">Ordinance No. <br> <span>{{ $ord->ordinance_number }}</span></h3>
                                    <p class="dateApproved">Date Approved: <br> <span>{{ date('F j, Y', strtotime($ord->date_approved)) }}</span></p>
                                </div>
								<hr>

                                <p class="description">{{ $ord->description }}</p>
								<div class="text-center text-lg-start d-flex align-items-center justify-content-center">
									<a href="{{ asset($ord->ordinance_file)  }}" target="_blank"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center">
										<span>Read More</span>
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