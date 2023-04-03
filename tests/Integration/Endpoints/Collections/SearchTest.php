<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class SearchTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createCollections('sample-collection');
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
                        'vector' => new VectorStruct([1, 3, 400], 'image')
                    ],
                    [
                        'id' => 2,
                        'vector' => new VectorStruct([1, 3, 300], 'image'),
                        'payload' => [
                            'image' => 'sample image'
                        ]
                    ],
                ]
            ]
        ];
    }

    public static function searchQueryProvider(): array
    {
        return [
            [new VectorStruct([1, 2, 300], 'image')],
            [new VectorStruct([100, 20, 300], 'image')],
        ];
    }

    /**
     * @dataProvider searchQueryProvider
     */
    public function testSearchPoint(VectorStruct $vector): void
    {

        $searchRequest = (new SearchRequest($vector))
            ->setLimit(3)
            ->setFilter([
                'must' => [
                    ['key' => 'image', 'match' => ['value' => 'sample image']]
                ]
            ])
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->getCollections('sample-collection')->points()->search($searchRequest);

        $this->assertEquals('ok', $response['status']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->delete('sample-collection');
    }
}