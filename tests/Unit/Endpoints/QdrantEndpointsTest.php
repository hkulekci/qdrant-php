<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Endpoints;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Qdrant\ClientInterface;
use Qdrant\Endpoints\Cluster;
use Qdrant\Endpoints\Quotas;
use Qdrant\Models\Request\QuotaConfig;
use Qdrant\Qdrant;
use Qdrant\Response;

class QdrantEndpointsTest extends TestCase
{
    public function testGet(): void
    {
        $client = $this->expectRequest('GET', '/quotas', null);

        (new Quotas($client))->get();
    }

    public function testUpdate(): void
    {
        $client = $this->expectRequest('PUT', '/quotas?wait=true', ['enabled' => true, 'max_disk_usage_percent' => 90]);

        (new Quotas($client))->update((new QuotaConfig())->setMaxDiskUsagePercent(90), ['wait' => 'true']);
    }

    public function testClientExposesQuotas(): void
    {
        $this->assertInstanceOf(Quotas::class, (new Qdrant($this->createMock(\Qdrant\Http\Transport::class)))->quotas());
    }

    public function testClusterTelemetry(): void
    {
        $client = $this->expectRequest('GET', '/cluster/telemetry?details_level=2', null);

        (new Cluster($client))->telemetry(['details_level' => 2]);
    }

    private function expectRequest(string $method, string $uri, ?array $body): ClientInterface
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('execute')
            ->with($this->callback(function (RequestInterface $request) use ($method, $uri, $body) {
                $this->assertSame($method, $request->getMethod());
                $this->assertSame($uri, (string)$request->getUri());
                $this->assertSame($body, json_decode((string)$request->getBody(), true));

                return true;
            }))
            ->willReturn($this->createMock(Response::class));

        return $client;
    }
}
