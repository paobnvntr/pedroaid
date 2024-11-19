<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="icon" href="{{ asset('images/PedroAID-Logo.png') }}" type="image/png">

	<title>PedroAID</title>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"
		type="text/css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

	<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap4.css" />
	<link rel="stylesheet" href="../../plugins/datatables/dataTables.dataTables.css">
	<link rel="stylesheet" href="../../plugins/datatables/buttons.dataTables.css">
	<link rel="stylesheet" href="../../plugins/datatables/select.dataTables.css">

	<link href="../../plugins/jquery-ui/jquery-ui.css" rel="stylesheet">
	<link rel="stylesheet" href="../../css/pedro-aid.css">

	<script src="../../plugins/jquery/jquery.min.js"></script>
	<script src="../../plugins/jquery-ui/jquery-ui.js"></script>
	@yield('scripts')
</head>

<body id="page-top">
	<div id="wrapper">

		@include('layouts.sidebar')

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				@include('layouts.navbar')

				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-0">
						<h1 class="h3 mb-0 text-gray-800 font-weight-bold">@yield('title')</h1>
					</div>

					@yield('contents')
				</div>
			</div>
		</div>
	</div>

	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-arrow-up"></i>
	</a>

	<script>
		$(document).ready(function () {
			$('.message-item').click(function (event) {
				event.preventDefault();

				var url = $(this).attr('href');
				var parts = url.split('/');
				var transactionId = parts[parts.length - 1];

				$.ajax({
					url: '/mark-message-as-read/' + transactionId,
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (response) {
						window.location.href = url;
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
			});

			$('.notification-item').click(function (event) {
				event.preventDefault();

				var url = $(this).attr('href');
				var parts = url.split('/');
				var transactionId = parts[parts.length - 1];

				$.ajax({
					url: '/mark-notification-as-read/' + transactionId,
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (response) {
						window.location.href = url;
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
			});

			$('.mark-all-messages-as-read').click(function (event) {
				event.preventDefault();

				$.ajax({
					url: '{{ route('markAllMessagesAsRead') }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (response) {
						window.location.reload();
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
			});

			$('.mark-all-notifications-as-read').click(function (event) {
				event.preventDefault();

				$.ajax({
					url: '{{ route('markAllNotificationsAsRead') }}',
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function (response) {
						window.location.reload();
					},
					error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
			});
		});
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
	<script src="../../js/pedro-aid.js"></script>
	<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables/dataTables.bootstrap4.min.js"></script>
	<script src="../../plugins/datatables/dataTables.buttons.min.js"></script>
	<script src="../../plugins/datatables/buttons.dataTables.min.js"></script>
	<script src="../../plugins/datatables/jszip.min.js"></script>
	<script src="../../plugins/datatables/pdfmake.min.js"></script>
	<script src="../../plugins/datatables/vfs_fonts.min.js"></script>
	<script src="../../plugins/datatables/buttons.html5.min.js"></script>
	<script src="../../plugins/datatables/buttons.print.min.js"></script>
	<script src="../../plugins/datatables/dataTables.select.min.js"></script>
	<script src="../../plugins/datatables/select.dataTables.min.js"></script>
	<script src="../../js/datatables-demo.js"></script>
</body>

</html>