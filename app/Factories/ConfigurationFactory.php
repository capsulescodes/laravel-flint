<?php

namespace App\Factories;

use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use App\Repositories\ConfigurationJsonRepository;
use PhpCsFixer\Finder;
use App\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


class ConfigurationFactory
{
    protected static $notName = [
        '_ide_helper_actions.php',
        '_ide_helper_models.php',
        '_ide_helper.php',
        '.phpstorm.meta.php',
        '*.blade.php',
    ];

    protected static $exclude = [
        'bootstrap/cache',
        'build',
        'node_modules',
        'storage',
    ];


    public static function preset( array $rules, array $fixers = [] ) : ConfigInterface
    {
        return ( new Config() )
            ->setParallelConfig( ParallelConfigFactory::detect() )
            ->setFinder( self::finder() )
            ->registerCustomFixers( array_merge( self::fixers(), $fixers ) )
            ->setRules( array_merge( $rules, resolve( ConfigurationJsonRepository::class )->rules() ) )
            ->setRiskyAllowed( true )
            ->setUsingCache( true );
    }

    public static function finder() : Finder
    {
        $localConfiguration = resolve( ConfigurationJsonRepository::class );

        $finder = Finder::create()->notName( static::$notName )->exclude( static::$exclude )->ignoreDotFiles( true )->ignoreVCS( true );

        foreach( $localConfiguration->finder() as $method => $arguments )
        {
            if( ! method_exists( $finder, $method ) ) abort( 1, sprintf( 'Option [%s] is not valid.', $method ) );

            $finder->{$method}($arguments);
        }

        return $finder;
    }

    public static function fixers() : array
    {
        $localConfiguration = resolve( ConfigurationJsonRepository::class );

        if( empty( $localConfiguration->fixers() ) ) return [];

        if( ! file_exists( Project::path() . '/vendor/autoload.php' ) ) abort( 1, sprintf( 'Project composer autoload file not found.' ) );

        require Project::path() . '/vendor/autoload.php';

        $classmap = Collection::make( include Project::path() . '/vendor/composer/autoload_classmap.php' );

        $fixers = [];

        foreach( $localConfiguration->fixers() as $name )
        {
            $classes = $classmap->keys()->filter( fn( $class ) => Str::of( $class )->startsWith( $name ) );

            if( $classes->isEmpty() ) abort( 1, sprintf( 'No fixers found for name [%s].', $name ) );

            foreach( $classes as $class ) $fixers[] = new $class();
        }

        return $fixers;
    }
}
