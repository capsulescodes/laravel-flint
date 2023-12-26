<?php

namespace App\Actions;

use PhpCsFixer\Error\ErrorsManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Output\SummaryOutput;
use Illuminate\Console\Command;
use PhpCsFixer\Console\Report\FixReport\CheckstyleReporter;
use PhpCsFixer\Console\Report\FixReport\GitlabReporter;
use PhpCsFixer\Console\Report\FixReport\JsonReporter;
use PhpCsFixer\Console\Report\FixReport\JunitReporter;
use PhpCsFixer\Console\Report\FixReport\TextReporter;
use PhpCsFixer\Console\Report\FixReport\XmlReporter;
use PhpCsFixer\Console\Report\FixReport\ReportSummary;


class ElaborateSummary
{
    public function __construct(
        protected ErrorsManager $errors,
        protected InputInterface $input,
        protected OutputInterface $output,
        protected SummaryOutput $summaryOutput
    ) {}


    public function execute( int $totalFiles, array $changes ) : int
    {
        $summary = new ReportSummary(
            $changes,
            $totalFiles,
            0,
            0,
            $this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE,
            $this->input->getOption( 'test' ),
            $this->output->isDecorated()
        );

        if( $this->input->getOption( 'format' ) )
        {
            $this->displayUsingFormatter( $summary );
        }
        else
        {
            $this->summaryOutput->handle( $summary, $totalFiles );
        }

        $failure = ( $summary->isDryRun() && count($changes ) > 0 )
            || count( $this->errors->getInvalidErrors() ) > 0
            || count( $this->errors->getExceptionErrors() ) > 0
            || count( $this->errors->getLintErrors() ) > 0;

        return $failure ? Command::FAILURE : Command::SUCCESS;
    }

    protected function displayUsingFormatter( ReportSummary $summary ) : void
    {
        $reporter = match( $format = $this->input->getOption( 'format' ) )
        {
            'checkstyle' => new CheckstyleReporter(),
            'gitlab' => new GitlabReporter(),
            'json' => new JsonReporter(),
            'junit' => new JunitReporter(),
            'txt' => new TextReporter(),
            'xml' => new XmlReporter(),
            default => abort( 1, sprintf( 'Format [%s] is not supported.', $format ) ),
        };

        $this->output->write( $reporter->generate( $summary ) );
    }
}
