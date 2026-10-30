<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;

final class LogoutUser
{
    public function __construct(private readonly Session $session) {}

    public function handle(): void
    {
        Auth::guard('web')->logout();

        $this->session->invalidate();
        $this->session->regenerateToken();
    }
}
