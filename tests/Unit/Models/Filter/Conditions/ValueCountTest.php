<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Condition\ValueCount;

class ValueCountTest extends TestCase
{
    public function testValueCountFilterWithMissingData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ValueCount expects at least one of "gt, gte, lt, lte" key');

        $filter = new ValueCount('key', []);

        $this->assertEquals(
            [
                'key' => 'key',
                'values_count' => []
            ],
            $filter->toArray()
        );
    }

    public function testValueCountFilterWithValidData(): void
    {
        $filter = new ValueCount('key', [
            'gt' => 5
        ]);

        $this->assertEquals(
            [
                'key' => 'key',
                'values_count' => [
                    'gt' => 5
                ]
            ],
            $filter->toArray()
        );
    }
}