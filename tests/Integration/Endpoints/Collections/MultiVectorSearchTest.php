<?php
/**
 * @since     Jul 2023
 * @author    Your Name <your-email@example.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\MultiVectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class MultiVectorSearchTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createCollections('multi-vector-collection');
        $response = $this->getCollections('multi-vector-collection')->points()
            ->upsert(PointsStruct::createFromArray(self::multiVectorPointDataProvider()[0][0]));
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);
    }

    public static function multiVectorPointDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id' => 1,
                        'vector' => new MultiVectorStruct([
                            'image' => [1, 3, 400],
                            'text' => [2, 5, 300]
                        ]),
                    ],
                    [
                        'id' => 2,
                        'vector' => new MultiVectorStruct([
                            'image' => [1, 3, 300],
                            'text' => [2, 5, 200]
                        ]),
                        'payload' => [
                            'images' => 'sample images'
                        ]
                    ]
                ]
            ]
        ];
    }

    public static function multiVectorSearchQueryProvider(): array
    {
        return [
            [new MultiVectorStruct(['image' => [1, 2, 300], 'text' => [2, 4, 200]])],
            [new MultiVectorStruct(['image' => [100, 20, 300], 'text' => [200, 40, 200]])],
        ];
    }

    /**
     * @dataProvider multiVectorSearchQueryProvider
     */
    public function testSearchPointWithMultiVector(MultiVectorStruct $multiVectorStruct): void
    {
        $searchRequest = (new SearchRequest($multiVectorStruct))
            ->setLimit(3)
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('images', 'sample images')
                )
            )
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->getCollections('multi-vector-collection')->points()->search($searchRequest);

        $this->assertEquals('ok', $response['status']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('multi-vector-collection')->delete();
    }
}