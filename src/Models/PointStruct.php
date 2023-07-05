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

    // TODO: we need a solution for point with uuid
    protected int $id;
    protected ?array $payload = null;
    protected VectorStruct $vector;

    public function __construct(int $id, VectorStruct $vector, array $payload = null)
    {
        $this->id = $id;
        $this->vector = $vector;
        $this->payload = $payload;
    }

    public static function createFromArray(array $pointArray): PointStruct
    {
        $required = ['id', 'vector'];
        if (count(array_intersect_key(array_flip($required), $pointArray)) !== count($required)) {
            throw new InvalidArgumentException('Missing point keys');
        }
        $vector = $pointArray['vector'];
        if (is_array($pointArray['vector'])) {
            $vector = new VectorStruct($pointArray['vector'], $pointArray['name'] ?? null);
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array|null
     */
    public function getPayload(): ?array
    {
        return $this->payload;
    }

    /**
     * @return VectorStruct
     */
    public function getVector(): VectorStruct
    {
        return $this->vector;
    }
}