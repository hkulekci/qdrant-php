<?php
/**
 * SearchParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;
use Qdrant\Models\VectorStruct;

class SearchRequest
{
    use ProtectedPropertyAccessor;

    protected ?Filter $filter = null;

    protected array $params = [];

    protected VectorStruct $vector;

    protected ?int $limit = null;

    protected ?int $offset = null;

    protected bool|array|null $withVector = null;

    protected bool|array|null $withPayload = null;

    public function __construct(VectorStruct $vector)
    {
        $this->vector = $vector;
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
            'vector' => $this->vector->toSearch(),
        ];
        if ($this->filter !== null) {
            $body['filter'] = $this->filter->toArray();
        }
        if ($this->params) {
            $body['params'] = $this->params;
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