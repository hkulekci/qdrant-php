<?php
/**
 * SearchParams
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;
use Qdrant\Models\VectorStructInterface;

class SearchRequest
{
    use ProtectedPropertyAccessor;

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

        if ($this->filter !== null && $this->filter->toArray())
        {
            $body['filter'] = $this->filter->toArray();
        }
        if ($this->scoreThreshold)
        {
            $body['score_threshold'] = $this->scoreThreshold;
        }
        if ($this->params)
        {
            $body['params'] = $this->params;
        }
        if ($this->limit)
        {
            $body['limit'] = $this->limit;
        }
        if ($this->offset)
        {
            $body['offset'] = $this->offset;
        }
        if ($this->withVector)
        {
            $body['with_vector'] = $this->withVector;
        }
        if ($this->withPayload)
        {
            $body['with_payload'] = $this->withPayload;
        }

        return $body;
    }
}
