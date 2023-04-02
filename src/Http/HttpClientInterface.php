<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Http;

use Psr\Http\Message\RequestInterface;
use Qdrant\Response;

interface HttpClientInterface
{
    public function execute(RequestInterface $request): Response;
}