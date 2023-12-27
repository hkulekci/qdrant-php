<?php
/**
 * PointStruct
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Traits\ProtectedPropertyAccessor;

class PointStruct
{
    use ProtectedPropertyAccessor;

    /**
     * @var int|string
     */
    protected $id;

    /**
     * @var VectorStructInterface
     */
    protected $vector;

    /**
     * @var array|null
     */
    protected $payload = null;

    public function __construct(
        $id,
        VectorStructInterface $vector,
        ?array $payload = null
    ) {
        $this->payload = $payload;
        $this->vector = $vector;
        $this->id = $id;
    }

    public static function createFromArray(array $pointArray): PointStruct
    {
        $required = ['id', 'vector'];
        if (count(array_intersect_key(array_flip($required), $pointArray)) !== count($required)) {
            throw new InvalidArgumentException('Missing point keys');
        }

        $vector = $pointArray['vector'];

        // Check if it's an array and convert it to a VectorStruct
        if (is_array($vector)) {
            $vector = new VectorStruct($vector, $pointArray['name'] ?? null);
        }

        // Check if it's already a VectorStruct or MultiVectorStruct
        if (!($vector instanceof VectorStructInterface)) {
            throw new InvalidArgumentException('Invalid vector type');
        }

        return new PointStruct($pointArray['id'], $vector, $pointArray['payload'] ?? null);
    }

    public function toArray(): array
    {
        $point = [
            'id' => $this->id,
            'vector' => $this->vector->toArray(),
        ];

        if ($this->payload) {
            $point['payload'] = $this->payload;
        }

        return $point;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function getVector(): VectorStructInterface
    {
        return $this->vector;
    }
}
