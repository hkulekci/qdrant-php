<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\Points\BatchQueryRequest;
use Qdrant\Models\Request\Points\QueryRequest;

class BatchQueryRequestTest extends TestCase
{
    public function testBatchQueryRequest(): void
    {
        $request1 = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $request2 = (new QueryRequest())
            ->setQuery(['nearest' => [0.4, 0.5, 0.6]])
            ->setLimit(5);

        $batch = new BatchQueryRequest([$request1, $request2]);

        $result = $batch->toArray();

        $this->assertArrayHasKey('searches', $result);
        $this->assertCount(2, $result['searches']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['searches'][0]['query']);
        $this->assertEquals(['nearest' => [0.4, 0.5, 0.6]], $result['searches'][1]['query']);
    }

    public function testBatchQueryRequestAddSearch(): void
    {
        $batch = new BatchQueryRequest([]);

        $batch->addSearch(
            (new QueryRequest())
                ->setQuery(['nearest' => [0.1, 0.2]])
                ->setLimit(10)
        );

        $result = $batch->toArray();

        $this->assertCount(1, $result['searches']);
    }
}
