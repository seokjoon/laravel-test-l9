<?php

use Faker\Generator as Faker;

$factory->define(App\Vote::class, function (Faker $faker) {

	$up = $faker->randomElements([true, false]);
	$down = !($up);
	$userIds = App\User::pluck('id')->toArray();

    return [
    	'up' => $up ? 1 : null,
		'down' => $down ? 1: null,
		'user_id' => function () use ($faker, $userIds) {
    		return $faker->randomElement($userIds);
		},
    ];
});
