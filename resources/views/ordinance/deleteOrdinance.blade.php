@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    <a href="{{ route('ordinance') }}" class="fas fa-angle-left fs-4"></a>
    <h1 class="mb-0 ml-4">Delete Ordinance Account</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('ordinance.destroyOrdinance', $ordinance->id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete Ordinance No. <strong>{{ $ordinance->ordinance_number }}</strong> permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Ordinance</button>
    </form>
    <hr>
@endsection