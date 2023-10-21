<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;

class DisabledQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new DisabledQuantization();

        $this->assertEquals(['Disabled'], $config->toArray());
    }
}
