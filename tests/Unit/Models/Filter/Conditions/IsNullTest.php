<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Filter\Conditions;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Filter\Condition\HasVector;
use Qdrant\Models\Filter\Condition\IsNull;

class IsNullTest extends TestCase
{
    public function testIsNull(): void
    {
        $this->assertEquals(['is_null' => ['key' => 'deleted_at']], (new IsNull('deleted_at'))->toArray());
    }

    public function testHasVector(): void
    {
        $this->assertEquals(['has_vector' => 'image'], (new HasVector('image'))->toArray());
    }
}
