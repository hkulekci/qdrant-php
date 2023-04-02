<?php
/**
 * Qdrant PHP Client
 *
 * @link      https://github.com/hkulekci/qdrant-php
 * @license   https://opensource.org/licenses/MIT MIT License
 */
declare(strict_types = 1);

namespace Qdrant\Tests;

use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;
use Qdrant\Config;
use Qdrant\Http\GuzzleClient;
use Qdrant\Qdrant;

class ClientTest extends TestCase
{
    public function testClient(): void
    {
        $config = (new Config('http://127.0.0.1'));
        $client = new Qdrant(new GuzzleClient($config));
        $httpFactory = new HttpFactory();
        $request = $httpFactory->createRequest('GET', '/');

        $response = $client->execute($request);
        $this->assertEquals('qdrant - vector search engine', $response['title']);
    }
}