<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateVector;
use Qdrant\Models\Request\SparseVectorParams;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Tests\Integration\AbstractIntegration;

class VectorsTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCreateAndDeleteNamedVectors(): void
    {
        $this->createCollections('sample-collection');
        $collection = $this->getCollections('sample-collection');

        $response = $collection->vectors()->create(
            'summary',
            CreateVector::dense(4, VectorParams::DISTANCE_DOT, VectorParams::DATATYPE_FLOAT16),
            ['wait' => 'true']
        );
        $this->assertEquals('ok', $response['status']);

        $response = $collection->vectors()->create(
            'keywords',
            CreateVector::sparse(SparseVectorParams::MODIFIER_IDF),
            ['wait' => 'true']
        );
        $this->assertEquals('ok', $response['status']);

        $params = $collection->info()['result']['config']['params'];
        $this->assertEquals(
            ['size' => 4, 'distance' => 'Dot', 'datatype' => 'float16'],
            $params['vectors']['summary']
        );
        $this->assertEquals(['modifier' => 'idf'], $params['sparse_vectors']['keywords']);

        $response = $collection->vectors()->delete('summary', ['wait' => 'true']);
        $this->assertEquals('ok', $response['status']);

        $params = $collection->info()['result']['config']['params'];
        $this->assertArrayNotHasKey('summary', $params['vectors']);
        $this->assertArrayHasKey('image', $params['vectors']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getCollections('sample-collection')->delete();
    }
}
