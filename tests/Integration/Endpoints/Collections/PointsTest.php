<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class PointsTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionIndex(): void
    {
        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $index = $collection->points();
        $this->assertEquals('sample-collection', $index->getCollectionName());
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

    /**
     * @dataProvider basicPointDataProvider
     */
    public function testUpsertPoint(array $points): void
    {
        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);

        $response = $this->getCollections('sample-collection')->points()->count();
        $this->assertEquals(2, $response['result']['count']);
    }

    public function testUpsertPointWithWrongSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createCollections('sample-collection');
        $points = [
            [
                'id' => 2,
                'vector' => new VectorStruct([500, 1, 3, 300, 400], 'image'),
                'payload' => [
                    'image' => 'sample image'
                ]
            ]
        ];
        $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points), ['wait' => 'true']);
    }

    /**
     * @dataProvider basicPointDataProvider
     */
    public function testIdPoint(array $points): void
    {
        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));
        $this->assertEquals('ok', $response['status']);

        $response = $this->getCollections('sample-collection')->points()->count();
        $this->assertEquals(2, $response['result']['count']);

        $response = $this->getCollections('sample-collection')->points()->id(1);
        $this->assertEquals(1, $response['result']['id']);

        $response = $this->getCollections('sample-collection')->points()->ids([1, 2]);
        $this->assertCount(2, $response['result']);
    }

    /**
     * @dataProvider basicPointDataProvider
     */
    public function testDeletePoint(array $points): void
    {
        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));
        $this->assertEquals('ok', $response['status']);

        $response = $this->getCollections('sample-collection')->points()->count();
        $this->assertEquals(2, $response['result']['count']);

        $response = $this->getCollections('sample-collection')->points()->delete([1, 2]);
        $this->assertEquals('ok', $response['status']);
    }

    /**
     * @dataProvider basicPointDataProvider
     */
    public function testScrollPoint(array $points): void
    {
        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));
        $this->assertEquals('ok', $response['status']);

        $filter = (new Filter())->addMust(new MatchString('image', 'sample image'));
        $response = $this->getCollections('sample-collection')->points()->scroll($filter);
        $this->assertCount(1, $response['result']['points']);
        $this->assertEquals(2, $response['result']['points'][0]['id']);
    }

    /**
     * @dataProvider basicPointDataProvider
     */
    public function testDeleteByFilterPoint(array $points): void
    {
        $this->createCollections('sample-collection');
        $response = $this->getCollections('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));
        $this->assertEquals('ok', $response['status']);

        $filter = (new Filter())->addMust(new MatchString('image', 'sample image'));
        $response = $this->getCollections('sample-collection')->points()->deleteByFilter($filter);
        $this->assertEquals('ok', $response['status']);

        $response = $this->getCollections('sample-collection')->points()->count();
        $this->assertEquals(1, $response['result']['count']);
    }

    public function testBatchUploadPoints(): void
    {
        $this->createCollections('sample-collection');
        $points = PointsStruct::createFromArray([
            [
                'id' => 1,
                'vector' => [
                    'image' => [1, 2, 3]
                ],
            ],
            [
                'id' => 2,
                'vector' => [
                    'image' => [3, 4, 5]
                ],
            ],
            [
                'id' => 3,
                'vector' => [
                    'image' => [3, 4, 5]
                ],
            ],
            [
                'id' => 4,
                'vector' => [
                    'image' => [3, 4, 5]
                ],
            ]
        ]);

        $this->getCollections('sample-collection')->points()->batch($points, ['wait' => 'true']);

        $response = $this->getCollections('sample-collection')->points()->count();
        $this->assertEquals(4, $response['result']['count']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getCollections('sample-collection')->delete();
    }
}