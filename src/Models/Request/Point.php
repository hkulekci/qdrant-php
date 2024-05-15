<?php
/**
 * Point
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\VectorStruct;
use Qdrant\Models\VectorStructInterface;

class Point implements RequestModel
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var mixed[]|null
     */
    protected $payload;
    /**
     * @var VectorStructInterface
     */
    protected $vector;

    /**
     * @param VectorStructInterface|mixed[] $vector
     */
    public function __construct(string $id, $vector, ?array $payload = null)
    {
        $this->id = $id;
        $this->payload = $payload;
        if (is_array($vector)) {
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

        if ($this->payload) {
            $data['payload'] = $this->payload;
        }

        return $data;
    }
}