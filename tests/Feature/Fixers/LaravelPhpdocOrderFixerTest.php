<?php

it('fixes the code', function () {
    [$statusCode, $output] = run('default', [
        'path' => base_path('tests/Fixtures/files/laravel_phpdoc_order.php'),
        '--preset' => 'laravel',
    ]);

    expect($statusCode)->toBe(1)
        ->and($output)
        ->toContain('  ⨯')
        ->toContain(
            <<<'EOF'
   /**
  - * @return string
    * @param  string  $foo
    * @param  string  $bar
  + * @return string
    */
EOF,
        );
});
