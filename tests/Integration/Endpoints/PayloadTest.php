<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class PayloadTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    private static function sampleCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(3, VectorParams::DISTANCE_COSINE), 'image');
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
                            'image' => 'other image'
                        ]
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
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testUpsertPoint(array $points): void
    {
        $collections = new Collections($this->client);

        $response = $collections->create('sample-collection', self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $response = $collections->setCollectionName('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));

        $this->assertEquals('ok', $response['status'], 'Point Could Not Inserted!');
        $this->assertEquals('acknowledged', $response['result']['status']);

        $response = $collections->setCollectionName('sample-collection')->points()->count();
        $this->assertEquals(2, $response['result']['count'], 'Count Not Matched!');
    }

    /**
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testDeletePoint(array $points): void
    {
        $collections = new Collections($this->client);

        $response = $collections->create('sample-collection', self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $response = $collections->setCollectionName('sample-collection')->points()
            ->upsert(PointsStruct::createFromArray($points));

        $this->assertEquals('ok', $response['status'], 'Point Could Not Inserted!');
        $this->assertEquals('acknowledged', $response['result']['status']);

        $response = $collections->setCollectionName('sample-collection')
            ->points()->delete([1, 3, 400]);
        $this->assertEquals('acknowledged', $response['result']['status'], 'Point Could Not Deleted!');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->delete('sample-collection');
    }
}