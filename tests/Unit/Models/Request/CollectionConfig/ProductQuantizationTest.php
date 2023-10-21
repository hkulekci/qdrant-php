<?php
/**
 * @since     Oct 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\ProductQuantization;

class ProductQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new ProductQuantization('x4');

        $this->assertEquals(['product' => ['compression' => 'x4']], $config->toArray());
    }

    public function testWithAllParameters(): void
    {
        $config = new ProductQuantization('x4', true);

        $this->assertEquals([
            'product' => [
                'compression' => 'x4',
                'always_ram' => true,
            ]
        ], $config->toArray());
    }
}
