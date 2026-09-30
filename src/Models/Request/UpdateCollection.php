<?php
/**
 * Update Collection
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Request\CollectionConfig\CollectionParams;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\QuantizationConfig;
use Qdrant\Models\Request\CollectionConfig\VectorParamsDiff;

class UpdateCollection implements RequestModel
{
    /**
     * @var array<string, VectorParamsDiff>
     */
    protected array $vectors = [];

    /**
     * @var array<string, SparseVectorParams>
     */
    protected array $sparseVectors = [];

    protected ?OptimizersConfig $optimizersConfig = null;

    protected ?HnswConfig $hnswConfig = null;

    protected ?CollectionParams $collectionParams = null;

    protected ?QuantizationConfig $quantizationConfig = null;

    protected ?array $strictModeConfig = null;

    protected ?array $metadata = null;

    /**
     * Update parameters of an existing vector. Use an empty name for the default (unnamed) vector.
     */
    public function addVector(VectorParamsDiff $vectorParams, string $name = ''): UpdateCollection
    {
        $this->vectors[$name] = $vectorParams;

        return $this;
    }

    /**
     * Update parameters of an existing sparse vector.
     */
    public function addSparseVector(string $name, SparseVectorParams $sparseVectorParams): UpdateCollection
    {
        $this->sparseVectors[$name] = $sparseVectorParams;

        return $this;
    }

    public function setOptimizersConfig(OptimizersConfig $optimizersConfig): UpdateCollection
    {
        $this->optimizersConfig = $optimizersConfig;

        return $this;
    }

    public function setHnswConfig(HnswConfig $hnswConfig): UpdateCollection
    {
        $this->hnswConfig = $hnswConfig;

        return $this;
    }

    public function setCollectionParams(CollectionParams $collectionParams): UpdateCollection
    {
        $this->collectionParams = $collectionParams;

        return $this;
    }

    public function setQuantizationConfig(QuantizationConfig $quantizationConfig): UpdateCollection
    {
        $this->quantizationConfig = $quantizationConfig;

        return $this;
    }

    /**
     * Strict mode limits for the collection, e.g. ['enabled' => true, 'max_query_limit' => 100].
     */
    public function setStrictModeConfig(array $strictModeConfig): UpdateCollection
    {
        $this->strictModeConfig = $strictModeConfig;

        return $this;
    }

    /**
     * Metadata to merge into the collection metadata, available since Qdrant 1.16.
     */
    public function setMetadata(array $metadata): UpdateCollection
    {
        $this->metadata = $metadata;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->vectors) {
            $data['vectors'] = array_map(
                static fn(VectorParamsDiff $params) => $params->toArray() ?: new \stdClass(),
                $this->vectors
            );
        }
        if ($this->sparseVectors) {
            $data['sparse_vectors'] = array_map(
                static fn(SparseVectorParams $params) => $params->toArray() ?: new \stdClass(),
                $this->sparseVectors
            );
        }
        if ($this->optimizersConfig) {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->hnswConfig) {
            $data['hnsw_config'] = $this->hnswConfig->toArray();
        }
        if ($this->collectionParams) {
            $data['params'] = $this->collectionParams->toArray();
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