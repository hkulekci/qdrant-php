# Qdrant PHP Client

Qdrant is a vector similarity engine & vector database. It deploys as an API service providing search for the nearest 
high-dimensional vectors. With Qdrant, embeddings or neural network encoders can be turned into full-fledged 
applications for matching, searching, recommending, and much more!

This library is a PHP Client for Qdrant.

An example to create a collection : 

```php
<?php
use Qdrant\Client;
use Qdrant\Endpoints\Collections;
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;

include __DIR__ . "/../vendor/autoload.php";
include_once 'config.php';

$config = new \Qdrant\Config(QDRANT_HOST);
$config->setApiKey(QDRANT_API_KEY);
$client = new Client($config);

$collections = new Collections($client);

$createCollection = new CreateCollection();
$createCollection->addVector(new VectorParams(1024, VectorParams::DISTANCE_COSINE), 'image');
$collections->create('images', $createCollection);
```

Some links to read : 

- https://dylancastillo.co/ai-search-engine-fastapi-qdrant-chatgpt/#prerequisites
- https://qdrant.tech/articles/qa-with-cohere-and-qdrant/
- https://github.com/qdrant/qdrant_client
- https://github.com/dylanjcastillo/ai-search-fastapi-qdrant-chatgpt
- https://pypi.org/project/sentence-transformers/