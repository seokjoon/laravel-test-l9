<?php

use Faker\Generator as Faker;

$factory->define(App\Attachment::class, function (Faker $faker) {
    return [
    	'filename' => sprintf('$s.%s', str_random(), $faker->randomElement(['jpg', 'png', 'zip', 'tar']))
    ];
});
