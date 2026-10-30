<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Support\Facades\Hash;

it('creates the admin user from the configured credentials', function (): void {
    config()->set('seeding.admin', [
        'name' => 'Store Admin',
        'email' => 'admin@store.test',
        'password' => 'very-secret',
    ]);

    $this->seed(AdminUserSeeder::class);

    $admin = User::query()->where('email', 'admin@store.test')->firstOrFail();

    expect($admin->name)->toBe('Store Admin')
        ->and(Hash::check('very-secret', $admin->password))->toBeTrue()
        ->and(User::query()->count())->toBe(1);
});

it('is idempotent for the same e-mail', function (): void {
    config()->set('seeding.admin', [
        'name' => 'Store Admin',
        'email' => 'admin@store.test',
        'password' => 'very-secret',
    ]);

    $this->seed(AdminUserSeeder::class);
    $this->seed(AdminUserSeeder::class);

    expect(User::query()->count())->toBe(1);
});

it('creates nobody when the credentials are empty', function (): void {
    config()->set('seeding.admin', ['name' => '', 'email' => null, 'password' => '']);

    $this->seed(AdminUserSeeder::class);

    expect(User::query()->count())->toBe(0);
});
