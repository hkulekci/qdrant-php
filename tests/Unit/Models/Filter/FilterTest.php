<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchInt;
use Qdrant\Models\Filter\Filter;

class FilterTest extends TestCase
{
    public function testEmptyFilter(): void
    {
        $filter = new Filter();

        $this->assertEmpty($filter->toArray());
    }

    public function testAddMustFilter(): void
    {
        $filter = (new Filter())->addMust(new MatchInt('key', 1));

        $this->assertEquals(
            [
                'must' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 1
                        ]
                    ]
                ]
            ],
            $filter->toArray()
        );
    }

    public function testAddMustNotFilter(): void
    {
        $filter = (new Filter())->addMustNot(new MatchInt('key', 1));

        $this->assertEquals(
            [
                'must_not' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 1
                        ]
                    ]
                ]
            ],
            $filter->toArray()
        );
    }

    public function testAddShouldFilter(): void
    {
        $filter = (new Filter())->addShould(new MatchInt('key', 1));

        $this->assertEquals(
            [
                'should' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 1
                        ]
                    ]
                ]
            ],
            $filter->toArray()
        );
    }

    public function testAddMinShouldFilter(): void
    {
        $filter = (new Filter())->addMinShould(new MatchInt('key', 1))->setMinShouldCount(1);

        $this->assertEquals(
            [
                'min_should' => [
                    'conditions' => [
                        [
                            'key' => 'key',
                            'match' => [
                                'value' => 1
                            ]
                        ]
                    ],
                    'min_count' => 1
                ],
            ],
            $filter->toArray()
        );
    }

    public function testAddAllFilter(): void
    {
        $filter = (new Filter())
            ->addShould(new MatchInt('key', 1))
            ->addMust(new MatchInt('key', 2))
            ->addMustNot(new MatchInt('key', 3))
            ->addMinShould(new MatchInt('key', 4))
            ->setMinShouldCount(1);

        $this->assertArrayHasKey('must', $filter->toArray());
        $this->assertArrayHasKey('must_not', $filter->toArray());
        $this->assertArrayHasKey('should', $filter->toArray());
        $this->assertEquals(
            [
                'must' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 2
                        ]
                    ]
                ],
                'must_not' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 3
                        ]
                    ]
                ],
                'should' => [
                    [
                        'key' => 'key',
                        'match' => [
                            'value' => 1
                        ]
                    ]
                ],
                'min_should' => [
                    'conditions' => [
                        [
                            'key' => 'key',
                            'match' => [
                                'value' => 4
                            ]
                        ]
                    ],
                    'min_count' => 1
                ],
            ],
            $filter->toArray()
        );
    }
}