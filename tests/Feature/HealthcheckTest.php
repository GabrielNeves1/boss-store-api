<?php

declare(strict_types=1);

it('responds ok on the healthcheck route', function (): void {
    $this->get('/up')->assertOk();
});
