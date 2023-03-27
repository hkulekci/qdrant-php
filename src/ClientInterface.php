<?php
/**
 * ClientInterface
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant;

interface ClientInterface
{
    public function execute(string $method, string $path, array $options = []): Response;
}