<?php
/**
 * Memory
 *
 * Memory usage strategy for collection components (vectors, quantized vectors, HNSW index, payload, payload indexes).
 * Replaces the deprecated `on_disk` and `always_ram` flags since Qdrant 1.19.
 *
 * https://qdrant.tech/documentation/ops-configuration/memory-tiers/
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;

final class Memory
{
    /** Data stays on disk and is only loaded into page cache on access, no warm-up. */
    public const COLD = 'cold';

    /** Data is stored on disk and pre-loaded into page cache on start. */
    public const CACHED = 'cached';

    /** Data is loaded into RAM and locked there, never evicted. */
    public const PINNED = 'pinned';

    public const ALL = [self::COLD, self::CACHED, self::PINNED];

    /**
     * @throws InvalidArgumentException
     */
    public static function assertValid(?string $memory): void
    {
        if ($memory !== null && !in_array($memory, self::ALL, true)) {
            throw new InvalidArgumentException(
                sprintf('Invalid memory value "%s", expected one of: %s', $memory, implode(', ', self::ALL))
            );
        }
    }
}
