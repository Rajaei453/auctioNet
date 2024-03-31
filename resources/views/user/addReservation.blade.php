@extends('layouts.userMaster')

@section('content')
<h3>Add Your Reservation</h3>

<form method="POST" action="{{ route('addReservation',$jwt_token) }}" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <label for="category" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Category') }}</h5></label>
        <div class="col-md-6">
            <div class="col-md-6">
                <select name="category_id" id="category_id" class="form-select" >
                    @foreach($categories as $category)
                        <option value="{{$category -> id}}">{{$category -> name}}</option>
                    @endforeach
                </select>
                @error('category')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror

            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="date" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Date') }}</h5></label>
        <div class="col-md-6">
            <input id="date" type="date" min="2023-01-01" class="form-control" name="date" value="{{ old('date') }}"  >
            @error('date')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="city" class="col-md-4 col-form-label text-md-end"><h5>{{ __('City') }}</h5></label>
        <div class="col-md-6">
            <select name="city" id="city" class="form-select" >
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
        <label for="provider" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Provider') }}</h5></label>
        <div class="col-md-6">
            <select name="provider" id="provider" class="form-select" >
                @foreach($providers as $provider)
                <option value="{{$provider->id}}">{{$provider->name}}</option>
                @endforeach
            </select>
            @error('provider')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="luxury" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Luxury') }}</h5></label>
        <div class="col-md-6">
            <select name="luxury" id="luxury" class="form-select" >
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
    <div class="row mb-3">
        <label for="chairs" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Chairs') }}</h5></label>
        <div class="col-md-6">
            <input id="chairs" type="number"  min="1" max="100" class="form-control" name="chairs" value="{{ old('chairs') }}"   >
            @error('chairs')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-3">
        <label for="details" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Comments') }}</h5></label>
        <div class="col-md-6">
            <input id="comments" type="text" class="form-control" name="comments" value="{{ old('comments') }}"   >
            @error('comments')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
    </div>
    <div class="row mb-0 dark:bg-gray-900">
        <div class="col-md-6 offset-md-4 " >
            <button type="submit" class="btn btn-primary" >
                {{ __('ADD') }}
            </button>
        </div>
    </div>
</form>
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
