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
            <h4 class="m-0 font-weight-bold text-primary mr-auto">List of City Ordinances</h4>
            <div class="d-flex">
                <a href="{{ route('ordinance.addOrdinance') }}" class="btn btn-primary">Add Ordinance</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableOrdinance" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Committee</th>
                            <th>Ordinance No.</th>
                            <th>Date Approved</th>
                            <th>Description</th>
                            <th>File</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($ordinance->count() > 0)
                            @foreach($ordinance as $ord)
                                <tr class="align-middle">
                                    <td></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $ord->committee }}</td>
                                    <td class="align-middle text-center">{{ $ord->ordinance_number }}</td>
                                    <td class="align-middle text-center">{{ $ord->date_approved }}</td>
                                    <td class="align-middle text-center"><div class="description">{{ $ord->description }}</div></td>
                                    <td class="align-middle text-center">
                                        <a href="{{ asset($ord->ordinance_file) }}" target="_blank" class="btn btn-primary">View</a>
                                    </td>  
                                    <td class="align-middle text-center">{{ $ord->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $ord->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('ordinance.editOrdinance', $ord->id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$ord->id}}">Delete</button>
                                    </td>
                                </tr>

                                @include('ordinance.deleteOrdinance')
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