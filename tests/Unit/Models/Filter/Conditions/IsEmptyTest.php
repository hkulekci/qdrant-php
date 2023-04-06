<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\IsEmpty;

class IsEmptyTest extends TestCase
{
    public function testIsEmptyFilterWithValidData(): void
    {
        $filter = new IsEmpty('key');

        $this->assertEquals(
            [
                'is_empty' => [
                    'key' => 'key'
                ]
            ],
            $filter->toArray()
        );
    }
}