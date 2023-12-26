<?php

use App\Commands\DefaultCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Foundation\Console\Kernel;
use Test\TestCase;


uses( TestCase::class )->in( 'Feature' );


function run( string $command, array $arguments ) : array
{
    $arguments = array_merge( [ '--test' => true ], $arguments );

    if( isset( $arguments[ 'path' ] ) ) $arguments[ 'path' ] = [ $arguments[ 'path' ] ];

    $commandInstance = match( $command )
    {
        'default' => resolve( DefaultCommand::class ),
    };

    $input = new ArrayInput( $arguments, $commandInstance->getDefinition() );

    $output = new BufferedOutput( BufferedOutput::VERBOSITY_VERBOSE );

    app()->singleton( InputInterface::class, fn() => $input );
    app()->singleton( OutputInterface::class, fn() => $output );

    $statusCode = resolve( Kernel::class )->call( $command, $arguments, $output );

    $output = preg_replace( '#\\x1b[[][^A-Za-z]*[A-Za-z]#', '', $output->fetch() );

    return [ $statusCode, $output ];
}
