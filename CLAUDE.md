# Qdrant PHP Client

PHP client library for [Qdrant](https://qdrant.tech) vector database. Package: `hkulekci/qdrant`

Versioning follows the Qdrant server: client `1.19.x` targets Qdrant `1.19`. When adding a feature, note the Qdrant
version that introduced it in the docblock (e.g. "available since Qdrant 1.18") and in `docs/ai-context.md`.

## Quick Reference

```bash
# Run tests
composer test

# Run with coverage
composer coverage

# Unit tests only
vendor/bin/phpunit tests/Unit/

# Integration tests (requires running Qdrant instance)
vendor/bin/phpunit tests/Integration/

# Local Qdrant for integration tests (tests assume standalone mode on port 6333)
docker run -d --rm -p 6333:6333 qdrant/qdrant:v1.19.1
```

`tests/bootstrap.php` loads `.env.test` (`QDRANT_URL`, `QDRANT_API_KEY`, `QDRANT_CLUSTER_MODE`), which overrides
environment variables passed on the command line.

## Architecture

```
src/
  Qdrant.php              # Main client, implements ClientInterface
  Config.php              # Host, port, API key
  Http/Builder.php        # Creates Transport from Config (auto-discovers PSR-18 client)
  Http/Transport.php      # PSR-18 HTTP transport layer
  Response.php            # Immutable ArrayAccess wrapper over PSR-7 response
  Endpoints/              # API endpoint classes (all extend AbstractEndpoint)
    Collections.php       #   Collection CRUD + sub-endpoints
    Collections/Points.php       # Point operations (upsert with update_mode/update_filter, delete, scroll, count)
    Collections/Points/Query.php # Universal query endpoint (query, batch, groups)
    Collections/Points/Recommend.php  # DEPRECATED - use Query instead
    Collections/Points/Payload.php    # Payload management (set, delete, clear)
    Collections/Index.php        # Field index management
    Collections/Aliases.php      # Collection alias management
    Collections/Cluster.php      # Collection cluster operations
    Collections/Shards.php       # Shard key management (create, delete, list)
    Collections/Snapshots.php    # Collection snapshots
    Collections/Vectors.php      # Add/remove named vectors on existing collection (1.18+)
    Cluster.php           # Cluster-level operations, cluster telemetry
    Snapshots.php         # Storage-level snapshots
    Service.php           # Telemetry, metrics
    Quotas.php            # Global resource quotas (1.19+)
    Aliases.php           # Global aliases
  Models/
    VectorStruct.php      # Single named/unnamed vector
    MultiVectorStruct.php # Multiple named vectors
    PointStruct.php       # Single point (id + vector + payload)
    PointsStruct.php      # Collection of points for upsert
    Filter/               # Filter building (Filter, conditions incl. MatchPrefix, Slice, IsNull, HasVector)
    Request/              # Request models (all implement toArray())
      CreateCollection.php / UpdateCollection.php  # Collection create/update bodies
      VectorParams.php / SparseVectorParams.php    # Dense and sparse vector config
      CreateVector.php / QuotaConfig.php / UpdateMode.php
      CollectionConfig/   # HNSW, optimizers, WAL, params, VectorParamsDiff, shard keys
                          # Quantization: Scalar, Product, Binary, Turbo, Disabled; Memory tiers
      Points/QueryRequest.php        # Universal query request
      Points/BatchQueryRequest.php   # Batch query request
      Points/QueryGroupsRequest.php  # Grouped query request
```

## Key Patterns

- **Bootstrapping**: `Config -> Builder -> Transport -> Qdrant`
- **Endpoint chaining**: `$client->collections('name')->points()->query()->query($request)`
- **Request models**: Fluent setters, all implement `toArray()` for JSON serialization
- **Filters**: Compose with `Filter::addMust()`, `addMustNot()`, `addShould()`
- **Response**: ArrayAccess — use `$response['status']`, `$response['result']`
- **Wait for indexing**: Pass `['wait' => 'true']` as query params to upsert/delete

## Conventions

- PSR-4 autoloading: `Qdrant\` -> `src/`, `Qdrant\Tests\` -> `tests/`
- Unit tests in `tests/Unit/` — no external dependencies, mock ClientInterface
- Integration tests in `tests/Integration/` — require running Qdrant (env: `QDRANT_URL`, `QDRANT_API_KEY`)
- All request classes use fluent interface (setters return `static`)
- Protected properties accessed via `ProtectedPropertyAccessor` trait magic getters
- Deprecated endpoints (search, recommend) kept for backward compatibility; prefer `query()` endpoint
- Options deprecated in Qdrant (`on_disk`, `always_ram`, `on_disk_payload`, `init_from`) stay in the client with
  `@deprecated`; new options are added as optional parameters/setters so existing code keeps working
- Empty option objects must serialize as JSON objects (`{}`), not arrays: return `new \stdClass()` when empty
- Setters validating enum-like values throw `Qdrant\Exception\InvalidArgumentException`; `null` means "not set"
  and is omitted from `toArray()`, while `false` and `0` are sent

## AI Context

For comprehensive API reference including all classes, methods, parameters, and usage examples, see `docs/ai-context.md`.
