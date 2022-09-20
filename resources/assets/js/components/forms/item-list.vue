<template>
    <div>
        <div class="row">
            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                <label> Qty </label>
                <input class="form-control selectedQty" v-model="qty" @keyup="sum()">
            </div>
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <label>Item</label>
                <select2
                    v-model="selectedItemID"
                    @change="changePrice($event)"
                    :options="options"   
                >
                </select2>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <label> Harga </label>
                <input class="form-control selectedPrice" v-model="totalPrice" disabled>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                qty: 1,
                selectedItemID: '',
                price: 0,
                totalPrice: 0,
                options: this.itemsarray,
            }
        },
        mounted() {
            if(this.row.itemID != undefined) {
                this.qty = this.row.qty;
                this.selectedItemID = this.row.itemID;
                this.changePrice();
            }
        },
        props: ['items', 'itemsarray', 'row'],
        methods: {
            changePrice() {
                this.price = this.items[this.selectedItemID]['price'];
                this.sum();
            },
            sum() {
                this.totalPrice = readNumber(this.qty * this.price);
                this.$parent.getChildTotals();
            }
        },
    }
</script>