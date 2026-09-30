<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\SparseVectorParams;
use Qdrant\Models\Request\VectorParams;

class SparseVectorParamsTest extends TestCase
{
    public function testBasic(): void
    {
        $this->assertEquals([], (new SparseVectorParams())->toArray());
    }

    public function testWithAllParameters(): void
    {
        $params = (new SparseVectorParams())
            ->setModifier(SparseVectorParams::MODIFIER_IDF)
            ->setFullScanThreshold(5000)
            ->setOnDisk(false)
            ->setMemory(Memory::CACHED)
            ->setDatatype(VectorParams::DATATYPE_FLOAT16);

        $this->assertEquals([
            'index' => [
                'full_scan_threshold' => 5000,
                'on_disk' => false,
                'memory' => 'cached',
                'datatype' => 'float16',
            ],
            'modifier' => 'idf',
        ], $params->toArray());
    }

    public function testInvalidModifier(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new SparseVectorParams())->setModifier('bm25');
    }
}
