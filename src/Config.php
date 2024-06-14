<?php
/**
 * Qdrant PHP Client
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

class Config
{
    protected ?string $apiKey = null;

    public function __construct(protected string $host, protected int $port = 6333)
    {
    }

    public function getHost(): string
    {
        return parse_url($this->host, PHP_URL_HOST) ?: $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getScheme(): string
    {
        return parse_url($this->host, PHP_URL_SCHEME) ?: 'http';
    }

    public function getDomain(): string
    {
        return $this->host.':'.$this->port;
    }

    public function setApiKey($apiKey): Config
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey ?? '';
    }
}
