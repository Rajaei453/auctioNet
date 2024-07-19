@extends('layouts.providerMaster')

@section('content')
<h3>Your Informations</h3>

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
<body class="container">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Edit') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('providerEdit',$jwt_token) }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$provider->name}}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$provider->email}}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="city" class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>
                        <div class="col-md-6">
                            <select name="city" id="city" class="form-select"  >
                                <option value="Homs">Homs</option>
                                <option value="Damascus">Damascus</option>
                                <option value="Hama">Hama</option>
                                <option value="Latakia">Latakia</option>
                            </select>

                            @error('city')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>
                        <div class="row mb-3">

                            <div>&nbsp;</div>



                            <div class="row mb-3">
                                <label for="luxury" class="col-md-4 col-form-label text-md-end">{{ __('Luxury') }}</label>

                                &nbsp;&nbsp; <div class="col-md-6">
                                    <select name="luxury" id="luxury" class="form-select" >
                                        <option value="1">1 Star</option>
                                        <option value="2">2 Stars</option>
                                        <option value="3">3 Stars</option>
                                        <option value="4">4 Stars</option>
                                        <option value="5">5 Stars</option>
                                    </select>
                                    @error('luxury')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>

                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Edit') }}
                                    </button>
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

                </form>
            </div>
        </div>
    </div>
</div>
</body>
@endsection
