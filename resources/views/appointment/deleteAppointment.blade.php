@extends('layouts.app')

@section('contents')
  <div class="d-flex align-items-center justify-content-start addStaff mb-4">
    @if($appointment->appointment_status == 'Pending' || $appointment->appointment_status == 'Declined')
        <a href="{{ route('appointment.pendingAppointment') }}" class="fas fa-angle-left fs-4"></a>
    @elseif($appointment->appointment_status == 'Booked' || $appointment->appointment_status == 'Rescheduled')
        <a href="{{ route('appointment') }}" class="fas fa-angle-left fs-4"></a>
    @else
      <a href="{{ route('appointment.finishedAppointment') }}" class="fas fa-angle-left fs-4"></a>
    @endif
    <h1 class="mb-0 ml-4">Delete Appointment</h1>
  </div>

  <div class="p-5">
    <form action="{{ route('appointment.destroyAppointment', $appointment->appointment_id) }}" method="POST" class="user">
      @csrf
      @method('DELETE')
      <div class="form-group">
        <p>Are you sure you want to delete Appointment ID: <strong>{{ $appointment->appointment_id }}</strong> permanently?</p>
      </div>
      
      <button type="submit" class="btn btn-danger btn-user btn-block">Permanently Delete Appointment</button>
    </form>
    <hr>
@endsection