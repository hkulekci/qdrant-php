<?php
/**
 * @since     Jun 2024
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\CreateShardKey;
use Qdrant\Models\Request\CollectionConfig\DeleteShardKey;
use Qdrant\Models\Request\CreateIndex;
use Qdrant\Tests\Integration\AbstractIntegration;

class ShardsTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionCreateShards(): void
    {
        //TODO: We need to find a way to enable distributed mode in tests?
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bad request: Distributed mode disabled');

        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $shards = $collection->shards();
        $this->assertEquals('sample-collection', $shards->getCollectionName());

        $shards->create(new CreateShardKey(1));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionDeleteShards(): void
    {
        //TODO: We need to find a way to enable distributed mode in tests?
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bad request: Distributed mode disabled');

        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $shards = $collection->shards();
        $this->assertEquals('sample-collection', $shards->getCollectionName());

        $shards->delete(new DeleteShardKey(1));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->getCollections('sample-collection')->delete();
    }
}