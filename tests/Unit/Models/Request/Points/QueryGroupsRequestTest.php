<?php

namespace Qdrant\Tests\Unit\Models\Request\Points;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\QueryGroupsRequest;

class QueryGroupsRequestTest extends TestCase
{
    public function testQueryGroupsRequestBasic(): void
    {
        $request = new QueryGroupsRequest('category');

        $this->assertEquals(
            ['group_by' => 'category'],
            $request->toArray()
        );
    }

    public function testQueryGroupsRequestWithAllOptions(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setShardKey('shard_1')
            ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
            ->setUsing('image')
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('city', 'Berlin')
                )
            )
            ->setParams(['hnsw_ef' => 128])
            ->setScoreThreshold(0.5)
            ->setGroupSize(5)
            ->setLimit(10)
            ->setWithPayload(true)
            ->setWithVector(true)
            ->setWithLookup('other_collection');

        $result = $request->toArray();

        $this->assertEquals('category', $result['group_by']);
        $this->assertEquals('shard_1', $result['shard_key']);
        $this->assertEquals(['nearest' => [0.1, 0.2, 0.3]], $result['query']);
        $this->assertEquals('image', $result['using']);
        $this->assertArrayHasKey('filter', $result);
        $this->assertEquals(['hnsw_ef' => 128], $result['params']);
        $this->assertEquals(0.5, $result['score_threshold']);
        $this->assertEquals(5, $result['group_size']);
        $this->assertEquals(10, $result['limit']);
        $this->assertTrue($result['with_payload']);
        $this->assertTrue($result['with_vector']);
        $this->assertEquals('other_collection', $result['with_lookup']);
    }

    public function testQueryGroupsRequestWithLookupArray(): void
    {
        $request = (new QueryGroupsRequest('category'))
            ->setWithLookup([
                'collection' => 'other_collection',
                'with_payload' => ['title'],
            ]);

        $result = $request->toArray();

        $this->assertEquals(
            ['collection' => 'other_collection', 'with_payload' => ['title']],
            $result['with_lookup']
        );
    }
}
