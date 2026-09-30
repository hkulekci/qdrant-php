<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\Slice;

class SliceTest extends TestCase
{
    public function testSliceWithValidData(): void
    {
        $this->assertEquals(
            ['slice' => ['index' => 1, 'total' => 4]],
            (new Slice(1, 4))->toArray()
        );
    }

    public function testSliceWithIndexOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Slice(4, 4);
    }

    public function testSliceWithInvalidTotal(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Slice(0, 0);
    }
}
