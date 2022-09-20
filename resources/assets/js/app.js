
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
//require('sweetalert2');

window.Vue = require("vue");

Vue.component("datepicker", require("vuejs-datepicker"));
Vue.component("item-list", require("./components/forms/item-list.vue"));
Vue.component("multi-input-item", require("./components/forms/multi-input-item.vue"));

import Select2 from "v-select2-component";
Vue.component("select2", Select2);

import SweetModal from 'sweet-modal-vue/src/plugin.js'
Vue.use(SweetModal)

window._ = require("lodash");
window.swal = require("sweetalert2");


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });

// Jquery
// window.$ = require('jquery');

// PENDING
// WAITING
// COMPLETED
// FAILED
// SETTLED
// REFUND
// VOID

window.doneColor = '#53BA83';
window.issuedColor = '#B6FFB6';
window.processingColor = '#DDDDFF';
window.failedColor = '#EE9A18';
window.expiredColor = '#A49F9F';
window.waitingColor = '#997ee5';
window.holdColor = '#8ED7FA';
window.noneColor = '#EDF5FA';
window.cancelledColor = '#E4DA71';
window.expiredColor = '#B0ADAD';
window.queueingColor = '#CCCCFF';
window.usedColor = '#DBFFB5';
window.queueColor = '#CCCCFF';
window.requeueingColor = '#CCCCFF';
//window.errorColor = '#FFACAC';
window.errorColor = '#E5D742';
window.reconfirmColor = '#D89393';
window.matchedColor = '#B6FFB6';
window.unmatchedColor = '#E5D742';
window.voidColor = '#B6E5FC';

window.getStatusColor = function(row, data, dataIndex) {
    data == 'DONE'        ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'SUCCESSBOT'  ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'SUCCESSMAN'  ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'USEABLE'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'ENABLED'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'SENT'        ? $('td', row).eq(5).addClass("fa fa-square").css('color', doneColor) :
    data == 'USED'        ? $('td', row).eq(5).addClass("fa fa-square").css('color', usedColor) :
    data == 'FAILED'      ? $('td', row).eq(5).addClass("fa fa-square").css('color', failedColor) :
    data == 'FAILMAN'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', failedColor) :
    data == 'WAITING'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', waitingColor) :
    data == 'ISSUED'      ? $('td', row).eq(5).addClass("fa fa-square").css('color', issuedColor) :
    data == 'CANCELLED'   ? $('td', row).eq(5).addClass("fa fa-square").css('color', cancelledColor) :
    data == 'EXPIRED'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', expiredColor) :
    data == 'PROCESSING'  ? $('td', row).eq(5).addClass("fa fa-square").css('color', processingColor) :
    data == 'QUEUEING'    ? $('td', row).eq(5).addClass("fa fa-square").css('color', queueingColor) :
    data == 'QUEUE'       ? $('td', row).eq(5).addClass("fa fa-square").css('color', queueColor) :
    data == 'REQUEUEING'  ? $('td', row).eq(5).addClass("fa fa-square").css('color', requeueingColor) :
    data == 'ERROR'       ? $('td', row).eq(5).addClass("fa fa-square").css('color', errorColor) :
    data == 'RECONFIRM'   ? $('td', row).eq(5).addClass("fa fa-square").css('color', reconfirmColor) :
    data == 'MATCHED'     ? $('td', row).eq(5).addClass("fa fa-square").css('color', matchedColor) :
    data == 'VOID'        ? $('td', row).eq(5).addClass("fa fa-square").css('color', voidColor) :
    data == 'UNMATCHED'   ? $('td', row).eq(5).addClass("fa fa-square").css('color', unmatchedColor) :
    data == 'HOLD'        ? $('td', row).eq(5).addClass("fa fa-square").css('color', holdColor) : '';
}