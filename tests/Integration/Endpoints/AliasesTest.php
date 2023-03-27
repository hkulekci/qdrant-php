<?php
/**
 * AliasesTest
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Tests\Integration\Endpoints;

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
    public function testCollectionEmptyAliases(): void
    {
        $collections = (new Collections($this->client));
        $response = $collections->create('sample-collection', self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $aliases = $collections->aliases();
        $response = $aliases->allAliases();
        $this->assertEquals('ok', $response['status']);
        $this->assertEmpty($response['result']['aliases']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCollectionCreateAliases(): void
    {
        $collections = (new Collections($this->client));
        $response = $collections->create('sample-collection', self::sampleCollectionOption());
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
    public function testCollectionCreateAndDeleteAliases(): void
    {
        $collections = (new Collections($this->client));
        $response = $collections->create('sample-collection', self::sampleCollectionOption());
        $this->assertEquals('ok', $response['status']);

        $aliases = $collections->aliases('sample-collection');
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

        $response = $aliases->allAliases();
        $this->assertEquals('ok', $response['status']);
        $this->assertEmpty($response['result']['aliases']);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);
        $collections->delete('sample-collection');
    }
}