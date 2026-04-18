# Qdrant PHP Client — AI Context Reference

This document provides a complete API reference for AI assistants working with the `hkulekci/qdrant` PHP client library.

## Installation

```bash
composer require hkulekci/qdrant
```

Requires a PSR-18 HTTP client and PSR-17 HTTP factory implementation. Example:
```bash
composer require symfony/http-client nyholm/psr7
```

## Bootstrapping

```php
use Qdrant\Config;
use Qdrant\Http\Builder;
use Qdrant\Qdrant;

$config = new Config('localhost', 6333);  // host, port
$config->setApiKey('your-api-key');       // optional

$transport = (new Builder())->build($config);
$client = new Qdrant($transport);
```

## Collection Management

### Create Collection

```php
use Qdrant\Models\Request\CreateCollection;
use Qdrant\Models\Request\VectorParams;

$createCollection = new CreateCollection();
$createCollection->addVector(new VectorParams(1536, VectorParams::DISTANCE_COSINE), 'content');
// Optional: named vectors for multi-vector collections
$createCollection->addVector(new VectorParams(768, VectorParams::DISTANCE_DOT), 'title');

$response = $client->collections('my-collection')->create($createCollection);
```

**VectorParams distance options:** `DISTANCE_COSINE`, `DISTANCE_EUCLID`, `DISTANCE_DOT`

### Other Collection Operations

```php
$client->collections()->list();                    // List all collections
$client->collections('name')->info();              // Collection details
$client->collections('name')->exists();            // Check existence
$client->collections('name')->delete();            // Delete collection
$client->collections('name')->update($updateReq);  // Update config
```

### Collection Config Options

```php
use Qdrant\Models\Request\CollectionConfig\HnswConfig;
use Qdrant\Models\Request\CollectionConfig\OptimizersConfig;
use Qdrant\Models\Request\CollectionConfig\WalConfig;
use Qdrant\Models\Request\CollectionConfig\ScalarQuantization;

$createCollection
    ->setShardNumber(2)
    ->setReplicationFactor(2)
    ->setOnDiskPayload(true)
    ->setHnswConfig((new HnswConfig())->setM(16)->setEfConstruct(100))
    ->setOptimizersConfig((new OptimizersConfig())->setIndexingThreshold(20000))
    ->setWalConfig((new WalConfig())->setWalCapacityMb(64))
    ->setQuantizationConfig(new ScalarQuantization('int8', 0.99, true));
```

## Point Operations

### Upsert Points

```php
use Qdrant\Models\PointsStruct;
use Qdrant\Models\PointStruct;
use Qdrant\Models\VectorStruct;

// Single vector
$points = new PointsStruct();
$points->addPoint(new PointStruct(
    1,                                          // id (int or string)
    new VectorStruct([0.1, 0.2, 0.3], 'content'), // vector with name
    ['title' => 'Hello', 'category' => 'greeting'] // payload (optional)
));

$client->collections('my-collection')->points()->upsert($points);

// Wait for indexing (important for immediate reads after write)
$client->collections('my-collection')->points()->upsert($points, ['wait' => 'true']);
```

### Create from Array

```php
$points = PointsStruct::createFromArray([
    ['id' => 1, 'vector' => new VectorStruct([0.1, 0.2, 0.3], 'content')],
    ['id' => 2, 'vector' => new VectorStruct([0.4, 0.5, 0.6], 'content'), 'payload' => ['color' => 'red']],
]);
```

### Multi-Vector Points

```php
use Qdrant\Models\MultiVectorStruct;

$vector = new MultiVectorStruct();
$vector->addVector('content', [0.1, 0.2, 0.3]);
$vector->addVector('title', [0.4, 0.5]);

$points = new PointsStruct();
$points->addPoint(new PointStruct(1, $vector, ['meta' => 'data']));
```

### Batch Upsert

