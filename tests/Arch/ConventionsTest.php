<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('every PHP file declares strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('env() is only read inside config files')
    ->expect('env')
    ->not->toBeUsed();
