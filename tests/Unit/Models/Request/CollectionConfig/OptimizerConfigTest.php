<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;

class OptimizerConfigTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new OptimizersConfig();

        $this->assertEquals([], $config->toArray());
    }

    public function testWithIndexingThreshold(): void
    {
        $config = (new OptimizersConfig())->setIndexingThreshold(10);

        $this->assertEquals([
            'indexing_threshold' => 10
        ], $config->toArray());
    }

    public function testWithMaxOptimizationThreads(): void
    {
        $config = (new OptimizersConfig())->setMaxOptimizationThreads(10);

        $this->assertEquals([
            'max_optimization_threads' => 10
        ], $config->toArray());
    }

    public function testWithMaxSegmentSize(): void
    {
        $config = (new OptimizersConfig())->setMaxSegmentSize(10);

        $this->assertEquals([
            'max_segment_size' => 10
        ], $config->toArray());
    }

    public function testWithDeletedThreshold(): void
    {
        $config = (new OptimizersConfig())->setDeletedThreshold(9.8);

        $this->assertEquals([
            'deleted_threshold' => 9.8
        ], $config->toArray());
    }

    public function testWithMemmapThreshold(): void
    {
        $config = (new OptimizersConfig())->setMemmapThreshold(10);

        $this->assertEquals([
            'memmap_threshold' => 10
        ], $config->toArray());
    }

    public function testWithDefaultSegmentNumber(): void
    {
        $config = (new OptimizersConfig())->setDefaultSegmentNumber(10);

        $this->assertEquals([
            'default_segment_number' => 10
        ], $config->toArray());
    }

    public function testWithFlushIntervalSec(): void
    {
        $config = (new OptimizersConfig())->setFlushIntervalSec(10);

        $this->assertEquals([
            'flush_interval_sec' => 10
        ], $config->toArray());
    }

    public function testWithVacuumMinVectorNumber(): void
    {
        $config = (new OptimizersConfig())->setVacuumMinVectorNumber(10);

        $this->assertEquals([
            'vacuum_min_vector_number' => 10
        ], $config->toArray());
    }
}
