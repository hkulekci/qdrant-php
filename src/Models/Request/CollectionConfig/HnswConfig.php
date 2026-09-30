<?php
/**
 * HnswConfig
 *
 * @since     Dec 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\RequestModel;

class HnswConfig implements RequestModel
{
    protected ?int $m = null;
    protected ?int $efConstruct = null;
    protected ?int $fullScanThreshold = null;
    protected ?int $maxIndexingThreads = null;
    protected ?bool $onDisk = null;
    protected ?string $memory = null;
    protected ?int $payloadM = null;
    protected ?bool $inlineStorage = null;

    public function setM(?int $m): HnswConfig
    {
        if ($m < 0) {
            throw new InvalidArgumentException('m should be bigger than 0');
        }
        $this->m = $m;

        return $this;
    }

    public function setEfConstruct(?int $efConstruct): HnswConfig
    {
        if ($efConstruct < 4) {
            throw new InvalidArgumentException('ef_construct should be bigger than 4');
        }
        $this->efConstruct = $efConstruct;

        return $this;
    }

    public function setFullScanThreshold(?int $fullScanThreshold): HnswConfig
    {
        if ($fullScanThreshold < 10) {
            throw new InvalidArgumentException('full_scan_threshold should be bigger than 10');
        }
        $this->fullScanThreshold = $fullScanThreshold;

        return $this;
    }

    public function setMaxIndexingThreads(?int $maxIndexingThreads): HnswConfig
    {
        if ($maxIndexingThreads < 0) {
            throw new InvalidArgumentException('max_indexing_threads should be bigger than 0');
        }
        $this->maxIndexingThreads = $maxIndexingThreads;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setMemory() instead.
     */
    public function setOnDisk(?bool $onDisk): HnswConfig
    {
        $this->onDisk = $onDisk;

        return $this;
    }

    /**
     * Memory usage strategy for the HNSW index, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setMemory(?string $memory): HnswConfig
    {
        Memory::assertValid($memory);
        $this->memory = $memory;

        return $this;
    }

    /**
     * Store copies of the original and quantized vectors within the HNSW index file, available since Qdrant 1.16.
     * Requires quantization to be enabled.
     */
    public function setInlineStorage(?bool $inlineStorage): HnswConfig
    {
        $this->inlineStorage = $inlineStorage;

        return $this;
    }

    public function setPayloadM(?int $payloadM): HnswConfig
    {
        if ($payloadM < 0) {
            throw new InvalidArgumentException('payload_m should be bigger than 0');
        }
        $this->payloadM = $payloadM;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'm' => $this->m,
            'ef_construct' => $this->efConstruct,
            'full_scan_threshold' => $this->fullScanThreshold,
            'max_indexing_threads' => $this->maxIndexingThreads,
            'on_disk' => $this->onDisk,
            'memory' => $this->memory,
            'payload_m' => $this->payloadM,
            'inline_storage' => $this->inlineStorage,
        ], static fn($value) => $value !== null);
    }
}