@extends('app')

@section('content')

    @component('../../components/pageTitle')
        @slot('title', "Transaksi")
    @endcomponent

    <div class="row" id="apps">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <br>
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" @submit.prevent>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <label for="nopo">No PO</label>
                                <input type="text" class="form-control" name="nopo" placeholder="No PO" value="" required="" v-model="nopo" readonly>
                                <div class="invalid-feedback" style="color: red">@{{ nopoError }}</div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <label for="date">Tanggal</label>
                                {{-- <datepicker :bootstrap-styling="true" v-model="date" name="date" placeholder="Tanggal"></datepicker> --}}
                                <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                                    <input type="text" name="date" class="form-control has-feedback-left" id="single_cal2" ref="date">
                                    <span class="fa fa-calendar-o form-control-feedback left"></span>
                                </div>
                                <div class="invalid-feedback" style="color: red">@{{ dateError }}</div>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <label>Customer</label>
                                <select2
                                    v-model="custID"
                                    :options="customerList"
                                >
                                <div class="invalid-feedback" style="color: red">@{{ custIDError }}</div>
                            </div>
                        </div>
                        <br>
                        <a @click="addRow()" class="btn btn-primary"> <span class="fa fa-plus"></span> Tambah Barang </a>
                        <a @click="removeRow()" class="btn btn-primary"> <span class="fa fa-minus"></span> Hapus Barang </a>
                        <div v-for="row in rows">
                            <item-list
                                :row="row"
                                :itemsarray="itemArray"
                                :items="itemData"
                                ref="itemList"
                            ></item-list>
                        </div>

                        <br>
                        <br>
                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 offset-1">
                                <input class="form-control" placeholder="Terbilang" readonly v-model="terbilang">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <input class="form-control" placeholder="Total Harga" readonly v-model="total">
                            </div>
                        </div>

                        <hr class="mb-4">
                        <button class="btn btn-primary btn-lg btn-block" @click="submit" :disabled="processing">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var app = new Vue({
            el: "#apps",
            data: {
                rows: [],
                customerList: {!! $customerList !!},
                itemArray: {!! $itemArray !!},
                itemData: {!! $itemData !!},
                total: 0,
                terbilang: '',
                
                nopo: "{!! $lastIDplus1 !!}",
                custID: '',

                nopoError: '',
                dateError: '',
                custIDError: '',

                processing: false
            },
            methods: {
                addRow: function() {
                    this.rows.push({});
                },
                removeRow: function() {
                    let length = this.rows.length - 1;
                    this.rows.splice(length, 1);
                    this.getChildTotals();
                },
                getChildTotals() {
                    let self = this;
                    let total = 0;
                    let selectedItemID = [];
                    let index = [];
                    this.$refs.itemList.forEach( (child) => {
                        //selectedItemID.push(child.selectedItemID);
                        total = total + unReadNumber(child.totalPrice);
                    });

                    // _.forEach(_.uniq(selectedItemID), function(id) {
                    //     self.itemArray.splice(_.findIndex(self.itemArray, function(o) { return o.id == id; }), 1);
                    // });
                    
                    this.total = readNumber(total);
                    this.terbilang = terbilang(total);
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
                submit() {
                    if(this.$refs.itemList == undefined) {
                        swal({
                            title: "Error!",
                            text: "Anda harus memasukan list barang",
                            type: "error",
                        });
                        return;
                    }
                    
                    this.getChildTotals();
                    let selectedItemID = this.getAllSelectedData('selectedItemID');
                    let qty = this.getAllSelectedData('qty');
                    let totalPrice = this.getAllSelectedData('totalPrice');
                    console.log(this.getAllSelectedData('selectedItemID'));
                    console.log(this.getAllSelectedData('qty'));
                    console.log(this.getAllSelectedData('totalPrice'));
                    console.log(this.nopo);
                    console.log(this.custID);
                    console.log(this.$refs.date.value);
                    console.log(unReadNumber(this.total));
                    console.log(this.terbilang);
                    if(this.getAllSelectedData('selectedItemID') == "") {
                        swal({
                            title: "Error!",
                            text: "Anda harus memilih list barang",
                            type: "error",
                        });
                        return;
                    } else if(this.nopo == ''|| this.custID == '' || this.date == '') {
                        swal({
                            title: "Error!",
                            text: "Silahkan lengkapi form yang disediakan",
                            type: "error",
                        });
                        return;
                    }

                    let details = _.zipObject(selectedItemID, _.zip(qty, totalPrice));

                    let data = {
                        'po': this.nopo,
                        'customerID': this.custID,
                        'doDate': moment(this.$refs.date.value, "DD/MM/YYYY").format('YYYY-MM-DD HH:mm:ss'),
                        'grandTotal': unReadNumber(this.total),
                        'terbilang': this.terbilang,
                        'details': details,
                    };

                    console.log(data);
                    
                    return this.ajaxPOST( '{!! action('PurchaseOrderController@store') !!}', data)
                        .then(function (response) {
                            window.open(`http://sinardb.host/po/${ response }`,'_blank');
                            location.reload();
                        });

                },
                getAllSelectedData(data) {
                    let selectedItemArray = [];
                    this.$refs.itemList.forEach( (child) => {
                        if(data == 'totalPrice') {
                            selectedItemArray.push(unReadNumber(child[data]));
                        } else {
                            selectedItemArray.push(child[data]);
                        }
                    });
                    return selectedItemArray;
                },
            },
        });
    </script>
@endsection