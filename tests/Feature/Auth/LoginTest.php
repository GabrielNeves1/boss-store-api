<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Testing\TestResponse;

function login(array $payload): TestResponse
{
    return test()
        ->withHeader('Origin', 'http://localhost:9000')
        ->postJson(route('auth.login'), $payload);
}

it('logs in with valid credentials and returns the user', function (): void {
    $user = User::factory()->create(['password' => 'correct-password']);

    login(['email' => $user->email, 'password' => 'correct-password'])
        ->assertOk()
        ->assertExactJson(['data' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]]);

    $this->assertAuthenticatedAs($user);
});

it('rejects wrong credentials with a validation error', function (): void {
    $user = User::factory()->create(['password' => 'correct-password']);

    login(['email' => $user->email, 'password' => 'wrong-password'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email' => __('auth.failed')]);

    $this->assertGuest();
});

it('validates the required fields', function (): void {
    login([])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('throttles repeated attempts for the same e-mail', function (): void {
    $user = User::factory()->create(['password' => 'correct-password']);
    $maxAttempts = config()->integer('auth.login.max_attempts_per_minute');

    foreach (range(1, $maxAttempts) as $attempt) {
        login(['email' => $user->email, 'password' => 'wrong-password'])->assertUnprocessable();
    }

    login(['email' => $user->email, 'password' => 'correct-password'])->assertTooManyRequests();
});
