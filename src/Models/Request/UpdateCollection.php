<?php
/**
 * Update Collection
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class UpdateCollection implements RequestModel
{
    /**
     * @var array
     */
    protected $vectors;

    /**
     * @var OptimizersConfigDiff|null
     */
    protected $optimizersConfig = null;

    /**
     * @var CollectionParamsDiff|null
     */
    protected $collectionParamsDiff = null;

    public function addVector(VectorParams $vectorParams, string $name = null): UpdateCollection
    {
        if ($name !== null) {
            $this->vectors[$name] = $vectorParams->toArray();
        } else {
            $this->vectors = $vectorParams->toArray();
        }

        return $this;
    }

    public function addOptimizersConfigDiff(OptimizersConfigDiff $optimizersConfig): UpdateCollection
    {
        $this->optimizersConfig = $optimizersConfig;

        return $this;
    }

    public function addCollectionParamsDiff(CollectionParamsDiff $collectionParamsDiff): UpdateCollection
    {
        $this->collectionParamsDiff = $collectionParamsDiff;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->vectors) {
            $data['vectors'] = $this->vectors;
        }
        if ($this->optimizersConfig) {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->collectionParamsDiff) {
            $data['collection_params_diff'] = $this->collectionParamsDiff->toArray();
        }

        return $data;
    }
}