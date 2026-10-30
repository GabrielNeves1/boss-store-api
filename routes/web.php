<?php

declare(strict_types=1);

use App\Http\Controllers\Fonts\FontFileController;
use Illuminate\Support\Facades\Route;

Route::get('fonts/{path}', FontFileController::class)
    ->where('path', '[A-Za-z0-9/_.-]+')
    ->name('fonts.show');
