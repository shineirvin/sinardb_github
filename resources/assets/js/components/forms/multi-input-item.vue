<template>
    <div>
        <div class="row">
            <div :class="colClass" v-for="(data, index) in forms">
                <label>{{ data.name }}</label>
                <input class="form-control has-feedback-left" v-model="code" :placeholder="data.placeholder" :id="data.id" v-if="data.id == 'code'">
                <span v-if="data.id == 'code'" class="fa fa-wrench form-control-feedback left" aria-hidden="true"></span>

                <input class="form-control has-feedback-left" v-model="name" :placeholder="data.placeholder" :id="data.id" v-if="data.id == 'name'">
                <span v-if="data.id == 'name'" class="fa fa-user form-control-feedback left" aria-hidden="true"></span>

                <input class="form-control has-feedback-left" v-model="address" :placeholder="data.placeholder" :id="data.id" v-if="data.id == 'address'">
                <span v-if="data.id == 'address'" class="fa fa-home form-control-feedback left" aria-hidden="true"></span>

                <input class="form-control has-feedback-left" v-model="price" :placeholder="data.placeholder" :id="data.id" @blur="formatPrice(price)" v-if="data.id == 'price'">
                <span v-if="data.id == 'price'" class="fa fa-money form-control-feedback left" aria-hidden="true"></span>
                <div class="invalid-feedback" style="color: red">{{ itemerrormsg }}</div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <label> Harga </label>
                <input class="form-control selectedPrice" v-model="price" required id="price" @blur="formatPrice(price)">
                <div class="invalid-feedback" style="color: red">{{ priceerrormsg }}</div>
            </div> -->
        </div>
        <br>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                itemName: '',
                price: '',
                name: '',
                address: '',
                code: '',
                
                colClass: ''
            }
        },
        props: ['forms', 'itemerrormsg', 'priceerrormsg'],
        mounted() {
            console.log(this.forms.length);
            let totalCol = 12 / parseInt(this.forms.length);
            console.log(totalCol);
            this.colClass = `col-lg-${ totalCol } col-md-${ totalCol } col-sm-${ totalCol } col-xs-${ totalCol } form-group has-feedback`;
        },
        methods: {
            formatPrice: function(value) {
                let val = this.addCommas(value.replace(/\D/g,''));
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
        },
    }
</script>