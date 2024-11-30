<?php

namespace Qdrant\Models;

interface VectorStructInterface
{
    /**
     * Get the name of the vector
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * Convert this vector to a search array.
     *
     * @param string|null $name
     * @return array
     */
    public function toSearchArray(?string $name = null): array;

    /**
     * Convert this vector an array for Point and PointsBatch.
     *
     * @return array
     */
    public function toArray(): array;
}
