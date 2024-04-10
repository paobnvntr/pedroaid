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
            <h4 class="m-0 font-weight-bold text-primary">List of Finished Appointment</h4>
            <a href="{{ route('appointment.addAppointment') }}" class="btn btn-primary">Add Appointment</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableFinishedAppointment" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Appointment ID</th>
                            <th>Date Finished</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Client Name</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($finished->count() > 0)
                            @foreach($finished as $appt)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $appt->appointment_id }}</td>
                                    <td class="align-middle text-center">{{ $appt->date_finished }}</td>
                                    <td class="align-middle text-center">{{ $appt->appointment_date }}</td>  
                                    <td class="align-middle text-center">{{ $appt->appointment_time }}</td>
                                    <td class="align-middle text-center">{{ $appt->name }}</td>
                                    <td class="align-middle text-center">{{ $appt->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $appt->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('appointment.appointmentDetails', $appt->appointment_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            @if (auth()->user()->level == 'Super Admin')
                                                <a href="{{ route('appointment.editAppointment', $appt->appointment_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <a href="{{ route('appointment.deleteAppointment', $appt->appointment_id) }}" type="button" class="btn btn-danger p-2">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-danger">List of No-Show Appointment</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableNoShowAppointment" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Appointment ID</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Client Name</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($no_show->count() > 0)
                            @foreach($no_show as $appt)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $appt->appointment_id }}</td>
                                    <td class="align-middle text-center">{{ $appt->appointment_date }}</td>  
                                    <td class="align-middle text-center">{{ $appt->appointment_time }}</td>
                                    <td class="align-middle text-center">{{ $appt->name }}</td>
                                    <td class="align-middle text-center">{{ $appt->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $appt->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('appointment.appointmentDetails', $appt->appointment_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            @if (auth()->user()->level == 'Super Admin')
                                                <a href="{{ route('appointment.editAppointment', $appt->appointment_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                                <a href="{{ route('appointment.deleteAppointment', $appt->appointment_id) }}" type="button" class="btn btn-danger p-2">Delete</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection