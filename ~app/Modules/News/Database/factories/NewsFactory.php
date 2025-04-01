<?php


use Modules\News\Entities\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
  return [
    'title' => $faker->text(50),
    'slug' => $faker->slug,
    'date' => $faker->date(),
    'description' => $faker->realText(400),
    'detail' => $faker->realText(5000),
    'cover' => $faker->imageUrl($width = 900, $height = 450),
    'type_id' => rand(1, 4),
    'view' => 1
  ];
});
