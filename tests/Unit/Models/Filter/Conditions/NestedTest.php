<?php
/**
 * @since     Jun 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\HasId;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Filter\Nested;

class NestedTest extends TestCase
{
    public function testNestedFilter(): void
    {
        $filter = new Nested('diet', new Filter());

        $this->assertEquals(
            [
                'nested' => [
                    'key' => 'diet',
                    'filter' => []
                ]
            ],
            $filter->toArray()
        );
    }

    public function testNestedWithMustFilter(): void
    {
        $filter = new Nested('diet', (new Filter())->addMust(new MatchString('food', 'meat')));

        $this->assertEquals(
            [
                'nested' => [
                    'key' => 'diet',
                    'filter' => [
                        'must' => [
                            [
                                'key' => 'food',
                                'match' => [
                                    'value' => 'meat'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            $filter->toArray()
        );
    }
}