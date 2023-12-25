<?php
/**
 * Update Collection
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\QuantizationConfig;

class UpdateCollection implements RequestModel
{
    protected ?OptimizersConfig $optimizersConfig = null;

    protected ?CollectionParamsDiff $collectionParamsDiff = null;

    protected ?QuantizationConfig $quantizationConfig = null;

    public function setOptimizersConfig(OptimizersConfig $optimizersConfig): UpdateCollection
    {
        $this->optimizersConfig = $optimizersConfig;

        return $this;
    }

    public function addCollectionParamsDiff(CollectionParamsDiff $collectionParamsDiff): UpdateCollection
    {
        $this->collectionParamsDiff = $collectionParamsDiff;

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
        if ($this->optimizersConfig) {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->collectionParamsDiff) {
            $data['collection_params_diff'] = $this->collectionParamsDiff->toArray();
        }

        if ($this->quantizationConfig instanceof DisabledQuantization) {
            $data['quantization_config'] = 'Disabled';
        } else if ($this->quantizationConfig !== null) {
            $data['quantization_config'] = $this->quantizationConfig->toArray();
        }

        return $data;
    }
}