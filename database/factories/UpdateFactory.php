<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Ecabs\Update::class, function (Faker $faker) {
    return [
        'category' => randomElement($array = array ('Blog','Entertainment','News')),
        'title' => $faker->text(200) ,
        'content_path' =>'20201.xml',
        'images_path' => 'a:2:{i:0;s:10:"202011.png";i:1;s:10:"202012.png";}', 
        'status' => 1,
    ];
});

