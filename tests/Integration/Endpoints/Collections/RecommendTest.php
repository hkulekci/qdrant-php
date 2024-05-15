<?php
/**
 * @since     Jun 2023
 * @author    Greg Priday <greg@siteorigin.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\PointsStruct;
use Qdrant\Models\Request\RecommendRequest;
use Qdrant\Models\VectorStruct;
use Qdrant\Tests\Integration\AbstractIntegration;

class RecommendTest extends AbstractIntegration
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

    public static function recommendQueryProvider(): array
    {
        return [
            [[1], [2]],  // Positive ids, Negative ids
            [[1, 2], []], // Positive ids, Negative ids
        ];
    }

    /**
     * @dataProvider recommendQueryProvider
     */
    public function testRecommendPoint(array $positive, array $negative): void
    {
        $recommendRequest = (new RecommendRequest($positive, $negative))
            ->setLimit(3)
            ->setUsing('image')
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            );

        $response = $this->getCollections('sample-collection')->points()->recommend($recommendRequest);

        $this->assertEquals('ok', $response['status']);
    }

    public function testRecommendWithThreshold(): void
    {
        // Upsert points
        $points = PointsStruct::createFromArray([
            // These points should match the recommend query
            ['id' => 4, 'vector' => new VectorStruct([0.2, 0.4, 0.5], 'image')],
            ['id' => 5, 'vector' => new VectorStruct([0.3, 0.5, 0.6], 'image')],
            ['id' => 6, 'vector' => new VectorStruct([0.21, 0.41, 0.51], 'image')],
        ]);
        $this->getCollections('sample-collection')->points()->upsert($points);

        // Create recommend request without score threshold
        $positiveIds = [6];
        $recommendRequestWithoutThreshold = (new RecommendRequest($positiveIds))
            ->setLimit(3)
            ->setUsing('image')
            ->setFilter((new Filter())->addMust(new MatchString('image', 'sample image')));

        // Create recommend request with score threshold
        $recommendRequestWithThreshold = clone $recommendRequestWithoutThreshold;
        $recommendRequestWithThreshold->setScoreThreshold(0.9);

        // Perform recommend without score threshold
        $responseWithoutThreshold = $this->getCollections('sample-collection')
            ->points()
            ->recommend($recommendRequestWithoutThreshold);

        // Perform recommend with score threshold
        $responseWithThreshold = $this->getCollections('sample-collection')
            ->points()
            ->recommend($recommendRequestWithThreshold);

        // Check that we got a response in both cases
        $this->assertEquals('ok', $responseWithoutThreshold['status']);
        $this->assertEquals('ok', $responseWithThreshold['status']);

        // Assert that the result count is higher or the same when no score threshold is used
        $this->assertGreaterThan(
            count($responseWithThreshold['result']),
            count($responseWithoutThreshold['result']),
            'The result count should be higher or the same when no score threshold is used'
        );
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}