```php
use Qdrant\Models\Request\PointsBatch;

$batch = new PointsBatch();
$batch->addPoint(PointStruct::createFromArray([
    'id' => 1,
    'vector' => new VectorStruct([0.1, 0.2, 0.3], 'content'),
    'payload' => ['color' => 'red']
]));
$client->collections('my-collection')->points()->batch($batch);
```

### Get Points

```php
// Single point by ID
$response = $client->collections('name')->points()->id(1);

// Multiple points by IDs
$response = $client->collections('name')->points()->ids([1, 2, 3], withPayload: true, withVector: false);

// Count points
$response = $client->collections('name')->points()->count();
$response = $client->collections('name')->points()->count($filter, exact: true);
```

### Delete Points

```php
// By IDs
$client->collections('name')->points()->delete([1, 2, 3]);

// By filter
$client->collections('name')->points()->deleteByFilter($filter);
```

### Scroll Points

```php
use Qdrant\Models\Request\ScrollRequest;

$request = (new ScrollRequest())
    ->setFilter($filter)
    ->setLimit(100)
    ->setOffset(0)              // int or string (point ID for cursor-based)
    ->setOrderBy('timestamp')   // string or array
    ->setWithPayload(true)
    ->setWithVector(false);

$response = $client->collections('name')->points()->scroll($request);
// or with just a filter:
$response = $client->collections('name')->points()->scroll($filter);
```

## Querying (Universal Query Endpoint)

The `query()` endpoint is the recommended way to search. It covers search, recommend, discover, and hybrid queries.

### Basic Vector Search

```php
use Qdrant\Models\Request\Points\QueryRequest;

$request = (new QueryRequest())
    ->setQuery(['nearest' => [0.1, 0.2, 0.3]])  // vector values
    ->setUsing('content')                         // vector field name
    ->setFilter($filter)
    ->setLimit(10)
    ->setOffset(0)
    ->setScoreThreshold(0.5)
    ->setWithPayload(true)
    ->setWithVector(false)
    ->setParams(['hnsw_ef' => 128, 'exact' => false]);

$response = $client->collections('name')->points()->query()->query($request);
```

### Query Types

```php
// Nearest vector search
$request->setQuery(['nearest' => [0.1, 0.2, 0.3]]);

// Nearest by point ID
$request->setQuery(['nearest' => 42]);

// Recommend (by positive/negative examples)
$request->setQuery(['recommend' => ['positive' => [1, 2], 'negative' => [3]]]);

// Discover
$request->setQuery(['discover' => ['target' => 1, 'context' => [['positive' => 2, 'negative' => 3]]]]);

// Fusion (for combining prefetch results)
$request->setQuery(['fusion' => 'rrf']);

// Order by payload field
$request->setQuery(['order_by' => 'price']);

// Sample random points
$request->setQuery(['sample' => 'random']);
```

### Multi-Stage / Hybrid Query (Prefetch)

```php
$request = (new QueryRequest())
    ->setPrefetch([
        ['query' => ['nearest' => [0.1, 0.2, 0.3]], 'using' => 'dense', 'limit' => 100],
        ['query' => ['nearest' => [1, 0, 1, 0]], 'using' => 'sparse', 'limit' => 100],
    ])
    ->setQuery(['fusion' => 'rrf'])
    ->setLimit(10);
```

### Batch Query

```php
use Qdrant\Models\Request\Points\BatchQueryRequest;

$batch = new BatchQueryRequest([
    (new QueryRequest())->setQuery(['nearest' => [0.1, 0.2, 0.3]])->setLimit(5),
    (new QueryRequest())->setQuery(['nearest' => [0.4, 0.5, 0.6]])->setLimit(5),
]);

$response = $client->collections('name')->points()->query()->batch($batch);
```

### Grouped Query

```php
use Qdrant\Models\Request\Points\QueryGroupsRequest;

$request = (new QueryGroupsRequest('category'))  // group_by field (required)
    ->setQuery(['nearest' => [0.1, 0.2, 0.3]])
    ->setUsing('content')
    ->setGroupSize(3)
    ->setLimit(10)
    ->setFilter($filter)
    ->setWithPayload(true)
    ->setWithVector(false)
    ->setWithLookup('other_collection')            // string or config array
    ->setLookupFrom(['collection' => 'c', 'vector' => 'v']);

$response = $client->collections('name')->points()->query()->groups($request);
```

