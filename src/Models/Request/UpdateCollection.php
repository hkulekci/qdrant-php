<?php
/**
 * Update Collection
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Request\CollectionConfig\CollectionParams;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\QuantizationConfig;

class UpdateCollection implements RequestModel
{
    protected ?OptimizersConfig $optimizersConfig = null;

    protected ?HnswConfig $hnswConfig = null;

    protected ?CollectionParams $collectionParams = null;

    protected ?QuantizationConfig $quantizationConfig = null;

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

    public function toArray(): array
    {
        $data = [];
        if ($this->optimizersConfig)
        {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->hnswConfig)
        {
            $data['hnsw_config'] = $this->hnswConfig->toArray();
        }
        if ($this->collectionParams)
        {
            $data['params'] = $this->collectionParams->toArray();
        }

        if ($this->quantizationConfig instanceof DisabledQuantization)
        {
            $data['quantization_config'] = 'Disabled';
        }
        elseif ($this->quantizationConfig !== null)
        {
            $data['quantization_config'] = $this->quantizationConfig->toArray();
        }

        return $data;
    }
}
