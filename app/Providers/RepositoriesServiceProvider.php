<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ConfigurationJsonRepository;
use Symfony\Component\Console\Input\InputInterface;
use App\Project;
use App\Contracts\PathsRepository;
use App\Repositories\GitPathsRepository;


class RepositoriesServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->singleton( ConfigurationJsonRepository::class, function()
        {
            $input = resolve( InputInterface::class );

            $config = $input->getOption('config') ?: Project::path() . '/flint.json';

            return new ConfigurationJsonRepository( $input->getOption( 'no-config' ) ? null : $config, $input->getOption( 'preset' ) );
        });

        $this->app->singleton( PathsRepository::class, fn() => new GitPathsRepository( Project::path() ) );
    }
}
