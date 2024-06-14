<?php
/**
 * Point
 *
 * @since     Mar 2023
 *
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\VectorStruct;
use Qdrant\Models\VectorStructInterface;

class Point implements RequestModel
{
    protected VectorStructInterface $vector;

    public function __construct(protected string $id, VectorStructInterface|array $vector, protected ?array $payload = null)
    {
        if (is_array($vector))
        {
            $vector = new VectorStruct($vector);
        }

        $this->vector = $vector;
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'vector' => $this->vector->toArray(),
        ];

        if ($this->payload)
        {
            $data['payload'] = $this->payload;
        }

        return $data;
    }
}
