@extends('layouts.app')
  
@section('contents')
    @if(Session::has('success'))
        <div class="alert alert-success" id="alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">List of Appointment Feedback</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableFeedbackAppointment" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Appointment ID</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            @if (auth()->user()->level == 'Super Admin')
                                <th></th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if($feedback->count() > 0)
                            @foreach($feedback as $fdbck)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $fdbck->transaction_id }}</td>
                                    <td class="align-middle text-center">{{ $fdbck->rating }}</td>  
                                    <td class="align-middle text-center">{{ $fdbck->comment }}</td>
                                    <td class="align-middle text-center">{{ $fdbck->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $fdbck->updated_at }}</td>
                                    @if (auth()->user()->level == 'Super Admin')
                                        <td class="align-middle text-center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('feedbackEditForm', $fdbck->transaction_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$fdbck->transaction_id}}">Delete</button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                                @include('appointment.deleteFeedback')
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let successAlert = document.getElementById('alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = 0;
                setTimeout(() => { successAlert.remove(); }, 500);
            }, 2000);
        }

        let failedAlert = document.getElementById('alert-failed');
        if (failedAlert) {
            setTimeout(() => {
                failedAlert.style.transition = "opacity 0.5s ease";
                failedAlert.style.opacity = 0;
                setTimeout(() => { failedAlert.remove(); }, 500);
            }, 2000);
        }
    });
</script>