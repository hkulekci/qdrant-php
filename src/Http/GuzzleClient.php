<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Qdrant\Config;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;
use Qdrant\Response;

class GuzzleClient implements HttpClientInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $this->config->getDomain(),
            'http_errors' => true,
        ]);
    }

    private function prepareHeaders(RequestInterface $request): RequestInterface
    {
        $request = $request->withHeader('content-type', 'application/json')
            ->withHeader('accept', 'application/json');

        if ($this->config->getApiKey()) {
            $request = $request->withHeader('api-key', $this->config->getApiKey());
        }

        return $request;
    }

    /**
     * @param RequestInterface $request
     * @return Response
     * @throws InvalidArgumentException
     * @throws JsonException
     * @throws ServerException
     * @throws ClientExceptionInterface
     */
    public function execute(RequestInterface $request): Response
    {
        $request = $this->prepareHeaders($request);
        try {
            $res = $this->client->send($request);

            return new Response($res);
        } catch (RequestException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode >= 400 && $statusCode < 500) {
                $errorResponse = new Response($e->getResponse());
                throw (new InvalidArgumentException($e->getMessage(), $statusCode))
                    ->setResponse($errorResponse);
            } elseif ($statusCode >= 500) {
                $errorResponse = new Response($e->getResponse());
                throw (new ServerException($e->getMessage(), $statusCode))
                    ->setResponse($errorResponse);
            }
        }
    }
}