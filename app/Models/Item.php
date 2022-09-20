<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Accessors;

class Item extends Model
{

    protected $fillable = [ 'name', 'price', 'code' ];

    protected $attributes = [
        'active' => 1,
    ];

    public function scopeGetItemList($query) {
        return $query->select('id', 'name', 'price', 'code')->where('active', '1');
    }

    public function scopeIsActive($query) {
        return $query->where('active', '1');
    }

    public function scopeDeactiveItem($query, $id) {
        $item = $query->find($id);
        $item->active = 0;
        $item->save();
        return true;
    }
    
}
