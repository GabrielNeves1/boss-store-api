<?php

declare(strict_types=1);

namespace App\Http\Controllers\Branding;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandingResource;
use App\Settings\BrandingSettings;

final class BrandingController extends Controller
{
    public function __invoke(BrandingSettings $settings): BrandingResource
    {
        return new BrandingResource($settings);
    }
}
