<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;

class SearchRequestTest extends TestCase
{
    public function testSearchRequestWithVector(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = new SearchRequest($vector);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ]
            ],
            $searchRequest->toArray()
        );
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

        $searchRequest = (new SearchRequest($vector))->setWithPayload(true);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'with_payload' => true
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithVectorParams(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setWithVector(true);

        $this->assertEquals(
            [
                'vector' => [
                    'name' => 'image',
                    'vector' => [0, 300, 1]
                ],
                'with_vector' => true
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithParams(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setParams([
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
                ]
            ],
            $searchRequest->toArray()
        );
    }

    public function testSearchRequestWithFilter(): void
    {
        $vector = new VectorStruct([0, 300, 1], 'image');

        $searchRequest = (new SearchRequest($vector))->setFilter([
            'must' => [
                ['key' => 'image', 'match' => ['value' => 'sample image']]
            ]
        ]);

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
                ]
            ],
            $searchRequest->toArray()
        );
    }
}