<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Unit;

use GuzzleHttp\Psr7\Response as HttpResponse;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Exception\ServerException;
use Qdrant\Response;

class ResponseTest extends TestCase
{
    public function testConstructResponse(): void
    {
        $httpResponse = new HttpResponse(
            200,
            [
                'content-type' => 'application/json'
            ],
            Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        $response = new Response($httpResponse);

        $this->assertEquals('bar', $response['foo']);
    }

    public function testConstructResponse2(): void
    {
        $httpResponse = new HttpResponse(
            200,
            [
                'content-type' => 'text/html'
            ],
            Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        $response = new Response($httpResponse);

        $this->assertEquals('{"foo":"bar"}', $response['content']);
    }

    public function testConstructResponseWith4xxHttpCode(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument exception');
        $this->expectExceptionCode(418);
        $httpResponse = new HttpResponse(
            418,
            [
                'content-type' => 'application/json'
            ],
            Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        new Response($httpResponse);
    }

    public function testConstructResponseWith5xxHttpCode(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Server Exception');
        $this->expectExceptionCode(510);
        $httpResponse = new HttpResponse(
            510,
            [],
            Utils::streamFor(json_encode(['foo' => 'bar']))
        );

        new Response($httpResponse);
    }
}