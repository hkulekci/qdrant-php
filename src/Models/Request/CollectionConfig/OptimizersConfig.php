<?php
/**
 * OptimizersConfig
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Models\Request\RequestModel;

class OptimizersConfig implements RequestModel
{
    /** @var float|null The minimal fraction of deleted vectors in a segment, required to perform segment optimization */
    protected ?float $deletedThreshold = null;

    /**
     * @var int|null The minimal number of vectors in a segment, required to perform segment optimization
     */
    protected ?int $vacuumMinVectorNumber = null;

    /**
     * @var int|null Target amount of segments optimizer will try to keep. Real amount of segments may vary depending on multiple parameters: - Amount of stored points - Current write RPS  It is recommended to select default number of segments as a factor of the number of search threads, so that each segment would be handled evenly by one of the threads If `default_segment_number = 0`, will be automatically selected by the number of available CPUs
     */
    protected ?int $defaultSegmentNumber = null;

    /**
     * @var int|null Do not create segments larger this size (in KiloBytes). Large segments might require disproportionately long indexation times, therefore it makes sense to limit the size of segments.  If indexation speed have more priority for your - make this parameter lower. If search speed is more important - make this parameter higher. Note: 1Kb = 1 vector of size 256
     */
    protected ?int $maxSegmentSize = null;

    /**
     * @var int|null Maximum size (in KiloBytes) of vectors to store in-memory per segment. Segments larger than this threshold will be stored as read-only memmaped file. To enable memmap storage, lower the threshold Note: 1Kb = 1 vector of size 256
     * @deprecated Since Qdrant 1.15, all vector storages use memory maps. Use the vector `memory` option instead.
     */
    protected ?int $memmapThreshold = null;

    /**
     * @var int|null Maximum size (in KiloBytes) of vectors allowed for plain index. Default value based on &lt;https://github.com/google-research/google-research/blob/master/scann/docs/algorithms.md&gt; Note: 1Kb = 1 vector of size 256
     */
    protected ?int $indexingThreshold = null;

    /**
     * @var int|null Minimum interval between forced flushes.
     */
    protected ?int $flushIntervalSec = null;

    /**
     * @var int|string|null Maximum available threads for optimization workers, or "auto"
     */
    protected int|string|null $maxOptimizationThreads = null;

    /**
     * @var bool|null Throttle updates to keep search results served only from optimized segments
     */
    protected ?bool $preventUnoptimized = null;


    public function setDeletedThreshold(?float $deletedThreshold): OptimizersConfig
    {
        $this->deletedThreshold = $deletedThreshold;

        return $this;
    }

    public function setIndexingThreshold(?int $indexingThreshold): OptimizersConfig
    {
        $this->indexingThreshold = $indexingThreshold;

        return $this;
    }

    public function setVacuumMinVectorNumber(?int $vacuumMinVectorNumber): OptimizersConfig
    {
        $this->vacuumMinVectorNumber = $vacuumMinVectorNumber;

        return $this;
    }

    public function setDefaultSegmentNumber(?int $defaultSegmentNumber): OptimizersConfig
    {
        $this->defaultSegmentNumber = $defaultSegmentNumber;

        return $this;
    }

    public function setMaxSegmentSize(?int $maxSegmentSize): OptimizersConfig
    {
        $this->maxSegmentSize = $maxSegmentSize;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.15, all vector storages use memory maps. Use the vector `memory` option instead.
     */
    public function setMemmapThreshold(?int $memmapThreshold): OptimizersConfig
    {
        $this->memmapThreshold = $memmapThreshold;

        return $this;
    }

    public function setFlushIntervalSec(?int $flushIntervalSec): OptimizersConfig
    {
        $this->flushIntervalSec = $flushIntervalSec;

        return $this;
    }

    /**
     * @param int|string|null $maxOptimizationThreads Number of threads, or "auto" to pick it based on available CPUs
     */
    public function setMaxOptimizationThreads(int|string|null $maxOptimizationThreads): OptimizersConfig
    {
        $this->maxOptimizationThreads = $maxOptimizationThreads;

        return $this;
    }

    /**
     * If true, updates are throttled so that searches never hit unoptimized segments, available since Qdrant 1.17.
     */
    public function setPreventUnoptimized(?bool $preventUnoptimized): OptimizersConfig
    {
        $this->preventUnoptimized = $preventUnoptimized;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        if ($this->deletedThreshold !== null) {
            $data['deleted_threshold'] = $this->deletedThreshold;
        }
        if ($this->vacuumMinVectorNumber !== null) {
            $data['vacuum_min_vector_number'] = $this->vacuumMinVectorNumber;
        }
        if ($this->defaultSegmentNumber !== null) {
            $data['default_segment_number'] = $this->defaultSegmentNumber;
        }
        if ($this->maxSegmentSize !== null) {
            $data['max_segment_size'] = $this->maxSegmentSize;
        }
        if ($this->memmapThreshold !== null) {
            $data['memmap_threshold'] = $this->memmapThreshold;
        }
        if ($this->indexingThreshold !== null) {
            $data['indexing_threshold'] = $this->indexingThreshold;
        }
        if ($this->flushIntervalSec !== null) {
            $data['flush_interval_sec'] = $this->flushIntervalSec;
        }
        if ($this->maxOptimizationThreads !== null) {
            $data['max_optimization_threads'] = $this->maxOptimizationThreads;
        }
        if ($this->preventUnoptimized !== null) {
            $data['prevent_unoptimized'] = $this->preventUnoptimized;
        }

        return $data;
    }
}