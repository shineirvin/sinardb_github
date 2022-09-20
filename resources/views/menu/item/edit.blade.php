@extends('app')

@section('content')
    <div id="app">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h2>Item Form</h2>
            <p class="lead">Item List</p>
        </div>
        <form method="POST" action="{{ action('ItemController@update', [$itemData->id]) }}">
            {{ method_field("PATCH") }}
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="firstName">Kode Barang</label>
                    <input type="text" class="form-control"  name="code" placeholder="Stunt Bolt" value="{{ $itemData->code }}" required="">
                    @if (count($errors) > 0)
                        @foreach ($errors->get('name') as $error)
                            <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <label for="firstName">Item name</label>
                    <input type="text" class="form-control"  name="name" placeholder="Stunt Bolt" value="{{ $itemData->name }}" required="">
                    @if (count($errors) > 0)
                        @foreach ($errors->get('name') as $error)
                            <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-4 mb-3">
                    <label for="lastName">Price</label>
                    <input type="text" class="form-control" name="price" v-model="price" placeholder="" value="" required="" @blur="formatPrice(price)" @focus="deformatPrice(price)">
                    @if (count($errors) > 0)
                        @foreach ($errors->get('price') as $error)
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
    </div>
    <script>
        const vm = new Vue({
            el: '#app',
            created() {
                this.formatPrice( {{ $itemData->price}} );
            },
            data: {
                price: '',
            },
            methods: {
                formatPrice: function(value) {
                    let digitOnly = value.toString().replace(/[^0-9]/gi, '');
                    let val = this.addCommas(digitOnly);
                    this.price = val;
                },
                addCommas: function(nStr) {
                    nStr += '';
                    var x = nStr.split('.');
                    var x1 = x[0];
                    var x2 = x.length > 1 ? '.' + x[1] : '';
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                            x1 = x1.replace(rgx, '$1' + '.' + '$2');
                    }
                    return x1 + x2;
                },
                deformatPrice: function() {
                    let digitOnly = this.price.toString().replace(/[^0-9]/gi, '');
                    this.price = digitOnly;
                }
            }
        });
    </script>
@endsection