<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Qdrant\Config;
use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Http\Builder;
use Qdrant\Http\GuzzleClient;
use Qdrant\Http\HttpClientInterface;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Qdrant;

abstract class AbstractIntegration extends TestCase
{
    /**
     * @var \Qdrant\Qdrant
     */
    protected $client;
    /**
     * @var \Qdrant\Endpoints\Collections|null
     */
    private $collections;

    protected function setUp(): void
    {
        parent::setUp();
        $config = (new Config('127.0.0.1'));
        $transform = (new Builder())->build($config);
        $this->client = new Qdrant($transform);
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function sampleCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(3, VectorParams::DISTANCE_COSINE), 'image')
            ->addVector(new VectorParams(3, VectorParams::DISTANCE_COSINE), 'text');
    }

    protected function createCollections($name, CreateCollection $withConfiguration = null): void
    {
        $this->collections = new Collections($this->client);
        $response = $this->collections->setCollectionName($name)
            ->create($withConfiguration ?: self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);
    }

    public function getCollections(string $collectionsName): Collections
    {
        $this->assertNotNull($this->collections, 'The collection did not created!');
        $this->collections->setCollectionName($collectionsName);

        return $this->collections;
    }
}