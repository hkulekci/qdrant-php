<?php
/**
 * SearchParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;
use Qdrant\Models\VectorStructInterface;

/**
 * @deprecated Use Qdrant\Models\Request\Points\QueryRequest instead. The search endpoint is deprecated in Qdrant API.
 */
class SearchRequest
{
    use ProtectedPropertyAccessor;

    protected string|array|null $shardKey = null;

    protected ?Filter $filter = null;

    protected array $params = [];

    protected ?int $limit = null;

    protected ?int $offset = null;

    protected bool|array|null $withVector = null;

    protected bool|array|null $withPayload = null;

    protected ?float $scoreThreshold = null;

    protected ?string $name = null;

    public function __construct(protected VectorStructInterface $vector)
    {
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setShardKey(string|array $shardKey): static
    {
        $this->shardKey = $shardKey;

        return $this;
    }

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setScoreThreshold(float $scoreThreshold): static
    {
        $this->scoreThreshold = $scoreThreshold;

        return $this;
    }

    public function setParams(array $params): static
    {
        $this->params = $params;

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

    public function toArray(): array
    {
        $body = [
            'vector' => $this->vector->toSearchArray($this->name ?? $this->vector->getName()),
        ];

        if ($this->shardKey !== null) {
            $body['shard_key'] = $this->shardKey;
        }
        if ($this->filter !== null && $this->filter->toArray()) {
            $body['filter'] = $this->filter->toArray();
        }
        if($this->scoreThreshold !== null) {
            $body['score_threshold'] = $this->scoreThreshold;
        }
        if ($this->params) {
            $body['params'] = $this->params;
        }
        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        } else {
            throw new InvalidArgumentException('limit is required for search endpoint!');
        }
        if ($this->offset) {
            $body['offset'] = $this->offset;
        }
        if ($this->withVector !== null) {
            $body['with_vector'] = $this->withVector;
        }
        if ($this->withPayload !== null) {
            $body['with_payload'] = $this->withPayload;
        }

        return $body;
    }

}
