<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
		<i class="fa fa-bars"></i>
	</button>

	<ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown no-arrow d-sm-none">
			<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-search fa-fw"></i>
			</a>

			<div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
				aria-labelledby="searchDropdown">
				<form class="form-inline mr-auto w-100 navbar-search">
					<div class="input-group">
						<input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
							aria-label="Search" aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-primary" type="button">
								<i class="fas fa-search fa-sm"></i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</li>

		<li class="nav-item dropdown no-arrow mx-1">
			<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-bell fa-fw"></i>
				@php
					$unreadNotifications = auth()->user()->unreadNotifications
						->filter(function ($notification) {
							return isset($notification->data['is_active']) && $notification->data['is_active'] === true &&
								(
									$notification->type === 'App\Notifications\NewAppointment' ||
									$notification->type === 'App\Notifications\NewDocumentRequest' ||
									$notification->type === 'App\Notifications\NewInquiry'
								);
						});
				@endphp

				@if ($unreadNotifications->isNotEmpty())
					<span class="badge badge-danger badge-counter">
						{{ $unreadNotifications->count() }}
					</span>
				@endif
			</a>

			<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
				aria-labelledby="alertsDropdown">
				<h6 class="dropdown-header d-flex justify-content-between align-items-center">
					<span>Unread Alerts</span>
					<a class="mark-all-notifications-as-read" href="#">
						<i class="fas fa-check"></i>
					</a>
				</h6>

				<div class="notification-container">
					@php
						$unreadNotificationsFiltered = auth()->user()->unreadNotifications
							->filter(function ($notification) {
								return isset($notification->data['is_active']) && $notification->data['is_active'] === true &&
									(
										$notification->type === 'App\Notifications\NewAppointment' ||
										$notification->type === 'App\Notifications\NewDocumentRequest' ||
										$notification->type === 'App\Notifications\NewInquiry'
									);
							});

						$unreadNotificationsGrouped = $unreadNotificationsFiltered->groupBy(function ($notification) {
							$transactionType = $notification->data['transaction_type'];
							$id = '';
							if ($transactionType === 'Appointment') {
								$id = $notification->data['appointment_id'];
							} else if ($transactionType === 'Document Request') {
								$id = $notification->data['documentRequest_id'];
							} else if ($transactionType === 'Inquiry') {
								$id = $notification->data['inquiry_id'];
							}
							return $transactionType . '_' . $id;
						});
					@endphp

					@foreach ($unreadNotificationsGrouped as $transactionKey => $notifications)
										@php
											$unreadCount = count($notifications);
											$transactionType = $notifications[0]->data['transaction_type'];
											[$type, $id] = explode('_', $transactionKey);
										  @endphp

										<a id="notification_{{ $notifications[0]->id }}"
											class="dropdown-item d-flex align-items-center notification-item" href="{{ 
										  $transactionType === 'Appointment' ? route('appointment.appointmentDetails', $id) :
							($transactionType === 'Document Request' ? route('document-request.documentRequestDetails', $id) :
								($transactionType === 'Inquiry' ? route('inquiry.inquiryDetails', $id) : '')) 
									  }}" data-transaction-id="{{ $id }}">
											<div class="dropdown-list-image mr-3">
												<img class="rounded-circle" src="/uploads/profile/staff/default_staff.jpg" alt="...">
											</div>
											<div class="font-weight-bold">
												<div class="text-truncate">New {{ $transactionType }}! @if ($unreadCount > 1) <span
												class="badge badge-danger">{{ $unreadCount }}</span> @endif</div>
												<div class="small text-gray-500">{{ $notifications[0]->data['name'] }} ·
													{{ $transactionType }}</div>
												<div class="small text-gray-500">{{ $notifications[0]->created_at->diffForHumans() }}</div>
											</div>
										</a>
					@endforeach
				</div>

				<a class="dropdown-item text-center small text-gray-500" href="{{ route('showAllNotifications') }}">Show
					All Alerts</a>
			</div>
		</li>

		<li class="nav-item dropdown no-arrow mx-1">
			<a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-envelope fa-fw" id="message-notification"></i>
				@php
					$unreadMessages = auth()->user()->unreadNotifications
						->filter(function ($notification) {
							return isset($notification->data['is_active']) && $notification->data['is_active'] === true &&
								(
									$notification->type === 'App\Notifications\NewAppointmentMessage' ||
									$notification->type === 'App\Notifications\NewDocumentRequestMessage' ||
									$notification->type === 'App\Notifications\NewInquiryMessage'
								);
						});
				@endphp

				@if ($unreadMessages->isNotEmpty())
					<span class="badge badge-danger badge-counter">
						{{ $unreadMessages->count() }}
					</span>
				@endif
			</a>

			<div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
				aria-labelledby="messagesDropdown">

				<h6 class="dropdown-header d-flex justify-content-between align-items-center">
					<span>Unread Messages</span>
					<a class="mark-all-messages-as-read" href="#">
						<i class="fas fa-envelope-open-text"></i>
					</a>
				</h6>

				<div class="notification-container">
					@php
						$unreadNotificationsFiltered = auth()->user()->unreadNotifications
							->filter(function ($notification) {
								return isset($notification->data['is_active']) && $notification->data['is_active'] === true &&
									(
										$notification->type === 'App\Notifications\NewAppointmentMessage' ||
										$notification->type === 'App\Notifications\NewDocumentRequestMessage' ||
										$notification->type === 'App\Notifications\NewInquiryMessage'
									);
							});

						$unreadNotificationsGrouped = $unreadNotificationsFiltered->groupBy(function ($notification) {
							$transactionType = $notification->data['transaction_type'];
							$id = '';
							if ($transactionType === 'Appointment') {
								$id = $notification->data['appointment_id'];
							} else if ($transactionType === 'Document Request') {
								$id = $notification->data['documentRequest_id'];
							} else if ($transactionType === 'Inquiry') {
								$id = $notification->data['inquiry_id'];
							}
							return $transactionType . '_' . $id;
						});
					@endphp

					@foreach ($unreadNotificationsGrouped as $transactionKey => $notifications)
										@php
											$unreadCount = count($notifications);
											$transactionType = $notifications[0]->data['transaction_type'];
											[$type, $id] = explode('_', $transactionKey);
										@endphp

										<a id="notification_{{ $notifications[0]->id }}"
											class="dropdown-item d-flex align-items-center message-item" href="{{ 
											$transactionType === 'Appointment' ? route('appointment.appointmentDetails', $id) :
							($transactionType === 'Document Request' ? route('document-request.documentRequestDetails', $id) :
								($transactionType === 'Inquiry' ? route('inquiry.inquiryDetails', $id) : '')) 
										}}" data-transaction-id="{{ $id }}">

											<div class="dropdown-list-image mr-3">
												<img class="rounded-circle" src="/uploads/profile/staff/default_staff.jpg" alt="...">
											</div>
											<div class="font-weight-bold">
												<div class="text-truncate">New Message! @if ($unreadCount > 1) <span
												class="badge badge-danger">{{ $unreadCount }}</span> @endif</div>
												<div class="small text-gray-500">{{ $notifications[0]->data['name'] }} ·
													{{ $transactionType }}</div>
												<div class="small text-gray-500">{{ $notifications[0]->created_at->diffForHumans() }}</div>
											</div>
										</a>
					@endforeach
				</div>

				<a class="dropdown-item text-center small text-gray-500" href="{{ route('showAllMessages') }}">Show All
					Messages</a>
			</div>
		</li>

		<div class="topbar-divider d-none d-sm-block"></div>

		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
				aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline text-gray-800 small">
					{{ auth()->user()->name }}
					<br>
					<small>{{ auth()->user()->level }}</small>
				</span>
				<img class="img-profile rounded-circle" src="/{{ auth()->user()->profile_picture }}">
			</a>

			<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
				<a class="dropdown-item" href="{{ route('profile') }}">
					<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
					Profile
				</a>

				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="{{ route('logout') }}">
					<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
					Logout
				</a>
			</div>
		</li>
	</ul>
</nav>