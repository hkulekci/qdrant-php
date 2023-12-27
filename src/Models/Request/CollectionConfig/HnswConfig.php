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
    /**
     * @var int|null
     */
    protected $m;

    /**
     * @var int|null
     */
    protected $efConstruct;

    /**
     * @var int|null
     */
    protected $fullScanThreshold;

    /**
     * @var int|null
     */
    protected $maxIndexingThreads;

    /**
     * @var bool|null
     */
    protected $onDisk;

    /**
     * @var int|null
     */
    protected $payloadM;

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

    public function setOnDisk(?bool $onDisk): HnswConfig
    {
        $this->onDisk = $onDisk;

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
        $data = [];
        if ($this->m) {
            $data['m'] = $this->m;
        }
        if ($this->efConstruct) {
            $data['ef_construct'] = $this->efConstruct;
        }
        if ($this->fullScanThreshold) {
            $data['full_scan_threshold'] = $this->fullScanThreshold;
        }
        if ($this->maxIndexingThreads) {
            $data['max_indexing_threads'] = $this->maxIndexingThreads;
        }
        if ($this->onDisk) {
            $data['on_disk'] = $this->onDisk;
        }
        if ($this->payloadM) {
            $data['payload_m'] = $this->payloadM;
        }

        return $data;
    }
}