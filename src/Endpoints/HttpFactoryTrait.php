<?php
/**
 * HttpFactoryTrait
 *
 * @since     Jan 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Qdrant\Exception\InvalidArgumentException;

trait HttpFactoryTrait
{
    /**
     * @var RequestFactoryInterface|null
     */
    protected $httpFactory;

    /**
     * @throws InvalidArgumentException
     */
    protected function createRequest(string $method, string $uri, array $body = []): RequestInterface
    {

        $request = $this->getHttpFactory()->createRequest($method, $uri);
        if ($body) {
            try {
                $request = $request->withBody(
                    $this->getHttpFactory()->createStream(json_encode($body, 0))
                );
            } catch (\JsonException $e) {
                throw new InvalidArgumentException('Json parse error!', $e->getCode(), $e);
            }
        }

        return $request;
    }

    public function getHttpFactory(): RequestFactoryInterface
    {
        if ($this->httpFactory === null) {
            $this->httpFactory = Psr17FactoryDiscovery::findRequestFactory();
        }
        return $this->httpFactory;
    }

    public function setHttpFactory(RequestFactoryInterface $httpFactory): void
    {
        $this->httpFactory = $httpFactory;
    }

    protected function queryBuild(array $params): string
    {
        return '?' . http_build_query($params);
    }
}