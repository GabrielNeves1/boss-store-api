<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class HealthcheckTest extends TestCase
{
    public function test_healthcheck_responds_ok(): void
    {
        $this->get('/up')->assertOk();
    }
}
