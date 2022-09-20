<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Item::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'price' => rand(10000, 1000000),
        'code' => rand(10, 100),
        'active' => 1,
    ];

    //factory(App\Models\Customer::class, 50)->create();
});
