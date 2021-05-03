<?php

namespace TKing\Launch\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Vapor
{

    public function __construct(private string $id, private string $name, private string $domain)
    {
        if (base_path('vapor.yml')) {
            $yml = File::get(base_path('vapor.yml'));
            if (empty($this->id)) {
                $this->id = $this->find($yml, 'id');
            }
            if (empty($this->name)) {
                $this->name = $this->find($yml, 'name');
            }
            if (empty($this->domain)) {
                $this->domain = $this->find($yml, 'domain');
            }
        }
    }

    public function copyGithubActionsCi(): void
    {
        File::copyDirectory($this->thisDirectory(".github"), base_path(".github"));
    }

    public function copyVaporYml(): void
    {
        File::put(
            base_path('vapor.yml'),
            Str::of(File::get($this->thisDirectory("vapor.yml")))->replace('%id%', $this->id)->replace('%name%', $this->name)->replace('%domain%', $this->domain)
        );
    }

    private function thisDirectory(string $path = ''): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . class_basename($this) . DIRECTORY_SEPARATOR . $path;
    }

    private function find(string $yml, string $key): string
    {
        $filtered = Str::of($yml)->explode(PHP_EOL)->filter(function ($line) use ($key) {
            return Str::of($line)->trim()->startsWith($key . ": ");
        });
        if ($filtered->count() == 1) {
            return Str::of($filtered->first())->trim()->replace($key . ": ", '')->trim();
        }
        return '';
    }
}
