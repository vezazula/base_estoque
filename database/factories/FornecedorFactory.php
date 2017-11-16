<?php

use Faker\Generator as Faker;
use Faker\Provider\pt_BR\Company;

$factory->define(App\Fornecedor::class, function (Faker $faker) {
    return [
            'nome' => $faker->company,
            'cnpj' => '97377559000177',
            'endereco' => $faker->address,
        ];
});
