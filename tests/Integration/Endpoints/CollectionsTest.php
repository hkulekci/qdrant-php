<?php
/**
 * Collections
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Integration\Endpoints;

use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfigDiff;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\InitFrom;
use Qdrant\Models\Request\UpdateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Tests\Integration\AbstractIntegration;

class CollectionsTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    private static function sampleCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function otherCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image')
            ->setInitFrom(new InitFrom('sample-collection'));
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function multiVectorCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image')
            ->addVector(new VectorParams(8, VectorParams::DISTANCE_DOT), 'text');
    }

    public function testCollections(): void
    {
        $collections = new Collections($this->client);
        $response = $collections->list();
        $this->assertEquals('ok', $response['status']);
    }

    public function testCollectionsCluster(): void
    {
        $collections = new Collections($this->client);

        $response = $collections->setCollectionName('sample-collection')
            ->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->setCollectionName('sample-collection')->cluster()->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('peer_id', $response['result']);
        $this->assertArrayHasKey('shard_count', $response['result']);
        $this->assertArrayHasKey('local_shards', $response['result']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCreateCollection(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCreateCollectionWithoutParameters(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCreateMultiVectorCollection(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::multiVectorCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testUpdateCollection(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);

        $response = $collections->update(
            (new UpdateCollection())->setOptimizersConfigDiff(
                (new OptimizersConfigDiff())
                    ->setIndexingThreshold(10000)
            )
        );
        $this->assertEquals('ok', $response['status']);
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testCreateCollectionWithOptimizersConfig(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $request = (new CreateCollection())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image')
            ->setOptimizersConfigDiff(
                (new OptimizersConfigDiff())->setIndexingThreshold(10000)
            );

        $response = $collections->create($request);
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCreateCollectionFromAnotherCollection(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->info();
        $this->assertEquals('green', $response['result']['status']);

        $collections->setCollectionName('other-collection');
        $response = $collections->create(self::otherCollectionOption());
        $this->assertEquals('ok', $response['status']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection')->delete();
        $collections->setCollectionName('other-collection')->delete();
    }
}