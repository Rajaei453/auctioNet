@extends('layouts.userMaster')

@section('content')
<h3>Reviews For {{$provider->name}}</h3>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">user</th>
        <th scope="col">content</th>
        <th scope="col">stars</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reviews as $review)
        <tr>
        <th scope="row">{{$review->id}}</th>
        <td>{{$review->user->name}}</td>
        <td>{{$review->content}}</td>
        <td>{{$review->stars}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title">Add Review</h4>
    </div>
    <form class="forms-sample"  method="POST" enctype="multipart/form-data" action="{{url('users/addReview'.'/'.$jwt_token.'/'.$provider->id)}}">
        @csrf

        <div class="modal-body">
            <div class="row mb-3">
                <label for="content" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Comments') }}</h5></label>
                <div class="col-md-6">
                    <input id="content" type="text" class="form-control" name="content" value="{{ old('content') }}"   >
                    @error('content')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <label for="stars" class="col-md-4 col-form-label text-md-end"><h5>{{ __('Stars') }}</h5></label>
            <div class="col-md-6">
                <select name="stars" id="stars" class="form-select" >
                    <option value="1">1 </option>
                    <option value="2">2 </option>
                    <option value="3">3 </option>
                    <option value="4">4 </option>
                    <option value="5">5 </option>
                </select>
            </div>
        </div>

        <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>

</div>


@endsection
