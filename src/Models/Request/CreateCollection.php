<?php
/**
 * Create Collection
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\QuantizationConfig;
use Qdrant\Models\Request\CollectionConfig\WalConfig;

class CreateCollection implements RequestModel
{
    public const SHARDING_METHOD_AUTO = 'auto';
    public const SHARDING_METHOD_CUSTOM = 'custom';

    /**
     * @var VectorParams|VectorParams[]
     */
    protected array $vectors = [];

    /**
     * @var array<string, SparseVectorParams>
     */
    protected array $sparseVectors = [];
    protected ?int $shardNumber = null;
    protected ?string $shardingMethod = null;
    protected ?int $replicationFactor = null;

    protected ?int $writeConsistencyFactor = null;

    protected ?bool $onDiskPayload = null;

    protected ?string $payloadMemory = null;

    protected ?InitFrom $initFrom = null;

    protected ?OptimizersConfig $optimizersConfig = null;

    protected ?HnswConfig $hnswConfig = null;

    protected ?WalConfig $walConfig = null;

    protected ?QuantizationConfig $quantizationConfig = null;

    protected ?array $strictModeConfig = null;

    protected ?array $metadata = null;

    public function addVector(VectorParams $vectorParams, ?string $name = null): CreateCollection
    {
        if ($name !== null) {
            $this->vectors[$name] = $vectorParams->toArray();
        } else {
            $this->vectors = $vectorParams->toArray();
        }

        return $this;
    }

    /**
     * Add a named sparse vector (e.g. for BM25/SPLADE based hybrid search).
     */
    public function addSparseVector(string $name, ?SparseVectorParams $sparseVectorParams = null): CreateCollection
    {
        $this->sparseVectors[$name] = $sparseVectorParams ?? new SparseVectorParams();

        return $this;
    }

    public function setShardNumber(int $shardNumber): CreateCollection
    {
        $this->shardNumber = $shardNumber;

        return $this;
    }

    /**
     * Use SHARDING_METHOD_CUSTOM for user-defined sharding with shard keys.
     */
    public function setShardingMethod(string $shardingMethod): CreateCollection
    {
        $this->shardingMethod = $shardingMethod;

        return $this;
    }

    public function setReplicationFactor(int $replicationFactor): CreateCollection
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    public function setWriteConsistencyFactor(int $writeConsistencyFactor): CreateCollection
    {
        $this->writeConsistencyFactor = $writeConsistencyFactor;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setPayloadMemory() instead.
     */
    public function setOnDiskPayload(bool $onDiskPayload): CreateCollection
    {
        $this->onDiskPayload = $onDiskPayload;

        return $this;
    }

    /**
     * Memory usage strategy for the payload storage, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setPayloadMemory(string $payloadMemory): CreateCollection
    {
        Memory::assertValid($payloadMemory);
        $this->payloadMemory = $payloadMemory;

        return $this;
    }

    /**
     * @deprecated `init_from` was removed in Qdrant 1.16. Use snapshots or the migration tool instead.
     */
    public function setInitFrom(InitFrom $initFrom): CreateCollection
    {
        $this->initFrom = $initFrom;

        return $this;
    }

    public function setOptimizersConfig(OptimizersConfig $optimizersConfig): CreateCollection
    {
        $this->optimizersConfig = $optimizersConfig;

        return $this;
    }

    public function setHnswConfig(HnswConfig $hnswConfig): CreateCollection
    {
        $this->hnswConfig = $hnswConfig;

        return $this;
    }

    public function setWalConfig(WalConfig $walConfig): CreateCollection
    {
        $this->walConfig = $walConfig;

        return $this;
    }

    public function setQuantizationConfig(QuantizationConfig $quantizationConfig): CreateCollection
    {
        $this->quantizationConfig = $quantizationConfig;

        return $this;
    }

    /**
     * Strict mode limits for the collection, e.g. ['enabled' => true, 'max_query_limit' => 100].
     *
     * https://qdrant.tech/documentation/guides/administration/#strict-mode
     */
    public function setStrictModeConfig(array $strictModeConfig): CreateCollection
    {
        $this->strictModeConfig = $strictModeConfig;

        return $this;
    }

    /**
     * Arbitrary key-value metadata attached to the collection, available since Qdrant 1.16.
     */
    public function setMetadata(array $metadata): CreateCollection
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->vectors) {
            $data['vectors'] = $this->vectors;
        }
        if ($this->sparseVectors) {
            $data['sparse_vectors'] = array_map(
                static fn(SparseVectorParams $params) => $params->toArray() ?: new \stdClass(),
                $this->sparseVectors
            );
        }
        if ($this->shardNumber !== null) {
            $data['shard_number'] = $this->shardNumber;
        }
        if ($this->shardingMethod !== null) {
            $data['sharding_method'] = $this->shardingMethod;
        }
        if ($this->replicationFactor !== null) {
            $data['replication_factor'] = $this->replicationFactor;
        }
        if ($this->writeConsistencyFactor !== null) {
            $data['write_consistency_factor'] = $this->writeConsistencyFactor;
        }
        if ($this->onDiskPayload !== null) {
            $data['on_disk_payload'] = $this->onDiskPayload;
        }
        if ($this->payloadMemory !== null) {
            $data['payload'] = ['memory' => $this->payloadMemory];
        }
        if ($this->initFrom !== null) {
            $data['init_from'] = $this->initFrom->toArray();
        }
        if ($this->optimizersConfig !== null) {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->hnswConfig !== null) {
            $data['hnsw_config'] = $this->hnswConfig->toArray();
        }
        if ($this->walConfig !== null) {
            $data['wal_config'] = $this->walConfig->toArray();
        }

        if ($this->quantizationConfig instanceof DisabledQuantization) {
            $data['quantization_config'] = 'Disabled';
        } else if ($this->quantizationConfig !== null) {
            $data['quantization_config'] = $this->quantizationConfig->toArray();
        }
        if ($this->strictModeConfig !== null) {
            $data['strict_mode_config'] = $this->strictModeConfig;
        }
        if ($this->metadata !== null) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }
}
