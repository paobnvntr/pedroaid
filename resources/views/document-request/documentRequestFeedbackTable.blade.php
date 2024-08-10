@extends('layouts.app')
  
@section('contents')
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">List of Document Request Feedback</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableFeedbackDocumentRequest" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Request ID</th>
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
                                                <a href="{{ route('document-request.feedbackEditForm', $fdbck->transaction_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$fdbck->transaction_id}}">Delete</button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                                @include('document-request.deleteFeedback')
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection