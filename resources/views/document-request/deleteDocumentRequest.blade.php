@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    @if($documentRequest->documentRequest_status == 'Pending' || $documentRequest->documentRequest_status == 'Declined')
        <a href="{{ route('document-request.pendingDocumentRequest') }}" class="fas fa-angle-left fs-4"></a>
    @elseif($documentRequest->documentRequest_status == 'To Claim' || $documentRequest->documentRequest_status == 'Claimed' || $documentRequest->documentRequest_status == 'Unclaimed')
        <a href="{{ route('document-request.finishedDocumentRequest') }}" class="fas fa-angle-left fs-4"></a>
    @else
        <a href="{{ route('document-request') }}" class="fas fa-angle-left fs-4"></a>
    @endif
    <h1 class="mb-0 ml-4">Delete Document Request</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('document-request.destroyDocumentRequest', $documentRequest->documentRequest_id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete Document Request ID: <strong>{{ $documentRequest->documentRequest_id }}</strong> permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Document Request</button>
    </form>
    <hr>
@endsection