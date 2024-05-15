<?php
/**
 * Qdrant PHP Client
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

class Config
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port = 6333;

    /**
     * @var string|null
     */
    protected $apiKey;

    public function __construct(string $host, int $port = 6333)
    {
        $this->host = $host;
        $this->port = $port;
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
        return $this->host . ':' . $this->port;
    }

    public function getApiKey(): string
    {
        return $this->apiKey ?? '';
    }

    public function setApiKey($apiKey): Config
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}