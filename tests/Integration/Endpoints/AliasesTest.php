<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Aliases;
use Qdrant\Endpoints\Cluster;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Tests\Integration\AbstractIntegration;

class AliasesTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testClusterInfo(): void
    {
        $aliases = new Aliases($this->client);
        $response = $aliases->all();

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('ok', $response['status']);
    }
}