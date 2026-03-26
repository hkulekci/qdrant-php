<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\QueryRequest;

class QueryRequestTest extends TestCase
{
    public function testEmptyQueryRequest(): void
    {
        $request = new QueryRequest();

        $this->assertEquals([], $request->toArray());
    }

    public function testQueryRequestWithNearestVector(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setLimit(10);

        $this->assertEquals(
            [
                'query' => ['nearest' => [0.1, 0.2, 0.3]],
                'limit' => 10,
            ],
            $request->toArray()
        );
    }

    public function testQueryRequestWithRecommend(): void
    {
        $request = (new QueryRequest())
            ->setQuery([
                'recommend' => [
                    'positive' => [1, 2, 3],
                    'negative' => [4],
                    'strategy' => 'average_vector',
                ]
            ])
            ->setLimit(5);

        $result = $request->toArray();

        $this->assertEquals([1, 2, 3], $result['query']['recommend']['positive']);
        $this->assertEquals([4], $result['query']['recommend']['negative']);
        $this->assertEquals(5, $result['limit']);
    }

    public function testQueryRequestWithFusion(): void
    {
        $request = (new QueryRequest())
            ->setPrefetch([
                ['query' => ['nearest' => [0.1, 0.2]], 'using' => 'dense', 'limit' => 100],
                ['query' => ['nearest' => [0.3, 0.4]], 'using' => 'sparse', 'limit' => 100],
            ])
            ->setQuery(['fusion' => 'rrf'])
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertCount(2, $result['prefetch']);
        $this->assertEquals(['fusion' => 'rrf'], $result['query']);
        $this->assertEquals(10, $result['limit']);
    }

    public function testQueryRequestWithFilter(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('city', 'Berlin')
                )
            )
            ->setLimit(10);

        $result = $request->toArray();

        $this->assertEquals(
            ['must' => [['key' => 'city', 'match' => ['value' => 'Berlin']]]],
            $result['filter']
        );
    }

    public function testQueryRequestWithAllOptions(): void
    {
        $request = (new QueryRequest())
            ->setShardKey('shard_1')
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setParams(['hnsw_ef' => 128, 'exact' => false])
            ->setScoreThreshold(0.5)
            ->setLimit(10)
            ->setOffset(5)
            ->setWithPayload(true)
            ->setWithVector(['image'])
            ->setLookupFrom(['collection' => 'other_collection']);

        $result = $request->toArray();

        $this->assertEquals('shard_1', $result['shard_key']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
        $this->assertEquals('image', $result['using']);
        $this->assertEquals(['hnsw_ef' => 128, 'exact' => false], $result['params']);
        $this->assertEquals(0.5, $result['score_threshold']);
        $this->assertEquals(10, $result['limit']);
        $this->assertEquals(5, $result['offset']);
        $this->assertTrue($result['with_payload']);
        $this->assertEquals(['image'], $result['with_vector']);
        $this->assertEquals(['collection' => 'other_collection'], $result['lookup_from']);
    }

    public function testQueryRequestWithOrderBy(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['order_by' => 'timestamp'])
            ->setLimit(10);

        $this->assertEquals(
            [
                'query' => ['order_by' => 'timestamp'],
                'limit' => 10,
            ],
            $request->toArray()
        );
    }

    public function testQueryRequestWithSample(): void
    {
        $request = (new QueryRequest())
            ->setQuery(['sample' => 'random'])
            ->setLimit(10);

        $this->assertEquals(
            [
                'query' => ['sample' => 'random'],
                'limit' => 10,
            ],
            $request->toArray()
        );
    }
}
