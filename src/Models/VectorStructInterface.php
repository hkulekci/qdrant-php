<?php

namespace Qdrant\Models;

interface VectorStructInterface
{
    /**
     * Get the name of the vector
     */
    public function getName(): ?string;

    /**
     * Convert this vector to a search array.
     */
    public function toSearchArray(?string $name = null): array;

    /**
     * Convert this vector an array for Point and PointsBatch.
     */
    public function toArray(): array;
}
