@extends('app')

@section('content')
    <div id="apps">
        <div class="x_panel">
            <div class="x_content">
                <h1> Transaksi </h1>
                <hr>
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" @submit.prevent>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                            <label for="nopo">No Transaksi</label>
                            <input type="text" class="form-control" name="nopo" placeholder="No PO" value="" required="" v-model="purchaseOrderID" disabled="disabled">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <label for="date">Tanggal</label>
                            <div class="col-md-11 xdisplay_inputx form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left" id="doDate" ref="date">
                                <span class="fa fa-calendar-o form-control-feedback left"></span>
                            </div>    
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                            <label>Customer</label>
                            <select2
                                v-model="selectedCustomerID"
                                :options="customerList"   
                            >
                            </select2>
                        </div>
                    </div>
                    <br>
                    <div v-for="row in rows">
                        <item-list
                            :row.sync="row"
                            :itemsarray.sync="itemArray"
                            :items.sync="itemData"
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
                    <button class="btn btn-primary btn-lg btn-block" @click="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
        <script>
            var app = new Vue({
                el: "#apps",
                data: {
                    purchaseOrderID: '{{ $purchaseOrder->po }}',
                    customerList: {!! $customerList !!},
                    selectedCustomerID: {{ $purchaseOrder->customers->id }},
                    date: "{{ $purchaseOrder->date }}",

                    rows: {!! $purchaseOrderDetail !!},
                    itemArray: {!! $itemArray !!},
                    itemData: {!! $itemData !!},
                    terbilang: '',
                    total: 0,
                },
                mounted() {
                    let self = this;
                    
                    $('#doDate').daterangepicker({
                        singleDatePicker: true,
                        startDate: moment(self.date, "DD-MM-YYYY"),
                        locale: {
                            format: 'DD/MM/YYYY',					
                        },
                        singleClasses: "picker_2"
                    }, function(start, end, label) {
                        console.log(start.toISOString(), end.toISOString(), label);
                    });
                },
                methods: {
                    submit() {
                        let selectedItemID = this.getAllSelectedData('selectedItemID');
                        let qty = this.getAllSelectedData('qty');
                        let totalPrice = this.getAllSelectedData('totalPrice');
                        console.log(this.getAllSelectedData('selectedItemID'));
                        console.log(this.getAllSelectedData('qty'));
                        console.log(this.getAllSelectedData('totalPrice'));
                        console.log(this.purchaseOrderID);
                        console.log(this.selectedCustomerID);
                        console.log(this.$refs.date.value);
                        console.log(unReadNumber(this.total));
                        console.log(this.terbilang);

                        let details = _.zipObject(selectedItemID, _.zip(qty, totalPrice));

                        let data = {
                            'po': this.purchaseOrderID,
                            'customerID': this.selectedCustomerID,
                            'poDate': moment(this.$refs.date.value, "DD/MM/YYYY").format('YYYY-MM-DD HH:mm:ss'),
                            'grandTotal': unReadNumber(this.total),
                            'terbilang': this.terbilang,
                            'details': details,
                        };

                        console.log(data);
                        return this.ajaxPOST( '{!! action('PurchaseOrderController@update', $id) !!}', data)
                            .then(function (response) {
                                window.open("http://sinardb.host/po/{!! $id !!}",'_blank');
                                location.reload();
                            });
                    },
                    ajaxPOST(url, params) {
                        let self = this;
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                method: 'PUT',
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
                    getChildTotals() {
                        let total = 0;
                        this.$refs.itemList.forEach( (child) => {
                            total = total + unReadNumber(child.totalPrice);
                        });
                        this.total = readNumber(total);
                        this.terbilang = terbilang(total);
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
                }
            });
                
            
        </script>
    @endpush
@endsection