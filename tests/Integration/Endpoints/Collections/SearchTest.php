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
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            )
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->getCollections('sample-collection')->points()->search($searchRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(1, $response['result']);
    }


    /**
     * @dataProvider searchQueryProvider
     */
    public function testSearchPointWithQueryParams(VectorStruct $vector): void
    {
        $searchRequest = (new SearchRequest($vector))
            ->setLimit(3)
            ->setFilter(
                (new Filter())->addMust(
                    new MatchString('image', 'sample image')
                )
            )
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->getCollections('sample-collection')
            ->points()->search($searchRequest, ['timeout' => 1, 'consistency' => 'all']);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(1, $response['result']);
    }

    /**
     * @dataProvider searchQueryProvider
     */
    public function testSearchPointEmptyFilter(VectorStruct $vector): void
    {
        $searchRequest = (new SearchRequest($vector))
            ->setLimit(3)
            ->setFilter(new Filter())
            ->setParams([
                'hnsw_ef' => 128,
                'exact' => false,
            ]);

        $response = $this->getCollections('sample-collection')->points()->search($searchRequest);

        $this->assertEquals('ok', $response['status']);
        $this->assertCount(2, $response['result']);
    }

    public function testSearchWithThreshold(): void
    {
        // Upsert points
        $points = PointsStruct::createFromArray([
            // This is a point that'll match the search query
            ['id' => 2, 'vector' => new VectorStruct([0.1, 0.3, 0.2], 'image')],
            // This will be the opposite of the search query, so should be filtered
            ['id' => 3, 'vector' => new VectorStruct([-0.1, -0.3, -0.2], 'image')],
        ]);
        $this->getCollections('sample-collection')->points()->upsert($points);

        // Create search request without score threshold
        $vector = new VectorStruct([0.1, 0.3, 0.2], 'image');
        $searchRequestWithoutThreshold = (new SearchRequest($vector))
            ->setLimit(3)
            ->setParams(['hnsw_ef' => 128, 'exact' => false]);

        // Create search request with score threshold
        $searchRequestWithThreshold = clone $searchRequestWithoutThreshold;
        $searchRequestWithThreshold->setScoreThreshold(0.5);

        // Perform search without score threshold
        $responseWithoutThreshold = $this->getCollections('sample-collection')
            ->points()
            ->search($searchRequestWithoutThreshold);

        // Perform search with score threshold
        $responseWithThreshold = $this->getCollections('sample-collection')
            ->points()
            ->search($searchRequestWithThreshold);

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