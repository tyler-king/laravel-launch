<?php

declare(strict_types=1);

namespace TKing\Launch\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class XDebug
{

    private const EOL = "\n";

    public function writeToDockerInstall()
    {
        $path = base_path('docker' . DIRECTORY_SEPARATOR . '8.0' . DIRECTORY_SEPARATOR . "Dockerfile");
        $currentDockerFile = Str::of(File::get($path))->explode(self::EOL);



        $map = [
            [
                "pecl install swoole",  'RUN yes | pecl install xdebug'
            ],
            [
                "pecl install swoole",  'RUN yes | pecl install xdebug'
            ],
            [
                "COPY php.ini /etc/php/8.0/cli/conf.d/99-sail.ini",  "COPY xdebug.ini /etc/php/8.0/cli/conf.d/docker-php-ext-xdebug.ini"
            ]
        ];

        foreach ($map as $replacers) {
            $replace = $replacers[0];
            $with = $replacers[1];
            $needsInstalled = $currentDockerFile->filter(function ($line) use ($with) {
                return Str::of($line)->contains($with);
            })->isEmpty();

            if ($needsInstalled) {
                $currentDockerFile = $currentDockerFile->map(function ($line)  use ($replace, $with) {
                    if (Str::of($line)->contains($replace)) {
                        return $line . self::EOL . $with;
                    }
                    return $line;
                });
            }
        }
        File::put($path, $currentDockerFile->implode(self::EOL));
    }

    public function publishIni()
    {
        $path = base_path('docker' . DIRECTORY_SEPARATOR . '8.0' . DIRECTORY_SEPARATOR . "xdebug.ini");
        File::copy($this->thisDirectory('xdebug.ini'), $path);
    }

    private function thisDirectory(string $path = ''): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . class_basename($this) . DIRECTORY_SEPARATOR . $path;
    }
}
