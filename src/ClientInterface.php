<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

use Qdrant\Endpoints\Collections;
use Qdrant\Http\HttpClientInterface;

interface ClientInterface extends HttpClientInterface
{
    public function collections(string $collectionName = null): Collections;
}