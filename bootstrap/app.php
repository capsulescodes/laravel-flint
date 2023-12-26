<?php

$app = new LaravelZero\Framework\Application( dirname( __DIR__ ) );

$app->singleton( Illuminate\Contracts\Console\Kernel::class, App\Kernel::class );

$app->singleton( Illuminate\Contracts\Debug\ExceptionHandler::class, Illuminate\Foundation\Exceptions\Handler::class );

return $app;
