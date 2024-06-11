<?php

namespace App\Repositories;


class ConfigurationJsonRepository
{
    protected array $finderOptions = [
        'exclude',
        'notPath',
        'notName',
    ];


    public function __construct(protected string | null $path, protected string | null $preset ) {}

    public function finder() : array
    {
        return collect( $this->get() )->filter( fn( $value, $key ) => in_array( $key, $this->finderOptions ) )->toArray();
    }

    public function fixers() : array
    {
        return $this->get()[ 'fixers' ] ?? [];
    }

    public function rules() : array
    {
        return $this->get()[ 'rules' ] ?? [];
    }

    public function cacheFile() : string | null
    {
        return $this->get()[ 'cache-file' ] ?? null;
    }

    public function preset() : string
    {
        return $this->preset ?: ( $this->get()[ 'preset' ] ?? 'laravel' );
    }

    protected function get() : array
    {
        if( ! is_null($this->path) && $this->fileExists( ( string ) $this->path ) )
        {
            return tap( json_decode( file_get_contents( $this->path ), true ), function( $configuration )
            {
                if( ! is_array( $configuration ) ) abort( 1, sprintf( 'The configuration file [%s] is not valid JSON.', $this->path ) );
            } );
        }

        return [];
    }

    protected function fileExists( string $path ) : bool
    {
        return match( true )
        {
            str_starts_with( $path, 'http://' ) || str_starts_with( $path, 'https://' ) => str_contains( get_headers( $path )[ 0 ], '200 OK' ),
            default => file_exists( $path )
        };
    }
}
