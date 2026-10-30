<?php

declare(strict_types=1);

namespace App\Http\Controllers\Fonts;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\MimeTypes;

/**
 * Serves the self-hosted brand fonts from resources/fonts through the
 * application, so the CORS headers the fronts need are always present.
 */
final class FontFileController extends Controller
{
    public function __invoke(string $path): BinaryFileResponse
    {
        $file = $this->resolve($path);

        return response()
            ->file($file, [
                'Content-Type' => $this->mimeType($file),
                'Cache-Control' => sprintf('public, max-age=%d, immutable', Config::integer('branding.fonts_cache_seconds')),
            ]);
    }

    private function resolve(string $path): string
    {
        $directory = realpath(resource_path('fonts'));
        $file = realpath(resource_path('fonts/'.$path));

        if ($directory === false || $file === false || ! Str::startsWith($file, $directory.DIRECTORY_SEPARATOR) || ! is_file($file)) {
            throw new NotFoundHttpException;
        }

        return $file;
    }

    private function mimeType(string $file): string
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        return MimeTypes::getDefault()->getMimeTypes($extension)[0] ?? 'application/octet-stream';
    }
}
