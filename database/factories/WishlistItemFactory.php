<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\WishlistItem;
use Faker\Generator as Faker;

$factory->define(WishlistItem::class, function (Faker $faker) {
    return [
        'name' => $faker->realText(150)
    ];
});
