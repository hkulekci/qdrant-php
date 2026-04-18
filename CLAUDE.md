# Qdrant PHP Client

PHP client library for [Qdrant](https://qdrant.tech) vector database. Package: `hkulekci/qdrant`

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
```

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
    Collections/Points.php       # Point operations (upsert, delete, scroll, count, search)
    Collections/Points/Query.php # Universal query endpoint (query, batch, groups)
    Collections/Points/Recommend.php  # DEPRECATED - use Query instead
    Collections/Points/Payload.php    # Payload management (set, delete, clear)
    Collections/Index.php        # Field index management
    Collections/Aliases.php      # Collection alias management
    Collections/Cluster.php      # Collection cluster operations
    Collections/Shards.php       # Shard key management
    Collections/Snapshots.php    # Collection snapshots
    Cluster.php           # Cluster-level operations
    Snapshots.php         # Storage-level snapshots
    Service.php           # Telemetry, metrics
    Aliases.php           # Global aliases
  Models/
    VectorStruct.php      # Single named/unnamed vector
    MultiVectorStruct.php # Multiple named vectors
    PointStruct.php       # Single point (id + vector + payload)
    PointsStruct.php      # Collection of points for upsert
    Filter/               # Filter building (Filter, conditions)
    Request/              # Request models (all implement toArray())
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

## AI Context

For comprehensive API reference including all classes, methods, parameters, and usage examples, see `docs/ai-context.md`.
