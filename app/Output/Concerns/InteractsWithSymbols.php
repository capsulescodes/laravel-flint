<?php

namespace App\Output\Concerns;

use PhpCsFixer\Error\Error;
use PhpCsFixer\FixerFileProcessedEvent;


trait InteractsWithSymbols
{
    protected array $statuses = [
        FixerFileProcessedEvent::STATUS_INVALID => ['symbol' => '!', 'format' => '<options=bold;fg=red>%s</>'],
        FixerFileProcessedEvent::STATUS_SKIPPED => ['symbol' => '.', 'format' => '<fg=gray>%s</>'],
        FixerFileProcessedEvent::STATUS_NO_CHANGES => ['symbol' => '.', 'format' => '<fg=gray>%s</>'],
        FixerFileProcessedEvent::STATUS_FIXED => [ ['symbol' => '⨯', 'format' => '<options=bold;fg=red>%s</>'], ['symbol' => '✓', 'format' => '<options=bold;fg=green>%s</>'] ],
        FixerFileProcessedEvent::STATUS_EXCEPTION => ['symbol' => '!', 'format' => '<options=bold;fg=red>%s</>'],
        FixerFileProcessedEvent::STATUS_LINT => ['symbol' => '!', 'format' => '<options=bold;fg=red>%s</>'],
    ];


    public function getSymbol( int $status ) : string
    {
        $statusSymbol = $this->statuses[ $status ];

        if( ! isset( $statusSymbol[ 'symbol' ] ) ) $statusSymbol = $this->input->getOption( 'test' ) ? $statusSymbol[ 0 ] : $statusSymbol[ 1 ];

        if( $this->output->isDecorated() ) return sprintf( $statusSymbol[ 'format' ], ( string ) $statusSymbol[ 'symbol' ] );

        return ( string ) $statusSymbol[ 'symbol' ];
    }

    protected function getSymbolFromErrorType( int $type ) : string
    {
        $status = match( $type )
        {
            Error::TYPE_INVALID => FixerFileProcessedEvent::STATUS_INVALID,
            Error::TYPE_EXCEPTION => FixerFileProcessedEvent::STATUS_EXCEPTION,
            Error::TYPE_LINT => FixerFileProcessedEvent::STATUS_LINT,
            default => FixerFileProcessedEvent::STATUS_INVALID,
        };

        return $this->getSymbol( $status );
    }
}
