<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\Range;

class RangeTest extends TestCase
{
    public function testRangeFilterWithMissingData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Range expects at least one of "gt, gte, lt, lte" keys');

        $filter = new Range('key', []);

        $this->assertEquals(
            [
                'key' => 'key',
                'range' => [],
            ],
            $filter->toArray()
        );
    }

    public function testRangeFilterWithValidData(): void
    {
        $filter = new Range('key', [
            'gt' => 5,
        ]);

        $this->assertEquals(
            [
                'key' => 'key',
                'range' => [
                    'gt' => 5,
                ],
            ],
            $filter->toArray()
        );
    }
}
