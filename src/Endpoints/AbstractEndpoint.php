<?php
/**
 * AbstractEndpoint
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Endpoints;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Query;
use Psr\Http\Message\RequestInterface;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Http\HttpClientInterface;

abstract class AbstractEndpoint
{
    /**
     * @var string|null
     */
    protected $collectionName = null;

    /**
     * @var HttpClientInterface
     */
    protected $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function setCollectionName(?string $collectionName)
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
        if ($params) {
            return '?' . Query::build($params);
        }
        return '';
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function createRequest(string $method, string $uri, array $body = []): RequestInterface
    {
        $httpFactory = new HttpFactory();
        $request = $httpFactory->createRequest($method, $uri);
        if ($body) {
            try {
                $request = $request->withBody(
                    $httpFactory->createStream(json_encode($body, 4194304))
                );
            } catch (\JsonException $e) {
                throw new InvalidArgumentException('Json parse error!');
            }
        }

        return $request;
    }
}