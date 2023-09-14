<?php
/**
 * OptimizersConfigDiff
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class OptimizersConfigDiff implements RequestModel
{
    /** @var float|null The minimal fraction of deleted vectors in a segment, required to perform segment optimization */
    protected $deletedThreshold = null;

    /**
     * @var int|null The minimal number of vectors in a segment, required to perform segment optimization
     */
    protected $vacuumMinVectorNumber = null;

    /**
     * @var int|null Target amount of segments optimizer will try to keep. Real amount of segments may vary depending on multiple parameters: - Amount of stored points - Current write RPS  It is recommended to select default number of segments as a factor of the number of search threads, so that each segment would be handled evenly by one of the threads If `default_segment_number = 0`, will be automatically selected by the number of available CPUs
     */
    protected $defaultSegmentNumber = null;

    /**
     * @var int|null Do not create segments larger this size (in KiloBytes). Large segments might require disproportionately long indexation times, therefore it makes sense to limit the size of segments.  If indexation speed have more priority for your - make this parameter lower. If search speed is more important - make this parameter higher. Note: 1Kb = 1 vector of size 256
     */
    protected $maxSegmentSize = null;

    /**
     * @var int|null Maximum size (in KiloBytes) of vectors to store in-memory per segment. Segments larger than this threshold will be stored as read-only memmaped file. To enable memmap storage, lower the threshold Note: 1Kb = 1 vector of size 256
     */
    protected $memmapThreshold = null;

    /**
     * @var int|null Maximum size (in KiloBytes) of vectors allowed for plain index. Default value based on &lt;https://github.com/google-research/google-research/blob/master/scann/docs/algorithms.md&gt; Note: 1Kb = 1 vector of size 256
     */
    protected $indexingThreshold = null;

    /**
     * @var int|null Minimum interval between forced flushes.
     */
    protected $flushIntervalSec = null;

    /**
     * @var int|null Maximum available threads for optimization workers
     */
    protected $maxOptimizationThreads = null;

    public function toArray(): array
    {
        $data = [];
        if ($this->deletedThreshold) {
            $data['deleted_threshold'] = $this->deletedThreshold;
        }
        if ($this->vacuumMinVectorNumber) {
            $data['vacuum_min_vector_number'] = $this->vacuumMinVectorNumber;
        }
        if ($this->defaultSegmentNumber) {
            $data['default_segment_number'] = $this->defaultSegmentNumber;
        }
        if ($this->maxSegmentSize) {
            $data['max_segment_size'] = $this->maxSegmentSize;
        }
        if ($this->memmapThreshold) {
            $data['memmap_threshold'] = $this->memmapThreshold;
        }
        if ($this->indexingThreshold) {
            $data['indexing_threshold'] = $this->indexingThreshold;
        }
        if ($this->flushIntervalSec) {
            $data['flush_interval_sec'] = $this->flushIntervalSec;
        }
        if ($this->maxOptimizationThreads) {
            $data['max_optimization_threads'] = $this->maxOptimizationThreads;
        }

        return $data;
    }

    public function setDeletedThreshold(?float $deletedThreshold): OptimizersConfigDiff
    {
        $this->deletedThreshold = $deletedThreshold;

        return $this;
    }

    public function setIndexingThreshold(?int $indexingThreshold): OptimizersConfigDiff
    {
        $this->indexingThreshold = $indexingThreshold;

        return $this;
    }
}