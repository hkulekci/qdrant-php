<?php
/**
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models;

class VectorStruct
{
    protected array $vector;
    protected ?string $name;

    public function __construct(array $vector, string $name = null)
    {
        $this->vector = $vector;
        $this->name = $name;
    }

    public function toSearch(): array
    {
        if ($this->name) {
            return [
                'name' => $this->name,
                'vector' => $this->vector,
            ];
        }
        return $this->vector;
    }

    public function toArray(): array
    {
        if ($this->name) {
            return [
                $this->name => $this->vector
            ];
        }
        return $this->vector;
    }
}