### QueryRequest — All Setters

| Method | Type | Notes |
|--------|------|-------|
| `setQuery(array)` | nearest/recommend/discover/fusion/order_by/sample | See query types above |
| `setUsing(string)` | Vector field name | Required for named vectors |
| `setFilter(Filter)` | Filter conditions | See Filters section |
| `setLimit(int)` | Max results | |
| `setOffset(int)` | Skip results | |
| `setScoreThreshold(float)` | Minimum score | |
| `setParams(array)` | HNSW params | e.g. `['hnsw_ef' => 128, 'exact' => false]` |
| `setWithPayload(bool\|array)` | Return payload | `true`, `false`, or `['field1', 'field2']` |
| `setWithVector(bool\|array)` | Return vectors | `true`, `false`, or `['vector_name']` |
| `setPrefetch(array)` | Multi-stage queries | Array of prefetch config objects |
| `setShardKey(string\|array)` | Target specific shards | |
| `setLookupFrom(array)` | Lookup from another collection | `['collection' => '...', 'vector' => '...']` |

## Filters

### Building Filters

```php
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Condition\MatchInt;
use Qdrant\Models\Filter\Condition\MatchBool;
use Qdrant\Models\Filter\Condition\MatchAny;
use Qdrant\Models\Filter\Condition\MatchExcept;
use Qdrant\Models\Filter\Condition\Range;
use Qdrant\Models\Filter\Condition\HasId;
use Qdrant\Models\Filter\Condition\IsEmpty;
use Qdrant\Models\Filter\Condition\FullTextMatch;
use Qdrant\Models\Filter\Condition\GeoRadius;
use Qdrant\Models\Filter\Condition\GeoBoundingBox;
use Qdrant\Models\Filter\Condition\GeoPolygon;
use Qdrant\Models\Filter\Condition\ValueCount;
use Qdrant\Models\Filter\Nested;

// Must (AND)
$filter = (new Filter())
    ->addMust(new MatchString('city', 'Berlin'))
    ->addMust(new MatchInt('status', 1));

// Must Not (NOT)
$filter->addMustNot(new MatchBool('deleted', true));

// Should (OR)
$filter->addShould(new MatchString('color', 'red'));
$filter->addShould(new MatchString('color', 'blue'));

// Min Should (at least N of conditions)
$filter->addMinShould(new MatchString('tag', 'a'));
$filter->addMinShould(new MatchString('tag', 'b'));
$filter->setMinShouldCount(1);
```

### Condition Types

```php
// Exact match
new MatchString('field', 'value');
new MatchInt('field', 42);
new MatchBool('field', true);

// Match any of values
new MatchAny('field', ['red', 'blue', 'green']);

// Match except (exclude values)
new MatchExcept('field', ['excluded1', 'excluded2']);

// Range
new Range('price', ['gte' => 10, 'lte' => 100]);  // supports: gt, gte, lt, lte

// Has specific IDs
new HasId([1, 2, 3]);

// Field is empty/missing
new IsEmpty('optional_field');

// Full text search (requires text index)
new FullTextMatch('description', 'search query');

// Geo radius
new GeoRadius('location', [
    'center' => ['lat' => 52.52, 'lon' => 13.405],
    'radius' => 1000.0
]);

// Geo bounding box
new GeoBoundingBox('location', [
    'top_left' => ['lat' => 52.6, 'lon' => 13.2],
    'bottom_right' => ['lat' => 52.4, 'lon' => 13.6]
]);

// Geo polygon
new GeoPolygon('location', $exteriorRing, $interiorRings);

// Value count (nested array length)
new ValueCount('tags', ['gte' => 2]);

// Nested filter
new Nested('address', (new Filter())->addMust(new MatchString('city', 'Berlin')));
```

