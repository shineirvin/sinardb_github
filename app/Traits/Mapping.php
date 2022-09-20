<?php namespace App\Traits;

use Carbon\Carbon;
use App\Models\Account;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

trait Mapping {

    public function mappingTransform($data, $from) {
        $data->map(function ($item, $key) use($from) {
            if($from == 'po') {
                $item->customerName = Customer::find($item->customerID)->name;
            }
            $item->date = $this->formatedDate($item->date);
        });
    }

    public function formatedDate($date) {
        return Carbon::parse($date)->format('d-m-Y');
    }

    public function priceTransform($data) {
        $data->map(function ($item, $key) {
            $item->price = "Rp " . number_format($item->price, 0, ',', '.');
        });
    }

    public function listTransform($collection) {
        $keyed = $collection->mapWithKeys(function ($data) {
            return [$data->id => $data];
        });

        return collect($keyed);
    }

    public function getEditButton($table, $from) {
        $table->map(function ($item, $key) use ($from) {
            if($from == 'po') {
                $item->action =  $this->viewButtonAttr($item->id, $from) . $this->editButtonAttr($item->id, $from) . $this->deleteButtonAttr($item->id, $from);
            } else {
                $item->action = $this->editButtonAttr($item->id, $from) . $this->deleteButtonAttr($item->id, $from);
            }
        });
    }

    public function viewButtonAttr($id, $from) {
        return "<a href='$from/$id' class='btn btn-primary'> <i class='fa fa-eye'></i> </a>";
    }
    public function editButtonAttr($id, $from) {
        return "<a href='$from/$id/edit' class='btn btn-warning'> <i class='fa fa-edit'></i> </a>";
    }
    public function deleteButtonAttr($id, $from) {
        return "<a onclick='deleteData($id, event)' class='btn btn-danger'> <i class='fa fa-trash'></i> </a>";
    }

    public function select2Transform($data) {
        foreach($data as $key => $item) {
            $itemObject[$key]['id'] = $item->id;
            $itemObject[$key]['text'] = $item->name;
        }
        return $itemObject;
    }

}