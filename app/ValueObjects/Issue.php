<?php

namespace App\ValueObjects;

use Illuminate\Support\Str;
use NunoMaduro\Collision\Highlighter;
use ReflectionClass;


class Issue
{
    public function __construct(
        protected string $path,
        protected string $file,
        protected string $symbol,
        protected array $payload
    ) {}


    public function file() : string
    {
        return str_replace( $this->path.DIRECTORY_SEPARATOR, '', $this->file );
    }

    public function description( bool $testing ) : string
    {
        if(! empty( $this->payload[ 'source' ] ) ) return $this->payload[ 'source' ]->getMessage();

        return collect( $this->payload[ 'appliedFixers' ] )->map( fn( $appliedFixer ) => $appliedFixer )->implode(', ');
    }

    public function fixable() : bool
    {
        return ! empty( $this->payload[ 'appliedFixers' ] );
    }

    public function code() : string | null
    {
        if( ! $this->fixable() )
        {
            $content = file_get_contents( $this->file );

            $exception = $this->payload[ 'source' ]->getPrevious() ?: $this->payload[ 'source' ];

            return ( new Highlighter() )->highlight( $content, $exception->getLine() );
        }

        return $this->diff();
    }

    public function symbol() : string
    {
        return $this->symbol;
    }

    /**
     * Returns the issue's diff, if any.
     *
     * @return string|null
     */
    protected function diff() : string | null
    {
        if( $this->payload[ 'diff' ] )
        {
            $highlighter = new Highlighter();

            $reflector = new ReflectionClass( $highlighter );

            $diff = $this->payload[ 'diff' ];

            $diff = str( $diff )->explode( "\n" )->map( function( $line )
            {
                if( Str::startsWith( $line, '+' ) )
                {
                    return '//+<fg=green>' . $line . '</>';
                }
                elseif( Str::startsWith( $line, '-' ) )
                {
                    return '//-<fg=red>' . $line . '</>';
                }

                return $line;

            } )->implode( "\n" );

            $method = tap( $reflector->getMethod( 'getHighlightedLines' ) )->setAccessible( true );

            $tokenLines = $method->invoke( $highlighter, "<?php\n" . $diff );

            $tokenLines = array_slice( $tokenLines, 3 );

            $method = tap( $reflector->getMethod( 'colorLines' ) )->setAccessible( true );

            $lines = $method->invoke( $highlighter, $tokenLines );

            $lines = collect( $lines )->map( function( $line )
            {
                if( str( $line )->startsWith( '[90;3m//-' ) ) return str( $line )->replaceFirst( '[90;3m//-', '' );

                if( str( $line )->startsWith( '//-' ) ) return str( $line )->replaceFirst( '//-', '' );

                if( str( $line )->startsWith( '[90;3m//+' ) ) return str( $line )->replaceFirst( '[90;3m//+', '' );

                if( str( $line )->startsWith( '//+' ) ) return str( $line )->replaceFirst( '//+', '' );

                return $line;
            } );

            return '  ' . $lines->implode( "\n  " );
        }
    }
}
