<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\HasId;

class HasIdTest extends TestCase
{
    public function testHarIdFilterWithValidData(): void
    {
        $filter = new HasId([1, 2]);

        $this->assertEquals(
            [
                'has_id' => [1, 2]
            ],
            $filter->toArray()
        );
    }
}