<?php
/**
 * QuotaConfig
 *
 * Global resource quotas, available since Qdrant 1.19.
 *
 * https://qdrant.tech/documentation/ops-configuration/quotas/
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

use Qdrant\Exception\InvalidArgumentException;

class QuotaConfig implements RequestModel
{
    protected ?int $maxResidentMemoryPercent = null;
    protected ?int $maxDiskUsagePercent = null;
    protected ?int $releaseMarginPercent = null;

    public function __construct(protected bool $enabled = true)
    {
    }

    /**
     * Reject updates when resident memory usage exceeds this percentage (1-100).
     *
     * @throws InvalidArgumentException
     */
    public function setMaxResidentMemoryPercent(?int $percent): QuotaConfig
    {
        $this->assertPercent($percent, 1, 'max_resident_memory_percent');
        $this->maxResidentMemoryPercent = $percent;

        return $this;
    }

    /**
     * Reject updates when disk usage exceeds this percentage (1-100).
     *
     * @throws InvalidArgumentException
     */
    public function setMaxDiskUsagePercent(?int $percent): QuotaConfig
    {
        $this->assertPercent($percent, 1, 'max_disk_usage_percent');
        $this->maxDiskUsagePercent = $percent;

        return $this;
    }

    /**
     * Usage must drop this many percent below the limit before updates are accepted again (0-100).
     *
     * @throws InvalidArgumentException
     */
    public function setReleaseMarginPercent(?int $percent): QuotaConfig
    {
        $this->assertPercent($percent, 0, 'release_margin_percent');
        $this->releaseMarginPercent = $percent;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'enabled' => $this->enabled,
            'max_resident_memory_percent' => $this->maxResidentMemoryPercent,
            'max_disk_usage_percent' => $this->maxDiskUsagePercent,
            'release_margin_percent' => $this->releaseMarginPercent,
        ], static fn($value) => $value !== null);
    }

    private function assertPercent(?int $percent, int $min, string $name): void
    {
        if ($percent !== null && ($percent < $min || $percent > 100)) {
            throw new InvalidArgumentException(sprintf('%s should be between %d and 100', $name, $min));
        }
    }
}
