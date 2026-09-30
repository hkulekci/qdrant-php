<?php
/**
 * CollectionParams
 *
 * @since     Mar 2023
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request\CollectionConfig;

use Qdrant\Exception\InvalidArgumentException;
use Qdrant\Models\Request\RequestModel;

class CollectionParams implements RequestModel
{
    protected ?int $replicationFactor = null;
    protected ?int $writeConsistencyFactor = null;
    protected ?int $readFanOutFactor = null;
    protected ?int $readFanOutDelayMs = null;
    protected ?bool $onDiskPayload = null;
    protected ?string $payloadMemory = null;

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

    /**
     * Delay in milliseconds before sending read requests to additional replicas, available since Qdrant 1.17.
     */
    public function setReadFanOutDelayMs(?int $readFanOutDelayMs): CollectionParams
    {
        $this->readFanOutDelayMs = $readFanOutDelayMs;

        return $this;
    }

    /**
     * @deprecated Since Qdrant 1.19, use setPayloadMemory() instead.
     */
    public function setOnDiskPayload(?bool $onDiskPayload): CollectionParams
    {
        $this->onDiskPayload = $onDiskPayload;

        return $this;
    }

    /**
     * Memory usage strategy for the payload storage, one of the Memory::* constants.
     *
     * @throws InvalidArgumentException
     */
    public function setPayloadMemory(?string $payloadMemory): CollectionParams
    {
        Memory::assertValid($payloadMemory);
        $this->payloadMemory = $payloadMemory;

        return $this;
    }

    public function toArray(): array
    {
        return array_filter([
            'replication_factor' => $this->replicationFactor,
            'write_consistency_factor' => $this->writeConsistencyFactor,
            'read_fan_out_factor' => $this->readFanOutFactor,
            'read_fan_out_delay_ms' => $this->readFanOutDelayMs,
            'on_disk_payload' => $this->onDiskPayload,
            'payload' => $this->payloadMemory !== null ? ['memory' => $this->payloadMemory] : null,
        ], static fn($value) => $value !== null);
    }
}
