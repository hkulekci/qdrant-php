<?php
/**
 * AliasesTest
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Aliases;
use Qdrant\Endpoints\Collections;
use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\AliasActions;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Tests\Integration\AbstractIntegration;

class AliasesTest extends AbstractIntegration
{
    /**
     * @throws InvalidArgumentException
     */
    private static function sampleCollectionOption(): CreateCollection
    {
        return (new CreateCollection())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionAliases(): void
    {
        $collection = new Collections($this->client);
        $this->createCollections('sample-collection');
        $collection->setCollectionName('sample-collection');

        $aliases = $collection->aliases();
        $this->assertEquals('sample-collection', $aliases->getCollectionName());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionEmptyAliases(): void
    {
        $collections = (new Collections($this->client));
        $collections->setCollectionName('sample-collection');
        $aliases = new Aliases($this->client);
        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $response = $aliases->all();
        $this->assertEquals('ok', $response['status']);
        $this->assertEmpty($response['result']['aliases']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionCreateAliases(): void
    {
        $collections = (new Collections($this->client));
        $collections->setCollectionName('sample-collection');
        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $collections->setCollectionName('sample-collection');
        $aliases = $collections->aliases();
        $aliasActions = new AliasActions();
        $aliasActions->add('sample-alias', 'sample-collection');
        $response = $aliases->actions($aliasActions);
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(true, $response['result']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionCreateAliasesWithChecking(): void
    {
        $collections = (new Collections($this->client));
        $collections->setCollectionName('sample-collection');
        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $collections->setCollectionName('sample-collection');
        $aliases = $collections->aliases();
        $aliasActions = new AliasActions();
        $aliasActions->add('sample-alias', 'sample-collection');
        $response = $aliases->actions($aliasActions);
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(true, $response['result']);

        $aliasesObj = new Collections\Aliases($this->client);
        $aliasesObj->setCollectionName('sample-collection');
        $response = $aliasesObj->aliases();

        $this->assertEquals('ok', $response['status']);
        $this->assertArrayHasKey('aliases', $response['result']);
        $this->assertNotEmpty($response['result']['aliases']);

        $this->assertEquals('sample-alias', $response['result']['aliases'][0]['alias_name']);
        $this->assertEquals('sample-collection', $response['result']['aliases'][0]['collection_name']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionCreateAndDeleteAliases(): void
    {
        $collections = (new Collections($this->client));
        $collections->setCollectionName('sample-collection');
        $response = $collections->create(self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $aliases = $collections->aliases();
        $aliasActions = new AliasActions();
        $aliasActions->add('sample-alias', 'sample-collection');
        $response = $aliases->actions($aliasActions);
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(true, $response['result']);

        $aliasActions = new AliasActions();
        $aliasActions->delete('sample-alias');
        $response = $aliases->actions($aliasActions);
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals(true, $response['result']);

        $response = (new Aliases($this->client))->all();
        $this->assertEquals('ok', $response['status']);
        $this->assertEmpty($response['result']['aliases']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');
        $collections->delete();
    }
}
