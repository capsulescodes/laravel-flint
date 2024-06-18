<?php

use LaravelZero\Framework\Exceptions\ConsoleException;


it( 'ensures configuration file is valid', function()
{
    run( 'default', [ '--config' => base_path( 'tests/Fixtures/with-invalid-configuration/default.json' ) ] );

} )->throws( ConsoleException::class, 'is not valid JSON.' );
