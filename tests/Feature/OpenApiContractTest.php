<?php

declare(strict_types=1);

use Dedoc\Scramble\Generator;

/*
 * The versioned openapi.json is the contract the fronts generate their types
 * from. It must always match what Scramble infers from the current code.
 * Regenerate it with `composer openapi:export`.
 *
 * The "servers" block is ignored because Scramble builds it from APP_URL,
 * which varies between environments.
 */
it('keeps openapi.json in sync with the code', function (): void {
    $exported = json_decode((string) file_get_contents(base_path('openapi.json')), true);
    $generated = json_decode((string) json_encode(app(Generator::class)()), true);

    expect($exported)->toBeArray()->and($generated)->toBeArray();

    unset($exported['servers'], $generated['servers']);

    expect($exported)->toEqual($generated);
});
