@extends('layouts.userMaster')

@section('content')
<h3>Your Reservations</h3>


<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Category</th>
        <th scope="col">Date</th>
        <th scope="col">Provider</th>
        <th scope="col">City</th>
        <th scope="col">Luxury</th>
        <th scope="col">Chairs</th>
        <th scope="col">Comments</th>
        <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservations as $reservation)
        <tr>
            <th scope="row">{{$reservation->id}}</th>
            <td>{{$reservation->category}}</td>
            <td>{{$reservation->date}}</td>
            <td>{{$reservation->provider->name}}</td>
            <td>{{$reservation->city}}</td>
            <td>{{$reservation->luxury}} Starts</td>
            <td>{{$reservation->chairs}}</td>
            <td>{{$reservation->comments}}</td>

            @if($reservation->approved == 'approved')
                <td class="alert alert-success" >{{$reservation->approved}}</td>
            @endif
            @if($reservation->approved == 'pending')
                <td class="alert alert-warning" >{{$reservation->approved}}</td>
            @endif
            @if($reservation->approved == 'canceled')
                <td class="alert alert-danger" >{{$reservation->approved}}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
<a class="btn btn-primary" href="{{route('addReservation',$jwt_token)}}" role="button">Add New Reservation</a>
@endsection
