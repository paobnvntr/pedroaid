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
            <h4 class="m-0 font-weight-bold text-primary">List of Pending Document Request</h4>
            <a href="{{ route('document-request.addDocumentRequest') }}" class="btn btn-primary">Add Request</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTablePendingDocumentRequest" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Request ID</th>
                            <th>Document Type</th>
                            <th>Client Name</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($pending->count() > 0)
                            @foreach($pending as $docreq)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $docreq->documentRequest_id }}</td>
                                    <td class="align-middle text-center">{{ $docreq->document_type }}</td>  
                                    <td class="align-middle text-center">{{ $docreq->name }}</td>
                                    <td class="align-middle text-center">{{ $docreq->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $docreq->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('document-request.documentRequestDetails', $docreq->documentRequest_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            @if (auth()->user()->level == 'Super Admin')
                                                <a href="{{ route('document-request.editDocumentRequest', $docreq->documentRequest_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$docreq->documentRequest_id}}">Delete</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @include('document-request.deleteDocumentRequest')
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-danger">List of Declined Request</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableDeclinedDocumentRequest" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Request ID</th>
                            <th>Document Type</th>
                            <th>Client Name</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($declined->count() > 0)
                            @foreach($declined as $docreq)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $docreq->documentRequest_id }}</td>
                                    <td class="align-middle text-center">{{ $docreq->document_type }}</td>  
                                    <td class="align-middle text-center">{{ $docreq->name }}</td>
                                    <td class="align-middle text-center">{{ $docreq->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $docreq->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('document-request.documentRequestDetails', $docreq->documentRequest_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            @if (auth()->user()->level == 'Super Admin')
                                                <a href="{{ route('document-request.editDocumentRequest', $docreq->documentRequest_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$docreq->documentRequest_id}}">Delete</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                @include('document-request.deleteDocumentRequest')
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