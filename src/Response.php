<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

use ArrayAccess;
use Exception;
use Psr\Http\Message\ResponseInterface;

class Response implements ArrayAccess
{
    protected array $raw;

    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    public function __toArray(): array
    {
        return $this->raw;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->raw[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->raw[$offset];
    }

    /**
     * @throws Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception('You can not change the response');
    }

    /**
     * @throws Exception
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new Exception('You can not change the response');
    }

    public static function buildFromHttpResponse(ResponseInterface $response): Response
    {
        $raw = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return new Response($raw);
    }
}