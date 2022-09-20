<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Accessors;

class PurchaseOrder extends Model
{
    use Accessors;

    protected $table = 'po';
    
    protected $fillable = [ 'po', 'date', 'customerID', 'grandTotal', 'terbilang' ];

    public static $columns = [ 'po', 'date', 'customerID', 'grandTotal', 'terbilang' ];

    public function scopeGetPOList($query) {
        return $query->select('id', 'po', 'date', 'customerID', 'grandTotal', 'terbilang')->where('active', '1');
    }

    public function poDetails() {
        return $this->hasMany('App\Models\PurchaseOrderDetail', 'poID', 'id');
    }

    public function customers() {
        return $this->hasOne('App\Models\Customer', 'id', 'customerID');
    }

}
