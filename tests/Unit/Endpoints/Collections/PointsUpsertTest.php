<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Endpoints\Collections;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Qdrant\ClientInterface;
use Qdrant\Endpoints\Collections;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\Request\UpdateMode;
use Qdrant\Models\VectorStruct;
use Qdrant\Response;

class PointsUpsertTest extends TestCase
{
    public function testUpsertWithUpdateModeAndFilter(): void
    {
        $client = $this->expectRequest('PUT', '/collections/test-collection/points', [
            'points' => [['id' => 1, 'vector' => ['image' => [1, 2, 3]]]],
            'update_mode' => 'update_only',
            'update_filter' => ['must' => [['key' => 'status', 'match' => ['value' => 'draft']]]],
        ]);

        $points = new PointsStruct();
        $points->addPoint(new PointStruct(1, new VectorStruct([1, 2, 3], 'image')));

        (new Collections($client))->setCollectionName('test-collection')->points()->upsert(
            $points,
            updateMode: UpdateMode::UPDATE_ONLY,
            updateFilter: (new Filter())->addMust(new MatchString('status', 'draft'))
        );
    }

    public function testUpsertWithoutUpdateOptions(): void
    {
        $client = $this->expectRequest('PUT', '/collections/test-collection/points', [
            'points' => [['id' => 1, 'vector' => ['image' => [1, 2, 3]]]],
        ]);

        $points = new PointsStruct();
        $points->addPoint(new PointStruct(1, new VectorStruct([1, 2, 3], 'image')));

        (new Collections($client))->setCollectionName('test-collection')->points()->upsert($points);
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
