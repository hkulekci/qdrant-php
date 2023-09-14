<?php
/**
 * RecommendRequest
 *
 * @since     Jun 2023
 * @author    Greg Priday <greg@siteorigin.com>
 */
namespace Qdrant\Models\Request;

use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class RecommendRequest
{
    use ProtectedPropertyAccessor;

    /**
     * @var Filter|null
     */
    protected $filter = null;

    /**
     * @var array
     */
    protected $positive;

    /**
     * @var array
     */
    protected $negative;

    /**
     * @var string|null
     */
    protected $using = null;

    /**
     * @var int|null
     */
    protected $limit = null;

    /**
     * @var int|null
     */
    protected $offset = null;

    /**
     * @var float|null
     */
    protected $scoreThreshold = null;

    public function __construct(array $positive, array $negative = [])
    {
        $this->positive = $positive;
        $this->negative = $negative;
    }

    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function setScoreThreshold(float $scoreThreshold)
    {
        $this->scoreThreshold = $scoreThreshold;

        return $this;
    }

    public function setUsing(string $using)
    {
        $this->using = $using;

        return $this;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function toArray(): array
    {
        $body = [
            'positive' => $this->positive,
            'negative' => $this->negative,
        ];

        if ($this->filter !== null && $this->filter->toArray()) {
            $body['filter'] = $this->filter->toArray();
        }
        if($this->scoreThreshold) {
            $body['score_threshold'] = $this->scoreThreshold;
        }
        if ($this->using) {
            $body['using'] = $this->using;
        }
        if ($this->limit) {
            $body['limit'] = $this->limit;
        }
        if ($this->offset) {
            $body['offset'] = $this->offset;
        }

        return $body;
    }
}