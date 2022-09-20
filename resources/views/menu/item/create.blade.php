@extends('app')

@section('content')
    <div id="apps">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h2>Form Item</h2>
        </div>
        <div>
            {{ csrf_field() }}
            <a @click="addRow()" class="btn btn-primary"> <span class="fa fa-plus"></span> Tambah Barang </a>
            <a @click="removeRow()" class="btn btn-primary"> <span class="fa fa-minus"></span> Hapus Barang </a>
            <br>
            <div v-for="row in rows">
                <multi-input-item
                    :forms="forms"
                    :itemerrormsg="itemErrorMsg"
                    :priceerrormsg="priceErrorMsg"
                    ref="itemList"
                ></multi-input-item>
            </div>
            {{-- <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName">Nama Item</label>
                    <input type="text" class="form-control"  name="name" placeholder="Stunt Bolt" value="" required="">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName">Harga</label>
                    <input type="text" class="form-control" name="price" v-model="price" placeholder="" value="" required="" @blur="formatPrice(price)">
                </div>
                <br>
                <br>
                <br>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Submit</button>
            </div> --}}
            <br>
            <button class="btn btn-primary btn-lg btn-block" @click="submit" type="submit">Submit</button>
        </div>
    </div>
    <script>
        const vm = new Vue({
            el: '#apps',
            data: {
                rows: [{}],
                price: 0,
                @if($errors->count() != 0)
                    @foreach ($errors->get('name') as $error)
                        itemErrorMsg: "{!! isset($error) ? $error : "" !!}",
                    @endforeach
                    @foreach ($errors->get('price') as $error)
                        priceErrorMsg: "{!! isset($error) ? $error : "" !!}",
                    @endforeach
                @else
                    itemErrorMsg: "",
                    priceErrorMsg: "",
                @endif

                forms: [
                    {
                        "id": 'code',
                        "name": 'Kode Barang',
                        "placeholder": "Kode Barang"
                    },
                    {
                        "id": "name",
                        "name": 'Nama Barang',
                        "placeholder": "Stunt Bolt"
                    },
                    {
                        "id": 'price',
                        "name": 'Harga',
                        "placeholder": "Amount"
                    }
                ]

            },
            components: ['multi-input-item'],
            methods: {
                addRow: function() {
                    this.rows.push({});
                },
                removeRow: function() {
                    let length = this.rows.length - 1;
                    this.rows.splice(length, 1);
                    this.getChildTotals();
                },
                formatPrice: function(value) {
                    let val = this.addCommas(value);
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
                submit() {
                    // console.log(this.getAllSelectedData('itemName'));
                    // console.log(this.getAllSelectedData('price'));
                    let selectedItemID = this.getAllSelectedData('name');
                    let price = this.getAllSelectedData('price');
                    let code = this.getAllSelectedData('code');

                    let details = _.zipObject(selectedItemID, _.zip(code, price));
                    console.log(details);
                    let data = {
                        'details': details,
                    };

                    console.log(data);
                    return this.ajaxPOST( '{!! url('items') !!}', data)
                        .then(function (response) {
                            location.reload();
                        });
                },
               ajaxPOST(url, params) {
                    let self = this;
                    if (this.processing == true) {
                        return;
                    } 
                    this.processing = true;
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            method: 'POST',
                            url: url,
                            data: params,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                self.processing = false;
                                resolve(data);
                            },
                            error: function (xhr, status) {
                                self.processing = false;
                                reject(status);
                            }
                        })
                    })
                },
                getAllSelectedData(data) {
                    let selectedItemArray = [];
                    this.$refs.itemList.forEach( (child) => {
                        console.log(child[data]);
                        if(child[data] == '') {
                            swal({
                                title: "Error!",
                                text: "Semua form harus diisi!",
                                type: "error",
                            });
                            return;
                        } else if(data == 'totalPrice') {
                            selectedItemArray.push(unReadNumber(child[data]));
                        } else {
                            selectedItemArray.push(child[data]);
                        }
                    });
                    return selectedItemArray;
                },
            }
        });
    </script>
@endsection