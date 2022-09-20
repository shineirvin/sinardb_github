<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Company::class, function (Faker $faker) {
    return [
        'name' => "Sinar Printing",
        'address' => "Jln Empang Bahagia Raya, Gg 2 No 10, Jelambar grogol pertamburan, Jakarta Barat",
        'phone' => "5601171",
        'phone2' => "081318290832",
        'email' => "printing.sinar@gmail.com",
    ];
});
