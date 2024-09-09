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

### Connecting to Qdrant 

```php
include __DIR__ . "/../vendor/autoload.php";
include_once 'config.php';

use Qdrant\Qdrant;
use Qdrant\Config;
use Qdrant\Http\Builder;

$config = new Config(QDRANT_HOST);
$config->setApiKey(QDRANT_API_KEY);

$transport = (new Builder())->build($config);
$client = new Qdrant($transport);
```

### Creating a Collection

```php
use Qdrant\Endpoints\Collections;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;

$createCollection = new CreateCollection();
$createCollection->addVector(new VectorParams(1536, VectorParams::DISTANCE_COSINE), 'content');
$response = $client->collections('contents')->create($createCollection);
```

### Inserting Points Into Collection

```php
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

$openai = OpenAI::client(OPENAI_API_KEY);

$query = 'sustainable agricultural startups';
$response = $openai->embeddings()->create([
    'model' => 'text-embedding-ada-002',
    'input' => $query,
]);
$embedding = array_values($response->embeddings[0]->embedding);

$points = new PointsStruct();
$points->addPoint(
    new PointStruct(
        (int) $imageId,
        new VectorStruct($embedding, 'content'),
        [
            'id' => 1,
            'meta' => 'Meta data'
        ]
    )
);
$client->collections('contents')->points()->upsert($points);
```

### Wait for Acknowledges

While upsert data, if you want to wait for upsert to actually happen, you can use query parameters:

```php
$client->collections('contents')->points()->upsert($points, ['wait' => 'true']);
```

You can check for more parameters : https://qdrant.github.io/qdrant/redoc/index.html#tag/points/operation/upsert_points

### Search on Points

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

$response = $client->collections('contents')->points()->search($searchRequest);
```

### Search on Points with OpenAI Embeddings

```php
$openai = OpenAI::client(OPENAI_API_KEY);

$query = 'lorem ipsum dolor sit amed';
$response = $openai->embeddings()->create([
    'model' => 'text-embedding-ada-002',
    'input' => $query,
]);
$embedding = array_values($response->embeddings[0]->embedding);

$searchRequest = (new SearchRequest(new VectorStruct($embedding, 'content')))
    ->setLimit(10)
    ->setParams([
        'hnsw_ef' => 128,
        'exact' => false,
    ])
    ->setWithPayload(true);

$response = $client->collections('contents')->points()->search($searchRequest);

foreach ($response['result'] as $item) {
    echo $item['score'] . ';' . $item['payload']['id'] . ';' . $item['payload']['meta_data'] . PHP_EOL;
}
```
