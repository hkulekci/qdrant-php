<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateVector;
use Qdrant\Models\Request\SparseVectorParams;
use Qdrant\Models\Request\VectorParams;

class CreateVectorTest extends TestCase
{
    public function testDense(): void
    {
        $vector = CreateVector::dense(384, VectorParams::DISTANCE_COSINE);

        $this->assertEquals(['dense' => ['size' => 384, 'distance' => 'Cosine']], $vector->toArray());
    }

    public function testDenseWithAllParameters(): void
    {
        $vector = CreateVector::dense(
            128,
            VectorParams::DISTANCE_DOT,
            VectorParams::DATATYPE_TURBO4,
            VectorParams::MULTIVECTOR_COMPARATOR_MAX_SIM
        );

        $this->assertEquals([
            'dense' => [
                'size' => 128,
                'distance' => 'Dot',
                'datatype' => 'turbo4',
                'multivector_config' => ['comparator' => 'max_sim'],
            ]
        ], $vector->toArray());
    }

    public function testDenseWithInvalidDistance(): void
    {
        $this->expectException(InvalidArgumentException::class);

        CreateVector::dense(3, 'Hamming');
    }

    public function testSparse(): void
    {
        $this->assertEquals('{"sparse":{}}', json_encode(CreateVector::sparse()->toArray()));
        $this->assertEquals(
            ['sparse' => ['modifier' => 'idf']],
            CreateVector::sparse(SparseVectorParams::MODIFIER_IDF)->toArray()
        );
    }
}
