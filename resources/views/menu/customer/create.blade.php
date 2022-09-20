@extends('app')

@section('content')
    <div id="apps">        
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
            <h2>Customer Form</h2>
            <p class="lead">Customer List</p>
        </div>
        <div>
            {{ csrf_field() }}
            <a @click="addRow()" class="btn btn-primary"> <span class="fa fa-plus"></span> Tambah Kolom </a>
            <a @click="removeRow()" class="btn btn-primary"> <span class="fa fa-minus"></span> Hapus Kolom </a>
            <br>
            <div v-for="row in rows">
                <multi-input-item
                    :forms="forms"
                    :itemerrormsg="itemErrorMsg"
                    :priceerrormsg="priceErrorMsg"
                    ref="customerList"
                ></multi-input-item>
            </div>
            <br>
            <button class="btn btn-primary btn-lg btn-block" @click="submit" type="submit">Submit</button>
            {{-- <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                    <label for="firstName">Customer name</label>
                    <input type="text" class="form-control" name="name" placeholder="PT Surya Jaya Teknindo" value="" required="">
                    @if (count($errors) > 0)
                        @foreach ($errors->get('name') as $error)
                            <div class="invalid-feedback" style="color: red">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mb-3">
                    <label for="lastName">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="" value="" required="">
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
            </div> --}}
        </div>
    </div>

    <script>
        const vm = new Vue({
            el: '#apps',
            data: {
                rows: [{}],
                @if($errors->count() != 0)
                    @foreach ($errors->get('name') as $error)
                        itemErrorMsg: "{!! isset($error) ? $error : "" !!}",
                    @endforeach
                    @foreach ($errors->get('address') as $error)
                        priceErrorMsg: "{!! isset($error) ? $error : "" !!}",
                    @endforeach
                @else
                    itemErrorMsg: "",
                    priceErrorMsg: "",
                @endif

                forms: [
                    {
                        "id": "name",
                        "name": 'Nama Customer',
                        "placeholder": "Nama Customer"
                    },
                    {
                        "id": 'address',
                        "name": 'Alamat',
                        "placeholder": "Alamat"
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
                submit() {
                    // console.log(this.getAllSelectedData('itemName'));
                    // console.log(this.getAllSelectedData('price'));
                    let name = this.getAllSelectedData('name');
                    let address = this.getAllSelectedData('address');
                    //if(name != 'failed')
                    let details = _.zipObject(name, address);
                    console.log(details);
                    let data = {
                        'details': details,
                    };

                    console.log(data);
                    return this.ajaxPOST( '{!! url('customers') !!}', data)
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
                    this.$refs.customerList.forEach( (child) => {
                        if(child[data] == '' && data == 'name') {
                            swal({
                                title: "Error!",
                                text: "Nama harus diisi!",
                                type: "error",
                            });
                            
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