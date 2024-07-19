@extends('layouts.userMaster')

@section('content')
<h3>Provider Details</h3>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">City</th>
        <th scope="col">Luxury</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{$provider->id}}</th>
            <td>{{$provider->name}}</td>
            <td>{{$provider->email}}</td>
            <td>{{$provider->city}}</td>
            <td>{{$provider->luxury}}</td>
        </tr>
    </tbody>
</table>

@endsection
