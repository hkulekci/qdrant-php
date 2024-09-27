<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Models\Request\ScrollRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class SearchPayloadTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->createCollections('sample-collection');

        $response = $this->getCollections('sample-collection')->index()->create(
            (new CreateIndex('payload', [
                'type' => 'integer',
                'range' => true,
                'lookup' => false,
                'is_principal' => true
            ])),
            [
                'wait' => 'true'
            ]
        );

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('completed', $response['result']['status']);

        $response = $this->getCollections('sample-collection')->points()
            ->upsert(
                PointsStruct::createFromArray(self::basicPointDataProvider()[0][0]),
                [
                    'wait' => 'true'
                ]
            );

        $this->assertEquals('ok', $response['status']);
        $this->assertEquals('completed', $response['result']['status']);
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
                            'color' => 'red'
                        ]
                    ],
                    [
                        'id' => 3,
                        'vector' => new VectorStruct([1, 3, 300], 'image'),
                        'payload' => [
                            'sort' => 3,
                            'color' => 'green'
                        ]
                    ],
                ]
            ]
        ];
    }

    public function testScrollWithPayload(): void
    {
        $scroll = (new ScrollRequest())->setWithPayload(true);
        $response = $this->getCollections('sample-collection')->points()->scroll($scroll);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(2, $response['result']);
        $this->assertCount(3, $response['result']['points']);
        $this->assertArrayHasKey('payload', $response['result']['points'][0]);
    }

    public function testScrollWithoutPayload(): void
    {
        $scroll = (new ScrollRequest())->setWithPayload(false);
        $response = $this->getCollections('sample-collection')->points()->scroll($scroll);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(2, $response['result']);
        $this->assertCount(3, $response['result']['points']);
        $this->assertArrayNotHasKey('payload', $response['result']['points'][0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}
