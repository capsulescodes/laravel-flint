<?php

namespace App\Output;

use App\Output\Concerns\InteractsWithSymbols;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpCsFixer\FixerFileProcessedEvent;
use Symfony\Component\Console\Terminal;


class ProgressOutput
{
    use InteractsWithSymbols;

    protected int $processed = 0;

    protected int $symbolsPerLine = 0;


    public function __construct( protected EventDispatcherInterface $dispatcher, protected InputInterface $input, protected OutputInterface $output )
    {
        $this->symbolsPerLine = ( new Terminal() )->getWidth() - 4;
    }

    public function subscribe() : void
    {
        $this->dispatcher->addListener( FixerFileProcessedEvent::NAME, [ $this, 'handle' ] );
    }

    public function unsubscribe() : void
    {
        $this->dispatcher->removeListener( FixerFileProcessedEvent::NAME, [ $this, 'handle' ] );
    }

    public function handle( FixerFileProcessedEvent $event ) : void
    {
        $symbolsOnCurrentLine = $this->processed % $this->symbolsPerLine;

        if( $symbolsOnCurrentLine >= ( new Terminal() )->getWidth() - 4 ) $symbolsOnCurrentLine = 0;

        if( $symbolsOnCurrentLine === 0 )
        {
            $this->output->writeln( '' );
            $this->output->write( '  ' );
        }

        $this->output->write( $this->getSymbol( $event->getStatus() ) );

        $this->processed++;
    }
}
