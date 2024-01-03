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
    protected $filter;

    /**
     * @var string|null
     */
    protected $using;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var int|null
     */
    protected $offset;

    /**
     * @var float|null
     */
    protected $scoreThreshold;

    /**
     * average_vector - Average positive and negative vectors and create a single query with the formula query = avg_pos + avg_pos - avg_neg. Then performs normal search.
     * best_score - Uses custom search objective. Each candidate is compared against all examples, its score is then chosen from the max(max_pos_score, max_neg_score). If the max_neg_score is chosen then it is squared and negated, otherwise it is just the max_pos_score.
     *
     * @var string|null
     */
    protected $strategy;

    /**
     * @var array
     */
    protected $positive = [];

    /**
     * @var array
     */
    protected $negative = [];

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

    public function setStrategy(string $strategy)
    {
        $this->strategy = $strategy;

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
        if ($this->strategy) {
            $body['strategy'] = $this->strategy;
        }

        return $body;
    }
}