<?php
/**
 * Qdrant PHP Client
 *
 * @link      https://github.com/hkulekci/qdrant-php
 * @license   https://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Qdrant\Tests;

use PHPUnit\Framework\TestCase;
use Qdrant\Client;
use Qdrant\Config;

class ClientTest extends TestCase
{
    public function testClient(): void
    {
        $config = (new Config('http://127.0.0.1'));

        $client = new Client($config);

        $response = $client->execute('GET','/');
        $this->assertEquals('qdrant - vector search engine', $response['title']);
    }
}