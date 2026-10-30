<?php

declare(strict_types=1);

use App\Models\User;

it('returns the authenticated user', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('auth.me'))
        ->assertOk()
        ->assertExactJson(['data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]]);
});

it('requires authentication', function (): void {
    $this->getJson(route('auth.me'))->assertUnauthorized();
});
