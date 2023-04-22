<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Qdrant\Domain\Assert;
use Qdrant\Exception\InvalidArgumentException;

class AssertTest extends TestCase
{
    public function testInvalidAssertKeyNotExists(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $data = [
            'foo' => 'bar'
        ];

        Assert::keysNotExists($data, ['foo']);
    }

    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testValidAssertKeyNotExists(): void
    {
        $data = [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];

        Assert::keysNotExists($data, ['bar']);
    }

    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testValidAssertExistsAtLeastOne(): void
    {
        $data = [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];

        Assert::keysExistsAtLeastOne($data, ['foo1', 'foo2']);
        Assert::keysExistsAtLeastOne($data, ['foo4', 'foo2']);
    }

    /**
     * @return void
     */
    public function testInvalidAssertExistsAtLeastOne(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $data = [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];

        Assert::keysExistsAtLeastOne($data, ['foo4', 'foo5']);
    }

    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testValidAssertKeysExists(): void
    {
        $data = [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];

        Assert::keysExists($data, ['foo1', 'foo2']);
    }

    /**
     * @return void
     */
    public function testInvalidAssertKeysExists(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $data = [
            'foo1' => 'bar1',
            'foo2' => 'bar2',
            'foo3' => 'bar3',
        ];

        Assert::keysExists($data, ['foo2', 'foo5']);
    }
}