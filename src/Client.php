<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;

class Client implements ClientInterface
{
    protected Config $config;
    protected \GuzzleHttp\Client $client;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->config->getDomain()
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     * @throws ServerException|InvalidArgumentException
     */
    public function execute(string $method, string $path, array $options = []): Response
    {
        $data = [
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'api-key' => $this->config->getApiKey(),
            ]
        ];
        if (($method === 'POST' || $method === 'PUT' || $method === 'PATCH') && $options) {
            $data['json'] = $options;
        }

        try {
            $res = $this->client->request($method, $path, $data);

            return Response::buildFromHttpResponse($res);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode >= 400 && $statusCode < 500) {
                throw new InvalidArgumentException($e->getMessage());
            } elseif ($statusCode >= 500) {
                throw new ServerException($e->getMessage());
            }
        }
    }

    public function collections(string $collectionName = null): Collections
    {
        return (new Collections($this))->setCollectionName($collectionName);
    }
}