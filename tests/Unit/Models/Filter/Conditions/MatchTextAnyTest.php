<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchTextAny;

class MatchTextAnyTest extends TestCase
{
    public function testMatchTextAnyFilterWithValidData(): void
    {
        $filter = new MatchTextAny('description', 'vector database');

        $this->assertEquals(
            [
                'key' => 'description',
                'match' => [
                    'text_any' => 'vector database'
                ]
            ],
            $filter->toArray()
        );
    }
}
