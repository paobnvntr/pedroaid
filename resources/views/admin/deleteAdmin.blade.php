@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    <a href="{{ route('admin') }}" class="fas fa-angle-left fs-4"></a>
    <h1 class="mb-0 ml-4">Delete Admin Account</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('admin.destroyAdmin', $admin->id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete <strong>{{ $admin->name }}'s</strong> account permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Account</button>
    </form>
    <hr>
@endsection