@extends('layouts.app')
  
@section('contents')
    <div class="card shadow mb-2">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Logs</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableLog" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @if($logs->count() > 0)
                            @foreach($logs as $lg)
                                <tr>
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $lg->type }}</td>
                                    <td class="align-middle text-center">{{ $lg->user }}</td>
                                    <td class="align-middle text-center">{{ $lg->subject }}</td>
                                    <td class="align-middle text-center">{{ $lg->message }}</td>  
                                    <td class="align-middle text-center">{{ $lg->created_at }}</td>  
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection