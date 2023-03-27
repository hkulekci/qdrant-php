<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration;

use OpenAI;
use PHPUnit\Framework\TestCase;
use Qdrant\Client;
use Qdrant\Config;
use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;

abstract class AbstractIntegration extends TestCase
{
    protected Client $client;

    private ?Collections $collections = null;

    protected function setUp(): void
    {
        parent::setUp();
        $config = (new Config('http://localhost'));

        $this->client = new Client($config);
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function sampleCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(3, VectorParams::DISTANCE_COSINE), 'image');
    }

    protected function createCollections($name): void
    {
        $this->collections = new Collections($this->client);
        $response = $this->collections->create($name, self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);
    }

    public function getCollections(string $collectionsName): Collections
    {
        $this->assertNotNull($this->collections);
        $this->collections->setCollectionName($collectionsName);

        return $this->collections;
    }
}