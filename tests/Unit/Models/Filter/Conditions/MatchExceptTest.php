<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchAny;
use Qdrant\Models\Filter\Condition\MatchExcept;

class MatchExceptTest extends TestCase
{
    public function testMatchExceptFilterWithValidData(): void
    {
        $filter = new MatchExcept('key', ['value1', 'value2']);

        $this->assertEquals(
            [
                'key' => 'key',
                'match' => [
                    'except' => ['value1', 'value2']
                ]
            ],
            $filter->toArray()
        );
    }
}