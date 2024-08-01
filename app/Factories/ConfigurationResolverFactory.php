<?php

namespace App\Factories;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repositories\ConfigurationJsonRepository;
use PhpCsFixer\Console\ConfigurationResolver;
use PhpCsFixer\Config;
use App\Project;
use PhpCsFixer\ToolInfo;
use ArrayIterator;


class ConfigurationResolverFactory
{
    public static array $presets = [
        'none',
        'laravel',
        'per',
        'psr12',
        'symfony',
        'empty',
    ];

    public static function fromIO( InputInterface $input, OutputInterface $output ) : array
    {
        $path = Project::paths( $input );

        $localConfiguration = resolve( ConfigurationJsonRepository::class );

        $preset = $localConfiguration->preset();

        if( ! in_array( $preset, static::$presets ) ) abort( 1, 'Preset not found.' );

        $resolver = new ConfigurationResolver(
            new Config('default'),
            [
                'allow-risky' => 'yes',
                'config' => implode( DIRECTORY_SEPARATOR, [ dirname( __DIR__, 2 ), 'resources', 'presets', sprintf( '%s.php', $preset ) ] ),
                'diff' => $output->isVerbose(),
                'dry-run' => $input->getOption( 'test' ) || $input->getOption('bail'),
                'path' => $path,
                'path-mode' => ConfigurationResolver::PATH_MODE_OVERRIDE,
                'cache-file' => $input->getOption( 'cache-file' ) ?? $localConfiguration->cacheFile() ?? implode( DIRECTORY_SEPARATOR, [ realpath( sys_get_temp_dir() ), md5( app()->isProduction() ? implode( '|', $path ) : ( string ) microtime() ) ] ),
                'stop-on-violation' => $input->getOption( 'bail' ),
                'verbosity' => $output->getVerbosity(),
                'show-progress' => 'true',
            ],
            Project::path(),
            new ToolInfo()
        );

        $totalFiles = count( new ArrayIterator( iterator_to_array( $resolver->getFinder() ) ) );

        return [ $resolver, $totalFiles ];
    }
}
