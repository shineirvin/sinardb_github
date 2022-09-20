<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('menu.po.index');
});

Route::resource('customers', 'CustomerController');
Route::get('customersData', 'CustomerController@customersData');

Route::resource('items', 'ItemController');
Route::get('itemsData', 'ItemController@itemsData');

Route::resource('company', 'CompanyController');

Route::resource('po', 'PurchaseOrderController');
Route::get('poData', 'PurchaseOrderController@poData');

Route::post('print', 'PurchaseOrderController@print');