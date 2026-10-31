<?php

declare(strict_types=1);

namespace App\Actions\Catalog\Media;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Process;

final class RunFfmpeg
{
    /**
     * @param  list<string>  $arguments
     */
    public function handle(array $arguments): void
    {
        Process::timeout(600)
            ->run([Config::string('catalog.media.ffmpeg_binary'), '-v', 'error', '-y', ...$arguments])
            ->throw();
    }
}
