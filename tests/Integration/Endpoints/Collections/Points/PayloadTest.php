<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections\Points;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchBool;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Filter\Nested;
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
                        'vector' => new VectorStruct([2, 3, 400], 'image'),
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
                    [
                        'id' => 3,
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
    public function testSetPayloadForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $response = $payload->set([2], ['foo' => 'bar', 'image' => 'sample image']);

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('acknowledged', $response['result']['status']);

        $response = $points->id(2);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('payload', $response['result']);
        $this->assertArrayHasKey('foo', $response['result']['payload']);
        $this->assertEquals('bar', $response['result']['payload']['foo']);
    }

    /**
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testDeletePayloadForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->delete([2], ['image']);

        $response = $points->id(2);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('payload', $response['result']);
        $this->assertEmpty($response['result']['payload']);
    }

    /**
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testDeleteOnlyOneKeyFromPayloadForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->set([2], ['foo' => 'bar', 'image' => 'sample image']);

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->delete([2], ['image']);

        $response = $points->id(2);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('payload', $response['result']);
        $this->assertArrayHasKey('foo', $response['result']['payload']);
        $this->assertEquals('bar', $response['result']['payload']['foo']);
    }

    /**
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testDeletePayloadWithFilterForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->set([2], ['foo' => 'bar', 'image' => 'sample image']);

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->delete([2], ['image'], (new Filter())->addMust(new MatchString('foo', 'bar')));

        $response = $points->id(2);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('payload', $response['result']);
        $this->assertArrayNotHasKey('image', $response['result']['payload']);
    }

    public static function nestedPointDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id' => 1,
                        'vector' => new VectorStruct([2, 3, 400], 'image'),
                        'payload' => [
                            'dinosaur' => 't-rex',
                            'diet' => [
                                ['food' => 'leaves', 'likes' => false],
                                ['food' => 'meat', 'likes' => true],
                            ]
                        ]
                    ],
                    [
                        'id' => 2,
                        'vector' => new VectorStruct([1, 3, 300], 'image'),
                        'payload' => [
                            'dinosaur' => 'diplodocus',
                            'diet' => [
                                ['food' => 'leaves', 'likes' => true],
                                ['food' => 'meat', 'likes' => false],
                            ]
                        ]
                    ],
                ]
            ]
        ];
    }

    /**
     * @throws InvalidArgumentException
     * @dataProvider nestedPointDataProvider
     */
    public function testNestedFilterForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $response = $points->scroll(
            (new Filter())->addMust(
                (new Nested(
                    'diet',
                    (new Filter())->addMust(
                        new MatchString('food', 'meat')
                    )->addMust(
                        new MatchBool('likes', false)
                    )
                ))
            )
        );

        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('points', $response['result']);
        $this->assertCount(1, $response['result']['points']);
        $this->assertEquals(2, $response['result']['points'][0]['id']);
    }


    /**
     * @throws InvalidArgumentException
     * @dataProvider basicPointDataProvider
     */
    public function testClearPayloadForPoint(array $pointsArray): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status'], 'Collection Could Not Created!');
        $points = $collections->setCollectionName('sample-collection')->points();
        $points->upsert(PointsStruct::createFromArray($pointsArray));

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->set([2], ['foo' => 'bar', 'image' => 'sample image']);

        $payload = $collections->setCollectionName('sample-collection')->points()->payload();
        $payload->clear([2]);

        $response = $points->id(2);
        $this->assertArrayHasKey('result', $response);
        $this->assertArrayHasKey('payload', $response['result']);
        $this->assertArrayNotHasKey('image', $response['result']['payload']);
        $this->assertArrayNotHasKey('foo', $response['result']['payload']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}