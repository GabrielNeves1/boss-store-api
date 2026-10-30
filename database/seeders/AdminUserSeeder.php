<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

final class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('seeding.admin.name');
        $email = config('seeding.admin.email');
        $password = config('seeding.admin.password');

        if (! $this->isFilled($name) || ! $this->isFilled($email) || ! $this->isFilled($password)) {
            $this->command?->warn(__('seeding.admin_skipped'));

            return;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => $password],
        );
    }

    /**
     * @phpstan-assert-if-true non-empty-string $value
     */
    private function isFilled(mixed $value): bool
    {
        return is_string($value) && $value !== '';
    }
}
