<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\MatchPhrase;

class MatchPhraseTest extends TestCase
{
    public function testMatchPhraseFilterWithValidData(): void
    {
        $filter = new MatchPhrase('description', 'vector database');

        $this->assertEquals(
            [
                'key' => 'description',
                'match' => [
                    'phrase' => 'vector database'
                ]
            ],
            $filter->toArray()
        );
    }
}
