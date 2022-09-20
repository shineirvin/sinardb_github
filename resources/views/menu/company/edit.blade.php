@extends('app')

@section('content')
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h2>Data Toko</h2>
        <p class="lead">Ubah Data Toko</p>
    </div>
    <form method="POST" action="{{ action('CompanyController@update', [$company->id]) }}">
        {{ method_field("PATCH") }}
        {{ csrf_field() }}
        <div class="row form-group has-feedback">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="firstName">Customer name</label>
                <input type="text" class="form-control has-feedback-left" name="name" placeholder="PT Surya Jaya Teknindo" value="{{ $company->name }}" required="">
                <span class="fa fa-shopping-cart form-control-feedback left" aria-hidden="true"></span>
                @if (count($errors) > 0)
                    @foreach ($errors->get('name') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="lastName">Email</label>
                <input type="text" class="form-control has-feedback-left" name="email" placeholder="" value="{{ $company->email }}" required="">
                <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                @if (count($errors) > 0)
                    @foreach ($errors->get('email') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="lastName">Telepon</label>
                <input type="text" class="form-control has-feedback-left" name="phone" placeholder="" value="{{ $company->phone }}" required="">
                <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                @if (count($errors) > 0)
                    @foreach ($errors->get('phone') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                <label for="lastName">Handphone</label>
                <input type="text" class="form-control has-feedback-left" name="phone2" placeholder="" value="{{ $company->phone2 }}" required="">
                <span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span><span class="fa fa-mobile form-control-feedback left" aria-hidden="true"></span>
                @if (count($errors) > 0)
                    @foreach ($errors->get('phone2') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-3">
                <label for="lastName">Address</label>
                <textarea type="text" class="form-control has-feedback-left" name="address" placeholder="" value="{{ $company->address }}" required="">{{ $company->address }}</textarea>
                <span class="fa fa-location-arrow form-control-feedback left" aria-hidden="true"></span>
                @if (count($errors) > 0)
                    @foreach ($errors->get('address') as $error)
                        <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                    @endforeach
                @endif
            </div>
        </div>
        <br>
        <br>
        <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
    </form>
@endsection