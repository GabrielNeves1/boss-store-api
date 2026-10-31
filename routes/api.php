<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CurrentUserController;
use App\Http\Controllers\Branding\BrandingController;
use App\Http\Controllers\Catalog\ListCategoriesController;
use App\Http\Controllers\Catalog\ListFeaturedUnitsController;
use App\Http\Controllers\Catalog\ListUnitsController;
use App\Http\Controllers\Catalog\ShowCategoryController;
use App\Http\Controllers\Catalog\ShowUnitController;
use Illuminate\Support\Facades\Route;

Route::get('branding', BrandingController::class)->name('branding.show');

Route::prefix('catalog')->name('catalog.')->group(function (): void {
    Route::get('categories', ListCategoriesController::class)->name('categories.index');
    Route::get('categories/{category}', ShowCategoryController::class)->name('categories.show');
    Route::get('units', ListUnitsController::class)->name('units.index');
    Route::get('units/featured', ListFeaturedUnitsController::class)->name('units.featured');
    Route::get('units/{unit}', ShowUnitController::class)->name('units.show');
});

Route::prefix('auth')->name('auth.')->group(function (): void {
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('me', CurrentUserController::class)->name('me');
    });
});
