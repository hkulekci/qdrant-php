<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CreateCollection;
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
}