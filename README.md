# Qdrant PHP Client

[![Test Application](https://github.com/hkulekci/qdrant-php/actions/workflows/test.yaml/badge.svg)](https://github.com/hkulekci/qdrant-php/actions/workflows/test.yaml) [![codecov](https://codecov.io/github/hkulekci/qdrant-php/branch/main/graph/badge.svg?token=5K8FAI0C9B)](https://codecov.io/github/hkulekci/qdrant-php)

This library is a PHP Client for Qdrant.  

Qdrant is a vector similarity engine & vector database. It deploys as an API service providing search for the nearest 
high-dimensional vectors. With Qdrant, embeddings or neural network encoders can be turned into full-fledged 
applications for matching, searching, recommending, and much more!

# Installation

You can install the client in your PHP project using composer:

```shell
composer require hkulekci/qdrant
```

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

$client = new Qdrant(new GuzzleClient($config));

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

Search with a filter :

```php
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;

$searchRequest = (new SearchRequest(new VectorStruct($embedding, 'elev_pitch')))
    ->setFilter(
        (new Filter())->addMust(
            new MatchString('name', 'Palm')
        )
    )
    ->setLimit(10)
    ->setParams([
        'hnsw_ef' => 128,
        'exact' => false,
    ])
    ->setWithPayload(true);

$response = $client->collections('startups')->points()->search($searchRequest);
```
