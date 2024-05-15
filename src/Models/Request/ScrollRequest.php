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
    protected $filter;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var int|string|null
     */
    protected $offset = null;

    /**
     * @var mixed[]|bool|null
     */
    protected $withVector = null;

    /**
     * @var mixed[]|bool|null
     */
    protected $withPayload = null;

    /**
     * @return static
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @return static
     */
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int|string $offset
     * @return static
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return static
     */
    public function setWithPayload($withPayload)
    {
        $this->withPayload = $withPayload;

        return $this;
    }

    /**
     * @return static
     */
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
