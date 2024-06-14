<?php
/**
 * RecommendRequest
 *
 * @since     Jun 2023
 *
 * @author    Greg Priday <greg@siteorigin.com>
 */

namespace Qdrant\Models\Request\Points;

use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class BatchRecommendRequest
{
    use ProtectedPropertyAccessor;

    /** @var RecommendRequest[] */
    protected array $searches = [];

    /**
     * @param  RecommendRequest[]  $searches
     */
    public function __construct(array $searches)
    {
        foreach ($searches as $search)
        {
            $this->addSearch($search);
        }
    }

    public function addSearch(RecommendRequest $request): static
    {
        $this->searches[] = $request;

        return $this;
    }

    public function toArray(): array
    {
        $searches = [];

        foreach ($this->searches as $search)
        {
            $searches[] = $search->toArray();
        }

        return [
            'searches' => $searches,
        ];
    }
}
