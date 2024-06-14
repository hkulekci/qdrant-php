<?php
/**
 * Transport.php
 *
 * @since     Jan 2024
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Qdrant\Config;

class Transport implements ClientInterface
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly Config $config
    ) {
    }

    private function prepareHeaders(RequestInterface $request): RequestInterface
    {
        $request = $request->withHeader('content-type', 'application/json')
            ->withHeader('accept', 'application/json')
            ->withUri(
                $request->getUri()
                    ->withHost($this->config->getHost())
                    ->withPort($this->config->getPort())
                    ->withScheme($this->config->getScheme())
            );

        if ($this->config->getApiKey())
        {
            $request = $request->withHeader('api-key', $this->config->getApiKey());
        }

        return $request;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $request = $this->prepareHeaders($request);

        return $this->client->sendRequest($request);
    }
}
