<?php
/**
 * Collections
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Tests\Integration\Endpoints\Collections;

use Qdrant\Endpoints\Collections;
use Qdrant\Models\Request\CollectionConfig\BinaryQuantization;
use Qdrant\Models\Request\CollectionConfig\DisabledQuantization;
use Qdrant\Models\Request\CollectionConfig\ProductQuantization;
use Qdrant\Models\Request\CollectionConfig\ScalarQuantization;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\UpdateCollection;
use Qdrant\Models\Request\VectorParams;
use Qdrant\Tests\Integration\AbstractIntegration;

class QuantizationConfigTest extends AbstractIntegration
{
    public function testCollectionsProductQuantization(): void
    {
        $request = (new CreateCollection())->setQuantizationConfig(new ProductQuantization('x4'))
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');

        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');
        $collections->create($request);
        $collections = new Collections($this->client);
        $response = $collections->setCollectionName('sample-collection')->info();
        $this->assertEquals('ok', $response['status']);

        $this->assertEquals([
            'product' => [
                'compression' => 'x4'
            ]
        ], $response['result']['config']['quantization_config']);
    }

    public function testCollectionsBinaryQuantization(): void
    {
        $request = (new CreateCollection())->setQuantizationConfig(new BinaryQuantization())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');

        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');
        $collections->create($request);
        $collections = new Collections($this->client);
        $response = $collections->setCollectionName('sample-collection')->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals([
            'binary' => []
        ], $response['result']['config']['quantization_config']);
    }

    public function testCollectionsScalarQuantization(): void
    {
        $request = (new CreateCollection())->setQuantizationConfig(new ScalarQuantization('int8'))
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');

        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');
        $collections->create($request);
        $collections = new Collections($this->client);
        $response = $collections->setCollectionName('sample-collection')->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals([
            'scalar' => [
                'type' => 'int8'
            ]
        ], $response['result']['config']['quantization_config']);
    }

    public function testCollectionsDisabledQuantization(): void
    {
        $request = (new CreateCollection())->setQuantizationConfig(new BinaryQuantization())
            ->addVector(new VectorParams(300, VectorParams::DISTANCE_COSINE), 'image');

        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection');
        $collections->create($request);

        $response = $collections->setCollectionName('sample-collection')->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertEquals([
            'binary' => []
        ], $response['result']['config']['quantization_config']);

        $updateRequest = (new UpdateCollection())->setQuantizationConfig(new DisabledQuantization());
        $response = $collections->update($updateRequest);
        $this->assertEquals('ok', $response['status']);

        $response = $collections->setCollectionName('sample-collection')->info();
        $this->assertEquals('ok', $response['status']);
        $this->assertNull($response['result']['config']['quantization_config']);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $collections = new Collections($this->client);
        $collections->setCollectionName('sample-collection')->delete();
    }
}