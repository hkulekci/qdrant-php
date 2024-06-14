<?php
/**
 * InvalidArgumentException
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Exception;

use Qdrant\Response;

class InvalidArgumentException extends \InvalidArgumentException
{
    protected Response $response;

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): InvalidArgumentException
    {
        $this->response = $response;

        return $this;
    }
}
