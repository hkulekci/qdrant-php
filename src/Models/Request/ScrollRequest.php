<?php

namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class ScrollRequest implements RequestModel
{

    use ProtectedPropertyAccessor;

    /**
     * @var Filter|null
     */
    protected $filter = null;

    /**
     * @var int|null
     */
    protected $limit = null;

    /**
     * @var int|string|null
     */
    protected $offset = null;

    /**
     * @var bool|array|null
     */
    protected $withVector = null;

    /**
     * @var bool|array|null
     */
    protected $withPayload = null;

    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int|string $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setWithPayload($withPayload)
    {
        $this->withPayload = $withPayload;

        return $this;
    }

    public function setWithVector($withVector)
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
