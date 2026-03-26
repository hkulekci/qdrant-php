<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;

class SearchRequestTest extends TestCase
{
    public function testSearchRequestWithVector(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithoutLimitThrowsException(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = new SearchRequest($vector);

        $this->expectException(InvalidArgumentException::class);
        $searchRequest->toArray();
    }

    public function testSearchRequestWithVectorAndLimit(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithVectorAndLimitAndOffset(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setOffset(10);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'limit' => 10,
                'offset' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithPayload(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setWithPayload(true);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'limit' => 10,
                'with_payload' => true
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithVectorParams(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setWithVector(true);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'limit' => 10,
                'with_vector' => true
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithParams(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setParams([
            'test1' => 'param1',
            'test2' => 'param2'
        ]);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'params' => [
                    'test1' => 'param1',
                    'test2' => 'param2'
                ],
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithFilter(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setFilter(
            (new Filter())->addMust(
                new MatchString('image', 'sample image')
            )
        );

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'filter' => [
                    'must' => [
                        ['key' => 'image', 'match' => ['value' => 'sample image']]
                    ]
                ],
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithShardKey(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setShardKey('shard_1');

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'shard_key' => 'shard_1',
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithShardKeyArray(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setLimit(10)->setShardKey(['shard_1', 'shard_2']);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'shard_key' => ['shard_1', 'shard_2'],
                'limit' => 10
            ],
            $searchRequest->toArray()
        );
    }
}