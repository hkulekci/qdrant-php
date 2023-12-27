<?php
/**
 * CollectionParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Models\Request\RequestModel;

class CollectionParams implements RequestModel
{
    /**
     * @var int|null
     */
    protected $replicationFactor;

    /**
     * @var int|null
     */
    protected $writeConsistencyFactor;

    /**
     * @var int|null
     */
    protected $readFanOutFactor;

    /**
     * @var bool|null
     */
    protected $onDiskPayload;

    public function setReplicationFactor(?int $replicationFactor): CollectionParams
    {
        $this->replicationFactor = $replicationFactor;

        return $this;
    }

    public function setWriteConsistencyFactor(?int $writeConsistencyFactor): CollectionParams
    {
        $this->writeConsistencyFactor = $writeConsistencyFactor;

        return $this;
    }

    public function setReadFanOutFactor(?int $readFanOutFactor): CollectionParams
    {
        $this->readFanOutFactor = $readFanOutFactor;

        return $this;
    }

    public function setOnDiskPayload(?bool $onDiskPayload): CollectionParams
    {
        $this->onDiskPayload = $onDiskPayload;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'replication_factor' => $this->replicationFactor ?? null,
            'write_consistency_factor' => $this->writeConsistencyFactor ?? null,
            'read_fan_out_factor' => $this->readFanOutFactor ?? null,
            'on_disk_payload' => $this->onDiskPayload ?? null,
        ]);
    }
}