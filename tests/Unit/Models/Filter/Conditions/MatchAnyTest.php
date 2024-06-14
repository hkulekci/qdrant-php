<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchAny;

class MatchAnyTest extends TestCase
{
    public function testMatchAnyFilterWithValidData(): void
    {
        $filter = new MatchAny('key', ['value1', 'value2']);

        $this->assertEquals(
            [
                'key' => 'key',
                'match' => [
                    'any' => ['value1', 'value2'],
                ],
            ],
            $filter->toArray()
        );
    }
}
