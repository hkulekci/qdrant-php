<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\TurboQuantization;
use Qdrant\Models\Request\CollectionConfig\WalConfig;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\SparseVectorParams;
use Qdrant\Models\Request\VectorParams;

class CreateCollectionTest extends TestCase
{
    public function testCreateCollectionWithVector(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE), 'image');

        $this->assertEquals(
            [
                'vectors' => [
                    'image' => [
                        'size' => '1024',
                        'distance' => 'Cosine'
                    ]
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithVectorWithoutName(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));


        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithShardNumber(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $collection->setShardNumber(1);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'shard_number' => 1
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithReplicationFactor(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $collection->setReplicationFactor(1);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'replication_factor' => 1
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithReplicationFactorAsZero(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $collection->setShardNumber(1);
        $collection->setReplicationFactor(0);
        $collection->setWriteConsistencyFactor(0);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'shard_number' => 1,
                'replication_factor' => 0,
                'write_consistency_factor' => 0
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithWriteConsistencyFactor(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $collection->setWriteConsistencyFactor(3);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'write_consistency_factor' => 3
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithOnDiskPayload(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $collection->setOnDiskPayload(false);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'on_disk_payload' => false
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithOptimizersConfig(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $diff = (new OptimizersConfig())
            ->setMaxSegmentSize(1)
            ->setDefaultSegmentNumber(1);

        $collection->setOptimizersConfig($diff);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'optimizers_config' => [
                    'max_segment_size' => 1,
                    'default_segment_number' => 1,
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithHnswConfig(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $diff = (new HnswConfig())
            ->setM(1)
            ->setPayloadM(1);

        $collection->setHnswConfig($diff);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'hnsw_config' => [
                    'm' => 1,
                    'payload_m' => 1,
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithHnswConfigAndZeroM(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $diff = (new HnswConfig())
            ->setM(0)
            ->setPayloadM(1);

        $collection->setHnswConfig($diff);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'hnsw_config' => [
                    'm' => 0,
                    'payload_m' => 1,
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithWalConfig(): void
    {
        $collection = new CreateCollection();
        $collection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE));
        $diff = (new WalConfig())
            ->setWalSegmentsAhead(1)
            ->setWalCapacityMb(1);

        $collection->setWalConfig($diff);

        $this->assertEquals(
            [
                'vectors' => [
                    'size' => '1024',
                    'distance' => 'Cosine'
                ],
                'wal_config' => [
                    'wal_segments_ahead' => 1,
                    'wal_capacity_mb' => 1,
                ]
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithTurboQuantization(): void
    {
        $collection = (new CreateCollection())
            ->addVector(new VectorParams(1536, VectorParams::DISTANCE_COSINE))
            ->setQuantizationConfig(new TurboQuantization(TurboQuantization::BITS_2, Memory::PINNED));

        $this->assertEquals(
            [
                'vectors' => ['size' => 1536, 'distance' => 'Cosine'],
                'quantization_config' => ['turbo' => ['bits' => 'bits2', 'memory' => 'pinned']],
            ],
            $collection->toArray()
        );
    }

    public function testCreateCollectionWithNewOptions(): void
    {
        $collection = (new CreateCollection())
            ->addVector(new VectorParams(3, VectorParams::DISTANCE_COSINE), 'dense')
            ->addSparseVector('bm25', (new SparseVectorParams())->setModifier(SparseVectorParams::MODIFIER_IDF))
            ->addSparseVector('splade')
            ->setShardingMethod(CreateCollection::SHARDING_METHOD_CUSTOM)
            ->setPayloadMemory(Memory::COLD)
            ->setStrictModeConfig(['enabled' => true, 'max_query_limit' => 100])
            ->setMetadata(['owner' => 'search-team']);

        $this->assertEquals(
            [
                'vectors' => ['dense' => ['size' => 3, 'distance' => 'Cosine']],
                'sparse_vectors' => [
                    'bm25' => ['modifier' => 'idf'],
                    'splade' => new \stdClass(),
                ],
                'sharding_method' => 'custom',
                'payload' => ['memory' => 'cold'],
                'strict_mode_config' => ['enabled' => true, 'max_query_limit' => 100],
                'metadata' => ['owner' => 'search-team'],
            ],
            $collection->toArray()
        );
        $this->assertStringContainsString('"splade":{}', json_encode($collection->toArray()));
    }

    public function testCreateCollectionWithOnlySparseVectors(): void
    {
        $collection = (new CreateCollection())->addSparseVector('text');

        $this->assertEquals(['sparse_vectors' => ['text' => new \stdClass()]], $collection->toArray());
    }
}
