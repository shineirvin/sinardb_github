@extends('app')

@section('content')
    <div id="apps">
        <div class="x_panel">
            <div class="x_content">
                <section class="sheet padding-10mm" style="width: 255mm;">
                    <h1> Faktur </h1>
                    <hr>
                    @foreach($purchaseOrderDetail->chunk(10) as $key => $data)
                        <button @click="printPO({!! $key !!})" class="btn btn-default"> Print page </button>
                        @break
                    @endforeach
                    <div class="row" style="padding: 13px;">
                        <div class="col-md-7">
                            <label>No Transaksi &nbsp &nbsp  :   {{ $purchaseOrder->po }}</label>
                        </div>
                        <div class="col-md-5 text-center" style="border: 2px solid #000080; border-radius: 20px; padding: 10px;">
                            <label>Jakarta, @{{ purchaseOrderDate }}</label>
                            <br>
                            <label>Kepada</label>
                            <br>
                            <label> {{ $purchaseOrder->customers->name }} </label>
                            <label> {{ $purchaseOrder->customers->address }} </label>
                        </div>
                    </div>
                    <br>
                    <table class="col-md-12 mb-4" style="border: 3px solid #000080; border-bottom: 1px solid white;">
                        <thead>
                            <tr>
                                <th style="padding: 5px" class="text-center" width="1%">NO</th>
                                <th style="padding: 5px" class="text-center" width="9%">Banyaknya</th>
                                <th class="text-center" width="60%">NAMA BARANG</th>
                                <th class="text-center" width="15%">Harga Satuan</th>
                                <th class="text-center" width="15%">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchaseOrderDetail as $key => $detail)
                                <tr>
                                    <td style="padding: 5px" class="text-center"> {{ $key+1 }} </td>
                                    <td style="padding: 5px" class="text-center">{{ $detail->qty }} @if($detail->qty > 1) PCS @else PC&nbsp&nbsp @endif</td>
                                    <td class="text-left" style="word-break: break-all; padding-left: 2em; border-width: 0px 1px 0px 1px; border-style: solid; border-color: black;"> {{ $detail->items->name }} </td>
                                
                                    <td style="padding-right: 2em; border-width: 0px 1px 0px 1px; border-style: solid; border-color: black;" class="text-right"> {{ $detail->price }} </td>
                                    <td style="padding-right: 2em;" class="text-right"> {{ $detail->sub_total }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" style="border-top: 1px solid black; border-bottom: 1px solid white; border-left: 3px solid white"> Terbilang </td>
                                <td colspan="3" style="border-width: 1px 1px 1px 1px; border-color: black; border-style: solid;"> {{ $purchaseOrder->terbilang }}</td>
                                <td class="text-right" style="padding-right: 2em; border-top: 1px solid black; border-bottom: 1px solid black;"> {{ $purchaseOrder->grand_total }} </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                </section>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#apps",
            data: {
                purchaseOrderDate: "{!! $purchaseOrder->date !!}",
            },
            mounted() {
                moment.locale('id');
                this.purchaseOrderDate = moment(this.purchaseOrderDate, "DD-MM-YYYY").format('LL');
            },
            methods: {
                printPO(key) {
                    console.log(key);
                    let data = {
                        'id': "{!! $id !!}"
                    };
                    
                    return this.ajaxPOST( '{!! url('print') !!}', data)
                        .then(function (response) {
                            console.log(response);
                        });
                },
                ajaxPOST(url, params) {
                    let self = this;
                    return new Promise((resolve, reject) => {
                        $.ajax({
                            method: 'POST',
                            url: url,
                            data: params,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                resolve(data);
                            },
                            error: function (xhr, status) {
                                reject(status);
                            }
                        })
                    })
                },
            }
        });
                
    </script>

@endsection