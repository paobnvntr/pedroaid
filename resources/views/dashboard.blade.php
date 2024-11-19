@extends('layouts.app')
  
@section('title', 'Welcome to Dashboard')
  
@section('contents')
	<hr>
	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
		<div class="row mt-3">
			<div class="col-xl-6 col-md-6">
				<div class="card border-left-secondary shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
									Committees</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $committees }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-users fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-6 col-md-6">
				<div class="card border-left-dark shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
									City Ordinances</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ordinances }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-city fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr>
	@endif

	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
		<div class="card shadow mb-4">
			<div
				class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Services Overview</h6>
			</div>

			<div class="card-body">
				<div class="chart-area">
					<canvas id="myAreaChart"></canvas>
				</div>
			</div>
		</div>

		<hr>
	@endif

  	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Appointment')
		<div class="row mt-3">
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-warning shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
									Pending Appointments</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_appointments }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
									Booked Appointments</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $booked_appointments }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-calendar-check fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Cancelled Appointments</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelled_appointments }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-ban fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
									Finished Appointments</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $finished_appointments }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-check fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-8 col-lg-7 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Today's Appointment</h6>
						<h6 class="m-0 font-weight-bold text-primary">Date: {{ now('Asia/Manila')->format('M d, Y') }}</h6>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover" id="dataTableAppointment" width="100%" cellspacing="0">
								<thead>
									@if($todays_appointments->count() > 0)
										<tr>
											<th>No.</th>
											<th>Appointment ID</th>
											<th>Name</th>
											<th>Time</th>
										</tr>
									@else
										<tr>
											<th>Appointments</th>
										</tr>
									@endif
								</thead>
								
								<tbody>
									@if($todays_appointments->count() > 0)
										@foreach($todays_appointments as $appt)
											<tr>
												<td class="align-middle">{{ $loop->iteration }}</td>
												<td class="align-middle">{{ $appt->appointment_id }}</td>
												<td class="align-middle">{{ $appt->name }}</td>
												<td class="align-middle">{{ $appt->appointment_time }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-center text-danger" colspan="4">No Appointment Today!</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-lg-5 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-orange">Appointments Feedback</h6>
					</div>

					<div class="card-body">
						<div class="chart-pie pt-4 pb-2">
							<canvas id="myPieChart"></canvas>
						</div>
						
						<div class="mt-4 text-center small">
							<span class="mr-2">
								<i class="fas fa-circle text-danger"></i> Poor
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-warning"></i> Fair
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-primary"></i> Good
							</span>
							<br>
							<span class="mr-2">
								<i class="fas fa-circle text-info"></i> Very Good
							</span>
							<span class="mr-2">
								<i class="fas fa-circle text-success"></i> Excellent
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr>
	@endif

	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Inquiry')
		<div class="row">
			<div class="col-xl-6 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-warning">Unanswered Inquiries</h6>
					</div>

					<div class="card-body">
						<div class="table-responsive mb-3">
							<table class="table table-hover" id="dataTableOnProcess" width="100%" cellspacing="0">
								<thead>
									@if($unanswered_inquiries->count() > 0)
										<tr>
											<th>No.</th>
											<th>Inquiry ID</th>
											<th>Name</th>
										</tr>
									@else
										<tr>
											<th>Unanswered Inquiries</th>
										</tr>
									@endif
								</thead>
								
								<tbody>
									@if($unanswered_inquiries->count() > 0)
										@foreach($unanswered_inquiries as $inq)
											<tr>
												<td class="align-middle">{{ $loop->iteration }}</td>
												<td class="align-middle">{{ $inq->inquiry_id }}</td>
												<td class="align-middle">{{ $inq->name }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-center text-danger" colspan="3">No Unanswered Inquiries!</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-6 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Answered Inquiries</h6>
					</div>

					<div class="card-body">
						<div class="table-responsive mb-3">
							<table class="table table-hover" id="dataTableOnProcess" width="100%" cellspacing="0">
								<thead>
									@if($answered_inquiries->count() > 0)
										<tr>
											<th>No.</th>
											<th>Inquiry ID</th>
											<th>Name</th>
										</tr>
									@else
										<tr>
											<th>Answered Inquiries</th>
										</tr>
									@endif
								</thead>
								
								<tbody>
									@if($answered_inquiries->count() > 0)
										@foreach($answered_inquiries as $inq)
											<tr>
												<td class="align-middle">{{ $loop->iteration }}</td>
												<td class="align-middle">{{ $inq->inquiry_id }}</td>
												<td class="align-middle">{{ $inq->name }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-center text-danger" colspan="3">No Answered Inquiries!</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr>
	@endif

	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin' || auth()->user()->transaction_level == 'Document Request')
		<div class="card shadow mb-4">
			<div
				class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Document Request Overview</h6>
			</div>

			<div class="card-body">
				<div class="chart-area">
					<canvas id="documentRequestGraph"></canvas>
				</div>
			</div>
		</div>
	
		<div class="row mt-3">
			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-warning shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
									Pending Requests</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pending_requests }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-orange shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-orange text-uppercase mb-1">
									On Hold Requests</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $onhold_requests }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-pause-circle fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
									Claimed Documents</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $claimed_requests }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-check fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Unclaimed Documents</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unclaimed_requests }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-window-close fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-6 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-warning">On Process Requests</h6>
					</div>

					<div class="card-body">
						<div class="table-responsive mb-3">
							<table class="table table-hover" id="dataTableOnProcess" width="100%" cellspacing="0">
								<thead>
									@if($processing_requests->count() > 0)
										<tr>
											<th>No.</th>
											<th>Request ID</th>
											<th>Document</th>
										</tr>
									@else
										<tr>
											<th>Processing Documents</th>
										</tr>
									@endif
								</thead>
								
								<tbody>
									@if($processing_requests->count() > 0)
										@foreach($processing_requests as $req)
											<tr>
												<td class="align-middle">{{ $loop->iteration }}</td>
												<td class="align-middle">{{ $req->documentRequest_id }}</td>
												<td class="align-middle">{{ $req->document_type }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-center text-danger" colspan="3">No Request on Process!</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-6 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">To Claim</h6>
					</div>
					<div class="card-body">
						<div class="table-responsive mb-3">
							<table class="table table-hover" id="dataTableToClaim" width="100%" cellspacing="0">
								<thead>
									@if($toclaim_requests->count() > 0)
										<tr>
											<th>No.</th>
											<th>Request ID</th>
											<th>Document</th>
										</tr>
									@else
										<tr>
											<th>To Claim Requests</th>
										</tr>
									@endif
								</thead>
								
								<tbody>
									@if($toclaim_requests->count() > 0)
										@foreach($toclaim_requests as $req)
											<tr>
												<td class="align-middle">{{ $loop->iteration }}</td>
												<td class="align-middle">{{ $req->documentRequest_id }}</td>
												<td class="align-middle">{{ $req->document_type }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td class="text-center text-danger" colspan="3">No Documents to Claim!</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-orange">Document Request Feedback</h6>
			</div>
			<div class="card-body">
				<h4 class="small font-weight-bold">Poor<span id="poorPercentage" class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div id="poorProgress" class="progress-bar bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Fair<span id="fairPercentage" class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div id="fairProgress" class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Good<span id="goodPercentage" class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div id="goodProgress" class="progress-bar bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Very Good<span id="very goodPercentage" class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div id="very goodProgress" class="progress-bar bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<h4 class="small font-weight-bold">Excellent<span id="excellentPercentage" class="float-right">20%</span></h4>
				<div class="progress mb-4">
					<div id="excellentProgress" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>

		<hr>
	@endif

	@if(auth()->user()->level == 'Super Admin' || auth()->user()->level == 'Admin')
		<div class="row mt-3">
			<div class="col-xl-4 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
									Staff</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staff }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-user-tie fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-md-6 mb-4">
				<div class="card border-left-orange shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-orange text-uppercase mb-1">
									Admin</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $admin }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-user-cog fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-4 col-md-6 mb-4">
				<div class="card border-left-danger shadow h-100 py-2">
					<div class="card-body">
						<div class="row no-gutters align-items-center">
							<div class="col mr-2">
								<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
									Super Admin</div>
								<div class="h5 mb-0 font-weight-bold text-gray-800">{{ $super_admin }}</div>
							</div>
							<div class="col-auto">
								<i class="fas fa-user-lock fa-2x text-gray-300"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-12 mb-4">
				<div class="card shadow appointmentChart">
					<div
						class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary">Latest Logs</h6>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover" id="dataTableLogDashboard" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th class="align-middle text-center">No.</th>
										<th class="align-middle text-center">Type</th>
										<th class="align-middle text-center">Subject</th>
										<th class="align-middle text-center">User</th>
									</tr>
								</thead>
								
								<tbody>
									@if($logs->count() > 0)
										@foreach($logs as $log)
											<tr>
												<td class="align-middle text-center">{{ $loop->iteration }}</td>
												<td class="align-middle text-center">{{ $log->type }}</td>
												<td class="align-middle text-center">{{ $log->subject }}</td>
												<td class="align-middle text-center">{{ $log->user }}</td>
											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

<script>
    var feedbackAppointmentRoute = "{{ route('dashboard.getAppointmentFeedbackData') }}";
	var feedbackDocumentRequestRoute = "{{ route('dashboard.getDocumentRequestFeedbackData') }}";
	var servicesCountRoute = "{{ route('dashboard.getServicesCountData') }}";
	var documentTypeCountRoute = "{{ route('dashboard.getDocumentTypeCountData') }}";
</script>

<script src="../../plugins/chart.js/Chart.min.js"></script>
<script src="../../js/services-line-graph.js"></script>
<script src="../../js/document-type-line-graph.js"></script>
<script src="../../js/chart-area.js"></script>
@endsection