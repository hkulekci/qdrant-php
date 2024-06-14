<?php
/**
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Qdrant\Config;

class ConfigTest extends TestCase
{
    public function testConfig(): void
    {
        $points = new Config('127.0.0.1');

        $this->assertEquals('127.0.0.1:6333', $points->getDomain());
    }

    public function testConfigWithApiKey(): void
    {
        $points = new Config('127.0.0.1');
        $points->setApiKey('api-key');

        $this->assertEquals('api-key', $points->getApiKey());
    }
}
