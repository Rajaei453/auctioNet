@extends('layouts.userMaster')

@section('content')
<h3>Providers in Your City</h3>

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
    @foreach($providers as $provider)
        <tr>
            <th scope="row">{{$provider->id}}</th>
            <td>{{$provider->name}}</td>
            <td>{{$provider->email}}</td>
            <td>{{$provider->city}}</td>
            <td>{{$provider->luxury}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
