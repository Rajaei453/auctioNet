@extends('layouts.providerMaster')
@section('content')

<h3>All Providers</h3>

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
            <td>{{$provider->luxury}} Stars</td>
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

