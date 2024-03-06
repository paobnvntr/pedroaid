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
            <h4 class="m-0 font-weight-bold text-primary">List of Staff</h4>
            <a href="{{ route('staff.addStaff') }}" class="btn btn-primary">Add Staff</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableStaff" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Transaction</th>
                            <th>Profile Picture</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if($staff->count() > 0)
                            @foreach($staff as $sf)
                                <tr>
                                    <td></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $sf->name }}</td>
                                    <td class="align-middle text-center">{{ $sf->username }}</td>
                                    <td class="align-middle text-center">{{ $sf->email }}</td>
                                    <td class="align-middle text-center">{{ $sf->transaction_level }}</td>  
                                    <td class="align-middle text-center"> <img src="{{ $sf->profile_picture }}" alt="{{ $sf->profile_picture }}" width="50" class="rounded-circle"> </td>
                                    <td class="align-middle text-center">{{ $sf->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $sf->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('staff.editStaff', $sf->id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <a href="{{ route('staff.deleteStaff', $sf->id) }}" type="button" class="btn btn-danger p-2">Delete</a>
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