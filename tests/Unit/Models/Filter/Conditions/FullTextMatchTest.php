<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\FullTextMatch;

class FullTextMatchTest extends TestCase
{
    public function testFullTextMatchWithValidData(): void
    {
        $filter = new FullTextMatch('title', 'query');

        $this->assertEquals(
            [
                'key' => 'title',
                'match' => [
                    'text' => 'query',
                ],
            ],
            $filter->toArray()
        );
    }
}
