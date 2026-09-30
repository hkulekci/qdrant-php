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

## Versioning & Compatibility

Starting with `v1.19.0`, the version of this client follows the Qdrant server version: `1.19.x` releases target and
are tested against Qdrant `1.19`, and new client releases are published together with new Qdrant minor versions.
Patch releases (`1.19.1`, `1.19.2`, ...) contain client-side fixes only.

> [!IMPORTANT]
> The jump from `v1.0.0` to `v1.19.0` only aligns the version numbers with Qdrant, it does not remove or rename any
> public API. Still, please review the following before upgrading:
>
> - Features such as TurboQuant, memory tiers, named vector management or quotas require a Qdrant server that
>   supports them. Older servers may reject or silently ignore the new fields.
> - `false` and `0` values set on `HnswConfig`, `WalConfig` and `CollectionParams` (for example `on_disk: false`)
>   were previously dropped and are now sent to the server.
> - `CreateShardKey` now sends `shards_number`; the shard number was previously ignored by the server.
> - `on_disk`, `always_ram`, `on_disk_payload`, `memmap_threshold` and `init_from` are deprecated in Qdrant and marked
>   `@deprecated` in the client. Prefer the `memory` options and snapshots.
> - The legacy `search()` and `recommend()` endpoints are deprecated; use the Query API.

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

Use the universal Query API (`points()->query()`). The legacy `search()` and `recommend()` endpoints are
deprecated and have been removed from the Qdrant OpenAPI specification since Qdrant 1.19.

```php
use Qdrant\Models\Filter\Condition\MatchString;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Request\Points\QueryRequest;

$request = (new QueryRequest())
    ->setQuery(['nearest' => $embedding])
    ->setUsing('content')
    ->setFilter((new Filter())->addMust(new MatchString('name', 'Palm')))
    ->setLimit(10)
    ->setParams(['hnsw_ef' => 128, 'exact' => false])
    ->setWithPayload(true);

$response = $client->collections('contents')->points()->query()->query($request);

foreach ($response['result']['points'] as $item) {
    echo $item['score'] . ';' . $item['payload']['id'] . PHP_EOL;
}
```

Hybrid search with prefetch and fusion (weighted RRF, MMR, formula and relevance feedback queries are passed the same way):

```php
$request = (new QueryRequest())
    ->setPrefetch([
        ['query' => $denseEmbedding, 'using' => 'dense', 'limit' => 50],
        ['query' => ['indices' => [1, 42], 'values' => [0.3, 0.7]], 'using' => 'keywords', 'limit' => 50],
    ])
    ->setQuery(['rrf' => ['k' => 60, 'weights' => [1.0, 0.5]]])
    ->setLimit(10);
```

### Quantization (TurboQuant, Scalar, Product, Binary)

TurboQuant (Qdrant 1.18+) gives up to 8x vector compression with high recall:

```php
use Qdrant\Models\Request\CollectionConfig\Memory;
use Qdrant\Models\Request\CollectionConfig\TurboQuantization;

$createCollection = (new CreateCollection())
    ->addVector(new VectorParams(1536, VectorParams::DISTANCE_COSINE), 'content')
    ->setQuantizationConfig(new TurboQuantization(TurboQuantization::BITS_4, Memory::PINNED));
```

Since Qdrant 1.19 vectors can be stored only as TurboQuant 4-bit, without keeping the original vectors:

```php
$createCollection->addVector(
    (new VectorParams(1536, VectorParams::DISTANCE_COSINE))->setDatatype(VectorParams::DATATYPE_TURBO4),
    'content'
);
```

Other quantization methods are available as `ScalarQuantization`, `ProductQuantization` and `BinaryQuantization`
(with `encoding` / `query_encoding` for 1.5-bit, 2-bit and asymmetric binary quantization). Use
`DisabledQuantization` with `UpdateCollection` to turn quantization off.

### Memory Tiers

Since Qdrant 1.19 the `memory` option (`cold`, `cached`, `pinned`) replaces the `on_disk` and `always_ram` flags
for each collection component:

```php
use Qdrant\Models\Request\CollectionConfig\HnswConfig;

$createCollection = (new CreateCollection())
    ->addVector((new VectorParams(1536, VectorParams::DISTANCE_COSINE))->setMemory(Memory::COLD), 'content')
    ->setHnswConfig((new HnswConfig())->setMemory(Memory::CACHED))
    ->setQuantizationConfig(new TurboQuantization(memory: Memory::PINNED))
    ->setPayloadMemory(Memory::COLD);
```

### Sparse Vectors and Named Vectors

```php
use Qdrant\Models\Request\CreateVector;
use Qdrant\Models\Request\SparseVectorParams;

$createCollection->addSparseVector('keywords', (new SparseVectorParams())->setModifier(SparseVectorParams::MODIFIER_IDF));

// Add or remove named vectors on an existing collection (Qdrant 1.18+)
$client->collections('contents')->vectors()->create('summary', CreateVector::dense(384, VectorParams::DISTANCE_COSINE));
$client->collections('contents')->vectors()->create('bm25', CreateVector::sparse(SparseVectorParams::MODIFIER_IDF));
$client->collections('contents')->vectors()->delete('summary');
```

### Update Modes and Conditional Updates

```php
use Qdrant\Models\Filter\Condition\MatchInt;
use Qdrant\Models\Request\UpdateMode;

// Only insert points that do not exist yet (Qdrant 1.17+)
$client->collections('contents')->points()->upsert($points, ['wait' => 'true'], UpdateMode::INSERT_ONLY);

// Only update existing points that match the filter (Qdrant 1.16+)
$client->collections('contents')->points()->upsert(
    $points,
    updateFilter: (new Filter())->addMust(new MatchInt('version', 1))
);
```

### Filters

Besides the classic conditions (`MatchString`, `MatchInt`, `MatchAny`, `Range`, `GeoRadius`, ...) the client supports
`MatchPrefix` (1.19, requires a keyword index created with `prefix: true`), `MatchTextAny`, `MatchPhrase`, `IsNull`,
`HasVector` and `Slice` (1.19, deterministic partitioning for parallel scroll or sampling):

```php
use Qdrant\Models\Filter\Condition\MatchPrefix;
use Qdrant\Models\Filter\Condition\Slice;
use Qdrant\Models\Request\CreateIndex;

$client->collections('contents')->index()->create(new CreateIndex('category', ['type' => 'keyword', 'prefix' => true]));

$filter = (new Filter())
    ->addMust(new MatchPrefix('category', 'elec'))
    ->addMust(new Slice(0, 4)); // first of 4 slices
```

### Operations

```php
use Qdrant\Models\Request\QuotaConfig;

$client->collections('contents')->optimizations(['with' => 'queued,completed']); // optimization progress (1.17+)
$client->collections('contents')->shards()->list();                                // shard keys (1.17+)
$client->cluster()->telemetry();                                                   // cluster-wide telemetry (1.17+)

// Global resource quotas (1.19+)
$client->quotas()->get();
$client->quotas()->update((new QuotaConfig())->setMaxDiskUsagePercent(90));
```

### AI-Assisted Development

This library ships with an AI context file that helps AI assistants (Claude Code, etc.) understand the full API surface. To enable it in your project:

```shell
mkdir -p .claude/docs
cp vendor/hkulekci/qdrant/docs/ai-context.md .claude/docs/qdrant-php.md
```

This gives AI tools a complete reference of all endpoints, request models, filters, and usage examples.
