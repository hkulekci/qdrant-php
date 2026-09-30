<?php
/**
 * Slice
 *
 * Deterministically splits points into `total` slices and matches only the slice at `index`.
 * Useful for parallel scrolling and deterministic sampling. Available since Qdrant 1.19.
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Filter\Condition;

use Qdrant\Exception\InvalidArgumentException;

class Slice implements ConditionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(protected int $index, protected int $total)
    {
        if ($total < 1) {
            throw new InvalidArgumentException('Slice total should be at least 1');
        }
        if ($index < 0 || $index >= $total) {
            throw new InvalidArgumentException('Slice index should be between 0 and total - 1');
        }
    }

    public function toArray(): array
    {
        return [
            'slice' => [
                'index' => $this->index,
                'total' => $this->total,
            ],
        ];
    }
}
