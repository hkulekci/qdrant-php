<?php
/**
 * InvalidArgumentException
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Exception;

use Qdrant\Response;

class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     * @return InvalidArgumentException
     */
    public function setResponse(Response $response): InvalidArgumentException
    {
        $this->response = $response;

        return $this;
    }
}