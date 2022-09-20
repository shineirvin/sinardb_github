<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Accessors;

class PurchaseOrderDetail extends Model
{
    use Accessors;

    protected $table = 'poDetails';

    protected $fillable = [ 'qty', 'itemID', 'price', 'subTotal' ];

    public function scopeGetPODetailsList($query) {
        return $query->select('id', 'qty', 'itemID', 'price', 'subTotal')->get();
    }

    public function items() {
        return $this->hasOne('App\Models\Item', 'id', 'itemID');
    }

}
