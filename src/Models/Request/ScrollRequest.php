<?php

namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class ScrollRequest implements RequestModel
{

    use ProtectedPropertyAccessor;

    protected ?Filter $filter = null;

    protected ?int $limit = null;

    protected int|string|null $offset = null;

    protected bool|array|null $withVector = null;

    protected bool|array|null $withPayload = null;

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(int|string $offset): static
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
        $body = [];

        if ($this->filter !== null && $this->filter->toArray()) {
            $body['filter'] = $this->filter->toArray();
        }
        if ($this->limit) {
            $body['limit'] = $this->limit;
        }
        if ($this->offset) {
            $body['offset'] = $this->offset;
        }
        if ($this->withVector) {
            $body['with_vector'] = $this->withVector;
        }
        if ($this->withPayload) {
            $body['with_payload'] = $this->withPayload;
        }

        return $body;
    }
}
