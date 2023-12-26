<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use App\Actions\FixCode;
use App\Actions\ElaborateSummary;


class DefaultCommand extends Command
{
    protected $name = 'default';

    protected $description = 'Fix the coding style of the given path';


    protected function configure() : void
    {
        parent::configure();

        $this->setDefinition( [
            new InputArgument( 'path', InputArgument::IS_ARRAY, 'The path to fix', [ (string) getcwd() ] ),
            new InputOption( 'config', '', InputOption::VALUE_REQUIRED, 'The configuration that should be used' ),
            new InputOption( 'preset', '', InputOption::VALUE_REQUIRED, 'The preset that should be used' ),
            new InputOption( 'test', '', InputOption::VALUE_NONE, 'Test for code style errors without fixing them' ),
            new InputOption( 'dirty', '', InputOption::VALUE_NONE, 'Only fix files that have uncommitted changes' ),
            new InputOption( 'format', '', InputOption::VALUE_REQUIRED, 'The output format that should be used' )
        ] );
    }

    public function handle( FixCode $fixCode, ElaborateSummary $elaborateSummary ) : int
    {
        [ $totalFiles, $changes ] = $fixCode->execute();

        return $elaborateSummary->execute( $totalFiles, $changes );
    }
}
