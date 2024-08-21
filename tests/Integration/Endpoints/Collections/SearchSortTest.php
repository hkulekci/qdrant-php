<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Models\Request\ScrollRequest;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class SearchSortTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createCollections('sample-collection');

        $response = $this->getCollections('sample-collection')->index()->create(
            (new CreateIndex('sort', [
                'type' => 'integer',
                'range' => true,
                'lookup' => false,
                'is_principal' => true
            ]))
        );

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);

        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray(self::basicPointDataProvider()[0][0]));

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);
    }

    public static function basicPointDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id' => 1,
                        'vector' => new VectorStruct([1, 3, 400], 'image'),
                        'payload' => [
                            'sort' => 1,
                            'color' => 'red'
                        ]
                    ],
                    [
                        'id' => 2,
                        'vector' => new VectorStruct([1, 3, 300], 'image'),
                        'payload' => [
                            'sort' => 2,
                            'image' => 'red'
                        ]
                    ],
                    [
                        'id' => 3,
                        'vector' => new VectorStruct([1, 3, 300], 'image'),
                        'payload' => [
                            'sort' => 3,
                            'image' => 'green'
                        ]
                    ],
                ]
            ]
        ];
    }

    public function testSearchPoint(): void
    {
        $filter = (new Filter())->addMust(
            new MatchString('color', 'red')
        );

        $scroll = (new ScrollRequest())->setFilter($filter)->setOrderBy('sort');
        $response = $this->getCollections('sample-collection')->points()->scroll($scroll);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(2, $response['result']);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}