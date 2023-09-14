<?php
/**
 * Point
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Models\MultiVectorStruct;
use Qdrant\Models\VectorStruct;
use Qdrant\Models\VectorStructInterface;

class Point implements RequestModel
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var VectorStructInterface
     */
    protected $vector;

    /**
     * @var array|null Payload values (optional)
     */
    protected $payload = null;

    public function __construct(string $id, $vector, array $payload = null)
    {
        if(is_array($vector)) {
            $vector = new VectorStruct($vector);
        }

        $this->id = $id;
        $this->vector = $vector;
        $this->payload = $payload;
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