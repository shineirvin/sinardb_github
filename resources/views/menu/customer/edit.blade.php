@extends('app')

@section('content')
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h2>Customer Form</h2>
        <p class="lead">Ubah Data Customer</p>
    </div>
    <form method="POST" action="{{ action('CustomerController@update', [$custData->id]) }}">
        {{ method_field("PATCH") }}
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="firstName">Customer name</label>
                <input type="text" class="form-control" name="name" placeholder="PT Surya Jaya Teknindo" value="{{ $custData->name }}" required="">
                @if (count($errors) > 0)
                    @foreach ($errors->get('name') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="lastName">Address</label>
                <input type="text" class="form-control" name="address" placeholder="" value="{{ $custData->address }}" required="">
                @if (count($errors) > 0)
                    @foreach ($errors->get('address') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <br>
            <br>
            <br>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
        </div>
    </form>
@endsection