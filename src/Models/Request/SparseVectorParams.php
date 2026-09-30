<?php
/**
 * SparseVectorParams
 *
 * https://qdrant.tech/documentation/concepts/vectors/#sparse-vectors
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\Memory;

class SparseVectorParams implements RequestModel
{
    public const MODIFIER_NONE = 'none';
    public const MODIFIER_IDF = 'idf';

    protected ?string $modifier = null;
    protected ?int $fullScanThreshold = null;
    protected ?bool $onDisk = null;
    protected ?string $memory = null;
    protected ?string $datatype = null;

    /**
     * Value modifier applied to the sparse vector, "idf" enables IDF weighting (BM25 style scoring).
     */
    public function setModifier(string $modifier): SparseVectorParams
    {
        if (!in_array($modifier, [self::MODIFIER_NONE, self::MODIFIER_IDF], true)) {
            throw new InvalidArgumentException('Invalid modifier for Sparse Vector Param');
        }
        $this->modifier = $modifier;

        return $this;
    }

    public function setFullScanThreshold(int $fullScanThreshold): SparseVectorParams
    {
        $this->fullScanThreshold = $fullScanThreshold;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setMemory() instead.
     */
    public function setOnDisk(bool $onDisk): SparseVectorParams
    {
        $this->onDisk = $onDisk;

        return $this;
    }

    /**
     * Memory usage strategy for the sparse index, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setMemory(string $memory): SparseVectorParams
    {
        Memory::assertValid($memory);
        $this->memory = $memory;

        return $this;
    }

    /**
     * Datatype used to store the sparse index weights, see VectorParams::DATATYPE_* constants.
     */
    public function setDatatype(string $datatype): SparseVectorParams
    {
        $this->datatype = $datatype;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        $index = array_filter([
            'full_scan_threshold' => $this->fullScanThreshold,
            'on_disk' => $this->onDisk,
            'memory' => $this->memory,
            'datatype' => $this->datatype,
        ], static fn($value) => $value !== null);

        if ($index) {
            $data['index'] = $index;
        }
        if ($this->modifier !== null) {
            $data['modifier'] = $this->modifier;
        }

        return $data;
    }
}
