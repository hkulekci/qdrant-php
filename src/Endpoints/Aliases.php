<?php
/**
 * Aliases
 *
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\ServiceLock;
use Qdrant\Response;

class Aliases extends AbstractEndpoint
{
    /**
     * @throws InvalidArgumentException
     */
    public function all(): Response
    {
        return $this->client->execute($this->createRequest('GET', '/aliases'));
    }
}