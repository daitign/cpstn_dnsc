@extends('layout.sidebar')
@section('title')
<title>Notifications</title>
@endsection
@section('page')
    <div class="page-header">
        <h2>Notifications</h2>
    </div>
   
    <div class="m-3">
        <div class="row">
            <div class="col-12">
                <div class="row mt-2">
                    <div class="col-12 px-2">
                        <div class="card p-3">
                            <div class="card-body pt-2">
                                <h4 class="text-success"> Notifications</h4>
                                <table class="table datatables">
                                    <tr>
                                        <th>#</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                    </tr>
                                    <div style="max-height:400px; overflow-y:scroll">
                                        <tbody>
                                            @if($notifications)
                                                @foreach($notifications as $notification)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            <a href="{{ $notification->data['link'] ?? route('notifications') }}">
                                                                {{ $notification->data['message'] }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $notification->created_at->diffForHumans() }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="2"><h6>NO NOTIFICATIONS FOUND</h6></td></tr>
                                            @endif
                                        </tbody>
                                    </div>
                                </table>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection