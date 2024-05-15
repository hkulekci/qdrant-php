<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

use ArrayAccess;
use Exception;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;

class Response implements ArrayAccess
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var mixed[]
     */
    protected $raw;

    /**
     * @throws ServerException
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        if ($response->getHeaderLine('content-type') === 'application/json') {
            $this->raw = json_decode($response->getBody()->getContents(), true, 512, 0);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception(json_last_error_msg());
            }
        } else {
            $this->raw = [
                'content' => $response->getBody()->getContents()
            ];
        }
    }

    public function __toArray(): array
    {
        return $this->raw;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->raw[$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->raw[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws Exception
     */
    public function offsetSet($offset, $value): void
    {
        throw new Exception('You can not change the response');
    }

    /**
     * @param mixed $offset
     * @throws Exception
     */
    public function offsetUnset($offset): void
    {
        throw new Exception('You can not change the response');
    }
}