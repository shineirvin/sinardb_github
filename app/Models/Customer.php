<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Accessors;

class Customer extends Model
{
    use Accessors;
    
    protected $fillable = [ 'name', 'address' ];

    protected $attributes = [
        'active' => 1,
    ];

    public function scopeGetCustomerList($query) {
        return $query->select('id', 'name', 'address')->where('active', '1');
    }

    public function scopeIsActive($query) {
        return $query->where('active', '1');
    }

    public function scopeDeactiveItem($query, $id) {
        $customer = $query->find($id);
        $customer->active = 0;
        $customer->save();
        return true;
    }
}
