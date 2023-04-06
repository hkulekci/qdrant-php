<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchInt;

class MatchIntTest extends TestCase
{
    public function testMatchIntFilterWithValidData(): void
    {
        $filter = new MatchInt('key', 1);

        $this->assertEquals(
            [
                'key' => 'key',
                'match' => [
                    'value' => 1
                ]
            ],
            $filter->toArray()
        );
    }
}