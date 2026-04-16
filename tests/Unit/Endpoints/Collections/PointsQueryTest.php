<?php

namespace Qdrant\Tests\Unit\Endpoints\Collections;

use PHPUnit\Framework\TestCase;
use Qdrant\ClientInterface;
use Qdrant\Endpoints\Collections\Points;
use Qdrant\Endpoints\Collections\Points\Query;

class PointsQueryTest extends TestCase
{
    public function testQueryReturnsQueryEndpoint(): void
    {
        $mockClient = $this->createMock(ClientInterface::class);
        $points = (new Points($mockClient))->setCollectionName('test-collection');

        $query = $points->query();

        $this->assertInstanceOf(Query::class, $query);
    }
}
