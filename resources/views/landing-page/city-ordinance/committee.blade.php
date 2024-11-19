@extends('landing-page.layouts.app')

@section('title', 'PedroAID - Committee')

@section('contents')
<section class="city-ordinance d-flex align-items-center">
	<div class="container">
		<div class="d-flex flex-column justify-content-center align-items-center">
			<h1 data-aos="fade-up" data-aos-delay="200">San Pedro City</h1>
			<h1 data-aos="fade-up" data-aos-delay="300">19th Legislative Council Committees</h1>
		</div>
	</div>
</section>

<section class="ordinance pt-4">
	<div class="container" data-aos="fade-up">

		<header class="section-header">
			<div class="text-center text-lg-start d-flex align-items-center justify-content-between">
				<p class="align-items-left">Committee List</p>
				<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
					<div class="input-group">
						<input type="text" id="searchInput" class="form-control bg-light border-0 small"
							placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-primary btn-search" type="button">
								<i class="bi bi-search"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</header>

		<div class="row gy-4" id="committeeRow">
			@if($committee->count() > 0)
				@foreach($committee as $com)
					<div class="col-lg-4 col-md-6">
						<div class="ordinance-box green">
							<h3>{{ $com->name }}</h3>
							<hr>

							<p class="mb-0 officialName">Hon. {{ $com->chairman }}</p>
							<p><strong>Chairman</strong></p>

							<p class="mb-0 officialName">Hon. {{ $com->vice_chairman }}</p>
							<p class="mb-4"><strong>Vice-Chairman</strong></p>

							@if($com->member_1 != null)
								<p><strong>Member(s):</strong></p>
								<p class="officialName">Hon. {{ $com->member_1 }}</p>
							@endif

							@if($com->member_2 != null)
								<p class="officialName">Hon. {{ $com->member_2 }}</p>
							@endif

							@if($com->member_3 != null)
								<p class="officialName">Hon. {{ $com->member_3 }}</p>
							@endif
							<div class="text-center text-lg-start d-flex align-items-center justify-content-center">
								<a href="{{ route('displayYear', $com->name) }}"
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
					<p>No Council Committee Existing!</p>
				</header>
			@endif
		</div>
	</div>
</section>

<section class="ordinance pt-4 d-none" id="ordinanceSection">
	<div class="container" data-aos="fade-up">

		<header class="section-header">
			<div class="text-center text-lg-start d-flex align-items-center justify-content-between">
				<p class="align-items-left">Ordinance List</p>
			</div>
		</header>

		<div class="row gy-4" id="ordinanceRow">

		</div>
	</div>
</section>

<script>
	const searchInput = document.getElementById('searchInput');
	const committeeRow = document.getElementById('committeeRow');

	function handleCommitteeSearch() {
		const searchQuery = searchInput.value.toLowerCase();
		const boxes = committeeRow.querySelectorAll('.col-lg-4');

		boxes.forEach(box => {
			const committeeNameElement = box.querySelector('h3');
			const officialNameElements = box.querySelectorAll('.officialName');
			if (!committeeNameElement) return;

			const committeeName = committeeNameElement.textContent.toLowerCase();
			let isMatch = committeeName.includes(searchQuery);
			if (!officialNameElements.length) return;

			officialNameElements.forEach(officialNameElement => {
				const officialName = officialNameElement.textContent.toLowerCase();
				if (officialName.includes(searchQuery)) {
					isMatch = true;
				}
			});

			box.classList.toggle('d-none', !isMatch);
		});
	}

	function handleOrdinanceSearch() {
		const searchQuery = searchInput.value.trim();

		if (searchQuery === '') {
			document.getElementById('ordinanceSection').classList.add('d-none');
			return;
		}

		document.getElementById('ordinanceSection').classList.remove('d-none');

		fetch(`/search-ordinances?query=${searchQuery}`)
			.then(response => response.json())
			.then(data => {
				const ordinanceContainer = document.getElementById('ordinanceRow');
				ordinanceContainer.innerHTML = '';

				data.forEach(ord => {
					const ordinanceBox = document.createElement('div');
					ordinanceBox.classList.add('col-lg-6', 'col-md-6');
					ordinanceBox.innerHTML = `
						<div class="ordinance-box green">
							<div class="d-flex align-items-center justify-content-between">
								<h3 class="ordinanceNumber">Ordinance No. <br> <span>${ord.ordinance_number}</span></h3>
								<p class="dateApproved">Date Approved: <br> <span>${ord.date_approved}</span></p>
							</div>
							<hr>

							<p class="description">${ord.description}</p>

							<div class="row">
								<div class="col-md-6">
									<a href="/${ord.ordinance_file}" target="_blank" id="view_${ord.id}"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center"
										onclick="incrementViewCount('${ord.id}')">
										<span>Read More</span>
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>

								<div class="col-md-6">
									<a href="/${ord.ordinance_file}" download="${ord.ordinance_number}.pdf"
										class="btn-view scrollto d-inline-flex align-items-center justify-content-center align-self-center"
										onclick="incrementDownloadCount('${ord.id}')">
										<span>Download</span>
										<i class="bi bi-download"></i>
									</a>
								</div>
							</div>
						
							<hr>

							<div class="d-flex align-items-center justify-content-end">
								<span id="view_count_${ord.id}" class="dateApproved">Views: ${ord.view_count}</span>
								<span id="download_count_${ord.id}" class="dateApproved ml-4">Downloads: ${ord.download_count}</span>
							</div>
						</div>
					`;
					ordinanceContainer.appendChild(ordinanceBox);
				});
			})
			.catch(error => console.error('Error:', error));
	}

	searchInput.addEventListener('input', handleCommitteeSearch);
	searchInput.addEventListener('input', handleOrdinanceSearch);

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