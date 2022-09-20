<?php namespace App\Traits;

use Carbon\Carbon;

trait Accessors
{
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function getPriceAttribute($value)
    {
        return "Rp " . number_format($value, 0, ',', '.');
    }

    public function getSubTotalAttribute($value)
    {
        return "Rp " . number_format($this->attributes['subTotal'], 0, ',', '.');
    }

    public function getGrandTotalAttribute($value)
    {
        return "Rp " . number_format($this->attributes['grandTotal'], 0, ',', '.');
    }

    public function getCustEmailAttribute($value) {
        return substr($value, 0, 15) . "...";
    }

    public function scopeDeactiveItem($query, $id) {
        $object = $query->find($id);
        $object->active = 0;
        $object->save();
        return true;
    }

}