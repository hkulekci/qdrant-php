<?php
/**
 * VectorParamsDiff
 *
 * Changes to apply to an existing vector's parameters, used with UpdateCollection.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\RequestModel;

class VectorParamsDiff implements RequestModel
{
    protected ?HnswConfig $hnswConfig = null;
    protected ?QuantizationConfig $quantizationConfig = null;
    protected ?bool $onDisk = null;
    protected ?string $memory = null;

    public function setHnswConfig(HnswConfig $hnswConfig): VectorParamsDiff
    {
        $this->hnswConfig = $hnswConfig;

        return $this;
    }

    /**
     * Use DisabledQuantization to turn quantization off for this vector.
     */
    public function setQuantizationConfig(QuantizationConfig $quantizationConfig): VectorParamsDiff
    {
        $this->quantizationConfig = $quantizationConfig;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setMemory() instead.
     */
    public function setOnDisk(bool $onDisk): VectorParamsDiff
    {
        $this->onDisk = $onDisk;

        return $this;
    }

    /**
     * Memory usage strategy for the vector storage, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setMemory(string $memory): VectorParamsDiff
    {
        Memory::assertValid($memory);
        $this->memory = $memory;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->hnswConfig !== null) {
            $data['hnsw_config'] = $this->hnswConfig->toArray();
        }
        if ($this->quantizationConfig instanceof DisabledQuantization) {
            $data['quantization_config'] = 'Disabled';
        } elseif ($this->quantizationConfig !== null) {
            $data['quantization_config'] = $this->quantizationConfig->toArray();
        }
        if ($this->onDisk !== null) {
            $data['on_disk'] = $this->onDisk;
        }
        if ($this->memory !== null) {
            $data['memory'] = $this->memory;
        }

        return $data;
    }
}
