<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Commands\DefaultCommand;
use App\Actions\FixCode;
use App\Actions\ElaborateSummary;


class CommandsServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->bindMethod( [ DefaultCommand::class, 'handle' ], fn( $command ) => $command->handle( resolve( FixCode::class ), resolve( ElaborateSummary::class ) ) );
    }
}
