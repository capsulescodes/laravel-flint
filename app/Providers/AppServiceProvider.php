<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\EventDispatcher\EventDispatcher;


class AppServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton( ErrorsManager::class, fn() => new ErrorsManager() );
        $this->app->singleton( EventDispatcher::class, fn() => new EventDispatcher() );
    }
}
