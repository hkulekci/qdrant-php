<?php
/**
 * @since     Oct 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Models\Request\CollectionConfig\BinaryQuantization;

class BinaryQuantizationTest extends TestCase
{
    public function testBasic(): void
    {
        $config = new BinaryQuantization();

        $this->assertEquals(['binary' => new \stdClass()], $config->toArray());
    }

    public function testWithAlwaysRamTrue(): void
    {
        $config = new BinaryQuantization(true);

        $this->assertEquals([
            'binary' => [
                'always_ram' => true,
            ],
        ], $config->toArray());
    }

    public function testWithAlwaysRamFalse(): void
    {
        $config = new BinaryQuantization(false);

        $this->assertEquals([
            'binary' => [
                'always_ram' => false,
            ],
        ], $config->toArray());
    }
}
