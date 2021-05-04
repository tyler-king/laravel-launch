<?php

namespace TKing\Launch\Helpers;

class OpenApi
{

    private $openapi;

    public function __construct(array $openapi)
    {
        $this->openapi = $openapi;
    }

    public function get(): array
    {
        return $this->openapi;
    }

    public static function fromConfig($openapi): self
    {
        if (is_string($openapi)) {
            if (file_exists($openapi)) {
                $openapi = file_get_contents($openapi);
            }
            $openapi = json_decode($openapi, true);
        }
        return new self($openapi ?? []);
    }

    public function getPaths(): array
    {
        return $this->openapi['paths'] ?? [];
    }

    public function getHomePage(): string
    {
        return $this->openapi['x-homepage'] ?? '';
    }
}
