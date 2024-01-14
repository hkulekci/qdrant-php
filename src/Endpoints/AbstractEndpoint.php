<?php
/**
 * AbstractEndpoint
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Endpoints;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Qdrant\ClientInterface;
use Qdrant\Exception\InvalidArgumentException;

abstract class AbstractEndpoint
{
    protected ?string $collectionName = null;

    public function __construct(protected ClientInterface $client)
    {
    }

    public function setCollectionName(?string $collectionName): static
    {
        $this->collectionName = $collectionName;

        return $this;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getCollectionName(): string
    {
        if ($this->collectionName === null) {
            throw new InvalidArgumentException('You need to specify the collection name');
        }
        return $this->collectionName;
    }

    protected function queryBuild(array $params): string
    {
        $p = [];
        foreach ($params as $k => $v) {
            $p[] = urldecode($k) . "=" . urlencode($v);
        }

        return '?' . implode('&', $p);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function createRequest(string $method, string $uri, array $body = []): RequestInterface
    {
        $httpFactory = Psr17FactoryDiscovery::findRequestFactory();
        $request = $httpFactory->createRequest($method, $uri);
        if ($body) {
            try {
                $request = $request->withBody(
                    $httpFactory->createStream(json_encode($body, JSON_THROW_ON_ERROR))
                );
            } catch (\JsonException $e) {
                throw new InvalidArgumentException('Json parse error!');
            }
        }

        return $request;
    }
}