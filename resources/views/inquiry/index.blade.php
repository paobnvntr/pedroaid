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
            <h4 class="m-0 font-weight-bold text-warning">List of Unanswered Inquiry</h4>
            <a href="{{ route('inquiry.addInquiry') }}" class="btn btn-primary">Add Inquiry</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableUnansweredInquiry" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Inquiry Message</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($unanswered_inquiry->count() > 0)
                            @foreach($unanswered_inquiry as $inq)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $inq->name }}</td>
                                    <td class="align-middle text-center">{{ $inq->email }}</td>  
                                    <td class="align-middle text-center">{{ $inq->inquiry }}</td>
                                    <td class="align-middle text-center">{{ $inq->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $inq->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('inquiry.inquiryDetails', $inq->inquiry_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            <a href="{{ route('inquiry.editInquiry', $inq->inquiry_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <a href="{{ route('inquiry.deleteInquiry', $inq->inquiry_id) }}" type="button" class="btn btn-danger p-2">Delete</a>
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

    <hr>

    <div class="card shadow mb-2">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-primary">List of Answered Inquiry</h4>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableAnsweredInquiry" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Inquiry Message</th>
                            <th>Date Created</th>
                            <th>Last Update</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($answered_inquiry->count() > 0)
                            @foreach($answered_inquiry as $inq)
                                <tr class="align-middle">
                                    <td class="align-middle text-center"></td>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">{{ $inq->name }}</td>
                                    <td class="align-middle text-center">{{ $inq->email }}</td>  
                                    <td class="align-middle text-center">{{ $inq->inquiry }}</td>
                                    <td class="align-middle text-center">{{ $inq->created_at }}</td>  
                                    <td class="align-middle text-center">{{ $inq->updated_at }}</td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{ route('inquiry.inquiryDetails', $inq->inquiry_id) }}" type="button" class="btn btn-primary p-2">Details</a>
                                            <a href="{{ route('inquiry.editInquiry', $inq->inquiry_id) }}" type="button" class="btn btn-warning p-2">Edit</a>
                                            <a href="{{ route('inquiry.deleteInquiry', $inq->inquiry_id) }}" type="button" class="btn btn-danger p-2">Delete</a>
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