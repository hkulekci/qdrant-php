<?php
namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;

class RecommendRequest
{
    protected ?Filter $filter = null;
    protected array $positive;
    protected array $negative;
    protected ?string $using = null;
    protected ?int $limit = null;

    public function __construct(array $positive, array $negative = [])
    {
        $this->positive = $positive;
        $this->negative = $negative;
    }

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setUsing(string $using): static
    {
        $this->using = $using;

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function toArray(): array
    {
        $body = [
            'positive' => $this->positive,
            'negative' => $this->negative,
        ];

        if ($this->filter !== null) {
            $body['filter'] = $this->filter->toArray();
        }
        if ($this->using) {
            $body['using'] = $this->using;
        }
        if ($this->limit) {
            $body['limit'] = $this->limit;
        }

        return $body;
    }
}