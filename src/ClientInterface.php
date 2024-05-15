<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

use Psr\Http\Message\RequestInterface;

interface ClientInterface
{
    public function execute(RequestInterface $request): Response;
}