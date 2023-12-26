<?php

namespace App\Output;

use App\Output\Concerns\InteractsWithSymbols;
use App\Project;
use App\Repositories\ConfigurationJsonRepository;
use App\ValueObjects\Issue;
use PhpCsFixer\Console\Report\FixReport\ReportSummary;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\FixerFileProcessedEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Support\Collection;

use function Termwind\render;
use function Termwind\renderUsing;


class SummaryOutput
{
    use InteractsWithSymbols;

    protected array $presets = [
        'none' => 'None',
        'per' => 'PER',
        'psr12' => 'PSR 12',
        'laravel' => 'Laravel',
        'symfony' => 'Symfony',
    ];


    public function __construct(
        protected ConfigurationJsonRepository $config,
        protected ErrorsManager $errors,
        protected InputInterface $input,
        protected OutputInterface $output,
    ) {}

    public function handle( ReportSummary $summary, int $totalFiles ) : void
    {
        renderUsing( $this->output );

        $issues = $this->getIssues( Project::path(), $summary );

        render( view('summary', [ 'totalFiles' => $totalFiles, 'issues' => $issues, 'testing' => $summary->isDryRun(), 'preset' => $this->presets[$this->config->preset() ] ] ) );

        foreach( $issues as $issue )
        {
            render( view( 'issue.show', [ 'issue' => $issue, 'isVerbose' => $this->output->isVerbose(),'testing' => $summary->isDryRun() ] ) );

            if( $this->output->isVerbose() && $issue->code() ) $this->output->writeln( $issue->code() );
        }

        $this->output->writeln( '' );
    }

    public function getIssues( string $path, ReportSummary $summary ) : Collection
    {
        $issues = collect( $summary->getChanged() )->map( fn( $information, $file ) => new Issue( $path, $file, $this->getSymbol( FixerFileProcessedEvent::STATUS_FIXED ), $information ) );

        return $issues
            ->merge( collect(
                $this->errors->getInvalidErrors() +
                $this->errors->getExceptionErrors() +
                $this->errors->getLintErrors()
            )->map( fn( $error ) => new Issue(
                $path,
                $error->getFilePath(),
                $this->getSymbolFromErrorType( $error->getType() ),
                [ 'source' => $error->getSource() ] )
            ) )
            ->sort( fn( $issueA, $issueB ) => $issueA <=> $issueB )
            ->values();
    }
}
