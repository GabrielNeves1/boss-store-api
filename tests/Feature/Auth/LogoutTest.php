<?php

declare(strict_types=1);

use App\Models\User;

it('logs the user out', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withHeader('Origin', 'http://localhost:9000')
        ->postJson(route('auth.logout'))
        ->assertNoContent();

    $this->assertGuest('web');
});

it('requires authentication', function (): void {
    $this->postJson(route('auth.logout'))->assertUnauthorized();
});
