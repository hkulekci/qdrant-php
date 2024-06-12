<?php
/**
 * RecommendRequest
 *
 * @since     Jun 2023
 * @author    Greg Priday <greg@siteorigin.com>
 */
namespace Qdrant\Models\Request\Points;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Filter\Filter;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class RecommendRequest
{
    use ProtectedPropertyAccessor;

    /**
     * average_vector - Average positive and negative vectors and create a single query with the formula
     * query = avg_pos + avg_pos - avg_neg. Then performs normal search.
     */
    const STRATEGY_AVERAGE_VECTOR = 'average_vector';

    /**
     * best_score - Uses custom search objective. Each candidate is compared against all examples, its
     * score is then chosen from the max(max_pos_score, max_neg_score). If the max_neg_score is chosen
     * then it is squared and negated, otherwise it is just the max_pos_score.
     */
    const STRATEGY_BEST_SCORE = 'best_score';

    protected ?string $shardKey = null;
    protected ?string $strategy = null;
    protected ?Filter $filter = null;
    protected ?string $using = null;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected ?float $scoreThreshold = null;

    public function __construct(protected array $positive, protected array $negative = [])
    {
    }

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter;

        return $this;
    }

    public function setShardKey(string $shardKey): static
    {
        $this->shardKey = $shardKey;

        return $this;
    }

    public function setStrategy(string $strategy): static
    {
        $strategies = [
            self::STRATEGY_AVERAGE_VECTOR,
            self::STRATEGY_BEST_SCORE,
        ];
        if (!in_array($strategy, $strategies)) {
            throw new InvalidArgumentException('Invalid strategy for recommendation.');
        }
        $this->strategy = $strategy;

        return $this;
    }

    public function setScoreThreshold(float $scoreThreshold): static
    {
        $this->scoreThreshold = $scoreThreshold;

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

    public function setOffset(int $offset): static
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

        if ($this->shardKey !== null) {
            $body['shard_key'] = $this->shardKey;
        }
        if ($this->filter !== null && $this->filter->toArray()) {
            $body['filter'] = $this->filter->toArray();
        }
        if($this->scoreThreshold !== null) {
            $body['score_threshold'] = $this->scoreThreshold;
        }
        if ($this->using !== null) {
            $body['using'] = $this->using;
        }
        if ($this->limit !== null) {
            $body['limit'] = $this->limit;
        }
        if ($this->strategy !== null) {
            $body['strategy'] = $this->strategy;
        }
        if ($this->offset !== null) {
            $body['offset'] = $this->offset;
        }

        return $body;
    }
}