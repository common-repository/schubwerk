<?php

namespace Schubwerk\Core;

class AbstractCore
{
    const API_VERSION = 'v1';
    protected string $baseUrl;
    protected string $apiKey;
    protected string $tempDir;
    protected string $publicDir;

    public function __construct(string $baseUrl, string $apiKey, string $tempDir, string $publicDir)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->tempDir = $tempDir;
        $this->publicDir = $publicDir;
    }

    protected function buildUrl(string $call)
    {
        return sprintf('%s/%s/projects/%s/events/%s',
            $this->getApiUrl(),
            self::API_VERSION,
            $this->apiKey,
            $call
        );
    }

    protected function getApiUrl(): string
    {
        return sprintf('%s/api/tracker', $this->baseUrl);
    }
}
