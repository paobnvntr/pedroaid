@extends('layouts.app')
  
@section('title', 'Notifications')
  
@section('contents')

    <div class="pt-4 pb-4">
        <div class="container">
            <div class="row">
                <!-- Unread Messages Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow p-4">
                        <div class="trackerAppointmentContact">
                            <h5 class="text-center">Unread Notifications</h5>
                            <hr>

                            <div class="pr-4 pl-4" id="messagesForm">
                                @php
                                    $unreadNotificationsFiltered = auth()->user()->unreadNotifications
                                        ->filter(function ($notification) {
                                            return $notification->data['is_active'] === true &&
                                                    (
                                                        $notification->type === 'App\Notifications\NewAppointment' ||
                                                        $notification->type === 'App\Notifications\NewDocumentRequest' ||
                                                        $notification->type === 'App\Notifications\NewInquiry'
                                                    );
                                        });

                                    $unreadNotificationsGrouped = $unreadNotificationsFiltered->groupBy(function($notification) {
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

                                    <a id="notification_{{ $notifications[0]->id }}" class="dropdown-item d-flex align-items-center mb-2 notification-item shadow-sm rounded" href="{{ 
                                        $transactionType === 'Appointment' ? route('appointment.appointmentDetails', $id) :
                                        ($transactionType === 'Document Request' ? route('document-request.documentRequestDetails', $id) :
                                        ($transactionType === 'Inquiry' ? route('inquiry.inquiryDetails', $id) : '')) 
                                    }}" data-transaction-id="{{ $id }}">
                                        <div class="dropdown-list-image mr-3">
                                            <img class="rounded-circle" src="/uploads/profile/staff/default_staff.jpg" alt="..." style="max-width: 50px;">
                                        </div>
                                        <div class="font-weight-bold">
                                            <div class="text-truncate font-weight-bold d-inline-block" style="max-width: 150px;">New {{ $notifications[0]->data['transaction_type'] }}! @if ($unreadCount > 1) <span class="badge badge-danger">{{ $unreadCount }}</span> @endif</div>
                                            <div class="small text-gray-500">{{ $notifications[0]->data['name'] }} · {{ $notifications[0]->data['transaction_type'] }}</div>
                                            <div class="small text-gray-500">{{ $notifications[0]->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Read Messages Card -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow p-4">
                        <div class="trackerAppointmentContact">
                            <h5 class="text-center">Read Notifications</h5>
                            <hr>

                            <div class="pr-4 pl-4" id="messagesForm">

                                @foreach (auth()->user()->readNotifications as $notification)
                                    @php
                                        $notificationDate = $notification->read_at;
                                        $sevenDaysAgo = now()->subDays(7);
                                        $transactionType = $notification->data['transaction_type'];
                                        $id = '';
                                        if ($transactionType === 'Appointment') {
                                            $id = $notification->data['appointment_id'];
                                        } else if ($transactionType === 'Document Request') {
                                            $id = $notification->data['documentRequest_id'];
                                        } else if ($transactionType === 'Inquiry') {
                                            $id = $notification->data['inquiry_id'];
                                        }
                                    @endphp

                                    {{-- Check if the notification type matches --}}
                                    @if ($notification->data['is_active'] === true && ($notification->type === 'App\Notifications\NewAppointment' || $notification->type === 'App\Notifications\NewDocumentRequest' || $notification->type === 'App\Notifications\NewInquiry'))
                                        {{-- Check if the notification is within the last 7 days --}}
                                        @if ($notificationDate->greaterThanOrEqualTo($sevenDaysAgo))
                                            <a id="notification_{{ $notification->id }}" class="dropdown-item d-flex align-items-center mb-2 notification-item shadow-sm rounded" href="{{ 
                                                $transactionType === 'Appointment' ? route('appointment.appointmentDetails', $id) :
                                                ($transactionType === 'Document Request' ? route('document-request.documentRequestDetails', $id) :
                                                ($transactionType === 'Inquiry' ? route('inquiry.inquiryDetails', $id) : '')) 
                                            }}">
                                                <div class="dropdown-list-image mr-3">
                                                    <img class="rounded-circle" src="/uploads/profile/staff/default_staff.jpg" alt="..." style="max-width: 50px;">
                                                </div>
                                                <div class="font-weight-bold">
                                                    <div class="text-truncate font-weight-bold d-inline-block" style="max-width: 150px;">New {{ $transactionType }}!</div>
                                                    <div class="small text-gray-500">{{ $notification->data['name'] }} · {{ $transactionType }}</div>
                                                    <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                                                </div>
                                            </a>
                                        @endif
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection