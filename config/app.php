<?php

return [

    'name' => 'Flint',

    'version' => '1.0.0',

    'env' => 'development',

    'providers' => [

        App\Providers\ActionsServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\CommandsServiceProvider::class,
        App\Providers\RepositoriesServiceProvider::class,

    ]

];
