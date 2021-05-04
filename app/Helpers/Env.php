<?php

namespace TKing\Launch\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Env
{
    private const EOL = "\n";

    public function edit(?string $name, ?string $database, ?int $projectNumber): void
    {
        $currentEnv = File::get(base_path('.env'));
        $currentEnv = Str::of($currentEnv)->explode(self::EOL);

        if (isset($projectNumber)) {
            $projectNumber = (int)$projectNumber;

            $currentEnv = $currentEnv->reject(function ($line) {
                return Str::of($line)->startsWith([
                    "#FORWARD PORT INFORMATION FOR MULTIPLE PROJECTS",
                    "APP_PORT=",
                    "FORWARD_DB_PORT=",
                    "FORWARD_REDIS_PORT=",
                    "FORWARD_MAILHOG_PORT=",
                    "FORWARD_MAILHOG_DASHBOARD_PORT="
                ]);
            })->concat([
                "#FORWARD PORT INFORMATION FOR MULTIPLE PROJECTS",
                "APP_PORT=" . (8000 + $projectNumber),
                "FORWARD_DB_PORT=" . (3306 + $projectNumber),
                "FORWARD_REDIS_PORT=" . (6379 + $projectNumber),
                "FORWARD_MAILHOG_PORT=" . (1025 + $projectNumber),
                "FORWARD_MAILHOG_DASHBOARD_PORT=" . (8026 + $projectNumber)
            ]);
        }

        $currentEnv = $currentEnv->map(function ($line) {
            if (Str::of($line)->startsWith("DB_HOST=")) {
                return "DB_HOST=mysql";
            }
            return $line;
        });

        $currentEnv = $currentEnv->map(function ($line) use ($name) {
            if (Str::of($line)->startsWith("APP_NAME=")) {
                return "APP_NAME=$name";
            }
            return $line;
        });

        $currentEnv = $currentEnv->map(function ($line) use ($database) {
            if (Str::of($line)->startsWith("DB_DATABASE=")) {
                return "DB_DATABASE=$database";
            }
            return $line;
        });

        File::put(base_path('.env'), $currentEnv->implode(self::EOL) . self::EOL);
    }

    public function getName(): ?string
    {
        $currentEnv = File::get(base_path('.env'));
        $currentEnv = Str::of($currentEnv)->explode(self::EOL);
        $currentEnv = $currentEnv->filter(function ($line) {
            return Str::of($line)->startsWith("APP_NAME=");
        });
        if ($currentEnv->count() == 1) {
            return Str::of($currentEnv->first())->replace("APP_NAME=", '');
        }
        return null;
    }

    public function getProject(): ?string
    {
        $currentEnv = File::get(base_path('.env'));
        $currentEnv = Str::of($currentEnv)->explode(self::EOL);
        $currentEnv = $currentEnv->filter(function ($line) {
            return Str::of($line)->startsWith("APP_PORT=") && $line !== "APP_PORT=";
        });
        if ($currentEnv->count() == 1) {
            $number = Str::of($currentEnv->first())->replace("APP_PORT=", '');
            if ($number !== '8000') {
                return (int)$number->__toString() - 8000;
            }
        }
        return null;
    }

    public function getDatabase(): ?string
    {
        $currentEnv = File::get(base_path('.env'));
        $currentEnv = Str::of($currentEnv)->explode(self::EOL);
        $currentEnv = $currentEnv->filter(function ($line) {
            return Str::of($line)->startsWith("DB_DATABASE=");
        });
        if ($currentEnv->count() == 1) {
            return Str::of($currentEnv->first())->replace("DB_DATABASE=", '');
        }
        return null;
    }

    public function fixup(): void
    {
        $this->gitAttributes();
        $this->dockerComposerMySQL();
    }

    private function gitAttributes(): void
    {
        $currentGitAttr = File::get(base_path('.gitattributes'));
        $currentGitAttr = Str::of($currentGitAttr)->explode(self::EOL);

        $currentGitAttr = $currentGitAttr->map(function ($line) {
            if (Str::of($line)->startsWith("* text=")) {
                return "* text=lf";
            }
            return $line;
        });

        File::put(base_path('.gitattributes'), $currentGitAttr->implode(self::EOL));
    }

    private function dockerComposerMySQL(): void
    {
        $currentDockerFile = File::get(base_path('docker-compose.yml'));
        $currentDockerFile = Str::of($currentDockerFile)->explode(self::EOL);

        $command = 'command: --default-authentication-plugin=mysql_native_password';
        $needsInstalled = $currentDockerFile->filter(function ($line) use ($command) {
            return Str::of($line)->contains($command);
        })->isEmpty();
        if ($needsInstalled) {
            $currentDockerFile = $currentDockerFile->map(function ($line) use ($command) {
                if (Str::of($line)->trim()->startsWith("environment:")) {
                    $prefix = strpos($line, 'e');
                    return str_repeat(' ', $prefix) . $command . self::EOL . $line;
                }
                return $line;
            });
            File::put(base_path('docker-compose.yml'), $currentDockerFile->implode(self::EOL));
        }
    }
}
