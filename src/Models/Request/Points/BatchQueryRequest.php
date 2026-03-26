<?php
/**
 * BatchQueryRequest
 *
 * @since     Mar 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace Qdrant\Models\Request\Points;

use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class BatchQueryRequest
{
    use ProtectedPropertyAccessor;

    /** @var QueryRequest[] $searches */
    protected array $searches = [];

    /**
     * @param QueryRequest[] $searches
     */
    public function __construct(array $searches)
    {
        foreach ($searches as $search) {
            $this->addSearch($search);
        }
    }

    public function addSearch(QueryRequest $request): static
    {
        $this->searches[] = $request;

        return $this;
    }

    public function toArray(): array
    {
        $searches = [];

        foreach ($this->searches as $search) {
            $searches[] = $search->toArray();
        }

        return [
            'searches' => $searches
        ];
    }
}
