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
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\InitFrom;
use Qdrant\Models\Request\SparseVectorParams;
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

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionExits(): void
    {
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');

        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $collections->exists();
        $this->assertEquals(true, $response['result']['exists']);

        $response = $collections->setCollectionName('sample-collection-that-not-exists')->exists();
        $this->assertEquals(false, $response['result']['exists']);
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
            (new UpdateCollection())->setOptimizersConfig(
                (new OptimizersConfig())
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
            ->setOptimizersConfig(
                (new OptimizersConfig())->setIndexingThreshold(10000)
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

    /**
     * @throws InvalidArgumentException
     */
    public function testCreateCollectionWithSparseVectorsMetadataAndMemoryTiers(): void
    {
        $request = (new CreateCollection())
            ->addVector(
                (new VectorParams(3, VectorParams::DISTANCE_MANHATTAN))->setMemory(Memory::COLD),
                'image'
            )
            ->addSparseVector('keywords', (new SparseVectorParams())->setModifier(SparseVectorParams::MODIFIER_IDF))
            ->setHnswConfig((new HnswConfig())->setMemory(Memory::CACHED))
            ->setOptimizersConfig((new OptimizersConfig())->setPreventUnoptimized(true))
            ->setPayloadMemory(Memory::COLD)
            ->setStrictModeConfig(['enabled' => true, 'max_query_limit' => 50])
            ->setMetadata(['team' => 'search']);

        $collections = (new Collections($this->client))->setCollectionName('sample-collection');
        $this->assertEquals('ok', $collections->create($request)['status']);

        $config = $collections->info()['result']['config'];
        $this->assertEquals('Manhattan', $config['params']['vectors']['image']['distance']);
        $this->assertEquals('cold', $config['params']['vectors']['image']['memory']);
        $this->assertEquals(['modifier' => 'idf'], $config['params']['sparse_vectors']['keywords']);
        $this->assertEquals(['memory' => 'cold'], $config['params']['payload']);
        $this->assertEquals('cached', $config['hnsw_config']['memory']);
        $this->assertTrue($config['optimizer_config']['prevent_unoptimized']);
        $this->assertEquals(50, $config['strict_mode_config']['max_query_limit']);
        $this->assertEquals(['team' => 'search'], $config['metadata']);

        $response = $collections->update((new UpdateCollection())->setMetadata(['version' => 2]));
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(
            ['team' => 'search', 'version' => 2],
            $collections->info()['result']['config']['metadata']
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionOptimizations(): void
    {
        $collections = (new Collections($this->client))->setCollectionName('sample-collection');
        $collections->create(self::sampleCollectionOption());

        $response = $collections->optimizations(['with' => 'queued,completed']);
        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('summary', $response['result']);
        $this->assertArrayHasKey('running', $response['result']);
        $this->assertArrayHasKey('queued', $response['result']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection')->delete();
        $collections->setCollectionName('other-collection')->delete();
    }
}
