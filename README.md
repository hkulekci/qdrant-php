# Qdrant PHP Client

[![Test Application](https://github.com/hkulekci/qdrant-php/actions/workflows/test.yaml/badge.svg)](https://github.com/hkulekci/qdrant-php/actions/workflows/test.yaml) [![codecov](https://codecov.io/github/hkulekci/qdrant-php/branch/main/graph/badge.svg?token=5K8FAI0C9B)](https://codecov.io/github/hkulekci/qdrant-php)

Qdrant is a vector similarity engine & vector database. It deploys as an API service providing search for the nearest 
high-dimensional vectors. With Qdrant, embeddings or neural network encoders can be turned into full-fledged 
applications for matching, searching, recommending, and much more!

This library is a PHP Client for Qdrant.

An example to create a collection :

```php
use Qdrant\Endpoints\Collections;
use Qdrant\Http\GuzzleClient;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;

include __DIR__ . "/../vendor/autoload.php";
include_once 'config.php';

$config = new \Qdrant\Config(QDRANT_HOST);
$config->setApiKey(QDRANT_API_KEY);
$client = new GuzzleClient($config);

$collections = new Collections($client);

$createCollection = new CreateCollection();
$createCollection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE), 'image');
$collections->create('images', $createCollection);
```

So now, we can insert a point : 

```php
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

$points = new PointsStruct();
$points->addPoint(
    new PointStruct(
        (int) $imageId,
        new VectorStruct($data['embeddings'][0], 'image'),
        [
            'id' => 1,
            'meta' => 'Meta data'
        ]
    )
);
$client->collections('startups')->points()
    ->upsert($points);
```

