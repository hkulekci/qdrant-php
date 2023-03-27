<?php
/**
 * Point
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

class Point implements RequestModel
{
    protected string $id;
    protected array $vector;

    /**
     * @var array|null Payload values (optional)
     */
    protected ?array $payload = null;

    public function __construct(string $id, array $vector, array $payload = null)
    {
        $this->id = $id;
        $this->vector = $vector;
        $this->payload = $payload;
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'vector' => $this->vector,
        ];

        if ($this->payload) {
            $data['payload'] = $this->payload;
        }

        return $data;
    }
}