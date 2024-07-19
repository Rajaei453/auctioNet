@extends('layouts.userMaster')
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
        <th scope="col">Actions</th>
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
            <td>
                <a href="{{url('users/providerDetails/'.$jwt_token.'/'.$provider->id)}}" type="button" class="btn btn-outline-dark" >{{__('Details')}}</a>

                <a href="{{url('users/providerReviews/'.$jwt_token.'/'.$provider->id)}}" type="button" class="btn btn-outline-warning" >{{__('Reviews')}}</a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Filter') }}</div>
            <form method="POST" action="{{ route('filterProviders',$jwt_token) }}" enctype="multipart/form-data">
    @csrf
                <div class="row mb-3">
        <label for="city" class="col-md-4 col-form-label text-md-end"><h5>{{ __('City') }}</h5></label>
        <div class="col-md-6">
            <select name="city" id="city" class="form-select" >
                <option value="0">all</option>
                <option value="Homs">Homs</option>
                <option value="Hama">Hama</option>
                <option value="Damascus">Damascus</option>
                <option value="Latakia">Latakia</option>
            </select>            @error('city')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
                <div class="row mb-3">
                    <label for="luxury" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Luxury') }}</h5></label>
                    <div class="col-md-6">
                        <select name="luxury" id="luxury" class="form-select" >
                <option value="0">all</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Starts</option>
                <option value="5">5 Starts</option>
            </select>
            @error('luxury')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-0 dark:bg-gray-900">
        <div class="col-md-6 offset-md-4 " >
            <button type="submit" class="btn btn-primary" >
                {{ __('Filter') }}
            </button>
        </div>
    </div>

</form>
</div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Filter') }}</div>
            <form method="POST" action="{{ route('filterByName',$jwt_token) }}" enctype="multipart/form-data">
    @csrf
                <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Name') }}</h5></label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"   >
                        @error('name')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>

        </div>
    <div class="row mb-0 dark:bg-gray-900">
        <div class="col-md-6 offset-md-4 " >
            <button type="submit" class="btn btn-primary" >
                {{ __('Filter') }}
            </button>
        </div>
    </div>

</form>
</div>
    </div>
</div>


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

