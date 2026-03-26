<?php
/**
 * QueryRequest
 *
 * @since     Mar 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Models\Request\Points;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class QueryRequest
{
    use ProtectedPropertyAccessor;

    protected string|array|null $shardKey = null;

    protected array|null $prefetch = null;

    protected array|null $query = null;

    protected ?string $using = null;

    protected ?Filter $filter = null;

    protected array $params = [];

    protected ?float $scoreThreshold = null;

    protected ?int $limit = null;

    protected ?int $offset = null;

    protected bool|array|null $withVector = null;

    protected bool|array|null $withPayload = null;

    protected ?array $lookupFrom = null;

    public function __construct()
    {
    }

    public function setShardKey(string|array $shardKey): static
    {
        $this->shardKey = $shardKey;

        return $this;
    }

    /**
     * Set prefetch sub-requests to perform first.
     *
     * @param array $prefetch Array of prefetch configurations
     */
    public function setPrefetch(array $prefetch): static
    {
        $this->prefetch = $prefetch;

        return $this;
    }

    /**
     * Set the query to perform.
     *
     * Examples:
     *   Nearest:   ['nearest' => [0.1, 0.2, 0.3]]
     *   Recommend: ['recommend' => ['positive' => [...], 'negative' => [...]]]
     *   Discover:  ['discover' => ['target' => [...], 'context' => [...]]]
     *   Fusion:    ['fusion' => 'rrf']
     *   Order by:  ['order_by' => 'field_name']
     *   Sample:    ['sample' => 'random']
     *
     * @param array $query
     */
    public function setQuery(array $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function setUsing(string $using): static
    {
        $this->using = $using;

        return $this;
    }

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function setScoreThreshold(float $scoreThreshold): static
    {
        $this->scoreThreshold = $scoreThreshold;

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function setWithPayload($withPayload): static
    {
        $this->withPayload = $withPayload;

        return $this;
    }

    public function setWithVector($withVector): static
    {
        $this->withVector = $withVector;

        return $this;
    }

    /**
     * Set lookup from another collection.
     *
     * @param array $lookupFrom e.g. ['collection' => 'other', 'vector' => 'name']
     */
    public function setLookupFrom(array $lookupFrom): static
    {
        $this->lookupFrom = $lookupFrom;

        return $this;
    }

    public function toArray(): array
    {
        $body = [];

        if ($this->shardKey !== null) {
            $body['shard_key'] = $this->shardKey;
        }
        if ($this->prefetch !== null) {
            $body['prefetch'] = $this->prefetch;
        }
        if ($this->query !== null) {
            $body['query'] = $this->query;
        }
        if ($this->using !== null) {
            $body['using'] = $this->using;
        }
        if ($this->filter !== null && $this->filter->toArray()) {
            $body['filter'] = $this->filter->toArray();
        }
        if ($this->params) {
            $body['params'] = $this->params;
        }
        if ($this->scoreThreshold !== null) {
            $body['score_threshold'] = $this->scoreThreshold;
        }
        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }
        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }
        if ($this->withVector !== null) {
            $body['with_vector'] = $this->withVector;
        }
        if ($this->withPayload !== null) {
            $body['with_payload'] = $this->withPayload;
        }
        if ($this->lookupFrom !== null) {
            $body['lookup_from'] = $this->lookupFrom;
        }

        return $body;
    }
}
