<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';   
    protected $fillable = [ 'name', 'address', 'email', 'phone1', 'phone2' ];

}
