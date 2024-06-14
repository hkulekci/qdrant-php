<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchBool;

class MatchBoolTest extends TestCase
{
    public function testMatchBoolFilterWithValidData(): void
    {
        $filter = new MatchBool('key', true);

        $this->assertEquals(
            [
                'key' => 'key',
                'match' => [
                    'value' => true,
                ],
            ],
            $filter->toArray()
        );
    }
}
