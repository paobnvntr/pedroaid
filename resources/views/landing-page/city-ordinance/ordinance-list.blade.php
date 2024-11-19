@extends('landing-page.layouts.app')

@section('title', 'PedroAID - City Ordinances')

@section('contents')
<section class="city-ordinance d-flex align-items-center">
	<div class="container">
		<div class="d-flex flex-column justify-content-center align-items-center">
			<h1 data-aos="fade-up">San Pedro City Ordinances</h1>
		</div>
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
								<p class="dateApproved">Date Approved: <br>
									<span>{{ date('F j, Y', strtotime($ord->date_approved)) }}</span></p>
							</div>
							<hr>

							<p class="description">{{ $ord->description }}</p>

							<div class="row">
								<div class="col-md-6">
									<a href="{{ asset($ord->ordinance_file)  }}" target="_blank" id="view_{{ $ord->id }}"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center"
										onclick="incrementViewCount('{{ $ord->id }}')">
										<span>Read More</span>
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>

								<div class="col-md-6">
									<a href="{{ asset($ord->ordinance_file)  }}" download="{{ $ord->ordinance_number }}.pdf"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center"
										onclick="incrementDownloadCount('{{ $ord->id }}')">
										<span>Download</span>
										<i class="bi bi-download"></i>
									</a>
								</div>
							</div>

							<hr>

							<div class="d-flex align-items-center justify-content-end">
								<span id="view_count_{{ $ord->id }}" class="dateApproved">Views: <span
										class="purecounter dateApproved" data-purecounter-start="0"
										data-purecounter-end="{{ $ord->view_count }}"
										data-purecounter-duration="1"></span></span>
								<span id="download_count_{{ $ord->id }}" class="dateApproved ml-4">Downloads: <span
										class="purecounter dateApproved" data-purecounter-start="0"
										data-purecounter-end="{{ $ord->download_count }}"
										data-purecounter-duration="1"></span></span>
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

<script>
	function incrementViewCount(id) {
		$.ajax({
			type: "GET",
			url: "/ordinance/view/" + id,
			success: function (response) {
				console.log("View count incremented successfully.");
				$('#view_count_' + id).text('Views: ' + response.view_count);
			},
			error: function (xhr, status, error) {
				console.error("Error incrementing view count:", error);
			}
		});
	}

	function incrementDownloadCount(id) {
		$.ajax({
			type: "GET",
			url: "/ordinance/download/" + id,
			success: function (response) {
				console.log("Download count incremented successfully.");
				$('#download_count_' + id).text('Downloads: ' + response.download_count);
			},
			error: function (xhr, status, error) {
				console.error("Error incrementing download count:", error);
			}
		});
	}
</script>

@endsection