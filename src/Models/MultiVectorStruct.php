<?php

namespace Qdrant\Models;

use Qdrant\Exception\InvalidArgumentException;

class MultiVectorStruct implements VectorStructInterface
{
    /**
     * @var mixed[]
     */
    protected $vectors = [];

    public function __construct(array $vectors = [])
    {
        foreach ($vectors as $name => $vector) {
            $this->addVector($name, $vector);
        }
    }

    public function addVector(string $name, array $vector): void
    {
        $this->vectors[$name] = $vector;
    }

    public function getName(): ?string
    {
        if (empty($this->vectors)) {
            throw new InvalidArgumentException('No vectors added yet');
        }
        reset($this->vectors);

        return key($this->vectors);
    }

    public function toSearchArray(string $name = null): array
    {
        // Throw an error if no name is given
        if ($name === null) {
            throw new InvalidArgumentException('Must provide a name to search');
        }

        if (!isset($this->vectors[$name])) {
            throw new InvalidArgumentException("Vector with name $name not found");
        }

        return [
            'name' => $name,
            'vector' => $this->vectors[$name],
        ];
    }

    public function toArray(): array
    {
        return $this->vectors;
    }

    public function count(): int
    {
        return count($this->vectors);
    }
}