## Field Indexes

```php
use Qdrant\Models\Request\CreateIndex;

// Simple keyword index
$client->collections('name')->index()->create(new CreateIndex('city', 'keyword'));

// Other field types: 'keyword', 'integer', 'float', 'bool', 'geo', 'text'
$client->collections('name')->index()->create(new CreateIndex('price', 'float'));

// Delete index
$client->collections('name')->index()->delete('field_name');
```

## Aliases

```php
use Qdrant\Models\Request\AliasActions;

$actions = new AliasActions();
$actions->add('my-alias', 'my-collection');
$actions->delete('old-alias');

$client->collections('my-collection')->aliases()->actions($actions);

// List aliases
$client->collections('my-collection')->aliases()->aliases();

// List all aliases (global)
// Access via Aliases endpoint on root Qdrant client
```

## Snapshots

```php
// Collection-level
$client->collections('name')->snapshots()->create([], ['wait' => 'true']);
$client->collections('name')->snapshots()->list();
$client->collections('name')->snapshots()->download('snapshot-name.snapshot');
$client->collections('name')->snapshots()->delete('snapshot-name.snapshot');

// Storage-level
$client->snapshots()->get();
$client->snapshots()->create();
$client->snapshots()->download('snapshot-name.snapshot');
$client->snapshots()->delete('snapshot-name.snapshot');
```

## Cluster Operations

```php
// Storage-level cluster info
$client->cluster()->info();

// Collection-level cluster info
$client->collections('name')->cluster()->info();

// Shard management
use Qdrant\Models\Request\CollectionConfig\CreateShardKey;
use Qdrant\Models\Request\CollectionConfig\DeleteShardKey;

$client->collections('name')->shards()->create(new CreateShardKey('shard_key_value'));
$client->collections('name')->shards()->delete(new DeleteShardKey('shard_key_value'));
```

## Payload Management

```php
// Set payload on specific points
$client->collections('name')->points()->payload()->set(
    [1, 2],                              // point IDs
    ['color' => 'red', 'size' => 'L']    // payload to set
);

// Delete payload keys
$client->collections('name')->points()->payload()->delete(
    [1, 2],           // point IDs
    ['color', 'size']  // keys to delete
);

// Clear all payload
$client->collections('name')->points()->payload()->clear([1, 2]);
```

## Response Handling

All endpoints return a `Response` object with ArrayAccess:

```php
$response = $client->collections('name')->points()->query()->query($request);

$response['status'];              // 'ok'
$response['result'];              // array of results
$response['result']['points'];    // for grouped queries

// Query results structure
foreach ($response['result'] as $point) {
    $point['id'];       // point ID
    $point['score'];    // similarity score
    $point['payload'];  // payload data (if requested)
    $point['vector'];   // vector data (if requested)
}
```

## Error Handling

```php
use Qdrant\Exception\InvalidArgumentException;  // 4xx errors
use Qdrant\Exception\ServerException;            // 5xx errors

try {
    $response = $client->collections('name')->points()->query()->query($request);
} catch (InvalidArgumentException $e) {
    $e->getMessage();     // Error message
    $e->getCode();        // HTTP status code
    $e->getResponse();    // Full Response object
} catch (ServerException $e) {
    // Server-side error
}
```

## Deprecated Endpoints

The following are deprecated in favor of the universal `query()` endpoint:

```php
// DEPRECATED: Use query() instead
$client->collections('name')->points()->search($searchRequest);
$client->collections('name')->points()->recommend()->recommend($recommendRequest);
$client->collections('name')->points()->recommend()->batch($batchRecommendRequest);
```

## Query Parameters

Most endpoint methods accept an optional `array $queryParams` as the last parameter:

```php
// Common query parameters
['wait' => 'true']                  // Wait for operation to complete
['timeout' => 30]                   // Timeout in seconds
['consistency' => 'all']            // Read consistency: 'all', 'majority', 'quorum', int
['ordering' => 'strong']            // Write ordering
```
