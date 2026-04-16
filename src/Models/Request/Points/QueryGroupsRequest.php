<?php
/**
 * QueryGroupsRequest
 *
 * @since     Mar 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Models\Request\Points;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class QueryGroupsRequest
{
    use ProtectedPropertyAccessor;

    protected string|array|null $shardKey = null;

    protected array|null $prefetch = null;

    protected array|null $query = null;

    protected ?string $using = null;

    protected ?Filter $filter = null;

    protected array $params = [];

    protected ?float $scoreThreshold = null;

    protected ?int $groupSize = null;

    protected ?int $limit = null;

    protected bool|array|null $withVector = null;

    protected bool|array|null $withPayload = null;

    protected ?array $lookupFrom = null;

    protected string|array|null $withLookup = null;

    public function __construct(protected string $groupBy)
    {
    }

    public function setShardKey(string|array $shardKey): static
    {
        $this->shardKey = $shardKey;

        return $this;
    }

    public function setPrefetch(array $prefetch): static
    {
        $this->prefetch = $prefetch;

        return $this;
    }

    /**
     * @see QueryRequest::setQuery()
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

    public function setGroupSize(int $groupSize): static
    {
        $this->groupSize = $groupSize;

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

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

    public function setLookupFrom(array $lookupFrom): static
    {
        $this->lookupFrom = $lookupFrom;

        return $this;
    }

    /**
     * Set with_lookup for grouping by IDs in another collection.
     *
     * @param string|array $withLookup Collection name or lookup config array
     */
    public function setWithLookup(string|array $withLookup): static
    {
        $this->withLookup = $withLookup;

        return $this;
    }

    public function toArray(): array
    {
        $body = [
            'group_by' => $this->groupBy,
        ];

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
        if ($this->groupSize !== null) {
            $body['group_size'] = $this->groupSize;
        }
        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
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
        if ($this->withLookup !== null) {
            $body['with_lookup'] = $this->withLookup;
        }

        return $body;
    }
}
