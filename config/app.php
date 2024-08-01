<?php

return [

    'name' => 'Laravel Flint',

    'version' => '0.1.1',

    'env' => 'development',

    'providers' => [

        App\Providers\ActionsServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\CommandsServiceProvider::class,
        App\Providers\RepositoriesServiceProvider::class,

    ]

];
