@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    <a href="{{ route('committee') }}" class="fas fa-angle-left fs-4"></a>
    <h1 class="mb-0 ml-4">Delete Committee</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('committee.destroyCommittee', $committee->id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete <strong>{{ $committee->name }}</strong> permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Committee</button>
    </form>
    <hr>
@endsection