<?php

namespace App\Actions;

use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Output\ProgressOutput;
use App\Factories\ConfigurationResolverFactory;
use LaravelZero\Framework\Exceptions\ConsoleException;
use PhpCsFixer\Runner\Runner;


class FixCode
{
    public function __construct(
        protected ErrorsManager $errors,
        protected EventDispatcher $events,
        protected InputInterface $input,
        protected OutputInterface $output,
        protected ProgressOutput $progress
    ) {}


    public function execute() : array
    {
        try
        {
            [ $resolver, $totalFiles ] = ConfigurationResolverFactory::fromIO( $this->input, $this->output );
        }
        catch( ConsoleException $exception )
        {
            if( ! $exception->getExitCode() ) return [ $exception->getExitCode(), [] ];

            throw $exception;
        }

        if( is_null( $this->input->getOption( 'format' ) ) )
        {
            $this->progress->subscribe();
        }

        $changes = ( new Runner(
            $resolver->getFinder(),
            $resolver->getFixers(),
            $resolver->getDiffer(),
            $this->events,
            $this->errors,
            $resolver->getLinter(),
            $resolver->isDryRun(),
            $resolver->getCacheManager(),
            $resolver->getDirectory(),
            $resolver->shouldStopOnViolation()
        ) )->fix();

        return tap( [ $totalFiles, $changes ], fn () => $this->progress->unsubscribe() );
    }
}
