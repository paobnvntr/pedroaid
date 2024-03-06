@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    <a href="{{ route('inquiry') }}" class="fas fa-angle-left fs-4"></a>
    <h1 class="mb-0 ml-4">Delete Inquiry</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('inquiry.destroyInquiry', $inquiry->inquiry_id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete Inquiry ID: <strong>{{ $inquiry->inquiry_id }}</strong> permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Inquiry</button>
    </form>
    <hr>
@endsection