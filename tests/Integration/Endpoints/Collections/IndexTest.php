<?php
/**
 * @since     Apr 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Tests\Integration\AbstractIntegration;

class IndexTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionIndex(): void
    {
        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $index = $collection->index();
        $this->assertEquals('sample-collection', $index->getCollectionName());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionIndexCreate(): void
    {
        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $index = $collection->index();
        $response = $index->create(new CreateIndex('image', 'keyword'));

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('acknowledged', $response['result']['status']);
        $this->assertEquals('ok', $response['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionIndexDelete(): void
    {
        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $response = $collection->index()->create(new CreateIndex('image', 'keyword'));

        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('acknowledged', $response['result']['status']);
        $this->assertEquals('ok', $response['status']);

        $response = $collection->index()->delete('image');
        $this->assertArrayHasKey('result', $response);
        $this->assertEquals('acknowledged', $response['result']['status']);
        $this->assertEquals('ok', $response['status']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);

        $collections->setCollectionName('sample-collection')->delete();
    }
}