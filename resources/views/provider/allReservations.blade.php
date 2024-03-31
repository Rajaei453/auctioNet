@extends('layouts.providerMaster')
@section('content')



<h3>All Reservations</h3>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">User Name</th>
        <th scope="col">Category</th>
        <th scope="col">Date</th>
        <th scope="col">City</th>
        <th scope="col">Luxury</th>
        <th scope="col">Chairs</th>
        <th scope="col">Comments</th>
        <th scope="col">Status</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservations as $reservation)
        <tr>
            <th scope="row">{{$reservation->id}}</th>
            <td>{{$reservation->user->name}}</td>
            <td>{{$reservation->category->name}}</td>
            <td>{{$reservation->date}}</td>
            <td>{{$reservation->city}}</td>
            <td>{{$reservation->luxury}} Starts</td>
            <td>{{$reservation->chairs}}</td>
            <td>{{$reservation->comments}}</td>
            <td>{{$reservation->approved}}</td>
            <td>
            @if($reservation->approved != 'approved')
            <a href="{{url('providers/approveReservation/'.$jwt_token.'/'.$reservation->id)}}" type="button" class="btn btn-success" >{{__('Approve')}}</a>
            @endif
            @if($reservation->approved != 'canceled')
            <a href="{{url('providers/cancelReservation/'.$jwt_token.'/'.$reservation->id)}}" type="button" class="btn btn-danger" >{{__('Cancel')}}</a>
            @endif
            </td>


        </tr>
    @endforeach
    </tbody>
</table>
@if(Session::has('success'))
    <div class="alert alert-success" role="alert">

        {{Session::get('success')}}

    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger" role="alert">

        {{Session::get('error')}}

    </div>
@endif
@endsection

