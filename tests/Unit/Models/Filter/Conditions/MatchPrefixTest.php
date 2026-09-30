<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchPrefix;

class MatchPrefixTest extends TestCase
{
    public function testMatchPrefixFilterWithValidData(): void
    {
        $filter = new MatchPrefix('category', 'elec');

        $this->assertEquals(
            [
                'key' => 'category',
                'match' => [
                    'prefix' => 'elec'
                ]
            ],
            $filter->toArray()
        );
    }
}
