<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchString;

class MatchStringTest extends TestCase
{
    public function testMatchStringFilterWithValidData(): void
    {
        $filter = new MatchString('key', 'value');

        $this->assertEquals(
            [
                'key' => 'key',
                'match' => [
                    'value' => 'value'
                ]
            ],
            $filter->toArray()
        );
    }
}