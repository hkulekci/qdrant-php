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
    protected ?OptimizersConfigDiff $optimizersConfig = null;

    protected ?CollectionParamsDiff $collectionParamsDiff = null;

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
        if ($this->optimizersConfig) {
            $data['optimizers_config'] = $this->optimizersConfig->toArray();
        }
        if ($this->collectionParamsDiff) {
            $data['collection_params_diff'] = $this->collectionParamsDiff->toArray();
        }

        return $data;
    }
}