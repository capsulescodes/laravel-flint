<?php

namespace App;

use App\Contracts\PathsRepository;
use Symfony\Component\Console\Input\InputInterface;


class Project
{
    public static function paths( InputInterface $input ) : array
    {
        if( $input->getOption( 'dirty' ) ) return static::resolveDirtyPaths();

        return $input->getArgument( 'path' );
    }

    public static function path() : string
    {
        return getcwd();
    }

    public static function resolveDirtyPaths() : array
    {
        $files = app( PathsRepository::class )->dirty();

        if( empty( $files ) ) abort( 0, 'No dirty files found.' );

        return $files;
    }
}
