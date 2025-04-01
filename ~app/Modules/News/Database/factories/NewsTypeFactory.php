<?php

use Modules\News\Entities\NewsType;
use Faker\Generator as Faker;

$factory->define(NewsType::class, function (Faker $faker) {
  return [
    'title' => $faker->text(10),
    'slug' => $faker->slug,
  ];
});
