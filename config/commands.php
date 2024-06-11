<?php

return [

    'default' => App\Commands\DefaultCommand::class,

    'paths' => [ app_path( 'Commands' ) ],

    'add' => [],

    'hidden' => [ LaravelZero\Framework\Commands\BuildCommand::class ],

    'remove' => [

        Illuminate\Console\Scheduling\ScheduleRunCommand::class,
        Illuminate\Console\Scheduling\ScheduleFinishCommand::class,

        LaravelZero\Framework\Commands\InstallCommand::class,
        LaravelZero\Framework\Commands\MakeCommand::class,
        LaravelZero\Framework\Commands\RenameCommand::class,
        LaravelZero\Framework\Commands\TestMakeCommand::class,
        LaravelZero\Framework\Commands\StubPublishCommand::class,

        NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand::class,
        NunoMaduro\LaravelConsoleSummary\SummaryCommand::class,

        Symfony\Component\Console\Command\DumpCompletionCommand::class

    ]
];
