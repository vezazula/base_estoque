<?php

use Faker\Generator as Faker;
use App\fornecedor;

$factory->define(App\Produto::class, function (Faker $faker) {
    return [
            'nome' => $faker->word,
            'descricao' => $faker->text,
            'custo' => rand(0, 1000) + (rand(0, 10) / 10),
            'quantidade' => rand(10, 1000),
            'fornecedor_id' => 1
        ];
});
