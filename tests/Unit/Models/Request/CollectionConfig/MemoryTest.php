<?php
/**
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Models\Request\CollectionConfig;

use PHPUnit\Framework\TestCase;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CollectionConfig\Memory;

class MemoryTest extends TestCase
{
    public function testValidValues(): void
    {
        foreach ([Memory::COLD, Memory::CACHED, Memory::PINNED, null] as $memory) {
            Memory::assertValid($memory);
        }

        $this->assertSame(['cold', 'cached', 'pinned'], Memory::ALL);
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Memory::assertValid('on_disk');
    }
}
