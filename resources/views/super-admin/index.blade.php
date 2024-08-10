@extends('layouts.app')
  
@section('contents')
    @if(Session::has('success'))
        <div class="alert alert-success" id="alert-success" role="alert">
            {!! Session::get('success') !!}
        </div>
    @endif

    @if(Session::has('failed'))
        <div class="alert alert-danger" id="alert-failed" role="alert">
            {{ Session::get('failed') }}
        </div>
    @endif

    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">List of Super Admin</h4>
            <a href="{{ route('super-admin.addSuperAdmin') }}" class="btn btn-primary">Add Super Admin</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableSuperAdmin" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Profile Picture</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if($super_admin->count() > 0)
                            @foreach($super_admin as $sad)
                                <tr>
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $sad->name }}</td>
                                    <td class="align-middle text-center">{{ $sad->username }}</td>
                                    <td class="align-middle text-center">{{ $sad->email }}</td>
                                    <td class="align-middle text-center"> <img src="{{ $sad->profile_picture }}" alt="{{ $sad->profile_picture }}" width="50" class="rounded-circle"> </td>
                                    <td class="align-middle text-center">{{ $sad->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $sad->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('super-admin.editSuperAdmin', $sad->id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$sad->id}}">Delete</button>
                                        </div>
                                    </td>
                                </tr>

                                @include('super-admin.deleteSuperAdmin')
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
            }, 10000);
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