<?php
/**
 * VectorParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;

class VectorParams implements RequestModel
{
    public const DISTANCE_COSINE = 'Cosine';
    public const DISTANCE_EUCLID = 'Euclid';
    public const DISTANCE_DOT = 'Dot';

    /**
     * @param $distance string [Cosine, Euclid, Dot]

     * @throws InvalidArgumentException
     */
    public function __construct(protected int $size, protected string $distance)
    {
        if (!in_array($distance, [self::DISTANCE_COSINE, self::DISTANCE_DOT, self::DISTANCE_EUCLID])) {
            throw new InvalidArgumentException('Invalid distance for Vector Param');
        }
    }

    public function toArray(): array
    {
        return [
            'size' => $this->size,
            'distance' => $this->distance,
        ];
    }
}
