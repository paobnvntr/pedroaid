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
            <h4 class="m-0 font-weight-bold text-primary mr-auto">List of Legislative Council Committee</h4>
            <div class="d-flex">
                <a href="{{ route('committee.addCommittee') }}" class="btn btn-primary">Add Committee</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="dataTableCommittee" width="120%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Chairman</th>
                            <th>Vice-Chairman</th>
                            <th>Member 1</th>
                            <th>Member 2</th>
                            <th>Member 3</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($committees->count() > 0)
                            @foreach($committees as $com)
                                <tr>
                                    <td></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $com->name }}</td>
                                    <td class="align-middle text-center">{{ $com->chairman }}</td>
                                    <td class="align-middle text-center">{{ $com->vice_chairman }}</td>
                                    <td class="align-middle text-center">{{ $com->member_1 }}</td> 
                                    <td class="align-middle text-center">{{ $com->member_2 }}</td> 
                                    <td class="align-middle text-center">{{ $com->member_3 }}</td> 
                                    <td class="align-middle text-center">{{ $com->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $com->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('committee.editCommittee', $com->id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <button type="button" class="btn btn-danger p-2" data-toggle="modal" data-target="#deleteModal{{$com->id}}">Delete</button>
                                        </div>
                                    </td>
                                </tr>

                                @include('committee.deleteCommittee')
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