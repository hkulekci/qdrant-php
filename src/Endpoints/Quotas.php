<?php
/**
 * Quotas
 *
 * Global resource quotas, available since Qdrant 1.19.
 *
 * https://qdrant.tech/documentation/ops-configuration/quotas/
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Endpoints;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\QuotaConfig;
use Qdrant\Response;

class Quotas extends AbstractEndpoint
{
    /**
     * # Get quotas
     * Get the current global quota configuration and usage
     *
     * @throws InvalidArgumentException
     */
    public function get(): Response
    {
        return $this->client->execute(
            $this->createRequest('GET', '/quotas')
        );
    }

    /**
     * # Update quotas
     * Update the global quota configuration
     *
     * @throws InvalidArgumentException
     */
    public function update(QuotaConfig $config, array $queryParams = []): Response
    {
        return $this->client->execute(
            $this->createRequest('PUT', '/quotas' . $this->queryBuild($queryParams), $config->toArray())
        );
    }
}
